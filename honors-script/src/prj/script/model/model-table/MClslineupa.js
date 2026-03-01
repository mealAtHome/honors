class MClslineupa
{
    constructor(dat)
    {
        /* data */      this.grpno      = GGC.Common.char(dat.grpno);
        /* data */      this.clsno      = GGC.Common.char(dat.clsno);
        /* data */      this.lineupidx  = GGC.Common.int(dat.lineupidx);
        /* data */      this.lineupname = GGC.Common.varchar(dat.lineupname);
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    /* data */  getGrpno()      { return this.grpno; }
    /* data */  getClsno()      { return this.clsno; }
    /* data */  getLineupidx()  { return this.lineupidx; }
    /* data */  getLineupname() { return this.lineupname; }
}

class MClslineupaList extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MClslineupa(dat));
        }
    }
}
