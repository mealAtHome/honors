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

    /* ----- */
    /* usertype */
    /* ----- */
    usertypeCvrt(val)
    {
        let rslt = "";
        switch(val)
        {
            case GGF.User.Usertype.NORMAL : rslt = "일반"; break;
            case GGF.User.Usertype.TEMP   : rslt = "임시"; break;
        }
        return rslt;
    },
    usertypeFeel(val)
    {
        let rslt = "";
        switch(val)
        {
            case GGF.User.Usertype.NORMAL : rslt = "pstv"; break;
            case GGF.User.Usertype.TEMP   : rslt = "hold"; break;
        }
        return rslt;
    },
    usertypeCard(val) { return `<span class="common-card" card-type="norm" card-color="${GGC.User.usertypeFeel(val)}">${GGC.User.usertypeCvrt(val)}</span>`; },
    usertypePill(val) { return `<span class="common-card" card-type="mini" card-color="${GGC.User.usertypeFeel(val)}">${GGC.User.usertypeCvrt(val)}</span>`; },
    usertypeFont(val) { return `<span class="common-colorFont" font-color="${GGC.User.usertypeFeel(val)}">${GGC.User.usertypeCvrt(val)}</span>`; },

    /* ----- */
    /* birthyear */
    /* ----- */
    birthyear(val)
    {
        let rslt = "";
        if(val != "")
            rslt = `(${val.substring(2, 4)})`;
        return rslt;
    },
    birthyearFont(val) { return `<span class="common-colorFont common-fonts07" font-color="body">&nbsp;${GGC.User.birthyear(val)}</span>`; },
};