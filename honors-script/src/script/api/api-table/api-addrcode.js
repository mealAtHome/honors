Api.Addrcode =
{
    /* ========================= */
    /* select */
    /* ========================= */
    searchByKeyword(keyword, noticeOK, noticeFail) { return Api.Addrcode.select({OPTION:"searchByKeyword", KEYWORD:keyword,}, noticeOK, noticeFail); },

    /* ========================= */
    /* main function */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Addrcode.select", noticeOK, noticeFail);
        let rslt = new MAddrcodes(ajax);
        return rslt;
    },

}
