GGF.User =
{
    Usertype :
    {
        NORMAL : "normal", /* GGF.User.Usertype.NORMAL : 일반 */
        TEMP   : "temp",   /* GGF.User.Usertype.TEMP : 임시 */
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
        YET             : "yet",                /* GGF.Cls.Clssettleflg.YET : 미정산 */
        DONE            : "done",               /* GGF.Cls.Clssettleflg.DONE : 정산완료 */
    },
    Grpfinancereflectflg :
    {
        Y               : "y",                  /* GGF.Cls.Grpfinancereflectflg.Y : 반영 */
        N               : "n",                  /* GGF.Cls.Grpfinancereflectflg.N : 미반영 */
        UNABLE          : "unable",             /* GGF.Cls.Grpfinancereflectflg.UNABLE : 반영불가 */
    },
};
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
    },

    Grpmstatus :
    {
        ACTIVE      : 'active',     /* GGF.GrpMember.Grpmstatus.ACTIVE */
        DELETE      : 'delete',     /* GGF.GrpMember.Grpmstatus.DELETE */
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
