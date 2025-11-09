Api.User =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectMe() { return Api.User.select({"OPTION":"selectMe"}); },
    selectMeForLogin() { return Api.User.select({"OPTION":"selectMeForLogin"}); },
    selectCntById(id, noticeOK, noticeFail) { return Api.User.select({"OPTION":"selectCntById", "ID":id}, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */
    updateBaccnoRefund(baccnoRefund, noticeOK, noticeFail) { return Api.User.update({"OPTION":"updateBaccnoRefund", "BACCNO_REFUND":baccnoRefund }, noticeOK, noticeFail); },

    /* ========================= */
    /* 등록 */
    /* ========================= */
    insert(id, pw, name, birthYear, phone, email, adrcvflg, hascarflg, address, teamname, noticeOK, noticeFail)
    {
        let ajaxData =
        {
            ID          : id,
            PW          : pw,
            NAME        : name,
            BIRTHYEAR   : birthYear,
            PHONE       : phone,
            EMAIL       : email,
            ADRCVFLG    : adrcvflg,
            HASCARFLG   : hascarflg,
            ADDRESS     : address,
            TEAMNAME    : teamname,
        };
        let ajax = Api.execute(ajaxData, "Api.User.insert", noticeOK, noticeFail);
        if(GGvalid.Api.isSucceed(ajax))
        {
            GGstorage.saveLoginInfoToFile(ajax.apikey, ajax.id, function(rslt) { /* console.log("write to file : ", rslt); */ });
            GGstorage.setUserid(ajax.id);
            GGstorage.setApikey(ajax.apikey);
        }
        else
            return false;
        return true;
    },

    /* =============== */
    /* 로그인 */
    /* =============== */
    login(id, pw, usertype, noticeOK, noticeFail)
    {
        let ajaxData =
        {
            "ID"        : id,
            "PW"        : pw,
            "USERTYPE"  : usertype,
            "TOKEN"     : GGstorage.getPushToken(),
            "PLATFORM"  : GGstorage.getDeviceKindSmall(),
        };
        let ajax = Api.execute(ajaxData, "Api.User.login", noticeOK, noticeFail);
        if(GGvalid.Api.isSucceed(ajax))
        {
            GGstorage.saveLoginInfoToFile(ajax.apikey, ajax.id, function(rslt) { /* console.log("write to file : ", rslt); */ });
            GGstorage.setUserid(ajax.id);
            GGstorage.setApikey(ajax.apikey);
            return true;
        }
        return false;
    },

    deleteUserInfo(id, pw, noticeOK, noticeFail)
    {
        let ajax = Api.execute({ID: id, PW: pw, }, "Api.User.deleteUserInfo", noticeOK, noticeFail);
        let rslt = GGvalid.Api.isSucceed(ajax);
        return rslt;
    },

    /* ========================= */
    /* main function */
    /* ========================= */
    select(ajaxData={}, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.User.select", noticeOK, noticeFail);
        let rslt = new MUsers(ajax);
        return rslt;
    },
    update(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.User.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },


}