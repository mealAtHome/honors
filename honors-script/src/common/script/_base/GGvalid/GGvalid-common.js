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