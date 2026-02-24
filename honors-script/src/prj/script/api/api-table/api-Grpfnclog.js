Api.Grpfnclog =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectGrpfncCapitaltotalByGrpno(grpno, pagenum, noticeOK, noticeFail) { return Api.Grpfnclog.select({OPTION:"selectGrpfncCapitaltotalByGrpno", GRPNO:grpno, PAGENUM:pagenum, }, noticeOK, noticeFail); },

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
        let ajax = Api.execute(ajaxData, "Api.Grpfnclog.select", noticeOK, noticeFail);
        let rslt = new MGrpfnclogs(ajax);
        return rslt;
    },
    selectDetail(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Grpfnclog.selectDetail", noticeOK, noticeFail);
        let rslt = new MGrpfnclogs(ajax);
        return rslt.getModel();
    },
    update(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Grpfnclog.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },

}