Api.GovAddr =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByKeyword(searchKeyword, pagenum, noticeOK, noticeFail)
    {
        let ajaxData =
        {
            SEARCH_KEYWORD : searchKeyword,
            PAGENUM        : pagenum,
        }
        let ajax = Api.execute(ajaxData, "Api.GovAddr.select", noticeOK, noticeFail);
        let rslt = new MGovAddrs(ajax);
        return rslt;
    },

}