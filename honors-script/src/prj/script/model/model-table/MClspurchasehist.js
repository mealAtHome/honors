class MClspurchasehist
{
    constructor(dat)
    {
        /* data */      this.grpno = GGC.Common.char(dat.grpno);
        /* data */      this.clsno = GGC.Common.char(dat.clsno);
        /* data */      this.histno = GGC.Common.int(dat.histno);
        /* data */      this.histtype = GGC.Common.enum(dat.histtype);
        /* data */      this.purchaseidx = GGC.Common.int(dat.purchaseidx);
        /* data */      this.productname = GGC.Common.varchar(dat.productname);
        /* data */      this.productbill = GGC.Common.int(dat.productbill);
        /* data */      this.regdt = GGC.Common.datetime(dat.regdt);
        /* custom */    this.pk = `grpno="${this.grpno}" clsno="${this.clsno}" histno="${this.histno}"`;
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    /* data */
    getGrpno() { return this.grpno; }
    getClsno() { return this.clsno; }
    getHistno() { return this.histno; }
    getHisttype() { return this.histtype; }
    getPurchaseidx() { return this.purchaseidx; }
    getProductname() { return this.productname; }
    getProductbill() { return this.productbill; }
    getRegdt() { return this.regdt; }

    /* custom */
    getPk() { return this.pk; }

    /* custom > custom */
    getHisttypeFont() { return GGC.Clspurchasehist.histtypeFont(this.getHisttype()); }
    getProductbillWon() { return GGC.Common.priceWon(this.productbill); }

    /* ========================= */
    /* fields - flg */
    /* ========================= */

    /* ========================= */
    /* fields - additional */
    /* ========================= */

}

class MClspurchasehists extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MClspurchasehist(dat));
        }
    }

    /* ========================= */
    /* ========================= */
    makeTable(el="")
    {
        let html = "";
        for(let i in this.models)
        {
            let model = this.models[i];
            html +=
            `
                <tr>
                    <td col="histno"             >${model.getHistno()}</td>
                    <td col="histtype"           >${model.getHisttypeFont()}</td>
                    <td col="productname"        >${model.getProductname()}</td>
                    <td col="productbill"        >${model.getProductbillWon()}</td>
                    <td col="regdt"              >${model.getRegdt()}</td>
                </tr>
            `;
        }

        html =
        `
            <table class="MClspurchasehist-makeTable-mainTable common-tbl-normal" tbl-type="rowborder">
                <thead>
                    <tr>
                        <th>번호</th>
                        <th>이력타입</th>
                        <th>품목</th>
                        <th>금액</th>
                        <th>등록일</th>
                    </tr>
                </thead>
                <tbody>
                    ${html}
                </tbody>
            </table>
        `;
        $(el).html(html);
    }

    /* ========================= */
    /* ========================= */
    make(el="")
    {

    }

}