Api.Clssettle =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByClsno                               (grpno, clsno   , noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectByClsno"                               , GRPNO:grpno, CLSNO:clsno   , }, noticeOK, noticeFail); },
    selectNotDepositedByUsernoForMng            (grpno, userno  , noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectNotDepositedByUsernoForMng"            , GRPNO:grpno, USERNO:userno , }, noticeOK, noticeFail); },
    selectNotDepositedAllByGrpnoForMng          (grpno          , noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectNotDepositedAllByGrpnoForMng"          , GRPNO:grpno,                 }, noticeOK, noticeFail); },
    selectMemberdepositflgYesByGrpnoForMng      (grpno          , noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectMemberdepositflgYesByGrpnoForMng"      , GRPNO:grpno,                 }, noticeOK, noticeFail); },
    selectNotDepositedByGrpnoForMng             (grpno          , noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectNotDepositedByGrpnoForMng"             , GRPNO:grpno,                 }, noticeOK, noticeFail); },
    selectNotDepositedAllByGrpnoClsnoForMng     (grpno, clsno   , noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectNotDepositedAllByGrpnoClsnoForMng"     , GRPNO:grpno, CLSNO:clsno   , }, noticeOK, noticeFail); },
    selectMemberdepositflgYesByGrpnoClsnoForMng (grpno, clsno   , noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectMemberdepositflgYesByGrpnoClsnoForMng" , GRPNO:grpno, CLSNO:clsno   , }, noticeOK, noticeFail); },
    selectNotDepositedByGrpnoClsnoForMng        (grpno, clsno   , noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectNotDepositedByGrpnoClsnoForMng"        , GRPNO:grpno, CLSNO:clsno   , }, noticeOK, noticeFail); },
    selectYetByUsernoForUsr                     (                 noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectYetByUsernoForUsr"                     ,                              }, noticeOK, noticeFail); },
    selectTmpByUsernoForUsr                     (                 noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectTmpByUsernoForUsr"                     ,                              }, noticeOK, noticeFail); },
    selectCmpByUsernoForUsr                     (                 noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectCmpByUsernoForUsr"                     ,                              }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */
    updateMemberdepositflgYesForUsr     (grpno, clsno, userno, noticeOK, noticeFail) { return Api.Clssettle.update({OPTION:"updateMemberdepositflgYesForUsr"  , GRPNO:grpno, CLSNO:clsno, USERNO:userno}, noticeOK, noticeFail); },
    updateManagerdepositflgYesForMng    (grpno, clsno, userno, noticeOK, noticeFail) { return Api.Clssettle.update({OPTION:"updateManagerdepositflgYesForMng" , GRPNO:grpno, CLSNO:clsno, USERNO:userno}, noticeOK, noticeFail); },

    /* ========================= */
    /* main */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Clssettle.select", noticeOK, noticeFail);
        let rslt = new MClssettles(ajax);
        return rslt;
    },
    selectDetail(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Clssettle.selectDetail", noticeOK, noticeFail);
        let rslt = new MClssettles(ajax);
        return rslt.getModel();
    },
    update(ajaxData={}, noticeOK=GGF.TOAST, noticeFail=GGF.TOAST)
    {
        let ajax = Api.execute(ajaxData, "Api.Clssettle.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },
};

