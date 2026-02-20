/*
    메뉴얼 코드

    let storeno  = $(this).attr("storeno");
    let storeStatus = $(this).attr("store_status");
    let ajaxData =
    {
        "OPTION"      : "changeStoreStatus",
        "STORENO" : storeno,
        "STORE_STATUS": storeStatus,
    }
    let process = function()
    {
        Common.showProgress();
        setTimeout(function()
        {
            let rslt = Api.Store.update(ajaxData, "toast", "toast");
            if(rslt)
                Navigation.executeShow();
        }, ajaxDelayTime);
    }
    Common.confirm2("실행하시겠습니까?", process);
 */

var Api =
{
    /* ======================= */
    /* define */
    /* ======================= */
    succeed : "S-0001", /* API성공 시, 반드시 아래의 코드를 반납하도록 되어있다. */
    failed  : "E-0001", /* API실패 시, 반드시 아래의 코드를 반납하도록 되어있다. */
    defaultPagenum  : 1,
    defaultPerpage  : 50,

    /* API 실행 후, 표시할 다이어로그 종류 */
    NONE  : "none",
    TOAST : "toast",
    ALERT : "alert",

    /* API 실행종류 */
    INSERT : "insert", /* 삭제필요 - 어디서 쓰는지 알기 어려움 */
    UPDATE : "update", /* 삭제필요 - 어디서 쓰는지 알기 어려움 */
    DELETE : "delete", /* 삭제필요 - 어디서 쓰는지 알기 어려움 */

    /* ======================= */
    /* 업데이트 한 인덱스를 반환 */
    /*
     */
    /* ======================= */
    getUpdateIndex(rslt=null)
    {
        if(rslt == null)
            return null;
        else if(rslt.UPDATE_INDEX == undefined)
            return null;
        return rslt.UPDATE_INDEX;
    },

    /* ======================= */
    /* 통신 없이도 API 결과를 만들어줌 (validation 등에 사용) */
    /* ======================= */
    getResult(isSucceed=true)
    {
        let ajax = {};
        if(isSucceed)
            ajax.CODE = Api.succeed;
        else
            ajax.CODE = Api.failed;
        return ajax;
    },

    /* ======================= */
    /* 통신 없이도 API 결과를 만들어줌 (validation 등에 사용) */
    /* ======================= */
    getResultError(code)
    {
        let rslt =
        {
            CODE  : Api.failed,
            MSG : $.i18n('(ajax)failed to connect'),
            DATA  : null,
        }

        if(code != undefined && code != null)
        {
            rslt.CODE  = code;
            rslt.MSG = code;
        }
        return rslt;
    },

    /* ======================= */
    /* 일부러 에러로 이루어진 api 결과를 생성하여 반환한다. */
    /* ======================= */
    getValidationErr()
    {
        let ajax =
        {
            CODE  : 'validationErr',
            MSG : $.i18n('(api)validation error'),
        }
        return ajax;
    },

    setMsgForRslt(rslt, noticeOK, noticeFail)
    {
        /* set MSG */
        if(rslt.CODE == Api.succeed && (rslt.MSG == "" || rslt.MSG == undefined)) rslt.MSG = $.i18n("(common)succeed");
        if(rslt.CODE != Api.succeed && (rslt.MSG == "" || rslt.MSG == undefined)) rslt.MSG = $.i18n("(common)failed");

        /* alert */
        if(rslt.CODE == Api.succeed) Common.noticeOK   (noticeOK  , rslt.MSG);
        if(rslt.CODE != Api.succeed) Common.noticeFail (noticeFail, rslt.MSG);

        return rslt;
    },

    /* ======================= */
    /* execute get type api

        ajaxData   : 서버에게 보낼 데이터
        funcName   : 실행 함수 (실행함수로부터 서버 URL를 습득한다)
        noticeOk   : 성공 했을 때, 알릴 것인가?
            [none, toast, alert]
        noticeFail : 실패 했을 때, 알릴 것인가?
            [none, toast, alert]

            - none  - 알리지 않음
            - toast - 토스트로 알림
            - alert - 확인창으로 알림
     */
    /* ======================= */
    execute(ajaxData, funcName, noticeOK="none", noticeFail="toast")
    {
        /* -------- */
        /* variables */
        /* -------- */
        let rslt = {};
        let ajaxURL = Navigation.getApiUrlByFuncName(funcName);

        if(ajaxURL == "")
        {
            rslt = Api.getResultError();
            rslt = Api.setMsgForRslt(rslt, noticeOK, noticeFail);
            return rslt;
        }

        /* -------- */
        /* 필수 파라메터 추가 */
        /* -------- */
        ajaxData.MODE           = localmode;
        ajaxData.VERSION        = versionSv;
        ajaxData.LANG           = GGstorage.getLang();
        ajaxData.APIKEY         = GGstorage.getApikey();
        ajaxData.SERVICE_LAYER  = GGstorage.getAppmode();

        /* -------- */
        /* process */
        /* -------- */
        $.ajax
        ({
            method: "POST",
            url: ajaxURL,
            data : ajaxData,
            dataType: "json",
            async: false,
            success: function(jsonData)
            {
                rslt = jsonData;
                if(rslt.CODE == "version")
                {
                    Navigation.moveFrontPage(Navigation.Page.Z00AppUpdateUrl);
                    return null;
                }
            },
            error: function(err)
            {
                rslt = Api.getResultError();
            }
        });

        /* notice result */
        rslt = Api.setMsgForRslt(rslt, noticeOK, noticeFail);
        return rslt;

    }, /* execute */

    /* ======================= */
    /* 메뉴 이미지 전용 */
    /* ======================= */
    ajaxWithMenuPic(imgUri="", params={}, noticeOK="none", noticeFail="toast")
    {
        /* add params */
        params.MODE        = localmode;
        params.APIKEY      = GGstorage.getApikey();
        params.LANG        = GGstorage.getLang();

        /* set file option */
        let options          = new FileUploadOptions();
        options.fileKey      = "file";
        options.fileName     = imgUri.substr(imgUri.lastIndexOf('/') + 1);
        options.mimeType     = "image/jpeg";
        options.params       = params;
        options.chunkedMode  = false;

        let ft = new FileTransfer();
        return new Promise(function(resolve, reject)
        {
            ft.upload
            (
                imgUri,
                ServerInfo.getServerHost()+"src/data/menu_pic/update_menuPic.php",
                function(result)
                {
                    let rslt = null;
                    try
                    {
                        rslt = JSON.parse(result.response);
                    }
                    catch(e)
                    {
                        throw e;
                    }
                    rslt = Api.setMsgForRslt(rslt, noticeOK, noticeFail);
                    resolve(result);
                },
                function(error)
                {
                    Common.noticeFail(noticeFail, "통신에 실패하였습니다.");
                    reject(error);
                },
                options
            );
        });
    },

    /* ======================= */
    /* 서버에 이미지 전송 (메뉴 외) */
    /* ======================= */
    sendImgToServer(entity, index1, index2, imgUri, successCallback=null, failedCallback=null)
    {
        /* add params */
        let params =
        {
            MODE        : localmode,
            APIKEY      : GGstorage.getApikey(),
            LANG        : GGstorage.getLang(),
            ENTITY      : entity,
            INDEX1      : index1,
            INDEX2      : index2,
        }

        /* set file option */
        let options          = new FileUploadOptions();
        options.fileKey      = "file";
        options.fileName     = imgUri.substr(imgUri.lastIndexOf('/') + 1);
        options.mimeType     = "image/jpeg";
        options.params       = params;
        options.chunkedMode  = false;

        let ft = new FileTransfer();
        ft.upload
        (
            imgUri,
            ServerInfo.getServerHost()+"src/data/utils/upload_image.php",
            function (result)
            {
                if(successCallback != null)
                    successCallback(result);
            },
            function (error)
            {
                if(failedCallback != null)
                    failedCallback(error);
            },
            options
        );
    },

    /*
        프로미스 모드에서 각 API를 추가하기 위해서 공간을 만들어 놓음
        script/api-promise/*.js 에서 각 API 파일이 추가됨.
    */
    Promise:
    {

    },

} /* API */