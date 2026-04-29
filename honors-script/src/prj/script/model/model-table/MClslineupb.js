class MClslineupb
{
    constructor(dat)
    {
        /* data */      this.grpno              = GGC.Common.char(dat.grpno);
        /* data */      this.clsno              = GGC.Common.char(dat.clsno);
        /* data */      this.lineupidx          = GGC.Common.char(dat.lineupidx);
        /* data */      this.lineupname         = GGC.Common.char(dat.lineupname);
        /* data */      this.orderno            = GGC.Common.int(dat.orderno);
        /* data */      this.battingflg         = GGC.Common.enum(dat.battingflg);
        /* data */      this.position           = GGC.Common.char(dat.position);
        /* data */      this.userno             = GGC.Common.char(dat.userno);
        /* data */      this.username           = GGC.Common.char(dat.username);
        /* data */      this.userregdt          = GGC.Common.datetime(dat.userregdt);
        /* data */      this.bill               = GGC.Common.int(dat.bill);
        /* data */      this.prepaidflg         = GGC.Common.enum(dat.prepaidflg);
        /* data */      this.etc                = GGC.Common.varchar(dat.etc);
        /* data */      this.clsstatus          = GGC.Common.char(dat.clsstatus);
        /* data */      this.cnt                = GGC.Common.int(dat.cnt);
        /* data */      this.memberpoint        = GGC.Common.int(dat.memberpoint);
        /* data */      this.applyername        = GGC.Common.char(dat.applyername);
        /* custom */    this.memberpointwon     = GGC.Common.priceWon(this.memberpoint);
        /* custom */    this.pk                 = `grpno="${this.grpno}" clsno="${this.clsno}" lineupidx="${this.lineupidx}" orderno="${this.orderno}"`;
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    /* data */      getGrpno() { return this.grpno; }
    /* data */      getClsno() { return this.clsno; }
    /* data */      getLineupidx() { return this.lineupidx; }
    /* data */      getLineupname() { return this.lineupname; }
    /* data */      getOrderno() { return this.orderno; }
    /* data */      getBattingflg() { return this.battingflg; }
    /* data */      getPosition() { return this.position; }
    /* data */      getUserno() { return this.userno; }
    /* data */      getUsername() { return this.username; }
    /* data */      getBill() { return this.bill; }
    /* data */      getPrepaidflg() { return this.prepaidflg; }
    /* data */      getEtc() { return this.etc; }
    /* data */      getClsstatus() { return this.clsstatus; }
    /* data */      getUserregdt() { return this.userregdt; }
    /* data */      getCnt() { return this.cnt; }
    /* data */      getMemberpoint() { return this.memberpoint; }
    /* data */      getApplyername() { return this.applyername; }
    /* custom */    getMemberpointWon() { return this.memberpointwon; }
    /* custom */    getPk() { return this.pk; }

    /* ========================= */
    /* fields - additional */
    /* ========================= */
    /* data */ getBillWon() { return GGC.Common.priceWon(this.bill); }
    /* data */ getPrepaidflgCvrt() { return GGC.Clslineupb.prepaidflgCvrt(this.prepaidflg); }
    /* data */ getPrepaidflgCard() { return GGC.Clslineupb.prepaidflgCard(this.prepaidflg); }
    /* data */ getPrepaidflgFont() { return GGC.Clslineupb.prepaidflgFont(this.prepaidflg); }

    /* ========================= */
    /* fields - flg */
    /* ========================= */
    isBattingflgY() { return this.getBattingflg() === GGF.Y; }
    hasApplyer()    { return !Common.isEmpty(this.getUsername()); }

}

class MClslineupbs extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MClslineupb(dat));
        }
    }

    groupByForSettle()
    {
        let arr = [];
        for(let i in this.models)
        {
            let model = this.models[i];
            let grpno = model.getGrpno();
            let clsno = model.getClsno();
            let userno = model.getUserno();
            let username = model.getUsername();
            let bill = model.getBill();
            let memberpoint = model.getMemberpoint();
            let prepaidflg = model.getPrepaidflg();

            // let dat =
            // {
            //     grpno        : grpno,
            //     clsno        : clsno,
            //     userno       : userno,
            //     username     : username,
            //     bill         : bill,
            //     memberpoint  : memberpoint,
            // };

            let isExist = false;
            for(let j in arr)
            {
                let dat = arr[j];
                if(dat.getGrpno() === grpno && dat.getClsno() === clsno && dat.getUserno() === userno)
                {
                    dat.bill += bill;
                    dat.billprepaid += prepaidflg === GGF.Y ? bill : 0;
                    isExist = true;
                    break;
                }
            }

            if(!isExist)
            {
                let tmp =
                {
                    grpno        : grpno,
                    clsno        : clsno,
                    userno       : userno,
                    username     : username,
                    bill         : bill,
                    billprepaid  : prepaidflg === GGF.Y ? bill : 0,
                    memberpoint  : memberpoint,
                };
                let dat = new MClslineupb(tmp);
                arr.push(dat);
            }
        }
        return arr;
    }

}