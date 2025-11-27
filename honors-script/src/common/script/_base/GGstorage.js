/* ============================ */
/* 프로젝트 전용 스토리지 함수 모음 */
/* ============================ */
var GGstorage =
{
    setLoginInfoFromFile(callback)
    {
        try
        {
            let deviceKind = GGstorage.getDeviceKind();
            if(deviceKind != GGF.System.DeviceKind.MOBILE)
            {
                callback(null);
                return;
            }

            /* if error */
            let onError = function(e) { console.error(e); callback(false); }

            /* read file */
            let readFile = function(fileEntry)
            {
                fileEntry.file(function(file)
                {
                    let reader = new FileReader();
                    reader.onloadend = function()
                    {
                        try
                        {
                            let result = JSON.parse(this.result);
                            if(result.apikey == undefined)
                            {
                                callback(false);
                                return;
                            }
                            GGstorage.setApikey(result.apikey);
                            GGstorage.setUserid(result.userid);
                            callback(true);
                        }
                        catch(e)
                        {
                            console.error(e);
                            callback(false);
                        }
                    };
                    reader.readAsText(file);
                }, onError);
            }

            /* create file */
            // console.log(cordova.file.externalDataDirectory);
            window.resolveLocalFileSystemURL(cordova.file.dataDirectory, function(dirEntry)
            {
                dirEntry.getFile(
                    "ggstorage",
                    {create: true, exclusive: false},
                    function(fileEntry)
                    {
                        fileEntry.createWriter(function (fileWriter)
                        {
                            fileWriter.onwrite = function() { readFile(fileEntry); };
                            fileWriter.onerror = onError;
                            fileWriter.seek(fileWriter.length);
                            fileWriter.write("");
                        });
                    }, onError);
            }, onError);
        }
        catch(e)
        {
            console.error(e);
            if(callback)
                callback(false);
            return;
        }
    },

    saveLoginInfoToFile(apikey, userid, callback)
    {
        try
        {
            /* 모바일 디바이스가 아닐 경우 */
            let deviceKind = GGstorage.getDeviceKind();
            if(deviceKind != GGF.System.DeviceKind.MOBILE)
            {
                callback(null);
                return;
            }

            /* if error */
            let onError = function(e) { console.error(e); callback(false); }

            /* create file */
            window.resolveLocalFileSystemURL(cordova.file.dataDirectory, function(dirEntry)
            {
                dirEntry.getFile(
                    "ggstorage",
                    {create: true, exclusive: false},
                    function(fileEntry)
                    {
                        fileEntry.createWriter(function(fileWriter)
                        {
                            fileWriter.onwrite = function() { callback(true); };
                            fileWriter.onerror = onError;
                            fileWriter.write(JSON.stringify({apikey: apikey, userid: userid}));
                        });
                    }, onError);
            }, onError);
        }
        catch(e)
        {
            console.error(e);
            if(callback)
                callback(false);
            return;
        }
    },

    /* ============================ */
    /* 각 localStorage 의 값을 저장/불러오기 (tournamentSize는 위에서 따로처리) */
    /* ============================ */
    getData()
    {
        let appMode = GGstorage.getAppmode();
        let data = localStorage.getItem(appMode);
        if(data == null)
            data = {};
        else
            data = JSON.parse(data);
        return data;
    },
    setVal(key, val)
    {
        try
        {
            let appMode = GGstorage.getAppmode();
            let data = GGstorage.getData();
            data[key] = val;
            data["_time"] = new Date(); /* 마지막 수정 시간 */
            localStorage.setItem(appMode, JSON.stringify(data));
        }
        catch(e)
        {
            // console.log(e);
            return false;
        }
        return true;
    },
    getVal(key, defVal=null)
    {
        let data = GGstorage.getData();
        let val = data[key] == undefined ? null : data[key];
        if(val == null && defVal != null)
        {
            val = defVal;
            GGstorage.setVal(key, defVal);
        }
        return val;
    },
    removeItem(key)
    {
        GGstorage.setVal(key, null);
    },

    getAppmode()
    {
        try
        {
            let appmode = new URLSearchParams(window.location.search).get("type");
            if(appmode == null)
                return "cus";
            return appmode;
        }
        catch(e)
        {
            console.error(e);
            return "cus";
        }
    },

    /* ========================= */
    /* 페이지 이동경로 저장 */
    /*  - get : 데이터를 꺼내와서 JSON decoding 하여 리턴 */
    /* ========================= */
    getPageStack()
    {
        return JSON.parse(GGstorage.getVal("pageStack"));
    },
    setPageStack(pageStack)
    {
        return GGstorage.setVal("pageStack", JSON.stringify(pageStack));
    },
    clearPageStack()
    {
        GGstorage.removeItem("pageStack");
    },

    /* ========================= */
    /* 각종 데이터 */
    /* ========================= */
    setAppMode(val)             { return GGstorage.setVal("appmode", val); },
    setRegion(val)              { return GGstorage.setVal("region", val); },
    setLang(val)                { return GGstorage.setVal("lang", val); },
    setUserno(val)              { return GGstorage.setVal("userno", val); },
    setUsername(val)            { return GGstorage.setVal("username", val); },
    setUserid(val)              { return GGstorage.setVal("userId", val); },
    setPushToken(val)           { return GGstorage.setVal("pushToken", val); },
    setApikey(val)              { return GGstorage.setVal("apikey", val); },
    setDeviceKind(val="")       { return GGstorage.setVal("deviceKind", val); },
    setDeviceKindSmall(val="")  { return GGstorage.setVal("deviceKindSmall", val); },

    getRegion()                 { return GGstorage.getVal("region"); },                /*  */
    getLang()                   { return GGstorage.getVal("lang"); },                  /* 언어팩 */
    getUserno()                 { return GGstorage.getVal("userno"); },                /*  */
    getUsername()               { return GGstorage.getVal("username"); },              /* */
    getUserid()                 { return GGstorage.getVal("userId"); },                /* username */
    getPushToken()              { return GGstorage.getVal("pushToken"); },             /* push token */
    getApikey()                 { return GGstorage.getVal("apikey"); },                /* API key */
    getDeviceKind()             { return GGstorage.getVal("deviceKind"); },            /* 접속한 디바이스의 디바이스 종류 */
    getDeviceKindSmall()        { return GGstorage.getVal("deviceKindSmall"); },       /* 접속한 디바이스의 디바이스 종류 */

    removeAppMode()             { return GGstorage.removeItem("appmode"); },
    removeRegion()              { return GGstorage.removeItem("region"); },
    removeLang()                { return GGstorage.removeItem("lang"); },
    removeUserno()              { return GGstorage.removeItem("userno"); },
    removeUsername()            { return GGstorage.removeItem("username"); },
    removeUserid()              { return GGstorage.removeItem("userId"); },
    removePushToken()           { return GGstorage.removeItem("pushToken"); },
    removeApikey()              { return GGstorage.removeItem("apikey"); },
    removeDeviceKind()          { return GGstorage.removeItem("deviceKind"); },
    removeDeviceKindSmall()     { return GGstorage.removeItem("deviceKindSmall"); },

    /* ========================= */
    /* 휴일 리스트 */
    /* ========================= */
    setHolidayUpdated(val=null) { return GGstorage.setVal("holidayUpdated", val); },
    getHolidayUpdated()         { return GGstorage.getVal("holidayUpdated"); },

    setHolidays(val={}){ return GGstorage.setVal("holidays", val); },
    getHolidays()
    {
        /* ----- */
        /* get holidays string */
        /* ----- */
        let holidays = GGstorage.getVal("holidays");
        if(holidays == null)
            GGstorage.refreshHolidays();
        holidays = GGstorage.getVal("holidays");

        /* ----- */
        /* get holidayUpdated */
        /* ----- */
        try
        {
            let holidayUpdated = GGstorage.getHolidayUpdated();
            if(holidayUpdated != null)
            {
                let days = GGdate.getDaysBetweenDates(holidayUpdated, GGdate.getToday());
                if(days >= 30)
                {
                    GGstorage.refreshHolidays();
                    holidays = GGstorage.getVal("holidays");
                }
            }
            else
            {
                GGstorage.setHolidayUpdated(GGdate.getToday());
            }
        }
        catch(e)
        {
            log.error(e);
            GGstorage.setHolidayUpdated(GGdate.getToday());
        }

        /* ----- */
        /* make instance */
        /* ----- */
        let holidaysArr = $.parseJSON(holidays);
        let mRefHolidays = _MCommon.fromArr(holidaysArr, MRefHolidays);
        return mRefHolidays;
    },
    refreshHolidays()
    {
        let mRefHoliday = Api.RefHoliday.selectAll();
        let string = mRefHoliday.getJson();
        GGstorage.setHolidays(string);

        /* set last updated */
        GGstorage.setHolidayUpdated(GGdate.getToday());
    },

    /* ========================= */
    /* 데이터를 활용한 함수 */
    /* ========================= */
    logout()
    {
        GGstorage.removeApikey();
        GGstorage.removeUserid();
        GGstorage.clearPageStack();
        Navigation.moveFrontPage(Navigation.Page.A00UserLogin);
    },

    isLogin()
    {
        let apikey = GGstorage.getApikey();
        if(apikey != null)
        {
            let mUser = Api.User.selectMeForLogin();
            if(mUser.getModels().length == 0)
                return false;
            return true;
        }
        return false;
    },
}