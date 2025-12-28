class MClspurchase
{
    constructor(dat)
    {
        /* data */      this.grpno = GGC.Common.char(dat.grpno);
        /* data */      this.clsno = GGC.Common.char(dat.clsno);
        /* data */      this.purchaseidx = GGC.Common.int(dat.purchaseidx);
        /* data */      this.productname = GGC.Common.char(dat.productname);
        /* data */      this.productbill = GGC.Common.int(dat.productbill);
        /* data */      this.regdt = GGC.Common.datetime(dat.regdt);
        /* custom */    this.pk = `grpno="${this.grpno}" clsno="${this.clsno}" purchaseidx="${this.purchaseidx}"`;
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    /* data */      getGrpno() { return this.grpno; }
    /* data */      getClsno() { return this.clsno; }
    /* data */      getPurchaseidx() { return this.purchaseidx; }
    /* data */      getProductname() { return this.productname; }
    /* data */      getProductbill() { return this.productbill; }
    /* data */      getRegdt() { return this.regdt; }
    /* custom */    getPk() { return this.pk; }
    /* custom */    getProductbillWon() { return GGC.Common.priceWon(this.productbill); }

    /* ========================= */
    /* fields - flg */
    /* ========================= */
    // isManagerdepositflgYes() { return this.getManagerdepositflg() === GGF.Y; }

    /* ========================= */
    /* fields - additional */
    /* ========================= */
    // getClstypeCvrt()   { return GGC.Cls.clstypeCvrt(this.getClstype()); }
    // getClsstatusCard() { return GGC.Cls.clsstatusCard(this.getClsstatus()); }

}

class MClspurchases extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MClspurchase(dat));
        }
    }
}