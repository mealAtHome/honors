Api.Clssettlehist =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByClsno(grpno, clsno, noticeOK, noticeFail) { return Api.Clssettlehist.select({OPTION:"selectByClsno", GRPNO:grpno, CLSNO:clsno, }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */

    /* ========================= */
    /* main */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Clssettlehist.select", noticeOK, noticeFail);
        let rslt = new MClssettlehists(ajax);
        return rslt;
    },
    selectDetail(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Clssettlehist.selectDetail", noticeOK, noticeFail);
        let rslt = new MClssettlehists(ajax);
        return rslt.getModel();
    },
    update(ajaxData={}, noticeOK=GGF.TOAST, noticeFail=GGF.TOAST)
    {
        let ajax = Api.execute(ajaxData, "Api.Clssettlehist.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },
};

