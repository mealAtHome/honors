var GGdate =
{
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

    getYYYY             (d) { return d.getFullYear(); },
    getYY               (d) { return d.getFullYear().toString().slice(-2); },
    getMM               (d) { return String(d.getMonth() + 1 ).padStart(2, '0'); },
    getDD               (d) { return String(d.getDate()      ).padStart(2, '0'); },
    getHH               (d) { return String(d.getHours()     ).padStart(2, '0'); },
    getII               (d) { return String(d.getMinutes()   ).padStart(2, '0'); },
    getSS               (d) { return String(d.getSeconds()   ).padStart(2, '0'); },
    getDDDD             (d) { return ["일","월","화","수","목","금","토"][d.getDay()]; },
    toYMDDHI            (d) { return `${GGdate.getYYYY(d)}-${GGdate.getMM(d)}-${GGdate.getDD(d)} (${GGdate.getDDDD(d)}) ${GGdate.getHH(d)}:${GGdate.getII(d)}`; },
    toMMDDddot          (d) { return `${GGdate.getMM(d)}.${GGdate.getDD(d)}(${GGdate.getDDDD(d)})`; },
    toYYMMDDddot        (d) { return `${GGdate.getYY(d)}.${GGdate.getMM(d)}.${GGdate.getDD(d)}(${GGdate.getDDDD(d)})`; },
    toYYMMDDdHHIIdot    (d) { return `${GGdate.getYY(d)}.${GGdate.getMM(d)}.${GGdate.getDD(d)}(${GGdate.getDDDD(d)}) ${GGdate.getHH(d)}:${GGdate.getII(d)}`; },
    toYYYYMMDD          (d) { return `${GGdate.getYYYY(d)}-${GGdate.getMM(d)}-${GGdate.getDD(d)}`; },
    toYYYYMMDDHHIISS    (d) { return `${GGdate.getYYYY(d)}-${GGdate.getMM(d)}-${GGdate.getDD(d)} ${GGdate.getHH(d)}:${GGdate.getII(d)}:${GGdate.getSS(d)}`; },

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

    /**
     * 두 개의 날짜를 받아서 기간을 표시합니다.
     * 두 날짜는 스트링 형식
     * @param {*} startdt
     * @param {*} closedt
     */
    period(startdt, closedt)
    {
        if(Common.isEmpty(startdt) || Common.isEmpty(closedt))
            return "-";

        /* to date class */
        startdt = GGdate.fromStr(startdt);
        closedt = GGdate.fromStr(closedt);

        /* is same date startdt, closedt? */
        let skipDate = false;
        if(
            startdt.getFullYear() === closedt.getFullYear() &&
            startdt.getMonth()    === closedt.getMonth() &&
            startdt.getDate()     === closedt.getDate()
        )
        {
            skipDate = true;
        }

        /* is 00 minutes both date? */
        let skipMinute = false;
        if(
            startdt.getMinutes() === 0 &&
            closedt.getMinutes() === 0
        )
        {
            skipMinute = true;
        }

        /* format */
        let rslt = GGdate.toYYMMDDddot(startdt);
        if      ( skipMinute) rslt += ` ${GGdate.getHH(startdt)}`;
        else if (!skipMinute) rslt += ` ${GGdate.getHH(startdt)}:${GGdate.getII(startdt)}`;

        if      ( skipDate &&  skipMinute) rslt += `-${GGdate.getHH(closedt)}시`;
        else if ( skipDate && !skipMinute) rslt += `-${GGdate.getHH(closedt)}:${GGdate.getII(closedt)}`;
        else if (!skipDate &&  skipMinute) rslt += ` ~ ${GGdate.toYYMMDDddot(closedt)} ${GGdate.getHH(closedt)}시`;
        else if (!skipDate && !skipMinute) rslt += ` ~ ${GGdate.toYYMMDDdHHIIdot(closedt)}`;

        /* return */
        return rslt;
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

    /* e.g. getSecondsBetweenDates( 22-Jul-2011, 29-jul-2011) => 7. */
    getSecondsBetweenDates(d0, d1)
    {
        let msPerSecond = 1e3;

        // Copy dates so don't mess them up
        if(d0 == null) d0 = new Date();
        if(d1 == null) d1 = new Date();

        let x0 = new Date(d0);
        let x1 = new Date(d1);

        // Round to remove daylight saving errors
        return Math.round( (x1 - x0) / msPerSecond );
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
}