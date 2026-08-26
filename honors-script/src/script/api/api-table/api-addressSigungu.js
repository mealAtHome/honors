Api.AddressSigungu =
{
    selectBySdidx(sdIdx, noticeOK, noticeFail) { return Api.AddressSigungu.select({OPTION:"selectBySdidx", SDIDX:sdIdx }, noticeOK, noticeFail); },


    /* ========================= */
    /* main */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.AddressSigungu.select", noticeOK, noticeFail);
        let rslt = new MAddressSigungus(ajax);
        return rslt;
    },

}