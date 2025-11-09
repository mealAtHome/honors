Api.Clslineup2 =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByClsno                   (grpno, clsno, noticeOK, noticeFail) { return Api.Clslineup2.select({OPTION:"selectByClsno"                 , GRPNO:grpno, CLSNO:clsno, }, noticeOK, noticeFail); },
    selectByClsnoForSettleForMng    (grpno, clsno, noticeOK, noticeFail) { return Api.Clslineup2.select({OPTION:"selectByClsnoForSettleForMng"  , GRPNO:grpno, CLSNO:clsno, }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */
    updateFromPage          (grpno, clsno, arr,                              noticeOK, noticeFail) { return Api.Clslineup2.update({OPTION:"updateFromPage"           , GRPNO:grpno, CLSNO:clsno, ARR:JSON.stringify(arr),                                           }, noticeOK, noticeFail); },
    updateApplyRegist       (grpno, clsno, teamname, orderno, username, etc, noticeOK, noticeFail) { return Api.Clslineup2.update({OPTION:"updateApplyRegist"        , GRPNO:grpno, CLSNO:clsno, TEAMNAME:teamname, ORDERNO:orderno, USERNAME:username , ETC:etc,   }, noticeOK, noticeFail); },
    updateApplyRegistStead  (grpno, clsno, teamname, orderno, userno  , etc, noticeOK, noticeFail) { return Api.Clslineup2.update({OPTION:"updateApplyRegistStead"   , GRPNO:grpno, CLSNO:clsno, TEAMNAME:teamname, ORDERNO:orderno, USERNO:userno     , ETC:etc,   }, noticeOK, noticeFail); },
    updateApplyCancel       (grpno, clsno, teamname, orderno,                noticeOK, noticeFail) { return Api.Clslineup2.update({OPTION:"updateApplyCancel"        , GRPNO:grpno, CLSNO:clsno, TEAMNAME:teamname, ORDERNO:orderno,                                }, noticeOK, noticeFail); },

    /* ========================= */
    /* main */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Clslineup2.select", noticeOK, noticeFail);
        let rslt = new MClslineup2s(ajax);
        return rslt;
    },
    update(ajaxData={}, noticeOK=GGF.TOAST, noticeFail=GGF.TOAST)
    {
        let ajax = Api.execute(ajaxData, "Api.Clslineup2.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },
};
