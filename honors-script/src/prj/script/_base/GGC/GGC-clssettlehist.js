GGC.Clssettlehist =
{
    /* ----- */
    /* histtype */
    /* ----- */
    histtypeCvrt(val)
    {
        if(val == GGF.Clssettlehist.Histtype.UPDATE) return "갱신";
        if(val == GGF.Clssettlehist.Histtype.DELETE) return "삭제";
        if(val == GGF.Clssettlehist.Histtype.AFTER) return "추가";
        return "";
    },
    histtypeFeel(val)
    {
        if(val == GGF.Clssettlehist.Histtype.UPDATE) return "prog";
        if(val == GGF.Clssettlehist.Histtype.DELETE) return "ngtv";
        if(val == GGF.Clssettlehist.Histtype.AFTER) return "pstv";
        return "";
    },
    histtypeCard(val) { return `<span class="common-tag-card" card-color="${GGC.Clssettlehist.histtypeFeel(val)}">${GGC.Clssettlehist.histtypeCvrt(val)}</span>`; },
    histtypeFont(val) { return `<span class="common-tag-font" font-color="${GGC.Clssettlehist.histtypeFeel(val)}">${GGC.Clssettlehist.histtypeCvrt(val)}</span>`; },
}