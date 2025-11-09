Api.AddressSido =
{

    selectAll(noticeOK, noticeFail) { return Api.AddressSido.select({OPTION:"selectAll"}, noticeOK, noticeFail); },

    /* ========================= */
    /* main */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.AddressSido.select", noticeOK, noticeFail);
        let rslt = new MAddressSidos(ajax);
        return rslt;
    },

}