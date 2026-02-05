class MGrpformnglog
{
    constructor(dat)
    {
        /* data */      this.grpno                              = GGC.Common.char(dat.grpno);
        /* data */      this.gfmlfield                          = GGC.Common.varchar(dat.gfmlfield);
        /* data */      this.gfmlkeyno                          = GGC.Common.int(dat.gfmlkeyno);
        /* data */      this.gfmlhistno                         = GGC.Common.int(dat.gfmlhistno);
        /* data */      this.gfmltype                           = GGC.Common.enum(dat.gfmltype);
        /* data */      this.gfmlcontent                        = GGC.Common.varchar(dat.gfmlcontent);
        /* data */      this.gfmlsummarypar                     = GGC.Common.int(dat.gfmlsummarypar);
        /* data */      this.gfmlsummaryreal                    = GGC.Common.int(dat.gfmlsummaryreal);
        /* data */      this.gfmlcomment                        = GGC.Common.varchar(dat.gfmlcomment);
        /* data */      this.regdt                              = GGC.Common.datetime(dat.regdt);
    }

    /* ========================= */
    /* getter */
    /* ========================= */
    /* data */  getGrpno() { return this.grpno; }
    /* data */  getGfmlfield() { return this.gfmlfield; }
    /* data */  getGfmlkeyno() { return this.gfmlkeyno; }
    /* data */  getGfmlhistno() { return this.gfmlhistno; }
    /* data */  getGfmltype() { return this.gfmltype; }
    /* data */  getGfmlcontent() { return this.gfmlcontent; }
    /* data */  getGfmlsummarypar() { return this.gfmlsummarypar; }
    /* data */  getGfmlsummaryreal() { return this.gfmlsummaryreal; }
    /* data */  getGfmlcomment() { return this.gfmlcomment; }
    /* data */  getRegdt() { return this.regdt; }
    /* pk */    getPk() { return `grpno="${this.grpno}" gfmlfield="${this.gfmlfield}" gfmlkeyno="${this.gfmlkeyno}" gfmlhistno="${this.gfmlhistno}"`; }

    /* ========================= */
    /* make */
    /* ========================= */
    getGfmlsummaryrealWon() { return GGC.Common.priceWon(this.getGfmlsummaryreal()); }

}


class MGrpformnglogs extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MGrpformnglog(dat));
        }
    } /* constructor */

    /* ========================= */
    /* 자본금 */
    /* ========================= */
    makeTableForCapital(el="")
    {
        /* html */
        let html = "";
        for(let i in this.getModels())
        {
            let model = this.getModels()[i];
            html +=
            `
                <tr>
                    <td style="text-align:center; white-space:nowrap;                        ">${model.getRegdt()}</td>
                    <td style="text-align:center; white-space:normal; word-break:break-word; ">${model.getGfmlsummaryrealWon()}</td>
                    <td style="text-align:center; white-space:nowrap;                        ">${model.getGfmlcomment()}</td>
                </tr>
            `;
        }

        /* table */
        html =
        `
            <div class="common-div-tableScrollX">
                <table class="MGrpformnglog-makeTableForCapital-tbl-top common-tbl-normal" tbl-type="rowborder">
                    <thead>
                        <tr>
                            <th>변경일자</th>
                            <th>변경 후 자본금</th>
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
        if(this.getPagecnt() > 1)
        {
            let pagenation = this.getPagenation();
            html = pagenation + html + pagenation;
        }
        $(el).html(html);
    }

}