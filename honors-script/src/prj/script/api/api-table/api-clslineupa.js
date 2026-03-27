Api.Clslineupa =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByClsno (grpno, clsno, noticeOK, noticeFail) { return Api.Clslineupa.select({OPTION:"selectByClsno", GRPNO:grpno, CLSNO:clsno, }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */
    updateFromPage(grpno, clsno, arr, noticeOK, noticeFail) { return Api.Clslineupb.update({OPTION:"updateFromPage", GRPNO:grpno, CLSNO:clsno, ARR:JSON.stringify(arr), noticeOK, noticeFail); },

    /* ========================= */
    /* main */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Clslineupa.select", noticeOK, noticeFail);
        let rslt = new MClslineupas(ajax);
        return rslt;
    },
    update(ajaxData={}, noticeOK=GGF.TOAST, noticeFail=GGF.TOAST)
    {
        let ajax = Api.execute(ajaxData, "Api.Clslineupa.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },
};
