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
                'jp' : `${scriptHost}/src/script/_base/i18n/jp.json?v=${scriptVersion}`,
                'kr' : `${scriptHost}/src/script/_base/i18n/kr.json?v=${scriptVersion}`,
            }
        );

        /* by device (common) */
        scriptArr.push(`${scriptHost}/src/script/_base/Navigation.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/_base/Navigation.Prj.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/_base/choseong.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/_base/common_.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/_base/commonEvent.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/_base/CommonEvent.Prj.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/_base/GGdate.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/_base/GGdialog.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/_base/GGhtml.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/_base/GGpage.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/_base/GGslideform.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/_base/GGtoast.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/_base/GGutils.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/_base/GGvalid/GGvalid.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/_base/GGvalid/GGvalid-api.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/_base/GGvalid/GGvalid-common.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/_base/GGvalid/GGvalid-user.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/_base/GGC/GGC.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/_base/GGC/GGC-all.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/_base/GGC/GGC-cls.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/_base/GGC/GGC-clslineupb.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/_base/GGC/GGC-clspurchasehist.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/_base/GGC/GGC-clssettle.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/_base/GGC/GGC-clssettlehist.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/_base/GGC/GGC-date.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/_base/GGC/GGC-grp.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/_base/GGC/GGC-GrpMember.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/_base/GGC/GGC-GrpfncSponsorship.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/_base/GGC/GGC-user.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/_base/api.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/_base/api-pr.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/api/api-ref/api-govAddr.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/api/api-ref/api-systemBoard.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/api/api-pr/ApiPr-addrcode.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/api/api-table/api-addressSido.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/api/api-table/api-addrcode.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/api/api-table/api-addressSigungu.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/api/api-table/api-bank.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/api/api-table/api-bankaccount.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/api/api-table/api-cls.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/api/api-table/api-clslineupa.js?v=${scriptVersion}`)
        scriptArr.push(`${scriptHost}/src/script/api/api-table/api-clslineupb.js?v=${scriptVersion}`)
        scriptArr.push(`${scriptHost}/src/script/api/api-table/api-clslineuptmpa.js?v=${scriptVersion}`)
        scriptArr.push(`${scriptHost}/src/script/api/api-table/api-clslineuptmpb.js?v=${scriptVersion}`)
        scriptArr.push(`${scriptHost}/src/script/api/api-table/api-clslineuptmpc.js?v=${scriptVersion}`)
        scriptArr.push(`${scriptHost}/src/script/api/api-table/api-clspurchase.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/api/api-table/api-clspurchasehist.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/api/api-table/api-clssettle.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/api/api-table/api-clssettlehist.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/api/api-table/api-clssettletmp.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/api/api-table/api-grp.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/api/api-table/api-grpIntro.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/api/api-table/Api.GrpfncLoss.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/api/api-table/api-GrpfncPurchase.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/api/api-table/api-GrpfncSponsorship.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/api/api-table/api-Grpfnca.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/api/api-table/api-Grpfnclog.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/api/api-table/api-grpmtaga.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/api/api-table/api-grpmtagb.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/api/api-table/api-grpMember.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/api/api-table/api-grpMemberPointhist.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/api/api-table/api-systemBoard.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/api/api-table/api-user.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/api/api-table/Api.Scheduleall.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/api/api-table/Api.Schedulebyweek.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-common/_MCommon.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-common/MApiResponse.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-ref/MAddressSido.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-ref/MAddrcode.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-ref/MAddressSigungu.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-ref/MBank.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-ref/MGovAddr.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-table/MBankaccount.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-table/MCls.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-table/MClslineupa.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-table/MClslineupb.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-table/MClslineuptmpa.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-table/MClslineuptmpb.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-table/MClslineuptmpc.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-table/MClspurchase.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-table/MClspurchasehist.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-table/MClssettle.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-table/MClssettlehist.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-table/MClssettletmp.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-table/MGrp.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-table/MGrpIntro.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-table/MGrpfncLoss.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-table/MGrpfncPurchase.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-table/MGrpfncSponsorship.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-table/MGrpfnca.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-table/MGrpfnclog.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-table/MGrpmtaga.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-table/MGrpmtagb.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-table/MGrpMember.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-table/MGrpMemberPointhist.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-table/MSystemBoard.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-table/MUser.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-table/MScheduleall.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-table/MSchedulebyweek.js?v=${scriptVersion}`);
        scriptArr.push(`${scriptHost}/src/script/model/model-table/MSchedulebytime.js?v=${scriptVersion}`);

        /* ==================== */
        /* add css */
        /* ==================== */
        cssArr.push(`${scriptHost}/src/css/common/css/common.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/common/css/common-btn.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/common/css/common-div.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/common/css/common-div-checkbox.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/common/css/common-div-dialog.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/common/css/common-hr.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/common/css/common-img.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/common/css/common-input.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/common/css/common-checkbox.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/common/css/common-p.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/common/css/common-radio.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/common/css/common-search.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/common/css/common-select.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/common/css/common-span.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/common/css/common-switch.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/common/css/common-tag.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/common/css/common-tbl.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/common/css/common-ul.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/common/css/common-tabbar.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/common-event/common-event.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/page/css/page.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/page/css/index.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/page/css/A00UserLogin.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/model/css/MUser.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/entity/css/entity.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/entity/css/entity-common.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/entity/css/entity-MBank.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/entity/css/entity-MGovAddr.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/entity/css/entity-MSystemBoard.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/entity/css/entity-MBankaccount.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/entity/css/entity-MCls.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/entity/css/entity-MClssettlehist.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/entity/css/entity-MClspurchasehist.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/entity/css/entity-MGrp.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/entity/css/entity-MGrpfncLoss.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/entity/css/entity-MGrpfncPurchase.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/entity/css/entity-MGrpfncSponsorship.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/entity/css/entity-MSchedulebyweek.css?v=${scriptVersion}`);
        cssArr.push(`${scriptHost}/src/css/entity/css/entity-MAddrcode.css?v=${scriptVersion}`);

        if(SCRIPT_MERGE)
        {
            scriptArr = [`${scriptHost}/app/app.js?v=${scriptVersion}`];
            cssArr = [`${scriptHost}/app/app.css?v=${scriptVersion}`];
        }

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

