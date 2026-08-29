Api.GrpIntro =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByPk(grpno, noticeOK, noticeFail) { return Api.GrpIntro.select({OPTION:"selectByPk", GRPNO:grpno,}, noticeOK, noticeFail).getModel(); },

    /* ========================= */
    /* update */
    /* ========================= */
    upsertForMng(grpno, grpintro, grpintrodetail, grprules, noticeOK, noticeFail) { return Api.GrpIntro.update({ OPTION:"upsertForMng", GRPNO:grpno, GRPINTRO:grpintro, GRPINTRODETAIL:grpintrodetail, GRPRULES:grprules, }, noticeOK, noticeFail); },

    /* ========================= */
    /* main function */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.GrpIntro.select", noticeOK, noticeFail);
        let rslt = new MGrpIntros(ajax);
        return rslt;
    },
    update(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.GrpIntro.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },

}
