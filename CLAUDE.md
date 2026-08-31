# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

`모임은야구` ("Groups Are Baseball") — a Cordova hybrid app for managing amateur baseball meetup groups (member rosters, schedules, dues/settlement, group finances). Backend is plain PHP (no framework, no Composer), frontend is Onsen UI + jQuery, no build/bundle step for JS in local dev.

## Repo layout (one git repo, multiple sub-projects, each its own Docker container)

| Folder | Container | Port | Role |
|---|---|---|---|
| `honors-build` | `honors-build` | 8082 | Serves the Cordova `www/index.html` app shell — **this is the URL you open to run the app locally** (`http://localhost:8082/www/index.html?type=mng`) |
| `honors-app` | `honors-app` | 8085 | Serves the actual page templates (`app/**/*.html`) that `honors-build`'s shell fetches per-navigation |
| `honors-script` | `honors-script` | 8084 | Serves all JS/CSS source files individually (LOCALMODE, see below) |
| `honors-server` | `honors-api` | 8080 (+3307) | PHP backend — all business logic |
| `honors-res` | `honors-res` | 8081 | Static resource host (uploaded images etc.) |
| `honors-docker/db` (seed data only, no live app source mount) | `honors-mysql` | 4809→3306 | Dev DB, **actual engine is MySQL 8.0.33** (production runs **MariaDB 10.5.29** — see Gotchas) |
| `honors-main` | `honors-main` | 8083 | Small static/legal content host (privacy policy etc.), not core app logic |
| `honors-sql` | — | — | Reference/history of schema changes as dated `.sql` files — **not auto-applied**; schema is applied manually (by the user) to dev/prod DBs, these files just document what changed and why |

Bring the stack up from `honors-docker/`: `docker-compose up -d`. There is no test suite and no lint/build command — verification is done by hitting the running app directly (browser + `docker exec honors-mysql mysql ...` + `docker exec honors-api php -l <file>`).

Dev DB access: `docker exec honors-mysql mysql -h127.0.0.1 -P3306 -uroot -p'svwy@pf!' honors -e "..."` (must use `-h127.0.0.1 -P3306`, socket connection fails inside the container). PHP lint: `MSYS_NO_PATHCONV=1 docker exec honors-api php -l /var/www/html/honors-server/<path>` (the `MSYS_NO_PATHCONV=1` is needed in Git Bash on Windows or the leading `/` gets mangled into a Windows path).

Test login: `seo` / `1111` — this account has `adminflg='y'`, so it bypasses all `isGrpmanager` permission checks. You cannot use it to negative-test manager-only authorization.

## Architecture

### LOCALMODE and script loading
`index.html` (in `honors-build/www/`) sets `LOCALMODE = true` and `SCRIPT_MERGE = false`. In this mode every JS/CSS file is fetched individually (not bundled) with a per-boot cache-busting query string (`?v=<timestamp>`), driven entirely by an explicit manifest in `GGbase.js`. **Any new shared JS file (model, api wrapper, etc.) must be added to this manifest or it silently never loads** — there is no auto-discovery.

### Backend: OPTION-dispatch BO pattern
Every table has one `_CommonBO`-descended class (`system/bo-table/XxxBO.php`, or `bo-ref/` for read-only reference tables). Each BO has exactly one `select($options, $option="")` and one `update($options, $option="")`, each containing a `switch($OPTION)` over string constants declared as `const someOptionName = "someOptionName";` on the class. `extract($options)` pulls POST fields into local variables matching their constant names (e.g. `$GRPNO`, `$EXECUTOR`).

- **Auth convention**: any option named `...ForMng` must start with an `isGrpmanager($GRPNO, $EXECUTOR, true)` check. Any option named `...ForInside` is internal-only (meant to be called from another BO's method, never exposed as a public API option) and deliberately skips auth — the caller is responsible for having already checked it.
- **Cross-BO composition**: a BO that needs another table's data/logic calls that BO's `ForInside` method directly (e.g. `GrpIntroBO::upsertForMng` calls `GrpBO->updateGrpintroForInside(...)`), wired via a `readBO()`/`setBO()` pair that does the `GGnavi::getXxxBO()` require and instance lookup.
- **Registry**: `system/common/GGnavi.php` is a flat list of `static function getXxxBO() { require_once ROOT."/src/system/bo-table/XxxBO.php"; }` — every BO must be registered here before anything can `require` it.
- **Field allowlist**: `src/env/post.php` is a hard allowlist of POST field names (`$options["FIELDNAME"] = get("FIELDNAME");`) — any new field a feature needs must be added here explicitly or it is silently dropped before reaching the BO.
- **Entry files**: `src/data/<entity>/selectXxx.php` / `updateXxx.php` are thin wrappers: `include env.php`, require the BO, call `$bo->selectByOption($options)` / `$bo->updateByOption($options)` inside a try/catch that converts `GGexception`/`Error` into an error response. Update entry files additionally wrap in `GGsql::autoCommitFalse()` / `GGsql::commit()`.
- Free-text values that end up in `LIKE`/string SQL must go through `GGsql::realEscapeString()` — most of the codebase interpolates values into SQL raw without escaping (existing pattern for values already constrained by app logic), but anything that's genuinely free user input (search keywords, long text fields) should be escaped explicitly.
- Spatial data: SRID-4326 `POINT` columns store coordinates as **`POINT(lat lng)`** (latitude first) — MySQL 8 enforces this axis order for SRID 4326 and throws if you pass `lng lat`; MariaDB doesn't enforce any order but this codebase writes/reads consistently as lat-first via `ST_X()=lat, ST_Y()=lng`, so it works identically on both engines. Getting this backwards is a real, silent-until-tested bug — verify with `ST_AsText()` after writing.

### Frontend: page/navigation wiring
Every page is a single `<ons-page id="XXXX">` HTML file (in `honors-app/app/<Bnn-folder>/`) with its own inline `<script>` containing a `var PAGECODE = { code, Data, show(), close() }` object. **Onsen re-executes this inline script every time the page is pushed onto the nav stack** — top-level `const`/`let` declarations will throw "already declared" on a second visit to the same page type; always use `var` (or scope inside functions) for page-level declarations.

Adding a new page or a new `Api.Xxx.yyy` endpoint requires touching `Navigation.Prj.js` in up to 6 places: the `Navigation.Page` enum (page id → 4-letter code), the URL switch, the `getData` switch, the `executeShow` switch, the `executeMoveBack` switch, and (for a new API function) `getApiUrlByFuncName`. Missing any one of these fails silently or throws only when that specific path is exercised.

New JS files (models in `model/model-table/` or `model/model-ref/`, API wrappers in `api/api-table/`) must also be added to `GGbase.js`'s script manifest (see LOCALMODE above).

### API call patterns: blocking vs Promise
Two coexisting client-side calling conventions:
- **Blocking (`Api.Xxx.yyy`)** — the original/default pattern, always wrapped by the caller in `Common.showProgress()` + `setTimeout(..., ajaxDelayTime)` + `Common.hideProgress()`. Used for most reads/writes triggered by an explicit button press.
- **Promise-based (`ApiPr.Xxx.yyy`)** — newer (introduced 2026-08, `api-pr.js` + `api/api-pr/` folder), built on `$.ajax.promise(...)`. Returns a jQuery Deferred; call `.then(json => new Model(json))` inside the wrapper and `.done(...)` at the call site. Intended for cases where blocking the UI would hurt UX (e.g. live-search-as-you-type) — do not wrap `ApiPr` calls in `Common.showProgress()`. Reference implementation: `script/api/api-pr/ApiPr-addrcode.js`.

### Recent restructuring (2026-08-26)
`honors-script/src` was flattened: the old `common/` vs `prj/` split (meant to separate shared framework code from project-specific code) was removed because it added more complexity than it saved as the project grew. Everything now lives directly under `src/script/...`, `src/css/...`, `src/res/...`. Project-specific base singletons that still need to be distinguished from generic framework code (`GGF.js`, `GGC.js`) live in their own subfolder under `_base/` (`_base/GGF/GGF.js`, `_base/GGC/GGC.js`) rather than in a separate top-level tree. If you see a stale path reference using `src/common/` or `src/prj/`, it predates this move.

### Legacy framework origin
The `GG*` framework (`GGnavi`, `GGsql`, `GGauth`, the whole BO/DAO layering) was originally built for a food-delivery app and reused for this baseball-meetup app. `GGnavi.php` still registers several delivery-domain BOs/DAOs (`CartDAO`, `OrdermenuDAO`, `DeliverychargeBO`, rider-matching batches, etc.) that are dead code for this project — don't assume something registered there is an active honors feature without checking it's actually referenced from `honors-app`.

## Code review notes (2026-08-30) — for future refactoring

Assessment written after a long session implementing several features across the stack (basecamp/location, group intro, group-card stats, user address management). Kept here so it survives account/session changes and can guide a deliberate refactoring pass later — not urgent, but worth revisiting.

### What's working well — keep doing this
- **OPTION-dispatch + auth-suffix naming** (`ForInside`/`ForMng`/`ForUsr`, see above) — the auth boundary is legible from the method name alone, no need to read the body to know if a call is protected. This held up well across every new feature built this session; keep extending it rather than inventing a new pattern.
- **Per-parent auto-increment idiom** — `(select ifnull(max(col),0)+1 from table where parent=$X)` inlined directly in an INSERT's value list (`grpmtaga.tagidx`, `user_addr.useraddridx`). No sequence table, no app-level lock, works fine inside `GGsql::autoCommitFalse()`/`commit()`. Reuse this for any future per-parent index column.
- **`ApiPr` vs `Api` split** — Promise-based non-blocking calls for live-search/autocomplete vs blocking+progress-spinner calls for explicit form submits. The naming makes the UX intent obvious at the call site; keep the convention rather than blocking everything.

### Worth improving — in rough priority order
1. **SQL injection surface.** Most queries interpolate values as raw strings; `GGsql::realEscapeString()` is applied ad hoc rather than systematically. Highest-risk points are free-text fields exposed to other users (search keywords, long text like `grp_intro.grprules`). Not a one-sitting fix, but **new code should prefer bind/prepared-statement style where practical**, and any raw interpolation of user-supplied text must go through `realEscapeString()` — no exceptions.
2. **Dead legacy code left unmarked in the tree.** Files like the pre-rewrite `UserAddrBO.php`, `B20-UserAddrUpdate.html`, `B21-UserAddrList.html` (delivery-app-era scaffolding, unregistered in `Navigation.Prj.js`) sat in the codebase with no marker distinguishing them from live code — one was accidentally overwritten mid-session before this was noticed. Same issue applies to the delivery-domain BOs still registered in `GGnavi.php` (see "Legacy framework origin" above). **Before writing a new BO/page file, grep for it in `Navigation.Prj.js` and check `git log` on it** — don't assume a file's existence means it's live. Consider moving confirmed-dead files to a `legacy/` folder or prefixing them, so this stops being a trap.
3. **Inconsistent `GGsql` return shapes.** `selectCnt()` returns a bare int, `selectOne()` returns a bare associative row, `select()` wraps results in `[DATA][0][...]`. Mixing these up compiles fine and fails silently or throws deep in unrelated code. No PHP-level fix without a bigger refactor, but worth a one-line comment atop each `GGsql` method reminding of this, or renaming to make the shape explicit (e.g. `selectOneRaw` vs the wrapped form).
4. **Schema changes are unversioned raw SQL, applied by hand.** `honors-sql/*.sql` files are historical record only, not auto-applied — a typo in one (e.g. an ALTER referencing a column name that doesn't exist) fails silently and is only caught by manually running `describe table` afterward, which already happened once this session. A lightweight migration tool (e.g. Flyway) would at least make "was this applied?" a checkable fact instead of tribal memory.
5. **Too many manual touchpoints per new feature.** Adding one page/endpoint requires touching `Navigation.Prj.js` in up to 6 places (enum, URL switch, `getData`, `executeShow`, `executeMoveBack`, `getApiUrlByFuncName`), plus `env/post.php`'s field allowlist, plus `GGbase.js`'s script manifest (all already documented above under "Frontend: page/navigation wiring" and "LOCALMODE"). This is high-friction and easy to partially forget — a feature can half-work (e.g. page loads but a specific action silently no-ops) because one of the 6+ spots was missed. Not fixable without touching the framework itself, but a personal pre-commit checklist for "new page/endpoint" would catch this before it becomes a live bug.
6. **No automated tests.** All verification in this session was manual (Docker + browser click-through + direct DB queries). Fine at current scale, but the OPTION-dispatch BO layer — especially the `ForMng`/`ForUsr` auth-check branches — would benefit most from even thin PHPUnit coverage, since those are exactly the branches where a missed check is a security bug, not just a UX bug, and manual testing won't reliably catch a regression there over time.

### Third-party integration footguns already hit (see also Gotchas below)
- Guessed Tabler icon classes (`ti-map-pin-filled`) and Naver Maps script params (`ncpKeyId` vs `ncpClientId`) both turned out wrong and cost debugging time. **Verify against the real source before using** (e.g. `curl` the actual webfont CSS) rather than guessing from memory/pattern-matching similar APIs — worth doing this reflexively for any new third-party integration point.

## Gotchas learned the hard way

- **Naver Maps script param**: use `ncpClientId=`, not the older `ncpKeyId=` — the latter 401s at `/v3/auth` even with a valid, correctly-registered key. The geocoder submodule (`naver.maps.Service.geocode`) requires `&submodules=geocoder` on the script URL.
- **`<button>` doesn't inherit page font-size** by default in browsers; this codebase fixes it globally with `button { font-size: inherit; }` in `common.scss` — don't re-patch individual buttons for size mismatches, check that global rule first.
- Prod is **MariaDB 10.5.29**, dev is **MySQL 8.0.33** — they are not GIS-feature-compatible (`ST_Distance_Sphere()` doesn't exist before MariaDB 10.9; MariaDB doesn't enforce SRS axis order the way MySQL 8 does). Anything using spatial functions should be sanity-checked against both, or written to avoid the gap entirely (see the lat-first `POINT` convention above).
