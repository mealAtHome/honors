Api.Scheduleall =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectBySclyear(sclyear, noticeOK, noticeFail) { return Api.Scheduleall.select({OPTION:"selectBySclyear" , SCLYEAR: sclyear }, noticeOK, noticeFail); },

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
        let ajax = Api.execute(ajaxData, "Api.Scheduleall.select", noticeOK, noticeFail);
        let rslt = new MSchedulealls(ajax);
        return rslt;
    },
    selectDetail(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Scheduleall.selectDetail", noticeOK, noticeFail);
        let rslt = new MSchedulealls(ajax);
        return rslt.getModel();
    },
    update(ajaxData={}, noticeOK=GGF.TOAST, noticeFail=GGF.TOAST)
    {
        return Api.execute(ajaxData, "Api.Scheduleall.update", noticeOK, noticeFail);
    },

}