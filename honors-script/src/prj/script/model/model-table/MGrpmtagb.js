class MGrpmtagb
{
    constructor(dat)
    {
        /* data */      this.grpno    = GGC.Common.char(dat.grpno);
        /* data */      this.tagidx   = GGC.Common.int(dat.tagidx);
        /* data */      this.userno   = GGC.Common.char(dat.userno);
        /* data */      this.regdt    = GGC.Common.datetime(dat.regdt);
        /* data */      this.username = GGC.Common.varchar(dat.username);
    }

    /* ========================= */
    /* getter */
    /* ========================= */
    /* data */      getGrpno() { return this.grpno; }
    /* data */      getTagidx() { return this.tagidx; }
    /* data */      getUserno() { return this.userno; }
    /* data */      getRegdt() { return this.regdt; }
    /* data */      getUsername() { return this.username; }
}


class MGrpmtagbs extends _MCommon
{
    constructor(ajax)
    {
        super(ajax);
        for(let i in this.data)
        {
            let dat = this.data[i];
            this.models.push(new MGrpmtagb(dat));
        }
    } /* constructor */

    /* ========================= */
    /* 이 태그에 이미 등록된 userno 목록 (일괄처리 화면의 체크박스 초기값용) */
    /* ========================= */
    getUsernoArr() { return this.getModels().map(model => model.getUserno()); }

}
