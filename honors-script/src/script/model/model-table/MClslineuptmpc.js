class MClslineuptmpc
{
    constructor(dat)
    {
        /* data */      this.grpno                = GGC.Common.char(dat.grpno);
        /* data */      this.lineupgroup          = GGC.Common.char(dat.lineupgroup);
        /* data */      this.lineupidx            = GGC.Common.char(dat.lineupidx);
        /* data */      this.orderno              = GGC.Common.int(dat.orderno);
        /* data */      this.battingflg           = GGC.Common.enum(dat.battingflg);
        /* data */      this.position             = GGC.Common.char(dat.position);
        /* data */      this.isfollowstandardbill = GGC.Common.enum(dat.isfollowstandardbill);
        /* data */      this.bill                 = GGC.Common.int(dat.bill);
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    /* data */  getGrpno()                { return this.grpno; }
    /* data */  getLineupgroup()          { return this.lineupgroup; }
    /* data */  getLineupidx()            { return this.lineupidx; }
    /* data */  getOrderno()              { return this.orderno; }
    /* data */  getBattingflg()           { return this.battingflg; }
    /* data */  getPosition()             { return this.position; }
    /* data */  getIsfollowstandardbill() { return this.isfollowstandardbill; }
    /* data */  getBill()                 { return this.bill; }

    isBattingflgY() { return this.battingflg === GGF.Y; }
}

class MClslineuptmpcs extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MClslineuptmpc(dat));
        }
    }
}
