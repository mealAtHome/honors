GGC.Bankaccount =
{
    defaultflgCvrt(val)
    {
        let rslt = "";
        switch(val)
        {
            case GGF.Bankaccount.Defaultflg.Y : rslt = "기본계좌"; break;
            case GGF.Bankaccount.Defaultflg.N : rslt = "일반계좌"; break;
        }
        return rslt;
    },

    defaultflgCard(val)
    {
        let rslt = "";
        let converted = GGC.Bankaccount.defaultflgCvrt(val);
        switch(val)
        {
            case GGF.Bankaccount.Defaultflg.Y : rslt = `<span class="common-span-card" bankaccount-defaultflg="${val}">${converted}</span>`; break;
            case GGF.Bankaccount.Defaultflg.N : rslt = `<span class="common-span-card" bankaccount-defaultflg="${val}">${converted}</span>`; break;
        }
        return rslt;
    },
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