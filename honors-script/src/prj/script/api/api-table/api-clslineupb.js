Api.Clslineupb =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByClsno                   (grpno, clsno, noticeOK, noticeFail) { return Api.Clslineupb.select({OPTION:"selectByClsno"                 , GRPNO:grpno, CLSNO:clsno, }, noticeOK, noticeFail); },
    selectByClsnoForSettleForMng    (grpno, clsno, noticeOK, noticeFail) { return Api.Clslineupb.select({OPTION:"selectByClsnoForSettleForMng"  , GRPNO:grpno, CLSNO:clsno, }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */
    updateFromPage          (grpno, clsno, arr,                              noticeOK, noticeFail) { return Api.Clslineupb.update({OPTION:"updateFromPage"           , GRPNO:grpno, CLSNO:clsno, ARR:JSON.stringify(arr),                                           }, noticeOK, noticeFail); },
    updateApplyRegist       (grpno, clsno, lineupidx, orderno, username, etc, noticeOK, noticeFail) { return Api.Clslineupb.update({OPTION:"updateApplyRegist"        , GRPNO:grpno, CLSNO:clsno, LINEUPIDX:lineupidx, ORDERNO:orderno, USERNAME:username , ETC:etc,   }, noticeOK, noticeFail); },
    updateApplyRegistStead  (grpno, clsno, lineupidx, orderno, userno  , etc, noticeOK, noticeFail) { return Api.Clslineupb.update({OPTION:"updateApplyRegistStead"   , GRPNO:grpno, CLSNO:clsno, LINEUPIDX:lineupidx, ORDERNO:orderno, USERNO:userno     , ETC:etc,   }, noticeOK, noticeFail); },
    updateApplyCancel       (grpno, clsno, lineupidx, orderno,                noticeOK, noticeFail) { return Api.Clslineupb.update({OPTION:"updateApplyCancel"        , GRPNO:grpno, CLSNO:clsno, LINEUPIDX:lineupidx, ORDERNO:orderno,                                }, noticeOK, noticeFail); },

    /* ========================= */
    /* main */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Clslineupb.select", noticeOK, noticeFail);
        let rslt = new MClslineupbs(ajax);
        return rslt;
    },
    update(ajaxData={}, noticeOK=GGF.TOAST, noticeFail=GGF.TOAST)
    {
        let ajax = Api.execute(ajaxData, "Api.Clslineupb.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },
};
