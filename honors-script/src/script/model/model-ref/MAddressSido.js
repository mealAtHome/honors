class MAddressSido
{
    constructor(dat)
    {
        this.sdidx = GGC.Common.int(dat.sdidx);
        this.sdname = GGC.Common.char(dat.sdname);
    }

    getSdidx()     { return this.sdidx; }
    getSdname()    { return this.sdname; }
}


class MAddressSidos extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MAddressSido(dat));
        }
    } /* construnctor */

    makeOption(el="")
    {
        let html = "";
        let isFirst = true;
        let models = this.getModels();
        for(let i in models)
        {
            let model = models[i];
            html += `<option value="${model.getSdidx()}" ${isFirst ? "selected" : ""}>${model.getSdname()}</option>`;

            if(isFirst)
                isFirst = false;
        }
        $(el).html(html);

        /* --------------- */
        /* set event */
        /* --------------- */

    }

}