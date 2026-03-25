class MClslineuptmpa
{
    constructor(dat)
    {
        /* data */  this.grpno        = GGC.Common.char(dat.grpno);
        /* data */  this.lineupgroup  = GGC.Common.char(dat.lineupgroup);
        /* data */  this.lineuptitle  = GGC.Common.varchar(dat.lineuptitle);
        /* data */  this.regdt        = GGC.Common.datetime(dat.regdt);
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    /* data */  getGrpno()          { return this.grpno; }
    /* data */  getLineupgroup()    { return this.lineupgroup; }
    /* data */  getLineuptitle()    { return this.lineuptitle; }
    /* data */  getRegdt()          { return this.regdt; }

    /* ========================= */
    /* make with buttons */
    /* ========================= */
    make(btnHtml="")
    {
        if(btnHtml != "")
            btnHtml = `<div class="common-div-cushionUp"><div class="common-div-btnList common-tag-fontsize09">${btnHtml}</div></div>`;
        let html =
        `
            <div class="MClslineuptmpa-make-div-modelTop common-div-card">
                <p class="common-p-entityTitle">라인업 템플릿</p>
                <table class="common-tbl-normal" tbl-type="noborder">
                    <tbody>
                        <tr>
                            <td><span class="common-tag-block common-tag-fontsize10">${this.getLineuptitle()}</span></td>
                        </tr>
                    </tbody>
                </table>
                ${btnHtml}
            </div>
        `;
        return html;
    }
}

class MClslineuptmpas extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MClslineuptmpa(dat));
        }
    }

    make()
    {

    }
}
