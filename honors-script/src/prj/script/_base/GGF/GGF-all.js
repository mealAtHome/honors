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
        GRP : "grp", /* GGF.Bankaccount.Bacctype.GRP : 그룹계좌 */
    },
    Defaultflg:
    {
        Y : "y", /* GGF.Bankaccount.Defaultflg.Y : 기본계좌 */
        N : "n", /* GGF.Bankaccount.Defaultflg.N : 일반계좌 */
    },
};

GGF.Cls =
{
    Clstype :
    {
        LINEUP1         : "lineup1",            /* GGF.Cls.Clstype.LINEUP1 : 라인업(1팀) */
        LINEUP2         : "lineup2",            /* GGF.Cls.Clstype.LINEUP2 : 라인업(1시합) */
        LINEUP4         : "lineup4",            /* GGF.Cls.Clstype.LINEUP4 : 라인업(2시합) */
    },
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
    }
};
