/*
    grp_joinreq : 모임 가입신청
    데이터·상태 판단만 둔다. 화면 html은 각 페이지에서 만든다 (PC 웹 재사용을 위해 렌더링을 모델 밖에 둠).
*/
class MGrpJoinreq
{
    constructor(dat)
    {
        /* data */ this.grpno      = GGC.Common.char(dat.grpno);
        /* data */ this.userno     = GGC.Common.char(dat.userno);
        /* data */ this.reqmsg     = GGC.Common.varchar(dat.reqmsg);
        /* data */ this.reqstatus  = GGC.Common.enum(dat.reqstatus);
        /* data */ this.procdt     = GGC.Common.datetime(dat.procdt);
        /* data */ this.regdt      = GGC.Common.datetime(dat.regdt);
        /* data */ this.username   = GGC.Common.varchar(dat.username);
        /* data */ this.birthyear  = GGC.Common.int(dat.birthyear);
        /* data */ this.hascarflg  = GGC.Common.enum(dat.hascarflg);
        /* custom */ this.pk       = `grpno="${this.grpno}" userno="${this.userno}"`;
    }

    getGrpno() { return this.grpno; }
    getUserno() { return this.userno; }
    getReqmsg() { return this.reqmsg; }
    getReqstatus() { return this.reqstatus; }
    getProcdt() { return this.procdt; }
    getRegdt() { return this.regdt; }
    getUsername() { return this.username; }
    getBirthyear() { return this.birthyear; }
    getHascarflg() { return this.hascarflg; }
    getPk() { return this.pk; }

    isWait() { return this.reqstatus === GGF.GrpJoinreq.Reqstatus.WAIT; }
    isRejected() { return this.reqstatus === GGF.GrpJoinreq.Reqstatus.REJECTED; }
}

class MGrpJoinreqs extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
            this.models.push(new MGrpJoinreq(this.data[i]));
    }
}
