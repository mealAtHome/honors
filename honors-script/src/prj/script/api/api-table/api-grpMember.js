Api.GrpMember =
{
    /* ========================= */
    /* select */
    /* ========================= */
    /* select */    selectMeByGrpno                             (           grpno,                                              noticeOK, noticeFail) { return Api.GrpMember.select({OPTION:"selectMeByGrpno"                           ,                      GRPNO: grpno,                                                                                }, noticeOK, noticeFail).getModel(); },
    /* select */    selectByExecutorForAll                      (                                                               noticeOK, noticeFail) { return Api.GrpMember.select({OPTION:"selectByExecutorForAll"                    ,                                                                                                                   }, noticeOK, noticeFail); },
    /* select */    selectByPkForAll                            (           grpno,  userno,                                     noticeOK, noticeFail) { return Api.GrpMember.select({OPTION:"selectByPkForAll"                          ,                      GRPNO: grpno,   USERNO: userno,                                                              }, noticeOK, noticeFail).getModel(); },
    /* select */    selectByPkForMng                            (           grpno,  userno,                                     noticeOK, noticeFail) { return Api.GrpMember.select({OPTION:"selectByPkForMng"                          ,                      GRPNO: grpno,   USERNO: userno,                                                              }, noticeOK, noticeFail).getModel(); },
    /* select */    selectByGrpnoForAll                         (           grpno,                                              noticeOK, noticeFail) { return Api.GrpMember.select({OPTION:"selectByGrpnoForAll"                       ,                      GRPNO: grpno,                                                                                }, noticeOK, noticeFail); },
    /* select */    selectByGrpnoForMng                         (pagenum,   grpno,                                              noticeOK, noticeFail) { return Api.GrpMember.select({OPTION:"selectByGrpnoForMng"                       , PAGENUM: pagenum,    GRPNO: grpno,                                                                                }, noticeOK, noticeFail); },
    /* select */    selectByKeywordForAll                       (           grpno,  keyword,                                    noticeOK, noticeFail) { return Api.GrpMember.select({OPTION:"selectByKeywordForAll"                     ,                      GRPNO: grpno,   KEYWORD: keyword,                                                            }, noticeOK, noticeFail); },
    /* select */    selectByKeywordWithPageForAll               (pagenum,   grpno,  keyword,                                    noticeOK, noticeFail) { return Api.GrpMember.select({OPTION:"selectByKeywordWithPageForAll"             , PAGENUM: pagenum,    GRPNO: grpno,   KEYWORD: keyword,                                                            }, noticeOK, noticeFail); },
    /* select */    selectByGrpnoUsernameSearchtypeForAll       (pagenum,   grpno,  username,   searchtype,                     noticeOK, noticeFail) { return Api.GrpMember.select({OPTION:"selectByGrpnoUsernameSearchtypeForAll"     , PAGENUM: pagenum,    GRPNO: grpno,   USERNAME: username,      SEARCHTYPE: searchtype,                             }, noticeOK, noticeFail); },
    /* UPDATE */    updateToDeleteForMng                        (           grpno,  userno,                                     noticeOK, noticeFail) { return Api.GrpMember.update({OPTION:"updateToDeleteForMng"                      ,                      GRPNO: grpno,   USERNO : userno,                                                             }, noticeOK, noticeFail); },
    /* UPDATE */    updateInjectPointForMng                     (           grpno,  userno,     point,          pointmemo,      noticeOK, noticeFail) { return Api.GrpMember.update({OPTION:"updateInjectPointForMng"                   ,                      GRPNO: grpno,   USERNO : userno,         POINT : point,              POINTMEMO: pointmemo,   }, noticeOK, noticeFail); },
    /* UPDATE */    updateGrpmpositionForMng                    (           grpno,  arr,                                        noticeOK, noticeFail) { return Api.GrpMember.update({OPTION:"updateGrpmpositionForMng"                  ,                      GRPNO: grpno,   ARR : arr,                                                                   }, noticeOK, noticeFail); },
    /* UPDATE */    makeTempUserForMng                          (           grpno,  username,   point,                          noticeOK, noticeFail) { return Api.GrpMember.update({OPTION:"makeTempUserForMng"                        ,                      GRPNO: grpno,   USERNAME : username,     POINT : point,                                      }, noticeOK, noticeFail); },
    /* UPDATE */    mergeTempToMemberForMng                     (           grpno,  userno,     target,                         noticeOK, noticeFail) { return Api.GrpMember.update({OPTION:"mergeTempToMemberForMng"                   ,                      GRPNO: grpno,   USERNO : userno,         TARGET : target ,                                   }, noticeOK, noticeFail); },

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