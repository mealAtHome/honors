<?php

class ClslineupbBO extends _CommonBO
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
        GGnavi::getUserBO();
        GGnavi::getClslineupaBO();
        GGnavi::getGrpMemberBO();
        $arr = array();
        $arr['ggAuth'] = GGauth::getInstance();
        $arr['userBO'] = UserBO::getInstance();
        $arr['grpMemberBO'] = GrpMemberBO::getInstance();
        return $arr;
    }

    /* ========================= */
    /* fields */
    /*
    */
    /* ========================= */
    const FIELD__GRPNO              = "grpno";              /* (pk) char(30)        */
    const FIELD__CLSNO              = "clsno";              /* (pk) char(14)        */
    const FIELD__LINEUPIDX          = "lineupidx";          /* (pk) char(10)        */
    const FIELD__ORDERNO            = "orderno";            /* (pk) int             */
    const FIELD__BATTINGFLG         = "battingflg";         /* (  ) enum('n', 'y')  */
    const FIELD__POSITION           = "position";           /* (  ) char(10)        */
    const FIELD__USERNO             = "userno";             /* (  ) char(30)        */
    const FIELD__USERNAME           = "username";           /* (  ) char(30)        */
    const FIELD__USERREGDT          = "userregdt";          /* (  ) datetime        */
    const FIELD__BILL               = "bill";               /* (  ) int             */
    const FIELD__PREPAIDFLG         = "prepaidflg";         /* (  ) enum('n', 'y')  */
    const FIELD__ETC                = "etc";                /* (  ) varchar(100)    */

    /* ========================= */
    /* enum */
    /*
    */
    /* ========================= */
    // const CLSSTATUS__EDIT   = "edit";          /* 작성중 */
    // const CLSSTATUS__ING    = "ing";           /* 진행중 */
    // const CLSSTATUS__END    = "end";           /* 일정완료 */

    static public function getConsts()
    {
        $arr = array();
        // $arr['clsstatusEdit']    = self::CLSSTATUS__EDIT;    /* 일정상태 : 작성중 */
        // $arr['clsstatusIng']     = self::CLSSTATUS__ING;     /* 일정상태 : 진행중 */
        // $arr['clsstatusEnd']     = self::CLSSTATUS__END;     /* 일정상태 : 일정완료 */
        return $arr;
    }


    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function getByPk($GRPNO, $CLSNO, $LINEUPIDX, $ORDERNO) { return Common::getDataOne($this->selectByPkForInside($GRPNO, $CLSNO, $LINEUPIDX, $ORDERNO)); }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByClsno                       ($GRPNO, $CLSNO)                         { throw new GGexceptionRule(); }
    public function selectByClsnoForInside              ($GRPNO, $CLSNO)                         { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectByLineupidxForInside          ($GRPNO, $CLSNO, $LINEUPIDX)             { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectByPkForInside                 ($GRPNO, $CLSNO, $LINEUPIDX, $ORDERNO)   { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectDuplicateApplyForInside       ($GRPNO, $CLSNO, $USERNO)                { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectByClsnoForSettleForMng        ($GRPNO, $CLSNO)                         { throw new GGexceptionRule(); }

    /* ========================= */
    /* select */
    /*
    */
    /* ========================= */
    const selectByClsno = "selectByClsno";
    const selectByClsnoForInside = "selectByClsnoForInside";
    const selectByLineupidxForInside = "selectByLineupidxForInside";
    const selectByPkForInside = "selectByPkForInside";
    const selectDuplicateApplyForInside = "selectDuplicateApplyForInside";
    const selectByClsnoForSettleForMng = "selectByClsnoForSettleForMng"; /* 중요 : 일정정산용 */
    protected function select($options, $option="")
    {
        /* vars */
        $ggAuth = GGauth::getInstance();
        extract(self::getConsts());
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* --------------- */
        /* sql body */
        /* --------------- */
        $query = "";
        $from = "";
        $select =
        "
              t.grpno
            , t.clsno
            , t.lineupidx
            , t.orderno
            , t.battingflg
            , t.position
            , t.userno
            , t.username
            , t.bill
            , t.prepaidflg
            , t.etc
            , t.userregdt
            , clslua.lineupname
            , cls.clsstatus
            , u.name as applyername
            , grpm.point as memberpoint
        ";
        $groupby = "";
        $orderby =
        "
              t.grpno
            , t.clsno
            , t.lineupidx
            , t.orderno
        ";

        /* --------------- */
        /* validation */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByClsno : { break; }
        }

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByClsno                        : { $from = "(select * from clslineupb where grpno = '$GRPNO' and clsno = '$CLSNO') t"; break; }
            case self::selectByClsnoForInside               : { $from = "(select * from clslineupb where grpno = '$GRPNO' and clsno = '$CLSNO') t"; break; }
            case self::selectByLineupidxForInside           : { $from = "(select * from clslineupb where grpno = '$GRPNO' and clsno = '$CLSNO' and lineupidx = $LINEUPIDX) t"; break; }
            case self::selectByPkForInside                  : { $from = "(select * from clslineupb where grpno = '$GRPNO' and clsno = '$CLSNO' and lineupidx = $LINEUPIDX and orderno = $ORDERNO) t"; break; }
            case self::selectDuplicateApplyForInside        : { $from = "(select * from clslineupb where grpno = '$GRPNO' and clsno = '$CLSNO' and userno = '$USERNO') t"; break; }
            case self::selectByClsnoForSettleForMng         : { $from = "(select * from clslineupb where grpno = '$GRPNO' and clsno = '$CLSNO' and (userno is not null and userno <> '')) t"; break; }
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
                left join cls
                    on
                        cls.grpno = t.grpno and
                        cls.clsno = t.clsno
                left join user u
                    on
                        u.userno = t.userno
                left join grp_member grpm
                    on
                        grpm.grpno = t.grpno and
                        grpm.userno = t.userno
                left join clslineupa clslua
                    on
                        clslua.grpno = t.grpno and
                        clslua.clsno = t.clsno and
                        clslua.lineupidx = t.lineupidx
            $groupby
            order by
                $orderby

        ";
        $rslt = GGsql::select($query, $from, $options, $OPTION);
        return $rslt;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    public function insertOneForInside($GRPNO, $CLSNO, $LINEUPIDX, $ORDERNO, $BATTINGFLG, $POSITION, $USERNO, $USERNAME, $BILL) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteByPkForInside($GRPNO, $CLSNO, $LINEUPIDX, $ORDERNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteByClsnoForInside($GRPNO, $CLSNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteByLineupidxWithSubForInside($GRPNO, $CLSNO, $LINEUPIDX) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function copyFromClsnoWithSubForInside($GRPNO, $CLSNO, $CLSNONEW) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function updateUsernoToTargetForInside($GRPNO, $USERNO, $TARGET) { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    const insertOneForInside = "insertOneForInside";
    const deleteByPkForInside = "deleteByPkForInside";
    const deleteByClsnoForInside = "deleteByClsnoForInside";
    const deleteByLineupidxWithSubForInside = "deleteByLineupidxWithSubForInside";
    const updateApplyRegist = "updateApplyRegist";
    const updateApplyRegistStead = "updateApplyRegistStead";
    const updateApplyCancel = "updateApplyCancel";
    const updatePrepaidflgToYForFin = "updatePrepaidflgToYForFin";                      /* [fin]  */
    const updatePrepaidflgToNForFin = "updatePrepaidflgToNForFin";                      /* [fin]  */
    const copyFromClsnoWithSubForInside = "copyFromClsnoWithSubForInside";
    const updateUsernoToTargetForInside = "updateUsernoToTargetForInside";
    protected function update($options, $option="")
    {
        /* vars */
        $rslt = Common::getReturn();
        extract($this->setBO());
        extract(self::getConsts());
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* =============== */
        /* process */
        /* =============== */
        switch($OPTION)
        {
            case self::insertOneForInside:
            {
                /* validation */
                if(Common::isEmpty(trim($GRPNO))) { throw new Exception(); }
                if(Common::isEmpty(trim($CLSNO))) { throw new Exception(); }
                if(Common::isEmpty(trim($LINEUPIDX))) { throw new Exception(); }
                if(Common::isEmpty(trim($ORDERNO))) { throw new Exception(); }
                if(Common::isEmpty(trim($BATTINGFLG))) { throw new Exception(); }
                if(Common::isEmpty(trim($POSITION))) { throw new Exception(); }
                if(Common::isEmpty(trim($BILL))) { throw new Exception(); }

                /* set value before insert */
                $USERNO   = Common::isEmpty($USERNO)   ? "null" : "'$USERNO'";
                $USERNAME = Common::isEmpty($USERNAME) ? "null" : "'$USERNAME'";

                /* process */
                $query =
                "
                    insert into clslineupb
                    (
                          grpno
                        , clsno
                        , lineupidx
                        , orderno
                        , battingflg
                        , position
                        , userno
                        , username
                        , bill
                    )
                    values
                    (
                          '$GRPNO'
                        , '$CLSNO'
                        ,  $LINEUPIDX
                        ,  $ORDERNO
                        , '$BATTINGFLG'
                        , '$POSITION'
                        ,  $USERNO
                        ,  $USERNAME
                        ,  $BILL
                    )
                ";
                GGsql::exeQuery($query);
                break;
            }
            case self::deleteByPkForInside:
            {
                /* validation */
                if(Common::isEmpty($GRPNO)) throw new Exception();
                if(Common::isEmpty($CLSNO)) throw new Exception();
                if(Common::isEmpty($LINEUPIDX)) throw new Exception();
                if(Common::isEmpty($ORDERNO)) throw new Exception();

                /* process */
                $query = "delete from clslineupb where grpno = '$GRPNO' and clsno = '$CLSNO' and lineupidx = $LINEUPIDX and orderno = $ORDERNO";
                GGsql::exeQuery($query);
                break;
            }
            case self::deleteByClsnoForInside:
            {
                /* select && delete */
                $clslineupbs = Common::getData($this->selectByClsnoForInside($GRPNO, $CLSNO));
                foreach($clslineupbs as $clslineupb)
                {
                    $lineupidx = Common::getField($clslineupb, self::FIELD__LINEUPIDX);
                    $orderno = Common::getField($clslineupb, self::FIELD__ORDERNO);
                    $this->deleteByPkForInside($GRPNO, $CLSNO, $lineupidx, $orderno);
                }
                break;
            }
            case self::deleteByLineupidxWithSubForInside:
            {
                /* *** sub table not exists */
                /* select && delete */
                $clslineupbs = Common::getData($this->selectByLineupidxForInside($GRPNO, $CLSNO, $LINEUPIDX));
                foreach($clslineupbs as $clslineupb)
                {
                    $orderno = Common::getField($clslineupb, self::FIELD__ORDERNO);
                    $this->deleteByPkForInside($GRPNO, $CLSNO, $LINEUPIDX, $orderno);
                }
                break;
            }
            case self::updateApplyRegist:
            {
                /* check cls info */
                /* is cls ing */
                $ggAuth->isClsIng($GRPNO, $CLSNO);

                /* is in cls apply period */
                /* 관리자의 경우, 예외 처리 없음 */
                $isGrpmanager = $ggAuth->isGrpmanager($GRPNO, $EXECUTOR); /* is grp manager */
                if($isGrpmanager != true) {
                    $ggAuth->isClsInApplyDt($GRPNO, $CLSNO);
                }

                /* 해당 포지션에 이미 다른 유저가 기입하였는지 확인 */
                $clslineupb = Common::getDataOne($this->selectByPkForInside($GRPNO, $CLSNO, $LINEUPIDX, $ORDERNO));
                $userno = Common::getField($clslineupb, self::FIELD__USERNO);
                if(Common::isNotEmpty($userno))
                    throw new GGexception("이미 신청된 일정입니다.");

                /* get username if not exists */
                $username = $userBO->getUsername($EXECUTOR);
                $USERNAME = Common::isEmpty($USERNAME) ? $username : $USERNAME;

                /* update */
                $query =
                "
                    update
                        clslineupb
                    set
                          userno = '$EXECUTOR'
                        , username = '$USERNAME'
                        , userregdt = now()
                        , etc = '$ETC'
                    where
                        grpno = '$GRPNO' and
                        clsno = '$CLSNO' and
                        lineupidx = $LINEUPIDX and
                        orderno = $ORDERNO
                ";
                GGsql::exeQuery($query);
                break;
            }
            case self::updateApplyRegistStead:
            {
                /* validation */
                $ggAuth->isClsIng($GRPNO, $CLSNO); /* is cls ing */

                /* is in cls apply period */
                /* 관리자의 경우, 예외 처리 없음 */
                $isGrpmanager = $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true); /* is grp manager */
                if($isGrpmanager != true)
                    $ggAuth->isClsInApplyDt($GRPNO, $CLSNO);

                /* 해당 포지션에 이미 다른 유저가 기입하였는지 확인 */
                $clslineupb = Common::getDataOne($this->selectByPkForInside($GRPNO, $CLSNO, $LINEUPIDX, $ORDERNO));
                $userno = Common::getField($clslineupb, self::FIELD__USERNO);
                if(Common::isNotEmpty($userno))
                    throw new GGexception("이미 신청된 일정입니다.");

                /* get username from userno */
                $USERNAME = $userBO->getUsername($USERNO);

                /* update */
                $query =
                "
                    update
                        clslineupb
                    set
                          userno = '$USERNO'
                        , username = '$USERNAME'
                        , userregdt = now()
                        , etc = '$ETC'
                    where
                        grpno = '$GRPNO' and
                        clsno = '$CLSNO' and
                        lineupidx = $LINEUPIDX and
                        orderno = $ORDERNO
                ";
                GGsql::exeQuery($query);
                break;
            }
            case self::updateApplyCancel:
            {
                /* check cls info */
                $ggAuth->isClsIng($GRPNO, $CLSNO);

                /* check already canceled */
                $clslineupb = $this->getByPk($GRPNO, $CLSNO, $LINEUPIDX, $ORDERNO);
                $userno = Common::getField($clslineupb, self::FIELD__USERNO);
                if(Common::isEmpty($userno))
                    throw new GGexception("이미 취소되어 있습니다.");

                /* validation */
                $isGrpmanager = $ggAuth->isGrpmanager($GRPNO, $EXECUTOR); /* is grpmanager */
                if($isGrpmanager == false)
                {
                    /* check userno */
                    if(Common::getField($clslineupb, self::FIELD__USERNO) != $EXECUTOR)
                        throw new GGexception("본인이 신청한 일정만 취소할 수 있습니다.");
                }

                /* 이미 선결제가 완료되었다면, 해당 멤버에게 사전정산금 지급 */
                if(Common::getField($clslineupb, self::FIELD__PREPAIDFLG) == GGF::Y)
                {
                    $targetUserno = Common::getField($clslineupb, self::FIELD__USERNO);
                    $bill = Common::getField($clslineupb, self::FIELD__BILL);
                    $grpMemberBO->updatePointForInside($GRPNO, $targetUserno, $bill, "참가취소로 인한 지급", $CLSNO);
                }

                /* is in cls apply period */
                /* 관리자의 경우, 예외 처리 없음 */
                if($isGrpmanager == false)
                    $ggAuth->isClsInApplyDt($GRPNO, $CLSNO);

                /* update */
                $query =
                "
                    update
                        clslineupb
                    set
                          userno = null
                        , username = null
                        , userregdt = null
                        , etc = null
                        , prepaidflg = 'n'
                    where
                        grpno = '$GRPNO' and
                        clsno = '$CLSNO' and
                        lineupidx = $LINEUPIDX and
                        orderno = $ORDERNO
                ";
                GGsql::exeQuery($query);
                break;
            }
            case self::updatePrepaidflgToYForFin:
            {
                /* validation : has finauth */
                $ggAuth->hasGrpmfinauth($GRPNO, $EXECUTOR, true);

                /* validation : check settle is edit */
                $ggAuth->isClssetleflgEdit($GRPNO, $CLSNO, true);

                /* process */
                $query =
                "
                    update
                        clslineupb
                    set
                        prepaidflg = 'y'
                    where
                        grpno = '$GRPNO' and
                        clsno = '$CLSNO' and
                        lineupidx = $LINEUPIDX and
                        orderno = $ORDERNO
                ";
                GGsql::exeQuery($query);
                break;
            }
            case self::updatePrepaidflgToNForFin:
            {
                /* validation : has finauth */
                $ggAuth->hasGrpmfinauth($GRPNO, $EXECUTOR, true);

                /* validation : check settle is edit */
                $ggAuth->isClssetleflgEdit($GRPNO, $CLSNO, true);

                /* process */
                $query =
                "
                    update
                        clslineupb
                    set
                        prepaidflg = 'n'
                    where
                        grpno = '$GRPNO' and
                        clsno = '$CLSNO' and
                        lineupidx = $LINEUPIDX and
                        orderno = $ORDERNO
                ";
                GGsql::exeQuery($query);
                break;
            }
            case self::copyFromClsnoWithSubForInside:
            {
                /* validation */
                if(Common::isEmpty($GRPNO)) throw new Exception();
                if(Common::isEmpty($CLSNO)) throw new Exception();
                if(Common::isEmpty($CLSNONEW)) throw new Exception();

                /* *** has no sub */

                /* process */
                $query =
                "
                    insert into clslineupb
                    (
                          grpno
                        , clsno
                        , lineupidx
                        , orderno
                        , battingflg
                        , position
                        , userno
                        , username
                        , userregdt
                        , bill
                    )
                    select
                           grpno
                        , '$CLSNONEW'
                        ,  lineupidx
                        ,  orderno
                        ,  battingflg
                        ,  position
                        ,  null
                        ,  null
                        ,  null
                        ,  bill
                    from
                        clslineupb
                    where
                        grpno = '$GRPNO' and
                        clsno = '$CLSNO'
                ";
                GGsql::exeQuery($query);
                break;
            }
            case self::updateUsernoToTargetForInside:
            {
                $query = "update clslineupb set userno = '$TARGET' where grpno = '$GRPNO' and userno = '$USERNO'";
                GGsql::exeQuery($query);
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }
        return $rslt;
    }

} /* end class */
?>
