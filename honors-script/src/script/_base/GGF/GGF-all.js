GGF.User =
{
    Usertype :
    {
        NORMAL : "normal", /* GGF.User.Usertype.NORMAL : 일반 */
        TEMP   : "temp",   /* GGF.User.Usertype.TEMP : 임시 */
    },
};

GGF.Grp =
{
    Clstermunit :
    {
        DAY   : "d", /* GGF.Grp.Clstermunit.DAY : 일 */
        WEEK  : "w", /* GGF.Grp.Clstermunit.WEEK : 주 */
        MONTH : "m", /* GGF.Grp.Clstermunit.MONTH : 월 */
        YEAR  : "y", /* GGF.Grp.Clstermunit.YEAR : 연 */
    },
    Grptype :
    {
        TEAM      : "team",      /* GGF.Grp.Grptype.TEAM : 팀 */
        CLUB      : "club",      /* GGF.Grp.Grptype.CLUB : 모임 */
        MERCENARY : "mercenary", /* GGF.Grp.Grptype.MERCENARY : 용병 모임 */
    },
    Joinpolicy :
    {
        APPROVE : "approve", /* GGF.Grp.Joinpolicy.APPROVE : 승인 후 가입 */
        FREE    : "free",    /* GGF.Grp.Joinpolicy.FREE : 바로 가입 */
        NONE    : "none",    /* GGF.Grp.Joinpolicy.NONE : 가입 받지 않음 */
    },
    Clsopen :
    {
        MEMBER : "member", /* GGF.Grp.Clsopen.MEMBER : 일정은 멤버만 */
        PUBLIC : "public", /* GGF.Grp.Clsopen.PUBLIC : 일정을 용병에게 공개 */
    },
};

GGF.Bankaccount =
{
    Bacctype :
    {
        USER : "user", /* GGF.Bankaccount.Bacctype.USER : 일반계좌 */
        GRP : "grp", /* GGF.Bankaccount.Bacctype.GRP : 모임계좌 */
    },
    Defaultflg:
    {
        Y : "y", /* GGF.Bankaccount.Defaultflg.Y : 기본계좌 */
        N : "n", /* GGF.Bankaccount.Defaultflg.N : 일반계좌 */
    },
};

GGF.Cls =
{
    Clsstatus :
    {
        EDIT            : "edit",               /* GGF.Cls.Clsstatus.EDIT : 작성중 */
        ING             : "ing",                /* GGF.Cls.Clsstatus.ING : 진행중 */
        END             : "end",                /* GGF.Cls.Clsstatus.END : 일정완료 */
        CANCEL          : "cancel",             /* GGF.Cls.Clsstatus.CANCEL : 일정취소 */
    },
    Clssettleflg :
    {
        EDIT            : "edit",               /* GGF.Cls.Clssettleflg.EDIT : 정산입력중 */
        DONE            : "done",               /* GGF.Cls.Clssettleflg.DONE : 정산확정됨 */
    },
    Grpfinancereflectflg :
    {
        Y               : "y",                  /* GGF.Cls.Grpfinancereflectflg.Y : 반영 */
        N               : "n",                  /* GGF.Cls.Grpfinancereflectflg.N : 미반영 */
        UNABLE          : "unable",             /* GGF.Cls.Grpfinancereflectflg.UNABLE : 반영불가 */
    },
};
GGF.Clssettle =
{
    Settlestatus :
    {
        WAIT           : "wait",               /* GGF.Clssettle.Settlestatus.WAIT : 입금대기 */
        MEMB           : "memb",               /* GGF.Clssettle.Settlestatus.MEMB : 입금완료 */
        DONE           : "done",               /* GGF.Clssettle.Settlestatus.DONE : 확인완료 */
        LOSS           : "loss",               /* GGF.Clssettle.Settlestatus.LOSS : 손실 */
    }
}
GGF.Clssettlehist =
{
    Histtype :
    {
        UPDATE  : "update", /* GGF.Clssettlehist.Histtype.UPDATE : 갱신 */
        DELETE  : "delete", /* GGF.Clssettlehist.Histtype.DELETE : 삭제 */
        AFTER   : "after",  /* GGF.Clssettlehist.Histtype.AFTER  : 추가 */
    },
};
GGF.Clspurchasehist =
{
    Histtype :
    {
        INSERT  : "insert", /* GGF.Clspurchasehist.Histtype.INSERT : 추가 */
        UPDATE  : "update", /* GGF.Clspurchasehist.Histtype.UPDATE : 갱신 */
        DELETE  : "delete", /* GGF.Clspurchasehist.Histtype.DELETE : 삭제 */
    },
};

GGF.PaymentMissedreq =
{
    Status :
    {
        REQ         : 'req',        /* GGF.PaymentMissedreq.Status.REQ */
        REQCANCEL   : 'reqcancel',  /* GGF.PaymentMissedreq.Status.REQCANCEL */
        ING         : 'ing',        /* GGF.PaymentMissedreq.Status.ING */
        NG          : 'ng',         /* GGF.PaymentMissedreq.Status.NG */
        OKWAIT      : 'okwait',     /* GGF.PaymentMissedreq.Status.OKWAIT */
        OK          : 'ok',         /* GGF.PaymentMissedreq.Status.OK */
    },
};

GGF.GrpMember =
{
    Grpmtype :
    {
        MNG         : 'mng',        /* GGF.GrpMember.Grpmtype.MNG */
        MNGSUB      : 'mngsub',     /* GGF.GrpMember.Grpmtype.MNGSUB */
        MEMBER      : 'member',     /* GGF.GrpMember.Grpmtype.MEMBER */
        GUEST       : 'guest',      /* GGF.GrpMember.Grpmtype.GUEST : 용병으로 참가한 비멤버 */
    },

    Grpmstatus :
    {
        ACTIVE      : 'active',     /* GGF.GrpMember.Grpmstatus.ACTIVE */
        DELETE      : 'delete',     /* GGF.GrpMember.Grpmstatus.DELETE */
    },
};

GGF.GrpJoinreq =
{
    Reqstatus :
    {
        WAIT     : 'wait',     /* GGF.GrpJoinreq.Reqstatus.WAIT : 승인 대기 */
        APPROVED : 'approved', /* GGF.GrpJoinreq.Reqstatus.APPROVED : 승인 */
        REJECTED : 'rejected', /* GGF.GrpJoinreq.Reqstatus.REJECTED : 거절 */
        CANCELED : 'canceled', /* GGF.GrpJoinreq.Reqstatus.CANCELED : 신청 취소 */
    },
};

GGF.GrpmPrivacy =
{
    PrivPhone :
    {
        ALL : "all", /* GGF.GrpmPrivacy.PrivPhone.ALL : 전화번호 전체공개 */
        GRP : "grp", /* GGF.GrpmPrivacy.PrivPhone.GRP : 전화번호 모임공개 */
        MNG : "mng", /* GGF.GrpmPrivacy.PrivPhone.MNG : 전화번호 관리자공개 */
        ANY : "any", /* GGF.GrpmPrivacy.PrivPhone.ANY : 전화번호 비공개 */
    },
}

GGF.GrpfncSponsorship =
{
    Spontype :
    {
        THING : 'thing', /* GGF.GrpfncSponsorship.Spontype.THING : 단발형 */
        MONEY : 'money', /* GGF.GrpfncSponsorship.Spontype.MONEY : 금전형 */
    },
};
