Api.Clslineuptmpb =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByGrpno           (grpno, pagenum,     noticeOK, noticeFail) { return Api.Clslineuptmpb.select({OPTION:"selectByGrpno"        , GRPNO:grpno, PAGENUM: pagenum         }, noticeOK, noticeFail); },
    selectByLineupgroup     (grpno, lineupgroup, noticeOK, noticeFail) { return Api.Clslineuptmpb.select({OPTION:"selectByLineupgroup"  , GRPNO:grpno, LINEUPGROUP:lineupgroup, }, noticeOK, noticeFail); },

    /* ========================= */
    /* main */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Clslineuptmpb.select", noticeOK, noticeFail);
        let rslt = new MClslineuptmpbs(ajax);
        return rslt;
    },
    update(ajaxData={}, noticeOK=GGF.TOAST, noticeFail=GGF.TOAST)
    {
        let ajax = Api.execute(ajaxData, "Api.Clslineuptmpb.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },
};
