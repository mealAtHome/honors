var GGbase =
{
    load(callback)
    {
        /* ==================== */
        /* set */
        /* ==================== */
        let scriptHost = ServerInfo.getScriptHost();
        let scriptArr = [];
        let cssArr = [];

        /* ==================== */
        /* add script */
        /* ==================== */

        // for product
        $.i18n().load(
            {
                'jp' : `${scriptHost}/src/prj/script/_base/i18n/jp.json?v=${scriptVersion}`,
                'kr' : `${scriptHost}/src/prj/script/_base/i18n/kr.json?v=${scriptVersion}`,
            }
        );

        /* by device (common) */
        /* common  */ scriptArr.push(`${scriptHost}/src/common/script/_base/api.js?v=${scriptVersion}`);
        /* common  */ scriptArr.push(`${scriptHost}/src/common/script/_base/api-pr.js?v=${scriptVersion}`);
        /* common  */ scriptArr.push(`${scriptHost}/src/common/script/_base/choseong.js?v=${scriptVersion}`);
        /* common  */ scriptArr.push(`${scriptHost}/src/common/script/_base/common_.js?v=${scriptVersion}`);
        /* common  */ scriptArr.push(`${scriptHost}/src/common/script/_base/commonEvent.js?v=${scriptVersion}`);
        /* common  */ scriptArr.push(`${scriptHost}/src/common/script/_base/GGC.js?v=${scriptVersion}`);
        /* common  */ scriptArr.push(`${scriptHost}/src/common/script/_base/GGdate.js?v=${scriptVersion}`);
        /* common  */ scriptArr.push(`${scriptHost}/src/common/script/_base/GGdialog.js?v=${scriptVersion}`);
        /* common  */ scriptArr.push(`${scriptHost}/src/common/script/_base/GGhtml.js?v=${scriptVersion}`);
        /* common  */ scriptArr.push(`${scriptHost}/src/common/script/_base/GGpage.js?v=${scriptVersion}`);
        /* common  */ scriptArr.push(`${scriptHost}/src/common/script/_base/GGslideform.js?v=${scriptVersion}`);
        /* common  */ scriptArr.push(`${scriptHost}/src/common/script/_base/GGtoast.js?v=${scriptVersion}`);
        /* common  */ scriptArr.push(`${scriptHost}/src/common/script/_base/GGutils.js?v=${scriptVersion}`);
        /* common  */ scriptArr.push(`${scriptHost}/src/common/script/_base/navigation.js?v=${scriptVersion}`);
        /* common  */ scriptArr.push(`${scriptHost}/src/common/script/_base/GGvalid/GGvalid.js?v=${scriptVersion}`);
        /* common  */ scriptArr.push(`${scriptHost}/src/common/script/_base/GGvalid/GGvalid-api.js?v=${scriptVersion}`);
        /* common  */ scriptArr.push(`${scriptHost}/src/common/script/_base/GGvalid/GGvalid-common.js?v=${scriptVersion}`);
        /* common  */ scriptArr.push(`${scriptHost}/src/common/script/api/api-ref/api-govAddr.js?v=${scriptVersion}`);
        /* common  */ scriptArr.push(`${scriptHost}/src/common/script/api/api-ref/api-systemBoard.js?v=${scriptVersion}`);
        /* common  */ scriptArr.push(`${scriptHost}/src/common/script/model/model-common/_MCommon.js?v=${scriptVersion}`);
        /* common  */ scriptArr.push(`${scriptHost}/src/common/script/model/model-common/MApiResponse.js?v=${scriptVersion}`);
        /* common  */ scriptArr.push(`${scriptHost}/src/common/script/model/model-ref/MAddressSido.js?v=${scriptVersion}`);
        /* common  */ scriptArr.push(`${scriptHost}/src/common/script/model/model-ref/MAddressSigungu.js?v=${scriptVersion}`);
        /* common  */ scriptArr.push(`${scriptHost}/src/common/script/model/model-ref/MBank.js?v=${scriptVersion}`);
        /* common  */ scriptArr.push(`${scriptHost}/src/common/script/model/model-ref/MGovAddr.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/_base/Navigation.Prj.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/_base/CommonEvent.Prj.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/_base/GGC/GGC-all.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/_base/GGC/GGC-cls.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/_base/GGC/GGC-clssettlehist.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/_base/GGC/GGC-clspurchasehist.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/_base/GGC/GGC-grp.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/_base/GGC/GGC-user.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/_base/GGvalid/GGvalid-user.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/api/api-table/api-addressSido.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/api/api-table/api-addressSigungu.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/api/api-table/api-bank.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/api/api-table/api-bankaccount.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/api/api-table/api-cls.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/api/api-table/api-clslineup2.js?v=${scriptVersion}`)
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/api/api-table/api-clspurchase.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/api/api-table/api-clspurchasehist.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/api/api-table/api-clssettle.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/api/api-table/api-clssettlehist.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/api/api-table/api-clssettletmp.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/api/api-table/api-grp.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/api/api-table/api-Grpformng.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/api/api-table/api-grpMember.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/api/api-table/api-grpMemberPointhist.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/api/api-table/api-systemBoard.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/api/api-table/api-user.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/api/api-table/Api.Scheduleall.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/api/api-table/Api.Schedulebyweek.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/model/model-table/MBankaccount.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/model/model-table/MCls.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/model/model-table/MClslineup2.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/model/model-table/MClspurchase.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/model/model-table/MClspurchasehist.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/model/model-table/MClssettle.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/model/model-table/MClssettlehist.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/model/model-table/MClssettletmp.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/model/model-table/MGrp.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/model/model-table/MGrpformng.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/model/model-table/MGrpMember.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/model/model-table/MGrpMemberPointhist.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/model/model-table/MSystemBoard.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/model/model-table/MUser.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/model/model-table/MScheduleall.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/model/model-table/MSchedulebyweek.js?v=${scriptVersion}`);
        /* project */ scriptArr.push(`${scriptHost}/src/prj/script/model/model-table/MSchedulebytime.js?v=${scriptVersion}`);

        /* ==================== */
        /* add css */
        /* ==================== */
        /* common  */ cssArr.push(`${scriptHost}/src/common/css/common/common.css?v=${scriptVersion}`);
        /* common  */ cssArr.push(`${scriptHost}/src/common/css/common/common-btn.css?v=${scriptVersion}`);
        /* common  */ cssArr.push(`${scriptHost}/src/common/css/common/common-div.css?v=${scriptVersion}`);
        /* common  */ cssArr.push(`${scriptHost}/src/common/css/common/common-div-backBtn.css?v=${scriptVersion}`);
        /* common  */ cssArr.push(`${scriptHost}/src/common/css/common/common-div-checkbox.css?v=${scriptVersion}`);
        /* common  */ cssArr.push(`${scriptHost}/src/common/css/common/common-div-dialog.css?v=${scriptVersion}`);
        /* common  */ cssArr.push(`${scriptHost}/src/common/css/common/common-hr.css?v=${scriptVersion}`);
        /* common  */ cssArr.push(`${scriptHost}/src/common/css/common/common-img.css?v=${scriptVersion}`);
        /* common  */ cssArr.push(`${scriptHost}/src/common/css/common/common-input.css?v=${scriptVersion}`);
        /* common  */ cssArr.push(`${scriptHost}/src/common/css/common/common-label-checkbox.css?v=${scriptVersion}`);
        /* common  */ cssArr.push(`${scriptHost}/src/common/css/common/common-p.css?v=${scriptVersion}`);
        /* common  */ cssArr.push(`${scriptHost}/src/common/css/common/common-radio.css?v=${scriptVersion}`);
        /* common  */ cssArr.push(`${scriptHost}/src/common/css/common/common-select.css?v=${scriptVersion}`);
        /* common  */ cssArr.push(`${scriptHost}/src/common/css/common/common-span.css?v=${scriptVersion}`);
        /* common  */ cssArr.push(`${scriptHost}/src/common/css/common/common-switch.css?v=${scriptVersion}`);
        /* common  */ cssArr.push(`${scriptHost}/src/common/css/common/common-tag.css?v=${scriptVersion}`);
        /* common  */ cssArr.push(`${scriptHost}/src/common/css/common/common-tbl.css?v=${scriptVersion}`);
        /* common  */ cssArr.push(`${scriptHost}/src/common/css/common/common-textarea.css?v=${scriptVersion}`);
        /* common  */ cssArr.push(`${scriptHost}/src/common/css/common/common-ul.css?v=${scriptVersion}`);
        /* common  */ cssArr.push(`${scriptHost}/src/common/css/common/common-user.css?v=${scriptVersion}`);
        /* common  */ cssArr.push(`${scriptHost}/src/common/css/entity/entity.css?v=${scriptVersion}`);
        /* common  */ cssArr.push(`${scriptHost}/src/common/css/entity/entity-common.css?v=${scriptVersion}`);
        /* common  */ cssArr.push(`${scriptHost}/src/common/css/entity/entity-MBank.css?v=${scriptVersion}`);
        /* common  */ cssArr.push(`${scriptHost}/src/common/css/entity/entity-MGovAddr.css?v=${scriptVersion}`);
        /* common  */ cssArr.push(`${scriptHost}/src/common/css/entity/entity-MSystemBoard.css?v=${scriptVersion}`);
        /* common  */ cssArr.push(`${scriptHost}/src/common/css/page/page.css?v=${scriptVersion}`);
        // /* project */ cssArr.push(`${scriptHost}/src/prj/css/prj/prj-span.css?v=${scriptVersion}`);
        /* project */ cssArr.push(`${scriptHost}/src/prj/css/entity/entity-MBankaccount.css?v=${scriptVersion}`);
        /* project */ cssArr.push(`${scriptHost}/src/prj/css/entity/entity-MCls.css?v=${scriptVersion}`);
        /* project */ cssArr.push(`${scriptHost}/src/prj/css/entity/entity-MClssettlehist.css?v=${scriptVersion}`);
        /* project */ cssArr.push(`${scriptHost}/src/prj/css/entity/entity-MClspurchasehist.css?v=${scriptVersion}`);
        /* project */ cssArr.push(`${scriptHost}/src/prj/css/entity/entity-MGrp.css?v=${scriptVersion}`);
        /* project */ cssArr.push(`${scriptHost}/src/prj/css/entity/entity-MSchedulebyweek.css?v=${scriptVersion}`);
        /* project */ cssArr.push(`${scriptHost}/src/prj/css/page/index.css?v=${scriptVersion}`);

        /* ==================== */
        /* add css / script */
        /* ==================== */
        let head = document.getElementsByTagName('head')[0];
        let scriptLoad = function(scriptArr, index)
        {
            if(scriptArr[index] == undefined)
            {
                let deviceKind = GGstorage.getDeviceKind();
                switch(deviceKind)
                {
                    case GGF.System.DeviceKind.MOBILE:
                    {
                        scriptloaded = true;
                        break;
                    }
                    case GGF.System.DeviceKind.PC:
                    {
                        callback();
                        break;
                    }
                }
                return;
            }

            let script = null;
            script = document.createElement('script');
            script.type = 'text/javascript';
            script.src = scriptArr[index];
            head.append(script);

            script.addEventListener('load', () => {
                scriptLoad(scriptArr, index+1);
            });
        }

        let cssLoad = function(cssArr, index)
        {
            if(cssArr[index] == undefined)
            {
                scriptLoad(scriptArr, 0);
                return;
            }

            let css = null;
            css = document.createElement('link');
            css.rel = "stylesheet";
            css.type = "text/css";
            css.href = cssArr[index];
            head.append(css);

            css.addEventListener('load', () => {
                cssLoad(cssArr, index+1);
            });
        }
        cssLoad(cssArr, 0);

    }, /* load */

}

