Api.Grpfnca =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByPk(grpno, noticeOK, noticeFail) { return Api.Grpfnca.select({OPTION:"selectByPk", GRPNO:grpno, }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */
    updateCapitaltotalByPk(grpno, grpfncCapitaltotal, comment, noticeOK, noticeFail) { return Api.Grpfnca.update({ OPTION:"updateCapitaltotalByPk", GRPNO:grpno, GRPFNC_CAPITALTOTAL:grpfncCapitaltotal, COMMENT:comment }, noticeOK, noticeFail); },
    recalByPk(grpno, noticeOK, noticeFail) { return Api.Grpfnca.update({ OPTION:"recalByPk", GRPNO:grpno, }, noticeOK, noticeFail); },

    /* ========================= */
    /* detail */
    /* ========================= */

    /* ========================= */
    /* main function */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Grpfnca.select", noticeOK, noticeFail);
        let rslt = new MGrpfncas(ajax);
        return rslt;
    },
    selectDetail(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Grpfnca.selectDetail", noticeOK, noticeFail);
        let rslt = new MGrpfncas(ajax);
        return rslt.getModel();
    },
    update(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Grpfnca.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },

}