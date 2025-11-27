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

    /* ================ */
    /* 크리티컬 에러 발생 시 */
    /* ================ */
    catchProc(e)
    {
        Common.hideProgress();
        console.error(e);
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
    confirm2(msg, okCallback)
    {
        Common.confirm
        ({
            title      : "알림",
            msg        : msg,
            okCallback : okCallback,
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