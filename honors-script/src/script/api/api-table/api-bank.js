Api.Bank =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectAll(noticeOK, noticeFail) { let ajaxData = { OPTION : "selectAll" }; return Api.Bank.select(ajaxData, noticeOK, noticeFail); },

    /* ========================= */
    /* main */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Bank.select", noticeOK, noticeFail);
        let rslt = new MBanks(ajax);
        return rslt;
    },

}