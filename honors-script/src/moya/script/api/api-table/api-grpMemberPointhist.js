Api.GrpMemberPointhist =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectLast3mByUserno(grpno, userno, noticeOK, noticeFail) { return Api.GrpMemberPointhist.select({OPTION:"selectLast3mByUserno" , GRPNO: grpno, USERNO: userno }, noticeOK, noticeFail); },

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
        let ajax = Api.execute(ajaxData, "Api.GrpMemberPointhist.select", noticeOK, noticeFail);
        let rslt = new MGrpMemberPointhists(ajax);
        return rslt;
    },
    selectDetail(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.GrpMemberPointhist.selectDetail", noticeOK, noticeFail);
        let rslt = new MGrpMemberPointhists(ajax);
        return rslt.getModel();
    },
    update(ajaxData={}, noticeOK=GGF.TOAST, noticeFail=GGF.TOAST)
    {
        return Api.execute(ajaxData, "Api.GrpMemberPointhist.update", noticeOK, noticeFail);
    },

}