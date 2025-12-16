Api.Schedulebyweek =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByPkInsertIfNotExists(sclyear, sclmonth, sclweek, noticeOK, noticeFail) { return Api.Schedulebyweek.select({OPTION:"selectByPkInsertIfNotExists", SCLYEAR: sclyear, SCLMONTH: sclmonth, SCLWEEK: sclweek }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */

    /* ========================= */
    /* detail */
    /* ========================= */

    /* ========================= */
    /* main function */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Schedulebyweek.select", noticeOK, noticeFail);
        let rslt = new MSchedulebyweeks(ajax);
        return rslt;
    },
    selectDetail(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Schedulebyweek.selectDetail", noticeOK, noticeFail);
        let rslt = new MSchedulebyweeks(ajax);
        return rslt.getModel();
    },
    update(ajaxData={}, noticeOK=GGF.TOAST, noticeFail=GGF.TOAST)
    {
        return Api.execute(ajaxData, "Api.Schedulebyweek.update", noticeOK, noticeFail);
    },

}