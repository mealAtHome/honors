class MClssettlehist
{
    constructor(dat)
    {
        /* data */      this.grpno = GGC.Common.char(dat.grpno);
        /* data */      this.clsno = GGC.Common.char(dat.clsno);
        /* data */      this.histno = GGC.Common.int(dat.histno);
        /* data */      this.userno = GGC.Common.char(dat.userno);
        /* data */      this.histtype = GGC.Common.enum(dat.histtype);
        /* data */      this.billstandard = GGC.Common.int(dat.billstandard);
        /* data */      this.billadjustment = GGC.Common.int(dat.billadjustment);
        /* data */      this.billpointed = GGC.Common.int(dat.billpointed);
        /* data */      this.billfinal = GGC.Common.int(dat.billfinal);
        /* data */      this.billmemo = GGC.Common.varchar(dat.billmemo);
        /* data */      this.billfinalafter = GGC.Common.int(dat.billfinalafter);
        /* data */      this.regdt = GGC.Common.datetime(dat.regdt);
        /* data */      this.username = GGC.Common.char(dat.username);
        /* custom */    this.pk = `grpno="${this.grpno}" clsno="${this.clsno}" userno="${this.userno}" histno="${this.histno}"`;
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    /* data */
    getGrpno() { return this.grpno; }
    getClsno() { return this.clsno; }
    getHistno() { return this.histno; }
    getUserno() { return this.userno; }
    getHisttype() { return this.histtype; }
    getBillstandard() { return this.billstandard; }
    getBilladjustment() { return this.billadjustment; }
    getBillpointed() { return this.billpointed; }
    getBillfinal() { return this.billfinal; }
    getBillmemo() { return this.billmemo; }
    getBillfinalafter() { return this.billfinalafter; }
    getRegdt() { return this.regdt; }
    getUsername() { return this.username; }

    /* custom */
    getPk() { return this.pk; }

    /* custom > custom */
    getHisttypeFont() { return GGC.Clssettlehist.histtypeFont(this.getHisttype()); }
    getBillstandardWon() { return GGC.Common.priceWon(this.billstandard); }
    getBilladjustmentWon() { return GGC.Common.priceWon(this.billadjustment); }
    getBillpointedWon() { return GGC.Common.priceWon(this.billpointed); }
    getBillfinalWon() { return GGC.Common.priceWon(this.billfinal); }
    getBillfinalafterWon() { return GGC.Common.priceWon(this.billfinalafter); }

    /* ========================= */
    /* fields - flg */
    /* ========================= */

    /* ========================= */
    /* fields - additional */
    /* ========================= */

}

class MClssettlehists extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MClssettlehist(dat));
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
                    <td col="username"           >${model.getUsername()}</td>
                    <td col="histtype"           >${model.getHisttypeFont()}</td>
                    <td col="billfinal"          >${model.getBillfinalWon()}</td>
                    <td col="billfinalafter"     >${model.getBillfinalafterWon()}</td>
                    <td col="billadjustment"     >${model.getBilladjustmentWon()}</td>
                    <td col="billpointed"        >${model.getBillpointedWon()}</td>
                    <td col="billmemo"           >${Common.ifempty(model.getBillmemo(), "-")}</td>
                    <td col="regdt"              >${model.getRegdt()}</td>
                </tr>
            `;
        }

        html =
        `
            <table class="MClssettlehist-makeTable-mainTable common-tbl-normal" tbl-type="rowborder">
                <thead>
                    <tr>
                        <th>청구대상</th>
                        <th>이력타입</th>
                        <th>변경전</th>
                        <th>변경후</th>
                        <th>청구보정</th>
                        <th>잔여금사용</th>
                        <th>비고</th>
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