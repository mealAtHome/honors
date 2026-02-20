class MGrpfncSponsorship
{
    constructor(dat)
    {
        /* data */      this.grpno = GGC.Common.char(dat.grpno);
        /* data */      this.sponidx = GGC.Common.int(dat.sponidx);
        /* data */      this.sponuserno = GGC.Common.varchar(dat.sponuserno);
        /* data */      this.sponusername = GGC.Common.varchar(dat.sponusername);
        /* data */      this.spontype = GGC.Common.enum(dat.spontype);
        /* data */      this.sponitem = GGC.Common.varchar(dat.sponitem);
        /* data */      this.sponcost = GGC.Common.int(dat.sponcost);
        /* data */      this.sponcomment = GGC.Common.varchar(dat.sponcomment);
        /* data */      this.regdt = GGC.Common.datetime(dat.regdt);
        /* data */      this.username = GGC.Common.varchar(dat.username);
    }

    /* ========================= */
    /* getter */
    /* ========================= */
    /* data */      getGrpno() { return this.grpno; }
    /* data */      getSponidx() { return this.sponidx; }
    /* data */      getSponuserno() { return this.sponuserno; }
    /* data */      getSponusername() { return this.sponusername; }
    /* data */      getSpontype() { return this.spontype; }
    /* data */      getSponitem() { return this.sponitem; }
    /* data */      getSponcost() { return this.sponcost; }
    /* data */      getSponcomment() { return this.sponcomment; }
    /* data */      getRegdt() { return this.regdt; }
    /* data */      getUsername() { return this.username; }

    /* ========================= */
    /* make */
    /* ========================= */
    /* custom */    getSponcostPriceFull() { return GGC.Common.priceFull(this.getSponcost()); }
    /* custom */    getSpontypeFont() { return GGC.GrpfncSponsorship.spontypeFont(this.getSpontype()); }
    /* custom */    getUsernameForDp() { return Common.isEmpty(this.getSponuserno()) ? this.getSponusername() : this.getUsername(); }
    /* custom */    getPk() { return `grpno="${this.getGrpno()}" sponidx="${this.getSponidx()}"`; }

    getSponitemFinal()
    {
        switch(this.getSpontype())
        {
            case GGF.GrpfncSponsorship.Spontype.THING: return this.getSponitem();
            case GGF.GrpfncSponsorship.Spontype.MONEY: return GGC.GrpfncSponsorship.spontypeCvrt(this.getSpontype());
        }
        return null;
    }
}


class MGrpfncSponsorships extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MGrpfncSponsorship(dat));
        }
    } /* constructor */

    makeTable(el="")
    {
        let html = "";
        for(let i in this.models)
        {
            let model = this.models[i];
            html +=
            `
                <tr>
                    <td col="sponidx"           >${model.getSponidx()}</td>
                    <td col="regdt"             >${model.getRegdt()}</td>
                    <td col="username"          >${model.getUsernameForDp()}</td>
                    <td col="sponitem"          >${model.getSponitemFinal()}</td>
                    <td col="sponcost"          >${model.getSponcostPriceFull()}</td>
                    <td col="sponcomment"       >${model.getSponcomment()}</td>
                </tr>
            `;
        }

        html =
        `
            <div class="common-div-scrollX">
                <table class="MGrpfncSponsorship-makeTable-mainTable common-tbl-normal" tbl-type="rowborder">
                    <thead>
                        <tr>
                            <th>번호</th>
                            <th>등록일</th>
                            <th>찬조자</th>
                            <th>품목</th>
                            <th>금액</th>
                            <th>비고</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${html}
                    </tbody>
                </table>
            </div>
        `;

        /* pagenation */
        let pagenation = this.getPagenation();
        html = pagenation + html + pagenation;
        $(el).html(html);
    }

}