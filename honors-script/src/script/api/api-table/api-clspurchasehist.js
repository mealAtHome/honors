Api.Clspurchasehist =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByClsno(grpno, clsno, noticeOK, noticeFail) { return Api.Clspurchasehist.select({OPTION:"selectByClsno", GRPNO:grpno, CLSNO:clsno, }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */

    /* ========================= */
    /* main */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Clspurchasehist.select", noticeOK, noticeFail);
        let rslt = new MClspurchasehists(ajax);
        return rslt;
    },
    selectDetail(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Clspurchasehist.selectDetail", noticeOK, noticeFail);
        let rslt = new MClspurchasehists(ajax);
        return rslt.getModel();
    },
    update(ajaxData={}, noticeOK=GGF.TOAST, noticeFail=GGF.TOAST)
    {
        let ajax = Api.execute(ajaxData, "Api.Clspurchasehist.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },
};

