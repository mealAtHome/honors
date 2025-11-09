class MMonth
{
    constructor(dat)
    {
        this.month = dat.month;
        this.cnt   = dat.cnt;
    }
    getMonth() { return this.month; }
    getCnt()   { return this.cnt; }
}

class MMonths extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MMonth(dat));
        }
    }
}