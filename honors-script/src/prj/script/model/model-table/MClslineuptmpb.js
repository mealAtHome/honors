class MClslineuptmpb
{
    constructor(dat)
    {
        /* data */  this.grpno          = GGC.Common.char(dat.grpno);
        /* data */  this.lineupgroup    = GGC.Common.char(dat.lineupgroup);
        /* data */  this.lineupidx      = GGC.Common.int(dat.lineupidx);
        /* data */  this.lineupname     = GGC.Common.varchar(dat.lineupname);
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    /* data */  getGrpno()       { return this.grpno; }
    /* data */  getLineupgroup() { return this.lineupgroup; }
    /* data */  getLineupidx()   { return this.lineupidx; }
    /* data */  getLineupname()  { return this.lineupname; }
}

class MClslineuptmpbs extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MClslineuptmpb(dat));
        }
    }
}
