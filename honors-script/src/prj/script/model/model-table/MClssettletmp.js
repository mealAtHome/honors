class MClssettletmp extends MClssettle
{
    constructor(dat)
    {
        super(dat);
        this.settledeleteflg = GGC.Common.enum(dat.settledeleteflg);
    }
    getSettledeleteflg() { return this.settledeleteflg; }

    isSettledelete() { return this.getSettledeleteflg() == GGF.Y; }
}

class MClssettletmps extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MClssettletmp(dat));
        }
    }

}