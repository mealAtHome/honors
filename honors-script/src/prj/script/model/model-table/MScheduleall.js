class MScheduleall
{
    constructor(dat)
    {
        /* data */      this.sclyear                = GGC.Common.int(dat.sclyear);
        /* data */      this.sclmonth               = GGC.Common.tinyint(dat.sclmonth);
        /* data */      this.sclweek                = GGC.Common.tinyint(dat.sclweek);
        /* data */      this.sclstartdate           = GGC.Common.date(dat.sclstartdate);
        /* data */      this.sclclosedate           = GGC.Common.date(dat.sclclosedate);
        /* custom */    this.pk                     = `sclyear="${this.sclyear}" sclmonth="${this.sclmonth}" sclweek="${this.sclweek}"`;
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    /* data */      getSclyear() { return this.sclyear; }
    /* data */      getSclmonth() { return this.sclmonth; }
    /* data */      getSclweek() { return this.sclweek; }
    /* data */      getSclstartdate() { return this.sclstartdate; }
    /* data */      getSclclosedate() { return this.sclclosedate; }
    /* custom */    getPk() { return this.pk; }

    /* ========================= */
    /* fields - flg */
    /* ========================= */
    // isManagerdepositflgYes() { return this.getManagerdepositflg() === GGF.Y; }

    /* ========================= */
    /* fields - additional */
    /* ========================= */
    // getClsstatusCard() { return GGC.Cls.clsstatusCard(this.getClsstatus()); }

}

class MSchedulealls extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MScheduleall(dat));
        }
    }

    /* ========================= */
    /* ========================= */
    makeWithMonth(el="")
    {
        let html = "";
        let y = null;
        let m = null;
        for(let i in this.models)
        {
            let model = this.models[i];

            let yChanged = false;
            let mChanged = false;
            if(y != model.getSclyear())  { yChanged = true; y = model.getSclyear(); }
            if(m != model.getSclmonth()) { mChanged = true; m = model.getSclmonth(); }

            if(yChanged)
            {
                
            }
        }
        return html;
    }

}