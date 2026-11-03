GGC.Grp =
{
    grpimgPath(key, img="", origin=false)
    {
        return GGC.Common.getImgPath("grp", key, img, origin);
    },

    /* ----- */
    /* 모임 타입·속성 표시명 */
    /* ----- */
    grptypeCvrt(val)
    {
        switch(val)
        {
            case GGF.Grp.Grptype.TEAM      : return "팀";
            case GGF.Grp.Grptype.CLUB      : return "모임";
            case GGF.Grp.Grptype.MERCENARY : return "용병";
        }
        return "";
    },
    joinpolicyCvrt(val)
    {
        switch(val)
        {
            case GGF.Grp.Joinpolicy.APPROVE : return "승인 후 가입";
            case GGF.Grp.Joinpolicy.FREE    : return "바로 가입";
            case GGF.Grp.Joinpolicy.NONE    : return "가입 받지 않음";
        }
        return "";
    },
    clsopenCvrt(val)
    {
        switch(val)
        {
            case GGF.Grp.Clsopen.MEMBER : return "멤버만";
            case GGF.Grp.Clsopen.PUBLIC : return "용병에게 공개";
        }
        return "";
    },

    /* 타입을 고르면 채워지는 속성 기본값 (프리셋). 타입을 고른 뒤 속성은 따로 바꿀 수 있다. */
    presetByGrptype(grptype)
    {
        switch(grptype)
        {
            case GGF.Grp.Grptype.TEAM      : return { joinpolicy: GGF.Grp.Joinpolicy.APPROVE, clsopen: GGF.Grp.Clsopen.MEMBER };
            case GGF.Grp.Grptype.CLUB      : return { joinpolicy: GGF.Grp.Joinpolicy.APPROVE, clsopen: GGF.Grp.Clsopen.MEMBER };
            case GGF.Grp.Grptype.MERCENARY : return { joinpolicy: GGF.Grp.Joinpolicy.NONE,    clsopen: GGF.Grp.Clsopen.PUBLIC };
        }
        return { joinpolicy: GGF.Grp.Joinpolicy.APPROVE, clsopen: GGF.Grp.Clsopen.MEMBER };
    },

}