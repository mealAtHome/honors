GGC.Clspurchasehist =
{
    /* ----- */
    /* histtype */
    /* ----- */
    histtypeCvrt(val)
    {
        if(val == GGF.Clspurchasehist.Histtype.INSERT) return "추가";
        if(val == GGF.Clspurchasehist.Histtype.UPDATE) return "갱신";
        if(val == GGF.Clspurchasehist.Histtype.DELETE) return "삭제";
        return "";
    },
    histtypeFeel(val)
    {
        if(val == GGF.Clspurchasehist.Histtype.INSERT) return "pstv";
        if(val == GGF.Clspurchasehist.Histtype.UPDATE) return "prog";
        if(val == GGF.Clspurchasehist.Histtype.DELETE) return "ngtv";
        return "";
    },
    histtypeCard(val) { return `<span class="common-tag-card" card-color="${GGC.Clspurchasehist.histtypeFeel(val)}">${GGC.Clspurchasehist.histtypeCvrt(val)}</span>`; },
    histtypeFont(val) { return `<span class="common-tag-font" font-color="${GGC.Clspurchasehist.histtypeFeel(val)}">${GGC.Clspurchasehist.histtypeCvrt(val)}</span>`; },
}