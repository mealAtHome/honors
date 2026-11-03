Api.UserAddr =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByUsernoForUsr(userno, noticeOK, noticeFail) { return Api.UserAddr.select({OPTION:"selectByUsernoForUsr", USERNO:userno,}, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */
    insertForUsr(userno, useraddrtitle, useraddrcode, lat, lng, noticeOK, noticeFail) { return Api.UserAddr.update({ OPTION:"insertForUsr", USERNO:userno, USERADDRTITLE:useraddrtitle, USERADDRCODE:useraddrcode, LAT:lat, LNG:lng, }, noticeOK, noticeFail); },
    updateForUsr(userno, useraddridx, useraddrtitle, useraddrcode, lat, lng, noticeOK, noticeFail) { return Api.UserAddr.update({ OPTION:"updateForUsr", USERNO:userno, USERADDRIDX:useraddridx, USERADDRTITLE:useraddrtitle, USERADDRCODE:useraddrcode, LAT:lat, LNG:lng, }, noticeOK, noticeFail); },
    deleteForUsr(userno, useraddridx, noticeOK, noticeFail) { return Api.UserAddr.update({ OPTION:"deleteForUsr", USERNO:userno, USERADDRIDX:useraddridx, }, noticeOK, noticeFail); },
    updateDefaultForUsr(userno, useraddridx, noticeOK, noticeFail) { return Api.UserAddr.update({ OPTION:"updateDefaultForUsr", USERNO:userno, USERADDRIDX:useraddridx, }, noticeOK, noticeFail); },

    /* ========================= */
    /* main function */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.UserAddr.select", noticeOK, noticeFail);
        let rslt = new MUserAddrs(ajax);
        return rslt;
    },
    update(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.UserAddr.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },

}
