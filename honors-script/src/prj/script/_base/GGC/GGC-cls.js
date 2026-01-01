GGC.Cls =
{
    clstypeCvrt(val)
    {
        let rslt = "";
        switch(val)
        {
            case GGF.Cls.Clstype.LINEUP1 : rslt = "라인업(1팀)"; break;
            case GGF.Cls.Clstype.LINEUP2 : rslt = "라인업(1시합)"; break;
            case GGF.Cls.Clstype.LINEUP4 : rslt = "라인업(2시합)"; break;
        }
        return rslt;
    },

    clsstatusCvrt(val)
    {
        let rslt = "";
        switch(val)
        {
            case GGF.Cls.Clsstatus.EDIT          : rslt = "작성중"; break;
            case GGF.Cls.Clsstatus.ING           : rslt = "진행중"; break;
            case GGF.Cls.Clsstatus.ENDCLS        : rslt = "정산중"; break;
            case GGF.Cls.Clsstatus.ENDSETTLE     : rslt = "완료"; break;
            case GGF.Cls.Clsstatus.CANCEL        : rslt = "취소"; break;
        }
        return rslt;
    },

    clsstatusCard(val)
    {
        let converted = GGC.Cls.clsstatusCvrt(val);
        let rslt = `<span class="common-span-card" clsstatus="${val}">${converted}</span>`;
        return rslt;
    },

    getGrpfinancereflectflgCvrt(val)
    {
        let rslt = "";
        switch(val)
        {
            case GGF.Cls.Grpfinancereflectflg.Y         : rslt = "반영"; break;
            case GGF.Cls.Grpfinancereflectflg.N         : rslt = "미반영"; break;
            case GGF.Cls.Grpfinancereflectflg.UNABLE    : rslt = "반영불가"; break;
        }
        return rslt;
    },
    getGrpfinancereflectflgColor(val)
    {
        let rslt = "";
        let text = GGC.Cls.getGrpfinancereflectflgCvrt(val);
        switch(val)
        {
            case GGF.Cls.Grpfinancereflectflg.Y         : rslt = `<span class="common-tag-colorPstv">${text}</span>`; break;
            case GGF.Cls.Grpfinancereflectflg.N         : rslt = `<span class="common-tag-colorWarn">${text}</span>`; break;
            case GGF.Cls.Grpfinancereflectflg.UNABLE    : rslt = `<span class="common-tag-colorNgtv">${text}</span>`; break;
        }
        return rslt;
    }

}

GGC.Clssettle =
{
    memberdepositflg(val)
    {
        let rslt = "";
        switch(val)
        {
            case GGF.Y : rslt = "완료"; break;
            case GGF.N : rslt = "미입금"; break;
        }
        return rslt;
    },
    memberdepositflgdt(val)
    {

    },
    managerdepositflg(val)
    {
        let rslt = "";
        switch(val)
        {
            case GGF.Y : rslt = "완료"; break;
            case GGF.N : rslt = "미입금"; break;
        }
        return rslt;
    },
    managerdepositflgdt(val)
    {

    },

    clssettleStatusPretty(memberdepositflg, managerdepositflg)
    {
        let status = "";
        let rslt = "";
        if      (managerdepositflg == GGF.Y)                              { status = "succeed"; rslt = "입금완료"; }
        else if (managerdepositflg == GGF.N && memberdepositflg == GGF.Y) { status = "warning"; rslt = "입금완료(임시)"; }
        else
        {
            status = "failed";
            rslt = "미입금";
        }

        let html = `<span class="common-span-card" span-type="${status}">${rslt}</span>`;
        return html;
    },
}