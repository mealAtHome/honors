class MGrpMemberPointhist
{
    constructor(dat)
    {
        /* data */      this.grpno              = GGC.Common.char(dat.grpno);
        /* data */      this.userno             = GGC.Common.char(dat.userno);
        /* data */      this.pointhistdt        = GGC.Common.date(dat.pointhistdt);
        /* data */      this.pointhistno        = GGC.Common.int(dat.pointhistno);
        /* data */      this.point              = GGC.Common.int(dat.point);
        /* data */      this.pointleft          = GGC.Common.int(dat.pointleft);
        /* data */      this.pointmemo          = GGC.Common.varchar(dat.pointmemo);
        /* data */      this.relclsno           = GGC.Common.char(dat.relclsno);
        /* data */      this.regidt             = GGC.Common.datetime(dat.regidt);
        /* custom */    this.pointtype          = GGC.GrpMemberPointhist.pointtype(dat.point);
        /* custom */    this.pointtypePretty    = GGC.GrpMemberPointhist.pointtypePretty(dat.point);
        /* custom */    this.pointPretty        = GGC.GrpMemberPointhist.pointPretty(dat.point);
        /* custom */    this.pointleftWon       = GGC.Common.priceWon(dat.pointleft);
        /* custom */    this.pk                 = `grpno="${this.grpno}" userno="${this.userno}" pointhistdt="${this.pointhistdt}" pointhistno="${this.pointhistno}"`;
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    getGrpno() { return this.grpno; }
    getUserno() { return this.userno; }
    getPointhistdt() { return this.pointhistdt; }
    getPointhistno() { return this.pointhistno; }
    getPoint() { return this.point; }
    getPointleft() { return this.pointleft; }
    getPointmemo() { return this.pointmemo; }
    getRelclsno() { return this.relclsno; }
    getRegidt() { return this.regidt; }

    /* custom */
    getPointtype() { return this.pointtype; }
    getPointtypePretty() { return this.pointtypePretty; }
    getPointPretty() { return this.pointPretty; }
    getPointleftWon() { return this.pointleftWon; }
    getPk() { return this.pk; }

    /* ========================= */
    /* fields - flg */
    /* ========================= */
    // isBattingflgY() { return this.getBattingflg() === GGF.Y; }
    // hasApplyer()    { return !Common.isEmpty(this.getUsername()); }

    /* ========================= */
    /* fields - additional */
    /* ========================= */
    // getClstypeCvrt()   { return GGC.Cls.clstypeCvrt(this.getClstype()); }
    // getClsstatusCard() { return GGC.Cls.clsstatusCard(this.getClsstatus()); }

}

class MGrpMemberPointhists extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MGrpMemberPointhist(dat));
        }
    }

    /* ========================= */
    /* ========================= */
    make(el="")
    {
        /* --------------- */
        /* loop clss */
        /* --------------- */
        let html = "";
        for(let i in this.getModels())
        {
            let model = this.getModels()[i];
            html +=
            `
                <div class="MGrpMemberPointhists-make-div-modelTop common-div-card" style="padding:0.2em 0.2em;">
                    <table class="common-tbl-normal" tbl-type="noborder" style="width:100%;">
                        <tbody>
                            <tr>
                                <td class="common-tag-alignL">
                                    <span class="common-tag-block common-tag-fontsize09">${model.getRegidt()}</span>
                                    <span class="common-tag-block common-tag-bold">${model.getPointmemo()}</span>
                                </td>
                                <td class="common-tag-alignR">
                                    <span class="common-tag-block common-tag-fontsize09">${model.getPointtypePretty()}</span>
                                    <span class="common-tag-block common-tag-bold">${model.getPointPretty()}</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="common-tag-alignR">
                                    <span class="common-tag-colorGrey common-tag-fontsize09">잔액 ${model.getPointleftWon()}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            `;
        }
        $(el).html(html);
    }

}