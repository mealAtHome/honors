Api.Clslineuptmpc =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByLineupgroup (grpno, lineupgroup, noticeOK, noticeFail) { return Api.Clslineuptmpc.select({OPTION:"selectByLineupgroup", GRPNO:grpno, LINEUPGROUP:lineupgroup, }, noticeOK, noticeFail); },

    /* ========================= */
    /* main */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Clslineuptmpc.select", noticeOK, noticeFail);
        let rslt = new MClslineuptmpcs(ajax);
        return rslt;
    },
};
