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

    /* ========================= */
    /* make */
    /* ========================= */
    make(grpintro="")
    {
        let heroHtml = "";
        if(grpintro != "")
        {
            heroHtml =
            `
                <div class="common-div-card">
                    <div class="common-header"><i class="ti ti-speakerphone"></i><span>한 줄 소개</span></div>
                    <div class="common-cushionHalfUp common-fonts10">${GGC.Common.escapeHtml(grpintro)}</div>
                </div>
            `;
        }

        return `
            ${heroHtml}
            <div class="common-div-card">
                <div class="common-header"><i class="ti ti-info-circle"></i><span>모임소개</span></div>
                <div class="common-cushionHalfUp common-colorBody common-fonts09" style="white-space:pre-wrap; line-height:1.6;">${GGC.Common.escapeHtml(this.getGrpintrodetail())}</div>
            </div>
            <div class="common-div-card">
                <div class="common-header"><i class="ti ti-gavel"></i><span>운영원칙 및 규칙</span></div>
                <div class="common-cushionHalfUp common-colorBody common-fonts09" style="white-space:pre-wrap; line-height:1.6;">${GGC.Common.escapeHtml(this.getGrprules())}</div>
            </div>
        `;
    }
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
