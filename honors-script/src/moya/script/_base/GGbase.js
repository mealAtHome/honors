var GGbase =
{
    load(callback)
    {
        /* ==================== */
        /* set */
        /* ==================== */
        let deviceKind = GGstorage.getDeviceKind();
        let scriptArr = [];
        let cssArr = [];
        let scriptver = new Date().getTime();

        /* ==================== */
        /* add script */
        /* ==================== */

        // for product
        $.i18n().load(
            {
                'jp' : "./js/script/i18n/jp.json?" + scriptver,
                'kr' : "./js/script/i18n/kr.json?" + scriptver,
            }
        );

        /* by device */
        switch(deviceKind)
        {
            case GGF.System.DeviceKind.MOBILE:
            case GGF.System.DeviceKind.PC:
            {
                break;
            }
            case GGF.System.DeviceKind.WEB:
            {
                break;
            }
        }
        scriptArr.push(`./js/script/api-pr/api-common/api-pr.js?${scriptver}`);
        scriptArr.push(`./js/script/api-pr/api-table/api-pr-cart.js?${scriptver}`);
        scriptArr.push(`./js/script/api/api-common/api.js?${scriptver}`);
        scriptArr.push(`./js/script/api/api-table/api-addressSido.js?${scriptver}`);
        scriptArr.push(`./js/script/api/api-table/api-addressSigungu.js?${scriptver}`);
        scriptArr.push(`./js/script/api/api-table/api-bank.js?${scriptver}`);
        scriptArr.push(`./js/script/api/api-table/api-bankaccount.js?${scriptver}`);
        scriptArr.push(`./js/script/api/api-table/api-cls.js?${scriptver}`);
        scriptArr.push(`./js/script/api/api-table/api-clslineup2.js?${scriptver}`);
        scriptArr.push(`./js/script/api/api-table/api-clssettle.js?${scriptver}`);
        scriptArr.push(`./js/script/api/api-table/api-systemBoard.js?${scriptver}`);
        scriptArr.push(`./js/script/api/api-table/api-user.js?${scriptver}`);
        scriptArr.push(`./js/script/api/api-table/api-grp.js?${scriptver}`);
        scriptArr.push(`./js/script/api/api-table/api-grpMember.js?${scriptver}`);
        scriptArr.push(`./js/script/api/api-table/api-grpMemberPointhist.js?${scriptver}`);
        scriptArr.push(`./js/script/base-GGF/GGF-page.js?${scriptver}`);
        scriptArr.push(`./js/script/base-html/gg-html.js?${scriptver}`);
        scriptArr.push(`./js/script/common/choseong.js?${scriptver}`);
        scriptArr.push(`./js/script/common/common-event.js?${scriptver}`);
        scriptArr.push(`./js/script/common/common.js?${scriptver}`);
        scriptArr.push(`./js/script/common/gg-alert.js?${scriptver}`);
        scriptArr.push(`./js/script/common/gg-settings.js?${scriptver}`);
        scriptArr.push(`./js/script/common/GGtoast.js?${scriptver}`);
        scriptArr.push(`./js/script/common/GGdate.js?${scriptver}`);
        scriptArr.push(`./js/script/common/GGdialog.js?${scriptver}`);
        scriptArr.push(`./js/script/common/GGslideform.js?${scriptver}`);
        scriptArr.push(`./js/script/common/GGpage.js?${scriptver}`);
        scriptArr.push(`./js/script/common/index-element.js?${scriptver}`);
        scriptArr.push(`./js/script/common/navigation.js?${scriptver}`);
        scriptArr.push(`./js/script/cvrt/_GGC.js?${scriptver}`);
        scriptArr.push(`./js/script/cvrt/GGC-comm.js?${scriptver}`);
        scriptArr.push(`./js/script/cvrt/GGC-cls.js?${scriptver}`);
        scriptArr.push(`./js/script/cvrt/GGC-store.js?${scriptver}`);
        scriptArr.push(`./js/script/cvrt/GGC-rider.js?${scriptver}`);
        scriptArr.push(`./js/script/cvrt/GGC-user.js?${scriptver}`);
        scriptArr.push(`./js/script/cvrt/GGC-grp.js?${scriptver}`);
        scriptArr.push(`./js/script/model/model-common/_MCommon.js?${scriptver}`);
        scriptArr.push(`./js/script/model/model-common/MApiResponse.js?${scriptver}`);
        scriptArr.push(`./js/script/model/model-common/MYear.js?${scriptver}`);
        scriptArr.push(`./js/script/model/model-common/MMonth.js?${scriptver}`);
        scriptArr.push(`./js/script/model/model-common/MYearMonth.js?${scriptver}`);
        scriptArr.push(`./js/script/model/model-system/MCount.js?${scriptver}`);
        scriptArr.push(`./js/script/model/model-table/MAddressSido.js?${scriptver}`);
        scriptArr.push(`./js/script/model/model-table/MAddressSigungu.js?${scriptver}`);
        scriptArr.push(`./js/script/model/model-table/MBank.js?${scriptver}`);
        scriptArr.push(`./js/script/model/model-table/MBankaccount.js?${scriptver}`);
        scriptArr.push(`./js/script/model/model-table/MCls.js?${scriptver}`);
        scriptArr.push(`./js/script/model/model-table/MClslineup2.js?${scriptver}`);
        scriptArr.push(`./js/script/model/model-table/MClssettle.js?${scriptver}`);
        scriptArr.push(`./js/script/model/model-table/MGrp.js?${scriptver}`);
        scriptArr.push(`./js/script/model/model-table/MGrpMember.js?${scriptver}`);
        scriptArr.push(`./js/script/model/model-table/MGrpMemberPointhist.js?${scriptver}`);
        scriptArr.push(`./js/script/model/model-table/MSystemBoard.js?${scriptver}`);
        scriptArr.push(`./js/script/model/model-table/MUser.js?${scriptver}`);
        scriptArr.push(`./js/script/utils/GGutils.js?${scriptver}`);
        scriptArr.push(`./js/script/validation/_validation.js?${scriptver}`);
        scriptArr.push(`./js/script/validation/validation-api.js?${scriptver}`);
        scriptArr.push(`./js/script/validation/validation-common_field.js?${scriptver}`);
        scriptArr.push(`./js/script/validation/validation-common.js?${scriptver}`);
        scriptArr.push(`./js/script/validation/validation-user.js?${scriptver}`);
        scriptArr.push(`http://api.yogimoim.com/plus.js?${scriptver}`);

        /* ==================== */
        /* add css */
        /* ==================== */

        /* by device */
        switch(deviceKind)
        {
            case GGF.System.DeviceKind.MOBILE:
            case GGF.System.DeviceKind.PC:
            {
                break;
            }
            case GGF.System.DeviceKind.WEB:
            {
                break;
            }
        }

        cssArr.push(`./js/css/common/common-btn.css?${scriptver}`);
        cssArr.push(`./js/css/common/common-div-backBtn.css?${scriptver}`);
        cssArr.push(`./js/css/common/common-div-dialog.css?${scriptver}`);
        cssArr.push(`./js/css/common/common-div.css?${scriptver}`);
        cssArr.push(`./js/css/common/common-hr.css?${scriptver}`);
        cssArr.push(`./js/css/common/common-img.css?${scriptver}`);
        cssArr.push(`./js/css/common/common-input.css?${scriptver}`);
        cssArr.push(`./js/css/common/common-label-checkbox.css?${scriptver}`);
        cssArr.push(`./js/css/common/common-p.css?${scriptver}`);
        cssArr.push(`./js/css/common/common-radio.css?${scriptver}`);
        cssArr.push(`./js/css/common/common-select.css?${scriptver}`);
        cssArr.push(`./js/css/common/common-span.css?${scriptver}`);
        cssArr.push(`./js/css/common/common-switch.css?${scriptver}`);
        cssArr.push(`./js/css/common/common-tag.css?${scriptver}`);
        cssArr.push(`./js/css/common/common-tbl.css?${scriptver}`);
        cssArr.push(`./js/css/common/common-td.css?${scriptver}`);
        cssArr.push(`./js/css/common/common-textarea.css?${scriptver}`);
        cssArr.push(`./js/css/common/common-ul.css?${scriptver}`);
        cssArr.push(`./js/css/common/common-user.css?${scriptver}`);
        cssArr.push(`./js/css/common/common.css?${scriptver}`);
        cssArr.push(`./js/css/entity/entity-common.css?${scriptver}`);
        cssArr.push(`./js/css/entity/entity-MArea.css?${scriptver}`);
        cssArr.push(`./js/css/entity/entity-MBank.css?${scriptver}`);
        cssArr.push(`./js/css/entity/entity-MBankaccount.css?${scriptver}`);
        cssArr.push(`./js/css/entity/entity-MCls.css?${scriptver}`);
        cssArr.push(`./js/css/entity/entity-MGrp.css?${scriptver}`);
        cssArr.push(`./js/css/entity/entity-MSystemBoard.css?${scriptver}`);
        cssArr.push(`./js/css/entity/entity.css?${scriptver}`);
        cssArr.push(`./js/css/pagecss/index.css?${scriptver}`);
        cssArr.push(`./js/css/pagecss/page.css?${scriptver}`);


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

