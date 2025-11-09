Api.Bankaccount =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByExecutorForUser (         noticeOK, noticeFail) { return Api.Bankaccount.select({ OPTION : "selectByExecutorForUser" ,                  }, noticeOK, noticeFail); },
    selectByExecutorForGrp  (bacckey, noticeOK, noticeFail) { return Api.Bankaccount.select({ OPTION : "selectByExecutorForGrp"  , BACCKEY:bacckey, }, noticeOK, noticeFail); },

    /* ========================= */
    /* 업데이트 */
    /* ========================= */
    insertForUser (         baccnickname, bacccode, baccacct, baccname, noticeOK, noticeFail) { return Api.Bankaccount.update({ OPTION:"insertForUser" ,                  BACCNICKNAME:baccnickname, BACCCODE:bacccode, BACCACCT:baccacct, BACCNAME:baccname }, noticeOK, noticeFail); },
    insertForGrp  (bacckey, baccnickname, bacccode, baccacct, baccname, noticeOK, noticeFail) { return Api.Bankaccount.update({ OPTION:"insertForGrp"  , BACCKEY:bacckey, BACCNICKNAME:baccnickname, BACCCODE:bacccode, BACCACCT:baccacct, BACCNAME:baccname }, noticeOK, noticeFail); },
    deleteByPk    (bacctype, bacckey, baccno, noticeOK, noticeFail) { return Api.Bankaccount.update({ OPTION: "deleteByPk", BACCTYPE: bacctype, BACCKEY: bacckey, BACCNO: baccno }, noticeOK, noticeFail); },

    /* ========================= */
    /* main */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Bankaccount.select", noticeOK, noticeFail);
        let rslt = new MBankaccounts(ajax);
        return rslt;
    },
    update(ajaxData={}, noticeOK=GGF.TOAST, noticeFail=GGF.TOAST)
    {
        let ajax = Api.execute(ajaxData, "Api.Bankaccount.update", noticeOK, noticeFail);
        let model = new MApiResponse(ajax);
        return model;
    },
}