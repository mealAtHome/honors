Api.Clspurchase =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByClsno (grpno, clsno, noticeOK, noticeFail) { return Api.Clspurchase.select({OPTION:"selectByClsno", GRPNO:grpno, CLSNO:clsno, }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */
    insertByArr(grpno, clsno, arr, noticeOK, noticeFail) { return Api.Clspurchase.update({ OPTION:"insertByArr", GRPNO:grpno, CLSNO:clsno, ARR:arr, }, noticeOK, noticeFail); },

    /* ========================= */
    /* main */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Clspurchase.select", noticeOK, noticeFail);
        let rslt = new MClspurchases(ajax);
        return rslt;
    },
    selectDetail(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Clspurchase.selectDetail", noticeOK, noticeFail);
        let rslt = new MClspurchases(ajax);
        return rslt.getModel();
    },
    update(ajaxData={}, noticeOK=GGF.TOAST, noticeFail=GGF.TOAST)
    {
        let ajax = Api.execute(ajaxData, "Api.Clspurchase.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },
};

