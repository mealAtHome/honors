class MGrpformng
{
    constructor(dat)
    {
        /* data */      this.grpno = GGC.Common.char(dat.grpno);
        /* data */      this.grpfnc_capital = GGC.Common.int(dat.grpfnc_capital);
        /* data */      this.grpfnc_sales = GGC.Common.int(dat.grpfnc_sales);
        /* data */      this.grpfnc_purchase = GGC.Common.int(dat.grpfnc_purchase);
        /* data */      this.grpfnc_profit = GGC.Common.int(dat.grpfnc_profit);
        /* data */      this.modidt = GGC.Common.datetime(dat.modidt);
        /* custom */    this.pk = `grpno="${this.grpno}"`;
    }

    /* ========================= */
    /* getter */
    /* ========================= */
    /* data */  getGrpno() { return this.grpno; }
    /* data */  getGrpfncCapital() { return this.grpfnc_capital; }
    /* data */  getGrpfncSales() { return this.grpfnc_sales; }
    /* data */  getGrpfncPurchase() { return this.grpfnc_purchase; }
    /* data */  getGrpfncProfit() { return this.grpfnc_profit; }
    /* data */  getModidt() { return this.modidt; }


    /* ========================= */
    /* make */
    /* ========================= */
    getGrpfncCapitalWon() { return GGC.Common.priceWon(this.getGrpfncCapital()); }
    getGrpfncSalesWon() { return GGC.Common.priceWon(this.getGrpfncSales()); }
    getGrpfncPurchaseWon() { return GGC.Common.priceWon(this.getGrpfncPurchase()); }
    getGrpfncProfitWon() { return GGC.Common.priceWon(this.getGrpfncProfit()); }

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