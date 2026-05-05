GGC.Clssettle =
{
    /* ----- */
    /* settlestatus */
    /* ----- */
    settlestatusCvrt(val)
    {
        if(val == GGF.Clssettle.Settlestatus.WAIT) return "입금대기";
        if(val == GGF.Clssettle.Settlestatus.MEMB) return "입금완료";
        if(val == GGF.Clssettle.Settlestatus.DONE) return "확인완료";
        if(val == GGF.Clssettle.Settlestatus.LOSS) return "손실";
        return "";
    },
    settlestatusFeel(val)
    {
        if(val == GGF.Clssettle.Settlestatus.WAIT) return "hold";
        if(val == GGF.Clssettle.Settlestatus.MEMB) return "prog";
        if(val == GGF.Clssettle.Settlestatus.DONE) return "pstv";
        if(val == GGF.Clssettle.Settlestatus.LOSS) return "ngtv";
        return "";
    },
    settlestatusCard(val) { return `<span class="common-tag-card" card-color="${GGC.Clssettle.settlestatusFeel(val)}">${GGC.Clssettle.settlestatusCvrt(val)}</span>`; },
    settlestatusFont(val) { return `<span class="common-tag-font" font-color="${GGC.Clssettle.settlestatusFeel(val)}">${GGC.Clssettle.settlestatusCvrt(val)}</span>`; },
}