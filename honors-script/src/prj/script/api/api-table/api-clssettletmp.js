Api.Clssettletmp =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByClsno (grpno, clsno   , noticeOK, noticeFail) { return Api.Clssettletmp.select({OPTION:"selectByClsno" , GRPNO:grpno, CLSNO:clsno , }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */
    insertByArr   (grpno, clsno, arr, noticeOK, noticeFail) { return Api.Clssettletmp.update({ OPTION:"insertByArr", GRPNO:grpno, CLSNO:clsno, ARR:arr, }, noticeOK, noticeFail); },
    deleteByClsno (grpno, clsno, noticeOK, noticeFail) { return Api.Clssettletmp.update({ OPTION:"deleteByClsno" , GRPNO:grpno, CLSNO:clsno, }, noticeOK, noticeFail); },

    /* ========================= */
    /* main */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Clssettletmp.select", noticeOK, noticeFail);
        let rslt = new MClssettletmps(ajax);
        return rslt;
    },
    selectDetail(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Clssettletmp.selectDetail", noticeOK, noticeFail);
        let rslt = new MClssettletmps(ajax);
        return rslt.getModel();
    },
    update(ajaxData={}, noticeOK=GGF.TOAST, noticeFail=GGF.TOAST)
    {
        let ajax = Api.execute(ajaxData, "Api.Clssettletmp.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },
};

