--
-- 모임 타입·속성, 일정 외부참가(용병), 게스트 멤버, 가입신청 (2026-11-03)
-- 개발로드맵 1단계 B(모임생성 + 모임 타입) / 용병 / D(가입 흐름)
-- 로컬 dev DB에는 Claude가 반영함. 운영(MariaDB) 반영은 사용자가 직접.
--
-- 모임 타입은 아래 속성의 기본값 묶음(프리셋)이다. 타입을 고른 뒤 속성은 개별로 바꿀 수 있다.
--   team      : 가입 approve, 일정 기본 member (시합별로 용병 모집 가능)
--   club      : 가입 approve, 일정 기본 member
--   mercenary : 가입 none,    일정 기본 public  (용병 모임: 모든 일정이 외부에 열림)
--

-- 모임 타입·속성
alter table grp add column grptype       enum('team','club','mercenary') not null default 'club'     after grpname;
alter table grp add column grpjoinpolicy enum('approve','free','none')   not null default 'approve'  after grptype;
alter table grp add column grpclsopen    enum('member','public')         not null default 'member'   after grpjoinpolicy;

-- 일정 외부참가(용병 모집)
alter table cls add column clsopenflg  enum('y','n') not null default 'n' after clsstatus;
alter table cls add column clsmercmax  int null                           after clsopenflg;  -- 용병 모집 인원 (null = 제한 없음)

-- 게스트 멤버 : 용병으로 참가한 비멤버. 정산·잔여금·기록을 기존 구조로 처리하기 위해 grp_member에 게스트로 등록
alter table grp_member modify column grpmtype enum('mng','mngsub','member','guest') default 'member';

-- 가입신청
create table grp_joinreq
(
      grpno       char(30)   not null
    , userno      char(30)   not null
    , reqmsg      varchar(200) null
    , reqstatus   enum('wait','approved','rejected','canceled') not null default 'wait'
    , procuserno  char(30)   null
    , procdt      datetime   null
    , regdt       datetime   null
    , primary key (grpno, userno)
    , key grp_joinreq_idx1 (userno)
) engine=InnoDB default charset=utf8mb4 collate=utf8mb4_unicode_ci;

-- 데이터 정리 : 일정 주관자(정·부)가 비어 있으면 빈 문자열 대신 null (일정 등록 시 '' 로 저장되던 문제, 2026-11-03 코드 수정)
update cls set clsusernoadm = null where clsusernoadm = '';
update cls set clsusernosub = null where clsusernosub = '';
