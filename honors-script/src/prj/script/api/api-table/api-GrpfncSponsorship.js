Api.GrpfncSponsorship =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByPk(grpno, sponidx, noticeOK, noticeFail) { return Api.GrpfncSponsorship.select({OPTION:"selectByPk", GRPNO:grpno, SPONIDX:sponidx }, noticeOK, noticeFail); },
    selectByGrpnoPagenum(grpno, pagenum, noticeOK, noticeFail) { return Api.GrpfncSponsorship.select({OPTION:"selectByGrpnoPagenum", GRPNO:grpno, PAGENUM:pagenum }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */
    insertFromPage(grpno, sponsortype, sponuserno, sponusername, spontype, sponitem, sponcost, sponcomment, noticeOK, noticeFail)
    {
        // /* validation */
        // if(Common.isEmpty(grpno))                                                           { Common.noticeFail (noticeFail, "시스템 오류입니다."); return _MCommon.getFailed(); }
        // if(Common.isEmpty(sponsortype))                                                     { Common.noticeFail (noticeFail, "시스템 오류입니다."); return _MCommon.getFailed(); }
        // if(Common.isEmpty(spontype))                                                        { Common.noticeFail (noticeFail, "시스템 오류입니다."); return _MCommon.getFailed(); }
        // if(sponsortype == "user" && Common.isEmpty(sponuserno))                             { Common.noticeFail (noticeFail, "찬조자가 선택되지 않았습니다."); return _MCommon.getFailed(); }
        // if(sponsortype == "name" && Common.isEmpty(sponusername))                           { Common.noticeFail (noticeFail, "찬조자 이름이 입력되지 않았습니다."); return _MCommon.getFailed(); }
        // if(spontype == GGF.GrpfncSponsorship.Spontype.THING && Common.isEmpty(sponitem))    { Common.noticeFail (noticeFail, "찬조품목이 공란입니다."); return _MCommon.getFailed(); }
        // if(spontype == GGF.GrpfncSponsorship.Spontype.MONEY && Common.isEmpty(sponcost))    { Common.noticeFail (noticeFail, "찬조금액이 공란입니다."); return _MCommon.getFailed(); }
        let ajaxData =
        {
            OPTION: "insertFromPage",
            GRPNO: grpno,
            SPONSORTYPE: sponsortype,
            SPONUSERNO: sponuserno,
            SPONUSERNAME: sponusername,
            SPONTYPE: spontype,
            SPONITEM: sponitem,
            SPONCOST: sponcost,
            SPONCOMMENT: sponcomment,
        };
        return Api.GrpfncSponsorship.update(ajaxData, noticeOK, noticeFail);
    },


    /* ========================= */
    /* detail */
    /* ========================= */

    /* ========================= */
    /* main function */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.GrpfncSponsorship.select", noticeOK, noticeFail);
        let rslt = new MGrpfncSponsorships(ajax);
        return rslt;
    },
    update(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.GrpfncSponsorship.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },

}