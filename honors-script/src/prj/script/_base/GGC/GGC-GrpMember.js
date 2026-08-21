GGC.GrpMember =
{
    grpmtypeCvrt(val)
    {
        let rslt = "";
        switch(val)
        {
            case GGF.GrpMember.Grpmtype.MNG    : rslt = "매니저"; break;
            case GGF.GrpMember.Grpmtype.MNGSUB : rslt = "부매니저"; break;
            case GGF.GrpMember.Grpmtype.MEMBER : rslt = "멤버"; break;
        }
        return rslt;
    },

    /* ----- */
    /* backnumber */
    /* ----- */
    backnumber(val)
    {
        let rslt = "";
        if(val != "")
            rslt = `${val}`;
        return rslt;
    },
    backnumberSpan(val) { return Common.isEmpty(val) ? "" : `<span class="common-colorFont common-fonts10">${GGC.GrpMember.backnumber(val)}&nbsp;</span>`; },
    backnumberPill(val) { return Common.isEmpty(val) ? "" : `<span class="common-card      common-fonts08" card-color="main" style="padding:var(--padTiny) var(--padBase);">${GGC.GrpMember.backnumber(val)}</span>`; },
};