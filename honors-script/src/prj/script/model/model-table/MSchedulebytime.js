class MScheduletime
{
    constructor(dat)
    {
        /* data */      this.userno                 = GGC.Common.char(dat.userno);
        /* data */      this.sclyear                = GGC.Common.int(dat.sclyear);
        /* data */      this.sclmonth               = GGC.Common.tinyint(dat.sclmonth);
        /* data */      this.sclweek                = GGC.Common.tinyint(dat.sclweek);
        /* data */      this.scldate                = GGC.Common.date(dat.scldate);
        /* data */      this.sclstarttime           = GGC.Common.time(dat.sclstarttime);
        /* data */      this.sclclosetime           = GGC.Common.time(dat.sclclosetime);
        /* data */      this.sclfreelevel           = GGC.Common.tinyint(dat.sclfreelevel);
        /* data */      this.modidt                 = GGC.Common.datetime(dat.modidt);
        /* data */      this.regdt                  = GGC.Common.datetime(dat.sclstartdate);
        /* data */      this.sclstartdate           = GGC.Common.date(dat.sclstartdate);
        /* data */      this.sclclosedate           = GGC.Common.date(dat.sclclosedate);
        /* custom */    this.pointOfDate            = GGdate.getPointOfDate(new Date(), new Date(this.sclstartdate), new Date(this.sclclosedate));
        /* custom */    this.pk                     = `sclyear="${this.sclyear}" sclmonth="${this.sclmonth}" sclweek="${this.sclweek}"`;
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    /* data */          getUserno() { return this.userno; }
    /* data */          getSclyear() { return this.sclyear; }
    /* data */          getSclmonth() { return this.sclmonth; }
    /* data */          getSclweek() { return this.sclweek; }
    /* data */          getScldate() { return this.scldate; }
    /* data */          getSclstarttime() { return this.sclstarttime; }
    /* data */          getSclclosetime() { return this.sclclosetime; }
    /* data */          getSclfreelevel() { return this.sclfreelevel; }
    /* data */          getModidt() { return this.modidt; }
    /* data */          getRegdt() { return this.regdt; }
    /* data */          getSclstartdate() { return this.sclstartdate; }
    /* data */          getSclclosedate() { return this.sclclosedate; }
    /* custom */        getPointOfDate() { return this.pointOfDate; }
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

class MScheduletimes extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MScheduletime(dat));
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
        let modelF = this.models[0];
        let sclstartdate = modelF.getSclstartdate();
        let weekhtml = "";
        let timeArr = [];
        let timehtml = "";
        for(let i = 0; i < 7; i++)
        {
            let date = new Date(sclstartdate);
            weekhtml += `<th date-day="${date.getDay()}">${GGdate.toMDdddd(date)}</th>`;
            sclstartdate = GGdate.addDays(sclstartdate, 1);

            /* 6 to 23 */
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
            {
                timehtml += timeArr[i][j];
            }
            timehtml += "</tr>";
        }
    }

}