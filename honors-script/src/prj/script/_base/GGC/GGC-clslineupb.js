GGC.Clslineupb =
{
    /* ----- */
    /* prepaidflg */
    /* ----- */
    prepaidflgCvrt(val)
    {
        let rslt = "";
        switch(val)
        {
            case GGF.Y : rslt = "완료"; break;
            case GGF.N : rslt = "-"; break;
        }
        return rslt;
    },
    prepaidflgFeel(val)
    {
        let rslt = "";
        switch(val)
        {
            case GGF.Y : rslt = "pstv"; break;
            case GGF.N : rslt = "hold"; break;
        }
        return rslt;
    },
    prepaidflgCard(val) { return `<span class="common-tag-card" card-color="${GGC.Clslineupb.prepaidflgFeel(val)}">${GGC.Clslineupb.prepaidflgCvrt(val)}</span>`; },
    prepaidflgFont(val) { return `<span class="common-tag-font" font-color="${GGC.Clslineupb.prepaidflgFeel(val)}">${GGC.Clslineupb.prepaidflgCvrt(val)}</span>`; },

}