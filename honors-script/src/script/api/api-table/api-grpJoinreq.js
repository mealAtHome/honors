Api.GrpJoinreq =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectMineByGrpnoForUsr(grpno, noticeOK, noticeFail) { return Api.GrpJoinreq.select({OPTION:"selectMineByGrpnoForUsr", GRPNO:grpno,}, noticeOK, noticeFail).getModel(); },
    selectWaitByGrpnoForMng(grpno, noticeOK, noticeFail) { return Api.GrpJoinreq.select({OPTION:"selectWaitByGrpnoForMng", GRPNO:grpno,}, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */
    requestForUsr(grpno, reqmsg, noticeOK, noticeFail) { return Api.GrpJoinreq.update({ OPTION:"requestForUsr", GRPNO:grpno, REQMSG:reqmsg, }, noticeOK, noticeFail); },
    cancelForUsr (grpno,         noticeOK, noticeFail) { return Api.GrpJoinreq.update({ OPTION:"cancelForUsr",  GRPNO:grpno, }, noticeOK, noticeFail); },
    approveForMng(grpno, userno, noticeOK, noticeFail) { return Api.GrpJoinreq.update({ OPTION:"approveForMng", GRPNO:grpno, USERNO:userno, }, noticeOK, noticeFail); },
    rejectForMng (grpno, userno, noticeOK, noticeFail) { return Api.GrpJoinreq.update({ OPTION:"rejectForMng",  GRPNO:grpno, USERNO:userno, }, noticeOK, noticeFail); },

    /* ========================= */
    /* main function */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.GrpJoinreq.select", noticeOK, noticeFail);
        let rslt = new MGrpJoinreqs(ajax);
        return rslt;
    },
    update(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.GrpJoinreq.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },

}
