class MYear
{
    constructor(dat)
    {
        this.year = dat.year;
        this.cnt  = dat.cnt;
    }
    getYear()       { return this.year; }
    getCnt()        { return this.cnt; }
}

class MYears extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MYear(dat));
        }
    }
}