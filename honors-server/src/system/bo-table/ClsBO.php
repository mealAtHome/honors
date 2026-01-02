<?php

class ClsBO extends _CommonBO
{
    /* ----- */
    /* singleton */
    /* ----- */
    private static $bo;
    public static function getInstance()
    {
        if(self::$bo == null)
            self::$bo = new static();
        return self::$bo;
    }
    function setBO()
    {
        GGnavi::getIdxBO();
        GGnavi::getGrpBO();
        GGnavi::getClslineup2BO();
        GGnavi::getPaymentABO();
        GGnavi::getGrpMemberBO();
        GGnavi::getClssettleBO();
        GGnavi::getClzcancelBO();
        $arr = array();
        $arr['ggAuth']       = GGauth::getInstance();
        $arr['idxBO']        = IdxBO::getInstance();
        $arr['grpBO']        = GrpBO::getInstance();
        $arr['clslineup2BO'] = Clslineup2BO::getInstance();
        $arr['paymentABO']   = PaymentABO::getInstance();
        $arr['grpMemberBO']  = GrpMemberBO::getInstance();
        $arr['clssettleBO'] = ClssettleBO::getInstance();
        $arr['clzcancelBO']  = ClzcancelBO::getInstance();
        return $arr;
    }
    /* ========================= */
    /* field */
    /*
    */
    /* ========================= */
    const FIELD__GRPNO                      = "grpno";                      /* (PK) char(30)              / NO  */
    const FIELD__CLSNO                      = "clsno";                      /* (PK) char(14)              / NO  */
    const FIELD__CLSSTATUS                  = "clsstatus";                  /* (  ) char(30)              / NO  */
    const FIELD__CLSSETTLEFLG               = "clssettleflg";               /* (  ) enum('yet','done')    / YES */
    const FIELD__CLSTYPE                    = "clstype";                    /* (  ) char(30)              / NO  */
    const FIELD__CLSTITLE                   = "clstitle";                   /* (  ) char(50)              / NO  */
    const FIELD__CLSCONTENT                 = "clscontent";                 /* (  ) varchar(255)          / YES  */
    const FIELD__CLSSTARTDT                 = "clsstartdt";                 /* (  ) datetime              / NO  */
    const FIELD__CLSCLOSEDT                 = "clsclosedt";                 /* (  ) datetime              / NO  */
    const FIELD__CLSGROUND                  = "clsground";                  /* (  ) char(50)              / NO  */
    const FIELD__CLSGROUNDADDR              = "clsgroundaddr";              /* (  ) char(50)              / YES */
    const FIELD__CLSBILLAPPLYPRICE          = "clsbillapplyprice";          /* (  ) int                   / NO  */
    const FIELD__CLSBILLAPPLYUNIT           = "clsbillapplyunit";           /* (  ) int                   / NO  */
    const FIELD__CLSAPPLYSTARTDT            = "clsapplystartdt";            /* (  ) datetime              / NO  */
    const FIELD__CLSAPPLYCLOSEDT            = "clsapplyclosedt";            /* (  ) datetime              / NO  */
    const FIELD__CLSUSERNOREG               = "clsusernoreg";               /* (  ) char(30)              / YES */
    const FIELD__CLSUSERNOADM               = "clsusernoadm";               /* (  ) char(30)              / YES */
    const FIELD__CLSUSERNOSUB               = "clsusernosub";               /* (  ) char(30)              / YES */
    const FIELD__CLSBILLSALES               = "clsbillsales";               /* (  ) int(11)               / YES */
    const FIELD__CLSBILLPURCHASE            = "clsbillpurchase";            /* (  ) int(11)               / YES */
    const FIELD__CLSBILLFINAL               = "clsbillfinal";               /* (  ) int(11)               / YES */
    const FIELD__GRPFINANCEREFLECTFLG       = "grpfinancereflectflg";       /* (  ) enum('y','n','skip')  / YES */
    const FIELD__CLSMODIDT                  = "clsmodidt";                  /* (  ) datetime              / YES */
    const FIELD__CLSREGDT                   = "clsregdt";                   /* (  ) datetime              / YES */

    /* ========================= */
    /* enum */
    /*
    */
    /* ========================= */
    const CLSTYPE__LINEUP1          = "lineup1";            /* clstype : 일반참가 */
    const CLSTYPE__LINEUP2          = "lineup2";            /* clstype : 라인업1시합 */
    const CLSTYPE__LINEUP4          = "lineup4";            /* clstype : 라인업2시합 */
    const CLSSTATUS__EDIT           = "edit";               /* clsstatus : 작성중 */
    const CLSSTATUS__ING            = "ing";                /* clsstatus : 진행중 */
    const CLSSTATUS__END            = "end";                /* clsstatus : 일정완료 */
    const CLSSTATUS__CANCEL         = "cancel";             /* clsstatus : 취소 */
    const CLSSETTLEFLG__YET         = "yet";                /* clssettleflg : 미정산 */
    const CLSSETTLEFLG__DONE        = "done";               /* clssettleflg : 정산완료 */

    /* ========================= */
    /* select options */
    /* ========================= */
    static public function getConsts()
    {
        $arr = array();
        $arr['clstypeLineup1']                  = self::CLSTYPE__LINEUP1;       /* 일반참가 */
        $arr['clstypeLineup2']                  = self::CLSTYPE__LINEUP2;       /* 라인업1시합 */
        $arr['clstypeLineup4']                  = self::CLSTYPE__LINEUP4;       /* 라인업2시합 */
        $arr['clsstatusEdit']                   = self::CLSSTATUS__EDIT;        /* 일정상태 : 작성중 */
        $arr['clsstatusIng']                    = self::CLSSTATUS__ING;         /* 일정상태 : 진행중 */
        $arr['clsstatusEnd']                    = self::CLSSTATUS__END;         /* 일정상태 : 일정완료 */
        $arr['clsstatusCancel']                 = self::CLSSTATUS__CANCEL;      /* 일정상태 : 취소 */
        $arr['clssettleflgYet']                 = self::CLSSETTLEFLG__YET;      /* 정산여부 : 미정산 */
        $arr['clssettleflgDone']                = self::CLSSETTLEFLG__DONE;     /* 정산여부 : 정산완료 */
        return $arr;
    }


    /* ========================= */
    /* select > sub > sub */
    /* ========================= */
    public function getByPk($GRPNO, $CLSNO) { return Common::getDataOne($this->selectByPkForInside($GRPNO, $CLSNO)); }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByPkForInside($GRPNO, $CLSNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /* ========================= */
    const selectByPkForInside = "selectByPkForInside";
    const selectByPkForAll = "selectByPkForAll";
    const selectByPkForMng = "selectByPkForMng";
    const selectByGrpnoForMng = "selectByGrpnoForMng"; /* GRPNO, PAGEFLG, PAGENUM */
    const selectByClsstatusForMng = "selectByClsstatusForMng";
    const selectAppliedFor1YearByUserno = "selectAppliedFor1YearByUserno";
    const selectFor1YearByGrpnoForAll = "selectFor1YearByGrpnoForAll";

    const selectForUserByClsstatusIng       = "selectForUserByClsstatusIng";        /* [user] [EXECUTOR]        : 탭검색 */
    const selectForUserByClsstatusEnd       = "selectForUserByClsstatusEnd";        /* [user] [EXECUTOR]        : 탭검색 */
    const selectForUserByClsstatusCancel    = "selectForUserByClsstatusCancel";     /* [user] [EXECUTOR]        : 탭검색 */
    const selectForMngrByClsstatusEdit      = "selectForMngrByClsstatusEdit";       /* [mngr] [EXECUTOR, GRPNO] : 탭검색 */
    const selectForMngrByClsstatusIng       = "selectForMngrByClsstatusIng";        /* [mngr] [EXECUTOR, GRPNO] : 탭검색 */
    const selectForMngrByClssettleflgN      = "selectForMngrByClssettleflgN";       /* [mngr] [EXECUTOR, GRPNO] : 탭검색 */
    const selectForMngrByClsstatusEnd       = "selectForMngrByClsstatusEnd";        /* [mngr] [EXECUTOR, GRPNO] : 탭검색 */
    const selectForMngrByClsstatusCancel    = "selectForMngrByClsstatusCancel";     /* [mngr] [EXECUTOR, GRPNO] : 탭검색 */
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        extract($this->setBO());
        extract(ClsBO::getConsts());
        extract(GrpMemberBO::getConsts());
        extract($options);

        /* orderride option */
        if($option != "")
            $OPTION = $option;

        /* --------------- */
        /* sql body */
        /* --------------- */
        $query  = "";
        $select = "";
        $from   = "";
        $select =
        "
              t.grpno
            , t.clsno
            , t.clsstatus
            , t.clssettleflg
            , t.clstype
            , t.clstitle
            , t.clscontent
            , t.clsstartdt
            , t.clsclosedt
            , t.clsground
            , t.clsgroundaddr
            , t.clsbillapplyprice
            , t.clsbillapplyunit
            , t.clsapplystartdt
            , t.clsapplyclosedt
            , t.clsusernoreg
            , t.clsusernoadm
            , t.clsusernosub
            , t.clsmodidt
            , t.clsregdt
            , grpu.id grpmanagerid
            , grp.grpimg
            , grp.grpname
            , u1.name clsusernoregname
            , u2.name clsusernoadmname
            , u3.name clsusernosubname
            , clzc.clscancelreason
        ";
        $selectForMng =
        "
            , t.clsbillsales
            , t.clsbillpurchase
            , t.clsbillfinal
            , t.grpfinancereflectflg
        ";

        /* --------------- */
        /* validation */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPkForMng:
            case self::selectByGrpnoForMng:
            case self::selectByClsstatusForMng:
            case self::selectForMngrByClsstatusEdit:
            case self::selectForMngrByClsstatusIng:
            case self::selectForMngrByClssettleflgN:
            case self::selectForMngrByClsstatusEnd:
            case self::selectForMngrByClsstatusCancel:
            {
                GGauth::isGrpmanager($GRPNO, $EXECUTOR, true);
                break;
            }
        }

        /* --------------- */
        /* add column to mng */
        /* --------------- */
        if(
            (isset($GRPNO)    && Common::isNotEmpty($GRPNO)) &&
            (isset($EXECUTOR) && Common::isNotEmpty($EXECUTOR))
        )
        {
            if(GGauth::isGrpmanager($GRPNO, $EXECUTOR, false))
                $select .= $selectForMng;
        }

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPkForInside              : { $from = "(select * from cls where grpno = '$GRPNO' and clsno = '$CLSNO') t"; break; }
            case self::selectByPkForAll                 : { $from = "(select * from cls where grpno = '$GRPNO' and clsno = '$CLSNO') t"; break; }
            case self::selectByPkForMng                 : { $from = "(select * from cls where grpno = '$GRPNO' and clsno = '$CLSNO') t"; break; }
            case self::selectByGrpnoForMng              : { $from = "(select * from cls where grpno = '$GRPNO') t"; break; }
            case self::selectByClsstatusForMng          : { $from = "(select * from cls where grpno = '$GRPNO' and clsstatus = '$CLSSTATUS') t"; break; }
            case self::selectFor1YearByGrpnoForAll      : { $from = "(select * from cls where grpno = '$GRPNO' and clsstartdt >= date_sub(now(), interval 1 year)) t"; break; }
            case self::selectForUserByClsstatusIng      : { $from = "(select * from cls where grpno in (select grpno from grp_member where userno = '$EXECUTOR' and grpmstatus = '$grpmstatusActive') and clsstatus = '$clsstatusIng') t"; break; }
            case self::selectForUserByClsstatusEnd      : { $from = "(select * from cls where grpno in (select grpno from grp_member where userno = '$EXECUTOR' and grpmstatus = '$grpmstatusActive') and clsstatus = '$clsstatusEnd') t"; break; }
            case self::selectForUserByClsstatusCancel   : { $from = "(select * from cls where grpno in (select grpno from grp_member where userno = '$EXECUTOR' and grpmstatus = '$grpmstatusActive') and clsstatus = '$clsstatusCancel') t"; break; }
            case self::selectForMngrByClsstatusEdit     : { $from = "(select * from cls where grpno = '$GRPNO' and clsstatus = '$clsstatusEdit') t"; break; }
            case self::selectForMngrByClsstatusIng      : { $from = "(select * from cls where grpno = '$GRPNO' and clsstatus = '$clsstatusIng') t"; break; }
            case self::selectForMngrByClssettleflgN     : { $from = "(select * from cls where grpno = '$GRPNO' and clsstatus = '$clsstatusEnd' and clssettleflg = '$clssettleflgYet') t"; break; }
            case self::selectForMngrByClsstatusEnd      : { $from = "(select * from cls where grpno = '$GRPNO' and clsstatus = '$clsstatusEnd') t"; break; }
            case self::selectForMngrByClsstatusCancel   : { $from = "(select * from cls where grpno = '$GRPNO' and clsstatus = '$clsstatusCancel') t"; break; }
            case self::selectAppliedFor1YearByUserno    :
            {
                $from =
                "
                    (
                        select
                            *
                        from
                            cls
                        where
                            grpno = '$GRPNO' and
                            clsno in
                            (
                                select
                                    distinct c.clsno
                                from
                                    cls c
                                    join clslineup2 cl
                                        on
                                            c.grpno = cl.grpno and
                                            c.clsno = cl.clsno
                                where
                                    c.grpno = '$GRPNO' and
                                    c.clsregdt >= date_sub(now(), interval 1 year) and
                                    cl.userno = '$USERNO'
                            )
                    ) t
                ";
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }

        /* --------------- */
        /* exe query */
        /* --------------- */
        $query =
        "
            select
                $select
            from
                $from
                left join grp       on t.grpno = grp.grpno
                left join user grpu on grp.grpmanager = grpu.userno
                left join user u1   on t.clsusernoreg = u1.userno
                left join user u2   on t.clsusernoadm = u2.userno
                left join user u3   on t.clsusernosub = u3.userno
                left join clzcancel clzc
                    on
                        t.grpno = clzc.grpno and
                        t.clsno = clzc.clsno
            order by
                t.clsstartdt desc
        ";
        $result = GGsql::select($query, $from, $options);
        return $result;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    // public function changeStoreStatus ($STORENO, $STORE_STATUS) { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    const insert = "insert";
    const update = "update";
    const updateClsstatusEditToIng   = "updateClsstatusEditToIng";   /* 매니저 : 일정상태를 진행중으로 변경 */
    const updateClsstatusIngToEnd = "updateClsstatusIngToEnd"; /* 매니저 : 일정상태를 종료로 변경 */
    const updateClsstatusToCancel = "updateClsstatusToCancel"; /* 매니저 : 일정상태를 취소로 변경 */
    const copyClsForMng = "copyClsForMng";
    const deleteByPkForMng = "deleteByPkForMng";
    protected function update($options, $option="")
    {
        /* vars */
        $result = Common::getReturn();

        /* get vars */
        extract($this->setBO());
        extract(ClsBO::getConsts());
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* =============== */
        /* validation (common) */
        /* =============== */
        switch($OPTION)
        {
            case self::insert:
            case self::update:
            {
                if(Common::isEmpty(trim($CLSTYPE))           ) { throw new GGexception("입력되지 않은 항목이 있습니다. (일정유형)"); }
                if(Common::isEmpty(trim($CLSTITLE))          ) { throw new GGexception("입력되지 않은 항목이 있습니다. (일정제목)"); }
                if(Common::isEmpty(trim($CLSCONTENT))        ) { throw new GGexception("입력되지 않은 항목이 있습니다. (일정상세)"); }
                if(Common::isEmpty(trim($CLSSTARTDT))        ) { throw new GGexception("입력되지 않은 항목이 있습니다. (일정시작일시)"); }
                if(Common::isEmpty(trim($CLSCLOSEDT))        ) { throw new GGexception("입력되지 않은 항목이 있습니다. (일정종료일시)"); }
                if(Common::isEmpty(trim($CLSGROUND))         ) { throw new GGexception("입력되지 않은 항목이 있습니다. (일정장소)"); }
                if(Common::isEmpty(trim($CLSGROUNDADDR))     ) { throw new GGexception("입력되지 않은 항목이 있습니다. (상세주소)"); }
                if(Common::isEmpty(trim($CLSBILLAPPLYPRICE)) ) { throw new GGexception("입력되지 않은 항목이 있습니다. (참가비)"); }
                if(Common::isEmpty(trim($CLSBILLAPPLYUNIT))  ) { throw new GGexception("입력되지 않은 항목이 있습니다. (참가비단위)"); }
                if(Common::isEmpty(trim($CLSAPPLYSTARTDT))   ) { throw new GGexception("입력되지 않은 항목이 있습니다. (모집시작일시)"); }
                if(Common::isEmpty(trim($CLSAPPLYCLOSEDT))   ) { throw new GGexception("입력되지 않은 항목이 있습니다. (모집종료일시)"); }
                break;
            }
        }

        /* =============== */
        /* validation (key) */
        /* =============== */
        switch($OPTION)
        {
            case self::insert: break;
            case self::update:
            case self::updateClsstatusEditToIng:
            {
                if(Common::isEmpty(trim($GRPNO))) { throw new GGexception(); }
                if(Common::isEmpty(trim($CLSNO))) { throw new GGexception(); }
                break;
            }
        }

        /* =============== */
        /* sql execution */
        /* =============== */
        switch($OPTION)
        {
            case self::insert:
            {
                /* validation */
                $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true); /* am i manager? */

                /* set var */
                $clsno = $idxBO->makeClsno();
                $CLSUSERNOADM = $CLSUSERNOADM === null ? "null" : "'$CLSUSERNOADM'";
                $CLSUSERNOSUB = $CLSUSERNOSUB === null ? "null" : "'$CLSUSERNOSUB'";

                /* query */
                $query =
                "
                    insert into cls
                    (
                          grpno
                        , clsno
                        , clsstatus
                        , clstype
                        , clstitle
                        , clscontent
                        , clsstartdt
                        , clsclosedt
                        , clsground
                        , clsgroundaddr
                        , clsbillapplyprice
                        , clsbillapplyunit
                        , clsapplystartdt
                        , clsapplyclosedt
                        , clsusernoreg
                        , clsusernoadm
                        , clsusernosub
                        , clsmodidt
                        , clsregdt
                    )
                    values
                    (
                          '$GRPNO'
                        , '$clsno'
                        , '$clsstatusEdit'
                        , '$CLSTYPE'
                        , '$CLSTITLE'
                        , '$CLSCONTENT'
                        , '$CLSSTARTDT'
                        , '$CLSCLOSEDT'
                        , '$CLSGROUND'
                        , '$CLSGROUNDADDR'
                        ,  $CLSBILLAPPLYPRICE
                        , '$CLSBILLAPPLYUNIT'
                        , '$CLSAPPLYSTARTDT'
                        , '$CLSAPPLYCLOSEDT'
                        , '$EXECUTOR'
                        ,  $CLSUSERNOADM
                        ,  $CLSUSERNOSUB
                        ,  now()
                        ,  now()
                    )
                ";
                GGsql::exeQuery($query);
                $result[GGF::DATA] = $clsno;
                break;
            }
            case self::update:
            {
                /* validation */
                $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true); /* am i manager? */
                $ggAuth->throwIfClsCancel($GRPNO, $CLSNO); /* is cancel status? */

                /* clstype is changed? */
                $clstypeOrigin = Common::getField($this->getByPk($GRPNO, $CLSNO), self::FIELD__CLSTYPE);
                if($clstypeOrigin != $CLSTYPE)
                    $clslineup2BO->deleteByClsnoForInside($GRPNO, $CLSNO);

                /* update cls info */
                $query   =
                "
                    update
                        cls
                    set
                          clsmodidt         =  now()
                        , clstype           = '$CLSTYPE'
                        , clstitle          = '$CLSTITLE'
                        , clscontent        = '$CLSCONTENT'
                        , clsstartdt        = '$CLSSTARTDT'
                        , clsclosedt        = '$CLSCLOSEDT'
                        , clsground         = '$CLSGROUND'
                        , clsgroundaddr     = '$CLSGROUNDADDR'
                        , clsbillapplyprice =  $CLSBILLAPPLYPRICE
                        , clsbillapplyunit  = '$CLSBILLAPPLYUNIT'
                        , clsapplystartdt   = '$CLSAPPLYSTARTDT'
                        , clsapplyclosedt   = '$CLSAPPLYCLOSEDT'
                    where
                        grpno = '$GRPNO' and
                        clsno = '$CLSNO'
                ";
                GGsql::exeQuery($query);
                break;
            }
            case self::updateClsstatusEditToIng:
            {
                /* validation */
                $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true); /* am i manager? */
                $ggAuth->isClsEdit($GRPNO, $CLSNO, true); /* is edit status? */

                /* update clsstatus */
                $query = "update cls set clsstatus = '$clsstatusIng', clsmodidt = now() where grpno = '$GRPNO' and clsno = '$CLSNO'";
                GGsql::exeQuery($query);
                break;
            }
            case self::updateClsstatusIngToEnd:
            {
                /* validation */
                $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true); /* am i manager? */
                $ggAuth->isClsIng($GRPNO, $CLSNO, true); /* is ing status? */

                /* update clsstatus */
                $query = "update cls set clsstatus = '$clsstatusEnd', clsmodidt = now() where grpno = '$GRPNO' and clsno = '$CLSNO'";
                GGsql::exeQuery($query);
                break;
            }
            case self::updateClsSettleInfoByPk:
            {
                /* validation */
                $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true); /* am i manager? */
                $ggAuth->isClsEnd($GRPNO, $CLSNO, true); /* is end status? */

                /* regist settle */
                $clssettleBO->insertForInside($GRPNO, $CLSNO, $EXECUTOR, $ARR);

                /* update clsstatus */
                $query = "update cls set clssettleflg = '$clssettleflgDone', clsmodidt = now() where grpno = '$GRPNO' and clsno = '$CLSNO'";
                GGsql::exeQuery($query);
                break;
            }
            case self::updateClsstatusToCancel:
            {
                /* validation */
                $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true); /* am i manager? */
                $ggAuth->throwIfClsCancel($GRPNO, $CLSNO); /* is cancel status? */

                /* update clsstatus */
                $query = "update cls set clsstatus = '$clsstatusCancel', clsmodidt = now() where grpno = '$GRPNO' and clsno = '$CLSNO'";
                GGsql::exeQuery($query);

                /* insert clzcancel */
                $clzcancelBO->insertForInside($GRPNO, $CLSNO, $CLSCANCELREASON);
                break;
            }
            case self::copyClsForMng:
            {
                /* validation */
                $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true); /* am i manager? */

                /* copy cls */
                $cls = $this->getByPk($GRPNO, $CLSNO);
                $clstype = Common::getField($cls, ClsBO::FIELD__CLSTYPE);
                $clsno = $idxBO->makeClsno();
                $query =
                "
                    insert into cls
                    (
                          grpno
                        , clsno
                        , clsstatus
                        , clstype
                        , clstitle
                        , clscontent
                        , clsstartdt
                        , clsclosedt
                        , clsground
                        , clsgroundaddr
                        , clsbillapplyprice
                        , clsbillapplyunit
                        , clsapplystartdt
                        , clsapplyclosedt
                        , clsmodidt
                        , clsregdt
                    )
                    select
                          '$GRPNO'
                        , '$clsno'
                        , '$clsstatusEdit'
                        ,  clstype
                        ,  clstitle
                        ,  clscontent
                        ,  clsstartdt
                        ,  clsclosedt
                        ,  clsground
                        ,  clsgroundaddr
                        ,  clsbillapplyprice
                        ,  clsbillapplyunit
                        ,  clsapplystartdt
                        ,  clsapplyclosedt
                        ,  now()
                        ,  now()
                    from
                        cls
                    where
                        grpno = '$GRPNO' and
                        clsno = '$CLSNO'
                ";
                GGsql::exeQuery($query);

                /* copy lineup */
                $clslineup2BO->copyFromClsnoForInside($GRPNO, $CLSNO, $clsno);
                break;
            }
            case self::deleteByPkForMng:
            {
                /* validation */
                $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true); /* am i manager? */

                /* delete cls */
                $query = "delete from cls where grpno = '$GRPNO' and clsno = '$CLSNO'";
                GGsql::exeQuery($query);

                /* delete lineup */
                $clslineup2BO->deleteByClsnoForInside($GRPNO, $CLSNO);
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }
        return $result;
    }

} /* end class */
?>
