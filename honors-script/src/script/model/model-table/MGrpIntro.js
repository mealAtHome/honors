class MGrpIntro
{
    constructor(dat)
    {
        /* data */ this.grpno         = GGC.Common.char(dat.grpno);
        /* data */ this.grpintrodetail = GGC.Common.varchar(dat.grpintrodetail);
        /* data */ this.grprules      = GGC.Common.varchar(dat.grprules);
        /* data */ this.modidt        = GGC.Common.datetime(dat.modidt);
        /* data */ this.regidt        = GGC.Common.datetime(dat.regidt);
    }

    /* ========================= */
    /* getter */
    /* ========================= */
    getGrpno() { return this.grpno; }
    getGrpintrodetail() { return this.grpintrodetail; }
    getGrprules() { return this.grprules; }
    getModidt() { return this.modidt; }
    getRegidt() { return this.regidt; }
}


class MGrpIntros extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MGrpIntro(dat));
        }
    } /* constructor */

}
