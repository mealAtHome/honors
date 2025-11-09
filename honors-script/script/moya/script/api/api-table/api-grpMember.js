Api.GrpMember =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByGrpnoForMng        (grpno,          noticeOK, noticeFail) { return Api.GrpMember.select({OPTION:"selectByGrpnoForMng"        , GRPNO: grpno,                  }, noticeOK, noticeFail); },
    selectByPkForMng           (grpno, userno,  noticeOK, noticeFail) { return Api.GrpMember.select({OPTION:"selectByPkForMng"           , GRPNO: grpno, USERNO: userno,  }, noticeOK, noticeFail).getModel(); },
    selectByPkForAll           (grpno, userno,  noticeOK, noticeFail) { return Api.GrpMember.select({OPTION:"selectByPkForAll"           , GRPNO: grpno, USERNO: userno,  }, noticeOK, noticeFail).getModel(); },
    selectMeByGrpno            (grpno,          noticeOK, noticeFail) { return Api.GrpMember.select({OPTION:"selectMeByGrpno"            , GRPNO: grpno,                  }, noticeOK, noticeFail).getModel(); },
    selectByGrpnoForAll        (grpno,          noticeOK, noticeFail) { return Api.GrpMember.select({OPTION:"selectByGrpnoForAll"        , GRPNO: grpno,                  }, noticeOK, noticeFail); },
    selectByKeywordForAll      (grpno, keyword, noticeOK, noticeFail) { return Api.GrpMember.select({OPTION:"selectByKeywordForAll"      , GRPNO: grpno, KEYWORD: keyword }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */
    updateToDeleteForMng       (grpno, userno,                      noticeOK, noticeFail) { return Api.GrpMember.update({OPTION:"updateToDeleteForMng"          , GRPNO: grpno, USERNO   : userno    ,                                              }, noticeOK, noticeFail); },
    updateInjectPointForMng    (grpno, userno   , point, pointmemo, noticeOK, noticeFail) { return Api.GrpMember.update({OPTION:"updateInjectPointForMng"       , GRPNO: grpno, USERNO   : userno    , POINT    : point     , POINTMEMO: pointmemo  }, noticeOK, noticeFail); },
    updateGrpmpositionForMng   (grpno, arr,                         noticeOK, noticeFail) { return Api.GrpMember.update({OPTION:"updateGrpmpositionForMng"      , GRPNO: grpno, ARR      : arr                                                      }, noticeOK, noticeFail); },
    makeTempUserForMng         (grpno, username , point,            noticeOK, noticeFail) { return Api.GrpMember.update({OPTION:"makeTempUserForMng"            , GRPNO: grpno, USERNAME : username  , POINT    : point     ,                       }, noticeOK, noticeFail); },
    mergeTempToMemberForMng    (grpno, userno   , target,           noticeOK, noticeFail) { return Api.GrpMember.update({OPTION:"mergeTempToMemberForMng"       , GRPNO: grpno, USERNO   : userno    , TARGET   : target    ,                       }, noticeOK, noticeFail); },

    /* ========================= */
    /* main function */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.GrpMember.select", noticeOK, noticeFail);
        let rslt = new MGrpMembers(ajax);
        return rslt;
    },
    update(ajaxData={}, noticeOK=GGF.TOAST, noticeFail=GGF.TOAST)
    {
        let ajax = Api.execute(ajaxData, "Api.GrpMember.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },


}