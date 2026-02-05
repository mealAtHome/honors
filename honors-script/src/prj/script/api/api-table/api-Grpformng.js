Api.Grpformng =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByPk(grpno, noticeOK, noticeFail) { return Api.Grpformng.select({OPTION:"selectByPk", GRPNO:grpno, }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */
    updateCapitaltotalByPk(grpno, grpfncCapitaltotal, comment, noticeOK, noticeFail) { return Api.Grpformng.update({ OPTION:"updateCapitaltotalByPk", GRPNO:grpno, GRPFNC_CAPITALTOTAL:grpfncCapitaltotal, COMMENT:comment }, noticeOK, noticeFail); },


    /* ========================= */
    /* detail */
    /* ========================= */

    /* ========================= */
    /* main function */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Grpformng.select", noticeOK, noticeFail);
        let rslt = new MGrpformngs(ajax);
        return rslt;
    },
    selectDetail(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Grpformng.selectDetail", noticeOK, noticeFail);
        let rslt = new MGrpformngs(ajax);
        return rslt.getModel();
    },
    update(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Grpformng.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },

}