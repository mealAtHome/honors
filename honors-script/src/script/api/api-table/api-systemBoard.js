Api.SystemBoard =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectMain      (         noticeOK, noticeFail) { return Api.SystemBoard.select({OPTION : "selectMain"                          }, noticeOK, noticeFail); },
    selectOpenByPk  (sbindex, noticeOK, noticeFail) { return Api.SystemBoard.select({OPTION : "selectOpenByPk", SBINDEX : sbindex,  }, noticeOK, noticeFail); },
    selectOpenList  (         noticeOK, noticeFail) { return Api.SystemBoard.select({OPTION : "selectOpenList",                     }, noticeOK, noticeFail); },

    /* ========================= */
    /* main function */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.SystemBoard.select", noticeOK, noticeFail);
        let rslt = new MSystemBoards(ajax);
        return rslt;
    },

}