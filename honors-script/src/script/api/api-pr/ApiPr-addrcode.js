ApiPr.Addrcode =
{
    /* ========================= */
    /* select */
    /* ========================= */
    searchByKeyword(keyword, noticeOK, noticeFail) { return ApiPr.Addrcode.select({OPTION:"searchByKeyword", KEYWORD:keyword,}, noticeOK, noticeFail); },

    /* ========================= */
    /* main function */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        return $.ajax.promise(ajaxData, "Api.Addrcode.select", noticeOK, noticeFail).then(function(json)
        {
            let models = new MAddrcodes(json);
            return models;
        });
    },

}
