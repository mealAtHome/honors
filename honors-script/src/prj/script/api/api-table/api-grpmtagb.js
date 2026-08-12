Api.Grpmtagb =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByGrpnoTagidx(grpno, tagidx, noticeOK, noticeFail) { return Api.Grpmtagb.select({OPTION:"selectByGrpnoTagidx", GRPNO:grpno, TAGIDX:tagidx, }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */
    bulkSetForMng(grpno, tagidx, usernoArr, noticeOK, noticeFail) { return Api.Grpmtagb.update({ OPTION:"bulkSetForMng", GRPNO:grpno, TAGIDX:tagidx, ARR:usernoArr, }, noticeOK, noticeFail); },

    /* ========================= */
    /* main function */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Grpmtagb.select", noticeOK, noticeFail);
        let rslt = new MGrpmtagbs(ajax);
        return rslt;
    },
    update(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Grpmtagb.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },

}
