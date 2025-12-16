class MSchedulebyweek
{
    constructor(dat)
    {
        /* data */      this.userno                 = GGC.Common.char(dat.userno);
        /* data */      this.sclyear                = GGC.Common.int(dat.sclyear);
        /* data */      this.sclmonth               = GGC.Common.tinyint(dat.sclmonth);
        /* data */      this.sclweek                = GGC.Common.tinyint(dat.sclweek);
        /* data */      this.scletc                 = GGC.Common.varchar(dat.scletc);
        /* data */      this.sclsubmit              = GGC.Common.enum(dat.sclsubmit);
        /* data */      this.modidt                 = GGC.Common.datetime(dat.modidt);
        /* data */      this.regdt                  = GGC.Common.datetime(dat.regdt);
        /* data */      this.sclstartdate           = GGC.Common.date(dat.sclstartdate);
        /* data */      this.sclclosedate           = GGC.Common.date(dat.sclclosedate);
        /* custom */    this.pk                     = `userno="${this.userno}" sclyear="${this.sclyear}" sclmonth="${this.sclmonth}" sclweek="${this.sclweek}"`;
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    /* data */          getUserno() { return this.userno; }
    /* data */          getSclyear() { return this.sclyear; }
    /* data */          getSclmonth() { return this.sclmonth; }
    /* data */          getSclweek() { return this.sclweek; }
    /* data */          getScletc() { return this.scletc; }
    /* data */          getSclsubmit() { return this.sclsubmit; }
    /* data */          getModidt() { return this.modidt; }
    /* data */          getRegdt() { return this.regdt; }
    /* data */          getSclstartdate() { return this.sclstartdate; }
    /* data */          getSclclosedate() { return this.sclclosedate; }
    /* custom */        getPk() { return this.pk; }

    /* ========================= */
    /* fields - flg */
    /* ========================= */
    // isManagerdepositflgYes() { return this.getManagerdepositflg() === GGF.Y; }

    /* ========================= */
    /* fields - additional */
    /* ========================= */
    // getClsstatusCard() { return GGC.Cls.clsstatusCard(this.getClsstatus()); }

}

class MSchedulebyweeks extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MSchedulebyweek(dat));
        }
    }

    /* ========================= */
    /* ========================= */
    makeTimeTable(el="")
    {
        if(this.models.length == 0)
            return;

        /* =============== */
        /* make html */
        /* =============== */
        let modelFirst = this.models[0];
        let sclstartdate = new Date(modelFirst.getSclstartdate());
        let weekhtml = "";
        let timeArr = [];
        let timehtml = "";

        /* loop 7 days */
        for(let i = 0; i < 7; i++)
        {
            let date = new Date(sclstartdate);
            date = new Date(date.setDate(sclstartdate.getDate() + i));
            weekhtml += `<th date-day="${date.getDay()}">${GGdate.getD(date)}<br>${GGdate.getDDDD(date)}</th>`;

            /* loop 6 to 23 */
            timeArr[i] = [];
            for(let j = 0; j < 36; j++)
            {
                let hour = 6 + Math.floor(j / 2);
                let min  = (j % 2) * 30;
                timeArr[i][j] = `<td date-day="${i}" time-hour="${hour}" time-min="${min}"></td>`;
            }
        }

        /* make timehtml */
        for(let j = 0; j < 36; j++)
        {
            timehtml += "<tr>";
            for(let i = 0; i < 7; i++)
                timehtml += timeArr[i][j];
            timehtml += "</tr>";
        }

        /* make html */
        let html =
        `
            <table class="common-tbl-normal" tbl-type="noborder">
                <thead>
                    <tr>
                        ${weekhtml}
                    </tr>
                </thead>
                <tbody>
                    ${timehtml}
                </tbody>
            </table>
        `;
        $(el).html(html);
    }

}