Api.GrpfncPurchase =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByPk(grpno, purchaseidx, noticeOK, noticeFail) { return Api.GrpfncPurchase.select({OPTION:"selectByPk", GRPNO:grpno, PURCHASEIDX:purchaseidx }, noticeOK, noticeFail); },
    selectByGrpnoPagenum(grpno, pagenum, noticeOK, noticeFail) { return Api.GrpfncPurchase.select({OPTION:"selectByGrpnoPagenum", GRPNO:grpno, PAGENUM:pagenum }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */
    insertFromPage(grpno, purchaseitem, purchasecost, purchasecomment, noticeOK, noticeFail)
    {
        let ajaxData =
        {
            OPTION: "insertFromPage",
            GRPNO: grpno,
            PURCHASEITEM: purchaseitem,
            PURCHASECOST: purchasecost,
            PURCHASECOMMENT: purchasecomment,
        };
        return Api.GrpfncPurchase.update(ajaxData, noticeOK, noticeFail);
    },


    /* ========================= */
    /* detail */
    /* ========================= */

    /* ========================= */
    /* main function */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.GrpfncPurchase.select", noticeOK, noticeFail);
        let rslt = new MGrpfncPurchases(ajax);
        return rslt;
    },
    update(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.GrpfncPurchase.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },

}