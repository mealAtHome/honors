var GGbase =
{
    load(callback)
    {
        /* ==================== */
        /* set */
        /* ==================== */
        let scriptArr = [];
        let cssArr = [];

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

        /* by device (common) */
        scriptArr.push(`./js/script/api-pr/api-common/api-pr.js`);

        scriptArr.push(`./js/script/api-pr/api-common/api-pr.js`);
        scriptArr.push(`./js/script/api-pr/api-table/api-pr-cart.js`);
        scriptArr.push(`./js/script/api/api-common/api.js`);
        scriptArr.push(`./js/script/api/api-table/api-addressSido.js`);
        scriptArr.push(`./js/script/api/api-table/api-addressSigungu.js`);
        scriptArr.push(`./js/script/api/api-table/api-bank.js`);
        scriptArr.push(`./js/script/api/api-table/api-bankaccount.js`);
        scriptArr.push(`./js/script/api/api-table/api-cls.js`);
        scriptArr.push(`./js/script/api/api-table/api-clslineup2.js`);
        scriptArr.push(`./js/script/api/api-table/api-clssettle.js`);
        scriptArr.push(`./js/script/api/api-table/api-systemBoard.js`);
        scriptArr.push(`./js/script/api/api-table/api-user.js`);
        scriptArr.push(`./js/script/api/api-table/api-grp.js`);
        scriptArr.push(`./js/script/api/api-table/api-grpMember.js`);
        scriptArr.push(`./js/script/api/api-table/api-grpMemberPointhist.js`);
        scriptArr.push(`./js/script/base-GGF/GGF-page.js`);
        scriptArr.push(`./js/script/base-html/gg-html.js`);
        scriptArr.push(`./js/script/common/choseong.js`);
        scriptArr.push(`./js/script/common/common-event.js`);
        scriptArr.push(`./js/script/common/common.js`);
        scriptArr.push(`./js/script/common/gg-alert.js`);
        scriptArr.push(`./js/script/common/gg-settings.js`);
        scriptArr.push(`./js/script/common/GGtoast.js`);
        scriptArr.push(`./js/script/common/GGdate.js`);
        scriptArr.push(`./js/script/common/GGdialog.js`);
        scriptArr.push(`./js/script/common/GGslideform.js`);
        scriptArr.push(`./js/script/common/GGpage.js`);
        scriptArr.push(`./js/script/common/index-element.js`);
        scriptArr.push(`./js/script/common/navigation.js`);
        scriptArr.push(`./js/script/cvrt/_GGC.js`);
        scriptArr.push(`./js/script/cvrt/GGC-comm.js`);
        scriptArr.push(`./js/script/cvrt/GGC-cls.js`);
        scriptArr.push(`./js/script/cvrt/GGC-store.js`);
        scriptArr.push(`./js/script/cvrt/GGC-rider.js`);
        scriptArr.push(`./js/script/cvrt/GGC-user.js`);
        scriptArr.push(`./js/script/cvrt/GGC-grp.js`);
        scriptArr.push(`./js/script/model/model-common/_MCommon.js`);
        scriptArr.push(`./js/script/model/model-common/MApiResponse.js`);
        scriptArr.push(`./js/script/model/model-common/MYear.js`);
        scriptArr.push(`./js/script/model/model-common/MMonth.js`);
        scriptArr.push(`./js/script/model/model-common/MYearMonth.js`);
        scriptArr.push(`./js/script/model/model-system/MCount.js`);
        scriptArr.push(`./js/script/model/model-table/MAddressSido.js`);
        scriptArr.push(`./js/script/model/model-table/MAddressSigungu.js`);
        scriptArr.push(`./js/script/model/model-table/MBank.js`);
        scriptArr.push(`./js/script/model/model-table/MBankaccount.js`);
        scriptArr.push(`./js/script/model/model-table/MCls.js`);
        scriptArr.push(`./js/script/model/model-table/MClslineup2.js`);
        scriptArr.push(`./js/script/model/model-table/MClssettle.js`);
        scriptArr.push(`./js/script/model/model-table/MGrp.js`);
        scriptArr.push(`./js/script/model/model-table/MGrpMember.js`);
        scriptArr.push(`./js/script/model/model-table/MGrpMemberPointhist.js`);
        scriptArr.push(`./js/script/model/model-table/MSystemBoard.js`);
        scriptArr.push(`./js/script/model/model-table/MUser.js`);
        scriptArr.push(`./js/script/utils/GGutils.js`);
        scriptArr.push(`./js/script/validation/_validation.js`);
        scriptArr.push(`./js/script/validation/validation-api.js`);
        scriptArr.push(`./js/script/validation/validation-common_field.js`);
        scriptArr.push(`./js/script/validation/validation-common.js`);
        scriptArr.push(`./js/script/validation/validation-user.js`);
        scriptArr.push(`http://api.yogimoim.com/plus.js`);

        /* ==================== */
        /* add css */
        /* ==================== */
        cssArr.push(`./js/css/common/common-btn.css`);
        cssArr.push(`./js/css/common/common-div-backBtn.css`);
        cssArr.push(`./js/css/common/common-div-dialog.css`);
        cssArr.push(`./js/css/common/common-div.css`);
        cssArr.push(`./js/css/common/common-hr.css`);
        cssArr.push(`./js/css/common/common-img.css`);
        cssArr.push(`./js/css/common/common-input.css`);
        cssArr.push(`./js/css/common/common-label-checkbox.css`);
        cssArr.push(`./js/css/common/common-p.css`);
        cssArr.push(`./js/css/common/common-radio.css`);
        cssArr.push(`./js/css/common/common-select.css`);
        cssArr.push(`./js/css/common/common-span.css`);
        cssArr.push(`./js/css/common/common-switch.css`);
        cssArr.push(`./js/css/common/common-tag.css`);
        cssArr.push(`./js/css/common/common-tbl.css`);
        cssArr.push(`./js/css/common/common-td.css`);
        cssArr.push(`./js/css/common/common-textarea.css`);
        cssArr.push(`./js/css/common/common-ul.css`);
        cssArr.push(`./js/css/common/common-user.css`);
        cssArr.push(`./js/css/common/common.css`);
        cssArr.push(`./js/css/entity/entity-common.css`);
        cssArr.push(`./js/css/entity/entity-MArea.css`);
        cssArr.push(`./js/css/entity/entity-MBank.css`);
        cssArr.push(`./js/css/entity/entity-MBankaccount.css`);
        cssArr.push(`./js/css/entity/entity-MCls.css`);
        cssArr.push(`./js/css/entity/entity-MGrp.css`);
        cssArr.push(`./js/css/entity/entity-MSystemBoard.css`);
        cssArr.push(`./js/css/entity/entity.css`);
        cssArr.push(`./js/css/pagecss/index.css`);
        cssArr.push(`./js/css/pagecss/page.css`);


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

