Api.Grpformnglog =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectGrpfncCapitaltotalByGrpno(grpno, pagenum, noticeOK, noticeFail) { return Api.Grpformnglog.select({OPTION:"selectGrpfncCapitaltotalByGrpno", GRPNO:grpno, PAGENUM:pagenum, }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */

    /* ========================= */
    /* detail */
    /* ========================= */

    /* ========================= */
    /* main function */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Grpformnglog.select", noticeOK, noticeFail);
        let rslt = new MGrpformnglogs(ajax);
        return rslt;
    },
    selectDetail(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Grpformnglog.selectDetail", noticeOK, noticeFail);
        let rslt = new MGrpformnglogs(ajax);
        return rslt.getModel();
    },
    update(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Grpformnglog.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },

}