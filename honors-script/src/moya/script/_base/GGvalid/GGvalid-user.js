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