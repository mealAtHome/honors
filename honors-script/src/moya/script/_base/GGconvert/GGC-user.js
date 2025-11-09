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