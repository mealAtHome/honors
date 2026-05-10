


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

var ApiPr = {};
$.ajax.promise = function(
    ajaxData,
    funcName,
    noticeOK="none",
    noticeFail="toast"
)
{
    /* -------- */
    /* variables */
    /* -------- */
    let deferred = $.Deferred();
    let rslt = {};
    let ajaxURL = Navigation.getApiUrlByFuncName(funcName);

    /* validation */
    if(ajaxURL == "")
    {
        Common.toast("url 이 무효입니다.");
        rslt =
        {
            CODE  : "AX-0001",
            MSG : $.i18n('(ajax)failed to connect'),
            DATA  : null,
        };
        deferred.reject(rslt);
    }

    /* -------- */
    /* 필수 파라메터 추가 */
    /* -------- */
    ajaxData.LANG           = GGstorage.getLang();
    ajaxData.APIKEY         = GGstorage.getApikey();
    ajaxData.SERVICE_LAYER  = GGstorage.getAppmode();

    /* --------------- */
    /* execute ajax */
    /* --------------- */
    $.ajax({
        method      : "POST",
        // contentType : false,
        // processData : false,
        url         : ajaxURL,
        data        : ajaxData,
        dataType    : "json",
    })
    .done(function (rslt, status, responseObj)
    {
        rslt = Api.setMsgForRslt(rslt, noticeOK, noticeFail);
        deferred.resolve(rslt);
    })
    .fail(function (result, status, responseObj)
    {
        let rslt = Api.getResultError();
        rslt = Api.setMsgForRslt(rslt, noticeOK, noticeFail);
        deferred.reject(rslt);
    });
    return deferred.promise();
};


var Choseong =
{
    getChoArr() { return ["ㄱ","ㄲ","ㄴ","ㄷ","ㄸ","ㄹ","ㅁ","ㅂ","ㅃ","ㅅ","ㅆ","ㅇ","ㅈ","ㅉ","ㅊ","ㅋ","ㅌ","ㅍ","ㅎ"]; },
    getChoStr() { return "ㄱㄲㄴㄷㄸㄹㅁㅂㅃㅅㅆㅇㅈㅉㅊㅋㅌㅍㅎ"; },

    /* =================== */
    /* 주어진 문자를 초성으로 변환 */
    /* =================== */
    toCho(str)
    {
        var res = "", // 초성으로 변환
        choArr = Choseong.getChoArr();

        for (var i in str) {
            code = Math.floor((str[i].charCodeAt() - 44032) / 588)
            res += code >= 0 ? choArr[code] : str[i];
        }
        return res;
    },

    /* =================== */
    /* 검색키워드와 주어진 str을 비교해서 일치하는지? */
    /* =================== */
    compare(key, str)
    {
        key = key.toString();
        str = str.toString();

        /* ------------ */
        /* 검색키워드와 비교문자열을 비교해볼 횟수 */
        /* ------------ */
        let loopCnt = (str.length - key.length) + 1;

        /* 검색키워드의 글자가 더 길다면, 절대 일치할 수 없음. */
        if(loopCnt < 0)
            return -1;

        /* ------------ */
        /* 검색키워드 안에 초성이 없다면 평범하게 함수로 비교 */
        /* ------------ */
        let choStr = Choseong.getChoStr();
        let choInKey = [];
        for(var i in key)
        {
            if(choStr.search(key[i]) >= 0)
                choInKey.push(i);
        }

        if(choInKey.length == 0)
            return Choseong.upgradeSearch(key, str);

        /* ------------ */
        /* 검색키워드 안에 초성이 있다면, str을 루프시켜 비교 */
        /* ------------ */
        for(var i = 0; i < loopCnt; i++)
        {
            let compare = str;

            /* i를 기준으로 해서, 초성이 있었던 인덱스에 비교문자열도 초성으로 변경 */
            for(var j = 0; j < choInKey.length; j++)
            {
                let choIdx = i+parseInt(choInKey[j]);
                let cho = Choseong.toCho(compare[choIdx]);
                let head = compare.substring(0,choIdx);
                let tail = compare.substr(choIdx+1);
                compare = head+cho+tail;
            }

            /* 초성을 변환한 뒤 일치하는 인덱스를 리턴한다. */
            if(Choseong.upgradeSearch(key, compare) >= 0)
                return Choseong.upgradeSearch(key, compare);
        }
        return -1;
    },

    /* =================== */
    /* search 시, 대문자는 소문자로 변환하여 비교 */
    /* =================== */
    upgradeSearch(key, str)
    {
        str = str.toUpperCase();
        key = key.toUpperCase();
        return str.search(key);
    }
}

var Common =
{
    /* ================ */
    /* 프로그래스 관련 */
    /* ================ */
    sleep()      { return new Promise(resolve => setTimeout(resolve, ajaxDelayTime)); },
    showCircle() { $("#index-progress").show(); $("#index-progress-mask").show(); return this.sleep(); },
    hideCircle() { $("#index-progress").hide(); $("#index-progress-mask").hide(); return this.sleep(); },

    showProgress() { $("#index-progress").show(); $("#index-progress-mask").show(); },
    hideProgress() { $("#index-progress").hide(); $("#index-progress-mask").hide(); },

    showFooterUsr(hyperlink)
    {
        $("#index-div-footerUsr").show();
        $(`#index-div-footerUsr > table > tbody > tr > td`).attr("tab", "");
        $(`#index-div-footerUsr > table > tbody > tr > td[hyperlink=${hyperlink}]`).attr("tab", "tab");
    },
    hideFooterUsr()
    {
        $("#index-div-footerUsr").hide();
        $(`#index-div-footerUsr > table > tbody > tr > td`).attr("tab", "");
    },

    /* isEmpty */
    isEmpty (val)             { return (val == undefined || val == null || val == "") ? true : false; },
    ifEmpty (val, defaultVal) { return Common.isEmpty(val) ? defaultVal : val; },
    ifempty (val, defaultVal) { return Common.isEmpty(val) ? defaultVal : val; },

    /* ================ */
    /* 크리티컬 에러 발생 시 */
    /* ================ */
    catchProc(e)
    {
        console.error(e);
        Common.hideProgress();
        Common.toast("예기치 못한 에러가 발생하였습니다. 잠시 후 다시 시도해주세요.");
        Navigation.moveBack();
    },

    /* ================ */
    /* 진행할 수 없는 에러 */
    /* ================ */
    error(msg="")
    {
        if(msg == "")
            msg = $.i18n('(common)unexpected error');

        Common.alertError(msg);
        Navigation.moveBack();
    },

    /* ================ */
    /* confirm 공통화 */
    /*
        parameter
            [선택] str      title      : confirm 에 표시할, 타이틀
            [선택] str      msg        : confirm 에 표시할, 메시지
            [필수] function okCallback : 확인을 클릭하면 실시할 함수
            [선택] function noCallback : 취소를 클릭하면 실시할 함수
            [선택] array    btn        : 표시할 버튼들

        return
            null
    */
    /* ================ */
    confirm2(msg, okCallback, ngCallback)
    {
        Common.confirm
        ({
            title      : "알림",
            msg        : msg,
            okCallback : okCallback,
            noCallback : ngCallback,
        });
    },
    confirmClose(msg="입력사항이 저장되지 않습니다. 그래도 닫으시겠습니까?")
    {
        Common.confirm
        ({
            title      : "알림",
            msg        : msg,
            okCallback : Navigation.moveBack,
        });
    },
    confirmExit()
    {
        if(appExitFlg == false)
        {
            appExitFlg = true;
            Common.toast("뒤로가기 버튼을 한번 더 누르시면 앱이 종료됩니다.");
            setTimeout(function()
            {
                appExitFlg = false;
            }, 3000);
        }
        else
        {
            navigator.app.exitApp();
        }
    },
    confirm(option={})
    {
        /* confirm parameter */
        let title       = option.title      != undefined ? option.title       : $.i18n("실행하시겠습니까?");
        let msg         = option.msg        != undefined ? option.msg         : $.i18n('확인');
        let okCallback  = option.okCallback != undefined ? option.okCallback  : function(){};
        let noCallback  = option.noCallback != undefined ? option.noCallback  : function(){};
        let btn         = option.btn        != undefined ? option.btn         : [$.i18n('취소'), $.i18n('확인')];

        /* execute confirm */
        let deviceKind = GGstorage.getDeviceKind();
        switch(deviceKind)
        {
            case GGF.System.DeviceKind.PC     :
            case GGF.System.DeviceKind.MOBILE :
            {
                ons.notification.confirm
                ({
                    "title" : title,
                    "message": msg,
                    "buttonLabels": btn,
                    "callback": function(ans)
                    {
                        if(ans == 1)
                            okCallback();
                        else
                            noCallback();
                    }
                });
                break;
            }
            case GGF.System.DeviceKind.WEB :
            {
                $.confirm
                ({
                    title : title,
                    content : msg,
                    boxWidth : "500px",
                    useBootstrap: false,
                    buttons :
                    {
                        cancel:
                        {
                            text : `${$.i18n('취소')}`,
                            btnClass: 'btn-orange',
                            action: function() {
                                noCallback();
                            }
                        },
                        ok:
                        {
                            text : `&nbsp;&nbsp;&nbsp;${$.i18n('확인')}&nbsp;&nbsp;&nbsp;`,
                            btnClass: 'btn-blue',
                            action: function() {
                                okCallback();
                            }
                        },
                    }
                });
                break;
            } /* end case */
        } /* deviceKind */
    }, /* function */

    /* ========================= */
    /* icon : info, warning, error, success */
    /* ========================= */
    noticeFail (notice="none", msg="") { Common.alert(notice, "error"   , "에러", msg); },
    noticeOK   (notice="none", msg="") { Common.alert(notice, "success" , "성공", msg); },

    toastInfo     (msg) { Common.alert("toast", GGF.System.AlertIcon.INFO    , "알림", msg); },
    toastError    (msg) { Common.alert("toast", GGF.System.AlertIcon.ERROR   , "에러", msg); },
    toastWarn     (msg) { Common.alert("toast", GGF.System.AlertIcon.WARNING , "경고", msg); },
    toastSuccess  (msg) { Common.alert("toast", GGF.System.AlertIcon.SUCCESS , "성공", msg); },
    alertInfo     (msg) { Common.alert("alert", GGF.System.AlertIcon.INFO    , "알림", msg); },
    alertError    (msg) { Common.alert("alert", GGF.System.AlertIcon.ERROR   , "에러", msg); },
    alertWarn     (msg) { Common.alert("alert", GGF.System.AlertIcon.WARNING , "경고", msg); },
    alertSuccess  (msg) { Common.alert("alert", GGF.System.AlertIcon.SUCCESS , "성공", msg); },

    toast(msg)
    {
        Common.alert("toast", GGF.System.AlertIcon.INFO, "", msg);
    },
    alert(displayType="toast", icon="info", title="", msg="")
    {
        /* displayType : none → 何もしない */
        if(displayType == "none")
            return false;

        /* set title */
        if(title == "")
        {
            switch(icon)
            {
                case GGF.System.AlertIcon.INFO     : title = $.i18n("(common.js)alert-info"); break;
                case GGF.System.AlertIcon.ERROR    : title = $.i18n("(common.js)alert-error"); break;
                case GGF.System.AlertIcon.WARNING  : title = $.i18n("(common.js)alert-warning"); break;
                case GGF.System.AlertIcon.SUCCESS  : title = $.i18n("(common.js)alert-success"); break;
            }
        }

        /* display */
        let deviceKind = GGstorage.getDeviceKind();
        switch(deviceKind)
        {
            case GGF.System.DeviceKind.PC     :
            case GGF.System.DeviceKind.MOBILE :
            {
                /* set msg */
                if(msg == "")
                    msg = title;

                switch(displayType)
                {
                    case "toast":
                    {
                        GGtoast.show(msg);
                        break;
                    }
                    case "alert":
                    {
                        ons.notification.alert
                        ({
                            "title": title,
                            "message": msg,
                        });
                        break;
                    }
                }
                break;
            }
            case GGF.System.DeviceKind.WEB :
            {
                $.toast
                ({
                    heading  : title,
                    icon     : icon,
                    text     : msg,
                    position : 'top-center',
                    stack    : false,
                    hideAfter: 5000,
                })
                break;
            }
        }
    },

    /* ================ */
    /* 버튼을 딜레이 시킴 */
    /* ================ */
    delayBtn(el="", delayTime=btnDelayTime)
    {
        $(el).prop("disabled", true);
        setTimeout(function()
        {
            $(el).prop("disabled", false);
        }, delayTime);
    },

    /* ===================== */
    /* 주어진 길이의 랜덤 문자열을 반환한다. */
    /* ===================== */
    getRandStr(length = 10)
    {
        let getRandom = function(min, max)
        {
            let rand = Math.floor(Math.random() * (max-min+1));
            return rand+min;
        }

        let characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        let charactersLength = characters.length;
        let randomString = '';
        for (let i = 0; i < length; i++)
        {
            randomString += characters[getRandom(0, charactersLength - 1)];
        }
        return randomString;
    },

    /* ================ */
    /* 4자리 수 올해 연도를 반환한다. */
    /* ================ */
    getYear()
    {
        let date = new Date();
        return date.getFullYear();
    },

    getLastMonthDate()
    {
        let start = new Date();
        if(start.getMonth() == 0)
        {
            start.setFullYear(start.getFullYear()-1);
            start.setMonth(11);
        }
        else
        {
            start.setMonth(start.getMonth()-1);
        }
        return start;
    },

    getThisMonthDate()
    {
        let end = new Date();
        return end;
    },

    getNextMonthDate()
    {
        let end = new Date();
        if(end.getMonth() == 11)
        {
            end.setFullYear(end.getFullYear()+1);
            end.setMonth(0);
        }
        else
            end.setMonth(end.getMonth()+1);

        return end;
    },

    /* ===================== */
    /* 테이블 정렬 */
    /*
        [*] option =
        {
            [*] tblDom  :
            [*] colNum  :
            [*] dir     : [asc, desc]
        }
        */
    /* ===================== */
    sortTable(option={})
    {
        /* ------------- */
        /* init vars */
        /* ------------- */
        let tblDom       = option.tblDom;
        let colNum       = option.colNum;
        let dir          = option.dir;

        let rows         = 0; /* tr */
        let i            = 0; /* rowNum */
        let thisRow      = 0; /* in switching process : nowRow */
        let nextRow      = 0; /* in switching process : nextRow */
        let switchcount  = 0; /*  */
        let shouldSwitch = 0; /* is need sort? */

        /* ------------- */
        /* process start */
        /* ------------- */
        let switching = true; /* sort complete flag : false means that sort is completed */
        while (switching)
        {
            /* init vars */
            switching = false;
            rows = tblDom.rows;

            /* ------ */
            /* 전체 tr 루프 */
            /* ------ */
            for (i = 1; i < (rows.length-1); i++)
            {
                /* init vars */
                shouldSwitch = false;

                /* get html : 다음 행과 비교 */
                x = rows[i].getElementsByTagName("TD")[colNum];
                y = rows[i+1].getElementsByTagName("TD")[colNum];
                if(dir == "asc")
                {
                    if(x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase())
                    {
                        shouldSwitch= true;
                        break;
                    }
                }
                else if (dir == "desc")
                {
                    if(x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase())
                    {
                        shouldSwitch = true;
                        break;
                    }
                }
            }

            /* ------ */
            /* 행을 바꿔야할 때 */
            /* ------ */
            if(shouldSwitch)
            {
                /* If a switch has been marked, make the switch and mark that a switch has been done: */
                rows[i].parentNode.insertBefore(rows[i+1], rows[i]);
                switching = true;

                /* Each time a switch is done, increase this count by 1: */
                switchcount ++;
            }
        } /* while */
    }, /* sortTable */


    /* ----------------- */
    /* 이미지경로출력 */
    /* ----------------- */
    getUserImgSrc(img)   { return GGC.Cvrt.img("user", img); },
    getStoreImgSrc(img)  { return GGC.Cvrt.img("store", img); },

    /* ----------------- */
    /* 이미지경로출력 (원본) */
    /* ----------------- */
    getUserImgSrcOrigin(img)   { return GGC.Cvrt.img("user", img, true); },
    getStoreImgSrcOrigin(img)  { return GGC.Cvrt.img("store", img, true); },

    img(type, img, origin=false)
    {
        let originPath = "";
        let src = "";

        /* is origin? */
        if(origin == true)
            originPath = "_origin";

        /* get image path */
        if(type != null && img != null)
            src = `${ServerInfo.getServerHost()}res/${type}${originPath}/${img}`;
        else
            src = dummySrc;

        return src;
    },

    getPicPath(img, origin=false)
    {
        let originPath = "";
        let src        = "";

        /* is origin? */
        if(origin == true)
            originPath = "-origin";

        /* get image path */
        if(img != undefined && img != "" && img != null)
            src = `${ServerInfo.getResourceHost()}/pic/${img}${originPath}.png`;
        else
            src = dummySrc;

        return src;
    },

    getPicFromAlbum(callback)
    {
        /* 모바일만 대응 */
        if(GGstorage.getDeviceKind() != GGF.System.DeviceKind.MOBILE)
        {
            Common.toastError($.i18n("(validation-m)for mobile"));
            return;
        }

        /* 앨범에서 사진 가져오기 */
        navigator.camera.getPicture
        (
            function(imageData)
            {
                plugins.crop
                (
                    function success(data)
                    {
                        callback(data);
                    },
                    function fail(){},
                    imageData,
                    {quality:100}
                );
            },
            function(msg){},
            {
                quality             : 100,
                // allowEdit           : true,
                // targetHeight        : 200,
                // targetWidth         : 200,
                destinationType     : Camera.DestinationType.FILE_URI,
                correctOrientation  : true,
                sourceType          : Camera.PictureSourceType.SAVEDPHOTOALBUM,
            }
        ); /* getPicture */
    },

    copyToClipboard(text)
    {
        let deviceKind = GGstorage.getDeviceKind();
        if(deviceKind != GGF.System.DeviceKind.MOBILE)
        {
            Common.toastError("모바일에서만 지원합니다.");
            return;
        }
        try
        {
            cordova.plugins.clipboard.copy(Common.decodeHTMLEntities(text));
            Common.toastInfo("복사되었습니다.");
        }
        catch(e)
        {
            console.error(e);
            Common.toastError("복사에 실패했습니다.");
        }
    },

    decodeHTMLEntities(str)
    {
        if(str !== undefined && str !== null && str !== '') {
            str = String(str);
            str = str.replace(/<script[^>]*>([\S\s]*?)<\/script>/gmi, '');
            str = str.replace(/<\/?\w(?:[^"'>]|"[^"]*"|'[^']*')*>/gmi, '');
            let element = document.createElement('div');
            element.innerHTML = str;
            str = element.textContent;
            element.textContent = '';
        }
        return str;
    }

};

var CommonEvent =
{
    /* ========================== */
    /* index.html 이 로드될 때, $(document).ready 에서 한 번만 실행 */
    /* ========================== */
    init()
    {
        $('body').on('keyup',  '.commonEvent-number-pricePretty',              $.proxy(CommonEvent.pricePretty, this));            /* 가격 입력 시, 정리된 가격을 표시 */
        $('body').on('click',  '.common-tag-checkbox',                         $.proxy(CommonEvent.tagCheckbox, this));
        $('body').on('click',  '.common-tr-checkTr',                           $.proxy(CommonEvent.checkTr, this));
        $('body').on('click',  '.commonEvent-btn-viewDetail',                  $.proxy(CommonEvent.pop, this));
        $('body').on('click',  '.common-th-record',                            $.proxy(CommonEvent.toastRecord, this));
        $('body').on('click',  '.subEntity-selectNavi-moveTD',                 $.proxy(CommonEvent.navigateSelect, this));
        $('body').on('click',  '.commonEvent-div-sidemenuBtn',                 $.proxy(CommonEvent.openSidemenu, this));           /* 사이드 메뉴 */
        $('body').on('click',  '.commonEvent-btn-radio',                       $.proxy(CommonEvent.radio, this));                  /* 라디오 버튼 */
        $('body').on('click',  '.commonEvent-btn-tab',                         $.proxy(CommonEvent.btntab, this));                  /* 라디오 버튼 */
        $('body').on('click',  '.commonEvent-tag-hyperlink',                   $.proxy(CommonEvent.hyperlink, this));              /* 라디오 버튼 */
        $('body').on('click',  '.commonEvent-svg-GGscore',                     $.proxy(CommonEvent.ggScore, this));                /* 리뷰점수표시 */
        $('body').on('click',  '.commonEvent-svg-GGscoreSub',                  $.proxy(CommonEvent.ggScoreSub, this));             /* 리뷰점수표시 */
        $('body').on('click',  '.commonEvent-img-viewer',                      $.proxy(CommonEvent.imgViewer, this));              /* 원본이미지 뷰어 */
        $('body').on('click',  '.commonEvent-el-action',                       $.proxy(CommonEvent.action, this));                 /* 특정 버튼에 액션부여 */
        $('body').on('click',  '.commonEvent-div-pagenationBtn',               $.proxy(CommonEvent.tapPagenationBtn, this));       /* 페이지네이션 div */
        $('body').on('click',  '.commonEvent-tbl-multitab > tbody > tr > td',  $.proxy(CommonEvent.multitab, this));               /* 멀티 탭 */
        $('body').on('click',  '.commonEvent-tbl-tab > tbody > tr > td',       $.proxy(CommonEvent.tab, this));                    /* 탭 */
        $('body').on('click',  '.commonEvent-tbl-btnGroup > tbody > tr > td',  $.proxy(CommonEvent.tab, this));                    /* 버튼 */
        $('body').on('click',  '.commonEvent-tbl-sort > thead > tr > th',      $.proxy(CommonEvent.sort, this));                   /* 테이블 정렬 */
        $('body').on('click',  '.commonEvent-btn-round',                       $.proxy(CommonEvent.roundBtn, this));               /* 유저의 탭으로 plus/minus 처리를 해줌. */
        $('body').on('click',  '.commonEvent-btn-plusMinus',                   $.proxy(CommonEvent.btnPlusMinus, this));           /* 유저의 탭으로 plus/minus 처리를 해줌. */
        $('body').on('click',  '.commonEvent-tag-userStorelove',               $.proxy(CommonEvent.userStorelove, this));           /* 유저의 탭으로 plus/minus 처리를 해줌. */

        /* viberator, sound */
        // $('body').on('click', 'button', $.proxy(touch.btn, this));
    },

    getTarget(e, className="")
    {
        let target = $(e.target);
        if(!target.hasClass(className))
            target = target.parents("."+className);

        return target;
    },

    sort(e)
    {
        /* get target */
        let target = $(e.target);
        try
        {
            if(target[0].tagName != "TH")
                target = target.parent("th");
        }
        catch(e)
        {
            Common.toast(e);
        }

        /* 변수정의 */
        let col      = target.attr('col');
        let able     = target.attr('sort_able');
        let datatype = target.attr('sort_datatype');
        let status   = target.attr('sort_status');
        let tbl      = target.parents('table');

        /* 소팅 불가능할 경우, 리턴 */
        if(able != "y")
            return;

        /* status : 현재 소팅방향이 asc 인지 desc 인지 */
        /*
            기존 소팅의 값이
            asc  > desc
            desc > asc
            else > asc
         */
        switch(status)
        {
            case "asc"  : status = "desc"; break;
            default     : status = "asc";  break;
        }

        /* 해당 테이블의 소팅상태를 모두 리셋 */
        tbl.find(`thead`).find(`th`).each(function() { $(this).attr("sort_status", ""); });

        /* 행에 번호 부여 && 정렬하고 싶은 값 추출 */
        let arr = []; /* 행 번호와 데이터를 함께 저장 */
        let i = 0;
        tbl.find(`tbody`).find(`tr`).each(function()
        {
            $(this).attr("sort_rownum", i);
            let colDat = $(this).find(`td[col=${col}]`).attr("sort_data");
            let dat =
            {
                ROW_NUM : i,
                COL_DAT : colDat,
            }
            arr.push(dat);
            i++;
        });

        /* DATA sorting */
        arr.sort(function(a,b)
        {
            let ori = a.COL_DAT;
            let com = b.COL_DAT;
            if(status == "asc")
            {
                switch(datatype)
                {
                    case "num": return ori - com;
                    case "str": return ori.localeCompare(com);
                }
            }
            else
            {
                switch(datatype)
                {
                    case "num": return com- ori;
                    case "str": return com.localeCompare(ori);
                }
            }
        });

        /* TR sorting */
        let tbody = tbl.find(`tbody`);
        for(let i in arr)
        {
            for(let j in arr)
            {
                if(i == arr[j].ROW_NUM)
                {
                    let el = tbody.find(`tr[sort_rownum=${j}]`);
                    if(i == 0)
                        tbody.prepend(el);
                    else
                        tbody.find(`tr[sort_rownum=${i-1}]`).after(el);
                }
            }
        }

        /* 클릭한 헤더만 소팅상태 설정 */
        target.attr("sort_status", status);
    },

    pricePretty(e)
    {
        /* get target */
        let target = this.getTarget(e, "commonEvent-number-pricePretty");

        /* 업데이트 하려는 el query */
        let price   = target.val();
        let pretty  = GGC.Common.priceHan(price);
        let elQuery = target.attr("event_pricepretty_el");
        let type    = target.attr("event_pricepretty_type");

        /* type 을 이용하여 자매 엘리먼트를 선택할 수 있다. */
        if(type != undefined)
        {
            switch(type)
            {
                case "sibling":
                {
                    target.parent().find(elQuery).html(pretty);
                    break;
                }
            }
        }
        else
        {
            $(elQuery).html(pretty);
        }
    },

    userStorelove(e)
    {
        /* dont throw event to parent */
        e.stopPropagation();

        /* get target */
        let target = this.getTarget(e, "commonEvent-tag-userStorelove");

        /* vars */
        let storeloveflg  = target.attr("storeloveflg");
        let storeno    = target.attr("storeno");

        /* update user_storelove */
        Common.showProgress();
        setTimeout(function()
        {
            switch(storeloveflg)
            {
                /* yes → no */
                case "y":
                {
                    Api.UserStorelove.delete(storeno);
                    target.css("background-image", `url('${GGutils.Img.getHeartEmpty()}`);
                    target.attr("storeloveflg", "n");
                    break;
                }
                /* no → yes */
                case "n":
                {
                    Api.UserStorelove.insert(storeno);
                    target.css("background-image", `url('${GGutils.Img.getHeartFilled()}`);
                    target.attr("storeloveflg", "y");
                    break;
                }
            }
            Common.hideProgress();
        }, ajaxDelayTime);
    },

    ggScore(e)
    {
        /* class name */
        let className = "commonEvent-svg-GGscore";

        /* 클래스 선택 */
        let target = $(e.target);
        if(!target.hasClass(className))
            target = target.parents("."+className);

        /* vars */
        let clickedScore = 1 * target.attr("score"); /* 선택한 점수 습득 */
        let parent = target.parents(".GGscore-div-top");

        /* set element (gray) */
        parent.find(".commonEvent-svg-GGscore[score-type=gray]").each(function()
        {
            let score = $(this).attr("score") * 1;
            if(score <= clickedScore)
                $(this).hide();
            else
                $(this).show();
        });

        /* set element (gold) */
        parent.find(".commonEvent-svg-GGscore[score-type=gold]").each(function()
        {
            let score = $(this).attr("score") * 1;
            if(score <= clickedScore)
                $(this).show();
            else
                $(this).hide();
        });

        /* reset sub score */
        parent.find(".commonEvent-svg-GGscoreSub").each(function()
        {
            let scoreType = $(this).attr("score-type");
            if(scoreType == "gold")
                $(this).hide();
            else
                $(this).show();
        });

        /* set announce */
        parent.find(".GGscore-div-announce").hide();
        parent.find(".GGscore-div-announce[score="+clickedScore+"]").show();

        /* set score to parent */
        parent.attr("score", clickedScore);

        /* set score to span */
        parent.find(".GGscore-span-score").html(clickedScore);

        /* if clickedScore is 5, hide socreSub */
        if(clickedScore == 5)
            parent.find(".GGscore-div-scoreSub").hide();
        else
            parent.find(".GGscore-div-scoreSub").show();
    },

    ggScoreSub(e)
    {
        /* class name */
        let className = "commonEvent-svg-GGscoreSub";

        /* 클래스 선택 */
        let target = $(e.target);
        if(!target.hasClass(className))
            target = target.parents("."+className);

        /* vars */
        let clickedScore = 1 * target.attr("score"); /* 선택한 점수 습득 */
        let parent = target.parents(".GGscore-div-top");

        /* set element (gray) */
        parent.find(".commonEvent-svg-GGscoreSub[score-type=gray]").each(function()
        {
            let score = $(this).attr("score") * 1;
            if(score <= clickedScore)
                $(this).hide();
            else
                $(this).show();
        });

        /* set element (gold) */
        parent.find(".commonEvent-svg-GGscoreSub[score-type=gold]").each(function()
        {
            let score = $(this).attr("score") * 1;
            if(score <= clickedScore)
                $(this).show();
            else
                $(this).hide();
        });

        /* set score to parent */
        clickedScore = Math.floor(parent.attr("score")*1) + "." + clickedScore;
        parent.attr("score", clickedScore);

        /* set score to span */
        parent.find(".GGscore-span-score").html(clickedScore);

        // /* if clickedScore is 5, hide socreSub */
        // if(clickedScore == 5)
        //     parent.find(".GGscore-div-scoreSub").hide();
        // else
        //     parent.find(".GGscore-div-scoreSub").show();
    },

    /* 특정 페이지로 이동 */
    /*
        class="commonEvent-tag-hyperlink"
        hyperlink-viewmode="page"
        hyperlink="${Navigation.Page.F02OrderDetail}"
        ${model.getPk()}
     */
    hyperlink(e)
    {
        CommonEvent.Prj.hyperlink(e);
    },

    /* ========================== */
    /* 특정 버튼에 이벤트 부여 */
    /* ========================== */
    action(e)
    {
        /* class name */
        let className = "commonEvent-el-action";

        /* 클래스 선택 */
        let target = $(e.target);
        if(!target.hasClass(className))
            target = target.parents("."+className);

        /* =============== */
        /* 액션 종류에 따라서 이벤트 실행 */
        /* =============== */
        let action = target.attr("event_action");
        switch(action)
        {
            /* --------------- */
            /* 라커룸 벤 삭제 */
            /* --------------- */
            case "deleteLockerroomBan":
            {
                let ajaxData =
                {
                    OPTION  : "id",
                    ID      : target.attr("lockerroom_ban_id"),
                };
                Common.showProgress();
                setTimeout(function()
                {
                    Api.LockerroomBan.deleteByOption(ajaxData, "toast", "toast");
                    Common.hideProgress();

                    /* refresh */
                    Navigation.executeShow();
                }, ajaxDelayTime);
                break;
            }
        }
    },

    /* ========================== */
    /* 이미지뷰어 */
    /* ========================== */
    imgViewer(e)
    {
        /* class name */
        let className = "commonEvent-img-viewer";

        /* 클래스 선택 */
        let target = $(e.target);
        if(!target.hasClass(className))
            target = target.parents("."+className);

        /* =============== */
        /* 액션 종류에 따라서 이벤트 실행 */
        /* =============== */
        // let imgType = target.attr("img_type");
        // let src = target.attr("img_src");
        // let endSrc = "";
        // switch(imgType)
        // {
        //     case "user"     : endSrc = GGC.Cvrt.userImgOrigin(src); break;
        //     case "team"     : endSrc = GGC.Cvrt.teamImgOrigin(src); break;
        //     case "player"   : endSrc = GGC.Cvrt.playerImgOrigin(src); break;
        //     case "league"   : endSrc = GGC.Cvrt.leagueImgOrigin(src); break;
        // }

        let picPath = target.attr("pic_path");
        let src = GGC.MenuPic.picPath(picPath, true);

        $("#index-img-image").prop("src", src);
        $("#index-div-imageViewerMask").show();
        $("#index-div-imageViewer").show();
    },

    /* ========================== */
    /* 사이드메뉴 열기 */
    /* ========================== */
    openSidemenu(e)
    {
        GGsidemenu.open();
    }, /* end tab */

    /* ========================== */
    /* 페이지네이션 탭 */
    /* ========================== */
    tapPagenationBtn(e)
    {
        /* 클래스 선택 */
        let target = $(e.target);

        try
        {
            if(target[0].tagName != "DIV")
                target = target.parent("div");
        } catch(e)
        {
            Common.toast(e);
        }

        /* 클래스의 현재 탭 상태 */
        let tab = target.attr("tab");

        /* 제외처리 */
        if(tab == "space")
            return;

        /* attr update */
        if(tab == undefined || tab == "")
        {
            target.parent("div").find(".commonEvent-div-pagenationBtn").attr("tab", "");
            target.attr("tab", "tab");
        }
    }, /* end tab */

    /* ========================== */
    /* commonEvent-tbl-multitab 여러개의 td 를 다중으로 선택할 수 있다. */
    /*
        attributes
          [*] tab
            - tab : 현재 활성화된 탭
            - space : 공백을 담당하는 td
    */
    /* ========================== */
    multitab(e)
    {
        /* 클래스 선택 */
        let target = $(e.target);
        try
        {
            if(target[0].tagName != "TD")
                target = target.parent("td");
        }
        catch(e)
        {
            Common.toast(e);
        }

        /* 클래스의 현재 탭 상태 */
        let tab = target.attr("tab");

        /* 제외처리 */
        if(tab == "space")
            return;

        /* attr update */
        if(tab == undefined || tab == "")
            target.attr("tab", "tab");
        else
            target.attr("tab", "");

    }, /* end tab */


    /* ========================== */
    /* commonEvent-btn-tab : 탭 효과 */
    /*
        attributes
          [*] tab
            - tab : 현재 활성화된 탭
    */
    /* ========================== */
    btntab(e)
    {
        /* 클래스 선택 */
        let target = $(e.target);

        /* 클래스의 현재 탭 상태 */
        let tab = target.attr("tab");

        /* attr update */
        if(tab == undefined || tab == "")
            target.attr("tab", "tab");
        else
            target.attr("tab", "");
    },

    /* ========================== */
    /* commonEvent-tbl-common 라는 클래스를 가진 엘리먼트에 페이지 탭 효과를 부여한다. */
    /*
        attributes
          [*] tab
            - tab : 현재 활성화된 탭
            - space : 공백을 담당하는 td
    */
    /* ========================== */
    tab(e)
    {
        /* 클래스 선택 */
        let target = $(e.target);

        try
        {
            if(target[0].tagName != "TD")
            {
                target = target.parent("td");
            }
        } catch(e)
        {
            Common.toast(e);
        }

        /* 클래스의 현재 탭 상태 */
        let tab = target.attr("tab");

        /* 제외처리 */
        if(tab == "space")
            return;

        /* attr update */
        if(tab == undefined || tab == "")
        {
            target.parent("tr").parent("tbody").find("td[tab!=space]").attr("tab", "");
            target.attr("tab", "tab");
        }

        /* tag hide and show */
        let tabHide = target.attr("tab-hide");
        let tabShow = target.attr("tab-show");
        if(tabHide != undefined)
            $(tabHide).hide();
        if(tabShow != undefined)
            $(tabShow).show();

    }, /* end tab */

    /* ========================== */
    /* commonEvent-btn-radio 라는 클래스를 가진 엘리먼트에 라디오 효과를 부여함 */
    /*
        attributes
          [*] radio_name    : 키
          [*] tab           : ["tab", ""]
          [*] linked_div    : 링크된 div만 표시하고, tab div는 가린다.
    */
    /* ========================== */
    radio(e)
    {
        let target = $(e.target);
        if(!target.hasClass("commonEvent-btn-radio"))
        {
            target = target.parents(".commonEvent-btn-radio");
        }

        let radioName = target.attr("radio_name");
        let tab       = target.attr("tab");

        /* attr update */
        if(tab == "" || tab == undefined)
        {
            $(".commonEvent-btn-radio[radio_name="+radioName+"]").attr("tab", "");
            target.attr("tab", "tab");
        }

        /* do have linked_div attr */
        // if($(e.target).attr("linked_div") != undefined)
        // {
        //     let linkedDiv = $(e.target).attr("linked_div");
        //     $("."+radioName).each(function()
        //     {
        //         let loopLinkedDiv = $(this).attr("linked_div");
        //         if(loopLinkedDiv == linkedDiv)
        //             $("#"+linkedDiv).show();
        //         else if(loopLinkedDiv != undefined)
        //             $("#"+loopLinkedDiv).hide();
        //     });
        // }
    }, /* end radio */

    /* ========================== */
    /* common-tag-checkbox 라는 클래스를 가진 엘리먼트에 체크박스 효과를 부여함 */
    /*
        attributes
          [*] checkbox_mode    : ["single", "multi"]
            - single : 한 개만 선택할 수 있음 (라디오효과)
            - multi  : 여러개를 한 번에 선택할 수 있음 (체크박스효과)
          [*] checkbox_name    : 키
          [*] checkbox_checked : ["y", "n"]
          [*] code             : 페이지코드
    */
    /* ========================== */
    tagCheckbox(e)
    {
        /* 클래스 선택 */
        let target = $(e.target);
        if(!target.hasClass("common-tag-checkbox"))
        {
            target = target.parents(".common-tag-checkbox");
        }

        let checkboxMode    = target.attr("checkbox_mode");
        let checkboxName    = target.attr("checkbox_name");
        let checkboxChecked = target.attr("checkbox_checked");

        /* single 의 경우, 다른 모든 체크박스를 해제한다. */
        if(checkboxMode == "single")
        {
            $(".common-tag-checkbox[checkbox_name="+checkboxName+"]").attr("checkbox_checked", "n");
        }

        /* 선택된 상태라면 선택해제, 선택되지 않은상태라면 선택으로 전환 */
        if(checkboxChecked == "n")
            target.attr("checkbox_checked", "y");
        else
            target.attr("checkbox_checked", "n");

    }, /* end checkbox */

    /* ========================== */
    /* common-tr-checkTr (이)라는 클래스를 가진 엘리먼트에 체크박스 효과를 부여함 */
    /*
        attributes
          [*] checkbox_mode    : ["single", "multi"]
            - single : 한 개만 선택할 수 있음 (라디오효과)
            - multi  : 여러개를 한 번에 선택할 수 있음 (체크박스효과)
          [*] checkbox_name    : 키
          [*] checkbox_checked : ["y", "n"]
    */
    /* ========================== */
    checkTr(e)
    {
        /* class name */
        let className = "common-tr-checkTr";

        /* 클래스 선택 */
        let target = $(e.target);
        if(!target.hasClass(className))
        {
            target = target.parents("."+className);
        }

        let checkboxMode    = target.attr("checkbox_mode");
        let checkboxName    = target.attr("checkbox_name");
        let checkboxChecked = target.attr("checkbox_checked");

        /* single 의 경우, 다른 모든 체크박스를 해제한다. */
        if(checkboxMode == "single")
        {
            $("."+className+"[checkbox_name="+checkboxName+"]").attr("checkbox_checked", "n");
        }

        if(checkboxChecked == "n")
            target.attr("checkbox_checked", "y");
        else
            target.attr("checkbox_checked", "n");

    }, /* end checkbox */

    /* ========================== */
    /* pop - 각 페이지의 POP 페이지로 연결 */
    /*
        attributes
            [*] type : ['user', 'team', 'player', 'league', 'game']
            userno
            team_index
            player_index
            league_index
            game_index
    */
    /* ========================== */
    pop(e)
    {
        /* class name */
        let className = "commonEvent-btn-viewDetail";

        /* 클래스 선택 */
        let target = $(e.target);
        if(!target.hasClass(className))
            target = target.parents("."+className);

        /* set */
        let type = target.attr("type");
        switch(type)
        {
            case "lgg" :
            {
                let pageDat =
                {
                    lggId    : target.attr("lgg_id"),
                }
                // Navigation.moveFrontPage(pageDat);
                break;
            }
        }
    }, /* end checkbox */

    /* ========================== */
    /* 기록 표시 페이지에서, 기록을 클릭할 시, 토스트로 기록이 무엇인지 표시 */
    /*
        attributes
            [*] type   : ['b','p','f','r',] 타자, 투수, 수비수, 주자를 의미
            [*] record : 각 기록의 축약형
    */
    /* ========================== */
    toastRecord(e)
    {
        /* class name */
        let className = "common-th-record";

        /* 클래스 선택 */
        let target = $(e.target);
        if(!target.hasClass(className))
            target = target.parents("."+className);

        /* set vars */
        let type    = target.attr("type");
        let record  = target.attr("record");
        let rslt    = GGC.Custom.recordName(type,record); /* type, record 를 이용하여 기록의 정확한 의미를 자연어로 변경 */

        /* toast to user */
        Common.toastInfo(record+" : "+rslt);

    }, /* end checkbox */

    /* ========================== */
    /* subEntity-selectNavi-table 의 select 의 선택사항을 조정 (앞의 값을 선택하거나, 뒤의 값을 선택하거나) */
    /*
        attributes
            [*] type : ['minus','plus']
                - minus : 앞의 값을 선택한다.
                - plus  : 뒤의 값을 선택한다.
            [*] select_id : 연결되어있는 select의 id
    */
    /* ========================== */
    navigateSelect(e)
    {
        /* class name */
        let className = "subEntity-selectNavi-moveTD";

        /* 클래스 선택 */
        let target = $(e.target);
        if(!target.hasClass(className))
            target = target.parents("."+className);

        /* set let */
        let type        = target.attr("type");         /* 앞으로 갈 것인지, 뒤로 갈 것인지 */
        let selectId    = target.attr("select_id");    /* 연동되어 있는 select 엘리먼트의 id */
        let nowOption   = $("#"+selectId+" > option:selected").attr("num")*1;
        let nextOption  = 0;
        let errMsg      = "";

        /* main process */
        switch(type)
        {
            /* 앞으로 */
            case "minus":
            {
                nextOption = nowOption-1;
                errMsg = $.i18n("(common)oldest");
                break;
            }
            case "plus":
            {
                nextOption = nowOption+1;
                errMsg = $.i18n("(common)lastest");
            }
        }

        /* select nextOption */
        if($("#"+selectId+" > option[num="+nextOption+"]").length > 0)
        {
            let nextVal = $("#"+selectId+" > option[num="+(nextOption)+"]").val();
            $("#"+selectId).val(nextVal).change();
        }
        else
        {
            Common.toastInfo(errMsg);
        }

    }, /* end checkbox */


    /* ========================== */
    /* commonEvent-btn-plusMinus : 특정span의 값에 +1/-1 을 해줌. */
    /*
        attributes
            [*] event_plusminus_type : ['minus','plus']
            [*] event_plusminus_unit : int

        commonEvent-span-plusMinus
            [*] event_plusminus_min :
            [*] event_plusminus_now :
            [*] event_plusminus_max :

        코드 예

        <td>
            <span>선택할 수 있는 사이드메뉴 개수</span>
            <button
                class="commonEvent-btn-plusMinus common-btn-outline borderRound"
                event_plusminus_type="minus"
                event_plusminus_unit="1"
            >－</button>
            <span
                class="commonEvent-span-plusMinus"
                event_plusminus_min="1"
                event_plusminus_now="1"
                event_plusminus_max="2"
            >1</span>개
            <button
                class="commonEvent-btn-plusMinus common-btn-outline borderRound"
                event_plusminus_type="plus"
                event_plusminus_unit="1"
            >＋</button>
        </td>
    */
    /* ========================== */
    btnPlusMinus(e)
    {
        /* class name */
        let className = "commonEvent-btn-plusMinus";

        /* 클래스 선택 */
        let target = $(e.target);
        if(!target.hasClass(className))
            target = target.parents("."+className);

        /* set let */
        let type    = target.attr("event_plusminus_type");
        let unit    = target.attr("event_plusminus_unit")*1;
        let spanEl  = target.parent().find(".commonEvent-span-plusMinus");
        let min     = spanEl.attr("event_plusminus_min")*1;
        let now     = spanEl.attr("event_plusminus_now")*1;
        let max     = spanEl.attr("event_plusminus_max")*1;
        let minFailedMsg = spanEl.attr("event_plusminus_min_failedmsg");
        let maxFailedMsg = spanEl.attr("event_plusminus_max_failedmsg");

        /* validate spenEl */
        if(spanEl.length != 1)
        {
            Common.toastInfo("button s target is not exists");
            return;
        }

        /* main process */
        switch(type)
        {
            case "minus":
            {
                if(now - unit < min)
                {
                    if(minFailedMsg == null)
                        errMsg = $.i18n("(common)it is min");
                    else
                        errMsg = minFailedMsg;

                    Common.toast(errMsg);
                    now = min;
                }
                else
                    now -= unit;

                break;
            }
            case "plus":
            {
                if(now + unit > max)
                {
                    if(maxFailedMsg == null)
                        errMsg = $.i18n("(common)it is max");
                    else
                        errMsg = maxFailedMsg;

                    Common.toast(errMsg);
                    now = max;
                }
                else
                    now += unit;
            }
        }

        /* select nextOption */
        if(now >= min && now <= max)
        {
            spanEl.attr("event_plusminus_now", now);
            spanEl.html(now);
        }
    },

    /* ========================== */
    /* commonEvent-btn-round : 특정span의 값에 +1/-1 을 해줌. */
    /*
        attributes
            [*] btn_type : ['minus','plus']
                - minus : -1
                - plus  : +1
            [*] linked_span : 연결되어있는 span

        $(linked_span)
            [*] min :
            [*] max :
            [*] field : span에 값을 표현하기 전에, 특정 필드값에 맞춰서 변환.
    */
    /* ========================== */
    roundBtn(e)
    {
        /* class name */
        let className = "commonEvent-btn-round";

        /* 클래스 선택 */
        let target = $(e.target);
        if(!target.hasClass(className))
            target = target.parents("."+className);

        /* set let */
        let eventType   = target.attr("event_type");    /* 앞으로 갈 것인지, 뒤로 갈 것인지 */
        let spanEl      = target.attr("linked_span")    /* 연동되어있는 span */
            spanEl      = $(spanEl);
        let min         = spanEl.attr("min")*1;
        let max         = spanEl.attr("max")*1;
        let nowVal      = spanEl.attr("now_value")*1;
        let field       = spanEl.attr("field");

        /* main process */
        switch(eventType)
        {
            case "minus":
            {
                nowVal -= 1;
                errMsg = $.i18n("(common)it is min");
                break;
            }
            case "plus":
            {
                nowVal += 1;
                errMsg = $.i18n("(common)it is max");
            }
        }

        /* select nextOption */
        if(nowVal >= min && nowVal <= max)
        {
            spanEl.attr("now_value", nowVal);
            switch(field)
            {
                case "MLeagueRank.lr_rank": spanEl.html(GGC.Cvrt.lr_rank(nowVal)); break;
            }
        }
        else
        {
            Common.toastInfo(errMsg);
        }
    },
}

var GGC = {};
GGC.Common =
{
    /* ============================== */
    /* 기본 필드 */
    /* ============================== */
    default(value, def="")
    {
        if(value == null || value == "")
            return def;
        return value;
    },

    enum      (value, def="") { return GGC.Common.data(value, GGF.Server.FieldType.enum     , def); },
    char      (value, def="") { return GGC.Common.data(value, GGF.Server.FieldType.char     , def); },
    varchar   (value, def="") { return GGC.Common.data(value, GGF.Server.FieldType.varchar  , def); },
    date      (value, def="") { return GGC.Common.data(value, GGF.Server.FieldType.date     , def); },
    datetime  (value, def="") { return GGC.Common.data(value, GGF.Server.FieldType.datetime , def); },
    time      (value, def="") { return GGC.Common.data(value, GGF.Server.FieldType.time     , def); },
    bigint    (value, def="") { return GGC.Common.data(value, GGF.Server.FieldType.bigint   , def); },
    int       (value, def="") { return GGC.Common.data(value, GGF.Server.FieldType.int      , def); },
    tinyint   (value, def="") { return GGC.Common.data(value, GGF.Server.FieldType.tinyint  , def); },
    double    (value, def="") { return GGC.Common.data(value, GGF.Server.FieldType.double   , def); },
    float     (value, def="") { return GGC.Common.data(value, GGF.Server.FieldType.float    , def); },
    data(value, fieldtype="", def="")
    {
        if(value == null || value == "")
        {
            switch(fieldtype)
            {
                case GGF.Server.FieldType.enum      : def = def; break;
                case GGF.Server.FieldType.char      : def = def; break;
                case GGF.Server.FieldType.varchar   : def = def; break;
                case GGF.Server.FieldType.date      : def = def; break;
                case GGF.Server.FieldType.datetime  : def = def; break;
                case GGF.Server.FieldType.time      : def = def; break;
                case GGF.Server.FieldType.bigint    : if(def=="") def = 0; else def = def*1; break;
                case GGF.Server.FieldType.int       : if(def=="") def = 0; else def = def*1; break;
                case GGF.Server.FieldType.tinyint   : if(def=="") def = 0; else def = def*1; break;
                case GGF.Server.FieldType.double    : if(def=="") def = 0; else def = def*1; break;
                case GGF.Server.FieldType.float     : if(def=="") def = 0; else def = def*1; break;
            }
            return def;
        }
        switch(fieldtype)
        {
            case GGF.Server.FieldType.enum      : value = value; break;
            case GGF.Server.FieldType.char      : value = value; break;
            case GGF.Server.FieldType.varchar   : value = value; break;
            case GGF.Server.FieldType.date      : value = value; break;
            case GGF.Server.FieldType.datetime  : value = value; break;
            case GGF.Server.FieldType.time      : value = value; break;
            case GGF.Server.FieldType.bigint    : value = 1*value; break;
            case GGF.Server.FieldType.int       : value = 1*value; break;
            case GGF.Server.FieldType.tinyint   : value = 1*value; break;
            case GGF.Server.FieldType.double    : value = 1*value; break;
            case GGF.Server.FieldType.float     : value = 1*value; break;
        }
        return value;
    },
    num(value, def=0)
    {
        if(value == null || value == "")
            return def;
        return value*1;
    },

    /* ============================== */
    /* get short from str */
    /* ============================== */
    getShort(str, len=14)
    {
        if(str == null || str == "")
            return "";
        if(str.length > len)
            return str.substring(0, len) + "..";
        return str;
    },

    /* ============================== */
    /* 공통 enum */
    /* ============================== */
    yn(val) { return val == 'y' ? "Ｏ" : "Ｘ"; },

    /* ============================== */
    /* 가격 필드 */
    /* ============================== */
    pricePretty  (val=0) { return GGC.Common.numToHangul(val); },
    priceWon     (val=0) { return GGC.Common.comma(val) + "원"; },
    priceWonFont (val) { return `<span class="common-tag-font" font-color="${val >= 0 ? 'pstv' : 'ngtv'}">${GGC.Common.comma(val)}원</span>`; },
    priceHan     (val=0) { return GGC.Common.numToHangul(val); },

    /* 기호가 붙는 옵션가격 */
    optpriceWon(val=0)
    {
        let rslt = GGC.Common.comma(val) + "원";
        if(val >= 0)
            rslt = "+" + rslt;
        return rslt;
    },
    wonColor(val) { return `<span class="common-tag-font" font-color="${val >= 0 ? 'pstv' : 'ngtv'}">${GGC.Common.optpriceWon(val)}</span>`; }, /* Mark + Price + Won + Font */


    /* ============================== */
    /* 천 단위 콤마 */
    /* ============================== */
    comma(val=0)
    {
        let parsed = parseInt(val);
        let rslt = "";

        /* 숫자가 맞는지 확인 */
        /* 0 이면 hypen 을 리턴 */
        /* 1 이상이면 천 단위에 콤마를 붙임 */
        if(isNaN(parsed))
            rslt = val;
        else
            rslt = parsed.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

        return rslt;
    },


    /* ============================== */
    /* 숫자를 만원으로 */
    /* ============================== */
    toManwon(val=0)
    {
        let parsed = parseInt(val);
        let rslt = "";

        /* 숫자가 맞는지 확인 */
        /* 0 이면 hypen 을 리턴 */
        /* 1 이상이면 천 단위에 콤마를 붙임 */
        if(isNaN(parsed))
            rslt = val;
        else
        {
            parsed = Math.round(parsed / 10000 * 10) / 10;
            rslt = parsed.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        return rslt + "만";
    },

    /* ============================== */
    /* 숫자가 0이면 "-" 으로 표시한다. + 천 단위 콤마 */
    /* ============================== */
    numberFormat(val=0)
    {
        let parsed = parseInt(val);
        let rslt = "";

        /* 숫자가 맞는지 확인 */
        /* 0 이면 hypen 을 리턴 */
        /* 1 이상이면 천 단위에 콤마를 붙임 */
        if(isNaN(parsed))
            rslt = val;
        else if(parsed == 0)
            rslt = "-";
        else
            rslt = parsed.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

        return rslt;
    },

    /* ============================== */
    /* 숫자를 한글로 변환 */
    /* ============================== */
    numToHangul(val=0, won=true)
    {
        let num = parseInt(val);
        if(typeof num != 'number')
            return '숫자가 아님';

        let rslt           = "";
        let numStr         = num.toString();
        let lengthForPad   = numStr.length + (4-(numStr.length % 4));   // 만약에 9자리면 3을 더해서 "12"가 됨.
        let numStrWithZero = numStr.padStart(lengthForPad, '0');        // 9자리 인경우 12자리까지 앞에다 0을 채워줌
        let loopCount      = Math.round(numStrWithZero.length / 4);     // 12자리 인경우 3

        for(let i = 1; i <= loopCount; i++)
        {
            let rightMinusPoint = numStrWithZero.length - (i * 4);
            let nowSelected = numStrWithZero.substring(rightMinusPoint, rightMinusPoint+4);

            nowSelected = parseInt(nowSelected);
            if(nowSelected == 0)
                continue;

            nowSelected = this.comma(nowSelected);
            switch(i)
            {
                case 1: rslt = " "+rslt + nowSelected; break;
                case 2: rslt = " "+nowSelected+"만"+rslt; break;
                case 3: rslt = " "+nowSelected+"억"+rslt; break;
                case 4: rslt = " "+nowSelected+"조"+rslt; break;
            }
        }

        if(rslt == "")
            rslt = 0;

        if(won == true)
            rslt += "원";

        rslt = rslt.trim();
        return rslt;
    },

    /* ============================== */
    /* 날짜형식 가공 */
    /* ============================== */
    // datetime(str)
    // {
    //     let rslt = new Array();

    //     try
    //     {
    //         str = str.split(" ");
    //         let time = str[1];
    //         let dateObj = new Date(str[0]);
    //         let year    = dateObj.getFullYear();
    //         let month   = dateObj.getMonth()+1;
    //             month   = month < 10 ? `0${month}` : month;
    //         let date    = dateObj.getDate();
    //             date    = date < 10 ? `0${date}` : date;
    //         let day = dateObj.getDay();
    //         let dddd = "";
    //         switch(day)
    //         {
    //             case 0: dddd = $.i18n("(common)sun"); break;
    //             case 1: dddd = $.i18n("(common)mon"); break;
    //             case 2: dddd = $.i18n("(common)tue"); break;
    //             case 3: dddd = $.i18n("(common)wed"); break;
    //             case 4: dddd = $.i18n("(common)thu"); break;
    //             case 5: dddd = $.i18n("(common)fri"); break;
    //             case 6: dddd = $.i18n("(common)sat"); break;
    //         }

    //         /* 날짜에 오류가 있는지 체크 */
    //         if(isNaN(year) || isNaN(month) || isNaN(date))
    //         {
    //             rslt.push("-");
    //             rslt.push("-");
    //         }
    //         else
    //         {
    //             rslt.push(`${year}-${month}-${date} (${dddd})`);
    //             rslt.push(time);
    //         }
    //     }
    //     catch(e)
    //     {
    //         rslt.push("-");
    //         rslt.push("-");
    //     }
    //     return rslt;
    // },

    /* ============================== */
    /* 요일 */
    /* ============================== */
    getWeekday(str)
    {
        let rslt = "";
        try
        {
            let date = new Date(str);
            let day  = date.getDay();
            switch(day)
            {
                case 0: rslt = $.i18n("(common)sun"); break;
                case 1: rslt = $.i18n("(common)mon"); break;
                case 2: rslt = $.i18n("(common)tue"); break;
                case 3: rslt = $.i18n("(common)wed"); break;
                case 4: rslt = $.i18n("(common)thu"); break;
                case 5: rslt = $.i18n("(common)fri"); break;
                case 6: rslt = $.i18n("(common)sat"); break;
            }
        }
        catch(e)
        {
            console.error(e);
        }
        return rslt;
    },

    /* ============================== */
    /* 지역정보 표시 */
    /* ============================== */
    area(area1Name, area2Name)
    {
        let rslt = "";

        if(area1Name == "-" || area1Name == null)
            rslt = $.i18n('(GGC)area-null');
        else
            rslt = `${area1Name} ${area2Name}`;

        return rslt;
    },

    /* ============================== */
    /* 숫자가 0이면 "-" 으로 표시한다. */
    /* ============================== */
    zeroToHyphen(val=null)
    {
        /* 숫자인지 검사한다. */
        let isNum = GGvalid.Common.isNumber(val)['code'];

        /* 숫자가 아닐경우 그냥 리턴 */
        if(!isNum)
            return val;
        /* 0이면 하이픈을 리턴 */
        else if(val == 0)
            return "-";
        /* 0이 아니면 그냥 리턴 */
        else
            return val;

    }, /* zeroToHyphen */

    /* ============================== */
    /* 이미지 패스 설정 */
    /* ============================== */
    getImgPath(entity, key, img, origin=false)
    {
        let originPath = "";
        let src        = "";

        /* is origin? */
        if(origin == true)
            originPath = "-origin";

        /* get image path */
        if(img != undefined && img != "" && img != null)
            src = `${ServerInfo.getResourceHost()}/${entity}/${key}/${img}${originPath}.png`;
        else if(entity == "user", entity == "store")
            src = dummySrc;

        return src;
    },

    dateDiff(value) { return GGC.Common.datePretty(value); },
    datePretty(value)
    {
        if(value == null || value == "")
            return "-";

        const today = new Date();
        const timeValue = new Date(value);

        const betweenTime = Math.floor((today.getTime() - timeValue.getTime()) / 1000 / 60);
        if (betweenTime < 1) return '1분 이내';
        if (betweenTime < 60)
            return `${betweenTime}분 전`;

        const betweenTimeHour = Math.floor(betweenTime / 60);
        if (betweenTimeHour < 24)
            return `${betweenTimeHour}시간 전`;

        const betweenTimeDay = Math.floor(betweenTime / 60 / 24);
        if (betweenTimeDay < 31)
            return `${betweenTimeDay}일 전`;

        const betweenTimeMonth = Math.floor(betweenTime / 60 / 24 / 30);
        if (betweenTimeMonth < 12)
            return `${betweenTimeMonth}개월 전`;

        return `${Math.floor(betweenTimeDay / 365)}년 전`;
    },

};

GGC.Attr =
{
    getCommonSpanTag(val="", none="", blue="", yellow="", red="")
    {
        if     (val == none)   return `common-span-tag="none"`;
        else if(val == blue)   return `common-span-tag="blue"`;
        else if(val == yellow) return `common-span-tag="yellow"`;
        else if(val == red)    return `common-span-tag="red"`;
    }
}

var GGdate =
{
    format(str, format, ifnull="-")
    {
        if(Common.isEmpty(str))
            return ifnull;

        let d = GGdate.fromStr(str);
        if(d == null || isNaN(d))
            return ifnull;

        switch(format)
        {
            case "YYYY-MM-DD"          : return    GGdate.toYYYYMMDD(d);
            case "YYYY-MM-DD(dd)"      : return `${GGdate.toYYYYMMDD(d)}(${GGdate.getDDDD(d)})`;
            case "YYYY-MM-DD HH:II:SS" : return    GGdate.toYYYYMMDDHHIISS(d);
            case "YY.MM.DD(dd)"        : return    GGdate.toYYMMDDddot(d);
            case "YY.MM.DD(dd) HH:II"  : return    GGdate.toYYMMDDdHHIIdot(d);
            case "MM.DD(dd)"           : return    GGdate.toMMDDddot(d);
            case "YYYY.MM.DD HH:II"    : return `${GGdate.getYYYY(d)}.${GGdate.getMM(d)}.${GGdate.getDD(d)} ${GGdate.getHH(d)}:${GGdate.getII(d)}`;
            default:
                return str;
        }
    },

    /* 2024-xx-xx xx:xx:xx */
    /* 0123456789012345678 */
    fromStr(str)
    {
        if(Common.isEmpty(str))
            return null;

        let d = new Date();
        d.setFullYear   (str.substring(0,4));
        d.setMonth      (str.substring(5,7) * 1 - 1); // month is 0-indexed
        d.setDate       (str.substring(8,10));
        d.setHours      (str.substring(11,13));
        d.setMinutes    (str.substring(14,16));
        d.setSeconds    (str.substring(17,19));
        return d;
    },

    /* 2024-xx-xx xx:xx:xx */
    /* 0123456789012345678 */
    fromStrYMD(str)
    {
        if(Common.isEmpty(str))
            return null;

        let d = new Date();
        d.setFullYear   (str.substring(0,4));
        d.setMonth      (str.substring(5,7) * 1 - 1); // month is 0-indexed
        d.setDate       (str.substring(8,10));
        d.setHours      (0);
        d.setMinutes    (0);
        d.setSeconds    (0);
        return d;
    },
    getDdddFromYmd(str) { return GGdate.getDDDD(GGdate.fromStrYMD(str)); },

    /* 2024-xx-xxTxx:xx */
    /* 0123456789012345 */
    fromDatetimeLocal(str)
    {
        if(Common.isEmpty(str))
            return null;

        let d = new Date();
        d.setFullYear   (str.substring(0,4));
        d.setMonth      (str.substring(5,7) * 1 - 1); // month is 0-indexed
        d.setDate       (str.substring(8,10));
        d.setHours      (str.substring(11,13));
        d.setMinutes    (str.substring(14,16));
        return d;
    },

    formatHH(d)   { return d == null ? null : (d.getHours()   + '') .padStart(2,"0"); },
    formatII(d)   { return d == null ? null : (d.getMinutes() + '') .padStart(2,"0"); },
    formatHHII(d) { return d == null ? null : `${GGdate.formatHH(d)}:${GGdate.formatII(d)}`; },

    getToday()
    {
        let d = new Date();
        return GGdate.getYYYYMMDD(d);
    },

    plusDate(date, days) { date.setDate(date.getDate() + days); return date; },

    getYYYYMMDD(d)
    {
        let year   = String(d.getFullYear());
        let month  = String(d.getMonth() + 1).padStart(2, '0');
        let day    = String(d.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    },
    getYMDTHIS(d)
    {
        let year    = String(d.getFullYear());
        let month   = String(d.getMonth() + 1 ).padStart(2, '0');
        let day     = String(d.getDate()      ).padStart(2, '0');
        let hours   = String(d.getHours()     ).padStart(2, '0');
        let minutes = String(d.getMinutes()   ).padStart(2, '0');
        let seconds = String(d.getSeconds()   ).padStart(2, '0');
        return `${year}-${month}-${day}T${hours}:${minutes}:${seconds}`;
    },

    getYYYY             (d) { if(d == null) return ""; return d.getFullYear(); },
    getYY               (d) { if(d == null) return ""; return d.getFullYear().toString().slice(-2); },
    getMM               (d) { if(d == null) return ""; return String(d.getMonth() + 1 ).padStart(2, '0'); },
    getM                (d) { if(d == null) return ""; return String(d.getMonth() + 1 ); },
    getDD               (d) { if(d == null) return ""; return String(d.getDate()      ).padStart(2, '0'); },
    getD                (d) { if(d == null) return ""; return String(d.getDate()      ); },
    getHH               (d) { if(d == null) return ""; return String(d.getHours()     ).padStart(2, '0'); },
    getII               (d) { if(d == null) return ""; return String(d.getMinutes()   ).padStart(2, '0'); },
    getSS               (d) { if(d == null) return ""; return String(d.getSeconds()   ).padStart(2, '0'); },
    getDDDD             (d) { if(d == null) return ""; return ["日","月","火","水","木","金","土"][d.getDay()]; },
    toYMDDHI            (d) { if(d == null) return ""; return `${GGdate.getYYYY(d)}-${GGdate.getMM(d)}-${GGdate.getDD(d)} (${GGdate.getDDDD(d)}) ${GGdate.getHH(d)}:${GGdate.getII(d)}`; },
    toMMDDddot          (d) { if(d == null) return ""; return `${GGdate.getMM(d)}.${GGdate.getDD(d)}(${GGdate.getDDDD(d)})`; },
    toYYMMDDddot        (d) { if(d == null) return ""; return `${GGdate.getYY(d)}.${GGdate.getMM(d)}.${GGdate.getDD(d)}(${GGdate.getDDDD(d)})`; },
    toYYMMDDdHHIIdot    (d) { if(d == null) return ""; return `${GGdate.getYY(d)}.${GGdate.getMM(d)}.${GGdate.getDD(d)}(${GGdate.getDDDD(d)}) ${GGdate.getHH(d)}:${GGdate.getII(d)}`; },
    toYYYYMMDD          (d) { if(d == null) return ""; return `${GGdate.getYYYY(d)}-${GGdate.getMM(d)}-${GGdate.getDD(d)}`; },
    toYYYYMMDDHHIISS    (d) { if(d == null) return ""; return `${GGdate.getYYYY(d)}-${GGdate.getMM(d)}-${GGdate.getDD(d)} ${GGdate.getHH(d)}:${GGdate.getII(d)}:${GGdate.getSS(d)}`; },
    toMDdddd            (d) { if(d == null) return ""; return `${GGdate.getM(d)}/${GGdate.getD(d)}(${GGdate.getDDDD(d)})`; },

    isHolidayToday()
    {
        let date = new Date();
        let day  = date.getDay();

        /* sat || sun */
        if(day == 0 || day == 6)
            return true;

        /* holidays */
        let mRefHolidays = GGstorage.getHolidays();
        return mRefHolidays.isHoliday(date);
    },

    formatTimeToHHII(str)
    {
        let now = new Date();
        let strDate = new Date(`${now.getFullYear()}-${now.getMonth()+1}-${now.getDate()} ${str}`);
        let hh = ''+strDate.getHours();
        let mm = ''+strDate.getMinutes();
        if(hh.length < 2) hh = '0'+hh;
        if(mm.length < 2) mm = '0'+mm;
        return `${hh}:${mm}`;
    },

    /**
     * period format
     * @param {*} startdt
     * @param {*} closedt
     */
    period(startdt, closedt)
    {
        if(Common.isEmpty(startdt) || Common.isEmpty(closedt))
            return "-";

        /* to date class */
        startdt = GGdate.fromStr(startdt);
        closedt = GGdate.fromStr(closedt);

        /* is same date startdt, closedt? */
        let skipDate = false;
        if(
            startdt.getFullYear() === closedt.getFullYear() &&
            startdt.getMonth()    === closedt.getMonth() &&
            startdt.getDate()     === closedt.getDate()
        )
        {
            skipDate = true;
        }

        /* is 00 minutes both date? */
        let skipMinute = false;
        if(
            startdt.getMinutes() === 0 &&
            closedt.getMinutes() === 0
        )
        {
            skipMinute = true;
        }

        /* format */
        let rslt = GGdate.toYYMMDDddot(startdt);
        if      ( skipMinute) rslt += ` ${GGdate.getHH(startdt)}`;
        else if (!skipMinute) rslt += ` ${GGdate.getHH(startdt)}:${GGdate.getII(startdt)}`;

        if      ( skipDate &&  skipMinute) rslt += `-${GGdate.getHH(closedt)}시`;
        else if ( skipDate && !skipMinute) rslt += `-${GGdate.getHH(closedt)}:${GGdate.getII(closedt)}`;
        else if (!skipDate &&  skipMinute) rslt += ` ~ ${GGdate.toYYMMDDddot(closedt)} ${GGdate.getHH(closedt)}시`;
        else if (!skipDate && !skipMinute) rslt += ` ~ ${GGdate.toYYMMDDdHHIIdot(closedt)}`;

        /* return */
        return rslt;
    },

    /* e.g. getDaysBetweenDates( 22-Jul-2011, 29-jul-2011) => 7. */
    getDaysBetweenDates(d0, d1)
    {
        let msPerDay = 8.64e7;

        // Copy dates so don't mess them up
        if(d0 == null) d0 = new Date();
        if(d1 == null) d1 = new Date();

        let x0 = new Date(d0);
        let x1 = new Date(d1);

        // Set to noon - avoid DST errors
        x0.setHours(12,0,0);
        x1.setHours(12,0,0);

        // Round to remove daylight saving errors
        return Math.round( (x1 - x0) / msPerDay );
    },

    /* ========================= */
    /* e.g. getSecondsBetweenDates( 22-Jul-2011, 29-jul-2011) => 7. */
    /* ========================= */
    isInSecondsFromNow(dateStr, seconds) { return GGdate.getSecondsBetweenDates(GGdate.fromStr(dateStr), new Date()) <= seconds; },
    isIn5MinFromNow(dateStr) { return GGdate.isInSecondsFromNow(dateStr, 5 * 60); },
    isIn1DayFromNow(dateStr) { return GGdate.isInSecondsFromNow(dateStr, 24 * 60 * 60); },
    getSecondsBetweenDates(d0, d1)
    {
        let msPerSecond = 1e3;

        // Copy dates so don't mess them up
        d0 = d0 == null ? new Date() : new Date(d0);
        d1 = d1 == null ? new Date() : new Date(d1);

        // Round to remove daylight saving errors
        return Math.round( (d1 - d0) / msPerSecond );
    },

    /* param : div id to "YYYY-MM-DD HH:II" str */
    fromDivYearToMin(el)
    {
        let yyyymmdd = $(`${el} > input[type=date]`).val();
        let hh = $(`${el} > input[type=number]:nth-child(2)`).val();
        let ii = $(`${el} > input[type=number]:nth-child(3)`).val();
        if(Common.isEmpty(yyyymmdd) || Common.isEmpty(hh) || Common.isEmpty(ii))
            return null;

        hh = hh.padStart(2,"0"); // 0 ~ 9  => 00 ~ 09
        ii = ii.padStart(2,"0"); // 0 ~ 9  => 00 ~ 09

        let dateStr = `${yyyymmdd} ${hh}:${ii}:00`;
        let date = new Date(dateStr);
        if(isNaN(date))
            return null;

        return dateStr;
    },

    /* param : date obj to div's input  */
    toDivYearToMin(el, date=new Date())
    {
        if(date == null || isNaN(date))
            return;

        let yyyymmdd = GGdate.getYYYYMMDD(date);
        let hh = GGdate.getHH(date).padStart(2,"0");
        let ii = GGdate.getII(date).padStart(2,"0");

        $(`${el} > input[type=date]`).val(yyyymmdd);
        $(`${el} > input[type=number]:nth-child(2)`).val(hh);
        $(`${el} > input[type=number]:nth-child(3)`).val(ii);
    },

    /**
     * Determine whether the target date is within, passed, or upcoming the from-to date range.
     * @param {*} tg target
     * @param {*} fr from
     * @param {*} to to
     * @returns
     */
    getPointOfDate(tg, fr, to)
    {
        /* get date only */
        tg = new Date(tg.getFullYear(), tg.getMonth(), tg.getDate()).getTime();
        fr = new Date(fr.getFullYear(), fr.getMonth(), fr.getDate()).getTime();
        to = new Date(to.getFullYear(), to.getMonth(), to.getDate()).getTime();

        if(tg >= fr && tg <= to)
            return GGF.GGdate.PointOfDate.WITHIN;
        if(tg > fr)
            return GGF.GGdate.PointOfDate.PASSED;
        if(tg < to)
            return GGF.GGdate.PointOfDate.UPCOMING;
    },
}

var GGdialog =
{
    /* ==================== */
    /* 앞으로 이동 (페이지/다이어로그 -> 다이어로그) */
    /*
        page : 불러올 페이지의 실제 경로
     */
    /* ==================== */
    show(page)
    {
        /* 이미 다이어로그가 떠 있는 상태라면 페이크다이어로그를 끌어올린 후, 페이지 로드 */
        if(
            $("#index-dialog-container").hasClass("index-dialog-bringUp") ||
            $("#index-dialog-container").hasClass("index-dialog-bringUpFast")
        )
        {
            $("#index-dialog-containerFake").addClass("index-dialog-fakeBringUp");
            Common.showProgress();
            setTimeout(function()
            {
                $("#index-dialog-container").load(page, ()=>
                {
                    $("#index-dialog-containerFake").removeClass("index-dialog-fakeBringUp");
                    Common.hideProgress();
                });
            }, ajaxDelayTime);
            return;
        }

        /* 현재 다이어로그가 아니라면, 페이지 로드 후, 다이어로그 끌어올리기 */
        $("#index-dialog-container").load(page, ()=>
        {
            $("#index-dialog-mask").show();
            $("#index-dialog-container").removeClass("index-dialog-pullDown");
            $("#index-dialog-container").removeClass("index-dialog-pullDownFast");
            $("#index-dialog-container").addClass("index-dialog-bringUp");
        });
    },

    /* ==================== */
    /* 다이어로그에서 다이어로그로 뒤로가기 */
    /*
        page : 불러올 페이지의 실제 경로
     */
    /* ==================== */
    moveBack(page)
    {
        Common.showProgress();

        /* == 1 == */
        $("#index-dialog-container").addClass("index-dialog-pullDownFast");
        $("#index-dialog-container").load(page, ()=>
        {
            $("#index-dialog-container").removeClass("index-dialog-pullDownFast");
            $("#index-dialog-container").addClass("index-dialog-bringUpFast");
        });
    },

    /* ==================== */
    /* 다이어로그 닫기 */
    /* ==================== */
    hide()
    {
        /* 이미 다이어로그가 숨겨져 있는 상태라면 스킵 */
        if(
            $("#index-dialog-container").hasClass("index-dialog-pullDown") ||
            $("#index-dialog-container").hasClass("index-dialog-pullDownFast")
        )
            return;

        /* 다이어로그 끌어내리기 */
        $("#index-dialog-mask").hide();
        $("#index-dialog-container").removeClass("index-dialog-bringUp");
        $("#index-dialog-container").removeClass("index-dialog-bringUpFast");
        $("#index-dialog-container").addClass("index-dialog-pullDown");

        /* 내용 삭제 */
        $("#index-dialog-container").html("");

        /*  */
        Navigation.executeShow();
    },

}

/* =============== */
/* 뒤로가기 */
/* =============== */
class GGbackbtnHtml extends HTMLElement
{
    connectedCallback()
    {
        this.innerHTML =
        `
            <span style="fill:rgb(0,0,0); padding:0px 12px; height:100%; margin-left:6px;">
                <svg
                    style="display:inline-flex; line-height:52px;"
                    width="16px"
                    height="16px"
                    viewBox="0 0 16 16"
                    version="1.1"
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                >
                    <defs></defs>
                    <g stroke="none" stroke-width="1" fill-rule="evenodd">
                        <g transform="translate(-32.000000, -32.000000)" fill-rule="nonzero">
                            <polygon id="md-back-button-icon" points="48 39 35.83 39 41.42 33.41 40 32 32 40 40 48 41.41 46.59 35.83 41 48 41"></polygon>
                        </g>
                    </g>
                </svg>
            </span>
        `;
    }
}
customElements.define('gg-backbtn', GGbackbtnHtml);

/* =============== */
/* 뒤로가기 (심플) */
/* =============== */
class GGbackbtnSimpleHtml extends HTMLElement
{
    connectedCallback()
    {
        this.innerHTML =
        `
            <span style="fill:rgb(2,6,84); padding:0px; height:100%;">
                <svg
                    style="display:inline-flex; line-height:56px;"
                    width="16px"
                    height="16px"
                    viewBox="0 0 16 16"
                    version="1.1"
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                >
                    <defs></defs>
                    <g stroke="none" stroke-width="1" fill-rule="evenodd">
                        <g transform="translate(-32.000000, -32.000000)" fill-rule="nonzero">
                            <polygon id="md-back-button-icon" points="48 39 35.83 39 41.42 33.41 40 32 32 40 40 48 41.41 46.59 35.83 41 48 41"></polygon>
                        </g>
                    </g>
                </svg>
            </span>
        `;
    }
}
customElements.define('gg-backbtn-simple', GGbackbtnSimpleHtml);

/* =============== */
/* 카트 */
/* =============== */
class GGcartHtml extends HTMLElement
{
    connectedCallback()
    {
        this.innerHTML =
        `
            <diV class="common-div-cartFab">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    fill="rgb(255,255,255)"
                    viewBox="0 0 24 24"
                >
                    <path d="M16.53 7l-.564 2h-15.127l-.839-2h16.53zm-14.013 6h12.319l.564-2h-13.722l.839 2zm5.983 5c-.828 0-1.5.672-1.5 1.5 0 .829.672 1.5 1.5 1.5s1.5-.671 1.5-1.5c0-.828-.672-1.5-1.5-1.5zm11.305-15l-3.432 12h-13.017l.839 2h13.659l3.474-12h1.929l.743-2h-4.195zm-6.305 15c-.828 0-1.5.671-1.5 1.5s.672 1.5 1.5 1.5 1.5-.671 1.5-1.5c0-.828-.672-1.5-1.5-1.5z"/>
                </svg>
            </div>
        `;
    }
}
customElements.define('gg-cart', GGcartHtml);

/* =============== */
/* 리뷰 스코어 */
/* =============== */
class GGscoreHtml extends HTMLElement
{
    connectedCallback()
    {
        this.innerHTML =
        `
            <div class="GGscore-div-top" style="text-align:center; padding-top:0.5em;">
                <div style="display:block; margin-bottom:0.3em;">
                    <svg class="commonEvent-svg-GGscore"    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="rgb(220,220,220)" style=""                 score="1" score-type="gray"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>
                    <svg class="commonEvent-svg-GGscore"    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#ffd700"          style="display:none;"    score="1" score-type="gold"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>
                    <svg class="commonEvent-svg-GGscore"    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="rgb(220,220,220)" style=""                 score="2" score-type="gray"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>
                    <svg class="commonEvent-svg-GGscore"    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#ffd700"          style="display:none;"    score="2" score-type="gold"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>
                    <svg class="commonEvent-svg-GGscore"    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="rgb(220,220,220)" style=""                 score="3" score-type="gray"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>
                    <svg class="commonEvent-svg-GGscore"    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#ffd700"          style="display:none;"    score="3" score-type="gold"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>
                    <svg class="commonEvent-svg-GGscore"    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="rgb(220,220,220)" style=""                 score="4" score-type="gray"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>
                    <svg class="commonEvent-svg-GGscore"    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#ffd700"          style="display:none;"    score="4" score-type="gold"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>
                    <svg class="commonEvent-svg-GGscore"    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="rgb(220,220,220)" style=""                 score="5" score-type="gray"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>
                    <svg class="commonEvent-svg-GGscore"    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#ffd700"          style="display:none;"    score="5" score-type="gold"><path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z"/></svg>
                </div>
                <div class="GGscore-div-scoreSub" style="display:none;">
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="rgb(220,220,220)"  style=""                score="1" score-type="gray"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="#ffd700"           style="display:none;"   score="1" score-type="gold"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="rgb(220,220,220)"  style=""                score="2" score-type="gray"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="#ffd700"           style="display:none;"   score="2" score-type="gold"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="rgb(220,220,220)"  style=""                score="3" score-type="gray"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="#ffd700"           style="display:none;"   score="3" score-type="gold"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="rgb(220,220,220)"  style=""                score="4" score-type="gray"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="#ffd700"           style="display:none;"   score="4" score-type="gold"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="rgb(220,220,220)"  style=""                score="5" score-type="gray"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="#ffd700"           style="display:none;"   score="5" score-type="gold"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="rgb(220,220,220)"  style=""                score="6" score-type="gray"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="#ffd700"           style="display:none;"   score="6" score-type="gold"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="rgb(220,220,220)"  style=""                score="7" score-type="gray"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="#ffd700"           style="display:none;"   score="7" score-type="gold"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="rgb(220,220,220)"  style=""                score="8" score-type="gray"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="#ffd700"           style="display:none;"   score="8" score-type="gold"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="rgb(220,220,220)"  style=""                score="9" score-type="gray"><rect width="14" height="30"/></svg>
                    <svg class="commonEvent-svg-GGscoreSub" xmlns="http://www.w3.org/2000/svg" width="14" height="30" viewBox="0 0 14 30" fill="#ffd700"           style="display:none;"   score="9" score-type="gold"><rect width="14" height="30"/></svg>
                </div>
                <span class="GGscore-span-score common-span-strong common-span-block"></span>
                <div>
                    <span class="GGscore-div-announce common-span-block" score="1" style="display:none;">도저히 견딜 수 없습니다.</span>
                    <span class="GGscore-div-announce common-span-block" score="2" style="display:none;">만족스럽지 못합니다.</span>
                    <span class="GGscore-div-announce common-span-block" score="3" style="display:none;">괜찮았습니다.</span>
                    <span class="GGscore-div-announce common-span-block" score="4" style="display:none;">만족스럽습니다.</span>
                    <span class="GGscore-div-announce common-span-block" score="5" style="display:none;">아주 만족스럽습니다.</span>
                </div>
            </div>
        `;
    }

    getScore()
    {
        return $(this).attr("score");
    }

    setScore(score)
    {
        let bigScore   = Math.floor(score*1);
        let smallScore = Math.ceil(((score*1) - bigScore) * 10);
        $(this).find(`.commonEvent-svg-GGscore[score-type=gray][score=${bigScore}]`).click();

        if(smallScore > 0)
            $(this).find(`.commonEvent-svg-GGscoreSub[score-type=gray][score=${smallScore}]`).click();
    }
}
customElements.define('gg-score', GGscoreHtml);

/* =============== */
/* 라이더 */
/* =============== */
class IconRiderHtml extends HTMLElement
{
    connectedCallback()
    {
        this.innerHTML =
        `
            <div class="common-div-svgTop">
                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="${size}" height="${size}" viewBox="0 0 ${size*2} ${size*2}" style="padding:5px;">
                    <path d="M 103 20 C 98 20 94 24 94 29 C 94 34 98 38 103 38 C 104.7 38 106 36.7 106 35 L 106 23 C 106 21.3 104.7 20 103 20 z M 4 24 C 2.3 24 1 25.3 1 27 L 1 57 C 1 58.7 2.3 60 4 60 L 34 60 C 35.7 60 37 58.7 37 57 L 37 27 C 37 25.3 35.7 24 34 24 L 24 24 C 23.4 24 23 24.4 23 25 L 23 31 C 23 31.6 22.6 32 22 32 L 16 32 C 15.4 32 15 31.6 15 31 L 15 25 C 15 24.4 14.6 24 14 24 L 4 24 z M 74 36 C 72.3 36 71 37.3 71 39 C 71 40.7 72.3 42 74 42 L 84 42 L 84 106 L 66 106 L 66 69 L 66 62 C 66 57.6 62.4 54 58 54 L 43 54 C 41.3 54 40 55.3 40 57 C 40 58.7 41.3 60 43 60 L 58 60 C 59.1 60 60 60.9 60 62 L 60 66 L 4 66 C 2.3 66 1 67.3 1 69 C 1 70.7 2.3 72 4 72 L 26.599609 72 C 20.999609 79.3 12.000781 93.5 11.800781 109 C 11.800781 110.7 13.100781 112 14.800781 112 L 14.900391 112 L 20.199219 112 C 21.599219 120.5 29.000391 127 37.900391 127 C 46.800391 127 54.199609 120.5 55.599609 112 L 93.900391 112 C 102.40039 111.9 110.29922 107.8 115.19922 101 L 121.09961 92.699219 C 121.59961 91.999219 121.79961 91.100781 121.59961 90.300781 C 121.39961 89.500781 120.89922 88.800781 120.19922 88.300781 C 116.49922 86.200781 112.29961 85.099609 108.09961 85.099609 C 100.79961 85.099609 94.300391 88.300781 89.900391 93.300781 L 89.900391 42.599609 C 92.700391 43.699609 94.799219 46.200781 95.199219 49.300781 L 99 79.400391 C 99.2 81.000391 100.70078 82.2 102.30078 82 C 103.90078 81.8 105.10039 80.299219 104.90039 78.699219 L 101.09961 48.599609 C 100.29961 41.399609 94.2 36 87 36 L 74 36 z M 34.300781 72 L 60 72 L 60 106 L 18 106 C 19.5 90.1 31.100781 75.7 34.300781 72 z M 108.19922 91.199219 C 110.29922 91.199219 112.30078 91.599219 114.30078 92.199219 L 110.5 97.599609 C 106.8 102.79961 100.59961 105.99961 94.099609 106.09961 L 90.300781 106.09961 C 91.700781 97.599609 99.199219 91.199219 108.19922 91.199219 z M 122.30273 102.11328 C 122.10234 102.12129 121.89922 102.14922 121.69922 102.19922 C 120.09922 102.59922 119.09961 104.30039 119.59961 105.90039 C 119.99961 107.30039 120.10039 108.80078 119.90039 110.30078 C 119.50039 113.50078 118 116.30078 115.5 118.30078 C 113 120.30078 109.89922 121.20039 106.69922 120.90039 C 103.69922 120.60039 100.9 119.10078 99 116.80078 C 97.9 115.50078 96.000781 115.4 94.800781 116.5 C 93.500781 117.6 93.4 119.49922 94.5 120.69922 C 97.5 124.09922 101.59961 126.30078 106.09961 126.80078 C 106.79961 126.90078 107.49961 126.90039 108.09961 126.90039 C 112.19961 126.90039 116.10078 125.5 119.30078 123 C 123.10078 120 125.40039 115.70039 125.90039 110.90039 C 126.10039 108.70039 126.00039 106.40078 125.40039 104.30078 C 125.05039 102.98828 123.70547 102.05723 122.30273 102.11328 z M 26.400391 112 L 49.599609 112 C 48.299609 117.2 43.6 121 38 121 C 32.4 121 27.700391 117.2 26.400391 112 z"></path>
                </svg>
            <div>
        `;
    }
}
customElements.define('gg-icon-rider', IconRiderHtml);

var GGpage =
{
    setCartFab()
    {
        ApiPr.Cart.selectByExecutor()
        .done(function(mCarts)
        {
            if(mCarts.models.length == 0)
            {
                // Common.toastInfo("has no models");
            }
        });
    }
}

var GGslideform =
{
    next: function(el)
    {
        let elOpen = $(el).find(".common-div-slideformChild[slideform-status='open']");
        let elNext = $(elOpen.attr("slideform-next"));
        let elProg = $(elNext.attr("slideform-progress"));
        let elProgVal = elNext.attr("slideform-progressval");
        elOpen.attr("slideform-status", "exit");
        elNext.attr("slideform-status", "open");
        elProg.css("width", elProgVal + "%");
    },

    prev: function(el)
    {
        let elOpen = $(el).find(".common-div-slideformChild[slideform-status='open']");
        let elPrev = $(elOpen.attr("slideform-prev"));
        let elProg = $(elPrev.attr("slideform-progress"));
        let elProgVal = elPrev.attr("slideform-progressval");
        elOpen.attr("slideform-status", "exit");
        elPrev.attr("slideform-status", "open");
        elProg.css("width", elProgVal + "%");
    },


};

var GGtoast =
{
    timeDeft: 3000,
    timeLeft: 0,
    show(msg)
    {
        const toastEl = $("#index-div-toast");
        toastEl.text(msg);
        toastEl.addClass("show");

        GGtoast.timeLeft += GGtoast.timeDeft;
        setTimeout(() =>
        {
            GGtoast.timeLeft -= GGtoast.timeDeft;
            if (GGtoast.timeLeft > 0)
                return;

            toastEl.removeClass("show");
        }, GGtoast.timeDeft);
    },
};


var GGutils =
{
    Img :
    {
        getIconUrl()     { return ServerInfo.getScriptHost() + "/source/common/res/icon"; },
        getHeartEmpty()  { return GGutils.Img.getIconUrl() + "/heart-empty.png"; },               /* GGutils.Img.getHeartEmpty() */
        getHeartFilled() { return GGutils.Img.getIconUrl() + "/heart-filled.png"; },              /* GGutils.Img.getHeartFilled() */
        getCart()        { return GGutils.Img.getIconUrl() + "/cart.svg"; },                      /* GGutils.Img.getCart() */
    },
    Navigation :
    {
        /* GGutils.Navigation.makeGetFromObject() */
        makeGetFromObject(obj)
        {
            let param = "";
            for (const [key, value] of Object.entries(obj))
            {
                if(param != "")
                    param += "&";

                param += `${key}=${value}`;
            }
            if(param != "")
                param = "?"+param;

            return param;
        }
    },
    Dist :
    {
        getDist(lat1, lon1, lat2, lon2)
        {
            lat1 = lat1*1;
            lon1 = lon1*1;
            lat2 = lat2*1;
            lon2 = lon2*1;
            const R = 6378; // 지구 반지름 (단위: km)
            const dLat = GGutils.Dist.deg2rad(lat2 - lat1);
            const dLon = GGutils.Dist.deg2rad(lon2 - lon1);
            const a =
                Math.sin(dLat/2) *
                Math.sin(dLat/2) +
                Math.cos(GGutils.Dist.deg2rad(lat1)) *
                Math.cos(GGutils.Dist.deg2rad(lat2)) *
                Math.sin(dLon/2) *
                Math.sin(dLon/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            const distance = R * c; // 두 지점 간의 거리 (단위: km)
            return distance;
        },
        deg2rad(deg)
        {
            return deg * (Math.PI/180);
        },
        distPretty(dist)
        {
            if(dist < 1)
                return Math.round(dist*1000) + "m";
            else
                return Math.round(dist, 1) + "km";
        }
    },
}

var Navigation =
{
    /* ================== */
    /* 이전 페이지에서 받은 데이터나, 뒤로가기를 실시 했을 때의 복구 데이터 호출 */
    /* ================== */
    getPageParam()
    {
        let rslt = null;
        let data = GGstorage.getPageStack();

        /* 복구할 데이터가 존재하는지? */
        if(data != null && data.length > 0)
        {
            let lastData = data[data.length-1];
            if(lastData.data != undefined)
                rslt = lastData.data;
        }
        return rslt;
    },

    /* ================== */
    /*
        이전 페이지의 데이터를 가져온다
        code가 공란이면, 가장 최근페이지를 가져온다
     */
    /* ================== */
    getPageData(code=null)
    {
        let rslt = null;
        let data = GGstorage.getPageStack();

        /* 데이터가 없을경우 */
        if(data == null)
            return null;

        /* 복구할 데이터가 존재하는지? */
        if(code == null)
        {
            let lastData = data[data.length-2];
            if(lastData.data != undefined)
                rslt = lastData.data;
        }
        else
        {
            for(let i = data.length-1; i >= 0; i--)
            {
                let dat  = data[i];
                if(dat.page == code)
                {
                    rslt = dat.data;
                    break;
                }
            }
        }
        return rslt;
    },

    /* ================== */
    /*
        이전 페이지의 데이터를 재정의한다.
        code가 공란이면, 가장 최근페이지의 데이터를 업데이트한다.
     */
    /* ================== */
    setPageData(code=null, updateData={})
    {
        let data = GGstorage.getPageStack();

        /* 데이터가 없을경우 */
        if(data == null)
            return false;

        /* 복구할 데이터가 존재하는지? */
        if(code == null)
        {
            data[data.length-2].data = updateData;
        }
        else
        {
            for(let i = data.length-1; i >= 0; i--)
            {
                let dat  = data[i];
                if(dat.page == code)
                {
                    dat.data = updateData;
                    break;
                }
            }
        }
        GGstorage.setPageStack(data);
        return true;
    },

    /* ============================== */
    /* 마지막 pageData 삭제 */
    /* ============================== */
    removeLastPageData()
    {
        let pageStack = GGstorage.getPageStack();
        pageStack.splice(pageStack.length-1);
        GGstorage.setPageStack(pageStack);
    },

    /* ================== */
    /* get last pagecode */
    /* ================== */
    getLastPagecode()
    {
        let pageStack = GGstorage.getPageStack();
        let code = "";
        if(pageStack[pageStack.length-1] != undefined)
            code = pageStack[pageStack.length-1].page;
        return code;
    },

    /* ================== */
    /* get last viewMode */
    /* ================== */
    getLastViewMode()
    {
        let pageStack = GGstorage.getPageStack();
        let viewMode = "";
        if(pageStack == null || pageStack.length == 0)
            return viewMode;

        if(pageStack[pageStack.length-1] != undefined)
            viewMode = pageStack[pageStack.length-1].data.viewMode;
        return viewMode;
    },

    /* ================== */
    /* 다음 페이지로 이동 (페이지 혹은 다이어로그) */
    /*
        viewMode            : 다음에 이동할 페이지가 어떤 형태인지?
        movePage            : 이동하고자하는 페이지의 코드
        nextPageParam       : 이동하고자하는 페이지에 넘길 데이터
        url                 : return url only 웹 페이지의 경우에만 사용 (웹 페이지는 SPA)
     */
    /* ================== */
    moveFrontPage   (movePage, nextPageParam, url=false) { Navigation.moveFront("page"   , movePage, nextPageParam, url); },
    moveFrontDialog (movePage, nextPageParam, url=false) { Navigation.moveFront("dialog" , movePage, nextPageParam, url); },
    moveFront(viewMode=null, movePage, nextPageParam={}, url=false)
    {
        /* vars */
        let pageStack = GGstorage.getPageStack();

        /* --------------- */
        /* (모바일 전용) 혹시 러닝중이면 1초뒤에 다시 실시 */
        /* --------------- */
        if($("#index-dom").attr("_is-running") == "true")
        {
            setTimeout(function()
            {
                Navigation.moveFront(viewMode, movePage, nextPageParam);
            }, 1000);
            return;
        }

        /* --------------- */
        /* viewMode */
        /* --------------- */
        /* viewMode 가 null 이면 가장 마지막 데이터에서 viewMode를 불러옴 */
        let lastViewMode = Navigation.getLastViewMode();
        if(viewMode == null)
            viewMode = lastViewMode;

        /* 마지막 페이지가 dialog 라면 다음 이동도 dialog로 처리한다. */
        if(lastViewMode == "dialog")
            viewMode = "dialog";

        /*
            다음 페이지로 이동하려는 viewMode 가 dialog 면서,
            하이퍼링크로 인해서 띄워져 있는 페이지를 다시 부르게 될 것이라면,
            페이지로 이동한다.
        */
        if(viewMode == "dialog")
        {
            /* pageStack 에서 마지막으로 viewMode 가 page 인 배열을 찾는다 */
            let lastPageCode = null;
            for(let i in pageStack)
            {
                let dat = pageStack[i];
                if(dat.data.viewMode == "page")
                    lastPageCode = dat.page;
            }
            if(lastPageCode == movePage)
                viewMode = "page";
        }

        /* --------------- */
        /* 현재 페이지의 선택사항을 저장 */
        /* --------------- */
        if(pageStack != null && pageStack.length > 0)
        {
            let lastestStack     = pageStack[pageStack.length - 1];
            let lastestPage      = lastestStack.page;          /* 현재 페이지의 코드 */
            let lastestViewMode  = lastestStack.data.viewMode; /* 현재 페이지의 viewMode */

            /* 현재 페이지의 선택사항과, viewMode을 저장 */
            let nowPageData = Navigation.getData(lastestPage);
            pageStack[pageStack.length - 1].data          = nowPageData;
            pageStack[pageStack.length - 1].data.viewMode = lastestViewMode;
        }
        else
        {
            pageStack = new Array();
        }

        /* --------------- */
        /* 다음 페이지와 이전 페이지가 동일하다면, 리프레쉬 후 종료 */
        /* --------------- */
        if(pageStack.length > 0 && pageStack[pageStack.length-1].page == movePage)
        {
            /* 현재 페이지의 선택사항을 저장 */
            let nowPageData = Navigation.getData(movePage);
            pageStack[pageStack.length - 1].data          = nowPageData;
            pageStack[pageStack.length - 1].data.viewMode = viewMode;

            /* 페이지의 show 함수를 실행 */
            Navigation.executeShow();
            return;
        }

        /* --------------- */
        /* 다음페이지에 전달할 파라미터를 저장 */
        /* --------------- */
        nextPageParam.viewMode = viewMode;
        nextPageParam.page     = movePage;
        let stack =
        {
            page: movePage,
            data: nextPageParam,
        };
        pageStack.push(stack);
        GGstorage.setPageStack(pageStack);

        /* --------------- */
        /* 실제적인 페이지 이동 */
        /* 페이지 이동에 대한 도큐먼트 : https://docs.google.com/spreadsheets/d/1aWIXsFjJcQ5Jqz1M6YfEBXO1itWStmLfkLnK6FO0IYg/edit#gid=2037633440 */
        /* --------------- */
        switch(viewMode)
        {
            case "page":
            {
                /* 다음 이동해야할 페이지의 viewMode이 "page"라면, 다이어로그는 숨긴다. */
                if(lastViewMode == "dialog")
                    GGdialog.hide();

                $('#index-dom')[0].pushPage(Navigation.getURL(movePage), {"animation": "slide"}).then(function()
                {
                    $("#index-dom > ons-page[load=y]").remove();
                    $("#index-dom > ons-page[id="+movePage+"]").attr("load", "y");
                });
                break;
            }
            case "dialog":
            {
                GGdialog.show(Navigation.getURL(movePage));
                break;
            }
        } /* end case (viewMode) */
        console.log(pageStack);
    },

    /* ================== */
    /* 뒤로가기 : pageStack에서 현재 페이지의 배열을 삭제하고, 바로 이전 배열의 페이지로 이동한다. */
    /* ================== */

    /* 여러 단계 뒤로가기 : pageStack에서 현재 페이지의 배열을 삭제하고, 지정한 단계만큼 이전 배열의 페이지로 이동한다. */
    moveBackTwice() { Navigation.moveBackMultiple(2); },
    moveBackMultiple(count=1)
    {
        /* 2개 이하면 의미 없음 */
        if(count < 2)
            return Navigation.moveBack();

        /* 현재 배열만 남기고, 지정한 단계만큼 배열을 삭제 후, 뒤로가기 */
        /* 자연스럽게 현재 배열은 moveBack으로 인하여 삭제됨 */
        let pageStack = GGstorage.getPageStack();
        pageStack.splice(pageStack.length - count, count - 1);
        GGstorage.setPageStack(pageStack);
        Navigation.moveBack();
    },

    /* 뒤로가기 : pageStack에서 현재 페이지의 배열을 삭제하고, 바로 이전 배열의 페이지로 이동한다. */
    moveBack(param={})
    {
        /* (모바일 전용) 혹시 러닝중이면 1초뒤에 다시 실시 */
        if($("#index-dom").attr("_is-running") == "true")
        {
            setTimeout(function()
            {
                Navigation.moveBack();
            }, 1000);
            return;
        }

        /* ---------- */
        /* 변수설정 && 복귀페이지 설정 */
        /* ---------- */
        let pageStack = GGstorage.getPageStack();
        let lastPageStack = pageStack.pop();
        let lastPageViewMode = lastPageStack.data.viewMode;

        /* 현재페이지의 데이터를 빼낸 후의 스택을 저장 */
        GGstorage.setPageStack(pageStack);

        /* 복귀해야할 페이지의 정보 (모바일에서는 pageStack.length 가 0이 되는일은 없다.) */
        let page     = null;
        let viewMode = null;
        let movePage = null;
        if(pageStack.length > 0)
        {
            page     = pageStack[pageStack.length-1];
            viewMode = page.data.viewMode;              /* 복귀해야할 페이지의 viewMode */
            movePage = page.page;                       /* 복귀해야할 페이지코드 */
        }

        /* ---------- */
        /* 실제적인 페이지 이동 */
        /* 현재 페이지의 viewMode에 따라, 뒤로가기의 액션은 달라진다. */
        /* ---------- */
        switch(lastPageViewMode)
        {
            case "page":
            {
                if(viewMode == "page")
                {
                    /* 이미 페이지가 엘리먼트로 존재하면, bringPageTop 함수를 사용 */
                    if($("#"+movePage).length > 0)
                    {
                        $('#index-dom')[0].bringPageTop(Navigation.getURL(movePage), {"animation": "lift"}).then(function()
                        {
                            $("#index-dom > ons-page[id!="+movePage+"]").remove();
                            Navigation.executeShow();
                        });
                    }
                    else
                    {
                        $('#index-dom')[0].pushPage(Navigation.getURL(movePage), {"animation": "lift"}).then(function()
                        {
                            $("#index-dom > ons-page[id!="+movePage+"]").remove();
                        });
                    }
                }
                else if(viewMode == "dialog")
                {
                    GGdialog.show(Navigation.getURL(movePage));
                }
                break;
            } /* 뒤로가기를 하기 전, 현재 페이지의 viewMode이 "page" 인경우. */

            /* 뒤로가기를 하기 전, 현재의 페이지가 "dialog"의 타입일 경우 */
            case "dialog":
            {
                if(viewMode == "page")
                {
                    /* 다이어로그 숨기기 */
                    GGdialog.hide();
                }
                else if(viewMode == "dialog")
                {
                    GGdialog.moveBack(Navigation.getURL(movePage));
                }
                break;
            } /* 뒤로가기를 하기 전, 현재 페이지의 viewMode이 "dialog" 인경우. */
        } /* 현재 페이지의 viewMode에 따라, 뒤로가기의 액션은 달라진다. */
        console.log(pageStack);
    },
}

var GGvalid =
{

};

/* ================================ */
/* 서버에서 받아온 API에서 성공여부와, 데이터가 존재하는지 확인 */
/* ================================ */
GGvalid.Api =
{
    /* ajaxResult.CODE가 성공했고, 데이터도 존재하는지? */
    isSucceedAndHasData(ajax)
    {
        let rslt = true;
        if(!this.isSucceed(ajax)) rslt = false;
        if(!this.hasData(ajax))   rslt = false;

        return rslt;
    },

    /* ajaxResult.CODE가 성공했는지 */
    isSuccess(ajax) { return this.isSucceed(ajax); },
    isSucceed(ajax)
    {
        let rslt = false;
        if(ajax.CODE == Api.succeed)
            rslt = true;

        return rslt;
    },

    /* ajaxResult.DATA 가 존재하는지? */
    hasData(ajax)
    {
        let rslt = false;
        if(ajax.DATA != undefined && ajax.DATA.length > 0)
            rslt = true;

        return rslt;
    },

} /* end obj */

/* ======================================= */
/* Validation 에서 사용하는 가장 기본적인 함수들 (숫자인지 확인 등) */
/* ======================================= */
GGvalid.Common =
{
    isNumber(val, min, max)
    {
        /* set result */
        let rslt = GGvalid.getInitRslt();

        /* logic */
        if(isNaN(val) == true)
        {
            rslt['code'] = false;
            rslt['msg']  = $.i18n('(validation-common)isNumber-noNumber');
        }
        else if(!$.isNumeric(val))
        {
            rslt['code'] = false;
            rslt['msg']  = $.i18n('(validation-common)isNumber-noNumber');
        }
        else if(min != null && val < min)
        {
            rslt['code'] = false;
            rslt['msg']  = $.i18n('(validation-common)isNumber-valueIs')+(min)+$.i18n('(validation-common)isNumber-inputUpper');
        }
        else if(max != null && val > max)
        {
            rslt['code'] = false;
            rslt['msg']  = $.i18n('(validation-common)isNumber-valueIs')+(max)+$.i18n('(validation-common)isNumber-inputUnder');
        }
        return rslt;
    },

    noSpaceBetweenString(val)
    {
        let rslt = GGvalid.getInitRslt();
        if(val.search(/\s/) != -1)
        {
            rslt['code'] = false;
            rslt['msg']  = $.i18n("(validation-common)noSpaceBetweenString-yes");
        }
        return rslt;
    },

    hasSpecialChar(val)
    {
        let pattern1 = /[\!\@\#\$\%\^\&\*\(\)\_\+\=\{\}\[\\\]\:\;\"\'\<\>\,\.\?\/]/;
        let pattern2 = /([\u2700-\u27BF]|[\uE000-\uF8FF]|\uD83C[\uDC00-\uDFFF]|\uD83D[\uDC00-\uDFFF]|[\u2011-\u26FF]|\uD83E[\uDD10-\uDDFF])/g;
        return pattern1.test(val) || pattern2.test(val);
    },

    hasSpecial(val)
    {
        let pattern1 =  /[\!\@\#\$\%\^\&\*\(\)\_\+\=\{\}\[\\\]\:\;\"\'\<\>\,\.\?\/]/;
        let pattern2 = /([\u2700-\u27BF]|[\uE000-\uF8FF]|\uD83C[\uDC00-\uDFFF]|\uD83D[\uDC00-\uDFFF]|[\u2011-\u26FF]|\uD83E[\uDD10-\uDDFF])/g;
        return pattern1.test(val) || pattern2.test(val);
    },

    isEmail(val)
    {
        /* set result */
        let rslt = GGvalid.getInitRslt();

        /* check */
        if(!/^[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*@[0-9a-zA-Z]([-_.]?[0-9a-zA-Z])*.[a-zA-Z]{2,3}$/i.test(val))
        {
            rslt['code'] = false;
            rslt['msg']  = $.i18n('(validation-common)isEmail-noEmail');
        }
        return rslt;
    },

}

Api.GovAddr =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByKeyword(searchKeyword, pagenum, noticeOK, noticeFail)
    {
        let ajaxData =
        {
            SEARCH_KEYWORD : searchKeyword,
            PAGENUM        : pagenum,
        }
        let ajax = Api.execute(ajaxData, "Api.GovAddr.select", noticeOK, noticeFail);
        let rslt = new MGovAddrs(ajax);
        return rslt;
    },

}

Api.SystemBoard =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectMain      (         noticeOK, noticeFail) { return Api.SystemBoard.select({OPTION : "selectMain"                          }, noticeOK, noticeFail); },
    selectOpenByPk  (sbindex, noticeOK, noticeFail) { return Api.SystemBoard.select({OPTION : "selectOpenByPk", SBINDEX : sbindex,  }, noticeOK, noticeFail); },
    selectOpenList  (         noticeOK, noticeFail) { return Api.SystemBoard.select({OPTION : "selectOpenList",                     }, noticeOK, noticeFail); },

    /* ========================= */
    /* main function */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.SystemBoard.select", noticeOK, noticeFail);
        let rslt = new MSystemBoards(ajax);
        return rslt;
    },

}

class _MCommon
{
    constructor(ajax)
    {
        this.isSucceed       = GGvalid.Api.isSucceed(ajax);
        this.code            = ajax.CODE;
        this.msg             = ajax.MSG;
        this.data            = ajax.DATA != undefined ? ajax.DATA : [];
        this.models          = [];
        this.pageflg         = ajax.PAGEFLG != undefined ? ajax.PAGEFLG : GGF.N;                /* 현재 페이지 */
        this.pagenum         = ajax.PAGENUM != undefined ? ajax.PAGENUM : Api.defaultPagenum;   /* 현재 페이지 */
        this.pagecnt         = ajax.PAGECNT != undefined ? ajax.PAGECNT : 0;                    /* 페이지 수 */
        this.perpage         = ajax.PERPAGE != undefined ? ajax.PERPAGE : Api.defaultPerpage;   /* 페이지 당 표시 수 */
        this.allcnt          = ajax.ALLCNT  != undefined ? ajax.ALLCNT  : 0;                    /* 총 모델 수 */
        this.cnt             = ajax.CNT     != undefined ? ajax.CNT     : 0;                    /* 현재 조회 수 */
    }
    isSuccess() { return this.isSucceed; }
    getSucceed() { return this.isSucceed; }
    getCode() { return this.code; }
    getMsg() { return this.msg; }
    getData() { return this.data; }
    getPageflg() { return this.pageflg; }
    getPagenum() { return this.pagenum; }
    getPagecnt() { return this.pagecnt; }
    getPerpage() { return this.perpage; }
    getAllcnt() { return this.allcnt; }
    getCnt() { return this.cnt; }

    isPagenation() { return this.getPageflg() === GGF.Y; }

    /* 변환 함수 */
    static getAjaxSucceed(arr=[])
    {
        let ajax =
        {
            CODE  : Api.succeed,
            MSG   : "",
            COUNT : arr.length,
            DATA  : arr,
        }
        return ajax;
    }
    static getFailed(message="error")
    {
        let ajax =
        {
            CODE  : Api.failed,
            MSG   : message,
            COUNT : 0,
            DATA  : [],
        }
        return ajax;
    }
    static fromArr   (arr=[] , clz=null) { return new clz(_MCommon.getAjaxSucceed(arr)); }
    static fromDat   (dat={} , clz=null) { return new clz(dat); }
    static fromModel (model  , clz=null) { return new clz(model); }

    /* 모델 반환 및 체크 */
    getModels()
    {
        if(this.models == undefined || this.models == null)
            this.models = [];
        return this.models;
    }
    hasModels() { return this.getModels().length > 0; }
    getModel() { return this.getModels().length == 0 ? null : this.getModels()[0]; }
    hasModel()
    {
        if(this.getModels().length == 0)  return false;
        return true;
    }

    /* ================================ */
    /* 페이지네이션 html을 반환한다 */
    /* ================================ */
    mergePagenation(html)
    {
        /* pagenation */
        if(this.getPagecnt() > 1)
        {
            let pagenation = this.getPagenation();
            html = pagenation + html + pagenation;
        }
        return html;
    }
    getPagenation()
    {
        let btncnt = 5; /* btn per page */
        let pagenum = this.pagenum;
        let pagecnt = this.pagecnt;
        let allcnt = this.allcnt;

        let startPage = pagenum - ((pagenum - 1) % btncnt);

        if(allcnt <= 1)
            return "";

        let pagenationHtml =
        `
            <div class="common-div-pagenationTop">
                ${startPage > 1 ? `<div class="common-btn-pagenationBtn common-tap" to_page="${(startPage-1) < 1 ? 1 : (startPage-1)}">&lt;</div>` : ""}
                <div class="common-div-pagenationBtnTop">
        `;
        for(let i = startPage; i <= pagecnt && i < startPage+btncnt; i++)
        {
            let isTab = i == pagenum ? `tab="tab"` : "";
            pagenationHtml += `<div class="common-btn-pagenationBtn commonEvent-div-pagenationBtn common-tap" to_page="${i}" ${isTab}>${i}</div>`;
        }
        pagenationHtml +=
        `
                </div>
                ${startPage + btncnt <= pagecnt ? `<div class="common-btn-pagenationBtn common-tap" to_page="${(startPage+btncnt) > pagecnt ? pagecnt : (startPage+btncnt)}">&gt;</div>` : ""}
            </div>
        `;
        return pagenationHtml;
    }
    static getToPage(el)
    {
        let toPage = el.attr("to_page");
        return toPage;
    }

}

/*
    let mApiResponse = Api....
    if(mApiResponse.isSuccess())
    {
        Navigation.executeShow();
        return;
    }
 */
class MApiResponse extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        this.userno = GGC.Common.char(ajax.userno);
    }

    getUserno() { return this.userno; }

    /* ========================= */
    /* additional methods */
    /* ========================= */
}

class MAddressSido
{
    constructor(dat)
    {
        this.sdidx = GGC.Common.int(dat.sdidx);
        this.sdname = GGC.Common.char(dat.sdname);
    }

    getSdidx()     { return this.sdidx; }
    getSdname()    { return this.sdname; }
}


class MAddressSidos extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MAddressSido(dat));
        }
    } /* construnctor */

    makeOption(el="")
    {
        let html = "";
        let isFirst = true;
        let models = this.getModels();
        for(let i in models)
        {
            let model = models[i];
            html += `<option value="${model.getSdidx()}" ${isFirst ? "selected" : ""}>${model.getSdname()}</option>`;

            if(isFirst)
                isFirst = false;
        }
        $(el).html(html);

        /* --------------- */
        /* set event */
        /* --------------- */

    }

}

class MAddressSigungu
{
    constructor(dat)
    {
        this.sdidx = GGC.Common.int(dat.sdidx);
        this.sggidx = GGC.Common.int(dat.sggidx);
        this.sggname = GGC.Common.char(dat.sggname);
        this.sdname = GGC.Common.char(dat.sdname);
        this.pk = `sdidx="${this.sdidx}" sggidx="${this.sggidx}"`;
    }

    getSdidx()   { return this.sdidx; }
    getSggidx()  { return this.sggidx; }
    getSggname() { return this.sggname; }
    getSdname()  { return this.sdname; }

    static makeInline(sdidx ,sdname, sggidx, sggname)
    {
        let html =
        `
            <div class="MAddressSigungu-makeInline-div-top common-tag-fontsize09" sdidx="${sdidx}" sdname="${sdname}" sggidx="${sggidx}" sggname="${sggname}">
                <span style="vertical-align:middle;">${sdname} ${sggname}</span>
                <button class="MAddressSigungu-makeInline-btn-delete common-btn-noline common-tag-fontsize09" btn-type="cancel" style="padding:0.3em;" sdidx="${sdidx}" sggidx="${sggidx}">삭제</button>
            </div>
        `;
        return html;
    }
}


class MAddressSigungus extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MAddressSigungu(dat));
        }
    } /* construnctor */

    makeOption(el="")
    {
        let html = "";
        let isFirst = true;
        let models = this.getModels();
        for(let i in models)
        {
            let model = models[i];
            html += `<option value="${model.getSggidx()}" ${isFirst ? "selected" : ""}>${model.getSggname()}</option>`;

            if(isFirst)
                isFirst = false;
        }
        $(el).html(html);
    }

}

class MBank
{
    constructor(dat)
    {
        /* field */
        this.bankcode                 = dat.bankcode;
        this.bankname                 = dat.bankname;
        this.maintenance_start        = dat.maintenance_start;
        this.maintenance_end          = dat.maintenance_end;
        this.maintenance_hecto_start  = dat.maintenance_hecto_start;
        this.maintenance_hecto_end    = dat.maintenance_hecto_end;
        this.maintenance_fixedterm    = dat.maintenance_fixedterm;
    }

    getBankcode()               { return this.bankcode; }
    getBankname()               { return this.bankname; }
    getMaintenanceStart()       { return this.maintenance_start; }
    getMaintenanceEnd()         { return this.maintenance_end; }
    getMaintenanceHectoStart()  { return this.maintenance_hecto_start; }
    getMaintenanceHectoEnd()    { return this.maintenance_hecto_end; }
    getMaintenanceFixedterm()   { return this.maintenance_fixedterm; }
}

class MBanks extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MBank(dat));
        }
    }

    /* ================================ */
    /* 은행 선택용 */
    /*
        el : 엘리먼트
        chosenBankcode : 이미 선택된 은행코드
     */
    /* ================================ */
    makeBankForChoose(el, chosenBankcode)
    {
        let html = "";
        html +=
        `
            <table class="MBank-makeBankForChoose-tbl-forChoose commonEvent-tbl-tab">
                <tbody>
        `;

        /* 행 반복 */
        let i = 0;
        for(let i in this.getModels())
        {
            let model = this.getModels()[i];
            if(i % 2 == 0) html += `<tr>`;

            html +=
            `
                <td
                    class="common-tap"
                    tab=""
                    bankcode="${model.getBankcode()}"
                >
                    ${model.getBankname()}
                </td>
            `;

            if(i % 2 == 1) html += `</tr>`;
            i++;
        }
        html += `</tbody></table>`;
        $(el).html(html);
    }
}

class MGovAddr
{
    constructor(dat)
    {
        /* ----------- */
        /* origin field */
        /* ----------- */
        this.admCd         = dat.admCd;
        this.bdKdcd        = dat.bdKdcd;
        this.bdMgtSn       = dat.bdMgtSn;
        this.bdNm          = dat.bdNm;
        this.buldMnnm      = dat.buldMnnm;
        this.buldSlno      = dat.buldSlno;
        this.detBdNmList   = dat.detBdNmList;
        this.emdNo         = dat.emdNo;
        this.liNm          = dat.liNm;
        this.lnbrMnnm      = dat.lnbrMnnm;
        this.lnbrSlno      = dat.lnbrSlno;
        this.mtYn          = dat.mtYn;
        this.rn            = dat.rn;
        this.rnMgtSn       = dat.rnMgtSn;
        this.zipNo         = dat.zipNo;
        this.siNm          = dat.siNm;
        this.sggNm         = dat.sggNm;
        this.emdNm         = dat.emdNm;
        this.roadAddr      = dat.roadAddr;
        this.jibunAddr     = dat.jibunAddr;
        this.engAddr       = dat.engAddr;
        this.roadAddrPart1 = dat.roadAddrPart1;
        this.roadAddrPart2 = dat.roadAddrPart2;
        this.udrtYn        = dat.udrtYn;

    } /* contructor */

    static getModelFromEl(el)
    {
        let dat =
        {
            admCd           : el.attr("adm_cd"),
            bdKdcd          : el.attr("bd_kdcd"),
            bdMgtSn         : el.attr("bd_mgt_sn"),
            bdNm            : el.attr("bd_nm"),
            buldMnnm        : el.attr("buld_mnnm"),
            buldSlno        : el.attr("buld_slno"),
            detBdNmList     : el.attr("det_bd_nm_list"),
            emdNo           : el.attr("emd_no"),
            liNm            : el.attr("li_nm"),
            lnbrMnnm        : el.attr("lnbr_mnnm"),
            lnbrSlno        : el.attr("lnbr_slno"),
            mtYn            : el.attr("mt_yn"),
            rn              : el.attr("rn"),
            rnMgtSn         : el.attr("rn_mgt_sn"),
            zipNo           : el.attr("zip_no"),
            siNm            : el.attr("si_nm"),
            sggNm           : el.attr("sgg_nm"),
            emdNm           : el.attr("emd_nm"),
            roadAddr        : el.attr("road_addr"),
            jibunAddr       : el.attr("jibun_addr"),
            engAddr         : el.attr("eng_addr"),
            roadAddrPart1   : el.attr("road_addr_part1"),
            roadAddrPart2   : el.attr("road_addr_part2"),
            udrtYn          : el.attr("udrt_yn"),
        };
        let mGovAddr = new MGovAddr(dat);
        return mGovAddr;
    }

    make()
    {
        let html =
        `
            <table
                class="MGovAddr-make-tbl-label"
                adm_cd="${this.admCd}"
                bd_kdcd="${this.bdKdcd}"
                bd_mgt_sn="${this.bdMgtSn}"
                bd_nm="${this.bdNm}"
                buld_mnnm="${this.buldMnnm}"
                buld_slno="${this.buldSlno}"
                det_bd_nm_list="${this.detBdNmList}"
                emd_no="${this.emdNo}"
                li_nm="${this.liNm}"
                lnbr_mnnm="${this.lnbrMnnm}"
                lnbr_slno="${this.lnbrSlno}"
                mt_yn="${this.mtYn}"
                rn="${this.rn}"
                rn_mgt_sn="${this.rnMgtSn}"
                zip_no="${this.zipNo}"
                si_nm="${this.siNm}"
                sgg_nm="${this.sggNm}"
                emd_nm="${this.emdNm}"
                road_addr="${this.roadAddr}"
                jibun_addr="${this.jibunAddr}"
                eng_addr="${this.engAddr}"
                road_addr_part1="${this.roadAddrPart1}"
                road_addr_part2="${this.roadAddrPart2}"
                udrt_yn="${this.udrtYn}"
            >
                <tbody>
                    <tr>
                        <td>${this.zipNo}</td>
                        <td>
                            ${this.roadAddr}<br/>
                            <span style="font-size:0.9em; color:#444">${this.jibunAddr}<span>
                        </td>
                    </tr>
                </tbody>
            </table>
        `;
        return html;
    }

}

class MGovAddrs extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);

        /* set result code to lang */
        this.isSucceed = false;
        this.msg = "";
        if(ajax.ERROR != undefined)
        {
            switch(ajax.ERROR)
            {
                case "0"     : this.msg = $.i18n("(MGovAddr)errorCode__0"); this.isSucceed = true; break; /* API 성공표시 */
                case "-999"  : this.msg = $.i18n("(MGovAddr)errorCode__-999");  break;
                case "E0001" : this.msg = $.i18n("(MGovAddr)errorCode__E0001"); break;
                case "E0005" : this.msg = $.i18n("(MGovAddr)errorCode__E0005"); break;
                case "E0006" : this.msg = $.i18n("(MGovAddr)errorCode__E0006"); break;
                case "E0008" : this.msg = $.i18n("(MGovAddr)errorCode__E0008"); break;
                case "E0009" : this.msg = $.i18n("(MGovAddr)errorCode__E0009"); break;
                case "E0010" : this.msg = $.i18n("(MGovAddr)errorCode__E0010"); break;
                case "E0011" : this.msg = $.i18n("(MGovAddr)errorCode__E0011"); break;
                case "E0012" : this.msg = $.i18n("(MGovAddr)errorCode__E0012"); break;
                case "E0013" : this.msg = $.i18n("(MGovAddr)errorCode__E0013"); break;
                case "E0014" : this.msg = $.i18n("(MGovAddr)errorCode__E0014"); break;
                case "E0015" : this.msg = $.i18n("(MGovAddr)errorCode__E0015"); break;
            }
        }
        if(this.msg == "")
            this.msg = $.i18n("(MGovAddr)errorCode__unknownErr");

        /* make data */
        this.models = [];
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MGovAddr(dat));
        }
    }

    static setInfoToObj(obj, el)
    {
        if(obj == undefined || obj == null)
        {
            Common.toast("시스템에 문제가 발생했습니다. 관리자에게 문의하세요. (주소정보취득)");
            return null;
        }

        let dataEl = $(`${el} > .MGovAddr-make-tbl-label`);
        if(dataEl.length == 0)
        {
            Common.toast("주소를 입력해주세요.");
            return null;
        }

        /* --------------- */
        /* make main data */
        /* --------------- */
        obj.ADDR_ZIPCODE    = dataEl.attr("zip_no");
        obj.ADDR_SIDO       = dataEl.attr("si_nm");
        obj.ADDR_SIGUNGU    = dataEl.attr("sgg_nm");
        obj.ADDR_EMD        = dataEl.attr("emd_nm");
        obj.ADDR_ROAD       = dataEl.attr("road_addr");
        obj.ADDR_JIBUN      = dataEl.attr("jibun_addr");
        obj.ADDR_ROADENG    = dataEl.attr("eng_addr");
        obj.ADDR_ADMCD      = dataEl.attr("adm_cd");
        obj.ADDR_RNMGTSN    = dataEl.attr("rn_mgt_sn");
        obj.ADDR_UDRTYN     = dataEl.attr("udrt_yn");
        obj.ADDR_BULDMNNM   = dataEl.attr("buld_mnnm");
        obj.ADDR_BULDSLNO   = dataEl.attr("buld_slno");
        return obj;
    }

    /* ================================ */
    /* 주소 리스트 출력 */
    /*
        opt :
        {
            [*] code : 페이지코드
            [-] nowPage      : 현재 페이지
            [-] viewCount    : 페이지당 글 수
        }
    */
    /* ================================ */
    makeForSearch(el)
    {
        /* ------------------------ */
        /* check error && check data */
        /* ------------------------ */
        /* ajax 결과확인 */
        if(!this.isSucceed)
            return GGhtml.getCard('failed', this.msg);

        /* ------------------------ */
        /* set variables */
        /* ------------------------ */
        let pagenationHtml = "";
        let html = "";

        /* ------------------------ */
        /* get html */
        /* ------------------------ */
        pagenationHtml = super.getPagenation();

        /* set header */
        html +=
        `
            <div style="margin:1em 0em;">
                <table class="common-tbl-normal MGovAddrs-makeForSearch-tbl-top" tbl-type="normal">
                    <thead>
                        <th>선택</th>
                        <th>우편번호</th>
                        <th>주소</th>
                    </thead>
                    <tbody>
        `;

        /* set body */
        for(let i in this.models)
        {
            let dat = this.models[i];
            html +=
            `
                <tr>
                    <td>
                        <button
                            class="common-btn-outline MGovAddr-makeForSearch-btn-address"
                            adm_cd="${dat.admCd}"
                            bd_kdcd="${dat.bdKdcd}"
                            bd_mgt_sn="${dat.bdMgtSn}"
                            bd_nm="${dat.bdNm}"
                            buld_mnnm="${dat.buldMnnm}"
                            buld_slno="${dat.buldSlno}"
                            det_bd_nm_list="${dat.detBdNmList}"
                            emd_no="${dat.emdNo}"
                            li_nm="${dat.liNm}"
                            lnbr_mnnm="${dat.lnbrMnnm}"
                            lnbr_slno="${dat.lnbrSlno}"
                            mt_yn="${dat.mtYn}"
                            rn="${dat.rn}"
                            rn_mgt_sn="${dat.rnMgtSn}"
                            zip_no="${dat.zipNo}"
                            si_nm="${dat.siNm}"
                            sgg_nm="${dat.sggNm}"
                            emd_nm="${dat.emdNm}"
                            road_addr="${dat.roadAddr}"
                            jibun_addr="${dat.jibunAddr}"
                            eng_addr="${dat.engAddr}"
                            road_addr_part1="${dat.roadAddrPart1}"
                            road_addr_part2="${dat.roadAddrPart2}"
                            udrt_yn="${dat.udrtYn}"
                        >
                            선택
                        </button>
                    </td>
                    <td>${dat.zipNo}</td>
                    <td>
                        ${dat.roadAddr}<br/>
                        <span style="font-size:0.9em; color:#444">${dat.jibunAddr}<span>
                    </td>
                </tr>
            `;
        }
        html += `</tbody></table></div>`;
        $(el).html(pagenationHtml + html + pagenationHtml);

    } /* end makeForSearch */
}

/* ================== */
/* 함수 이름으로부터 서버의 연결지점을 가져온다. */
/* ================== */
Navigation.getApiUrlByFuncName = function(funcName="")
{
    let url = "";
    let serverHost  = ServerInfo.getServerHost();
    switch(funcName)
    {
        /* SystemBoard */               case "Api.SystemBoard.select"                               : url = serverHost + "/src/data/systemBoard/selectSystemBoard.php"; break;
        /* User */                      case "Api.User.login"                                       : url = serverHost + "/src/data/user/login.php"; break;
        /* User */                      case "Api.User.select"                                      : url = serverHost + "/src/data/user/selectUser.php"; break;
        /* User */                      case "Api.User.insert"                                      : url = serverHost + "/src/data/user/insertUser.php"; break;
        /* User */                      case "Api.User.update"                                      : url = serverHost + "/src/data/user/updateUser.php"; break;
        /* User */                      case "Api.User.deleteUserInfo"                              : url = serverHost + "/src/data/user/deleteUserInfo.php"; break;
        /* Grp */                       case "Api.Grp.select"                                       : url = serverHost + "/src/data/grp/selectGrp.php"; break;
        /* GrpfncSponsorship */         case "Api.GrpfncSponsorship.select"                         : url = serverHost + "/src/data/grpfncSponsorship/selectGrpfncSponsorship.php"; break;
        /* GrpfncSponsorship */         case "Api.GrpfncSponsorship.update"                         : url = serverHost + "/src/data/grpfncSponsorship/updateGrpfncSponsorship.php"; break;
        /* GrpfncPurchase */            case "Api.GrpfncPurchase.select"                            : url = serverHost + "/src/data/grpfncPurchase/selectGrpfncPurchase.php"; break;
        /* GrpfncPurchase */            case "Api.GrpfncPurchase.update"                            : url = serverHost + "/src/data/grpfncPurchase/updateGrpfncPurchase.php"; break;
        /* GrpfncLoss */                case "Api.GrpfncLoss.select"                                : url = serverHost + "/src/data/grpfncLoss/selectGrpfncLoss.php"; break;
        /* GrpfncLoss */                case "Api.GrpfncLoss.update"                                : url = serverHost + "/src/data/grpfncLoss/updateGrpfncLoss.php"; break;
        /* Grpfnca */                   case "Api.Grpfnca.select"                                   : url = serverHost + "/src/data/grpfnca/selectGrpfnca.php"; break;
        /* Grpfnca */                   case "Api.Grpfnca.update"                                   : url = serverHost + "/src/data/grpfnca/updateGrpfnca.php"; break;
        /* Grpfnclog */                 case "Api.Grpfnclog.select"                                 : url = serverHost + "/src/data/grpfnclog/selectGrpfnclog.php"; break;
        /* cls */                       case "Api.Cls.select"                                       : url = serverHost + "/src/data/cls/selectCls.php"; break;
        /* cls */                       case "Api.Cls.update"                                       : url = serverHost + "/src/data/cls/updateCls.php"; break;
        /* clslineupa */                case "Api.Clslineupa.select"                                : url = serverHost + "/src/data/clslineupa/selectClslineupa.php"; break;
        /* clslineupa */                case "Api.Clslineupa.update"                                : url = serverHost + "/src/data/clslineupa/updateClslineupa.php"; break;
        /* clslineupb */                case "Api.Clslineupb.select"                                : url = serverHost + "/src/data/clslineupb/selectClslineupb.php"; break;
        /* clslineupb */                case "Api.Clslineupb.update"                                : url = serverHost + "/src/data/clslineupb/updateClslineupb.php"; break;
        /* clslineuptmpa */             case "Api.Clslineuptmpa.select"                             : url = serverHost + "/src/data/clslineuptmpa/selectClslineuptmpa.php"; break;
        /* clslineuptmpa */             case "Api.Clslineuptmpa.update"                             : url = serverHost + "/src/data/clslineuptmpa/updateClslineuptmpa.php"; break;
        /* clslineuptmpb */             case "Api.Clslineuptmpb.select"                             : url = serverHost + "/src/data/clslineuptmpb/selectClslineuptmpb.php"; break;
        /* clslineuptmpb */             case "Api.Clslineuptmpb.update"                             : url = serverHost + "/src/data/clslineuptmpb/updateClslineuptmpb.php"; break;
        /* clslineuptmpc */             case "Api.Clslineuptmpc.select"                             : url = serverHost + "/src/data/clslineuptmpc/selectClslineuptmpc.php"; break;
        /* clspurchase */               case "Api.Clspurchase.select"                               : url = serverHost + "/src/data/clspurchase/selectClspurchase.php"; break;
        /* clspurchase */               case "Api.Clspurchase.update"                               : url = serverHost + "/src/data/clspurchase/updateClspurchase.php"; break;
        /* clspurchasehist */           case "Api.Clspurchasehist.select"                           : url = serverHost + "/src/data/clspurchasehist/selectClspurchasehist.php"; break;
        /* clssettle */                 case "Api.Clssettle.select"                                 : url = serverHost + "/src/data/clssettle/selectClssettle.php"; break;
        /* clssettle */                 case "Api.Clssettle.update"                                 : url = serverHost + "/src/data/clssettle/updateClssettle.php"; break;
        /* clssettlehist */             case "Api.Clssettlehist.select"                             : url = serverHost + "/src/data/clssettlehist/selectClssettlehist.php"; break;
        /* clssettletmp */              case "Api.Clssettletmp.select"                              : url = serverHost + "/src/data/clssettletmp/selectClssettletmp.php"; break;
        /* clssettletmp */              case "Api.Clssettletmp.update"                              : url = serverHost + "/src/data/clssettletmp/updateClssettletmp.php"; break;
        /* GrpMember */                 case "Api.GrpMember.select"                                 : url = serverHost + "/src/data/grpMember/selectGrpMember.php"; break;
        /* GrpMember */                 case "Api.GrpMember.update"                                 : url = serverHost + "/src/data/grpMember/updateGrpMember.php"; break;
        /* GrpMemberPointhist */        case "Api.GrpMemberPointhist.select"                        : url = serverHost + "/src/data/grpMemberPointhist/selectGrpMemberPointhist.php"; break;
        /* GrpMemberPointhist */        case "Api.GrpMemberPointhist.update"                        : url = serverHost + "/src/data/grpMemberPointhist/updateGrpMemberPointhist.php"; break;
        /* Scheduleall */               case "Api.Scheduleall.select"                               : url = serverHost + "/src/data/scheduleall/selectScheduleall.php"; break;
        /* Schedulebyweek */            case "Api.Schedulebyweek.select"                            : url = serverHost + "/src/data/schedulebyweek/selectSchedulebyweek.php"; break;
        /* Bank */                      case "Api.Bank.select"                                      : url = serverHost + "/src/data/bank/selectBank.php"; break;
        /* Bankaccount */               case "Api.Bankaccount.select"                               : url = serverHost + "/src/data/bankaccount/selectBankaccount.php"; break;
        /* Bankaccount */               case "Api.Bankaccount.update"                               : url = serverHost + "/src/data/bankaccount/updateBankaccount.php"; break;
        /* System.Check */              case "Api.System.Check.check"                               : url = serverHost + "/src/data/system/check.php"; break;

    } /* end switch */
    return url;
};

Navigation.Page =
{
    A00UserLogin : "ULGN",
    A01UserInsert : "UINT",
    A02UserPrivacyDelete : "UPRD",
    A11UserMainHome : "UMHM",
    A12UserMainGrp : "UMGP",
    A13UserMainCls : "UMCL",
    A14UserMainSettle : "UMST",
    A15UserMainManage : "UMMG",
    A81UserBankacctList : "UBKL",
    A82UserBankacctUpdate : "UBKU",
    A1011ManagePhonePrivacy : "UMPP",
    B10ManagerChooseGrp : "MGCG",
    B11ManagerMainHome : "MMHM",
    B12ManagerMainClasses : "MMCL",
    B13ManagerMainMembers : "MMMB",
    B14ManagerMainSettle : "MMST",
    B80GrpManageHome : "MMMG",
    B85ManagerMemberLayering : "MMLY",
    B71GrpMemberDetail : "GMDT",
    B72GrpMemberMergeTemp : "GMMT",
    B81ManagerBankacctList : "MBKL",
    B82ManagerBankacctUpdate : "MBKU",
    B1000GrpFinanceHome : "GFHM",
    B1010GrpFinanceCapitalList : "GFCL",
    B1011GrpFinanceCapitalUpdate : "GFCU",
    B1020GrpFinanceSponsorList : "GFSL",
    B1021GrpFinanceSponsorUpdate : "GFSU",
    B1030GrpFinancePurchaseList : "GFPL",
    B1031GrpFinancePurchaseUpdate : "GFPU",
    B1040GrpFinanceLossList : "GFLL",
    B1041GrpFinanceLossUpdate : "GFLU",
    C00AdminChooseUser : "AACU",
    D10DetailGrp : "DGRP",
    D21DetailClssettle : "DCLS",
    D22DetailClssettleByClsno : "DCSC",
    F00Class000Detail : "CLSD",
    F00Class001DetailApplyDialog : "CLSA",
    F00Class080TextCls : "CLDC",
    F00Class081TextApply : "CLDA",
    F00Class082TextSettle : "CLDS",
    F10ClassUpdate000Default : "CUDE",
    F10ClassUpdate010LineupUpdate : "CUTF",
    F10ClassUpdate011Lineuptmp : "CULT",
    F10ClassUpdate020SettleEdit : "CUST",
    F10ClassUpdate021SettleSend : "CUSS",
    F10ClassUpdate026Purchase : "CUPU",
    F10ClassUpdate030Cancel : "CUCC",
    G10ScheduleByYear : "GSCY",
    G20ScheduleByWeek : "GSCW",
    S10ChooseGrpMember : "CHGM",
    S11ChooseCls : "CHCL",
    Z00AppUpdateUrl : "SAPP",
    Z21SystemBoardList : "SBLI",
    Z22SystemBoardDetail : "SBDL",
};


/* ================== */
/* ページコードを利用して、URLを出す */
/* ================== */
Navigation.getURL = function(str)
{
    let url = "";
    switch(str)
    {
        case Navigation.Page.A00UserLogin                                  : url = "./app/contents/A00-user/A00-UserLogin.html"; break;
        case Navigation.Page.A01UserInsert                                 : url = "./app/contents/A00-user/A01-UserInsert.html"; break;
        case Navigation.Page.A02UserPrivacyDelete                          : url = "./app/contents/A00-user/A02-UserPrivacyDelete.html"; break;
        case Navigation.Page.A11UserMainHome                               : url = "./app/contents/A00-user/A11-UserMainHome.html"; break;
        case Navigation.Page.A12UserMainGrp                                : url = "./app/contents/A00-user/A12-UserMainGrp.html"; break;
        case Navigation.Page.A13UserMainCls                                : url = "./app/contents/A00-user/A13-UserMainCls.html"; break;
        case Navigation.Page.A14UserMainSettle                             : url = "./app/contents/A00-user/A14-UserMainSettle.html"; break;
        case Navigation.Page.A15UserMainManage                             : url = "./app/contents/A00-user/A15-UserMainManage.html"; break;
        case Navigation.Page.A81UserBankacctList                           : url = "./app/contents/A00-user/A81-UserBankacctList.html"; break;
        case Navigation.Page.A82UserBankacctUpdate                         : url = "./app/contents/A00-user/A82-UserBankacctUpdate.html"; break;
        case Navigation.Page.A1011ManagePhonePrivacy                       : url = "./app/contents/A10-userManage/A1011ManagePhonePrivacy.html"; break;
        case Navigation.Page.B10ManagerChooseGrp                           : url = "./app/contents/B00-manager/B10-ManagerChooseGrp.html"; break;
        case Navigation.Page.B11ManagerMainHome                            : url = "./app/contents/B00-manager/B11-ManagerMainHome.html"; break;
        case Navigation.Page.B12ManagerMainClasses                         : url = "./app/contents/B00-manager/B12-ManagerMainClasses.html"; break;
        case Navigation.Page.B13ManagerMainMembers                         : url = "./app/contents/B00-manager/B13-ManagerMainMembers.html"; break;
        case Navigation.Page.B14ManagerMainSettle                          : url = "./app/contents/B00-manager/B14-ManagerMainSettle.html"; break;
        case Navigation.Page.B85ManagerMemberLayering                      : url = "./app/contents/B00-manager/B85-ManagerMemberLayering.html"; break;
        case Navigation.Page.B71GrpMemberDetail                            : url = "./app/contents/B00-manager/B71-GrpMemberDetail.html"; break;
        case Navigation.Page.B72GrpMemberMergeTemp                         : url = "./app/contents/B00-manager/B72-GrpMemberMergeTemp.html"; break;
        case Navigation.Page.B80GrpManageHome                              : url = "./app/contents/B00-manager/B80GrpManageHome.html"; break;
        case Navigation.Page.B81ManagerBankacctList                        : url = "./app/contents/B00-manager/B81-ManagerBankacctList.html"; break;
        case Navigation.Page.B82ManagerBankacctUpdate                      : url = "./app/contents/B00-manager/B82-ManagerBankacctUpdate.html"; break;
        case Navigation.Page.B1000GrpFinanceHome                           : url = "./app/contents/B10-grpfnc/B1000GrpFinanceHome.html"; break;
        case Navigation.Page.B1010GrpFinanceCapitalList                    : url = "./app/contents/B10-grpfnc/B1010GrpFinanceCapitalList.html"; break;
        case Navigation.Page.B1011GrpFinanceCapitalUpdate                  : url = "./app/contents/B10-grpfnc/B1011GrpFinanceCapitalUpdate.html"; break;
        case Navigation.Page.B1020GrpFinanceSponsorList                    : url = "./app/contents/B10-grpfnc/B1020GrpFinanceSponsorList.html"; break;
        case Navigation.Page.B1021GrpFinanceSponsorUpdate                  : url = "./app/contents/B10-grpfnc/B1021GrpFinanceSponsorUpdate.html"; break;
        case Navigation.Page.B1030GrpFinancePurchaseList                   : url = "./app/contents/B10-grpfnc/B1030GrpFinancePurchaseList.html"; break;
        case Navigation.Page.B1031GrpFinancePurchaseUpdate                 : url = "./app/contents/B10-grpfnc/B1031GrpFinancePurchaseUpdate.html"; break;
        case Navigation.Page.B1040GrpFinanceLossList                       : url = "./app/contents/B10-grpfnc/B1040GrpFinanceLossList.html"; break;
        case Navigation.Page.B1041GrpFinanceLossUpdate                     : url = "./app/contents/B10-grpfnc/B1041GrpFinanceLossUpdate.html"; break;
        case Navigation.Page.C00AdminChooseUser                            : url = "./app/contents/C00-admin/C00-AdminChooseUser.html"; break;
        case Navigation.Page.D10DetailGrp                                  : url = "./app/contents/D00-detail/D10-DetailGrp.html"; break;
        case Navigation.Page.D21DetailClssettle                            : url = "./app/contents/D00-detail/D21DetailClssettle.html"; break;
        case Navigation.Page.D22DetailClssettleByClsno                     : url = "./app/contents/D00-detail/D22-DetailClssettleByClsno.html"; break;
        case Navigation.Page.F00Class000Detail                             : url = "./app/contents/F00-class/F00-Class000Detail.html"; break;
        case Navigation.Page.F00Class001DetailApplyDialog                  : url = "./app/contents/F00-class/F00-Class001DetailApplyDialog.html"; break;
        case Navigation.Page.F00Class080TextCls                            : url = "./app/contents/F00-class/F00-Class080TextCls.html"; break;
        case Navigation.Page.F00Class081TextApply                          : url = "./app/contents/F00-class/F00-Class081TextApply.html"; break;
        case Navigation.Page.F00Class082TextSettle                         : url = "./app/contents/F00-class/F00-Class082TextSettle.html"; break;
        case Navigation.Page.F10ClassUpdate000Default                      : url = "./app/contents/F00-class/F10-ClassUpdate000Default.html"; break;
        case Navigation.Page.F10ClassUpdate010LineupUpdate                 : url = "./app/contents/F00-class/F10-ClassUpdate010LineupUpdate.html"; break;
        case Navigation.Page.F10ClassUpdate011Lineuptmp                    : url = "./app/contents/F00-class/F10-ClassUpdate011Lineuptmp.html"; break;
        case Navigation.Page.F10ClassUpdate020SettleEdit                   : url = "./app/contents/F00-class/F10-ClassUpdate020SettleEdit.html"; break;
        case Navigation.Page.F10ClassUpdate021SettleSend                   : url = "./app/contents/F00-class/F10-ClassUpdate021SettleSend.html"; break;
        case Navigation.Page.F10ClassUpdate026Purchase                     : url = "./app/contents/F00-class/F10-ClassUpdate026Purchase.html"; break;
        case Navigation.Page.F10ClassUpdate030Cancel                       : url = "./app/contents/F00-class/F10-ClassUpdate030Cancel.html"; break;
        case Navigation.Page.G10ScheduleByYear                             : url = "./app/contents/G00-schedule/G10ScheduleByYear.html"; break;
        case Navigation.Page.G20ScheduleByWeek                             : url = "./app/contents/G00-schedule/G20ScheduleByWeek.html"; break;
        case Navigation.Page.S10ChooseGrpMember                            : url = "./app/contents/S00-common/S10-ChooseGrpMember.html"; break;
        case Navigation.Page.S11ChooseCls                                  : url = "./app/contents/S00-common/S11-ChooseCls.html"; break;
        case Navigation.Page.Z00AppUpdateUrl                               : url = "./app/contents/Z00-system/Z00-AppUpdateUrl.html"; break;
        case Navigation.Page.Z21SystemBoardList                            : url = "./app/contents/Z00-system/Z21-SystemBoardList.html"; break;
        case Navigation.Page.Z22SystemBoardDetail                          : url = "./app/contents/Z00-system/Z22-SystemBoardDetail.html"; break;
    }
    return url;
};

/* ================================== */
/* 각 페이지를 이동할 때, 데이터를 각 페이지에서 가져온다.  */
/* ================================== */
Navigation.getData = function(code)
{
    switch(code)
    {
        case Navigation.Page.A00UserLogin                           : return data = ULGN.Data;
        case Navigation.Page.A01UserInsert                          : return data = UINT.Data;
        case Navigation.Page.A02UserPrivacyDelete                   : return data = UPRD.Data;
        case Navigation.Page.A11UserMainHome                        : return data = UMHM.Data;
        case Navigation.Page.A12UserMainGrp                         : return data = UMGP.Data;
        case Navigation.Page.A13UserMainCls                         : return data = UMCL.Data;
        case Navigation.Page.A14UserMainSettle                      : return data = UMST.Data;
        case Navigation.Page.A15UserMainManage                      : return data = UMMG.Data;
        case Navigation.Page.A81UserBankacctList                    : return data = UBKL.Data;
        case Navigation.Page.A82UserBankacctUpdate                  : return data = UBKU.Data;
        case Navigation.Page.A1011ManagePhonePrivacy                : return data = UMPP.Data;
        case Navigation.Page.B10ManagerChooseGrp                    : return data = MGCG.Data;
        case Navigation.Page.B11ManagerMainHome                     : return data = MMHM.Data;
        case Navigation.Page.B12ManagerMainClasses                  : return data = MMCL.Data;
        case Navigation.Page.B13ManagerMainMembers                  : return data = MMMB.Data;
        case Navigation.Page.B14ManagerMainSettle                   : return data = MMST.Data;
        case Navigation.Page.B80GrpManageHome                       : return data = MMMG.Data;
        case Navigation.Page.B85ManagerMemberLayering               : return data = MMLY.Data;
        case Navigation.Page.B71GrpMemberDetail                     : return data = GMDT.Data;
        case Navigation.Page.B72GrpMemberMergeTemp                  : return data = GMMT.Data;
        case Navigation.Page.B81ManagerBankacctList                 : return data = MBKL.Data;
        case Navigation.Page.B82ManagerBankacctUpdate               : return data = MBKU.Data;
        case Navigation.Page.B1000GrpFinanceHome                    : return data = GFHM.Data;
        case Navigation.Page.B1010GrpFinanceCapitalList             : return data = GFCL.Data;
        case Navigation.Page.B1011GrpFinanceCapitalUpdate           : return data = GFCU.Data;
        case Navigation.Page.B1020GrpFinanceSponsorList             : return data = GFSL.Data;
        case Navigation.Page.B1021GrpFinanceSponsorUpdate           : return data = GFSU.Data;
        case Navigation.Page.B1030GrpFinancePurchaseList            : return data = GFPL.Data;
        case Navigation.Page.B1031GrpFinancePurchaseUpdate          : return data = GFPU.Data;
        case Navigation.Page.B1040GrpFinanceLossList                : return data = GFLL.Data;
        case Navigation.Page.B1041GrpFinanceLossUpdate              : return data = GFLU.Data;
        case Navigation.Page.C00AdminChooseUser                     : return data = AACU.Data;
        case Navigation.Page.D10DetailGrp                           : return data = DGRP.Data;
        case Navigation.Page.D21DetailClssettle                     : return data = DCLS.Data;
        case Navigation.Page.D22DetailClssettleByClsno              : return data = DCSC.Data;
        case Navigation.Page.F00Class000Detail                      : return data = CLSD.Data;
        case Navigation.Page.F00Class001DetailApplyDialog           : return data = CLSA.Data;
        case Navigation.Page.F00Class080TextCls                     : return data = CLDC.Data;
        case Navigation.Page.F00Class081TextApply                   : return data = CLDA.Data;
        case Navigation.Page.F00Class082TextSettle                  : return data = CLDS.Data;
        case Navigation.Page.F10ClassUpdate000Default               : return data = CUDE.Data;
        case Navigation.Page.F10ClassUpdate010LineupUpdate          : return data = CUTF.Data;
        case Navigation.Page.F10ClassUpdate011Lineuptmp             : return data = CULT.Data;
        case Navigation.Page.F10ClassUpdate020SettleEdit            : return data = CUST.Data;
        case Navigation.Page.F10ClassUpdate021SettleSend            : return data = CUSS.Data;
        case Navigation.Page.F10ClassUpdate026Purchase              : return data = CUPU.Data;
        case Navigation.Page.F10ClassUpdate030Cancel                : return data = CUCC.Data;
        case Navigation.Page.G10ScheduleByYear                      : return data = GSCY.Data;
        case Navigation.Page.G20ScheduleByWeek                      : return data = GSCW.Data;
        case Navigation.Page.S10ChooseGrpMember                     : return data = CHGM.Data;
        case Navigation.Page.S11ChooseCls                           : return data = CHCL.Data;
        case Navigation.Page.Z00AppUpdateUrl                        : return data = SAPP.Data;
        case Navigation.Page.Z21SystemBoardList                     : return data = SBLI.Data;
        case Navigation.Page.Z22SystemBoardDetail                   : return data = SBDL.Data;
        default:
        {
            console.log("Navigation.getData : 해당 페이지 코드에 대한 데이터를 찾을 수 없습니다. code = " + code);
            return null;
        }
    }
};

/* ======================= */
/* 페이지 가장 최근 스택의 show 함수를 실행시킨다. */
/* ======================= */
Navigation.executeShow = function()
{
    /* ------------- */
    /* get pageStack */
    /* ------------- */
    let pageStack = GGstorage.getPageStack();
    let lastPage = null;
    if(pageStack == undefined || pageStack.length == 0)
    {
        console.log("pageStack이 정의되지 않았거나, 길이가 0 입니다.");
        return;
    }
    else
    {
        lastPage = pageStack[pageStack.length-1];
    }

    /* 파라미터 추가설정 > 뒤로가기를 한 후, 초기화를 실행할 것인지? */
    let data = Navigation.getData(lastPage.page);
    if(data.executeShowWhenClose != undefined)
    {
        if(data.executeShowWhenClose == false)
        {
            console.log("executeShowWhenClose가 false로 설정되어 있습니다.");
            data.executeShowWhenClose = true;
            return;
        }
    }

    /* 페이지에 대한 show 실행 */
    let lastPageCode = lastPage['page'];
    switch(lastPageCode)
    {
        case Navigation.Page.A00UserLogin                             : ULGN.show(); break;
        case Navigation.Page.A01UserInsert                            : UINT.show(); break;
        case Navigation.Page.A02UserPrivacyDelete                     : UPRD.show(); break;
        case Navigation.Page.A11UserMainHome                          : UMHM.show(); break;
        case Navigation.Page.A12UserMainGrp                           : UMGP.show(); break;
        case Navigation.Page.A13UserMainCls                           : UMCL.show(); break;
        case Navigation.Page.A14UserMainSettle                        : UMST.show(); break;
        case Navigation.Page.A15UserMainManage                        : UMMG.show(); break;
        case Navigation.Page.A81UserBankacctList                      : UBKL.show(); break;
        case Navigation.Page.A82UserBankacctUpdate                    : UBKU.show(); break;
        case Navigation.Page.A1011ManagePhonePrivacy                  : UMPP.show(); break;
        case Navigation.Page.B10ManagerChooseGrp                      : MGCG.show(); break;
        case Navigation.Page.B11ManagerMainHome                       : MMHM.show(); break;
        case Navigation.Page.B12ManagerMainClasses                    : MMCL.show(); break;
        case Navigation.Page.B13ManagerMainMembers                    : MMMB.show(); break;
        case Navigation.Page.B14ManagerMainSettle                     : MMST.show(); break;
        case Navigation.Page.B80GrpManageHome                         : MMMG.show(); break;
        case Navigation.Page.B85ManagerMemberLayering                 : MMLY.show(); break;
        case Navigation.Page.B71GrpMemberDetail                       : GMDT.show(); break;
        case Navigation.Page.B72GrpMemberMergeTemp                    : GMMT.show(); break;
        case Navigation.Page.B81ManagerBankacctList                   : MBKL.show(); break;
        case Navigation.Page.B82ManagerBankacctUpdate                 : MBKU.show(); break;
        case Navigation.Page.B1000GrpFinanceHome                      : GFHM.show(); break;
        case Navigation.Page.B1010GrpFinanceCapitalList               : GFCL.show(); break;
        case Navigation.Page.B1011GrpFinanceCapitalUpdate             : GFCU.show(); break;
        case Navigation.Page.B1020GrpFinanceSponsorList               : GFSL.show(); break;
        case Navigation.Page.B1021GrpFinanceSponsorUpdate             : GFSU.show(); break;
        case Navigation.Page.B1030GrpFinancePurchaseList              : GFPL.show(); break;
        case Navigation.Page.B1031GrpFinancePurchaseUpdate            : GFPU.show(); break;
        case Navigation.Page.B1040GrpFinanceLossList                  : GFLL.show(); break;
        case Navigation.Page.B1041GrpFinanceLossUpdate                : GFLU.show(); break;
        case Navigation.Page.C00AdminChooseUser                       : AACU.show(); break;
        case Navigation.Page.D10DetailGrp                             : DGRP.show(); break;
        case Navigation.Page.D21DetailClssettle                       : DCLS.show(); break;
        case Navigation.Page.D22DetailClssettleByClsno                : DCSC.show(); break;
        case Navigation.Page.F00Class000Detail                        : CLSD.show(); break;
        case Navigation.Page.F00Class001DetailApplyDialog             : CLSA.show(); break;
        case Navigation.Page.F00Class080TextCls                       : CLDC.show(); break;
        case Navigation.Page.F00Class081TextApply                     : CLDA.show(); break;
        case Navigation.Page.F00Class082TextSettle                    : CLDS.show(); break;
        case Navigation.Page.F10ClassUpdate000Default                 : CUDE.show(); break;
        case Navigation.Page.F10ClassUpdate010LineupUpdate            : CUTF.show(); break;
        case Navigation.Page.F10ClassUpdate011Lineuptmp               : CULT.show(); break;
        case Navigation.Page.F10ClassUpdate020SettleEdit              : CUST.show(); break;
        case Navigation.Page.F10ClassUpdate021SettleSend              : CUSS.show(); break;
        case Navigation.Page.F10ClassUpdate026Purchase                : CUPU.show(); break;
        case Navigation.Page.F10ClassUpdate030Cancel                  : CUCC.show(); break;
        case Navigation.Page.G10ScheduleByYear                        : GSCY.show(); break;
        case Navigation.Page.G20ScheduleByWeek                        : GSCW.show(); break;
        case Navigation.Page.S10ChooseGrpMember                       : CHGM.show(); break;
        case Navigation.Page.S11ChooseCls                             : CHCL.show(); break;
        case Navigation.Page.Z00AppUpdateUrl                          : SAPP.show(); break;
        case Navigation.Page.Z21SystemBoardList                       : SBLI.show(); break;
        case Navigation.Page.Z22SystemBoardDetail                     : SBDL.show(); break;
    }
};

Navigation.executeMoveBack = function()
{
    /* ------------- */
    /* get pageStack */
    /* ------------- */
    let pageStack = GGstorage.getPageStack();
    let lastPage = null;
    if(pageStack == undefined || pageStack.length == 0)
    {
        console.log("pageStack이 정의되지 않았거나, 길이가 0 입니다.");
        return;
    }
    else
    {
        lastPage = pageStack[pageStack.length-1];
    }
    let lastPageCode = lastPage['page'];

    /* 페이지에 대한 show 실행 */
    switch(lastPageCode)
    {
        case Navigation.Page.A00UserLogin                             : ULGN.close(true); break;
        case Navigation.Page.A01UserInsert                            : UINT.close(true); break;
        case Navigation.Page.A02UserPrivacyDelete                     : UPRD.close(true); break;
        case Navigation.Page.A11UserMainHome                          : UMHM.close(true); break;
        case Navigation.Page.A12UserMainGrp                           : UMGP.close(true); break;
        case Navigation.Page.A13UserMainCls                           : UMCL.close(true); break;
        case Navigation.Page.A14UserMainSettle                        : UMST.close(true); break;
        case Navigation.Page.A15UserMainManage                        : UMMG.close(true); break;
        case Navigation.Page.A81UserBankacctList                      : UBKL.close(true); break;
        case Navigation.Page.A82UserBankacctUpdate                    : UBKU.close(true); break;
        case Navigation.Page.A1011ManagePhonePrivacy                  : UMPP.close(true); break;
        case Navigation.Page.B10ManagerChooseGrp                      : MGCG.close(true); break;
        case Navigation.Page.B11ManagerMainHome                       : MMHM.close(true); break;
        case Navigation.Page.B12ManagerMainClasses                    : MMCL.close(true); break;
        case Navigation.Page.B13ManagerMainMembers                    : MMMB.close(true); break;
        case Navigation.Page.B14ManagerMainSettle                     : MMST.close(true); break;
        case Navigation.Page.B80GrpManageHome                         : MMMG.close(true); break;
        case Navigation.Page.B85ManagerMemberLayering                 : MMLY.close(true); break;
        case Navigation.Page.B71GrpMemberDetail                       : GMDT.close(true); break;
        case Navigation.Page.B72GrpMemberMergeTemp                    : GMMT.close(true); break;
        case Navigation.Page.B81ManagerBankacctList                   : MBKL.close(true); break;
        case Navigation.Page.B82ManagerBankacctUpdate                 : MBKU.close(true); break;
        case Navigation.Page.B1000GrpFinanceHome                      : GFHM.close(true); break;
        case Navigation.Page.B1010GrpFinanceCapitalList               : GFCL.close(true); break;
        case Navigation.Page.B1011GrpFinanceCapitalUpdate             : GFCU.close(true); break;
        case Navigation.Page.B1020GrpFinanceSponsorList               : GFSL.close(true); break;
        case Navigation.Page.B1021GrpFinanceSponsorUpdate             : GFSU.close(true); break;
        case Navigation.Page.B1030GrpFinancePurchaseList              : GFPL.close(true); break;
        case Navigation.Page.B1031GrpFinancePurchaseUpdate            : GFPU.close(true); break;
        case Navigation.Page.B1040GrpFinanceLossList                  : GFLL.close(true); break;
        case Navigation.Page.B1041GrpFinanceLossUpdate                : GFLU.close(true); break;
        case Navigation.Page.C00AdminChooseUser                       : AACU.close(true); break;
        case Navigation.Page.D10DetailGrp                             : DGRP.close(true); break;
        case Navigation.Page.D21DetailClssettle                       : DCLS.close(true); break;
        case Navigation.Page.D22DetailClssettleByClsno                : DCSC.close(true); break;
        case Navigation.Page.F00Class000Detail                        : CLSD.close(true); break;
        case Navigation.Page.F00Class001DetailApplyDialog             : CLSA.close(true); break;
        case Navigation.Page.F00Class080TextCls                       : CLDC.close(true); break;
        case Navigation.Page.F00Class081TextApply                     : CLDA.close(true); break;
        case Navigation.Page.F00Class082TextSettle                    : CLDS.close(true); break;
        case Navigation.Page.F10ClassUpdate000Default                 : CUDE.close(true); break;
        case Navigation.Page.F10ClassUpdate010LineupUpdate            : CUTF.close(true); break;
        case Navigation.Page.F10ClassUpdate011Lineuptmp               : CULT.close(true); break;
        case Navigation.Page.F10ClassUpdate020SettleEdit              : CUST.close(true); break;
        case Navigation.Page.F10ClassUpdate021SettleSend              : CUSS.close(true); break;
        case Navigation.Page.F10ClassUpdate026Purchase                : CUPU.close(true); break;
        case Navigation.Page.F10ClassUpdate030Cancel                  : CUCC.close(true); break;
        case Navigation.Page.G10ScheduleByYear                        : GSCY.close(true); break;
        case Navigation.Page.G20ScheduleByWeek                        : GSCW.close(true); break;
        case Navigation.Page.S10ChooseGrpMember                       : CHGM.close(true); break;
        case Navigation.Page.S11ChooseCls                             : CHCL.close(true); break;
        case Navigation.Page.Z00AppUpdateUrl                          : SAPP.close(true); break;
        case Navigation.Page.Z21SystemBoardList                       : SBLI.close(true); break;
        case Navigation.Page.Z22SystemBoardDetail                     : SBDL.close(true); break;
    }
};

/* ================== */
/* goto home */
/* ================== */
Navigation.moveHome = function()
{
    GGstorage.clearPageStack();
    Navigation.moveAfterLogin();
};

/* ================== */
/* goto login */
/* ================== */
Navigation.moveLogin = function()
{
    GGstorage.clearPageStack();
    Navigation.moveFrontPage(Navigation.Page.A00UserLogin);
};
Navigation.loginIntoGrp = function(grpno) { Navigation.moveFrontPage(Navigation.Page.B11ManagerMainHome, {grpno: grpno}); };

/* ================== */
/* logout */
/* ================== */
Navigation.moveLogout = function()
{
    GGstorage.clearPageStack();
    Navigation.moveFrontPage(Navigation.Page.A00UserLogin);
};

/* ============================== */
/* 로그인 후, 메인페이지로 이동 */
/* ============================== */
Navigation.moveAfterLogin = function()
{
    GGstorage.Prj.clearGrpmtype();
    let appMode = GGstorage.getAppmode();
    switch(appMode)
    {
        case GGF.System.AppMode.CUS : Navigation.moveFrontPage(Navigation.Page.A11UserMainHome); break;
        case GGF.System.AppMode.MNG : Navigation.moveFrontPage(Navigation.Page.B10ManagerChooseGrp); break;
        case GGF.System.AppMode.ADM : Navigation.moveFrontPage(Navigation.Page.E10AdminMain); break;
        default: Navigation.moveFrontPage(Navigation.Page.A11UserMainHome); break;
    }
}

CommonEvent.Prj =
{
    hyperlink(e)
    {
        /* class name */
        let className = "commonEvent-tag-hyperlink";

        /* 클래스 선택 */
        let target = $(e.target);
        if(!target.hasClass(className))
            target = target.parents("."+className);

        /* 가고자 하는 페이지코드 */
        let validation = true;
        let pageData = {};
        let viewMode = target.attr("hyperlink-viewmode");
        let pageCode = target.attr("hyperlink");
        switch(pageCode)
        {
            case Navigation.Page.F10ClassUpdate000Default           : { pageData = { option : target.attr("option"),  grpno     : target.attr("grpno")      , clsno     : target.attr("clsno")    ,                                         }; break; }
            case Navigation.Page.F00Class000Detail                  : { pageData = {                                  grpno     : target.attr("grpno")      , clsno     : target.attr("clsno")    ,                                         }; break; }
            case Navigation.Page.F10ClassUpdate020SettleEdit        : { pageData = {                                  grpno     : target.attr("grpno")      , clsno     : target.attr("clsno")    ,                                         }; break; }
            case Navigation.Page.F10ClassUpdate026Purchase          : { pageData = {                                  grpno     : target.attr("grpno")      , clsno     : target.attr("clsno")    ,                                         }; break; }
            case Navigation.Page.B71GrpMemberDetail                 : { pageData = {                                  grpno     : target.attr("grpno")      , userno    : target.attr("userno")   ,                                         }; break; }
            case Navigation.Page.B72GrpMemberMergeTemp              : { pageData = {                                  grpno     : target.attr("grpno")      , userno    : target.attr("userno")   ,                                         }; break; }
            case Navigation.Page.D10DetailGrp                       : { pageData = {                                  grpno     : target.attr("grpno")                                            ,                                         }; break; }
            case Navigation.Page.D21DetailClssettle                 : { pageData = {                                  grpno     : target.attr("grpno")      , clsno     : target.attr("clsno")    , userno    : target.attr("userno")   ,   }; break; }
            case Navigation.Page.Z22SystemBoardDetail               : { pageData = {                                  sbindex   : target.attr("sbindex")                                          ,                                         }; break; }
            case Navigation.Page.F10ClassUpdate010LineupUpdate      : { pageData = { option : target.attr("option"),  grpno     : target.attr("grpno")      , clsno     : target.attr("clsno")    ,                                         }; break; }
            case Navigation.Page.F10ClassUpdate030Cancel            : { pageData = {                                  grpno     : target.attr("grpno")      , clsno     : target.attr("clsno")    ,                                         }; break; }
            case Navigation.Page.G20ScheduleByWeek                  : { pageData = {sclyear : target.attr("sclyear"), sclmonth  : target.attr("sclmonth")   , sclweek   : target.attr("sclweek")  ,                                         }; break; }
        }
        if(validation)
            Navigation.moveFront(viewMode, pageCode, pageData);
    },
}

GGC.Bankaccount =
{
    defaultflgCvrt(val)
    {
        let rslt = "";
        switch(val)
        {
            case GGF.Bankaccount.Defaultflg.Y : rslt = "기본계좌"; break;
            case GGF.Bankaccount.Defaultflg.N : rslt = "일반계좌"; break;
        }
        return rslt;
    },

    defaultflgCard(val)
    {
        let rslt = "";
        let converted = GGC.Bankaccount.defaultflgCvrt(val);
        switch(val)
        {
            case GGF.Bankaccount.Defaultflg.Y : rslt = `<span class="common-span-card" bankaccount-defaultflg="${val}">${converted}</span>`; break;
            case GGF.Bankaccount.Defaultflg.N : rslt = `<span class="common-span-card" bankaccount-defaultflg="${val}">${converted}</span>`; break;
        }
        return rslt;
    },
};

GGC.GrpMember =
{
    grpmtypeCvrt(val)
    {
        let rslt = "";
        switch(val)
        {
            case GGF.GrpMember.Grpmtype.MNG    : rslt = "매니저"; break;
            case GGF.GrpMember.Grpmtype.MNGSUB : rslt = "부매니저"; break;
            case GGF.GrpMember.Grpmtype.MEMBER : rslt = "멤버"; break;
        }
        return rslt;
    },
};

GGC.GrpMemberPointhist =
{
    pointtype(point)
    {
        let rslt = "";
        if(point >= 0)
            rslt = "입금";
        else if(point < 0)
            rslt = "출금";
        return rslt;
    },
    pointtypePretty(point)
    {
        let rslt = GGC.GrpMemberPointhist.pointtype(point);
        if(point >= 0)
            return `<span class="common-tag-colorPstv">${rslt}</span>`;
        else if(point < 0)
            return `<span class="common-tag-colorNgtv">${rslt}</span>`;
    },
    pointPretty(point)
    {
        let rslt = GGC.Common.priceWon(point);
        if(point >= 0)
            return `<span class="common-tag-colorPstv">${rslt}</span>`;
        else if(point < 0)
            return `<span class="common-tag-colorNgtv">${rslt}</span>`;
    },
}

GGC.Cls =
{

    /* ----- */
    /* clsstatus */
    /* ----- */
    clsstatusCvrt(val)
    {
        let rslt = "";
        switch(val)
        {
            case GGF.Cls.Clsstatus.EDIT   : rslt = "일정작성중"; break;
            case GGF.Cls.Clsstatus.ING    : rslt = "일정진행중"; break;
            case GGF.Cls.Clsstatus.END    : rslt = "일정종료"; break;
            case GGF.Cls.Clsstatus.CANCEL : rslt = "일정취소"; break;
        }
        return rslt;
    },
    clsstatusFeel(val)
    {
        let rslt = "";
        switch(val)
        {
            case GGF.Cls.Clsstatus.EDIT   : rslt = "hold"; break;
            case GGF.Cls.Clsstatus.ING    : rslt = "prog"; break;
            case GGF.Cls.Clsstatus.END    : rslt = "pstv"; break;
            case GGF.Cls.Clsstatus.CANCEL : rslt = "ngtv"; break;
        }
        return rslt;
    },
    clsstatusCard(val) { return `<span class="common-tag-card" card-color="${GGC.Cls.clsstatusFeel(val)}">${GGC.Cls.clsstatusCvrt(val)}</span>`; },
    clsstatusFont(val) { return `<span class="common-tag-font" font-color="${GGC.Cls.clsstatusFeel(val)}">${GGC.Cls.clsstatusCvrt(val)}</span>`; },

    /* ----- */
    /* clssettleflg */
    /* ----- */
    clssettleflgCvrt(val)
    {
        let rslt = "";
        switch(val)
        {
            case GGF.Cls.Clssettleflg.EDIT    : rslt = "정산입력중"; break;
            case GGF.Cls.Clssettleflg.DONE    : rslt = "정산확정됨"; break;
        }
        return rslt;
    },
    clssettleflgFeel(val)
    {
        let rslt = "";
        switch(val)
        {
            case GGF.Cls.Clssettleflg.EDIT    : rslt = "hold"; break;
            case GGF.Cls.Clssettleflg.DONE    : rslt = "pstv"; break;
        }
        return rslt;
    },
    clssettleflgCard(val) { return `<span class="common-tag-card" card-color="${GGC.Cls.clssettleflgFeel(val)}">${GGC.Cls.clssettleflgCvrt(val)}</span>`; },
    clssettleflgFont(val) { return `<span class="common-tag-font" font-color="${GGC.Cls.clssettleflgFeel(val)}">${GGC.Cls.clssettleflgCvrt(val)}</span>`; },


    /* ----- */
    /* grpfinancereflectflg */
    /* ----- */
    getGrpfinancereflectflgCvrt(val)
    {
        let rslt = "";
        switch(val)
        {
            case GGF.Cls.Grpfinancereflectflg.Y         : rslt = "반영"; break;
            case GGF.Cls.Grpfinancereflectflg.N         : rslt = "미반영"; break;
            case GGF.Cls.Grpfinancereflectflg.UNABLE    : rslt = "반영불가"; break;
        }
        return rslt;
    },
    getGrpfinancereflectflgFeel(val)
    {
        let rslt = "";
        switch(val)
        {
            case GGF.Cls.Grpfinancereflectflg.Y         : rslt = "pstv"; break;
            case GGF.Cls.Grpfinancereflectflg.N         : rslt = "hold"; break;
            case GGF.Cls.Grpfinancereflectflg.UNABLE    : rslt = "ngtv"; break;
        }
        return rslt;
    },
    getGrpfinancereflectflgCard(val) { return `<span class="common-tag-card" card-color="${GGC.Cls.getGrpfinancereflectflgFeel(val)}">${GGC.Cls.getGrpfinancereflectflgCvrt(val)}</span>`; },
    getGrpfinancereflectflgFont(val) { return `<span class="common-tag-font" font-color="${GGC.Cls.getGrpfinancereflectflgFeel(val)}">${GGC.Cls.getGrpfinancereflectflgCvrt(val)}</span>`; },

}

GGC.Clslineupb =
{
    /* ----- */
    /* prepaidflg */
    /* ----- */
    prepaidflgCvrt(val)
    {
        let rslt = "";
        switch(val)
        {
            case GGF.Y : rslt = "완료"; break;
            case GGF.N : rslt = "-"; break;
        }
        return rslt;
    },
    prepaidflgFeel(val)
    {
        let rslt = "";
        switch(val)
        {
            case GGF.Y : rslt = "pstv"; break;
            case GGF.N : rslt = "hold"; break;
        }
        return rslt;
    },
    prepaidflgCard(val) { return `<span class="common-tag-card" card-color="${GGC.Clslineupb.prepaidflgFeel(val)}">${GGC.Clslineupb.prepaidflgCvrt(val)}</span>`; },
    prepaidflgFont(val) { return `<span class="common-tag-font" font-color="${GGC.Clslineupb.prepaidflgFeel(val)}">${GGC.Clslineupb.prepaidflgCvrt(val)}</span>`; },

}

GGC.Clssettle =
{
    /* ----- */
    /* settlestatus */
    /* ----- */
    settlestatusCvrt(val)
    {
        if(val == GGF.Clssettle.Settlestatus.WAIT) return "입금대기";
        if(val == GGF.Clssettle.Settlestatus.MEMB) return "입금완료";
        if(val == GGF.Clssettle.Settlestatus.DONE) return "확인완료";
        if(val == GGF.Clssettle.Settlestatus.LOSS) return "손실";
        return "";
    },
    settlestatusFeel(val)
    {
        if(val == GGF.Clssettle.Settlestatus.WAIT) return "hold";
        if(val == GGF.Clssettle.Settlestatus.MEMB) return "prog";
        if(val == GGF.Clssettle.Settlestatus.DONE) return "pstv";
        if(val == GGF.Clssettle.Settlestatus.LOSS) return "ngtv";
        return "";
    },
    settlestatusCard(val) { return `<span class="common-tag-card" card-color="${GGC.Clssettle.settlestatusFeel(val)}">${GGC.Clssettle.settlestatusCvrt(val)}</span>`; },
    settlestatusFont(val) { return `<span class="common-tag-font" font-color="${GGC.Clssettle.settlestatusFeel(val)}">${GGC.Clssettle.settlestatusCvrt(val)}</span>`; },
}

GGC.Clssettlehist =
{
    /* ----- */
    /* histtype */
    /* ----- */
    histtypeCvrt(val)
    {
        if(val == GGF.Clssettlehist.Histtype.UPDATE) return "갱신";
        if(val == GGF.Clssettlehist.Histtype.DELETE) return "삭제";
        if(val == GGF.Clssettlehist.Histtype.AFTER) return "추가";
        return "";
    },
    histtypeFeel(val)
    {
        if(val == GGF.Clssettlehist.Histtype.UPDATE) return "prog";
        if(val == GGF.Clssettlehist.Histtype.DELETE) return "ngtv";
        if(val == GGF.Clssettlehist.Histtype.AFTER) return "pstv";
        return "";
    },
    histtypeCard(val) { return `<span class="common-tag-card" card-color="${GGC.Clssettlehist.histtypeFeel(val)}">${GGC.Clssettlehist.histtypeCvrt(val)}</span>`; },
    histtypeFont(val) { return `<span class="common-tag-font" font-color="${GGC.Clssettlehist.histtypeFeel(val)}">${GGC.Clssettlehist.histtypeCvrt(val)}</span>`; },
}

GGC.Clspurchasehist =
{
    /* ----- */
    /* histtype */
    /* ----- */
    histtypeCvrt(val)
    {
        if(val == GGF.Clspurchasehist.Histtype.INSERT) return "추가";
        if(val == GGF.Clspurchasehist.Histtype.UPDATE) return "갱신";
        if(val == GGF.Clspurchasehist.Histtype.DELETE) return "삭제";
        return "";
    },
    histtypeFeel(val)
    {
        if(val == GGF.Clspurchasehist.Histtype.INSERT) return "pstv";
        if(val == GGF.Clspurchasehist.Histtype.UPDATE) return "prog";
        if(val == GGF.Clspurchasehist.Histtype.DELETE) return "ngtv";
        return "";
    },
    histtypeCard(val) { return `<span class="common-tag-card" card-color="${GGC.Clspurchasehist.histtypeFeel(val)}">${GGC.Clspurchasehist.histtypeCvrt(val)}</span>`; },
    histtypeFont(val) { return `<span class="common-tag-font" font-color="${GGC.Clspurchasehist.histtypeFeel(val)}">${GGC.Clspurchasehist.histtypeCvrt(val)}</span>`; },
}

GGC.Grp =
{
    grpimgPath(key, img="", origin=false)
    {
        return GGC.Common.getImgPath("grp", key, img, origin);
    },

}

GGC.GrpfncSponsorship =
{
    /* ----- */
    /* spontype */
    /* ----- */
    spontypeCvrt(val)
    {
        if(val == GGF.GrpfncSponsorship.Spontype.THING) return "품목";
        if(val == GGF.GrpfncSponsorship.Spontype.MONEY) return "금전";
        return "";
    },
    spontypeFeel(val)
    {
        if(val == GGF.GrpfncSponsorship.Spontype.THING) return "pstv";
        if(val == GGF.GrpfncSponsorship.Spontype.MONEY) return "pstv";
        return "";
    },
    spontypeCard(val) { return `<span class="common-tag-card" card-color="${GGC.GrpfncSponsorship.spontypeFeel(val)}">${GGC.GrpfncSponsorship.spontypeCvrt(val)}</span>`; },
    spontypeFont(val) { return `<span class="common-tag-font" font-color="${GGC.GrpfncSponsorship.spontypeFeel(val)}">${GGC.GrpfncSponsorship.spontypeCvrt(val)}</span>`; },
}

GGC.User =
{
    img_(key, img="", origin=false) { return GGC.Common.getImgPath("user", key, img, origin); },

    hascarflg(flg)
    {
        if(flg === GGF.Y)
            return "🚗자차";
        else if(flg === GGF.N)
            return "자차없음";
        else
            return "알수없음";
    },

    usertypeCvrt(usertype)
    {
        let rslt = "";
        switch(usertype)
        {
            case "normal" : rslt = "일반"; break;
            case "temp"   : rslt = "임시"; break;
        }
        return rslt;
    }
}

GGvalid.User =
{
    id(el, noticeFail)
    {
        let val = $(el).val();
        let rslt = true;
        let pattern =  /^[a-zA-Z0-9]+$/;
        if     (Common.isEmpty(val))          { Common.noticeFail(noticeFail, "아이디를 입력해주세요."); rslt = false; }
        else if(pattern.test(val)   == false) { Common.noticeFail(noticeFail, "숫자와 영문만 입력할 수 있습니다."); rslt = false; }
        else if(val.length < 3)               { Common.noticeFail(noticeFail, "아이디는 3자 이상이어야 합니다."); rslt = false; }
        else if(val.length > 50)              { Common.noticeFail(noticeFail, "아이디는 50자 이하이어야 합니다."); rslt = false; }

        /* 포커스 및 선택 */
        if(rslt == false) {
            $(el).focus();
            $(el).select();
        }
        return rslt;
    },

    pw(el, noticeFail)
    {
        let val = $(el).val();
        let rslt = true;
        if     (Common.isEmpty(val)) { Common.noticeFail(noticeFail, "비밀번호를 입력해주세요."); rslt = false; }
        else if(val.length < 4)      { Common.noticeFail(noticeFail, "비밀번호는 4자 이상이어야 합니다."); rslt = false; }

        /* 포커스 및 선택 */
        if(rslt == false) {
            $(el).focus();
            $(el).select();
        }
        return rslt;
    },

    name(el, noticeFail)
    {
        let val = $(el).val();
        let rslt = true;
        if     (Common.isEmpty(val))               { Common.noticeFail(noticeFail, "이름을 입력해주세요."); rslt = false; }
        else if(val.length < 2)                    { Common.noticeFail(noticeFail, "이름은 2자 이상이어야 합니다."); rslt = false; }
        else if(GGvalid.Common.hasSpecial(val)) { Common.noticeFail(noticeFail, "이름에 특수문자를 사용할 수 없습니다."); rslt = false; }

        /* 포커스 및 선택 */
        if(rslt == false) {
            $(el).focus();
            $(el).select();
        }
        return rslt;
    },



    /* ===================== */
    /* 유저 이메일 */
    /* ===================== */
    email(val, fieldname="", noticeOK, noticeFail)
    {
        let validation = [];
        validation['isAbleLength']          = {"val":val, "option":{min:1, max:100}, };
        validation['noSpaceBetweenString']  = {"val":val, };
        validation['isEmail']               = {"val":val, };
        return GGvalid.validationBridge(validation, fieldname, noticeOK, noticeFail);
    },
}

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

Api.Bank =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectAll(noticeOK, noticeFail) { let ajaxData = { OPTION : "selectAll" }; return Api.Bank.select(ajaxData, noticeOK, noticeFail); },

    /* ========================= */
    /* main */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Bank.select", noticeOK, noticeFail);
        let rslt = new MBanks(ajax);
        return rslt;
    },

}

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

Api.Cls =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByPkForAll                    (grpno, clsno     , noticeOK, noticeFail) { return Api.Cls.select({OPTION:"selectByPkForAll"                , GRPNO: grpno, CLSNO     : clsno,     }, noticeOK, noticeFail).getModel(); },
    selectByPkForMng                    (grpno, clsno     , noticeOK, noticeFail) { return Api.Cls.select({OPTION:"selectByPkForMng"                , GRPNO: grpno, CLSNO     : clsno,     }, noticeOK, noticeFail).getModel(); },
    selectByGrpnoForMng                 (grpno, pagenum   , noticeOK, noticeFail) { return Api.Cls.select({OPTION:"selectByGrpnoForMng"             , GRPNO: grpno, PAGENUM   : pagenum,   }, noticeOK, noticeFail); },
    selectByGrpnoForAll                 (grpno, pagenum   , noticeOK, noticeFail) { return Api.Cls.select({OPTION:"selectByGrpnoForAll"             , GRPNO: grpno, PAGENUM   : pagenum,   }, noticeOK, noticeFail); },
    selectByClsstatusForMng             (grpno, clsstatus , noticeOK, noticeFail) { return Api.Cls.select({OPTION:"selectByClsstatusForMng"         , GRPNO: grpno, CLSSTATUS : clsstatus, }, noticeOK, noticeFail); },
    selectAppliedFor1YearByUserno       (grpno, userno    , noticeOK, noticeFail) { return Api.Cls.select({OPTION:"selectAppliedFor1YearByUserno"   , GRPNO: grpno, USERNO    : userno,    }, noticeOK, noticeFail); },
    selectFor1YearByGrpnoForAll         (grpno            , noticeOK, noticeFail) { return Api.Cls.select({OPTION:"selectFor1YearByGrpnoForAll"     , GRPNO: grpno,                        }, noticeOK, noticeFail); },

    selectClsstatusEditInImMng          (                   noticeOK, noticeFail) { return Api.Cls.select({OPTION:"selectClsstatusEditInImMng"                      }, noticeOK, noticeFail); },
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

    updateClsstatusEditToIng            (grpno, clsno                  , noticeOK, noticeFail) { return Api.Cls.update({ OPTION: "updateClsstatusEditToIng"                 , GRPNO:grpno, CLSNO:clsno, }, noticeOK, noticeFail); },
    updateClsstatusIngToEnd             (grpno, clsno                  , noticeOK, noticeFail) { return Api.Cls.update({ OPTION: "updateClsstatusIngToEnd"                  , GRPNO:grpno, CLSNO:clsno, }, noticeOK, noticeFail); },
    updateClssettleflgDone              (grpno, clsno                  , noticeOK, noticeFail) { return Api.Cls.update({ OPTION: "updateClssettleflgDone"                   , GRPNO:grpno, CLSNO:clsno, }, noticeOK, noticeFail); },
    updateClsstatusToCancel             (grpno, clsno, clscancelreason , noticeOK, noticeFail) { return Api.Cls.update({ OPTION: "updateClsstatusToCancel"                  , GRPNO:grpno, CLSNO:clsno, CLSCANCELREASON:clscancelreason }, noticeOK, noticeFail); },
    deleteByPkWithSubForMng             (grpno, clsno                  , noticeOK, noticeFail) { return Api.Cls.update({ OPTION: "deleteByPkWithSubForMng"                  , GRPNO:grpno, CLSNO:clsno, }, noticeOK, noticeFail); },
    updateGrpfinancereflectflgToYForMng (grpno, clsno                  , noticeOK, noticeFail) { return Api.Cls.update({ OPTION: "updateGrpfinancereflectflgToYForMng"      , GRPNO:grpno, CLSNO:clsno, }, noticeOK, noticeFail); },
    updateGrpfinancereflectflgToNForMng (grpno, clsno                  , noticeOK, noticeFail) { return Api.Cls.update({ OPTION: "updateGrpfinancereflectflgToNForMng"      , GRPNO:grpno, CLSNO:clsno, }, noticeOK, noticeFail); },

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
    copyClsForMng(grpno, clsno, noticeOK, noticeFail) { return Api.Cls.update({ OPTION: "copyClsForMng", GRPNO:grpno, CLSNO:clsno, }, noticeOK, noticeFail); },

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

Api.Clslineupa =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByClsno (grpno, clsno, noticeOK, noticeFail) { return Api.Clslineupa.select({OPTION:"selectByClsno", GRPNO:grpno, CLSNO:clsno, }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */
    updateFromPage(grpno, clsno, arr, noticeOK, noticeFail) { return Api.Clslineupa.update({OPTION:"updateFromPage", GRPNO:grpno, CLSNO:clsno, ARR:JSON.stringify(arr), }, noticeOK, noticeFail); },

    /* ========================= */
    /* main */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Clslineupa.select", noticeOK, noticeFail);
        let rslt = new MClslineupas(ajax);
        return rslt;
    },
    update(ajaxData={}, noticeOK=GGF.TOAST, noticeFail=GGF.TOAST)
    {
        let ajax = Api.execute(ajaxData, "Api.Clslineupa.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },
};


Api.Clslineupb =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByClsno                   (grpno, clsno,              noticeOK, noticeFail) { return Api.Clslineupb.select({OPTION:"selectByClsno"                 , GRPNO:grpno, CLSNO:clsno,                         }, noticeOK, noticeFail); },
    selectByClsnoForSettleForMng    (grpno, clsno,              noticeOK, noticeFail) { return Api.Clslineupb.select({OPTION:"selectByClsnoForSettleForMng"  , GRPNO:grpno, CLSNO:clsno,                         }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */
    updateApplyRegist             (grpno, clsno, lineupidx, orderno, username, etc, noticeOK, noticeFail) { return Api.Clslineupb.update({OPTION:"updateApplyRegist"            , GRPNO:grpno, CLSNO:clsno, LINEUPIDX:lineupidx, ORDERNO:orderno, USERNAME:username , ETC:etc,   }, noticeOK, noticeFail); },
    updateApplyRegistStead        (grpno, clsno, lineupidx, orderno, userno  , etc, noticeOK, noticeFail) { return Api.Clslineupb.update({OPTION:"updateApplyRegistStead"       , GRPNO:grpno, CLSNO:clsno, LINEUPIDX:lineupidx, ORDERNO:orderno, USERNO:userno     , ETC:etc,   }, noticeOK, noticeFail); },
    updateApplyCancel             (grpno, clsno, lineupidx, orderno,                noticeOK, noticeFail) { return Api.Clslineupb.update({OPTION:"updateApplyCancel"            , GRPNO:grpno, CLSNO:clsno, LINEUPIDX:lineupidx, ORDERNO:orderno,                                }, noticeOK, noticeFail); },
    updatePrepaidflgToYForFin     (grpno, clsno, lineupidx, orderno,                noticeOK, noticeFail) { return Api.Clslineupb.update({OPTION:"updatePrepaidflgToYForFin"    , GRPNO:grpno, CLSNO:clsno, LINEUPIDX:lineupidx, ORDERNO:orderno,                                }, noticeOK, noticeFail); },
    updatePrepaidflgToNForFin     (grpno, clsno, lineupidx, orderno,                noticeOK, noticeFail) { return Api.Clslineupb.update({OPTION:"updatePrepaidflgToNForFin"    , GRPNO:grpno, CLSNO:clsno, LINEUPIDX:lineupidx, ORDERNO:orderno,                                }, noticeOK, noticeFail); },

    /* ========================= */
    /* main */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Clslineupb.select", noticeOK, noticeFail);
        let rslt = new MClslineupbs(ajax);
        return rslt;
    },
    update(ajaxData={}, noticeOK=GGF.TOAST, noticeFail=GGF.TOAST)
    {
        let ajax = Api.execute(ajaxData, "Api.Clslineupb.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },
};


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


Api.Clslineuptmpb =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByGrpno           (grpno, pagenum,     noticeOK, noticeFail) { return Api.Clslineuptmpb.select({OPTION:"selectByGrpno"        , GRPNO:grpno, PAGENUM: pagenum         }, noticeOK, noticeFail); },
    selectByLineupgroup     (grpno, lineupgroup, noticeOK, noticeFail) { return Api.Clslineuptmpb.select({OPTION:"selectByLineupgroup"  , GRPNO:grpno, LINEUPGROUP:lineupgroup, }, noticeOK, noticeFail); },

    /* ========================= */
    /* main */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Clslineuptmpb.select", noticeOK, noticeFail);
        let rslt = new MClslineuptmpbs(ajax);
        return rslt;
    },
    update(ajaxData={}, noticeOK=GGF.TOAST, noticeFail=GGF.TOAST)
    {
        let ajax = Api.execute(ajaxData, "Api.Clslineuptmpb.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },
};


Api.Clslineuptmpc =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByLineupgroup (grpno, lineupgroup, noticeOK, noticeFail) { return Api.Clslineuptmpc.select({OPTION:"selectByLineupgroup", GRPNO:grpno, LINEUPGROUP:lineupgroup, }, noticeOK, noticeFail); },

    /* ========================= */
    /* main */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Clslineuptmpc.select", noticeOK, noticeFail);
        let rslt = new MClslineuptmpcs(ajax);
        return rslt;
    },
};


Api.Clspurchase =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByClsno (grpno, clsno, noticeOK, noticeFail) { return Api.Clspurchase.select({OPTION:"selectByClsno", GRPNO:grpno, CLSNO:clsno, }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */
    insertByArr(grpno, clsno, arr, noticeOK, noticeFail) { return Api.Clspurchase.update({ OPTION:"insertByArr", GRPNO:grpno, CLSNO:clsno, ARR:arr, }, noticeOK, noticeFail); },

    /* ========================= */
    /* main */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Clspurchase.select", noticeOK, noticeFail);
        let rslt = new MClspurchases(ajax);
        return rslt;
    },
    selectDetail(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Clspurchase.selectDetail", noticeOK, noticeFail);
        let rslt = new MClspurchases(ajax);
        return rslt.getModel();
    },
    update(ajaxData={}, noticeOK=GGF.TOAST, noticeFail=GGF.TOAST)
    {
        let ajax = Api.execute(ajaxData, "Api.Clspurchase.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },
};



Api.Clspurchasehist =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByClsno(grpno, clsno, noticeOK, noticeFail) { return Api.Clspurchasehist.select({OPTION:"selectByClsno", GRPNO:grpno, CLSNO:clsno, }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */

    /* ========================= */
    /* main */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Clspurchasehist.select", noticeOK, noticeFail);
        let rslt = new MClspurchasehists(ajax);
        return rslt;
    },
    selectDetail(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Clspurchasehist.selectDetail", noticeOK, noticeFail);
        let rslt = new MClspurchasehists(ajax);
        return rslt.getModel();
    },
    update(ajaxData={}, noticeOK=GGF.TOAST, noticeFail=GGF.TOAST)
    {
        let ajax = Api.execute(ajaxData, "Api.Clspurchasehist.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },
};



Api.Clssettle =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByPkForMng                            (grpno, clsno   , userno    ,   noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectByPkForMng"                            , GRPNO:grpno, CLSNO:clsno    , USERNO:userno     }, noticeOK, noticeFail); },
    selectByClsno                               (grpno, clsno   ,               noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectByClsno"                               , GRPNO:grpno, CLSNO:clsno    ,                   }, noticeOK, noticeFail); },
    selectByClsnoForMng                         (grpno, clsno   ,               noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectByClsnoForMng"                         , GRPNO:grpno, CLSNO:clsno    ,                   }, noticeOK, noticeFail); },
    selectSettlestatusOpenByUsernoForMng        (grpno, userno  ,               noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectSettlestatusOpenByUsernoForMng"        , GRPNO:grpno, USERNO:userno  ,                   }, noticeOK, noticeFail); },
    selectSettlestatusOpenByGrpnoForMng         (grpno,                         noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectSettlestatusOpenByGrpnoForMng"         , GRPNO:grpno,                                    }, noticeOK, noticeFail); },
    selectSettlestatusDoneByGrpnoForMng         (grpno,                         noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectSettlestatusDoneByGrpnoForMng"         , GRPNO:grpno,                                    }, noticeOK, noticeFail); },
    selectSettlestatusDoneByGrpnoWithPageForMng (grpno, pageno  ,               noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectSettlestatusDoneByGrpnoWithPageForMng" , GRPNO:grpno, PAGENUM:pageno                     }, noticeOK, noticeFail); },
    selectSettlestatusLossByGrpnoForMng         (grpno,                         noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectSettlestatusLossByGrpnoForMng"         , GRPNO:grpno,                                    }, noticeOK, noticeFail); },
    selectYetByUsernoForUsr                     (                               noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectYetByUsernoForUsr"                     ,                                                 }, noticeOK, noticeFail); },
    selectTmpByUsernoForUsr                     (                               noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectTmpByUsernoForUsr"                     ,                                                 }, noticeOK, noticeFail); },
    selectCmpByUsernoForUsr                     (                               noticeOK, noticeFail) { return Api.Clssettle.select({OPTION:"selectCmpByUsernoForUsr"                     ,                                                 }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */
    updateSettlestatusToMembForUsr    (grpno, clsno, userno, noticeOK, noticeFail) { return Api.Clssettle.update({OPTION:"updateSettlestatusToMembForUsr"   , GRPNO:grpno, CLSNO:clsno, USERNO:userno}, noticeOK, noticeFail); },
    updateSettlestatusToDoneForFnc    (grpno, clsno, userno, noticeOK, noticeFail) { return Api.Clssettle.update({OPTION:"updateSettlestatusToDoneForFnc"   , GRPNO:grpno, CLSNO:clsno, USERNO:userno}, noticeOK, noticeFail); },
    updateSettlestatusToLossForFnc    (grpno, clsno, userno, noticeOK, noticeFail) { return Api.Clssettle.update({OPTION:"updateSettlestatusToLossForFnc"   , GRPNO:grpno, CLSNO:clsno, USERNO:userno}, noticeOK, noticeFail); },
    updateSettlestatusToWaitForFnc    (grpno, clsno, userno, noticeOK, noticeFail) { return Api.Clssettle.update({OPTION:"updateSettlestatusToWaitForFnc"   , GRPNO:grpno, CLSNO:clsno, USERNO:userno}, noticeOK, noticeFail); },

    /* ========================= */
    /* main */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Clssettle.select", noticeOK, noticeFail);
        let rslt = new MClssettles(ajax);
        return rslt;
    },
    selectDetail(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Clssettle.selectDetail", noticeOK, noticeFail);
        let rslt = new MClssettles(ajax);
        return rslt.getModel();
    },
    update(ajaxData={}, noticeOK=GGF.TOAST, noticeFail=GGF.TOAST)
    {
        let ajax = Api.execute(ajaxData, "Api.Clssettle.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },
};



Api.Clssettlehist =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByClsno(grpno, clsno, noticeOK, noticeFail) { return Api.Clssettlehist.select({OPTION:"selectByClsno", GRPNO:grpno, CLSNO:clsno, }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */

    /* ========================= */
    /* main */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Clssettlehist.select", noticeOK, noticeFail);
        let rslt = new MClssettlehists(ajax);
        return rslt;
    },
    selectDetail(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Clssettlehist.selectDetail", noticeOK, noticeFail);
        let rslt = new MClssettlehists(ajax);
        return rslt.getModel();
    },
    update(ajaxData={}, noticeOK=GGF.TOAST, noticeFail=GGF.TOAST)
    {
        let ajax = Api.execute(ajaxData, "Api.Clssettlehist.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },
};



Api.Clssettletmp =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByClsno (grpno, clsno   , noticeOK, noticeFail) { return Api.Clssettletmp.select({OPTION:"selectByClsno" , GRPNO:grpno, CLSNO:clsno , }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */
    insertByArr   (grpno, clsno, arr, noticeOK, noticeFail) { return Api.Clssettletmp.update({ OPTION:"insertByArr", GRPNO:grpno, CLSNO:clsno, ARR:arr, }, noticeOK, noticeFail); },
    deleteByClsno (grpno, clsno, noticeOK, noticeFail) { return Api.Clssettletmp.update({ OPTION:"deleteByClsno" , GRPNO:grpno, CLSNO:clsno, }, noticeOK, noticeFail); },

    /* ========================= */
    /* main */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Clssettletmp.select", noticeOK, noticeFail);
        let rslt = new MClssettletmps(ajax);
        return rslt;
    },
    selectDetail(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Clssettletmp.selectDetail", noticeOK, noticeFail);
        let rslt = new MClssettletmps(ajax);
        return rslt.getModel();
    },
    update(ajaxData={}, noticeOK=GGF.TOAST, noticeFail=GGF.TOAST)
    {
        let ajax = Api.execute(ajaxData, "Api.Clssettletmp.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },
};



Api.Grp =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectManaging     (            noticeOK, noticeFail) { return Api.Grp.select({OPTION:"selectManaging"      ,                                       }, noticeOK, noticeFail); },
    selectByPk         (grpno     , noticeOK, noticeFail) { return Api.Grp.select({OPTION:"selectByPk"          , GRPNO      : grpno,                   }, noticeOK, noticeFail).getModel(); },
    selectByGrpmanager (grpmanager, noticeOK, noticeFail) { return Api.Grp.select({OPTION:"selectByGrpmanager"  , GRPMANAGER : grpmanager,              }, noticeOK, noticeFail); },
    selectActiveForUsr (            noticeOK, noticeFail) { return Api.Grp.select({OPTION:"selectActiveForUsr"  ,                                       }, noticeOK, noticeFail); },

    /* ========================= */
    /* detail */
    /* ========================= */

    /* ========================= */
    /* main function */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Grp.select", noticeOK, noticeFail);
        let rslt = new MGrps(ajax);
        return rslt;
    },
    selectDetail(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Grp.selectDetail", noticeOK, noticeFail);
        let rslt = new MGrps(ajax);
        return rslt.getModel();
    },

}

Api.GrpfncLoss =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByPk(grpno, lossidx, noticeOK, noticeFail) { return Api.GrpfncLoss.select({OPTION:"selectByPk", GRPNO:grpno, LOSSIDX:lossidx }, noticeOK, noticeFail); },
    selectByGrpnoPagenum(grpno, pagenum, noticeOK, noticeFail) { return Api.GrpfncLoss.select({OPTION:"selectByGrpnoPagenum", GRPNO:grpno, PAGENUM:pagenum }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */
    insertFromPage(grpno, lossitem, losscost, losscomment, noticeOK, noticeFail)
    {
        let ajaxData =
        {
            OPTION: "insertFromPage",
            GRPNO: grpno,
            LOSSITEM: lossitem,
            LOSSCOST: losscost,
            LOSSCOMMENT: losscomment,
        };
        return Api.GrpfncLoss.update(ajaxData, noticeOK, noticeFail);
    },
    deleteByPk(grpno, lossidx, noticeOK, noticeFail) { return Api.GrpfncLoss.update({ OPTION:"deleteByPk", GRPNO:grpno, LOSSIDX:lossidx }, noticeOK, noticeFail); },

    /* ========================= */
    /* detail */
    /* ========================= */

    /* ========================= */
    /* main function */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.GrpfncLoss.select", noticeOK, noticeFail);
        let rslt = new MGrpfncLosses(ajax);
        return rslt;
    },
    update(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.GrpfncLoss.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },

}

Api.GrpfncPurchase =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByPk(grpno, purchaseidx, noticeOK, noticeFail) { return Api.GrpfncPurchase.select({OPTION:"selectByPk", GRPNO:grpno, PURCHASEIDX:purchaseidx }, noticeOK, noticeFail); },
    selectByGrpnoPagenum(grpno, pagenum, noticeOK, noticeFail) { return Api.GrpfncPurchase.select({OPTION:"selectByGrpnoPagenum", GRPNO:grpno, PAGENUM:pagenum }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */
    insertFromPage(grpno, purchaseitem, purchasecost, purchasecomment, noticeOK, noticeFail)
    {
        let ajaxData =
        {
            OPTION: "insertFromPage",
            GRPNO: grpno,
            PURCHASEITEM: purchaseitem,
            PURCHASECOST: purchasecost,
            PURCHASECOMMENT: purchasecomment,
        };
        return Api.GrpfncPurchase.update(ajaxData, noticeOK, noticeFail);
    },
    deleteByPk(grpno, purchaseidx, noticeOK, noticeFail) { return Api.GrpfncPurchase.update({ OPTION:"deleteByPk", GRPNO:grpno, PURCHASEIDX:purchaseidx }, noticeOK, noticeFail); },


    /* ========================= */
    /* detail */
    /* ========================= */

    /* ========================= */
    /* main function */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.GrpfncPurchase.select", noticeOK, noticeFail);
        let rslt = new MGrpfncPurchases(ajax);
        return rslt;
    },
    update(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.GrpfncPurchase.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },

}

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
    deleteByPk(grpno, sponidx, noticeOK, noticeFail) { return Api.GrpfncSponsorship.update({ OPTION:"deleteByPk", GRPNO:grpno, SPONIDX:sponidx }, noticeOK, noticeFail); },


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

Api.Grpfnca =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByPk(grpno, noticeOK, noticeFail) { return Api.Grpfnca.select({OPTION:"selectByPk", GRPNO:grpno, }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */
    updateCapitaltotalByPk(grpno, grpfncCapitaltotal, comment, noticeOK, noticeFail) { return Api.Grpfnca.update({ OPTION:"updateCapitaltotalByPk", GRPNO:grpno, GRPFNC_CAPITALTOTAL:grpfncCapitaltotal, COMMENT:comment }, noticeOK, noticeFail); },
    recalByPk(grpno, noticeOK, noticeFail) { return Api.Grpfnca.update({ OPTION:"recalByPk", GRPNO:grpno, }, noticeOK, noticeFail); },

    /* ========================= */
    /* detail */
    /* ========================= */

    /* ========================= */
    /* main function */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Grpfnca.select", noticeOK, noticeFail);
        let rslt = new MGrpfncas(ajax);
        return rslt;
    },
    selectDetail(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Grpfnca.selectDetail", noticeOK, noticeFail);
        let rslt = new MGrpfncas(ajax);
        return rslt.getModel();
    },
    update(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Grpfnca.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },

}

Api.Grpfnclog =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectGrpfncCapitaltotalByGrpno(grpno, pagenum, noticeOK, noticeFail) { return Api.Grpfnclog.select({OPTION:"selectGrpfncCapitaltotalByGrpno", GRPNO:grpno, PAGENUM:pagenum, }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */

    /* ========================= */
    /* detail */
    /* ========================= */

    /* ========================= */
    /* main function */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Grpfnclog.select", noticeOK, noticeFail);
        let rslt = new MGrpfnclogs(ajax);
        return rslt;
    },
    selectDetail(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Grpfnclog.selectDetail", noticeOK, noticeFail);
        let rslt = new MGrpfnclogs(ajax);
        return rslt.getModel();
    },
    update(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Grpfnclog.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },

}

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

Api.GrpMemberPointhist =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectLast3mByUserno(grpno, userno, noticeOK, noticeFail) { return Api.GrpMemberPointhist.select({OPTION:"selectLast3mByUserno" , GRPNO: grpno, USERNO: userno }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */

    /* ========================= */
    /* detail */
    /* ========================= */

    /* ========================= */
    /* main function */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.GrpMemberPointhist.select", noticeOK, noticeFail);
        let rslt = new MGrpMemberPointhists(ajax);
        return rslt;
    },
    selectDetail(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.GrpMemberPointhist.selectDetail", noticeOK, noticeFail);
        let rslt = new MGrpMemberPointhists(ajax);
        return rslt.getModel();
    },
    update(ajaxData={}, noticeOK=GGF.TOAST, noticeFail=GGF.TOAST)
    {
        return Api.execute(ajaxData, "Api.GrpMemberPointhist.update", noticeOK, noticeFail);
    },

}

Api.SystemBoard =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectMain      (         noticeOK, noticeFail) { return Api.SystemBoard.select({OPTION : "selectMain"                          }, noticeOK, noticeFail); },
    selectOpenByPk  (sbindex, noticeOK, noticeFail) { return Api.SystemBoard.select({OPTION : "selectOpenByPk", SBINDEX : sbindex,  }, noticeOK, noticeFail); },
    selectOpenList  (         noticeOK, noticeFail) { return Api.SystemBoard.select({OPTION : "selectOpenList",                     }, noticeOK, noticeFail); },

    /* ========================= */
    /* main function */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.SystemBoard.select", noticeOK, noticeFail);
        let rslt = new MSystemBoards(ajax);
        return rslt;
    },

}

Api.User =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectMe() { return Api.User.select({"OPTION":"selectMe"}); },
    selectMeForLogin() { return Api.User.select({"OPTION":"selectMeForLogin"}); },
    selectCntById(id, noticeOK, noticeFail) { return Api.User.select({"OPTION":"selectCntById", "ID":id}, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */
    updateBaccnoRefund(baccnoRefund, noticeOK, noticeFail) { return Api.User.update({OPTION:"updateBaccnoRefund", BACCNO_REFUND:baccnoRefund }, noticeOK, noticeFail); },
    updatePhonePrivacyByPk(privPhone, privPhoneGrpm, noticeOK, noticeFail) { return Api.User.update({OPTION:"updatePhonePrivacyByPk", PRIV_PHONE:privPhone, PRIV_PHONE_GRPM:privPhoneGrpm }, noticeOK, noticeFail); },

    /* ========================= */
    /* 등록 */
    /* ========================= */
    insert(id, pw, name, birthYear, phone, email, adrcvflg, hascarflg, address, lineupidx, noticeOK, noticeFail)
    {
        let ajaxData =
        {
            ID          : id,
            PW          : pw,
            NAME        : name,
            BIRTHYEAR   : birthYear,
            PHONE       : phone,
            EMAIL       : email,
            ADRCVFLG    : adrcvflg,
            HASCARFLG   : hascarflg,
            ADDRESS     : address,
            LINEUPIDX    : lineupidx,
        };
        let ajax = Api.execute(ajaxData, "Api.User.insert", noticeOK, noticeFail);
        if(GGvalid.Api.isSucceed(ajax))
        {
            GGstorage.saveLoginInfoToFile(ajax.apikey, ajax.id, function(rslt) { /* console.log("write to file : ", rslt); */ });
            GGstorage.setUserid(ajax.id);
            GGstorage.setUsername(ajax.username);
            GGstorage.setApikey(ajax.apikey);
        }
        else
            return false;
        return true;
    },

    /* =============== */
    /* 로그인 */
    /* =============== */
    login(id, pw, usertype, noticeOK, noticeFail)
    {
        let ajaxData =
        {
            "ID"        : id,
            "PW"        : pw,
            "USERTYPE"  : usertype,
            "TOKEN"     : GGstorage.getPushToken(),
            "PLATFORM"  : GGstorage.getDeviceKindSmall(),
        };
        let ajax = Api.execute(ajaxData, "Api.User.login", noticeOK, noticeFail);
        if(GGvalid.Api.isSucceed(ajax))
        {
            GGstorage.saveLoginInfoToFile(ajax.apikey, ajax.id, function(rslt) { /* console.log("write to file : ", rslt); */ });
            GGstorage.setUserid(ajax.id);
            GGstorage.setApikey(ajax.apikey);
            return true;
        }
        return false;
    },

    deleteUserInfo(id, pw, noticeOK, noticeFail)
    {
        let ajax = Api.execute({ID: id, PW: pw, }, "Api.User.deleteUserInfo", noticeOK, noticeFail);
        let rslt = GGvalid.Api.isSucceed(ajax);
        return rslt;
    },

    /* ========================= */
    /* main function */
    /* ========================= */
    select(ajaxData={}, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.User.select", noticeOK, noticeFail);
        let rslt = new MUsers(ajax);
        return rslt;
    },
    update(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.User.update", noticeOK, noticeFail);
        let rslt = new MApiResponse(ajax);
        return rslt;
    },


}

Api.Scheduleall =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByPk       (sclyear, sclmonth, sclweek, noticeOK, noticeFail) { return Api.Scheduleall.select({OPTION:"selectByPk"       , SCLYEAR: sclyear, SCLMONTH: sclmonth, SCLWEEK: sclweek }, noticeOK, noticeFail); },
    selectByPM3month (                            noticeOK, noticeFail) { return Api.Scheduleall.select({OPTION:"selectByPM3month" }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */

    /* ========================= */
    /* detail */
    /* ========================= */

    /* ========================= */
    /* main function */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Scheduleall.select", noticeOK, noticeFail);
        let rslt = new MSchedulealls(ajax);
        return rslt;
    },
    selectDetail(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Scheduleall.selectDetail", noticeOK, noticeFail);
        let rslt = new MSchedulealls(ajax);
        return rslt.getModel();
    },
    update(ajaxData={}, noticeOK=GGF.TOAST, noticeFail=GGF.TOAST)
    {
        return Api.execute(ajaxData, "Api.Scheduleall.update", noticeOK, noticeFail);
    },

}

Api.Schedulebyweek =
{
    /* ========================= */
    /* select */
    /* ========================= */
    selectByPkInsertIfNotExists(sclyear, sclmonth, sclweek, noticeOK, noticeFail) { return Api.Schedulebyweek.select({OPTION:"selectByPkInsertIfNotExists", SCLYEAR: sclyear, SCLMONTH: sclmonth, SCLWEEK: sclweek }, noticeOK, noticeFail); },

    /* ========================= */
    /* update */
    /* ========================= */

    /* ========================= */
    /* detail */
    /* ========================= */

    /* ========================= */
    /* main function */
    /* ========================= */
    select(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Schedulebyweek.select", noticeOK, noticeFail);
        let rslt = new MSchedulebyweeks(ajax);
        return rslt;
    },
    selectDetail(ajaxData, noticeOK, noticeFail)
    {
        let ajax = Api.execute(ajaxData, "Api.Schedulebyweek.selectDetail", noticeOK, noticeFail);
        let rslt = new MSchedulebyweeks(ajax);
        return rslt.getModel();
    },
    update(ajaxData={}, noticeOK=GGF.TOAST, noticeFail=GGF.TOAST)
    {
        return Api.execute(ajaxData, "Api.Schedulebyweek.update", noticeOK, noticeFail);
    },

}

class MBankaccount
{
    constructor(dat)
    {
        /* data */   this.bacctype            = GGC.Common.enum(dat.bacctype);
        /* data */   this.bacckey             = GGC.Common.char(dat.bacckey);
        /* data */   this.baccno              = GGC.Common.int(dat.baccno);
        /* data */   this.baccnickname        = GGC.Common.char(dat.baccnickname);
        /* data */   this.bacccode            = GGC.Common.char(dat.bacccode);
        /* data */   this.baccacct            = GGC.Common.char(dat.baccacct);
        /* data */   this.baccname            = GGC.Common.char(dat.baccname);
        /* data */   this.usable              = GGC.Common.enum(dat.usable);
        /* data */   this.defaultflg          = GGC.Common.enum(dat.defaultflg);
        /* data */   this.modidt              = GGC.Common.datetime(dat.modidt);
        /* data */   this.regidt              = GGC.Common.datetime(dat.regidt);
        /* data */   this.bankname            = GGC.Common.char(dat.bankname);
        /* data */   this.baccnodefault_user  = GGC.Common.int(dat.baccnodefault_user);
        /* data */   this.baccnodefault_grp   = GGC.Common.int(dat.baccnodefault_grp);
        /* custom */ this.pk                  = `bacctype="${this.bacctype}" bacckey="${this.bacckey}" baccno="${this.baccno}"`; /* pk */
    }

    getBacctype() { return this.bacctype; }
    getBacckey() { return this.bacckey; }
    getBaccno() { return this.baccno; }
    getBaccnickname() { return this.baccnickname; }
    getBacccode() { return this.bacccode; }
    getBaccacct() { return this.baccacct; }
    getBaccname() { return this.baccname; }
    getUsable() { return this.usable; }
    getDefaultflg() { return this.defaultflg; }
    getModidt() { return this.modidt; }
    getRegidt() { return this.regidt; }
    getBankname() { return this.bankname; }
    getPk() { return this.pk; }

    /* ========================= */
    /* fields - additional */
    /* ========================= */
    getDefaultflgCard() { return GGC.Bankaccount.defaultflgCard(this.getDefaultflg()); }

    /* ========================= */
    /* default html */
    /* ========================= */
    make(mode="")
    {
        let btnHtml = ``;
        if(this.getDefaultflg() == GGF.Bankaccount.Defaultflg.Y)
            btnHtml += `<span class="common-tag-inlineBlock">${this.getDefaultflgCard()}</span>`;
        switch(mode)
        {
            case "choose":
            {
                btnHtml += `<button class="common-btn-outline MBankaccount-make-btn-choose" ${this.getPk()} el="${chooseEl}">선택</button>`;
                break;
            }
            default:
            {
                if(this.getDefaultflg() == GGF.Bankaccount.Defaultflg.N || this.getDefaultflg() == "")
                    btnHtml += `<button class="common-btn-outline MBankaccount-make-btn-delete" btn-type="cancel" ${this.getPk()}>삭제</button>`;
                break;
            }
        }

        /* html */
        let html =
        `
            <div class="MBankaccount-make-div-top common-div-card">
                <div class="common-tag-block">
                    <span class="common-tag-strong">계좌</span>
                    <span>${this.getBaccnickname()}</span>
                </div>
                <span class="common-tag-block"></span>
                <span class="common-tag-block">${this.getBankname()}&nbsp;${this.getBaccacct()}</span>
                <span class="common-tag-block">예금주명 : ${this.getBaccname()}</span>
                <div class="common-tag-block common-tag-fontsize09 common-tag-paddingUD02">
                    ${btnHtml}
                </div>
            </div>
        `;
        return html;
    }

}

class MBankaccounts extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MBankaccount(dat));
        }
    }

    makeSelectOption()
    {
        let html = `<option value="">선택</option>`;
        for(let i in this.models)
        {
            let model = this.models[i];
            html +=
            `
                <option
                    value="${model.getBaccno()}"
                    baccno="${model.getBaccno()}"
                    baccnickname="${model.getBaccnickname()}"
                    bacccode="${model.getBacccode()}"
                    baccacct="${model.getBaccacct()}"
                    baccname="${model.getBaccname()}"
                    bankname="${model.getBankname()}"

                >
                    ${model.getBankname()}
                    ${model.getBaccacct()}
                    ${model.getBaccnickname()}
                </option>`;
        }
        return html;
    }

    /* ========================= */
    /* 등록된 계좌가 없다고 안내 */
    /* ========================= */
    static makeAnnounceIfEmpty()
    {
        let html =
        `
            <div class="common-div-card" card-type="warning">
                <div class="common-div-cushionD">
                    정산/환불용 계좌를 등록하여주시기 바랍니다.
                </div>
                <button class="common-btn-outline common-tag-block" btn-type="warning" onclick="Navigation.moveFrontPage(Navigation.Page.B32UserBaccnoRefundUpdate);">계좌등록</button>
            </div>
        `;
        return html;
    }

    make(el, chooseEl="")
    {
        /* ========================= */
        /* make html */
        /* ========================= */
        let html = "";
        for(let i in this.getModels())
        {
            let model = this.getModels()[i];
            html += model.make(chooseEl);
        }

        /* ========================= */
        /* set html */
        /* ========================= */
        $(el).html(html);

        /* ========================= */
        /* set event */
        /* ========================= */
        $(`${el} .MBankaccount-make-btn-delete`).off("click");
        $(`${el} .MBankaccount-make-btn-choose`).off("click");

        /* --------------- */
        /* choose entity */
        /* --------------- */
        // $(`${el} .MBankaccount-make-btn-choose`).on("click", function()
        // {
        //     let el            = $(this).attr("el");
        //     let bankaccountNo = $(this).attr("baccno");
        //     let mBankaccounts = Api.Bankaccount.selectByPk(bankaccountNo, "none", "toast");
        //     let mBankaccount  = mBankaccounts.getModel();
        //     let html          = mBankaccount.makeForView();
        //     $(el).html(html);
        //     Navigation.moveBack();
        // });

        /* --------------- */
        /* delete entity */
        /* --------------- */
        $(`${el} .MBankaccount-make-btn-delete`).on("click", function()
        {
            let bacctype = $(this).attr("bacctype");
            let bacckey  = $(this).attr("bacckey");
            let baccno   = $(this).attr("baccno");
            let process = function()
            {
                Common.showProgress();
                setTimeout(function()
                {
                    let mApiResponse =Api.Bankaccount.deleteByPk(bacctype, bacckey, baccno, "toast", "toast");
                    if(mApiResponse.isSuccess())
                        Navigation.showLastPage();

                    Common.hideProgress();
                }, ajaxDelayTime);
            }
            Common.confirm2("삭제하시겠습니까?", process);
        });
    }
}

class MCls
{
    constructor(dat)
    {
        /* data */      this.grpno                  = GGC.Common.char(dat.grpno);
        /* data */      this.clsno                  = GGC.Common.char(dat.clsno);
        /* data */      this.clsstatus              = GGC.Common.char(dat.clsstatus);
        /* data */      this.clstitle               = GGC.Common.char(dat.clstitle);
        /* data */      this.clscontent             = GGC.Common.varchar(dat.clscontent);
        /* data */      this.clsstartdt             = GGC.Common.datetime(dat.clsstartdt);
        /* data */      this.clsclosedt             = GGC.Common.datetime(dat.clsclosedt);
        /* data */      this.clsground              = GGC.Common.char(dat.clsground);
        /* data */      this.clsgroundaddr          = GGC.Common.char(dat.clsgroundaddr);
        /* data */      this.clsbillapplyprice      = GGC.Common.int(dat.clsbillapplyprice);
        /* data */      this.clsbillapplyunit       = GGC.Common.char(dat.clsbillapplyunit);
        /* data */      this.clsapplystartdt        = GGC.Common.datetime(dat.clsapplystartdt);
        /* data */      this.clsapplyclosedt        = GGC.Common.datetime(dat.clsapplyclosedt);
        /* data */      this.clssettleflg           = GGC.Common.enum(dat.clssettleflg);
        /* data */      this.clsmodidt              = GGC.Common.datetime(dat.clsmodidt);
        /* data */      this.clsregdt               = GGC.Common.datetime(dat.clsregdt);
        /* data */      this.grpmanagerid           = GGC.Common.char(dat.grpmanagerid);
        /* data */      this.grpimg                 = GGC.Common.char(dat.grpimg);
        /* data */      this.grpname                = GGC.Common.char(dat.grpname);
        /* data */      this.clsusernoregname       = GGC.Common.char(dat.clsusernoregname);
        /* data */      this.clsusernoadmname       = GGC.Common.char(dat.clsusernoadmname);
        /* data */      this.clsusernosubname       = GGC.Common.char(dat.clsusernosubname);
        /* data */      this.clsbillsales           = GGC.Common.int(dat.clsbillsales);
        /* data */      this.clsbillpurchase        = GGC.Common.int(dat.clsbillpurchase);
        /* data */      this.clsbillfinal           = GGC.Common.int(dat.clsbillfinal);
        /* data */      this.grpfinancereflectflg   = GGC.Common.enum(dat.grpfinancereflectflg);
        /* data */      this.clscancelreason        = GGC.Common.char(dat.clscancelreason);
        /* custom */    this.grpimgPath             = GGC.Grp.grpimgPath(this.getGrpno(), this.getGrpimg(), false);
        /* custom */    this.pk                     = `grpno="${this.grpno}" clsno="${this.clsno}"`;
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    /* data */      getGrpno() { return this.grpno; }
    /* data */      getClsno() { return this.clsno; }
    /* data */      getClsstatus() { return this.clsstatus; }
    /* data */      getClstitle() { return this.clstitle; }
    /* data */      getClscontent() { return this.clscontent; }
    /* data */      getClsstartdt() { return this.clsstartdt; }
    /* data */      getClsclosedt() { return this.clsclosedt; }
    /* data */      getClsground() { return this.clsground; }
    /* data */      getClsgroundaddr() { return this.clsgroundaddr; }
    /* data */      getClsbillapplyprice() { return this.clsbillapplyprice; }
    /* data */      getClsbillapplyunit() { return this.clsbillapplyunit; }
    /* data */      getClsapplystartdt() { return this.clsapplystartdt; }
    /* data */      getClsapplyclosedt() { return this.clsapplyclosedt; }
    /* data */      getClssettleflg() { return this.clssettleflg; }
    /* data */      getClsmodidt() { return this.clsmodidt; }
    /* data */      getClsregdt() { return this.clsregdt; }
    /* data */      getGrpmanagerid() { return this.grpmanagerid; }
    /* data */      getGrpimg() { return this.grpimg; }
    /* data */      getGrpname() { return this.grpname; }
    /* data */      getClsusernoregname() { return this.clsusernoregname; }
    /* data */      getClsusernoadmname() { return this.clsusernoadmname; }
    /* data */      getClsusernosubname() { return this.clsusernosubname; }
    /* data */      getClsbillsales() { return this.clsbillsales; }
    /* data */      getClsbillpurchase() { return this.clsbillpurchase; }
    /* data */      getClsbillfinal() { return this.clsbillfinal; }
    /* data */      getGrpfinancereflectflg() { return this.grpfinancereflectflg; }
    /* data */      getClscancelreason() { return this.clscancelreason; }
    /* custom */    getGrpimgPath() { return this.grpimgPath; }
    /* custom */    getPk() { return this.pk; }

    /* ========================= */
    /* fields - additional */
    /* ========================= */
    getClsstatusCard()              { return GGC.Cls.clsstatusCard(this.getClsstatus()); }
    getClsstatusFont()              { return GGC.Cls.clsstatusFont(this.getClsstatus()); }
    getClssettleflgCvrt()           { return GGC.Cls.clssettleflgCvrt(this.getClssettleflg()); }
    getClssettleflgCard()           { return GGC.Cls.clssettleflgCard(this.getClssettleflg()); }
    getClssettleflgFont()           { return GGC.Cls.clssettleflgFont(this.getClssettleflg()); }
    getClsPeriod()                  { return GGdate.period(this.getClsstartdt(), this.getClsclosedt()); }
    getClsapplyPeriod()             { return GGdate.period(this.getClsapplystartdt(), this.getClsapplyclosedt()); }
    getClsapplybillpriceWon()       { return this.getClsbillapplyunit() + " 당, " + GGC.Common.priceWon(this.getClsbillapplyprice()); }
    getClsbillsalesWon()            { return GGC.Common.priceWon(this.getClsbillsales()); }
    getClsbillpurchaseWon()         { return GGC.Common.priceWon(this.getClsbillpurchase()); }
    getClsbillfinalWon()            { return GGC.Common.priceWon(this.getClsbillfinal()); }
    getGrpfinancereflectflgFont()   { return GGC.Cls.getGrpfinancereflectflgFont(this.getGrpfinancereflectflg()); }

    getClsstartdtYMDdd() { return GGdate.format(this.getClsstartdt(), "YYYY-MM-DD(dd)"); }

    /* ========================= */
    /* is */
    /* ========================= */
    isEnd()                     { return this.getClsstatus()    === GGF.Cls.Clsstatus.END; }
    isCancel()                  { return this.getClsstatus()    === GGF.Cls.Clsstatus.CANCEL; }
    isClsstatusEdit()           { return this.getClsstatus()    === GGF.Cls.Clsstatus.EDIT; }
    isClsstatusIng()            { return this.getClsstatus()    === GGF.Cls.Clsstatus.ING; }
    isClsstatusEnd()            { return this.getClsstatus()    === GGF.Cls.Clsstatus.END; }
    isClssettleflgEdit()        { return this.getClssettleflg() === GGF.Cls.Clssettleflg.EDIT; }

    /* ========================= */
    /* make with buttons */
    /* ========================= */
    make(btnHtml="")
    {
        let html = "";
        let model = this;
        html +=
        `
            <div class="MClss-make-div-modelTop common-div-card">
                <table class="common-tbl-imglabel" label-size="3rd">
                    <tbody>
                        <tr>
                            <td><div class="common-img-label" style="background-image:url('${model.getGrpimgPath()}')"></div></td>
                            <td><span class="common-tag-block common-tag-fontsize10">${model.getGrpname()}</span></td>
                        </tr>
                    </tbody>
                </table>
                <div class="common-div-cushionUp">
                    <div class="common-tag-block">
                        <span class="common-tag-fontsize11 common-tag-strong">일정</span>
                        <span class="common-tag-fontsize09"></span>
                    </div>
                    <span class="common-tag-block">${model.getClstitle()}</span>
                    <div class="common-div-cushionUD">
                        ${true         ? `<span class="common-tag-inlineBlock">${model.getClsstatusCard()}</span>` : ""}
                        ${this.isEnd() ? `<span class="common-tag-inlineBlock">${model.getClssettleflgCard()}</span>` : ""}
                    </div>
                    <div class="common-div-cushionUp common-div-btnList common-tag-fontsize09">
                        ${btnHtml}
                    </div>
                </div>
            </div>
        `;
        return html;
    }


}

class MClss extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MCls(dat));
        }
    }

    /* ========================= */
    /* ========================= */
    make(el="")
    {
        let html = "";
        for(let i in this.getModels())
        {
            let model = this.getModels()[i];
            let isManager = GGstorage.Prj.isManagerOfGrp(model.getGrpno());

            /* ----- */
            /* clsstatus 에 따른 버튼표시 */
            /* ----- */
            let btnHtml = `<button class="common-btn-outline commonEvent-tag-hyperlink" hyperlink="${Navigation.Page.F00Class000Detail}" hyperlink-viewmode="page" ${model.getPk()}>상세보기</button>`;
            if(isManager)
            {
                switch(model.getClsstatus())
                {
                    case GGF.Cls.Clsstatus.EDIT:
                    {
                        btnHtml += `&nbsp;<button class="common-btn-outline commonEvent-tag-hyperlink" hyperlink="${Navigation.Page.F10ClassUpdate000Default}" hyperlink-viewmode="page" option="update" ${model.getPk()}>일정수정</button>`;
                        btnHtml += `&nbsp;<button class="common-btn-outline commonEvent-tag-hyperlink" hyperlink="${Navigation.Page.F10ClassUpdate010LineupUpdate}" hyperlink-viewmode="page" option="update" ${model.getPk()}>참가설정</button>`;
                        btnHtml += `&nbsp;<button class="common-btn-outline MClss-make-btn-editToIng" ${model.getPk()}>일정공개</button>`;
                        btnHtml += `&nbsp;<button class="common-btn-outline MClss-make-btn-deleteCls" btn-type="delete" ${model.getPk()}>일정삭제</button>`;
                        break;
                    }
                    case GGF.Cls.Clsstatus.ING:
                    {
                        btnHtml += `&nbsp;<button class="common-btn-outline commonEvent-tag-hyperlink" hyperlink="${Navigation.Page.F10ClassUpdate010LineupUpdate}" hyperlink-viewmode="page" option="update" ${model.getPk()}>참가설정</button>`;
                        btnHtml += `&nbsp;<button class="common-btn-outline MClss-make-btn-ingToEnd" ${model.getPk()}>일정종료</button>`;
                        btnHtml += `&nbsp;<button class="common-btn-outline commonEvent-tag-hyperlink" hyperlink="${Navigation.Page.F10ClassUpdate030Cancel}" hyperlink-viewmode="page" btn-type="cancel" ${model.getPk()}>일정취소</button>`;
                        break;
                    }
                    case GGF.Cls.Clsstatus.END:
                    {
                        switch(model.getClssettleflg())
                        {
                            case GGF.Cls.Clssettleflg.EDIT:
                                btnHtml += `&nbsp;<button class="common-btn-outline commonEvent-tag-hyperlink" hyperlink="${Navigation.Page.F10ClassUpdate020SettleEdit}"   hyperlink-viewmode="page" ${model.getPk()}>정산입력</button>`;
                                btnHtml += `&nbsp;<button class="common-btn-outline commonEvent-tag-hyperlink" hyperlink="${Navigation.Page.F10ClassUpdate026Purchase}" hyperlink-viewmode="page" ${model.getPk()}>구매입력</button>`;
                                break;
                            case GGF.Cls.Clssettleflg.DONE:
                                break;
                        }
                        break;
                    }
                }
            }

            /* make html */
            html += model.make(btnHtml);
        }
        $(el).html(html);

        /* 일정공개 */
        $(`${el} .MClss-make-btn-editToIng`).off("click").on("click", function()
        {
            let grpno = $(this).attr("grpno");
            let clsno = $(this).attr("clsno");
            let process = function()
            {
                Common.showProgress();
                setTimeout(function()
                {
                    let mApiResponse = Api.Cls.updateClsstatusEditToIng(grpno, clsno);
                    if(mApiResponse.isSuccess())
                    {
                        Common.hideProgress();
                        Navigation.executeShow();
                        return;
                    }
                    Common.hideProgress();
                }, ajaxDelayTime);
            }
            Common.confirm2("해당 일정이 멤버에게 공개됩니다. 계속하시겠습니까?", process);
        });

        /* 일정삭제 */
        $(`${el} .MClss-make-btn-deleteCls`).off("click").on("click", function()
        {
            let grpno = $(this).attr("grpno");
            let clsno = $(this).attr("clsno");
            let process = function()
            {
                Common.showProgress();
                setTimeout(function()
                {
                    let mApiResponse = Api.Cls.deleteByPkWithSubForMng(grpno, clsno);
                    if(mApiResponse.isSuccess())
                    {
                        Common.hideProgress();
                        Navigation.executeShow();
                        return;
                    }
                    Common.hideProgress();
                }, ajaxDelayTime);
            }
            Common.confirm2("일정이 삭제됩니다. 계속하시겠습니까?", process);
        });

        /* 일정종료 */
        $(`${el} .MClss-make-btn-ingToEnd`).off("click").on("click", function()
        {
            let grpno = $(this).attr("grpno");
            let clsno = $(this).attr("clsno");
            let process = function()
            {
                Common.showProgress();
                setTimeout(function()
                {
                    let mApiResponse = Api.Cls.updateClsstatusIngToEnd(grpno, clsno);
                    if(mApiResponse.isSuccess())
                    {
                        Common.hideProgress();
                        Navigation.executeShow();
                        return;
                    }
                    Common.hideProgress();
                }, ajaxDelayTime);
            }
            Common.confirm2("일정이 종료됩니다. 이후 정산을 꼭 진행하시기 바랍니다. 계속하시겠습니까?", process);
        });
    }

    /* ========================= */
    /* 선택용 */
    /* ========================= */
    makeForChoose(el="")
    {
        /* html */
        let html = "";
        for(let i in this.getModels())
        {
            let model = this.getModels()[i];
            let btnHtml = `<button class="common-btn-inline MClss-make-btn-choose" ${model.getPk()}>선택하기</button>`;
            html += model.make(btnHtml);
        }
        $(el).html(this.mergePagenation(html));
    }

    /* ========================= */
    /* 선택용 */
    /* ========================= */
    makeTableForFinanceReflect(el="")
    {
        /* html */
        let html = "";
        for(let i in this.getModels())
        {
            let model = this.getModels()[i];
            let grpfinancereflectflg = model.getGrpfinancereflectflg();
            html +=
            `
                <tr>
                    <td style="text-align:center;">
                        <button class="common-btn-outline MClss-makeTableForFinanceReflect-btn-toY" ${model.getPk()} ${grpfinancereflectflg != GGF.Cls.Grpfinancereflectflg.N ? `style="display:none;"` : ``}>반영설정</button>
                        <button class="common-btn-outline MClss-makeTableForFinanceReflect-btn-toN" ${model.getPk()} ${grpfinancereflectflg != GGF.Cls.Grpfinancereflectflg.Y ? `style="display:none;"` : ``}>반영해제</button>
                    </td>
                    <td style="text-align:center;">${model.getGrpfinancereflectflgFont()}</td>
                    <td>${model.getClsstartdtYMDdd()}</td>
                    <td>${model.getClstitle()}</td>
                </tr>
            `;
        }

        /* table */
        html =
        `
            <div class="common-div-scrollX">
                <table class="common-tbl-normal" tbl-type="rowborder" style="white-space:nowrap;">
                    <thead>
                        <tr>
                            <th>정산반영</th>
                            <th>반영여부</th>
                            <th>일정일자</th>
                            <th>일정</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${html}
                    </tbody>
                </table>
            </div>
        `;
        $(el).html(this.mergePagenation(html));

        /* event */
        $(`${el} .MClss-makeTableForFinanceReflect-btn-toY`).off("click").on("click", function()
        {
            let grpno = $(this).attr("grpno");
            let clsno = $(this).attr("clsno");
            Common.showProgress();
            setTimeout(function()
            {
                let mApiResponse = Api.Cls.updateGrpfinancereflectflgToYForMng(grpno, clsno);
                if(mApiResponse.isSuccess())
                {
                    Common.hideProgress();
                    Navigation.executeShow();
                    return;
                }
                Common.hideProgress();
            }, ajaxDelayTime);
        });

        $(`${el} .MClss-makeTableForFinanceReflect-btn-toN`).off("click").on("click", function()
        {
            let grpno = $(this).attr("grpno");
            let clsno = $(this).attr("clsno");
            Common.showProgress();
            setTimeout(function()
            {
                let mApiResponse = Api.Cls.updateGrpfinancereflectflgToNForMng(grpno, clsno);
                if(mApiResponse.isSuccess())
                {
                    Common.hideProgress();
                    Navigation.executeShow();
                    return;
                }
                Common.hideProgress();
            }, ajaxDelayTime);
        });

    }

}

class MClslineupa
{
    constructor(dat)
    {
        /* data */      this.grpno      = GGC.Common.char(dat.grpno);
        /* data */      this.clsno      = GGC.Common.char(dat.clsno);
        /* data */      this.lineupidx  = GGC.Common.int(dat.lineupidx);
        /* data */      this.lineupname = GGC.Common.varchar(dat.lineupname);
        /* optional */  this.clstitle   = GGC.Common.varchar(dat.clstitle);
        /* optional */  this.clsstartdt = GGC.Common.varchar(dat.clsstartdt);
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    /* data */  getGrpno()      { return this.grpno; }
    /* data */  getClsno()      { return this.clsno; }
    /* data */  getLineupidx()  { return this.lineupidx; }
    /* data */  getLineupname()  { return this.lineupname; }
    /* data */  getClstitle()   { return this.clstitle; }
    /* data */  getClsstartdt() { return this.clsstartdt; }
}

class MClslineupas extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MClslineupa(dat));
        }
    }
}


class MClslineupb
{
    constructor(dat)
    {
        /* data */      this.grpno              = GGC.Common.char(dat.grpno);
        /* data */      this.clsno              = GGC.Common.char(dat.clsno);
        /* data */      this.lineupidx          = GGC.Common.char(dat.lineupidx);
        /* data */      this.lineupname         = GGC.Common.char(dat.lineupname);
        /* data */      this.orderno            = GGC.Common.int(dat.orderno);
        /* data */      this.battingflg         = GGC.Common.enum(dat.battingflg);
        /* data */      this.position           = GGC.Common.char(dat.position);
        /* data */      this.userno             = GGC.Common.char(dat.userno);
        /* data */      this.username           = GGC.Common.char(dat.username);
        /* data */      this.userregdt          = GGC.Common.datetime(dat.userregdt);
        /* data */      this.bill               = GGC.Common.int(dat.bill);
        /* data */      this.prepaidflg         = GGC.Common.enum(dat.prepaidflg);
        /* data */      this.etc                = GGC.Common.varchar(dat.etc);
        /* data */      this.clsstatus          = GGC.Common.char(dat.clsstatus);
        /* data */      this.clssettleflg       = GGC.Common.char(dat.clssettleflg);
        /* data */      this.cnt                = GGC.Common.int(dat.cnt);
        /* data */      this.memberpoint        = GGC.Common.int(dat.memberpoint);
        /* data */      this.applyername        = GGC.Common.char(dat.applyername);
        /* custom */    this.memberpointwon     = GGC.Common.priceWon(this.memberpoint);
        /* custom */    this.pk                 = `grpno="${this.grpno}" clsno="${this.clsno}" lineupidx="${this.lineupidx}" orderno="${this.orderno}"`;
        /* other */     this.billprepaid        = dat.billprepaid == undefined ? 0 : GGC.Common.int(dat.billprepaid);
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    /* data */      getGrpno() { return this.grpno; }
    /* data */      getClsno() { return this.clsno; }
    /* data */      getLineupidx() { return this.lineupidx; }
    /* data */      getLineupname() { return this.lineupname; }
    /* data */      getOrderno() { return this.orderno; }
    /* data */      getBattingflg() { return this.battingflg; }
    /* data */      getPosition() { return this.position; }
    /* data */      getUserno() { return this.userno; }
    /* data */      getUsername() { return this.username; }
    /* data */      getBill() { return this.bill; }
    /* data */      getPrepaidflg() { return this.prepaidflg; }
    /* data */      getEtc() { return this.etc; }
    /* data */      getClsstatus() { return this.clsstatus; }
    /* data */      getClssettleflg() { return this.clssettleflg; }
    /* data */      getUserregdt() { return this.userregdt; }
    /* data */      getCnt() { return this.cnt; }
    /* data */      getMemberpoint() { return this.memberpoint; }
    /* data */      getApplyername() { return this.applyername; }
    /* custom */    getMemberpointWon() { return this.memberpointwon; }
    /* custom */    getPk() { return this.pk; }
    /* other */     getBillprepaid() { return this.billprepaid; }

    /* ========================= */
    /* fields - additional */
    /* ========================= */
    /* data */ getBillWon() { return GGC.Common.priceWon(this.bill); }
    /* data */ getPrepaidflgCvrt() { return GGC.Clslineupb.prepaidflgCvrt(this.prepaidflg); }
    /* data */ getPrepaidflgCard() { return GGC.Clslineupb.prepaidflgCard(this.prepaidflg); }
    /* data */ getPrepaidflgFont() { return GGC.Clslineupb.prepaidflgFont(this.prepaidflg); }

    /* ========================= */
    /* fields - flg */
    /* ========================= */
    isBattingflgY() { return this.getBattingflg() === GGF.Y; }
    isPrepaid()     { return this.getPrepaidflg() === GGF.Y; }
    hasApplyer()    { return !Common.isEmpty(this.getUsername()); }

}

class MClslineupbs extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MClslineupb(dat));
        }
    }

    groupByForSettle()
    {
        let arr = [];
        for(let i in this.models)
        {
            let model = this.models[i];
            let grpno = model.getGrpno();
            let clsno = model.getClsno();
            let userno = model.getUserno();
            let username = model.getUsername();
            let bill = model.getBill();
            let memberpoint = model.getMemberpoint();
            let prepaidflg = model.getPrepaidflg();

            // let dat =
            // {
            //     grpno        : grpno,
            //     clsno        : clsno,
            //     userno       : userno,
            //     username     : username,
            //     bill         : bill,
            //     memberpoint  : memberpoint,
            // };

            let isExist = false;
            for(let j in arr)
            {
                let dat = arr[j];
                if(dat.getGrpno() === grpno && dat.getClsno() === clsno && dat.getUserno() === userno)
                {
                    dat.bill += bill;
                    dat.billprepaid += prepaidflg === GGF.Y ? bill : 0;
                    dat.prepaidflg = dat.prepaidflg === GGF.Y ? GGF.Y : prepaidflg; // 선불 여부는 하나라도 Y면 Y
                    isExist = true;
                    break;
                }
            }

            if(!isExist)
            {
                let tmp =
                {
                    grpno        : grpno,
                    clsno        : clsno,
                    userno       : userno,
                    username     : username,
                    bill         : bill,
                    billprepaid  : prepaidflg === GGF.Y ? bill : 0,
                    prepaidflg   : prepaidflg,
                    memberpoint  : memberpoint,
                };
                let dat = new MClslineupb(tmp);
                arr.push(dat);
            }
        }
        return arr;
    }

}

class MClslineuptmpa
{
    constructor(dat)
    {
        /* data */  this.grpno        = GGC.Common.char(dat.grpno);
        /* data */  this.lineupgroup  = GGC.Common.char(dat.lineupgroup);
        /* data */  this.lineuptitle  = GGC.Common.varchar(dat.lineuptitle);
        /* data */  this.regdt        = GGC.Common.datetime(dat.regdt);
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    /* data */  getGrpno()          { return this.grpno; }
    /* data */  getLineupgroup()    { return this.lineupgroup; }
    /* data */  getLineuptitle()    { return this.lineuptitle; }
    /* data */  getRegdt()          { return this.regdt; }

    /* ========================= */
    /* make with buttons */
    /* ========================= */
    make(btnHtml="")
    {
        if(btnHtml != "")
            btnHtml = `<div class="common-div-cushionUp"><div class="common-div-btnList common-tag-fontsize09">${btnHtml}</div></div>`;
        let html =
        `
            <div class="MClslineuptmpa-make-div-modelTop common-div-card">
                <p class="common-p-entityTitle">라인업 템플릿</p>
                <table class="common-tbl-normal" tbl-type="noborder">
                    <tbody>
                        <tr>
                            <td><span class="common-tag-block common-tag-fontsize10">${this.getLineuptitle()}</span></td>
                        </tr>
                    </tbody>
                </table>
                ${btnHtml}
            </div>
        `;
        return html;
    }
}

class MClslineuptmpas extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MClslineuptmpa(dat));
        }
    }

    make()
    {

    }
}


class MClslineuptmpb
{
    constructor(dat)
    {
        /* data */  this.grpno          = GGC.Common.char(dat.grpno);
        /* data */  this.lineupgroup    = GGC.Common.char(dat.lineupgroup);
        /* data */  this.lineupidx      = GGC.Common.int(dat.lineupidx);
        /* data */  this.lineupname     = GGC.Common.varchar(dat.lineupname);
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    /* data */  getGrpno()       { return this.grpno; }
    /* data */  getLineupgroup() { return this.lineupgroup; }
    /* data */  getLineupidx()   { return this.lineupidx; }
    /* data */  getLineupname()  { return this.lineupname; }
}

class MClslineuptmpbs extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MClslineuptmpb(dat));
        }
    }
}


class MClslineuptmpc
{
    constructor(dat)
    {
        /* data */      this.grpno                = GGC.Common.char(dat.grpno);
        /* data */      this.lineupgroup          = GGC.Common.char(dat.lineupgroup);
        /* data */      this.lineupidx            = GGC.Common.char(dat.lineupidx);
        /* data */      this.orderno              = GGC.Common.int(dat.orderno);
        /* data */      this.battingflg           = GGC.Common.enum(dat.battingflg);
        /* data */      this.position             = GGC.Common.char(dat.position);
        /* data */      this.isfollowstandardbill = GGC.Common.enum(dat.isfollowstandardbill);
        /* data */      this.bill                 = GGC.Common.int(dat.bill);
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    /* data */  getGrpno()                { return this.grpno; }
    /* data */  getLineupgroup()          { return this.lineupgroup; }
    /* data */  getLineupidx()            { return this.lineupidx; }
    /* data */  getOrderno()              { return this.orderno; }
    /* data */  getBattingflg()           { return this.battingflg; }
    /* data */  getPosition()             { return this.position; }
    /* data */  getIsfollowstandardbill() { return this.isfollowstandardbill; }
    /* data */  getBill()                 { return this.bill; }

    isBattingflgY() { return this.battingflg === GGF.Y; }
}

class MClslineuptmpcs extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MClslineuptmpc(dat));
        }
    }
}


class MClspurchase
{
    constructor(dat)
    {
        /* data */      this.grpno = GGC.Common.char(dat.grpno);
        /* data */      this.clsno = GGC.Common.char(dat.clsno);
        /* data */      this.purchaseidx = GGC.Common.int(dat.purchaseidx);
        /* data */      this.productname = GGC.Common.char(dat.productname);
        /* data */      this.productbill = GGC.Common.int(dat.productbill);
        /* data */      this.purchasememo = GGC.Common.varchar(dat.purchasememo);
        /* data */      this.regdt = GGC.Common.datetime(dat.regdt);
        /* custom */    this.pk = `grpno="${this.grpno}" clsno="${this.clsno}" purchaseidx="${this.purchaseidx}"`;
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    /* data */      getGrpno() { return this.grpno; }
    /* data */      getClsno() { return this.clsno; }
    /* data */      getPurchaseidx() { return this.purchaseidx; }
    /* data */      getProductname() { return this.productname; }
    /* data */      getProductbill() { return this.productbill; }
    /* data */      getPurchasememo() { return this.purchasememo; }
    /* data */      getRegdt() { return this.regdt; }
    /* custom */    getProductbillWon() { return GGC.Common.priceWon(this.productbill); }
    /* custom */    getPk() { return this.pk; }

    /* ========================= */
    /* fields - flg */
    /* ========================= */
    // isManagerdepositflgYes() { return this.getManagerdepositflg() === GGF.Y; }

    /* ========================= */
    /* fields - additional */
    /* ========================= */
    // getClsstatusCard() { return GGC.Cls.clsstatusCard(this.getClsstatus()); }

}

class MClspurchases extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MClspurchase(dat));
        }
    }
}

class MClspurchasehist
{
    constructor(dat)
    {
        /* data */      this.grpno = GGC.Common.char(dat.grpno);
        /* data */      this.clsno = GGC.Common.char(dat.clsno);
        /* data */      this.histno = GGC.Common.int(dat.histno);
        /* data */      this.histtype = GGC.Common.enum(dat.histtype);
        /* data */      this.purchaseidx = GGC.Common.int(dat.purchaseidx);
        /* data */      this.productname = GGC.Common.varchar(dat.productname);
        /* data */      this.productbill = GGC.Common.int(dat.productbill);
        /* data */      this.productbillafter = GGC.Common.int(dat.productbillafter);
        /* data */      this.regdt = GGC.Common.datetime(dat.regdt);
        /* custom */    this.pk = `grpno="${this.grpno}" clsno="${this.clsno}" histno="${this.histno}"`;
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    /* data */
    getGrpno() { return this.grpno; }
    getClsno() { return this.clsno; }
    getHistno() { return this.histno; }
    getHisttype() { return this.histtype; }
    getPurchaseidx() { return this.purchaseidx; }
    getProductname() { return this.productname; }
    getProductbill() { return this.productbill; }
    getProductbillafter() { return this.productbillafter; }
    getRegdt() { return this.regdt; }

    /* custom */
    getPk() { return this.pk; }

    /* custom > custom */
    getHisttypeFont() { return GGC.Clspurchasehist.histtypeFont(this.getHisttype()); }
    getProductbillWon() { return GGC.Common.priceWon(this.productbill); }
    getProductbillafterWon() { return GGC.Common.priceWon(this.productbillafter); }

    /* ========================= */
    /* fields - flg */
    /* ========================= */

    /* ========================= */
    /* fields - additional */
    /* ========================= */

}

class MClspurchasehists extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MClspurchasehist(dat));
        }
    }

    /* ========================= */
    /* ========================= */
    makeTable(el="")
    {
        let html = "";
        for(let i in this.models)
        {
            let model = this.models[i];
            html +=
            `
                <tr>
                    <td col="histno"             >${model.getHistno()}</td>
                    <td col="histtype"           >${model.getHisttypeFont()}</td>
                    <td col="productname"        >${model.getProductname()}</td>
                    <td col="productbill"        >${model.getProductbillWon()}</td>
                    <td col="productbillafter"   >${model.getProductbillafterWon()}</td>
                    <td col="regdt"              >${model.getRegdt()}</td>
                </tr>
            `;
        }

        html =
        `
            <table class="MClspurchasehist-makeTable-mainTable common-tbl-normal" tbl-type="rowborder">
                <thead>
                    <tr>
                        <th>번호</th>
                        <th>이력타입</th>
                        <th>품목</th>
                        <th>변경전</th>
                        <th>변경후</th>
                        <th>등록일</th>
                    </tr>
                </thead>
                <tbody>
                    ${html}
                </tbody>
            </table>
        `;
        $(el).html(html);
    }

    /* ========================= */
    /* ========================= */
    make(el="")
    {

    }

}

class MClssettle
{
    constructor(dat)
    {
        /* data */      this.grpno                  = GGC.Common.char(dat.grpno);
        /* data */      this.clsno                  = GGC.Common.char(dat.clsno);
        /* data */      this.userno                 = GGC.Common.char(dat.userno);
        /* data */      this.billstandard           = GGC.Common.int(dat.billstandard);
        /* data */      this.billprepaid            = GGC.Common.int(dat.billprepaid);
        /* data */      this.billadjustment         = GGC.Common.int(dat.billadjustment);
        /* data */      this.billdiscount           = GGC.Common.int(dat.billdiscount);
        /* data */      this.billpointed            = GGC.Common.int(dat.billpointed);
        /* data */      this.billfinal              = GGC.Common.int(dat.billfinal);
        /* data */      this.billmemo               = GGC.Common.varchar(dat.billmemo);
        /* data */      this.settlestatus           = GGC.Common.char(dat.settlestatus);
        /* data */      this.settlemembdt           = GGC.Common.datetime(dat.settlemembdt);
        /* data */      this.settledonedt           = GGC.Common.datetime(dat.settledonedt);
        /* data */      this.settlelossdt           = GGC.Common.datetime(dat.settlelossdt);
        /* data */      this.regdt                  = GGC.Common.datetime(dat.regdt);
        /* data */      this.username               = GGC.Common.char(dat.username);
        /* data */      this.userid                 = GGC.Common.char(dat.userid);
        /* data */      this.clstitle               = GGC.Common.varchar(dat.clstitle);
        /* data */      this.clsstartdt             = GGC.Common.datetime(dat.clsstartdt);
        /* data */      this.clsclosedt             = GGC.Common.datetime(dat.clsclosedt);
        /* data */      this.clsground              = GGC.Common.varchar(dat.clsground);
        /* data */      this.grpmanagerid           = GGC.Common.char(dat.grpmanagerid);
        /* data */      this.grpname                = GGC.Common.char(dat.grpname);
        /* data */      this.bankname               = GGC.Common.char(dat.bankname);
        /* data */      this.baccacct               = GGC.Common.char(dat.baccacct);
        /* data */      this.baccname               = GGC.Common.char(dat.baccname);
        /* data */      this.grpm_point             = GGC.Common.int(dat.grpm_point);
        /* custom */    this.pk                     = `grpno="${this.grpno}" clsno="${this.clsno}" userno="${this.userno}"`;
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    /* data */
    getGrpno() { return this.grpno; }
    getClsno() { return this.clsno; }
    getUserno() { return this.userno; }
    getBillstandard() { return this.billstandard; }
    getBillprepaid() { return this.billprepaid; }
    getBilladjustment() { return this.billadjustment; }
    getBilldiscount() { return this.billdiscount; }
    getBillpointed() { return this.billpointed; }
    getBillfinal() { return this.billfinal; }
    getBillmemo() { return this.billmemo; }
    getSettlestatus() { return this.settlestatus; }
    getSettlemembdt() { return this.settlemembdt; }
    getSettledonedt() { return this.settledonedt; }
    getLossflg() { return this.lossflg; }
    getSettlelossdt() { return this.settlelossdt; }
    getRegdt() { return this.regdt; }
    getUsername() { return this.username; }
    getUserid() { return this.userid; }
    getClstitle() { return this.clstitle; }
    getClsstartdt() { return this.clsstartdt; }
    getClsclosedt() { return this.clsclosedt; }
    getClsground() { return this.clsground; }
    getGrpmanagerid() { return this.grpmanagerid; }
    getGrpname() { return this.grpname; }
    getBankname() { return this.bankname; }
    getBaccacct() { return this.baccacct; }
    getBaccname() { return this.baccname; }
    getGrpmPoint() { return this.grpm_point; }

    /* custom */
    getPk() { return this.pk; }

    /* custom > custom */
    getBillstandardWon() { return GGC.Common.priceWon(this.billstandard); }
    getBillprepaidWon() { return GGC.Common.priceWon(this.billprepaid); }
    getBilladjustmentWon() { return GGC.Common.priceWon(this.billadjustment); }
    getBilldiscountWon() { return GGC.Common.priceWon(this.billdiscount); }
    getBillpointedWon() { return GGC.Common.priceWon(this.billpointed); }
    getBillfinalWon() { return GGC.Common.priceWon(this.billfinal); }
    getGrpmPointWon() { return GGC.Common.priceWon(this.grpm_point); }
    getClsPeriod() { return GGdate.period(this.getClsstartdt(), this.getClsclosedt()); }
    getGrpmPointAfterSettle() { return this.getGrpmPoint() + this.getBillpointed();}
    getGrpmPointAfterSettleWon() { return GGC.Common.priceWon(this.getGrpmPointAfterSettle()); }

    /* ========================= */
    /* fields - cvrt */
    /* ========================= */
    getSettlestatusCvrt() { return GGC.Clssettle.settlestatusCvrt(this.getSettlestatus()); }
    getSettlestatusFont() { return GGC.Clssettle.settlestatusFont(this.getSettlestatus()); }
    getSettleStatusCard() { return GGC.Clssettle.settlestatusCard(this.getSettlestatus()); }

    /* ========================= */
    /* fields - flg */
    /* ========================= */
    isIn5MinWhenSettledonedt() { return Common.isEmpty(this.getSettledonedt()) ? false : GGdate.isIn5MinFromNow(this.getSettledonedt()); }
    hasBillprepaid() { return this.getBillprepaid() >= 1; }

    /* ========================= */
    /* fields - is */
    /* ========================= */
    isSettlestatusWait() { return this.getSettlestatus() === GGF.Clssettle.Settlestatus.WAIT; }
    isSettlestatusMemb() { return this.getSettlestatus() === GGF.Clssettle.Settlestatus.MEMB; }
    isSettlestatusDone() { return this.getSettlestatus() === GGF.Clssettle.Settlestatus.DONE; }
    isSettlestatusLoss() { return this.getSettlestatus() === GGF.Clssettle.Settlestatus.LOSS; }

    /* ========================= */
    /* fields - additional */
    /* ========================= */
    // getClsstatusCard() { return GGC.Cls.clsstatusCard(this.getClsstatus()); }

}

class MClssettles extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MClssettle(dat));
        }
    }

    /* ========================= */
    /* ========================= */
    make(el="")
    {
        let userid = GGstorage.getUserid();
        let html = "";
        for(let i in this.getModels())
        {
            let model = this.getModels()[i];
            let isManager = GGstorage.Prj.isManagerOfGrp(model.getGrpno());

            /* ----- */
            /* 상태에 따른 버튼표시 */
            /* ----- */
            let isMe = model.getUserid() == userid;
            let btnHtml   = "";
            let baccHtml  = "";

            /* 입금완료 : 나의 미입금이면서, 미입금상태라면, 완료된 상태가 아니라면 */
            /* 입금확인 : 관리자면서, 미임금상태라면 */
            /* 입금확인취소 : 관리자면서, 입금완료상태인데, 입금완료한지 5분 이내라면 */
            /* 손실처리 : 관리자면서, 입금완료 상태가 아니며, 손실처리상태가 아니라면 */
            switch(model.getSettlestatus())
            {
                case GGF.Clssettle.Settlestatus.WAIT :
                {
                    if(isMe)
                        btnHtml += ` <button class="common-btn-outline MClssettle-make-btn-settlestatusToMemb" ${model.getPk()} btn-type="normal">입금완료</button> `;

                    if(isManager)
                    {
                        btnHtml += ` <button class="common-btn-outline MClssettle-make-btn-settlestatusToDone" ${model.getPk()} btn-type="normal">입금확인</button> `;
                        btnHtml += ` <button class="common-btn-outline MClssettle-make-btn-settlestatusToLoss" ${model.getPk()} btn-type="delete">손실처리</button> `;
                    }
                    break;
                }
                case GGF.Clssettle.Settlestatus.MEMB :
                {
                    if(isManager)
                    {
                        btnHtml += ` <button class="common-btn-outline MClssettle-make-btn-settlestatusToDone" ${model.getPk()} btn-type="normal">입금확인</button> `;
                        btnHtml += ` <button class="common-btn-outline MClssettle-make-btn-settlestatusToLoss" ${model.getPk()} btn-type="delete">손실처리</button> `;
                    }
                    break;
                }
                case GGF.Clssettle.Settlestatus.DONE :
                {
                    if(isManager)
                    {
                        if(model.isIn5MinWhenSettledonedt())
                        btnHtml += ` <button class="common-btn-outline MClssettle-make-btn-settlestatusToWait" ${model.getPk()} btn-type="cancel">입금확인취소</button> `;
                        btnHtml += ` <button class="common-btn-outline MClssettle-make-btn-settlestatusToLoss" ${model.getPk()} btn-type="delete">손실처리</button> `;
                    }
                    break;
                }
                case GGF.Clssettle.Settlestatus.LOSS :
                {
                    if(isManager)
                        btnHtml += ` <button class="common-btn-outline MClssettle-make-btn-settlestatusToDone" ${model.getPk()} btn-type="normal">입금확인</button> `;
                    break;
                }
            }

            /* 미입금상태라면, 입금계좌표시 */
            if(model.isSettlestatusDone() == false)
            {
                baccHtml +=
                `
                    <div class="common-tag-marginUp08 common-tag-alignL common-tag-fontsize09">
                        <span class="common-tag-block">입금계좌</span>
                        <span class="common-tag-block">
                            <span style="vertical-align:middle">${model.getBankname()} ${model.getBaccacct()} ${model.getBaccname()}</span>
                            <button class="common-btn-outline MClssettle-make-btn-copyBacc" copytext="${model.getBankname()} ${model.getBaccacct()}" style="margin-left:0.2em;">복사</button>
                        </span>
                    </div>
                `;
            }

            /* ----- */
            /* make html */
            /* ----- */
            html +=
            `
                <div class="MGrpMembers-make-div-modelTop common-div-card">
                    <span class="common-tag-block common-tag-strong common-tag-cushionDw03">정산상세 - ${model.getUsername()}</span>
                    <span class="common-tag-block ">${model.getGrpname()}</span>
                    <span class="common-tag-block ">${model.getClstitle()}</span>
                    <span class="common-tag-block common-tag-colorGrey common-tag-fontsize08">${model.getClsPeriod()}</span>
                    <span class="common-tag-block common-tag-alignR common-tag-bold">${model.getBillfinalWon()}</span>
                    <span class="common-tag-block common-tag-alignR common-tag-colorGrey common-tag-fontsize08">기준금액:${model.getBillstandardWon()}</span>
                    <span class="common-tag-block common-tag-alignR common-tag-colorGrey common-tag-fontsize08">사전정산:${model.getBillprepaidWon()}</span>
                    <span class="common-tag-block common-tag-alignR common-tag-colorGrey common-tag-fontsize08">청구추가:${model.getBilladjustmentWon()}</span>
                    <span class="common-tag-block common-tag-alignR common-tag-colorGrey common-tag-fontsize08">청구차감:${model.getBilldiscountWon()}</span>
                    ${model.getBillpointed() >= 1  ? `<span class="common-tag-block common-tag-alignR common-tag-colorGrey common-tag-fontsize08">사전정산:${model.getBillpointedWon()}</span>` : ""}
                    ${model.getBillmemo()    != "" ? `<span class="common-tag-block common-tag-alignR common-tag-colorGrey common-tag-fontsize08">보정사유:${model.getBillmemo()}</span>` : ""}
                    <span class="common-tag-block common-tag-marginUp common-tag-fontsize09">
                        <button class="common-btn-outline commonEvent-tag-hyperlink" hyperlink="${Navigation.Page.F00Class000Detail}" hyperlink-viewmode="page" ${model.getPk()}>일정상세</button>
                        ${btnHtml}
                    </span>
                    ${baccHtml}
                    <div class="common-tag-positionAbsUR">${model.getSettleStatusCard()}</div>
                </div>
            `;
        }
        $(el).html(this.mergePagenation(html));

        /* 입금완료 */
        $(`${el} .MClssettle-make-btn-settlestatusToMemb`).off("click").on("click", function()
        {
            let grpno = $(this).attr("grpno");
            let clsno = $(this).attr("clsno");
            let userno = $(this).attr("userno");
            let process = function()
            {
                Common.showProgress();
                setTimeout(function()
                {
                    Api.Clssettle.updateSettlestatusToMembForUsr(grpno, clsno, userno, GGF.TOAST, GGF.TOAST);
                    Navigation.executeShow();

                    Common.hideProgress();
                }, ajaxDelayTime);
            }
            Common.confirm2("입금을 완료하셨습니까?", process);
        });

        /* 입금확인완료 */
        $(`${el} .MClssettle-make-btn-settlestatusToDone`).off("click").on("click", function()
        {
            let grpno = $(this).attr("grpno");
            let clsno = $(this).attr("clsno");
            let userno = $(this).attr("userno");
            let process = function()
            {
                Common.showProgress();
                setTimeout(function()
                {
                    Api.Clssettle.updateSettlestatusToDoneForFnc(grpno, clsno, userno, GGF.TOAST, GGF.TOAST);
                    Navigation.executeShow();

                    Common.hideProgress();
                }, ajaxDelayTime);
            }
            Common.confirm2("입금이 확인되셨습니까?", process);
        });

        /* 입금확인취소 */
        $(`${el} .MClssettle-make-btn-settlestatusToWait`).off("click").on("click", function()
        {
            let grpno = $(this).attr("grpno");
            let clsno = $(this).attr("clsno");
            let userno = $(this).attr("userno");
            let process = function()
            {
                Common.showProgress();
                setTimeout(function()
                {
                    Api.Clssettle.updateSettlestatusToWaitForFnc(grpno, clsno, userno, GGF.TOAST, GGF.TOAST);
                    Navigation.executeShow();

                    Common.hideProgress();
                }, ajaxDelayTime);
            }
            Common.confirm2("입금확인을 취소하시겠습니까?", process);
        });

        /* 손실처리 */
        $(`${el} .MClssettle-make-btn-settlestatusToLoss`).off("click").on("click", function()
        {
            let grpno = $(this).attr("grpno");
            let clsno = $(this).attr("clsno");
            let userno = $(this).attr("userno");
            let process = function()
            {
                Common.showProgress();
                setTimeout(function()
                {
                    Api.Clssettle.updateSettlestatusToLossForFnc(grpno, clsno, userno, GGF.TOAST, GGF.TOAST);
                    Navigation.executeShow();

                    Common.hideProgress();
                }, ajaxDelayTime);
            }
            Common.confirm2("손실처리를 진행하시겠습니까?", process);
        });

        /* 입금계좌 복사 */
        $(`${el} .MClssettle-make-btn-copyBacc`).off("click").on("click", function()
        {
            let copytext = $(this).attr("copytext");
            Common.copyToClipboard(copytext);
        });
    }

}

class MClssettlehist
{
    constructor(dat)
    {
        /* data */      this.grpno = GGC.Common.char(dat.grpno);
        /* data */      this.clsno = GGC.Common.char(dat.clsno);
        /* data */      this.histno = GGC.Common.int(dat.histno);
        /* data */      this.userno = GGC.Common.char(dat.userno);
        /* data */      this.histtype = GGC.Common.enum(dat.histtype);
        /* data */      this.billstandard = GGC.Common.int(dat.billstandard);
        /* data */      this.billprepaid = GGC.Common.int(dat.billprepaid);
        /* data */      this.billadjustment = GGC.Common.int(dat.billadjustment);
        /* data */      this.billdiscount = GGC.Common.int(dat.billdiscount);
        /* data */      this.billpointed = GGC.Common.int(dat.billpointed);
        /* data */      this.billfinal = GGC.Common.int(dat.billfinal);
        /* data */      this.billmemo = GGC.Common.varchar(dat.billmemo);
        /* data */      this.billfinalafter = GGC.Common.int(dat.billfinalafter);
        /* data */      this.regdt = GGC.Common.datetime(dat.regdt);
        /* data */      this.username = GGC.Common.char(dat.username);
        /* custom */    this.pk = `grpno="${this.grpno}" clsno="${this.clsno}" userno="${this.userno}" histno="${this.histno}"`;
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    /* data */
    getGrpno() { return this.grpno; }
    getClsno() { return this.clsno; }
    getHistno() { return this.histno; }
    getUserno() { return this.userno; }
    getHisttype() { return this.histtype; }
    getBillstandard() { return this.billstandard; }
    getBillprepaid() { return this.billprepaid; }
    getBilladjustment() { return this.billadjustment; }
    getBilldiscount() { return this.billdiscount; }
    getBillpointed() { return this.billpointed; }
    getBillfinal() { return this.billfinal; }
    getBillmemo() { return this.billmemo; }
    getBillfinalafter() { return this.billfinalafter; }
    getRegdt() { return this.regdt; }
    getUsername() { return this.username; }

    /* custom */
    getPk() { return this.pk; }

    /* custom > custom */
    getHisttypeFont() { return GGC.Clssettlehist.histtypeFont(this.getHisttype()); }
    getBillstandardWon() { return GGC.Common.priceWon(this.billstandard); }
    getBillprepaidWon() { return GGC.Common.priceWon(this.billprepaid); }
    getBilladjustmentWon() { return GGC.Common.priceWon(this.billadjustment); }
    getBilldiscountWon() { return GGC.Common.priceWon(this.billdiscount); }
    getBillpointedWon() { return GGC.Common.priceWon(this.billpointed); }
    getBillfinalWon() { return GGC.Common.priceWon(this.billfinal); }
    getBillfinalafterWon() { return GGC.Common.priceWon(this.billfinalafter); }

    /* ========================= */
    /* fields - flg */
    /* ========================= */

    /* ========================= */
    /* fields - additional */
    /* ========================= */

}

class MClssettlehists extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MClssettlehist(dat));
        }
    }

    /* ========================= */
    /* ========================= */
    makeTable(el="")
    {
        let html = "";
        for(let i in this.models)
        {
            let model = this.models[i];
            html +=
            `
                <tr>
                    <td col="username"           >${model.getUsername()}</td>
                    <td col="histtype"           >${model.getHisttypeFont()}</td>
                    <td col="billfinal"          >${model.getBillfinalWon()}</td>
                    <td col="billfinalafter"     >${model.getBillfinalafterWon()}</td>
                    <td col="billadjustment"     >${model.getBilladjustmentWon()}</td>
                    <td col="billdiscount"       >${model.getBilldiscountWon()}</td>
                    <td col="billpointed"        >${model.getBillpointedWon()}</td>
                    <td col="billmemo"           >${Common.ifempty(model.getBillmemo(), "-")}</td>
                    <td col="regdt"              >${model.getRegdt()}</td>
                </tr>
            `;
        }

        html =
        `
            <table class="MClssettlehist-makeTable-mainTable common-tbl-normal" tbl-type="rowborder">
                <thead>
                    <tr>
                        <th>청구대상</th>
                        <th>이력타입</th>
                        <th>변경전</th>
                        <th>변경후</th>
                        <th>청구추가</th>
                        <th>청구차감</th>
                        <th>잔여금사용</th>
                        <th>비고</th>
                        <th>등록일</th>
                    </tr>
                </thead>
                <tbody>
                    ${html}
                </tbody>
            </table>
        `;
        $(el).html(html);
    }

    /* ========================= */
    /* ========================= */
    make(el="")
    {

    }

}

class MClssettletmp extends MClssettle
{
    constructor(dat)
    {
        super(dat);
    }
}

class MClssettletmps extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MClssettletmp(dat));
        }
    }

}

class MGrp
{
    constructor(dat)
    {
        /* data */    this.grpno                = GGC.Common.char(dat.grpno);
        /* data */    this.grpmanager           = GGC.Common.char(dat.grpmanager);
        /* data */    this.grpimg               = GGC.Common.char(dat.grpimg);
        /* data */    this.grpname              = GGC.Common.char(dat.grpname);
        /* data */    this.modidt               = GGC.Common.datetime(dat.modidt);
        /* data */    this.regidt               = GGC.Common.datetime(dat.regidt);
        /* data */    this.grpmanager_name      = GGC.Common.varchar(dat.grpmanager_name);
        /* data */    this.grpmanager_phone     = GGC.Common.varchar(dat.grpmanager_phone);
        /* data */    this.bacctype             = GGC.Common.enum(dat.bacctype);
        /* data */    this.bacckey              = GGC.Common.char(dat.bacckey);
        /* data */    this.baccno               = GGC.Common.int(dat.baccno);
        /* data */    this.baccnickname         = GGC.Common.char(dat.baccnickname);
        /* data */    this.bacccode             = GGC.Common.char(dat.bacccode);
        /* data */    this.baccacct             = GGC.Common.char(dat.baccacct);
        /* data */    this.baccname             = GGC.Common.char(dat.baccname);
        /* data */    this.bankname             = GGC.Common.char(dat.bankname);
        /* custom */  this.grpimgPath           = GGC.Grp.grpimgPath(this.getGrpno(), this.getGrpimg(), false);
        /* custom */  this.pk                   = `grpno="${this.grpno}"`;
    }

    /* ========================= */
    /* getter */
    /* ========================= */

    /* data */
    getGrpno() { return this.grpno; }
    getGrpmanager() { return this.grpmanager; }
    getGrpimg() { return this.grpimg; }
    getGrpname() { return this.grpname; }
    getModidt() { return this.modidt; }
    getRegidt() { return this.regidt; }
    getGrpmanagerName() { return this.grpmanager_name; }
    getGrpmanagerPhone() { return this.grpmanager_phone; }
    getBacctype() { return this.bacctype; }
    getBacckey() { return this.bacckey; }
    getBaccno() { return this.baccno; }
    getBaccnickname() { return this.baccnickname; }
    getBacccode() { return this.bacccode; }
    getBaccacct() { return this.baccacct; }
    getBaccname() { return this.baccname; }
    getBankname() { return this.bankname; }

    /* custom */
    getGrpimgPath() { return this.grpimgPath; }

    /* custom */
    getPk() { return this.pk; }

    /* ========================= */
    /* make */
    /* ========================= */

    make(btnHtml="")
    {
        if(btnHtml != "")
        {
            btnHtml =
            `
                <tr>
                    <td colspan="2">
                        ${btnHtml}
                    </td>
                </tr>
            `;
        }

        let html = "";
        html +=
        `
            <div class="Mgrp-make-div-top" grpno="${this.getGrpno()}" grpmanager="${this.getGrpmanager()}">
                <table class="Mgrp-make-tbl-top">
                    <tbody>
                        <tr>
                            <td><div class="Mgrp-make-div-image" style="background-image:url('${this.getGrpimgPath()}')"></div></td>
                            <td>
                                <span class="common-tag-block common-tag-fontsize11">${this.getGrpname()}</span>
                                <span class="common-tag-block">
                                    <span class="common-tag-block common-tag-fontsize10">대표 ${this.getGrpmanagerName()}</span>
                                    <span class="common-tag-block common-tag-fontsize09">TEL. ${this.getGrpmanagerPhone()}</span>
                                </span>
                            </td>
                        </tr>
                        ${btnHtml}
                    </tbody>
                </table>
            </div>
        `;
        return html;
    }

}


class MGrps extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MGrp(dat));
        }
    } /* constructor */



    /* ========================= */
    /* make */
    /* ========================= */
    makeForView(el)       { this.make("makeForView", el); }
    makeForChooseGrp(el)  { this.make("makeForChooseGrp", el); }
    makeForChooseGrp2(el) { this.make("makeForChooseGrp2", el); }
    make(option, el)
    {
        let firstBtn = true;
        let html = "";
        for(let i in this.models)
        {
            let model = this.models[i];
            let buttonHtml = "";
            switch(option)
            {
                case "makeForView"        : { buttonHtml = `<button class="common-btn-outline commonEvent-tag-hyperlink" hyperlink="${Navigation.Page.D10DetailGrp}" hyperlink-viewmode="page" ${model.getPk()}>상세보기</button>`; break; }
                case "makeForChooseGrp"   : { buttonHtml = `<button class="common-btn-inline Mgrp-make-btn-login" grpno="${model.getGrpno()}">선택하기</button>`; break; }
                case "makeForChooseGrp2"  : { buttonHtml = `<button class="CUDE-btn-chooseGrp commonEvent-btn-radio common-btn-radio" radio_name="CUDE-btn-chooseGrp" tab="" grpno="${model.getGrpno()}">선택</button>`; break; }
            }
            html +=
            `
                <div class="Mgrp-make-div-top common-div-card" grpno="${model.getGrpno()}" grpmanager="${model.getGrpmanager()}">
                    <table class="Mgrp-make-tbl-top">
                        <tbody>
                            <tr>
                                <td><div class="Mgrp-make-div-image" style="background-image:url('${model.getGrpimgPath()}')"></div></td>
                                <td>
                                    <span class="common-tag-block common-tag-fontsize11">${model.getGrpname()}</span>
                                    <span class="common-tag-block">
                                        <span class="common-tag-block common-tag-fontsize10">대표 ${model.getGrpmanagerName()}</span>
                                        <span class="common-tag-block common-tag-fontsize09">TEL. ${model.getGrpmanagerPhone()}</span>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="common-tag-fontsize09" style="text-align:right;">
                                    ${buttonHtml}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            `;
            firstBtn = false;
        }
        $(el).html(html);

        $(`${el} .Mgrp-make-btn-login`).off("click").on("click", function()
        {
            let grpno = $(this).attr("grpno");
            Navigation.moveFrontPage(Navigation.Page.B11ManagerMainHome, {grpno: grpno});
        });
    }


}

class MGrpfncLoss
{
    constructor(dat)
    {
        /* data */      this.grpno = GGC.Common.char(dat.grpno);
        /* data */      this.lossidx = GGC.Common.int(dat.lossidx);
        /* data */      this.lossitem = GGC.Common.varchar(dat.lossitem);
        /* data */      this.losscost = -1 * GGC.Common.int(dat.losscost);
        /* data */      this.losscomment = GGC.Common.varchar(dat.losscomment);
        /* data */      this.regdt = GGC.Common.datetime(dat.regdt);
        /* data */      this.username = GGC.Common.varchar(dat.username);
    }

    /* ========================= */
    /* getter */
    /* ========================= */
    /* data */      getGrpno() { return this.grpno; }
    /* data */      getLossidx() { return this.lossidx; }
    /* data */      getLossitem() { return this.lossitem; }
    /* data */      getLosscost() { return this.losscost; }
    /* data */      getLosscomment() { return this.losscomment; }
    /* data */      getRegdt() { return this.regdt; }
    /* data */      getUsername() { return this.username; }
    /* custom */    getPk() { return `grpno="${this.getGrpno()}" lossidx="${this.getLossidx()}"`; }

    /* ========================= */
    /* convert */
    /* ========================= */
    getLosscostWonColor() { return GGC.Common.wonColor(this.getLosscost()); }

    /* ========================= */
    /* fields - flg */
    /* ========================= */
    isIn1DayWhenRegdt() { return Common.isEmpty(this.getRegdt()) ? false : GGdate.isIn1DayFromNow(this.getRegdt()); }
}


class MGrpfncLosses extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MGrpfncLoss(dat));
        }
    } /* constructor */

    makeTable(el="")
    {
        let html = "";
        for(let i in this.models)
        {
            let model = this.models[i];
            let disable = model.isIn1DayWhenRegdt() ? "" : "disabled";
            html +=
            `
                <tr>
                    <td col="delete"            ><button class="MGrpfncLoss-makeTable-btn-delete common-btn-outline" btn-type="cancel" ${model.getPk()} ${disable}>삭제</td>
                    <td col="lossidx"           >${model.getLossidx()}</td>
                    <td col="regdt"             >${model.getRegdt()}</td>
                    <td col="lossitem"          >${model.getLossitem()}</td>
                    <td col="losscost"          >${model.getLosscostWonColor()}</td>
                    <td col="losscomment"       >${model.getLosscomment()}</td>
                </tr>
            `;
        }

        html =
        `
            <div class="common-div-scrollX">
                <table class="MGrpfncLoss-makeTable-mainTable common-tbl-normal" tbl-type="rowborder">
                    <thead>
                        <tr>
                            <th>삭제</th>
                            <th>번호</th>
                            <th>등록일</th>
                            <th>손실품목</th>
                            <th>손실금액</th>
                            <th>비고</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${html}
                    </tbody>
                </table>
            </div>
        `;
        $(el).html(this.mergePagenation(html));

        /* event */
        $(`${el} .MGrpfncLoss-makeTable-btn-delete`).off("click").on("click", function()
        {
            let grpno = $(this).attr("grpno");
            let lossidx = $(this).attr("lossidx");
            let process = function()
            {
                Common.showProgress();
                setTimeout(function()
                {
                    try
                    {
                        let mApiResponse = Api.GrpfncLoss.deleteByPk(grpno, lossidx);
                        if(mApiResponse.isSuccess())
                            Navigation.executeShow();
                    }
                    catch(e)
                    {
                        console.error(e);
                    }
                    Common.hideProgress();
                }, ajaxDelayTime);
            }
            Common.confirm2("손실 내역을 삭제하시겠습니까?", process);
        });
    }

}

class MGrpfncPurchase
{
    constructor(dat)
    {
        /* data */      this.grpno = GGC.Common.char(dat.grpno);
        /* data */      this.purchaseidx = GGC.Common.int(dat.purchaseidx);
        /* data */      this.purchaseitem = GGC.Common.varchar(dat.purchaseitem);
        /* data */      this.purchasecost = GGC.Common.int(dat.purchasecost);
        /* data */      this.purchasecomment = GGC.Common.varchar(dat.purchasecomment);
        /* data */      this.regdt = GGC.Common.datetime(dat.regdt);
        /* data */      this.username = GGC.Common.varchar(dat.username);
    }

    /* ========================= */
    /* getter */
    /* ========================= */
    /* data */      getGrpno() { return this.grpno; }
    /* data */      getPurchaseidx() { return this.purchaseidx; }
    /* data */      getPurchaseitem() { return this.purchaseitem; }
    /* data */      getPurchasecost() { return this.purchasecost; }
    /* data */      getPurchasecomment() { return this.purchasecomment; }
    /* data */      getRegdt() { return this.regdt; }
    /* data */      getUsername() { return this.username; }
    /* custom */    getPk() { return `grpno="${this.getGrpno()}" purchaseidx="${this.getPurchaseidx()}"`; }

    /* ========================= */
    /* convert */
    /* ========================= */
    getPurchasecostWonColor() { return GGC.Common.wonColor(this.getPurchasecost()); }

    /* ========================= */
    /* fields - flg */
    /* ========================= */
    isIn1DayWhenRegdt() { return Common.isEmpty(this.getRegdt()) ? false : GGdate.isIn1DayFromNow(this.getRegdt()); }

}


class MGrpfncPurchases extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MGrpfncPurchase(dat));
        }
    } /* constructor */

    makeTable(el="")
    {
        let html = "";
        for(let i in this.models)
        {
            let model = this.models[i];
            let disable = model.isIn1DayWhenRegdt() ? "" : "disabled";

            html +=
            `
                <tr>
                    <td col="delete"                ><button class="MGrpfncPurchase-makeTable-btn-delete common-btn-outline" btn-type="cancel" ${model.getPk()} ${disable}>삭제</td>
                    <td col="purchaseidx"           >${model.getPurchaseidx()}</td>
                    <td col="regdt"                 >${model.getRegdt()}</td>
                    <td col="purchaseitem"          >${model.getPurchaseitem()}</td>
                    <td col="purchasecost"          >${model.getPurchasecostWonColor()}</td>
                    <td col="purchasecomment"       >${model.getPurchasecomment()}</td>
                </tr>
            `;
        }

        html =
        `
            <div class="common-div-scrollX">
                <table class="MGrpfncPurchase-makeTable-mainTable common-tbl-normal" tbl-type="rowborder">
                    <thead>
                        <tr>
                            <th>삭제</th>
                            <th>번호</th>
                            <th>등록일</th>
                            <th>품목</th>
                            <th>금액</th>
                            <th>비고</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${html}
                    </tbody>
                </table>
            </div>
        `;
        $(el).html(this.mergePagenation(html));

        /* event */
        $(`${el} .MGrpfncPurchase-makeTable-btn-delete`).off("click").on("click", function()
        {
            let grpno = $(this).attr("grpno");
            let purchaseidx = $(this).attr("purchaseidx");
            let process = function()
            {
                Common.showProgress();
                setTimeout(function()
                {
                    try
                    {
                        let mApiResponse = Api.GrpfncPurchase.deleteByPk(grpno, purchaseidx);
                        if(mApiResponse.isSuccess())
                            Navigation.executeShow();
                    }
                    catch(e)
                    {
                        console.error(e);
                    }
                    Common.hideProgress();
                }, ajaxDelayTime);
            }
            Common.confirm2("구매 내역을 삭제하시겠습니까?", process);
        });
    }

}

class MGrpfncSponsorship
{
    constructor(dat)
    {
        /* data */      this.grpno = GGC.Common.char(dat.grpno);
        /* data */      this.sponidx = GGC.Common.int(dat.sponidx);
        /* data */      this.sponuserno = GGC.Common.varchar(dat.sponuserno);
        /* data */      this.sponusername = GGC.Common.varchar(dat.sponusername);
        /* data */      this.spontype = GGC.Common.enum(dat.spontype);
        /* data */      this.sponitem = GGC.Common.varchar(dat.sponitem);
        /* data */      this.sponcost = GGC.Common.int(dat.sponcost);
        /* data */      this.sponcomment = GGC.Common.varchar(dat.sponcomment);
        /* data */      this.regdt = GGC.Common.datetime(dat.regdt);
        /* data */      this.username = GGC.Common.varchar(dat.username);
    }

    /* ========================= */
    /* getter */
    /* ========================= */
    /* data */      getGrpno() { return this.grpno; }
    /* data */      getSponidx() { return this.sponidx; }
    /* data */      getSponuserno() { return this.sponuserno; }
    /* data */      getSponusername() { return this.sponusername; }
    /* data */      getSpontype() { return this.spontype; }
    /* data */      getSponitem() { return this.sponitem; }
    /* data */      getSponcost() { return this.sponcost; }
    /* data */      getSponcomment() { return this.sponcomment; }
    /* data */      getRegdt() { return this.regdt; }
    /* data */      getUsername() { return this.username; }
    /* custom */    getPk() { return `grpno="${this.getGrpno()}" sponidx="${this.getSponidx()}"`; }

    /* ========================= */
    /* convert */
    /* ========================= */
    getSponcostWonColor() { return GGC.Common.wonColor(this.getSponcost()); }
    getSpontypeFont() { return GGC.GrpfncSponsorship.spontypeFont(this.getSpontype()); }
    getUsernameForDp() { return Common.isEmpty(this.getSponuserno()) ? this.getSponusername() : this.getUsername(); }

    /* ========================= */
    /* fields - flg */
    /* ========================= */
    isIn1DayWhenRegdt() { return Common.isEmpty(this.getRegdt()) ? false : GGdate.isIn1DayFromNow(this.getRegdt()); }

    getSponitemFinal()
    {
        switch(this.getSpontype())
        {
            case GGF.GrpfncSponsorship.Spontype.THING: return this.getSponitem();
            case GGF.GrpfncSponsorship.Spontype.MONEY: return GGC.GrpfncSponsorship.spontypeCvrt(this.getSpontype());
        }
        return null;
    }
}


class MGrpfncSponsorships extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MGrpfncSponsorship(dat));
        }
    } /* constructor */

    makeTable(el="")
    {
        let html = "";
        for(let i in this.models)
        {
            let model = this.models[i];
            let disable = model.isIn1DayWhenRegdt() ? "" : "disabled";

            html +=
            `
                <tr>
                    <td col="delete"            ><button class="MGrpfncSponsorship-makeTable-btn-delete common-btn-outline" btn-type="cancel" ${model.getPk()} ${disable}>삭제</td>
                    <td col="sponidx"           >${model.getSponidx()}</td>
                    <td col="regdt"             >${model.getRegdt()}</td>
                    <td col="username"          >${model.getUsernameForDp()}</td>
                    <td col="sponitem"          >${model.getSponitemFinal()}</td>
                    <td col="sponcost"          >${model.getSponcostWonColor()}</td>
                    <td col="sponcomment"       >${model.getSponcomment()}</td>
                </tr>
            `;
        }

        html =
        `
            <div class="common-div-scrollX">
                <table class="MGrpfncSponsorship-makeTable-mainTable common-tbl-normal" tbl-type="rowborder">
                    <thead>
                        <tr>
                            <th>삭제</th>
                            <th>번호</th>
                            <th>등록일</th>
                            <th>찬조자</th>
                            <th>품목</th>
                            <th>금액</th>
                            <th>비고</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${html}
                    </tbody>
                </table>
            </div>
        `;
        $(el).html(this.mergePagenation(html));

        /* event */
        $(`${el} .MGrpfncSponsorship-makeTable-btn-delete`).off("click").on("click", function()
        {
            let grpno = $(this).attr("grpno");
            let sponidx = $(this).attr("sponidx");
            let process = function()
            {
                Common.showProgress();
                setTimeout(function()
                {
                    try
                    {
                        let mApiResponse = Api.GrpfncSponsorship.deleteByPk(grpno, sponidx);
                        if(mApiResponse.isSuccess())
                            Navigation.executeShow();
                    }
                    catch(e)
                    {
                        console.error(e);
                    }
                    Common.hideProgress();
                }, ajaxDelayTime);
            }
            Common.confirm2("찬조 내역을 삭제하시겠습니까?", process);
        });
    }

}

class MGrpfnca
{
    constructor(dat)
    {
        /* data */      this.grpno                              =      GGC.Common.char(dat.grpno);
        /* data */      this.grpfnc_totalall                    =      GGC.Common.bigint(dat.grpfnc_totalall);
        /* data */      this.grpfnc_totalnormal                 =      GGC.Common.bigint(dat.grpfnc_totalnormal);
        /* data */      this.grpfnc_totalcls                    =      GGC.Common.bigint(dat.grpfnc_totalcls);
        /* data */      this.grpfnc_capitaltotal                =      GGC.Common.bigint(dat.grpfnc_capitaltotal);
        /* data */      this.grpfnc_sponsorshiptotal            =      GGC.Common.bigint(dat.grpfnc_sponsorshiptotal);
        /* data */      this.grpfnc_purchasetotal               = -1 * GGC.Common.bigint(dat.grpfnc_purchasetotal);
        /* data */      this.grpfnc_losstotal                   = -1 * GGC.Common.bigint(dat.grpfnc_losstotal);
        /* data */      this.grpfnc_clssalestotal               =      GGC.Common.bigint(dat.grpfnc_clssalestotal);
        /* data */      this.grpfnc_clssalesunpaidtotal         = -1 * GGC.Common.bigint(dat.grpfnc_clssalesunpaidtotal);
        /* data */      this.grpfnc_clssaleslosstotal           = -1 * GGC.Common.bigint(dat.grpfnc_clssaleslosstotal);
        /* data */      this.grpfnc_clspurchasetotal            = -1 * GGC.Common.bigint(dat.grpfnc_clspurchasetotal);
        /* data */      this.grpfnc_memberpointtotal            =      GGC.Common.bigint(dat.grpfnc_memberpointtotal);
        /* data */      this.modidt                             =      GGC.Common.datetime(dat.modidt);
        /* custom */    this.pk = `grpno="${this.grpno}"`;
    }

    /* ========================= */
    /* getter */
    /* ========================= */
    /* data */  getGrpno() { return this.grpno; }
    /* data */  getGrpfncTotalall() { return this.grpfnc_totalall; }
    /* data */  getGrpfncTotalnormal() { return this.grpfnc_totalnormal; }
    /* data */  getGrpfncTotalcls() { return this.grpfnc_totalcls; }
    /* data */  getGrpfncCapitaltotal() { return this.grpfnc_capitaltotal; }
    /* data */  getGrpfncSponsorshiptotal() { return this.grpfnc_sponsorshiptotal; }
    /* data */  getGrpfncPurchasetotal() { return this.grpfnc_purchasetotal; }
    /* data */  getGrpfncLosstotal() { return this.grpfnc_losstotal; }
    /* data */  getGrpfncClssalestotal() { return this.grpfnc_clssalestotal; }
    /* data */  getGrpfncClssalesunpaidtotal() { return this.grpfnc_clssalesunpaidtotal; }
    /* data */  getGrpfncClssaleslosstotal() { return this.grpfnc_clssaleslosstotal; }
    /* data */  getGrpfncClspurchasetotal() { return this.grpfnc_clspurchasetotal; }
    /* data */  getGrpfncMemberpointtotal() { return this.grpfnc_memberpointtotal; }
    /* data */  getModidt() { return this.modidt; }


    /* ========================= */
    /* make */
    /* ========================= */
    /* Won */      getGrpfncCapitaltotalWon() { return GGC.Common.priceWon(this.getGrpfncCapitaltotal()); }
    /* wonColor */ getGrpfncTotalallWonColor() { return GGC.Common.wonColor(this.getGrpfncTotalall()); }
    /* wonColor */ getGrpfncTotalnormalWonColor() { return GGC.Common.wonColor(this.getGrpfncTotalnormal()); }
    /* wonColor */ getGrpfncTotalclsWonColor() { return GGC.Common.wonColor(this.getGrpfncTotalcls()); }
    /* wonColor */ getGrpfncCapitaltotalWonColor() { return GGC.Common.wonColor(this.getGrpfncCapitaltotal()); }
    /* wonColor */ getGrpfncSponsorshiptotalWonColor() { return GGC.Common.wonColor(this.getGrpfncSponsorshiptotal()); }
    /* wonColor */ getGrpfncPurchasetotalWonColor() { return GGC.Common.wonColor(this.getGrpfncPurchasetotal()); }
    /* wonColor */ getGrpfncLosstotalWonColor() { return GGC.Common.wonColor(this.getGrpfncLosstotal()); }
    /* wonColor */ getGrpfncClssalestotalWonColor() { return GGC.Common.wonColor(this.getGrpfncClssalestotal()); }
    /* wonColor */ getGrpfncClssalesunpaidtotalWonColor() { return GGC.Common.wonColor(this.getGrpfncClssalesunpaidtotal()); }
    /* wonColor */ getGrpfncClssaleslosstotalWonColor() { return GGC.Common.wonColor(this.getGrpfncClssaleslosstotal()); }
    /* wonColor */ getGrpfncClspurchasetotalWonColor() { return GGC.Common.wonColor(this.getGrpfncClspurchasetotal()); }
    /* wonColor */ getGrpfncMemberpointtotalWonColor() { return GGC.Common.wonColor(this.getGrpfncMemberpointtotal()); }
    getModidtDiff() { return GGC.Common.dateDiff(this.getModidt()); }

}


class MGrpfncas extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MGrpfnca(dat));
        }
    } /* constructor */


}

class MGrpfnclog
{
    constructor(dat)
    {
        /* data */      this.grpno                              = GGC.Common.char(dat.grpno);
        /* data */      this.gfmlfield                          = GGC.Common.varchar(dat.gfmlfield);
        /* data */      this.gfmlkeyno                          = GGC.Common.int(dat.gfmlkeyno);
        /* data */      this.gfmlhistno                         = GGC.Common.int(dat.gfmlhistno);
        /* data */      this.gfmltype                           = GGC.Common.enum(dat.gfmltype);
        /* data */      this.gfmlcontent                        = GGC.Common.varchar(dat.gfmlcontent);
        /* data */      this.gfmlsummarypar                     = GGC.Common.int(dat.gfmlsummarypar);
        /* data */      this.gfmlsummaryreal                    = GGC.Common.int(dat.gfmlsummaryreal);
        /* data */      this.gfmlcomment                        = GGC.Common.varchar(dat.gfmlcomment);
        /* data */      this.regdt                              = GGC.Common.datetime(dat.regdt);
    }

    /* ========================= */
    /* getter */
    /* ========================= */
    /* data */  getGrpno() { return this.grpno; }
    /* data */  getGfmlfield() { return this.gfmlfield; }
    /* data */  getGfmlkeyno() { return this.gfmlkeyno; }
    /* data */  getGfmlhistno() { return this.gfmlhistno; }
    /* data */  getGfmltype() { return this.gfmltype; }
    /* data */  getGfmlcontent() { return this.gfmlcontent; }
    /* data */  getGfmlsummarypar() { return this.gfmlsummarypar; }
    /* data */  getGfmlsummaryreal() { return this.gfmlsummaryreal; }
    /* data */  getGfmlcomment() { return this.gfmlcomment; }
    /* data */  getRegdt() { return this.regdt; }
    /* pk */    getPk() { return `grpno="${this.grpno}" gfmlfield="${this.gfmlfield}" gfmlkeyno="${this.gfmlkeyno}" gfmlhistno="${this.gfmlhistno}"`; }

    /* ========================= */
    /* make */
    /* ========================= */
    getGfmlsummaryrealWon() { return GGC.Common.priceWon(this.getGfmlsummaryreal()); }

}


class MGrpfnclogs extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MGrpfnclog(dat));
        }
    } /* constructor */

    /* ========================= */
    /* 자본금 */
    /* ========================= */
    makeTableForCapital(el="")
    {
        /* html */
        let html = "";
        for(let i in this.getModels())
        {
            let model = this.getModels()[i];
            html +=
            `
                <tr>
                    <td style="text-align:center; white-space:nowrap;                        ">${model.getRegdt()}</td>
                    <td style="text-align:center; white-space:normal;                        ">${model.getGfmlsummaryrealWon()}</td>
                    <td style="text-align:left;   white-space:nowrap; word-break:break-word; ">${model.getGfmlcomment()}</td>
                </tr>
            `;
        }

        /* table */
        html =
        `
            <div class="common-div-scrollX">
                <table class="MGrpfnclog-makeTableForCapital-tbl-top common-tbl-normal" tbl-type="rowborder">
                    <thead>
                        <tr>
                            <th>변경일자</th>
                            <th>변경 후 자본금</th>
                            <th>비고</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${html}
                    </tbody>
                </table>
            </div>
        `;
        $(el).html(this.mergePagenation(html));
    }

}

class MGrpMember
{
    constructor(dat)
    {
        /* data */      this.grpno              = GGC.Common.char(dat.grpno);
        /* data */      this.userno             = GGC.Common.char(dat.userno);
        /* data */      this.grpmtype           = GGC.Common.enum(dat.grpmtype);
        /* data */      this.grpmposition       = GGC.Common.char(dat.grpmposition);
        /* data */      this.grpmfinauth        = GGC.Common.enum(dat.grpmfinauth);
        /* data */      this.grpmstatus         = GGC.Common.enum(dat.grpmstatus);
        /* data */      this.point              = GGC.Common.int(dat.point);
        /* data */      this.deletedt           = GGC.Common.datetime(dat.deletedt);
        /* data */      this.regidt             = GGC.Common.datetime(dat.regidt);
        /* data */      this.grpname            = GGC.Common.char(dat.grpname);
        /* data */      this.grpmanagerid       = GGC.Common.char(dat.grpmanagerid);
        /* data */      this.priv_phone         = GGC.Common.enum(dat.priv_phone);
        /* custom */    this.mUser              = _MCommon.fromDat(dat, MUser);
        /* custom */    this.pointWon           = GGC.Common.priceWon(dat.point);
        /* custom */    this.grpmtypeCvrt       = GGC.GrpMember.grpmtypeCvrt(this.grpmtype);
        /* custom */    this.pk                 = `grpno="${this.grpno}" userno="${this.userno}"`;
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    getGrpno() { return this.grpno; }
    getUserno() { return this.userno; }
    getGrpmtype() { return this.grpmtype; }
    getGrpmposition() { return this.grpmposition; }
    getGrpmfinauth() { return this.grpmfinauth; }
    getGrpmstatus() { return this.grpmstatus; }
    getDeletedt() { return this.deletedt; }
    getPoint() { return this.point; }
    getRegidt() { return this.regidt; }
    getGrpname() { return this.grpname; }
    getGrpmanagerid() { return this.grpmanagerid; }
    getPrivPhone() { return this.priv_phone; }

    /* custom */
    getMUser() { return this.mUser; }
    getPointWon() { return this.pointWon; }
    getGrpmtypeCvrt() { return this.grpmtypeCvrt; }
    getPk() { return this.pk; }

    /* custom > custom */
    getRegidtDate() { return this.regidt.substring(0, 10); }

    /* ========================= */
    /* fields - flg */
    /* ========================= */
    // isBattingflgY() { return this.getBattingflg() === GGF.Y; }
    // hasApplyer()    { return !Common.isEmpty(this.getUsername()); }
    isGrpmstatusDelete() { return this.getGrpmstatus() === GGF.GrpMember.Grpmstatus.DELETE; }
    isUsertypeTemp() { return this.getMUser().isUsertypeTemp(); }
    hasGrpmfinauth() { return this.getGrpmfinauth() === GGF.Y || this.getMUser().isAdmin(); }

    /* ========================= */
    /* fields - additional */
    /* ========================= */
    // getClsstatusCard() { return GGC.Cls.clsstatusCard(this.getClsstatus()); }

    make(buttonHtml="")
    {
        let model = this;
        let mUser = model.getMUser();

        /* buttonHtml */
        let buttonHtmlFinal = "";
        if(buttonHtml != "")
        {
            buttonHtmlFinal =
            `
                <span class="common-tag-block common-tag-marginUD common-tag-fontsize09">
                    ${buttonHtml}
                </span>
            `;
        }

        /* final html */
        let html =
        `
            <div class="MGrpMembers-make-div-modelTop common-div-card">
                <span class="common-tag-block common-tag-strong">
                    ${model.getGrpmtypeCvrt()}
                    ${Common.isEmpty(model.getGrpmposition()) ? "" : ` - ${model.getGrpmposition()}`}
                    ${mUser.isUsertypeTemp() ? "[임시]" : ""}</span>
                <span class="common-tag-block">${mUser.getName()} ${mUser.getBirthyear() != "" ? `(${mUser.getBirthyearShort()})` : ""}</span>
                <span class="common-tag-block common-tag-fontsize09">
                    ${mUser.getPhone()         != "" ? `<span class="common-tag-block common-tag-colorGrey">${mUser.getPhone()}</span>` : ""}
                    ${mUser.getAddress()       != "" ? `<span class="common-tag-block common-tag-colorGrey">${mUser.getAddress()}</span>` : ""}
                    ${mUser.getHascarflgCvrt() != "" ? `<span class="common-tag-block common-tag-colorGrey">${mUser.getHascarflgCvrt()}</span>` : ""}
                </span>
                ${buttonHtmlFinal}
            </div>
        `;
        return html;
    }

    /*
        let mGrpMemberDummy = MGrpMember.makeDummy();
        $("#GFSU-div-sponsorMember").html(mGrpMemberDummy);
     */
    static makeDummy()
    {
        let dat =
        {
            /* grpmember */
            grpno: "G0000000001",
            userno: "U0000000001",
            grpmtype: GGF.GrpMember.Grpmtype.MEMBER,
            grpmposition: "",
            grpmstatus: GGF.GrpMember.Grpmstatus.ACTIVE,
            point: 10000,
            deletedt: "2024-01-01 00:00:00",
            regidt: "2024-01-01 00:00:00",
            grpmanagerid: "U0000000001",

            /* user */
            name: "홍길동",
            birthyear: "1990",
            phone: "010-1234-5678",
            address: "서울시 강남구",
            hascarflg: GGF.Y,
            usertype: GGF.User.Usertype.NORMAL,
        };
        let model = new MGrpMember(dat);
        let mUser = model.getMUser();

        /* final html */
        let html =
        `
            <div class="MGrpMembers-make-div-modelTop common-div-card">
                <span class="common-tag-block common-tag-strong">
                    ${model.getGrpmtypeCvrt()}
                    ${Common.isEmpty(model.getGrpmposition()) ? "" : ` - ${model.getGrpmposition()}`}
                    ${mUser.isUsertypeTemp() ? "[임시]" : ""}</span>
                <span class="common-tag-block">${mUser.getName()} ${mUser.getBirthyear() != "" ? `(${mUser.getBirthyearShort()})` : ""}</span>
                <span class="common-tag-block common-tag-fontsize09">
                    ${mUser.getPhone()         != "" ? `<span class="common-tag-block common-tag-colorGrey">${mUser.getPhone()}</span>` : ""}
                    ${mUser.getAddress()       != "" ? `<span class="common-tag-block common-tag-colorGrey">${mUser.getAddress()}</span>` : ""}
                    ${mUser.getHascarflgCvrt() != "" ? `<span class="common-tag-block common-tag-colorGrey">${mUser.getHascarflgCvrt()}</span>` : ""}
                </span>
            </div>
        `;
        return html;
    }

}

class MGrpMembers extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MGrpMember(dat));
        }
    }

    /* ========================= */
    /* ========================= */
    makeForChoose(el) { this.make("makeForChoose", el); }
    make(option="", el="")
    {
        /* overloading */
        if(option != "" && el == "")
        {
            el = option;
            option = "";
        }

        /* =============== */
        /* loop models */
        /* =============== */
        let html = "";
        for(let i in this.getModels())
        {
            let model = this.getModels()[i];
            let isManager = GGstorage.Prj.isManagerOfGrp(model.getGrpno());

            /* --------------- */
            /* button */
            /* --------------- */
            let buttonHtml = "";
            switch(option)
            {
                case "":
                {
                    if(isManager)
                    {
                        buttonHtml += `<button class="common-btn-outline commonEvent-tag-hyperlink" hyperlink="${Navigation.Page.B71GrpMemberDetail}" hyperlink-viewmode="page" ${model.getPk()}>멤버상세</button>&nbsp;`;

                        if(model.isUsertypeTemp())
                        {
                            buttonHtml += `<button class="common-btn-outline commonEvent-tag-hyperlink" hyperlink="${Navigation.Page.B72GrpMemberMergeTemp}" hyperlink-viewmode="page" ${model.getPk()}>멤버병합</button>&nbsp;`;
                        }
                    }
                    break;
                }
                case "makeForChoose":
                {
                    buttonHtml += `<button class="common-btn-inline  MGrpMember-make-btn-choose" ${model.getPk()}>선택하기</button>&nbsp;`;
                    break;
                }
            }
            html += model.make(buttonHtml);
        }
        $(el).html(this.mergePagenation(html));
    }


}

class MGrpMemberPointhist
{
    constructor(dat)
    {
        /* data */      this.grpno              = GGC.Common.char(dat.grpno);
        /* data */      this.userno             = GGC.Common.char(dat.userno);
        /* data */      this.pointhistdt        = GGC.Common.date(dat.pointhistdt);
        /* data */      this.pointhistno        = GGC.Common.int(dat.pointhistno);
        /* data */      this.point              = GGC.Common.int(dat.point);
        /* data */      this.pointleft          = GGC.Common.int(dat.pointleft);
        /* data */      this.pointmemo          = GGC.Common.varchar(dat.pointmemo);
        /* data */      this.relclsno           = GGC.Common.char(dat.relclsno);
        /* data */      this.regidt             = GGC.Common.datetime(dat.regidt);
        /* custom */    this.pointtype          = GGC.GrpMemberPointhist.pointtype(dat.point);
        /* custom */    this.pointtypePretty    = GGC.GrpMemberPointhist.pointtypePretty(dat.point);
        /* custom */    this.pointPretty        = GGC.GrpMemberPointhist.pointPretty(dat.point);
        /* custom */    this.pointleftWon       = GGC.Common.priceWon(dat.pointleft);
        /* custom */    this.pk                 = `grpno="${this.grpno}" userno="${this.userno}" pointhistdt="${this.pointhistdt}" pointhistno="${this.pointhistno}"`;
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    getGrpno() { return this.grpno; }
    getUserno() { return this.userno; }
    getPointhistdt() { return this.pointhistdt; }
    getPointhistno() { return this.pointhistno; }
    getPoint() { return this.point; }
    getPointleft() { return this.pointleft; }
    getPointmemo() { return this.pointmemo; }
    getRelclsno() { return this.relclsno; }
    getRegidt() { return this.regidt; }

    /* custom */
    getPointtype() { return this.pointtype; }
    getPointtypePretty() { return this.pointtypePretty; }
    getPointPretty() { return this.pointPretty; }
    getPointleftWon() { return this.pointleftWon; }
    getPk() { return this.pk; }

    /* ========================= */
    /* fields - flg */
    /* ========================= */
    // isBattingflgY() { return this.getBattingflg() === GGF.Y; }
    // hasApplyer()    { return !Common.isEmpty(this.getUsername()); }

    /* ========================= */
    /* fields - additional */
    /* ========================= */
    // getClsstatusCard() { return GGC.Cls.clsstatusCard(this.getClsstatus()); }

}

class MGrpMemberPointhists extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MGrpMemberPointhist(dat));
        }
    }

    /* ========================= */
    /* ========================= */
    make(el="")
    {
        /* --------------- */
        /* loop clss */
        /* --------------- */
        let html = "";
        for(let i in this.getModels())
        {
            let model = this.getModels()[i];
            html +=
            `
                <div class="MGrpMemberPointhists-make-div-modelTop common-div-card" style="padding:0.2em 0.2em;">
                    <table class="common-tbl-normal" tbl-type="noborder" style="width:100%;">
                        <tbody>
                            <tr>
                                <td class="common-tag-alignL">
                                    <span class="common-tag-block common-tag-fontsize09">${model.getRegidt()}</span>
                                    <span class="common-tag-block common-tag-bold">${model.getPointmemo()}</span>
                                </td>
                                <td class="common-tag-alignR">
                                    <span class="common-tag-block common-tag-fontsize09">${model.getPointtypePretty()}</span>
                                    <span class="common-tag-block common-tag-bold">${model.getPointPretty()}</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="common-tag-alignR">
                                    <span class="common-tag-colorGrey common-tag-fontsize09">잔액 ${model.getPointleftWon()}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            `;
        }
        $(el).html(html);
    }

}

class MSystemBoard
{
    constructor(dat)
    {
        /* dat */     this.sbindex       = GGC.Common.int(dat.sbindex);
        /* dat */     this.sb_level       = GGC.Common.enum(dat.sb_level);
        /* dat */     this.sb_title       = GGC.Common.char(dat.sb_title);
        /* dat */     this.is_open        = GGC.Common.enum(dat.is_open);
        /* dat */     this.is_cus         = GGC.Common.enum(dat.is_cus);
        /* dat */     this.is_biz         = GGC.Common.enum(dat.is_biz);
        /* dat */     this.is_main        = GGC.Common.enum(dat.is_main);
        /* dat */     this.url            = GGC.Common.char(dat.url);
        /* dat */     this.modidt         = GGC.Common.datetime(dat.modidt);
        /* dat */     this.regidt         = GGC.Common.datetime(dat.regidt);
        /* custom */  this.fullUrl        = `${ServerInfo.getServerHost()}/src/z-res/_system_board/${this.getUrl()}`;
        /* custom */  this.regidtPretty   = GGdate.toYMDDHI(new Date(this.getRegidt()));
        /* pk */      this.pk             = `sbindex="${this.sbindex}"`;
    }

    /* dat */     getSbIndex()          { return this.sbindex; }
    /* dat */     getSbLevel()          { return this.sb_level; }
    /* dat */     getSbTitle()          { return this.sb_title; }
    /* dat */     getIsOpen()           { return this.is_open; }
    /* dat */     getIsCus()            { return this.is_cus; }
    /* dat */     getIsBiz()            { return this.is_biz; }
    /* dat */     getIsMain()           { return this.is_main; }
    /* dat */     getUrl()              { return this.url; }
    /* dat */     getModidt()           { return this.modidt; }
    /* dat */     getRegidt()           { return this.regidt; }
    /* custom */  getFullUrl()          { return this.fullUrl; }
    /* custom */  getRegidtPretty()     { return this.regidtPretty; }
    /* pk */      getPk()               { return this.pk; }

    makeMainBanner()
    {
        let html =
        `
            <div
                class="MSystemBoard-makeMainBanner-div-top commonEvent-tag-hyperlink common-tap"
                hyperlink="${Navigation.Page.Z22SystemBoardDetail}"
                hyperlink-viewmode="page"
                ${this.getPk()}
            >
                <div class="common-div-cushionD">
                    <span class="common-span-subcontent" style="margin-left:0.1em;">${this.getRegidtPretty()}</span><br>
                    <span class="common-span-content">${this.getSbTitle()}</span>
                </div>
                <img
                    class="MSystemBoard-makeMainBanner-img"
                    src="${this.getFullUrl()}/banner.png"
                >
            </div>
        `;
        return html;
    }

    makeHorizon()
    {
        let html =
        `
            <div
                class="MSystemBoard-makeHorizon-div-top commonEvent-tag-hyperlink common-tap"
                hyperlink="${Navigation.Page.Z22SystemBoardDetail}"
                hyperlink-viewmode="page"
                ${this.getPk()}
            >
                <table class="entity-common-tbl MSystemBoard-makeHorizon-table-info">
                    <tbody>
                        <tr>
                            <td>
                                <div class="MSystemBoard-makeHorizon-div-image" style="background-image:url('${this.getFullUrl()}/thumbnail.png')"></div>
                            </td>
                            <td>
                                <span class="common-span-block common-span-content">${this.getSbTitle()}</span>
                                <span class="common-span-block common-span-subcontent">${this.getRegidtPretty()}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        `;
        return html;
    }

}

class MSystemBoards extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MSystemBoard(dat));
        }
    }

    makeMainBanners(el="")
    {
        /* =============== */
        /* get each model's html */
        /* =============== */
        let html = "";
        for(let i in this.getModels())
            html += this.getModels()[i].makeMainBanner();

        /* =============== */
        /* set html */
        /* =============== */
        html =
        `
            <div class="MSystemBoards-makeMainBanners-top">
                ${html}
                <button class="MSystemBoards-makeMainBanners-btn-more common-btn-outline common-btn-more">공지사항 더 보기</button>
            </div>
        `;
        $(el).html(html);

        /* =============== */
        /* set event */
        /* =============== */

        /* 공지사항 더 보기 */
        $(`${el} .MSystemBoards-makeMainBanners-btn-more`).click(function()
        {
            Navigation.moveFrontPage(Navigation.Page.Z21SystemBoardList, {});
        });

    }

    makeHorizon(el="")
    {
        /* =============== */
        /* get each model's html */
        /* =============== */
        let html = "";
        for(let i in this.getModels())
            html += this.getModels()[i].makeHorizon();

        /* =============== */
        /* set html */
        /* =============== */
        html =
        `
            <div class="MSystemBoards-makeHorizon-div-top">
                ${html}
            </div>
        `;
        $(el).html(html);
    }
}

class MUser
{
    constructor(dat)
    {
        /* data */      this.userno         = GGC.Common.char(dat.userno);
        /* data */      this.usertype       = GGC.Common.enum(dat.usertype);
        /* data */      this.id             = GGC.Common.char(dat.id);
        /* data */      this.pw             = GGC.Common.char(dat.pw);
        /* data */      this.img            = GGC.Common.char(dat.img);
        /* data */      this.name           = GGC.Common.char(dat.name);
        /* data */      this.birthyear      = GGC.Common.date(dat.birthyear);
        /* data */      this.phone          = GGC.Common.char(dat.phone);
        /* data */      this.email          = GGC.Common.char(dat.email);
        /* data */      this.hascarflg      = GGC.Common.enum(dat.hascarflg);
        /* data */      this.address        = GGC.Common.char(dat.address);
        /* data */      this.point          = GGC.Common.int(dat.point);
        /* data */      this.adminflg       = GGC.Common.enum(dat.adminflg);
        /* data */      this.modidt         = GGC.Common.datetime(dat.modidt);
        /* data */      this.regidt         = GGC.Common.datetime(dat.regidt);
        /* data */      this.priv_phone     = GGC.Common.enum(dat.priv_phone);
        /* custom */    this.hascarflgCvrt  = GGC.User.hascarflg(dat.hascarflg);
        /* custom */    this.img_           = GGC.User.img_(this.userno, this.img, false);
        /* custom */    this.usertypeCvrt   = GGC.User.usertypeCvrt(this.usertype);
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    /* data */
    getUserno() { return this.userno; }
    getUsertype() { return this.usertype; }
    getId() { return this.id; }
    getPw() { return this.pw; }
    getImg() { return this.img; }
    getName() { return this.name; }
    getBirthyear() { return this.birthyear; }
    getPhone() { return this.phone; }
    getEmail() { return this.email; }
    getHascarflg() { return this.hascarflg; }
    getAddress() { return this.address; }
    getPoint() { return this.point; }
    getAdminflg() { return this.adminflg; }
    getModidt() { return this.modidt; }
    getRegidt() { return this.regidt; }
    getPrivPhone() { return this.priv_phone; }

    /* custom */
    getHascarflgCvrt() { return this.hascarflgCvrt; }
    getImg_() { return this.img_; }
    getUsertypeCvrt() { return this.usertypeCvrt; }

    /* birthyear short ver */
    getBirthyearShort()
    {
        let rslt = "출생연도없음";
        if(this.birthyear != "")
            rslt = this.birthyear.substring(2, 4);
        return rslt;
    }

    /* ========================= */
    /* is ? */
    /* ========================= */
    isUsertypeTemp() { return this.getUsertype() === GGF.User.Usertype.TEMP; }
    isAdmin() { return this.getAdminflg() === GGF.Y; }

}

class MUsers extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(var i in this.data)
        {
            var dat = this.data[i];
            this.models.push(new MUser(dat));
        }
    }

    /* ========================= */
    /* ========================= */
    make(el="")
    {
        let html = "";
        for(let i in this.getModels())
        {
            let model = this.getModels()[i];
            html +=
            `
                <div class="MUser-make-div-modelTop common-div-card">
                    <span class="common-tag-block common-tag-strong">유저</span>
                    <span class="common-tag-block">${model.getName()} ${model.getBirthyear() != "" ? `(${model.getBirthyearShort()})` : ""}</span>
                    <span class="common-tag-block common-tag-fontsize09">
                        ${model.getPhone()         != "" ? `<span class="common-tag-block common-tag-colorGrey">${model.getPhone()}</span>` : ""}
                        ${model.getHascarflgCvrt() != "" ? `<span class="common-tag-block common-tag-colorGrey">${model.getHascarflgCvrt()}</span>` : ""}
                    </span>
                </div>
            `;
        }
        $(el).html(html);
    }
}

class MScheduleall
{
    constructor(dat)
    {
        /* data */      this.sclyear                = GGC.Common.int(dat.sclyear);
        /* data */      this.sclmonth               = GGC.Common.tinyint(dat.sclmonth);
        /* data */      this.sclweek                = GGC.Common.tinyint(dat.sclweek);
        /* data */      this.sclstartdate           = GGC.Common.date(dat.sclstartdate);
        /* data */      this.sclclosedate           = GGC.Common.date(dat.sclclosedate);
        /* data */      this.sclsubmit              = Common.ifEmpty(GGC.Common.enum(dat.sclsubmit), "n");
        /* custom */    this.pointOfDate            = GGdate.getPointOfDate(new Date(), new Date(this.sclstartdate), new Date(this.sclclosedate));
        /* custom */    this.pk                     = `sclyear="${this.sclyear}" sclmonth="${this.sclmonth}" sclweek="${this.sclweek}"`;
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    /* data */      getSclyear() { return this.sclyear; }
    /* data */      getSclmonth() { return this.sclmonth; }
    /* data */      getSclweek() { return this.sclweek; }
    /* data */      getSclstartdate() { return this.sclstartdate; }
    /* data */      getSclclosedate() { return this.sclclosedate; }
    /* data */      getSclsubmit() { return this.sclsubmit; }
    /* custom */    getPointOfDate() { return this.pointOfDate; }
    /* custom */    getPk() { return this.pk; }

    /* ========================= */
    /* fields - flg */
    /* ========================= */
    isSameYearMonth(year, month) { return this.getSclyear() == year && this.getSclmonth() == month; }
    // isManagerdepositflgYes() { return this.getManagerdepositflg() === GGF.Y; }

    /* ========================= */
    /* fields - additional */
    /* ========================= */
    // getClsstatusCard() { return GGC.Cls.clsstatusCard(this.getClsstatus()); }

    makeTimeTable()
    {

    }


}

class MSchedulealls extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MScheduleall(dat));
        }
    }

    /* ========================= */
    /* ========================= */
    makeWeekList(el="")
    {
        let tbody = "";
        let now = new Date();
        let nowY = null;
        for(let i = 0; i < this.models.length; i++)
        {
            let model = this.models[i];
            let pointOfDate = model.getPointOfDate();

            /* by year */
            if(nowY != model.getSclyear())
            {
                nowY = model.getSclyear();
                tbody +=
                `
                    <tr>
                        <td colspan="2">
                            <div class="common-div-calendarSquareYear">
                                <span>${model.getSclyear()}년</span>
                            </div>
                        </td>
                    </tr>
                `;
            }

            /* by month */
            if(model.isSameYearMonth(nowY, now.getMonth() + 1))
                pointOfDate = GGF.GGdate.PointOfDate.WITHIN;
            tbody +=
            `
                <tr>
                    <td>
                        <div class="common-div-calendarSquare" common-type="alone" point-of-date="${pointOfDate}">
                            <div><span>${model.getSclmonth()}월</span></div>
                        </div>
                    </td>
                    <td>
                        <div class="common-div-calendarSquareWeekTop">
            `;

            /* by week */
            let nowM = model.getSclmonth();
            let skipCount = 0;
            for(let j = i; j < this.models.length; j++)
            {
                let modelJ = this.models[j];
                if(nowM != modelJ.getSclmonth())
                    break;

                /* set type */
                tbody +=
                `
                    <button
                        class="MScheduleall-btn-week common-btn-outline commonEvent-tag-hyperlink"
                        point-of-date="${modelJ.getPointOfDate()}"
                        ${modelJ.getPk()}
                        hyperlink="${Navigation.Page.G20ScheduleByWeek}"
                        hyperlink-viewmode="page"
                    >
                        ${modelJ.getSclweek()}주
                    </button>
                `;

                skipCount++;
            }
            i += skipCount - 1;

            /* close tr */
            tbody += "</div></td></tr>";
        }

        /* close table */
        let html =
        `
            <table class="common-tbl-normal" tbl-type="noborder">
                <tbody>
                    ${tbody}
                </tbody>
            </table>
        `;
        $(el).html(html);

        /* ========================= */
        /* set event */
        /* ========================= */
        // $(`${el} .MScheduleall-btn-week`).click(function()
        // {
        // });
    }

}

class MSchedulebyweek
{
    constructor(dat)
    {
        /* data */      this.userno                 = GGC.Common.char(dat.userno);
        /* data */      this.sclyear                = GGC.Common.int(dat.sclyear);
        /* data */      this.sclmonth               = GGC.Common.tinyint(dat.sclmonth);
        /* data */      this.sclweek                = GGC.Common.tinyint(dat.sclweek);
        /* data */      this.scletc                 = GGC.Common.varchar(dat.scletc);
        /* data */      this.sclsubmit              = GGC.Common.enum(dat.sclsubmit);
        /* data */      this.modidt                 = GGC.Common.datetime(dat.modidt);
        /* data */      this.regdt                  = GGC.Common.datetime(dat.regdt);
        /* data */      this.sclstartdate           = GGC.Common.date(dat.sclstartdate);
        /* data */      this.sclclosedate           = GGC.Common.date(dat.sclclosedate);
        /* custom */    this.pk                     = `userno="${this.userno}" sclyear="${this.sclyear}" sclmonth="${this.sclmonth}" sclweek="${this.sclweek}"`;
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    /* data */          getUserno() { return this.userno; }
    /* data */          getSclyear() { return this.sclyear; }
    /* data */          getSclmonth() { return this.sclmonth; }
    /* data */          getSclweek() { return this.sclweek; }
    /* data */          getScletc() { return this.scletc; }
    /* data */          getSclsubmit() { return this.sclsubmit; }
    /* data */          getModidt() { return this.modidt; }
    /* data */          getRegdt() { return this.regdt; }
    /* data */          getSclstartdate() { return this.sclstartdate; }
    /* data */          getSclclosedate() { return this.sclclosedate; }
    /* custom */        getPk() { return this.pk; }

    /* ========================= */
    /* fields - flg */
    /* ========================= */
    // isManagerdepositflgYes() { return this.getManagerdepositflg() === GGF.Y; }

    /* ========================= */
    /* fields - additional */
    /* ========================= */
    // getClsstatusCard() { return GGC.Cls.clsstatusCard(this.getClsstatus()); }

}

class MSchedulebyweeks extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MSchedulebyweek(dat));
        }
    }

    /* ========================= */
    /* ========================= */
    makeTimeTable(el="")
    {
        if(this.models.length == 0)
            return;

        /* =============== */
        /* make html */
        /* =============== */
        let modelFirst = this.models[0];
        let sclstartdate = new Date(modelFirst.getSclstartdate());
        let weekhtml = "";
        let timeArr = [];
        let timehtml = "";

        /* loop 7 days */
        for(let i = -1; i < 7; i++)
        {
            if(i == -1)
            {
                weekhtml += `<th>시간</th>`;
                continue;
            }
            if(i >= 0)
            {
                let date = new Date(sclstartdate);
                date = new Date(date.setDate(sclstartdate.getDate() + i));
                weekhtml += `<th date-day="${date.getDay()}">${GGdate.getD(date)}<br>${GGdate.getDDDD(date)}</th>`;
            }

            /* loop 6 to 23 */
            timeArr[i] = [];
            for(let j = 0; j < 18; j++)
                timeArr[i][j] = `<td date-day="${i}" time-hour="${j+6}"></td>`;
        }

        /* make timehtml */
        for(let j = 0; j < 18; j++)
        {
            timehtml += "<tr>";
            for(let i = -1; i < 7; i++)
            {
                if(i == -1)
                {
                    timehtml += `<th>${j+6}:00</th>`;
                    continue;
                }
                timehtml += timeArr[i][j];
            }
            timehtml += "</tr>";
        }

        /* make html */
        let html =
        `
            <table class="MSchedulebyweek-makeTimeTable-tbl-top common-tbl-normal" tbl-type="normal">
                <thead>
                    <tr>
                        ${weekhtml}
                    </tr>
                </thead>
                <tbody>
                    ${timehtml}
                </tbody>
            </table>
        `;
        $(el).html(html);
    }

}

class MSchedulebytime
{
    constructor(dat)
    {
        /* data */      this.userno                 = GGC.Common.char(dat.userno);
        /* data */      this.sclyear                = GGC.Common.int(dat.sclyear);
        /* data */      this.sclmonth               = GGC.Common.tinyint(dat.sclmonth);
        /* data */      this.sclweek                = GGC.Common.tinyint(dat.sclweek);
        /* data */      this.scldate                = GGC.Common.date(dat.scldate);
        /* data */      this.sclstarttime           = GGC.Common.time(dat.sclstarttime);
        /* data */      this.sclclosetime           = GGC.Common.time(dat.sclclosetime);
        /* data */      this.sclfreelevel           = GGC.Common.tinyint(dat.sclfreelevel);
        /* data */      this.modidt                 = GGC.Common.datetime(dat.modidt);
        /* data */      this.regdt                  = GGC.Common.datetime(dat.sclstartdate);
        /* data */      this.sclstartdate           = GGC.Common.date(dat.sclstartdate);
        /* data */      this.sclclosedate           = GGC.Common.date(dat.sclclosedate);
        /* custom */    this.pointOfDate            = GGdate.getPointOfDate(new Date(), new Date(this.sclstartdate), new Date(this.sclclosedate));
        /* custom */    this.pk                     = `sclyear="${this.sclyear}" sclmonth="${this.sclmonth}" sclweek="${this.sclweek}"`;
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    /* data */          getUserno() { return this.userno; }
    /* data */          getSclyear() { return this.sclyear; }
    /* data */          getSclmonth() { return this.sclmonth; }
    /* data */          getSclweek() { return this.sclweek; }
    /* data */          getScldate() { return this.scldate; }
    /* data */          getSclstarttime() { return this.sclstarttime; }
    /* data */          getSclclosetime() { return this.sclclosetime; }
    /* data */          getSclfreelevel() { return this.sclfreelevel; }
    /* data */          getModidt() { return this.modidt; }
    /* data */          getRegdt() { return this.regdt; }
    /* data */          getSclstartdate() { return this.sclstartdate; }
    /* data */          getSclclosedate() { return this.sclclosedate; }
    /* custom */        getPointOfDate() { return this.pointOfDate; }
    /* custom */        getPk() { return this.pk; }

    /* ========================= */
    /* fields - flg */
    /* ========================= */
    // isManagerdepositflgYes() { return this.getManagerdepositflg() === GGF.Y; }

    /* ========================= */
    /* fields - additional */
    /* ========================= */
    // getClsstatusCard() { return GGC.Cls.clsstatusCard(this.getClsstatus()); }

}

class MSchedulebytimes extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MSchedulebytime(dat));
        }
    }

    /* ========================= */
    /* ========================= */
    makeTimeTable(el="")
    {
        if(this.models.length == 0)
            return;

        /* =============== */
        /* make html */
        /* =============== */
        let modelF = this.models[0];
        let sclstartdate = modelF.getSclstartdate();
        let weekhtml = "";
        let timeArr = [];
        let timehtml = "";

        /* loop 7 days */
        for(let i = 0; i < 7; i++)
        {
            let date = new Date(sclstartdate);
            weekhtml += `<th date-day="${date.getDay()}">${GGdate.toMDdddd(date)}</th>`;
            sclstartdate = GGdate.addDays(sclstartdate, 1);

            /* loop 6 to 23 */
            timeArr[i] = [];
            for(let j = 0; j < 36; j++)
            {
                let hour = 6 + Math.floor(j / 2);
                let min  = (j % 2) * 30;
                timeArr[i][j] = `<td date-day="${i}" time-hour="${hour}" time-min="${min}"></td>`;
            }
        }

        /* make timehtml */
        for(let j = 0; j < 36; j++)
        {
            timehtml += "<tr>";
            for(let i = 0; i < 7; i++)
                timehtml += timeArr[i][j];
            timehtml += "</tr>";
        }

        /* make html */
        let html =
        `
            <table class="common-tbl-normal" tbl-type="noborder">
                <thead>
                    <tr>
                        ${weekhtml}
                    </tr>
                </thead>
                <tbody>
                    ${timehtml}
                </tbody>
            </table>
        `;
        $(el).html(html);
    }

}