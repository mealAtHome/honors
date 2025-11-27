class MAddressSigungu
{
    constructor(dat)
    {
        this.sdidx = GGC.Common.int(dat.sdidx);
        this.sggidx = GGC.Common.int(dat.sggidx);
        this.sggname = GGC.Common.char(dat.sggname);
        this.sdname = GGC.Common.char(dat.sdname);
        this.pk = `sdidx="${this.sdidx}" sggidx="${this.sggidx}"`;
    }

    getSdidx()   { return this.sdidx; }
    getSggidx()  { return this.sggidx; }
    getSggname() { return this.sggname; }
    getSdname()  { return this.sdname; }

    static makeInline(sdidx ,sdname, sggidx, sggname)
    {
        let html =
        `
            <div class="MAddressSigungu-makeInline-div-top common-tag-fontsize09" sdidx="${sdidx}" sdname="${sdname}" sggidx="${sggidx}" sggname="${sggname}">
                <span style="vertical-align:middle;">${sdname} ${sggname}</span>
                <button class="MAddressSigungu-makeInline-btn-delete common-btn-noline common-tag-fontsize09" btn-type="cancel" style="padding:0.3em;" sdidx="${sdidx}" sggidx="${sggidx}">삭제</button>
            </div>
        `;
        return html;
    }
}


class MAddressSigungus extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MAddressSigungu(dat));
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
            html += `<option value="${model.getSggidx()}" ${isFirst ? "selected" : ""}>${model.getSggname()}</option>`;

            if(isFirst)
                isFirst = false;
        }
        $(el).html(html);
    }

}