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
            case GGF.Cls.Clsstatus.END    : rslt = "endd"; break;
            case GGF.Cls.Clsstatus.CANCEL : rslt = "ngtv"; break;
        }
        return rslt;
    },
    clsstatusCard(val) { return `<span class="common-card" card-color="${GGC.Cls.clsstatusFeel(val)}">${GGC.Cls.clsstatusCvrt(val)}</span>`; },
    clsstatusFont(val) { return `<span class="common-colorFont" font-color="${GGC.Cls.clsstatusFeel(val)}">${GGC.Cls.clsstatusCvrt(val)}</span>`; },

    /* ----- */
    /* clssettleflg */
    /* ----- */
    clssettleflgCvrt(val)
    {
        let rslt = "";
        switch(val)
        {
            case GGF.Cls.Clssettleflg.EDIT    : rslt = "정산입력중"; break;
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
            case GGF.Cls.Clssettleflg.DONE    : rslt = "pstv"; break;
        }
        return rslt;
    },
    clssettleflgCard(val) { return `<span class="common-card" card-color="${GGC.Cls.clssettleflgFeel(val)}">${GGC.Cls.clssettleflgCvrt(val)}</span>`; },
    clssettleflgFont(val) { return `<span class="common-colorFont" font-color="${GGC.Cls.clssettleflgFeel(val)}">${GGC.Cls.clssettleflgCvrt(val)}</span>`; },


    /* ----- */
    /* grpfinancereflectflg */
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
    getGrpfinancereflectflgCard(val) { return `<span class="common-card" card-color="${GGC.Cls.getGrpfinancereflectflgFeel(val)}">${GGC.Cls.getGrpfinancereflectflgCvrt(val)}</span>`; },
    getGrpfinancereflectflgFont(val) { return `<span class="common-colorFont" font-color="${GGC.Cls.getGrpfinancereflectflgFeel(val)}">${GGC.Cls.getGrpfinancereflectflgCvrt(val)}</span>`; },

    /* ----- */
    /* clsapplystartdt, clsapplyclosedt */
    /* ----- */
    clsapplyPeriodCard(startDateStr, endDateStr)
    {
        let now = new Date();
        let startDate = new Date(startDateStr);
        let endDate = new Date(endDateStr);

        let str = "";
        let point = GGdate.getPointOfDate(now, startDate, endDate);
        let color = "";
        switch(point)
        {
            case GGF.GGdate.PointOfDate.UPCOMING : color = GGF.Color.NTCE; str = `모집예정 (${GGdate.getTextForUpcoming(now, startDate)}부터)`; break; /* 두 기간 이전 */
            case GGF.GGdate.PointOfDate.WITHIN   : color = GGF.Color.PROG; str = `모집중 (${GGdate.getTextForUpcoming(now, endDate)}까지)`; break; /* 두 기간 사이 */
            case GGF.GGdate.PointOfDate.PASSED   : color = GGF.Color.ENDD; str = "모집종료"; break; /* 두 기간 이후 */
        }
        return `<div class="common-card" card-type="mini" card-color="${color}"><i class="ti ti-calendar-code"></i><span>&nbsp;${str}</span></div>`;
    }
}