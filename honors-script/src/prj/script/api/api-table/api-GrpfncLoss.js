Api.GrpfncLoss =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByPk(grpno, lossidx, noticeOK, noticeFail) { return Api.GrpfncLoss.select({OPTION:"selectByPk", GRPNO:grpno, LOSSIDX:lossidx }, noticeOK, noticeFail); },
    selectByGrpnoPagenum(grpno, pagenum, noticeOK, noticeFail) { return Api.GrpfncLoss.select({OPTION:"selectByGrpnoPagenum", GRPNO:grpno, PAGENUM:pagenum }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */
    insertFromPage(grpno, lossitem, losscost, losscomment, noticeOK, noticeFail)
    {
        let ajaxData =
        {
            OPTION: "insertFromPage",
            GRPNO: grpno,
            LOSSITEM: lossitem,
            LOSSCOST: losscost,
            LOSSCOMMENT: losscomment,
        };
        return Api.GrpfncLoss.update(ajaxData, noticeOK, noticeFail);
    },


    /* ========================= */
    /* detail */
    /* ========================= */

    /* ========================= */
    /* main function */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.GrpfncLoss.select", noticeOK, noticeFail);
        let rslt = new MGrpfncLosses(ajax);
        return rslt;
    },
    update(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.GrpfncLoss.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },

}