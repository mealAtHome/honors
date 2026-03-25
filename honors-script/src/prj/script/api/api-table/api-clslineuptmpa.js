Api.Clslineuptmpa =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByGrpnoLineuptitle    (grpno, lineuptitle, noticeOK, noticeFail) { return Api.Clslineuptmpa.select({OPTION:"selectByGrpnoLineuptitle" , GRPNO:grpno, LINEUPTITLE:lineuptitle, }, noticeOK, noticeFail); },
    selectByGrpno               (grpno, pagenum,     noticeOK, noticeFail) { return Api.Clslineuptmpa.select({OPTION:"selectByGrpno"            , GRPNO:grpno, PAGENUM: pagenum         }, noticeOK, noticeFail); },
    selectByPk                  (grpno, lineupgroup, noticeOK, noticeFail) { return Api.Clslineuptmpa.select({OPTION:"selectByPk"               , GRPNO:grpno, LINEUPGROUP:lineupgroup, }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */
    insertFromPage(grpno, lineuptitle, clslineuptmpbArr, clslineuptmpcArr, noticeOK, noticeFail)
    {
        /* validation */
        if(Common.isEmpty(lineuptitle))  { Common.toastError("템플릿 이름을 입력해주세요."); return _MCommon.getFailed(); }
        if(clslineuptmpbArr.length == 0) { Common.toastError("저장할 라인업이 없습니다."); return _MCommon.getFailed(); }

        /* data */
        let ajaxData =
        {
            OPTION: "insertFromPage",
            GRPNO: grpno,
            LINEUPTITLE: lineuptitle,
            CLSLINEUPTMPB_ARR: JSON.stringify(clslineuptmpbArr),
            CLSLINEUPTMPC_ARR: JSON.stringify(clslineuptmpcArr),
        }
        return Api.Clslineuptmpa.update(ajaxData, noticeOK, noticeFail);
    },
    updateLineuptitle       (grpno, lineupgroup, lineuptitle, noticeOK, noticeFail) { return Api.Clslineuptmpa.update({OPTION:"updateLineuptitle", GRPNO:grpno, LINEUPGROUP:lineupgroup, LINEUPTITLE:lineuptitle, }, noticeOK, noticeFail); },
    deleteByLineupgroup     (grpno, lineupgroup,              noticeOK, noticeFail) { return Api.Clslineuptmpa.update({OPTION:"deleteByLineupgroup", GRPNO:grpno, LINEUPGROUP:lineupgroup, }, noticeOK, noticeFail); },

    /* ========================= */
    /* main */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Clslineuptmpa.select", noticeOK, noticeFail);
        let rslt = new MClslineuptmpas(ajax);
        return rslt;
    },
    update(ajaxData={}, noticeOK=GGF.TOAST, noticeFail=GGF.TOAST)
    {
        let ajax = Api.execute(ajaxData, "Api.Clslineuptmpa.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },
};
