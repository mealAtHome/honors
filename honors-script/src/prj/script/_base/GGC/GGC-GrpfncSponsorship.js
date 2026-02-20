GGC.GrpfncSponsorship =
{
    /* ----- */
    /* spontype */
    /* ----- */
    spontypeCvrt(val)
    {
        if(val == GGF.GrpfncSponsorship.Spontype.THING) return "품목";
        if(val == GGF.GrpfncSponsorship.Spontype.MONEY) return "금전";
        return "";
    },
    spontypeFeel(val)
    {
        if(val == GGF.GrpfncSponsorship.Spontype.THING) return "pstv";
        if(val == GGF.GrpfncSponsorship.Spontype.MONEY) return "pstv";
        return "";
    },
    spontypeCard(val) { return `<span class="common-tag-card" card-color="${GGC.GrpfncSponsorship.spontypeFeel(val)}">${GGC.GrpfncSponsorship.spontypeCvrt(val)}</span>`; },
    spontypeFont(val) { return `<span class="common-tag-font" font-color="${GGC.GrpfncSponsorship.spontypeFeel(val)}">${GGC.GrpfncSponsorship.spontypeCvrt(val)}</span>`; },
}