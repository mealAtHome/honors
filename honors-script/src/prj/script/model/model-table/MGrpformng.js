class MGrpformng
{
    constructor(dat)
    {
        /* data */      this.grpno                              = GGC.Common.char(dat.grpno);
        /* data */      this.grpfnc_capitaltotal                = GGC.Common.bigint(dat.grpfnc_capitaltotal);
        /* data */      this.grpfnc_sponsorshiptotal            = GGC.Common.bigint(dat.grpfnc_sponsorshiptotal);
        /* data */      this.grpfnc_purchasetotal               = GGC.Common.bigint(dat.grpfnc_purchasetotal);
        /* data */      this.grpfnc_losstotal                   = GGC.Common.bigint(dat.grpfnc_losstotal);
        /* data */      this.grpfnc_clssalestotal               = GGC.Common.bigint(dat.grpfnc_clssalestotal);
        /* data */      this.grpfnc_clssalesunpaidtotal         = GGC.Common.bigint(dat.grpfnc_clssalesunpaidtotal);
        /* data */      this.grpfnc_clssaleslosstotal           = GGC.Common.bigint(dat.grpfnc_clssaleslosstotal);
        /* data */      this.grpfnc_clspurchasetotal            = GGC.Common.bigint(dat.grpfnc_clspurchasetotal);
        /* data */      this.grpfnc_alltotal                    = GGC.Common.bigint(dat.grpfnc_alltotal);
        /* data */      this.modidt                             = GGC.Common.datetime(dat.modidt);
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
    getGrpfncCapitaltotalWon() { return GGC.Common.priceWon(this.getGrpfncCapitaltotal()); }
    getGrpfncSponsorshiptotalWon() { return GGC.Common.priceWon(this.getGrpfncSponsorshiptotal()); }
    getGrpfncPurchasetotalWon() { return GGC.Common.priceWon(this.getGrpfncPurchasetotal()); }
    getGrpfncLosstotalWon() { return GGC.Common.priceWon(this.getGrpfncLosstotal()); }
    getGrpfncClssalestotalWon() { return GGC.Common.priceWon(this.getGrpfncClssalestotal()); }
    getGrpfncClssalesunpaidtotalWon() { return GGC.Common.priceWon(this.getGrpfncClssalesunpaidtotal()); }
    getGrpfncClssaleslosstotalWon() { return GGC.Common.priceWon(this.getGrpfncClssaleslosstotal()); }
    getGrpfncClspurchasetotalWon() { return GGC.Common.priceWon(this.getGrpfncClspurchasetotal()); }
    getGrpfncAlltotalWon() { return GGC.Common.priceWon(this.getGrpfncAlltotal()); }

}


class MGrpformngs extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MGrpformng(dat));
        }
    } /* constructor */


}