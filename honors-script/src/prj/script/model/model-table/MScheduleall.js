class MScheduleall
{
    constructor(dat)
    {
        /* data */      this.sclyear                = GGC.Common.int(dat.sclyear);
        /* data */      this.sclmonth               = GGC.Common.tinyint(dat.sclmonth);
        /* data */      this.sclweek                = GGC.Common.tinyint(dat.sclweek);
        /* data */      this.sclstartdate           = GGC.Common.date(dat.sclstartdate);
        /* data */      this.sclclosedate           = GGC.Common.date(dat.sclclosedate);
        /* data */      this.sclsubmit              = Common.ifEmpty(GGC.Common.enum(dat.sclsubmit), "n");
        /* custom */    this.pointOfDate            = GGdate.getPointOfDate(new Date(), new Date(this.sclstartdate), new Date(this.sclclosedate));
        /* custom */    this.pk                     = `sclyear="${this.sclyear}" sclmonth="${this.sclmonth}" sclweek="${this.sclweek}"`;
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    /* data */      getSclyear() { return this.sclyear; }
    /* data */      getSclmonth() { return this.sclmonth; }
    /* data */      getSclweek() { return this.sclweek; }
    /* data */      getSclstartdate() { return this.sclstartdate; }
    /* data */      getSclclosedate() { return this.sclclosedate; }
    /* data */      getSclsubmit() { return this.sclsubmit; }
    /* custom */    getPointOfDate() { return this.pointOfDate; }
    /* custom */    getPk() { return this.pk; }

    /* ========================= */
    /* fields - flg */
    /* ========================= */
    isSameYearMonth(year, month) { return this.getSclyear() == year && this.getSclmonth() == month; }
    // isManagerdepositflgYes() { return this.getManagerdepositflg() === GGF.Y; }

    /* ========================= */
    /* fields - additional */
    /* ========================= */
    // getClsstatusCard() { return GGC.Cls.clsstatusCard(this.getClsstatus()); }

}

class MSchedulealls extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MScheduleall(dat));
        }
    }

    /* ========================= */
    /* ========================= */
    makeWeekList(el="")
    {
        let html =
        `
            <table class="common-tbl-normal" tbl-type="noborder">
                <tbody>
        `;

        let now = new Date();
        let nowY = null;
        for(let i = 0; i < this.models.length; i++)
        {
            let model = this.models[i];
            let pointOfDate = model.getPointOfDate();

            /* by year */
            if(nowY != model.getSclyear())
            {
                nowY = model.getSclyear();
                html +=
                `
                    <tr>
                        <td colspan="2">
                            <div class="common-div-calendarSquareYear">
                                <span>${model.getSclyear()}년</span>
                            </div>
                        </td>
                    </tr>
                `;
            }

            /* by month */
            if(model.isSameYearMonth(nowY, now.getMonth() + 1))
                pointOfDate = GGF.GGdate.PointOfDate.WITHIN;
            html +=
            `
                <tr>
                    <td>
                        <div class="common-div-calendarSquare" common-type="alone" point-of-date="${pointOfDate}">
                            <div><span>${model.getSclmonth()}월</span></div>
                        </div>
                    </td>
                    <td>
                        <div class="common-div-calendarSquareWeekTop">
            `;

            /* by week */
            let nowM = model.getSclmonth();
            let skipCount = 0;
            for(let j = i; j < this.models.length; j++)
            {
                let modelJ = this.models[j];
                if(nowM != modelJ.getSclmonth())
                    break;

                /* set type */
                html += `<button class="common-btn-outline" point-of-date="${modelJ.getPointOfDate()}">${modelJ.getSclweek()}주</button>`;

                skipCount++;
            }
            i += skipCount - 1;

            /* close tr */
            html += "</div></td></tr>";
        }

        /* close table */
        html +=
        `
                </tbody>
            </table>
        `;
        $(el).html(html);
    }

}