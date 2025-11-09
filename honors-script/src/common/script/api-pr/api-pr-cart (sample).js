ApiPr.Cart =
{
    /* ========================= */
    /* select */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        return $.ajax.promise(ajaxData, "Api.Cart.select", noticeOK, noticeFail).then(function(json)
        {
            let models = new MCarts(json);
            return models;
        });
    },

    selectByExecutor(noticeOK, noticeFail) { return ApiPr.Cart.select({OPTION : "selectByExecutor"}, noticeOK, noticeFail); },

    /* ========================= */
    /* selectCount */
    /* ========================= */
    // selectCountByExecutor(noticeOK, noticeFail) { return ApiPr.Cart.selectCount({OPTION : "selectByExecutor"}, noticeOK, noticeFail); },
    // selectCount(ajaxData, noticeOK, noticeFail)
    // {
    //     return $.ajax.promise(ajaxData, "Api.Cart.selectCount", noticeOK, noticeFail).then(function(json)
    //     {
    //         let model = new MCount(json);
    //         return model;
    //     });
    // },

    /* --------------- */
    /* search */
    /* --------------- */
    // search(ajaxData, noticeOK, noticeFail)
    // {
    //     return ApiPr.Cart.executeSelect(ajaxData, "Api.Cart.search", noticeOK, noticeFail);
    // },

    /* --------------- */
    /* select */
    /* --------------- */
    // select(ajaxData, noticeOK, noticeFail)
    // {
    //     return ApiPr.Cart.executeSelect(ajaxData, "Api.Cart.select", noticeOK, noticeFail);
    // },

    /* ========================= */
    /* selectDetail */
    /* ========================= */
    // selectDetail(logno, deploy, noticeOK, noticeFail)
    // {
    //     let ajaxData =
    //     {
    //         LOGNO  : logno,
    //         DEPLOY : deploy,
    //     }
    //     return ApiPr.Cart.executeSelect(ajaxData, "Api.Cart.selectDetail", noticeOK, noticeFail);
    // },

}