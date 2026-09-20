var GGdate =
{
    format(str, format, ifnull="-")
    {
        if(Common.isEmpty(str))
            return ifnull;

        let d = GGdate.fromStr(str);
        if(d == null || isNaN(d))
            return ifnull;

        switch(format)
        {
            case "YYYY-MM-DD"          : return    GGdate.toYYYYMMDD(d);
            case "YYYY-MM-DD(dd)"      : return `${GGdate.toYYYYMMDD(d)}(${GGdate.getDDDD(d)})`;
            case "YYYY-MM-DD HH:II:SS" : return    GGdate.toYYYYMMDDHHIISS(d);
            case "YY.MM.DD(dd)"        : return    GGdate.toYYMMDDddot(d);
            case "YY.MM.DD(dd) HH:II"  : return    GGdate.toYYMMDDdHHIIdot(d);
            case "MM.DD(dd)"           : return    GGdate.toMMDDddot(d);
            case "YYYY.MM.DD HH:II"    : return `${GGdate.getYYYY(d)}.${GGdate.getMM(d)}.${GGdate.getDD(d)} ${GGdate.getHH(d)}:${GGdate.getII(d)}`;
            default:
                return str;
        }
    },

    /* 2024-xx-xx xx:xx:xx */
    /* 0123456789012345678 */
    fromStr(str)
    {
        if(Common.isEmpty(str))
            return null;

        let d = new Date();
        d.setFullYear   (str.substring(0,4));
        d.setMonth      (str.substring(5,7) * 1 - 1); // month is 0-indexed
        d.setDate       (str.substring(8,10));
        d.setHours      (str.substring(11,13));
        d.setMinutes    (str.substring(14,16));
        d.setSeconds    (str.substring(17,19));
        return d;
    },

    /* 2024-xx-xx xx:xx:xx */
    /* 0123456789012345678 */
    fromStrYMD(str)
    {
        if(Common.isEmpty(str))
            return null;

        let d = new Date();
        d.setFullYear   (str.substring(0,4));
        d.setMonth      (str.substring(5,7) * 1 - 1); // month is 0-indexed
        d.setDate       (str.substring(8,10));
        d.setHours      (0);
        d.setMinutes    (0);
        d.setSeconds    (0);
        return d;
    },
    getDdddFromYmd(str) { return GGdate.getDDDD(GGdate.fromStrYMD(str)); },

    /* 2024-xx-xxTxx:xx */
    /* 0123456789012345 */
    fromDatetimeLocal(str)
    {
        if(Common.isEmpty(str))
            return null;

        let d = new Date();
        d.setFullYear   (str.substring(0,4));
        d.setMonth      (str.substring(5,7) * 1 - 1); // month is 0-indexed
        d.setDate       (str.substring(8,10));
        d.setHours      (str.substring(11,13));
        d.setMinutes    (str.substring(14,16));
        return d;
    },

    formatHH(d)   { return d == null ? null : (d.getHours()   + '') .padStart(2,"0"); },
    formatII(d)   { return d == null ? null : (d.getMinutes() + '') .padStart(2,"0"); },
    formatHHII(d) { return d == null ? null : `${GGdate.formatHH(d)}:${GGdate.formatII(d)}`; },

    getToday()
    {
        let d = new Date();
        return GGdate.getYYYYMMDD(d);
    },

    plusDate(date, days) { date.setDate(date.getDate() + days); return date; },

    getYYYYMMDD(d)
    {
        let year   = String(d.getFullYear());
        let month  = String(d.getMonth() + 1).padStart(2, '0');
        let day    = String(d.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    },
    getYMDTHIS(d)
    {
        let year    = String(d.getFullYear());
        let month   = String(d.getMonth() + 1 ).padStart(2, '0');
        let day     = String(d.getDate()      ).padStart(2, '0');
        let hours   = String(d.getHours()     ).padStart(2, '0');
        let minutes = String(d.getMinutes()   ).padStart(2, '0');
        let seconds = String(d.getSeconds()   ).padStart(2, '0');
        return `${year}-${month}-${day}T${hours}:${minutes}:${seconds}`;
    },

    getYYYY             (d) { if(d == null) return ""; return d.getFullYear(); },
    getYY               (d) { if(d == null) return ""; return d.getFullYear().toString().slice(-2); },
    getMM               (d) { if(d == null) return ""; return String(d.getMonth() + 1 ).padStart(2, '0'); },
    getM                (d) { if(d == null) return ""; return String(d.getMonth() + 1 ); },
    getDD               (d) { if(d == null) return ""; return String(d.getDate()      ).padStart(2, '0'); },
    getD                (d) { if(d == null) return ""; return String(d.getDate()      ); },
    getHH               (d) { if(d == null) return ""; return String(d.getHours()     ).padStart(2, '0'); },
    getII               (d) { if(d == null) return ""; return String(d.getMinutes()   ).padStart(2, '0'); },
    getSS               (d) { if(d == null) return ""; return String(d.getSeconds()   ).padStart(2, '0'); },
    getDDDD             (d) { if(d == null) return ""; return ["일","월","화","수","목","금","토"][d.getDay()]; },
    toYMDDHI            (d) { if(d == null) return ""; return `${GGdate.getYYYY(d)}-${GGdate.getMM(d)}-${GGdate.getDD(d)} (${GGdate.getDDDD(d)}) ${GGdate.getHH(d)}:${GGdate.getII(d)}`; },
    toMMDDddot          (d) { if(d == null) return ""; return `${GGdate.getMM(d)}.${GGdate.getDD(d)}(${GGdate.getDDDD(d)})`; },
    toYYMMDDddot        (d) { if(d == null) return ""; return `${GGdate.getYY(d)}.${GGdate.getMM(d)}.${GGdate.getDD(d)}(${GGdate.getDDDD(d)})`; },
    toYYMMDDdHHIIdot    (d) { if(d == null) return ""; return `${GGdate.getYY(d)}.${GGdate.getMM(d)}.${GGdate.getDD(d)}(${GGdate.getDDDD(d)}) ${GGdate.getHH(d)}:${GGdate.getII(d)}`; },
    toYYYYMMDD          (d) { if(d == null) return ""; return `${GGdate.getYYYY(d)}-${GGdate.getMM(d)}-${GGdate.getDD(d)}`; },
    toYYYYMMDDHHIISS    (d) { if(d == null) return ""; return `${GGdate.getYYYY(d)}-${GGdate.getMM(d)}-${GGdate.getDD(d)} ${GGdate.getHH(d)}:${GGdate.getII(d)}:${GGdate.getSS(d)}`; },
    toMDdddd            (d) { if(d == null) return ""; return `${GGdate.getM(d)}/${GGdate.getD(d)}(${GGdate.getDDDD(d)})`; },

    strToYYMMDDdHHIIdot (d) { return GGdate.toYYMMDDdHHIIdot(GGdate.fromStr(d)); },

    isHolidayToday()
    {
        let date = new Date();
        let day  = date.getDay();

        /* sat || sun */
        if(day == 0 || day == 6)
            return true;

        /* holidays */
        let mRefHolidays = GGstorage.getHolidays();
        return mRefHolidays.isHoliday(date);
    },

    formatTimeToHHII(str)
    {
        let now = new Date();
        let strDate = new Date(`${now.getFullYear()}-${now.getMonth()+1}-${now.getDate()} ${str}`);
        let hh = ''+strDate.getHours();
        let mm = ''+strDate.getMinutes();
        if(hh.length < 2) hh = '0'+hh;
        if(mm.length < 2) mm = '0'+mm;
        return `${hh}:${mm}`;
    },

    // /**
    //  * period format
    //  * @param {*} startdt
    //  * @param {*} closedt
    //  */
    // period(startdt, closedt)
    // {
    //     if(Common.isEmpty(startdt) || Common.isEmpty(closedt))
    //         return "-";

    //     /* to date class */
    //     startdt = GGdate.fromStr(startdt);
    //     closedt = GGdate.fromStr(closedt);

    //     /* is same date startdt, closedt? */
    //     let skipDate = false;
    //     if(
    //         startdt.getFullYear() === closedt.getFullYear() &&
    //         startdt.getMonth()    === closedt.getMonth() &&
    //         startdt.getDate()     === closedt.getDate()
    //     )
    //     {
    //         skipDate = true;
    //     }

    //     /* is 00 minutes both date? */
    //     let skipMinute = false;
    //     if(
    //         startdt.getMinutes() === 0 &&
    //         closedt.getMinutes() === 0
    //     )
    //     {
    //         skipMinute = true;
    //     }

    //     /* format */
    //     let rslt = GGdate.toYYMMDDddot(startdt);
    //     if      ( skipMinute) rslt += ` ${GGdate.getHH(startdt)}`;
    //     else if (!skipMinute) rslt += ` ${GGdate.getHH(startdt)}:${GGdate.getII(startdt)}`;

    //     if      ( skipDate &&  skipMinute) rslt += `-${GGdate.getHH(closedt)}시`;
    //     else if ( skipDate && !skipMinute) rslt += `-${GGdate.getHH(closedt)}:${GGdate.getII(closedt)}`;
    //     else if (!skipDate &&  skipMinute) rslt += ` ~ ${GGdate.toYYMMDDddot(closedt)} ${GGdate.getHH(closedt)}시`;
    //     else if (!skipDate && !skipMinute) rslt += ` ~ ${GGdate.toYYMMDDdHHIIdot(closedt)}`;

    //     /* return */
    //     return rslt;
    // },

    /**
     * 일정(cls) 시작~종료 기간을 표시용 문자열로 변환
     *
     * 예) 9월 12일 (토) 10-13시 (3시간)               - 시작/종료가 같은 날
     * 예) 9월 12일 (토) 23-25시 (2시간)               - 자정을 넘기지만 24시간 이내라, 시작일 기준으로 24시를 넘겨 표기
     * 예) 9월 12일 (토) 10시~ (3일간)                 - 24시간을 초과하면 종료일시 대신 기간(일수)으로 표기
     * 예) 2027년 1월 1일 (토) 10-13시 (3시간)         - 올해가 아니면 연도를 표기 (미래/과거 모두)
     *
     * @param {*} startdt
     * @param {*} closedt
     */
    period(startdt, closedt)
    {
        if(Common.isEmpty(startdt) || Common.isEmpty(closedt))
            return "-";

        /* to date class */
        let start = GGdate.fromStr(startdt);
        let close = GGdate.fromStr(closedt);
        if(start == null || close == null || isNaN(start) || isNaN(close))
            return "-";

        /* 연도 (올해가 아닐 때만 표기) */
        let yearPrefix = start.getFullYear() !== new Date().getFullYear() ? `${start.getFullYear()}년 ` : "";

        /* 날짜 + 요일 */
        let dateStr = `${yearPrefix}${start.getMonth()+1}월 ${start.getDate()}일(${GGdate.getDDDD(start)})`;

        /* 소요시간 */
        let diffMs = close - start;
        let diffHours = diffMs / (60*60*1000);

        /* 24시간 초과 : 종료일시 대신 기간(일수)으로 표기 */
        if(diffHours > 24)
        {
            let dayCount = Math.ceil(diffHours / 24);
            let startTimeStr = start.getMinutes() === 0 ? `${start.getHours()}시` : `${start.getHours()}:${GGdate.getII(start)}`;
            return `${dateStr} ${startTimeStr}~ (${dayCount}일간)`;
        }

        /* 24시간 이내 : 시작일 기준으로 자정을 넘긴 시각까지 이어서 표기 (예 : 23-25시) */
        let startDateOnly = new Date(start.getFullYear(), start.getMonth(), start.getDate());
        let closeDateOnly = new Date(close.getFullYear(), close.getMonth(), close.getDate());
        let dayDiff = GGdate.getDaysBetweenDates(startDateOnly, closeDateOnly);
        let endHour = close.getHours() + (dayDiff * 24);

        let timeStr = "";
        if(start.getMinutes() === 0 && close.getMinutes() === 0)
            timeStr = `${start.getHours()}-${endHour}시`;
        else
            timeStr = `${start.getHours()}:${GGdate.getII(start)}-${String(endHour).padStart(2,'0')}:${GGdate.getII(close)}`;

        /* 소요시간 표기 */
        let durationStr = "";
        if(diffMs % (60*60*1000) === 0)
        {
            durationStr = `(${diffHours}시간)`;
        }
        else
        {
            let durationH = Math.floor(diffHours);
            let durationM = Math.round((diffMs % (60*60*1000)) / (60*1000));
            durationStr = durationH > 0 ? `(${durationH}시간 ${durationM}분)` : `(${durationM}분)`;
        }

        return `${dateStr} ${timeStr} ${durationStr}`;
    },

    /* e.g. getDaysBetweenDates( 22-Jul-2011, 29-jul-2011) => 7. */
    getDaysBetweenDates(d0, d1)
    {
        let msPerDay = 8.64e7;

        // Copy dates so don't mess them up
        if(d0 == null) d0 = new Date();
        if(d1 == null) d1 = new Date();

        let x0 = new Date(d0);
        let x1 = new Date(d1);

        // Set to noon - avoid DST errors
        x0.setHours(12,0,0);
        x1.setHours(12,0,0);

        // Round to remove daylight saving errors
        return Math.round( (x1 - x0) / msPerDay );
    },

    /* ========================= */
    /* e.g. getSecondsBetweenDates( 22-Jul-2011, 29-jul-2011) => 7. */
    /* ========================= */
    isInSecondsFromNow(dateStr, seconds) { return GGdate.getSecondsBetweenDates(GGdate.fromStr(dateStr), new Date()) <= seconds; },
    isIn5MinFromNow(dateStr) { return GGdate.isInSecondsFromNow(dateStr, 5 * 60); },
    isIn1DayFromNow(dateStr) { return GGdate.isInSecondsFromNow(dateStr, 24 * 60 * 60); },
    getSecondsBetweenDates(d0, d1)
    {
        let msPerSecond = 1e3;

        // Copy dates so don't mess them up
        d0 = d0 == null ? new Date() : new Date(d0);
        d1 = d1 == null ? new Date() : new Date(d1);

        // Round to remove daylight saving errors
        return Math.round( (d1 - d0) / msPerSecond );
    },

    /* param : div id to "YYYY-MM-DD HH:II" str */
    fromDivYearToMin(el)
    {
        let yyyymmdd = $(`${el} > input[type=date]`).val();
        let hh = $(`${el} > input[type=number]:nth-child(2)`).val();
        let ii = $(`${el} > input[type=number]:nth-child(3)`).val();
        if(Common.isEmpty(yyyymmdd) || Common.isEmpty(hh) || Common.isEmpty(ii))
            return null;

        hh = hh.padStart(2,"0"); // 0 ~ 9  => 00 ~ 09
        ii = ii.padStart(2,"0"); // 0 ~ 9  => 00 ~ 09

        let dateStr = `${yyyymmdd} ${hh}:${ii}:00`;
        let date = new Date(dateStr);
        if(isNaN(date))
            return null;

        return dateStr;
    },

    /* param : date obj to div's input  */
    toDivYearToMin(el, date=new Date())
    {
        if(date == null || isNaN(date))
            return;

        let yyyymmdd = GGdate.getYYYYMMDD(date);
        let hh = GGdate.getHH(date).padStart(2,"0");
        let ii = GGdate.getII(date).padStart(2,"0");

        $(`${el} > input[type=date]`).val(yyyymmdd);
        $(`${el} > input[type=number]:nth-child(2)`).val(hh);
        $(`${el} > input[type=number]:nth-child(3)`).val(ii);
    },

    /**
     * Determine whether the target date is within, passed, or upcoming the from-to date range.
     * @param {*} tg target
     * @param {*} fr from
     * @param {*} to to
     * @returns
     */
    getPointOfDate(tg, fr, to)
    {
        if (tg instanceof Date == false) { if (!isNaN(Date.parse(tg))) tg = new Date(tg); else throw new Error("Invalid target date"); }
        if (fr instanceof Date == false) { if (!isNaN(Date.parse(fr))) fr = new Date(fr); else throw new Error("Invalid from date"); }
        if (to instanceof Date == false) { if (!isNaN(Date.parse(to))) to = new Date(to); else throw new Error("Invalid to date"); }

        /* get date only */
        tg = new Date(tg.getFullYear(), tg.getMonth(), tg.getDate()).getTime();
        fr = new Date(fr.getFullYear(), fr.getMonth(), fr.getDate()).getTime();
        to = new Date(to.getFullYear(), to.getMonth(), to.getDate()).getTime();

        if(tg >= fr && tg <= to)
            return GGF.GGdate.PointOfDate.WITHIN;
        if(tg > to)
            return GGF.GGdate.PointOfDate.PASSED;
        if(tg < fr)
            return GGF.GGdate.PointOfDate.UPCOMING;
    },
    inWithinPeriod(fr, to) { return GGdate.getPointOfDate(new Date(), fr, to) === GGF.GGdate.PointOfDate.WITHIN; },

    /**
     * 날짜가 다가오기 전을 위한 텍스트를 반환합니다.
     *
     * @param {Date|string|number} standDate
     * @param {Date|string|number} startDate
     * @returns {string}
     */
    getTextForUpcoming(standDate, startDate)
    {
        standDate = new Date(standDate);
        startDate = new Date(startDate);

        /* 시간 제거 (날짜 기준 비교) */
        const today = new Date(standDate.getFullYear(), standDate.getMonth(), standDate.getDate());
        const start = new Date(startDate.getFullYear(), startDate.getMonth(), startDate.getDate());

        const DAY = 24 * 60 * 60 * 1000;
        const diffDays = Math.floor((start - today) / DAY);
        if (diffDays === 0) return "오늘";
        if (diffDays === 1) return "내일";
        if (diffDays === 2) return "모레";

        /* ---------- 이번주 / 다음주 / 다다음주 ---------- */
        const getWeekStart = (date) =>
        {
            const d = new Date(date);
            const day = d.getDay(); // 일요일=0
            const diff = day === 0 ? -6 : 1 - day; // 월요일 시작
            d.setDate(d.getDate() + diff);
            d.setHours(0,0,0,0);
            return d;
        };

        const thisWeekEnd = new Date(getWeekStart(today));
        thisWeekEnd.setDate(thisWeekEnd.getDate() + 6);

        const nextWeekEnd = new Date(thisWeekEnd);
        nextWeekEnd.setDate(nextWeekEnd.getDate() + 7);

        const nextNextWeekEnd = new Date(nextWeekEnd);
        nextNextWeekEnd.setDate(nextNextWeekEnd.getDate() + 7);

        const weekday = ["일", "월", "화", "수", "목", "금", "토"];
        if (start <= thisWeekEnd) return `이번주 ${weekday[start.getDay()]}요일`;
        if (start <= nextWeekEnd) return `다음주 ${weekday[start.getDay()]}요일`;
        if (start <= nextNextWeekEnd) return `다다음주 ${weekday[start.getDay()]}요일`;

        /* ---------- 1개월 미만 ---------- */

        if (diffDays < 30)
            return `${diffDays}일 남음`;

        /* ---------- 1개월 이상 ---------- */

        let months =
            (start.getFullYear() - today.getFullYear()) * 12 +
            (start.getMonth() - today.getMonth());

        let monthAnchor = new Date(today);
        monthAnchor.setMonth(monthAnchor.getMonth() + months);

        if (monthAnchor > start) {
            months--;
            monthAnchor = new Date(today);
            monthAnchor.setMonth(monthAnchor.getMonth() + months);
        }

        const remainDays = Math.floor((start - monthAnchor) / DAY);
        return `${months}개월 뒤, ${remainDays}일 남음`;
    },

    getAgeByYear(birthYear)
    {
        if(Common.isEmpty(birthYear))
            return "";
        let nowYear = new Date().getFullYear();
        return nowYear - birthYear + 1;
    }
}