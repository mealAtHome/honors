var GGC = {};
GGC.Common =
{
    /* ============================== */
    /* 기본 필드 */
    /* ============================== */
    default(value, def="")
    {
        if(value == null || value == "")
            return def;
        return value;
    },

    enum      (value, def="") { return GGC.Common.data(value, GGF.Server.FieldType.enum     , def); },
    char      (value, def="") { return GGC.Common.data(value, GGF.Server.FieldType.char     , def); },
    varchar   (value, def="") { return GGC.Common.data(value, GGF.Server.FieldType.varchar  , def); },
    date      (value, def="") { return GGC.Common.data(value, GGF.Server.FieldType.date     , def); },
    datetime  (value, def="") { return GGC.Common.data(value, GGF.Server.FieldType.datetime , def); },
    int       (value, def="") { return GGC.Common.data(value, GGF.Server.FieldType.int      , def); },
    tinyint   (value, def="") { return GGC.Common.data(value, GGF.Server.FieldType.tinyint  , def); },
    double    (value, def="") { return GGC.Common.data(value, GGF.Server.FieldType.double   , def); },
    float     (value, def="") { return GGC.Common.data(value, GGF.Server.FieldType.float    , def); },
    data(value, fieldtype="", def="")
    {
        if(value == null || value == "")
        {
            switch(fieldtype)
            {
                case GGF.Server.FieldType.enum      : def = def; break;
                case GGF.Server.FieldType.char      : def = def; break;
                case GGF.Server.FieldType.varchar   : def = def; break;
                case GGF.Server.FieldType.date      : def = def; break;
                case GGF.Server.FieldType.datetime  : def = def; break;
                case GGF.Server.FieldType.int       : if(def=="") def = 0; else def = def*1; break;
                case GGF.Server.FieldType.tinyint   : if(def=="") def = 0; else def = def*1; break;
                case GGF.Server.FieldType.double    : if(def=="") def = 0; else def = def*1; break;
                case GGF.Server.FieldType.float     : if(def=="") def = 0; else def = def*1; break;
            }
            return def;
        }
        switch(fieldtype)
        {
            case GGF.Server.FieldType.enum      : value = value; break;
            case GGF.Server.FieldType.char      : value = value; break;
            case GGF.Server.FieldType.varchar   : value = value; break;
            case GGF.Server.FieldType.date      : value = value; break;
            case GGF.Server.FieldType.datetime  : value = value; break;
            case GGF.Server.FieldType.int       : value = 1*value; break;
            case GGF.Server.FieldType.tinyint   : value = 1*value; break;
            case GGF.Server.FieldType.double    : value = 1*value; break;
            case GGF.Server.FieldType.float     : value = 1*value; break;
        }
        return value;
    },
    num(value, def=0)
    {
        if(value == null || value == "")
            return def;
        return value*1;
    },

    /* ============================== */
    /* get short from str */
    /* ============================== */
    getShort(str, len=14)
    {
        if(str == null || str == "")
            return "";
        if(str.length > len)
            return str.substring(0, len) + "..";
        return str;
    },

    /* ============================== */
    /* 공통 enum */
    /* ============================== */
    yn(val) { return val == 'y' ? "Ｏ" : "Ｘ"; },

    /* ============================== */
    /* 가격 필드 */
    /* ============================== */
    pricePretty (val=0) { return GGC.Common.numToHangul(val); },
    priceWon    (val=0) { return GGC.Common.comma(val) + "원"; },
    priceHan    (val=0) { return GGC.Common.numToHangul(val); },

    /* 기호가 붙는 옵션가격 */
    optpriceWon(val=0)
    {
        let rslt = GGC.Common.comma(val) + "원";
        if(val >= 0)
            rslt = "+" + rslt;
        else
            rslt = "-" + rslt;
        return rslt;
    },

    /* ============================== */
    /* 천 단위 콤마 */
    /* ============================== */
    comma(val=0)
    {
        let parsed = parseInt(val);
        let rslt = "";

        /* 숫자가 맞는지 확인 */
        /* 0 이면 hypen 을 리턴 */
        /* 1 이상이면 천 단위에 콤마를 붙임 */
        if(isNaN(parsed))
            rslt = val;
        else
            rslt = parsed.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

        return rslt;
    },

    /* ============================== */
    /* 숫자를 만원으로 */
    /* ============================== */
    toManwon(val=0)
    {
        let parsed = parseInt(val);
        let rslt = "";

        /* 숫자가 맞는지 확인 */
        /* 0 이면 hypen 을 리턴 */
        /* 1 이상이면 천 단위에 콤마를 붙임 */
        if(isNaN(parsed))
            rslt = val;
        else
        {
            parsed = Math.round(parsed / 10000 * 10) / 10;
            rslt = parsed.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        return rslt + "만";
    },

    /* ============================== */
    /* 숫자가 0이면 "-" 으로 표시한다. + 천 단위 콤마 */
    /* ============================== */
    numberFormat(val=0)
    {
        let parsed = parseInt(val);
        let rslt = "";

        /* 숫자가 맞는지 확인 */
        /* 0 이면 hypen 을 리턴 */
        /* 1 이상이면 천 단위에 콤마를 붙임 */
        if(isNaN(parsed))
            rslt = val;
        else if(parsed == 0)
            rslt = "-";
        else
            rslt = parsed.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

        return rslt;
    },

    /* ============================== */
    /* 숫자를 한글로 변환 */
    /* ============================== */
    numToHangul(val=0, won=true)
    {
        let num = parseInt(val);
        if(typeof num != 'number')
            return '숫자가 아님';

        let rslt           = "";
        let numStr         = num.toString();
        let lengthForPad   = numStr.length + (4-(numStr.length % 4));   // 만약에 9자리면 3을 더해서 "12"가 됨.
        let numStrWithZero = numStr.padStart(lengthForPad, '0');        // 9자리 인경우 12자리까지 앞에다 0을 채워줌
        let loopCount      = Math.round(numStrWithZero.length / 4);     // 12자리 인경우 3

        for(let i = 1; i <= loopCount; i++)
        {
            let rightMinusPoint = numStrWithZero.length - (i * 4);
            let nowSelected = numStrWithZero.substring(rightMinusPoint, rightMinusPoint+4);

            nowSelected = parseInt(nowSelected);
            if(nowSelected == 0)
                continue;

            nowSelected = this.comma(nowSelected);
            switch(i)
            {
                case 1: rslt = " "+rslt + nowSelected; break;
                case 2: rslt = " "+nowSelected+"만"+rslt; break;
                case 3: rslt = " "+nowSelected+"억"+rslt; break;
                case 4: rslt = " "+nowSelected+"조"+rslt; break;
            }
        }

        if(rslt == "")
            rslt = 0;

        if(won == true)
            rslt += "원";

        rslt = rslt.trim();
        return rslt;
    },

    /* ============================== */
    /* 날짜형식 가공 */
    /* ============================== */
    // datetime(str)
    // {
    //     let rslt = new Array();

    //     try
    //     {
    //         str = str.split(" ");
    //         let time = str[1];
    //         let dateObj = new Date(str[0]);
    //         let year    = dateObj.getFullYear();
    //         let month   = dateObj.getMonth()+1;
    //             month   = month < 10 ? `0${month}` : month;
    //         let date    = dateObj.getDate();
    //             date    = date < 10 ? `0${date}` : date;
    //         let day = dateObj.getDay();
    //         let dddd = "";
    //         switch(day)
    //         {
    //             case 0: dddd = $.i18n("(common)sun"); break;
    //             case 1: dddd = $.i18n("(common)mon"); break;
    //             case 2: dddd = $.i18n("(common)tue"); break;
    //             case 3: dddd = $.i18n("(common)wed"); break;
    //             case 4: dddd = $.i18n("(common)thu"); break;
    //             case 5: dddd = $.i18n("(common)fri"); break;
    //             case 6: dddd = $.i18n("(common)sat"); break;
    //         }

    //         /* 날짜에 오류가 있는지 체크 */
    //         if(isNaN(year) || isNaN(month) || isNaN(date))
    //         {
    //             rslt.push("-");
    //             rslt.push("-");
    //         }
    //         else
    //         {
    //             rslt.push(`${year}-${month}-${date} (${dddd})`);
    //             rslt.push(time);
    //         }
    //     }
    //     catch(e)
    //     {
    //         rslt.push("-");
    //         rslt.push("-");
    //     }
    //     return rslt;
    // },

    /* ============================== */
    /* 요일 */
    /* ============================== */
    getWeekday(str)
    {
        let rslt = "";
        try
        {
            let date = new Date(str);
            let day  = date.getDay();
            switch(day)
            {
                case 0: rslt = $.i18n("(common)sun"); break;
                case 1: rslt = $.i18n("(common)mon"); break;
                case 2: rslt = $.i18n("(common)tue"); break;
                case 3: rslt = $.i18n("(common)wed"); break;
                case 4: rslt = $.i18n("(common)thu"); break;
                case 5: rslt = $.i18n("(common)fri"); break;
                case 6: rslt = $.i18n("(common)sat"); break;
            }
        }
        catch(e)
        {
            console.error(e);
        }
        return rslt;
    },

    /* ============================== */
    /* 지역정보 표시 */
    /* ============================== */
    area(area1Name, area2Name)
    {
        let rslt = "";

        if(area1Name == "-" || area1Name == null)
            rslt = $.i18n('(GGC)area-null');
        else
            rslt = `${area1Name} ${area2Name}`;

        return rslt;
    },

    /* ============================== */
    /* 숫자가 0이면 "-" 으로 표시한다. */
    /* ============================== */
    zeroToHyphen(val=null)
    {
        /* 숫자인지 검사한다. */
        let isNum = Validation.Common.isNumber(val)['code'];

        /* 숫자가 아닐경우 그냥 리턴 */
        if(!isNum)
            return val;
        /* 0이면 하이픈을 리턴 */
        else if(val == 0)
            return "-";
        /* 0이 아니면 그냥 리턴 */
        else
            return val;

    }, /* zeroToHyphen */

    /* ============================== */
    /* 이미지 패스 설정 */
    /* ============================== */
    getImgPath(entity, key, img, origin=false)
    {
        let originPath = "";
        let src        = "";

        /* is origin? */
        if(origin == true)
            originPath = "-origin";

        /* get image path */
        if(img != undefined && img != "" && img != null)
            src = `${ServerInfo.getResourceHost()}/${entity}/${key}/${img}${originPath}.png`;
        else if(entity == "user", entity == "store")
            src = dummySrc;

        return src;
    },

    datePretty(value)
    {
        if(value == null || value == "")
            return "-";

        const today = new Date();
        const timeValue = new Date(value);

        const betweenTime = Math.floor((today.getTime() - timeValue.getTime()) / 1000 / 60);
        if (betweenTime < 1) return '1분 이내';
        if (betweenTime < 60)
            return `${betweenTime}분 전`;

        const betweenTimeHour = Math.floor(betweenTime / 60);
        if (betweenTimeHour < 24)
            return `${betweenTimeHour}시간 전`;

        const betweenTimeDay = Math.floor(betweenTime / 60 / 24);
        if (betweenTimeDay < 31)
            return `${betweenTimeDay}일 전`;

        const betweenTimeMonth = Math.floor(betweenTime / 60 / 24 / 30);
        if (betweenTimeMonth < 12)
            return `${betweenTimeMonth}개월 전`;

        return `${Math.floor(betweenTimeDay / 365)}년 전`;
    },

};

GGC.Attr =
{
    getCommonSpanTag(val="", none="", blue="", yellow="", red="")
    {
        if     (val == none)   return `common-span-tag="none"`;
        else if(val == blue)   return `common-span-tag="blue"`;
        else if(val == yellow) return `common-span-tag="yellow"`;
        else if(val == red)    return `common-span-tag="red"`;
    }
}