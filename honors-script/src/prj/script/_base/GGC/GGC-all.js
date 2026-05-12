GGC.Bankaccount =
{
    /* ----- */
    /* defaultflg */
    /* ----- */
    defaultflgCvrt(val)
    {
        if(val == GGF.Y) return "기본계좌";
        if(val == GGF.N) return "일반계좌";
        return "";
    },
    defaultflgFeel(val)
    {
        if(val == GGF.Y) return "hold";
        if(val == GGF.N) return "prog";
        return "";
    },
    defaultflgCard(val) { return `<span class="common-tag-card" card-color="${GGC.Bankaccount.defaultflgFeel(val)}">${GGC.Bankaccount.defaultflgCvrt(val)}</span>`; },
    defaultflgFont(val) { return `<span class="common-tag-font" font-color="${GGC.Bankaccount.defaultflgFeel(val)}">${GGC.Bankaccount.defaultflgCvrt(val)}</span>`; },

};

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
};

GGC.GrpMemberPointhist =
{
    pointtype(point)
    {
        let rslt = "";
        if(point >= 0)
            rslt = "입금";
        else if(point < 0)
            rslt = "출금";
        return rslt;
    },
    pointtypePretty(point)
    {
        let rslt = GGC.GrpMemberPointhist.pointtype(point);
        if(point >= 0)
            return `<span class="common-tag-colorPstv">${rslt}</span>`;
        else if(point < 0)
            return `<span class="common-tag-colorNgtv">${rslt}</span>`;
    },
    pointPretty(point)
    {
        let rslt = GGC.Common.priceWon(point);
        if(point >= 0)
            return `<span class="common-tag-colorPstv">${rslt}</span>`;
        else if(point < 0)
            return `<span class="common-tag-colorNgtv">${rslt}</span>`;
    },
}