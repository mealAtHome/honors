class MGrpfnca
{
    constructor(dat)
    {
        /* data */      this.grpno                              =      GGC.Common.char(dat.grpno);
        /* data */      this.grpfnc_capitaltotal                =      GGC.Common.bigint(dat.grpfnc_capitaltotal);
        /* data */      this.grpfnc_sponsorshiptotal            =      GGC.Common.bigint(dat.grpfnc_sponsorshiptotal);
        /* data */      this.grpfnc_purchasetotal               = -1 * GGC.Common.bigint(dat.grpfnc_purchasetotal);
        /* data */      this.grpfnc_losstotal                   = -1 * GGC.Common.bigint(dat.grpfnc_losstotal);
        /* data */      this.grpfnc_clssalestotal               =      GGC.Common.bigint(dat.grpfnc_clssalestotal);
        /* data */      this.grpfnc_clssalesunpaidtotal         = -1 * GGC.Common.bigint(dat.grpfnc_clssalesunpaidtotal);
        /* data */      this.grpfnc_clssaleslosstotal           = -1 * GGC.Common.bigint(dat.grpfnc_clssaleslosstotal);
        /* data */      this.grpfnc_clspurchasetotal            = -1 * GGC.Common.bigint(dat.grpfnc_clspurchasetotal);
        /* data */      this.grpfnc_alltotal                    =      GGC.Common.bigint(dat.grpfnc_alltotal);
        /* data */      this.modidt                             =      GGC.Common.datetime(dat.modidt);
        /* custom */    this.pk = `grpno="${this.grpno}"`;
    }

    /* ========================= */
    /* getter */
    /* ========================= */
    /* data */  getGrpno() { return this.grpno; }
    /* data */  getGrpfncCapitaltotal() { return this.grpfnc_capitaltotal; }
    /* data */  getGrpfncSponsorshiptotal() { return this.grpfnc_sponsorshiptotal; }
    /* data */  getGrpfncPurchasetotal() { return this.grpfnc_purchasetotal; }
    /* data */  getGrpfncLosstotal() { return this.grpfnc_losstotal; }
    /* data */  getGrpfncClssalestotal() { return this.grpfnc_clssalestotal; }
    /* data */  getGrpfncClssalesunpaidtotal() { return this.grpfnc_clssalesunpaidtotal; }
    /* data */  getGrpfncClssaleslosstotal() { return this.grpfnc_clssaleslosstotal; }
    /* data */  getGrpfncClspurchasetotal() { return this.grpfnc_clspurchasetotal; }
    /* data */  getGrpfncAlltotal() { return this.grpfnc_alltotal; }
    /* data */  getModidt() { return this.modidt; }


    /* ========================= */
    /* make */
    /* ========================= */
    /* Won */      getGrpfncCapitaltotalWon() { return GGC.Common.priceWon(this.getGrpfncCapitaltotal()); }
    /* wonColor */ getGrpfncCapitaltotalWonColor() { return GGC.Common.wonColor(this.getGrpfncCapitaltotal()); }
    /* wonColor */ getGrpfncSponsorshiptotalWonColor() { return GGC.Common.wonColor(this.getGrpfncSponsorshiptotal()); }
    /* wonColor */ getGrpfncPurchasetotalWonColor() { return GGC.Common.wonColor(this.getGrpfncPurchasetotal()); }
    /* wonColor */ getGrpfncLosstotalWonColor() { return GGC.Common.wonColor(this.getGrpfncLosstotal()); }
    /* wonColor */ getGrpfncClssalestotalWonColor() { return GGC.Common.wonColor(this.getGrpfncClssalestotal()); }
    /* wonColor */ getGrpfncClssalesunpaidtotalWonColor() { return GGC.Common.wonColor(this.getGrpfncClssalesunpaidtotal()); }
    /* wonColor */ getGrpfncClssaleslosstotalWonColor() { return GGC.Common.wonColor(this.getGrpfncClssaleslosstotal()); }
    /* wonColor */ getGrpfncClspurchasetotalWonColor() { return GGC.Common.wonColor(this.getGrpfncClspurchasetotal()); }
    /* wonColor */ getGrpfncAlltotalWonColor() { return GGC.Common.wonColor(this.getGrpfncAlltotal()); }
    getModidtDiff() { return GGC.Common.dateDiff(this.getModidt()); }

}


class MGrpfncas extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MGrpfnca(dat));
        }
    } /* constructor */


}