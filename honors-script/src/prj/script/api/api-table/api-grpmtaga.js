Api.Grpmtaga =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByPk    (grpno, tagidx, noticeOK, noticeFail) { return Api.Grpmtaga.select({OPTION:"selectByPk"   , GRPNO:grpno, TAGIDX:tagidx, }, noticeOK, noticeFail).getModel(); },
    selectByGrpno (grpno,         noticeOK, noticeFail) { return Api.Grpmtaga.select({OPTION:"selectByGrpno", GRPNO:grpno,                }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */
    insertFromPage(grpno, tagname, tagcolorfont, tagcolorback, noticeOK, noticeFail)
    {
        let ajaxData =
        {
            OPTION: "insertFromPage",
            GRPNO: grpno,
            TAGNAME: tagname,
            TAGCOLORFONT: tagcolorfont,
            TAGCOLORBACK: tagcolorback,
        };
        return Api.Grpmtaga.update(ajaxData, noticeOK, noticeFail);
    },
    updateFromPage(grpno, tagidx, tagname, tagcolorfont, tagcolorback, noticeOK, noticeFail)
    {
        let ajaxData =
        {
            OPTION: "updateFromPage",
            GRPNO: grpno,
            TAGIDX: tagidx,
            TAGNAME: tagname,
            TAGCOLORFONT: tagcolorfont,
            TAGCOLORBACK: tagcolorback,
        };
        return Api.Grpmtaga.update(ajaxData, noticeOK, noticeFail);
    },
    deleteByPk(grpno, tagidx, noticeOK, noticeFail) { return Api.Grpmtaga.update({ OPTION:"deleteByPk", GRPNO:grpno, TAGIDX:tagidx, }, noticeOK, noticeFail); },

    /* ========================= */
    /* main function */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Grpmtaga.select", noticeOK, noticeFail);
        let rslt = new MGrpmtagas(ajax);
        return rslt;
    },
    update(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Grpmtaga.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },

}
