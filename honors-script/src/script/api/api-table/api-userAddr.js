Api.UserAddr =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByUsernoForMng(userno, noticeOK, noticeFail) { return Api.UserAddr.select({OPTION:"selectByUsernoForMng", USERNO:userno,}, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */
    insertForMng(userno, useraddrtitle, useraddrcode, lat, lng, noticeOK, noticeFail) { return Api.UserAddr.update({ OPTION:"insertForMng", USERNO:userno, USERADDRTITLE:useraddrtitle, USERADDRCODE:useraddrcode, LAT:lat, LNG:lng, }, noticeOK, noticeFail); },
    updateForMng(userno, useraddridx, useraddrtitle, useraddrcode, lat, lng, noticeOK, noticeFail) { return Api.UserAddr.update({ OPTION:"updateForMng", USERNO:userno, USERADDRIDX:useraddridx, USERADDRTITLE:useraddrtitle, USERADDRCODE:useraddrcode, LAT:lat, LNG:lng, }, noticeOK, noticeFail); },
    deleteForMng(userno, useraddridx, noticeOK, noticeFail) { return Api.UserAddr.update({ OPTION:"deleteForMng", USERNO:userno, USERADDRIDX:useraddridx, }, noticeOK, noticeFail); },
    updateDefaultForMng(userno, useraddridx, noticeOK, noticeFail) { return Api.UserAddr.update({ OPTION:"updateDefaultForMng", USERNO:userno, USERADDRIDX:useraddridx, }, noticeOK, noticeFail); },

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
