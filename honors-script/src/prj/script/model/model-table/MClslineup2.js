class MClslineup2
{
    constructor(dat)
    {
        /* data */      this.grpno              = GGC.Common.char(dat.grpno);
        /* data */      this.clsno              = GGC.Common.char(dat.clsno);
        /* data */      this.teamname           = GGC.Common.char(dat.teamname);
        /* data */      this.teamnick           = GGC.Common.char(dat.teamnick);
        /* data */      this.orderno            = GGC.Common.int(dat.orderno);
        /* data */      this.battingflg         = GGC.Common.enum(dat.battingflg);
        /* data */      this.position           = GGC.Common.char(dat.position);
        /* data */      this.userno             = GGC.Common.char(dat.userno);
        /* data */      this.username           = GGC.Common.char(dat.username);
        /* data */      this.userregdt          = GGC.Common.datetime(dat.userregdt);
        /* data */      this.bill               = GGC.Common.int(dat.bill);
        /* data */      this.etc                = GGC.Common.varchar(dat.etc);
        /* data */      this.clsstatus          = GGC.Common.char(dat.clsstatus);
        /* data */      this.cnt                = GGC.Common.int(dat.cnt);
        /* data */      this.memberpoint        = GGC.Common.int(dat.memberpoint);
        /* data */      this.applyername        = GGC.Common.char(dat.applyername);
        /* custom */    this.memberpointwon     = GGC.Common.priceWon(this.memberpoint);
        /* custom */    this.pk                 = `grpno="${this.grpno}" clsno="${this.clsno}" teamname="${this.teamname}" orderno="${this.orderno}"`;
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    /* data */      getGrpno() { return this.grpno; }
    /* data */      getClsno() { return this.clsno; }
    /* data */      getTeamname() { return this.teamname; }
    /* data */      getTeamnick() { return this.teamnick; }
    /* data */      getOrderno() { return this.orderno; }
    /* data */      getBattingflg() { return this.battingflg; }
    /* data */      getPosition() { return this.position; }
    /* data */      getUserno() { return this.userno; }
    /* data */      getUsername() { return this.username; }
    /* data */      getBill() { return this.bill; }
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

    /* ========================= */
    /* fields - flg */
    /* ========================= */
    isBattingflgY() { return this.getBattingflg() === GGF.Y; }
    hasApplyer()    { return !Common.isEmpty(this.getUsername()); }

}

class MClslineup2s extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat  = this.data[i];
            this.models.push(new MClslineup2(dat));
        }
    }

    getTeamnick(teamname="a")
    {
        let teamnick = "";
        for(let i in this.getModels())
        {
            let model = this.getModels()[i];
            if(model.getTeamname() == teamname)
            {
                teamnick = model.getTeamnick();
                break;
            }
        }
        return teamnick;
    }

}