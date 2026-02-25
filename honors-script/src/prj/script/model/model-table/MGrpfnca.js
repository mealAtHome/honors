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
    getGrpfncCapitaltotalPriceColor() { return GGC.Common.priceColor(this.getGrpfncCapitaltotal()); }
    getGrpfncSponsorshiptotalPriceColor() { return GGC.Common.priceColor(this.getGrpfncSponsorshiptotal()); }
    getGrpfncPurchasetotalPriceColor() { return GGC.Common.priceColor(this.getGrpfncPurchasetotal()); }
    getGrpfncLosstotalPriceColor() { return GGC.Common.priceColor(this.getGrpfncLosstotal()); }
    getGrpfncClssalestotalPriceColor() { return GGC.Common.priceColor(this.getGrpfncClssalestotal()); }
    getGrpfncClssalesunpaidtotalPriceColor() { return GGC.Common.priceColor(this.getGrpfncClssalesunpaidtotal()); }
    getGrpfncClssaleslosstotalPriceColor() { return GGC.Common.priceColor(this.getGrpfncClssaleslosstotal()); }
    getGrpfncClspurchasetotalPriceColor() { return GGC.Common.priceColor(this.getGrpfncClspurchasetotal()); }
    getGrpfncAlltotalPriceColor() { return GGC.Common.priceColor(this.getGrpfncAlltotal()); }
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