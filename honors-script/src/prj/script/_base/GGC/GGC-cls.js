GGC.Cls =
{

    /* ----- */
    /* clsstatus */
    /* ----- */
    clsstatusCvrt(val)
    {
        let rslt = "";
        switch(val)
        {
            case GGF.Cls.Clsstatus.EDIT   : rslt = "일정작성중"; break;
            case GGF.Cls.Clsstatus.ING    : rslt = "일정진행중"; break;
            case GGF.Cls.Clsstatus.END    : rslt = "일정종료"; break;
            case GGF.Cls.Clsstatus.CANCEL : rslt = "일정취소"; break;
        }
        return rslt;
    },
    clsstatusFeel(val)
    {
        let rslt = "";
        switch(val)
        {
            case GGF.Cls.Clsstatus.EDIT   : rslt = "hold"; break;
            case GGF.Cls.Clsstatus.ING    : rslt = "prog"; break;
            case GGF.Cls.Clsstatus.END    : rslt = "pstv"; break;
            case GGF.Cls.Clsstatus.CANCEL : rslt = "ngtv"; break;
        }
        return rslt;
    },
    clsstatusCard(val) { return `<span class="common-tag-card" card-color="${GGC.Cls.clsstatusFeel(val)}">${GGC.Cls.clsstatusCvrt(val)}</span>`; },
    clsstatusFont(val) { return `<span class="common-tag-font" font-color="${GGC.Cls.clsstatusFeel(val)}">${GGC.Cls.clsstatusCvrt(val)}</span>`; },

    /* ----- */
    /* clssettleflg */
    /* ----- */
    clssettleflgCvrt(val)
    {
        let rslt = "";
        switch(val)
        {
            case GGF.Cls.Clssettleflg.EDIT    : rslt = "정산입력중"; break;
            case GGF.Cls.Clssettleflg.PROC    : rslt = "정산확인중"; break;
            case GGF.Cls.Clssettleflg.DONE    : rslt = "정산확정됨"; break;
        }
        return rslt;
    },
    clssettleflgFeel(val)
    {
        let rslt = "";
        switch(val)
        {
            case GGF.Cls.Clssettleflg.EDIT    : rslt = "hold"; break;
            case GGF.Cls.Clssettleflg.PROC    : rslt = "prog"; break;
            case GGF.Cls.Clssettleflg.DONE    : rslt = "pstv"; break;
        }
        return rslt;
    },
    clssettleflgCard(val) { return `<span class="common-tag-card" card-color="${GGC.Cls.clssettleflgFeel(val)}">${GGC.Cls.clssettleflgCvrt(val)}</span>`; },
    clssettleflgFont(val) { return `<span class="common-tag-font" font-color="${GGC.Cls.clssettleflgFeel(val)}">${GGC.Cls.clssettleflgCvrt(val)}</span>`; },


    /* ----- */
    /* ----- */
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
    getGrpfinancereflectflgFeel(val)
    {
        let rslt = "";
        switch(val)
        {
            case GGF.Cls.Grpfinancereflectflg.Y         : rslt = "pstv"; break;
            case GGF.Cls.Grpfinancereflectflg.N         : rslt = "hold"; break;
            case GGF.Cls.Grpfinancereflectflg.UNABLE    : rslt = "ngtv"; break;
        }
        return rslt;
    },
    getGrpfinancereflectflgCard(val) { return `<span class="common-tag-card" card-color="${GGC.Cls.getGrpfinancereflectflgFeel(val)}">${GGC.Cls.getGrpfinancereflectflgCvrt(val)}</span>`; },
    getGrpfinancereflectflgFont(val) { return `<span class="common-tag-font" font-color="${GGC.Cls.getGrpfinancereflectflgFeel(val)}">${GGC.Cls.getGrpfinancereflectflgCvrt(val)}</span>`; },


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

    clssettleStatusPretty(memberdepositflg, managerdepositflg, lossflg)
    {
        let status = "";
        let rslt = "";
        if(managerdepositflg == GGF.Y)                              { status = "succeed"; rslt = "입금확인"; }
        if(managerdepositflg == GGF.N && memberdepositflg == GGF.Y) { status = "warning"; rslt = "입금완료"; }
        if(managerdepositflg == GGF.N && memberdepositflg == GGF.N) { status = "warning"; rslt = "미입금"; }
        if(managerdepositflg == GGF.N && lossflg          == GGF.Y) { status = "failed";  rslt = "손실"; }

        let html = `<span class="common-span-card" span-type="${status}">${rslt}</span>`;
        return html;
    },
}