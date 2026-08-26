Api.Clssettle =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByPkForMng                            (grpno, clsno   , userno    ,   noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectByPkForMng"                            , GRPNO:grpno, CLSNO:clsno    , USERNO:userno     }, noticeOK, noticeFail); },
    selectByClsno                               (grpno, clsno   ,               noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectByClsno"                               , GRPNO:grpno, CLSNO:clsno    ,                   }, noticeOK, noticeFail); },
    selectByClsnoForMng                         (grpno, clsno   ,               noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectByClsnoForMng"                         , GRPNO:grpno, CLSNO:clsno    ,                   }, noticeOK, noticeFail); },
    selectSettlestatusOpenByUsernoForMng        (grpno, userno  ,               noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectSettlestatusOpenByUsernoForMng"        , GRPNO:grpno, USERNO:userno  ,                   }, noticeOK, noticeFail); },
    selectSettlestatusOpenByGrpnoForMng         (grpno,                         noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectSettlestatusOpenByGrpnoForMng"         , GRPNO:grpno,                                    }, noticeOK, noticeFail); },
    selectSettlestatusDoneByGrpnoForMng         (grpno,                         noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectSettlestatusDoneByGrpnoForMng"         , GRPNO:grpno,                                    }, noticeOK, noticeFail); },
    selectSettlestatusDoneByGrpnoWithPageForMng (grpno, pageno  ,               noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectSettlestatusDoneByGrpnoWithPageForMng" , GRPNO:grpno, PAGENUM:pageno                     }, noticeOK, noticeFail); },
    selectSettlestatusLossByGrpnoForMng         (grpno,                         noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectSettlestatusLossByGrpnoForMng"         , GRPNO:grpno,                                    }, noticeOK, noticeFail); },
    selectYetByUsernoForUsr                     (                               noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectYetByUsernoForUsr"                     ,                                                 }, noticeOK, noticeFail); },
    selectTmpByUsernoForUsr                     (                               noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectTmpByUsernoForUsr"                     ,                                                 }, noticeOK, noticeFail); },
    selectCmpByUsernoForUsr                     (                               noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectCmpByUsernoForUsr"                     ,                                                 }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */
    updateSettlestatusToMembForUsr    (grpno, clsno, userno, noticeOK, noticeFail) { return Api.Clssettle.update({OPTION:"updateSettlestatusToMembForUsr"   , GRPNO:grpno, CLSNO:clsno, USERNO:userno}, noticeOK, noticeFail); },
    updateSettlestatusToDoneForFnc    (grpno, clsno, userno, noticeOK, noticeFail) { return Api.Clssettle.update({OPTION:"updateSettlestatusToDoneForFnc"   , GRPNO:grpno, CLSNO:clsno, USERNO:userno}, noticeOK, noticeFail); },
    updateSettlestatusToLossForFnc    (grpno, clsno, userno, noticeOK, noticeFail) { return Api.Clssettle.update({OPTION:"updateSettlestatusToLossForFnc"   , GRPNO:grpno, CLSNO:clsno, USERNO:userno}, noticeOK, noticeFail); },
    updateSettlestatusToWaitForFnc    (grpno, clsno, userno, noticeOK, noticeFail) { return Api.Clssettle.update({OPTION:"updateSettlestatusToWaitForFnc"   , GRPNO:grpno, CLSNO:clsno, USERNO:userno}, noticeOK, noticeFail); },

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

