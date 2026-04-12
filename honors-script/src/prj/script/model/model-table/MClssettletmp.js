class MClssettletmp extends MClssettle
{
    constructor(dat)
    {
        super(dat);
    }
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