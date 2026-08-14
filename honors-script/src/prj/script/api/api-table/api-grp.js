Api.Grp =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectManaging     (            noticeOK, noticeFail) { return Api.Grp.select({OPTION:"selectManaging"      ,                                       }, noticeOK, noticeFail); },
    selectByPk         (grpno     , noticeOK, noticeFail) { return Api.Grp.select({OPTION:"selectByPk"          , GRPNO      : grpno,                   }, noticeOK, noticeFail).getModel(); },
    selectByGrpmanager (grpmanager, noticeOK, noticeFail) { return Api.Grp.select({OPTION:"selectByGrpmanager"  , GRPMANAGER : grpmanager,              }, noticeOK, noticeFail); },
    selectActiveForUsr (            noticeOK, noticeFail) { return Api.Grp.select({OPTION:"selectActiveForUsr"  ,                                       }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */
    updateBacknumberlengthForMng(grpno, backnumberlength, noticeOK, noticeFail) { return Api.Grp.update({ OPTION:"updateBacknumberlengthForMng", GRPNO:grpno, BACKNUMBERLENGTH:backnumberlength, }, noticeOK, noticeFail); },

    /* ========================= */
    /* detail */
    /* ========================= */

    /* ========================= */
    /* main function */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Grp.select", noticeOK, noticeFail);
        let rslt = new MGrps(ajax);
        return rslt;
    },
    selectDetail(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Grp.selectDetail", noticeOK, noticeFail);
        let rslt = new MGrps(ajax);
        return rslt.getModel();
    },
    update(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Grp.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },

}