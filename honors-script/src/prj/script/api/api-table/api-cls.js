Api.Cls =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByPkForAll                    (grpno, clsno     , noticeOK, noticeFail) { return Api.Cls.select({OPTION:"selectByPkForAll"                , GRPNO: grpno, CLSNO     : clsno,     }, noticeOK, noticeFail).getModel(); },
    selectByPkForMng                    (grpno, clsno     , noticeOK, noticeFail) { return Api.Cls.select({OPTION:"selectByPkForMng"                , GRPNO: grpno, CLSNO     : clsno,     }, noticeOK, noticeFail).getModel(); },
    selectByGrpnoForMng                 (grpno, pagenum   , noticeOK, noticeFail) { return Api.Cls.select({OPTION:"selectByGrpnoForMng"             , GRPNO: grpno, PAGENUM   : pagenum,   }, noticeOK, noticeFail); },
    selectByClsstatusForMng             (grpno, clsstatus , noticeOK, noticeFail) { return Api.Cls.select({OPTION:"selectByClsstatusForMng"         , GRPNO: grpno, CLSSTATUS : clsstatus, }, noticeOK, noticeFail); },
    selectAppliedFor1YearByUserno       (grpno, userno    , noticeOK, noticeFail) { return Api.Cls.select({OPTION:"selectAppliedFor1YearByUserno"   , GRPNO: grpno, USERNO    : userno,    }, noticeOK, noticeFail); },
    selectFor1YearByGrpnoForAll         (grpno            , noticeOK, noticeFail) { return Api.Cls.select({OPTION:"selectFor1YearByGrpnoForAll"     , GRPNO: grpno,                        }, noticeOK, noticeFail); },

    selectForUserByClsstatusIng         (                   noticeOK, noticeFail) { return Api.Cls.select({OPTION:"selectForUserByClsstatusIng"                     }, noticeOK, noticeFail); },
    selectForUserByClssettleflgN        (                   noticeOK, noticeFail) { return Api.Cls.select({OPTION:"selectForUserByClssettleflgN"                    }, noticeOK, noticeFail); },
    selectForUserByClsstatusEnd         (                   noticeOK, noticeFail) { return Api.Cls.select({OPTION:"selectForUserByClsstatusEnd"                     }, noticeOK, noticeFail); },
    selectForUserByClsstatusCancel      (                   noticeOK, noticeFail) { return Api.Cls.select({OPTION:"selectForUserByClsstatusCancel"                  }, noticeOK, noticeFail); },
    selectForMngrByClsstatusEdit        (grpno,             noticeOK, noticeFail) { return Api.Cls.select({OPTION:"selectForMngrByClsstatusEdit"    , GRPNO: grpno, }, noticeOK, noticeFail); },
    selectForMngrByClsstatusIng         (grpno,             noticeOK, noticeFail) { return Api.Cls.select({OPTION:"selectForMngrByClsstatusIng"     , GRPNO: grpno, }, noticeOK, noticeFail); },
    selectForMngrByClssettleflgN        (grpno,             noticeOK, noticeFail) { return Api.Cls.select({OPTION:"selectForMngrByClssettleflgN"    , GRPNO: grpno, }, noticeOK, noticeFail); },
    selectForMngrByClsstatusEnd         (grpno,             noticeOK, noticeFail) { return Api.Cls.select({OPTION:"selectForMngrByClsstatusEnd"     , GRPNO: grpno, }, noticeOK, noticeFail); },
    selectForMngrByClsstatusCancel      (grpno,             noticeOK, noticeFail) { return Api.Cls.select({OPTION:"selectForMngrByClsstatusCancel"  , GRPNO: grpno, }, noticeOK, noticeFail); },

    /* ========================= */
    /* insert */
    /* ========================= */
    updateFromPage(
          option
        , grpno
        , clsno
        , clstype
        , clstitle
        , clscontent
        , clsstartdt
        , clsclosedt
        , clsground
        , clsgroundaddr
        , clsbillapplyunit
        , clsbillapplyprice
        , clsapplystartdt
        , clsapplyclosedt
        , clsusernoadm
        , clsusernosub
        , noticeOK
        , noticeFail
    )
    {
        let ajax =
        {
            OPTION              : option,
            GRPNO               : grpno,
            CLSNO               : clsno,
            CLSTYPE             : clstype,
            CLSTITLE            : clstitle,
            CLSCONTENT          : clscontent,
            CLSSTARTDT          : clsstartdt,
            CLSCLOSEDT          : clsclosedt,
            CLSGROUND           : clsground,
            CLSGROUNDADDR       : clsgroundaddr,
            CLSBILLAPPLYUNIT    : clsbillapplyunit,
            CLSBILLAPPLYPRICE   : clsbillapplyprice,
            CLSAPPLYSTARTDT     : clsapplystartdt,
            CLSAPPLYCLOSEDT     : clsapplyclosedt,
            CLSUSERNOADM        : clsusernoadm,
            CLSUSERNOSUB        : clsusernosub
        };
        return Api.Cls.update(ajax, noticeOK, noticeFail);
    },

    updateClsstatusEditToIng      (grpno, clsno                  , noticeOK, noticeFail) { return Api.Cls.update({ OPTION: "updateClsstatusEditToIng"  , GRPNO:grpno, CLSNO:clsno, }, noticeOK, noticeFail); },
    updateClsstatusIngToEnd       (grpno, clsno                  , noticeOK, noticeFail) { return Api.Cls.update({ OPTION: "updateClsstatusIngToEnd"   , GRPNO:grpno, CLSNO:clsno, }, noticeOK, noticeFail); },
    updateClsSettleInfoByPk       (grpno, clsno, arr             , noticeOK, noticeFail) { return Api.Cls.update({ OPTION: "updateClsSettleInfoByPk"   , GRPNO:grpno, CLSNO:clsno, ARR:arr }, noticeOK, noticeFail); },
    updateClsstatusToCancel       (grpno, clsno, clscancelreason , noticeOK, noticeFail) { return Api.Cls.update({ OPTION: "updateClsstatusToCancel"   , GRPNO:grpno, CLSNO:clsno, CLSCANCELREASON:clscancelreason }, noticeOK, noticeFail); },
    deleteByPkForMng              (grpno, clsno                  , noticeOK, noticeFail) { return Api.Cls.update({ OPTION: "deleteByPkForMng"          , GRPNO:grpno, CLSNO:clsno, }, noticeOK, noticeFail); },


    /*
        $("#CLSD-btn-copyCls").click(function()
        {
            let process = function()
            {
                Common.showProgress();
                setTimeout(function()
                {
                    let mApiResponse = Api.Cls.copyClsForMng(CLSD.Data.grpno, CLSD.Data.clsno, GGF.toast, GGF.toast);
                    // if(mApiResponse.isSuccess())
                    Common.hideProgress();
                }, ajaxDelayTime);
            }
            Common.confirm2(`일정을 복사하시겠습니까?`, process);
        });
     */
    copyClsForMng                 (grpno, clsno                  , noticeOK, noticeFail) { return Api.Cls.update({ OPTION: "copyClsForMng"                   , GRPNO:grpno, CLSNO:clsno, }, noticeOK, noticeFail); },

    /* ========================= */
    /* main */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Cls.select", noticeOK, noticeFail);
        let rslt = new MClss(ajax);
        return rslt;
    },
    selectDetail(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Cls.selectDetail", noticeOK, noticeFail);
        let rslt = new MClss(ajax);
        return rslt.getModel();
    },
    update(ajaxData={}, noticeOK=GGF.TOAST, noticeFail=GGF.TOAST)
    {
        let ajax = Api.execute(ajaxData, "Api.Cls.update", noticeOK, noticeFail);
        let model = new MApiResponse(ajax);
        return model;
    },
};