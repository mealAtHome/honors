<?php

class Clslineup2BO extends _CommonBO
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
        GGnavi::getPaymentABO();
        GGnavi::getClsBO();
        $arr = array();
        $arr['ggAuth'] = GGauth::getInstance();
        $arr['userBO'] = UserBO::getInstance();
        $arr['paymentABO'] = PaymentABO::getInstance();
        $arr['clsBO'] = ClsBO::getInstance();
        return $arr;
    }

    /* ========================= */
    /* field */
    /*
    */
    /* ========================= */
    const FIELD__GRPNO              = "grpno";              /* (pk) char(30)        */
    const FIELD__CLSNO              = "clsno";              /* (pk) char(14)        */
    const FIELD__TEAMNAME           = "teamname";           /* (pk) char(10)        */
    const FIELD__TEAMNICK           = "teamnick";           /* (  ) char(20)        */
    const FIELD__ORDERNO            = "orderno";            /* (pk) int             */
    const FIELD__BATTINGFLG         = "battingflg";         /* (  ) enum('n', 'y')  */
    const FIELD__POSITION           = "position";           /* (  ) char(10)        */
    const FIELD__USERNO             = "userno";             /* (  ) char(30)        */
    const FIELD__USERNAME           = "username";           /* (  ) char(30)        */
    const FIELD__USERREGDT          = "userregdt";          /* (  ) datetime        */
    const FIELD__BILL               = "bill";               /* (  ) int             */
    const FIELD__ETC                = "etc";                /* (  ) varchar(100)    */

    /* ========================= */
    /* enum */
    /*
    */
    /* ========================= */
    // const CLSSTATUS__EDIT           = "edit";               /* 작성중 */
    // const CLSSTATUS__ING            = "ing";                /* 진행중 */
    // const CLSSTATUS__ENDCLS         = "endcls";             /* 일정완료 */
    // const CLSSTATUS__ENDSETTLE      = "endsettle";          /* 정산완료 */

    /* ========================= */
    /* select options */
    /* ========================= */
    static public function getConsts()
    {
        $arr = array();
        // $arr['clsstatusEdit']                   = self::CLSSTATUS__EDIT;        /* 일정상태 : 작성중 */
        // $arr['clsstatusIng']                    = self::CLSSTATUS__ING;         /* 일정상태 : 진행중 */
        // $arr['clsstatusEndcls']                 = self::CLSSTATUS__ENDCLS;      /* 일정상태 : 일정완료 */
        // $arr['clsstatusEndsettle']              = self::CLSSTATUS__ENDSETTLE;   /* 일정상태 : 정산완료 */
        return $arr;
    }


    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function getByPk($GRPNO, $CLSNO, $TEAMNAME, $ORDERNO) { return Common::getDataOne($this->selectByPkForInside($GRPNO, $CLSNO, $TEAMNAME, $ORDERNO)); }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByClsnoForInside              ($GRPNO, $CLSNO)                        { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectByPkForInside                 ($GRPNO, $CLSNO, $TEAMNAME, $ORDERNO)   { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectDuplicateApplyForInside       ($GRPNO, $CLSNO, $USERNO)               { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /* ========================= */
    const selectByClsno = "selectByClsno";
    const selectByClsnoForInside = "selectByClsnoForInside";
    const selectByPkForInside = "selectByPkForInside";
    const selectDuplicateApplyForInside = "selectDuplicateApplyForInside";
    const selectByClsnoForSettleForMng = "selectByClsnoForSettleForMng"; /* 중요 : 일정정산용 */
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        extract($this->setBO());
        extract(Clslineup2BO::getConsts());
        extract($options);

        /* orderride option */
        if($option != "")
            $OPTION = $option;

        /* --------------- */
        /* sql body */
        /* --------------- */
        $query = "";
        $from = "";
        $select2 = "";
        $select =
        "
              t.grpno
            , t.clsno
            , t.teamname
            , t.teamnick
            , t.orderno
            , t.battingflg
            , t.position
            , t.userno
            , t.username
            , t.bill
            , t.etc
            , t.userregdt
            , cls.clsstatus
            , u.name as applyername
        ";
        $groupby = "";
        $orderby =
        "
              t.grpno
            , t.clsno
            , t.teamname
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
            case self::selectByClsno                        : { $from = "(select * from clslineup2 where grpno = '$GRPNO' and clsno = '$CLSNO') t"; break; }
            case self::selectByClsnoForInside               : { $from = "(select * from clslineup2 where grpno = '$GRPNO' and clsno = '$CLSNO') t"; break; }
            case self::selectByPkForInside                  : { $from = "(select * from clslineup2 where grpno = '$GRPNO' and clsno = '$CLSNO' and teamname = '$TEAMNAME' and orderno = $ORDERNO) t"; break; }
            case self::selectDuplicateApplyForInside        : { $from = "(select * from clslineup2 where grpno = '$GRPNO' and clsno = '$CLSNO' and userno = '$USERNO') t"; break; }
            case self::selectByClsnoForSettleForMng :
            {
                $select =
                "
                      t.grpno
                    , t.clsno
                    , t.userno
                    , u.name username
                    , count(*) as cnt
                    , sum(t.bill) as bill
                    , grpm.point as memberpoint
                ";
                $from = "(select * from clslineup2 where grpno = '$GRPNO' and clsno = '$CLSNO' and (userno is not null and userno <> '')) t";
                $groupby =
                "
                    group by
                          t.grpno
                        , t.clsno
                        , t.userno
                        , u.name
                ";
                $orderby = "u.name";
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
                $select2
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
            $groupby
            order by
                $orderby

        ";
        $result = GGsql::select($query, $from, $options);
        return $result;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    public function deleteByClsnoForInside($GRPNO, $CLSNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function copyFromClsnoForInside($GRPNO, $CLSNO, $CLSNONEW) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function updateUsernoToTargetForInside($GRPNO, $USERNO, $TARGET) { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    const deleteByClsnoForInside = "deleteByClsnoForInside";
    const updateFromPage = "updateFromPage";
    const updateApplyRegist = "updateApplyRegist";
    const updateApplyRegistStead = "updateApplyRegistStead";
    const updateApplyCancel = "updateApplyCancel";
    const copyFromClsnoForInside = "copyFromClsnoForInside";
    const updateUsernoToTargetForInside = "updateUsernoToTargetForInside";
    protected function update($options, $option="")
    {
        /* return */
        $clsno = null;

        /* get vars */
        extract($this->setBO());
        extract(Clslineup2BO::getConsts());
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* =============== */
        /* auth */
        /* =============== */
        switch($OPTION)
        {
            case self::updateFromPage: { /* TODO : is manager of grp? */ break; }
        }

        /* =============== */
        /* validation (common) */
        /* =============== */
        switch($OPTION)
        {
            case self::updateFromPage:
            {
                break;
            }
        }

        /* =============== */
        /* validation (key) */
        /* =============== */
        switch($OPTION)
        {
            case self::updateFromPage:
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
            case self::deleteByClsnoForInside:
            {
                $query = "delete from clslineup2 where grpno = '$GRPNO' and clsno = '$CLSNO'";
                GGsql::exeQuery($query);
                break;
            }
            case self::updateFromPage:
            {
                /* check is manager */
                $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true); /* is grp manager, no exception */

                /* validation */
                $ggAuth->throwIfClsCancel($GRPNO, $CLSNO); /* is cancel status? */

                /* get cls info */
                $cls = $clsBO->getByPk($GRPNO, $CLSNO);
                if($cls == null)
                    throw new GGexception("존재하지 않는 일정입니다. 다시 시도하여 주세요.");

                /* if clsstatus is edit, delete first */
                $clsstatus = Common::getField($cls, ClsBO::FIELD__CLSSTATUS);
                if($clsstatus == ClsBO::CLSSTATUS__EDIT)
                    $this->deleteByClsnoForInside($GRPNO, $CLSNO);

                /* insert */
                $arr = json_decode($ARR, true);
                foreach($arr as $dat)
                {
                    $DATATYPE   = $dat['DATATYPE'];
                    $TEAMNAME   = $dat['TEAMNAME'];
                    $TEAMNICK   = $dat['TEAMNICK'];
                    $ORDERNO    = $dat['ORDERNO'];
                    $BATTINGFLG = $dat['BATTINGFLG'];
                    $POSITION   = $dat['POSITION'];
                    $USERNO     = isset($dat['USERNO'])   ? $dat['USERNO'] : null;
                    $USERNAME   = isset($dat['USERNAME']) ? $dat['USERNAME'] : null;
                    $BILL       = $dat['BILL'];

                    /* if datatype old, update bill only */
                    if($DATATYPE == "old")
                    {
                        $query =
                        "
                            update
                                clslineup2
                            set
                                bill = $BILL
                            where
                                grpno = '$GRPNO' and
                                clsno = '$CLSNO' and
                                teamname = '$TEAMNAME' and
                                orderno = $ORDERNO
                        ";
                        GGsql::exeQuery($query);
                        continue;
                    }

                    /* update */
                    $query =
                    "
                        insert into clslineup2
                        (
                              grpno
                            , clsno
                            , teamname
                            , teamnick
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
                            , '$TEAMNAME'
                            , '$TEAMNICK'
                            ,  $ORDERNO
                            , '$BATTINGFLG'
                            , '$POSITION'
                            ,  null
                            ,  null
                            ,  $BILL
                        )
                    ";
                    GGsql::exeQuery($query);
                }
                break;
            }
            case self::updateApplyRegist:
            {
                /* validation */
                $ggAuth->throwIfClsCancel($GRPNO, $CLSNO); /* is cancel status? */

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
                $clslineup2 = Common::getDataOne($this->selectByPkForInside($GRPNO, $CLSNO, $TEAMNAME, $ORDERNO));
                $userno = Common::getField($clslineup2, self::FIELD__USERNO);
                if(Common::isNotEmpty($userno))
                    throw new GGexception("이미 신청된 일정입니다.");

                /* get username if not exists */
                $username = $userBO->getUsername($EXECUTOR);
                $USERNAME = Common::isEmpty($USERNAME) ? $username : $USERNAME;

                /* update */
                $query =
                "
                    update
                        clslineup2
                    set
                          userno = '$EXECUTOR'
                        , username = '$USERNAME'
                        , userregdt = now()
                        , etc = '$ETC'
                    where
                        grpno = '$GRPNO' and
                        clsno = '$CLSNO' and
                        teamname = '$TEAMNAME' and
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
                $clslineup2 = Common::getDataOne($this->selectByPkForInside($GRPNO, $CLSNO, $TEAMNAME, $ORDERNO));
                $userno = Common::getField($clslineup2, self::FIELD__USERNO);
                if(Common::isNotEmpty($userno))
                    throw new GGexception("이미 신청된 일정입니다.");

                /* get username from userno */
                $USERNAME = $userBO->getUsername($USERNO);

                /* update */
                $query =
                "
                    update
                        clslineup2
                    set
                          userno = '$USERNO'
                        , username = '$USERNAME'
                        , userregdt = now()
                        , etc = '$ETC'
                    where
                        grpno = '$GRPNO' and
                        clsno = '$CLSNO' and
                        teamname = '$TEAMNAME' and
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
                $clslineup2 = $this->getByPk($GRPNO, $CLSNO, $TEAMNAME, $ORDERNO);
                $userno = Common::getField($clslineup2, self::FIELD__USERNO);
                if(Common::isEmpty($userno))
                    throw new GGexception("이미 취소되어 있습니다.");

                /* validation */
                $isGrpmanager = $ggAuth->isGrpmanager($GRPNO, $EXECUTOR); /* is grpmanager */
                if($isGrpmanager == false)
                {
                    /* check userno */
                    if(Common::getField($clslineup2, self::FIELD__USERNO) != $EXECUTOR)
                        throw new GGexception("본인이 신청한 일정만 취소할 수 있습니다.");
                }
                else
                {
                    /* check userregdt passed 1 day */
                    // if(Common::getField($clslineup2, self::FIELD__USERNO) != $EXECUTOR)
                    // {
                    //     $userregdt = Common::getField($clslineup2, self::FIELD__USERREGDT);
                    //     $userregdtObj = GGF::getDateFromString($userregdt);
                    //     $userregdtObj1DayAfter = GGdate::addTime($userregdtObj, "1 day");
                    //     $nowObj = GGdate::now();
                    //     if($nowObj < $userregdtObj1DayAfter)
                    //         throw new GGexception("멤버의 취소는, 일정기명 후 24시간이 지나야 취소할 수 있습니다.");
                    // }
                }

                /* is in cls apply period */
                /* 관리자의 경우, 예외 처리 없음 */
                if($isGrpmanager == false)
                    $ggAuth->isClsInApplyDt($GRPNO, $CLSNO);

                /* update */
                $query =
                "
                    update
                        clslineup2
                    set
                          userno = ''
                        , username = ''
                        , userregdt = null
                    where
                        grpno = '$GRPNO' and
                        clsno = '$CLSNO' and
                        teamname = '$TEAMNAME' and
                        orderno = $ORDERNO
                ";
                GGsql::exeQuery($query);
                break;
            }
            case self::copyFromClsnoForInside:
            {
                $query =
                "
                    insert into clslineup2
                    (
                          grpno
                        , clsno
                        , teamname
                        , teamnick
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
                        ,  teamname
                        ,  teamnick
                        ,  orderno
                        ,  battingflg
                        ,  position
                        ,  null
                        ,  null
                        ,  null
                        ,  bill
                    from
                        clslineup2
                    where
                        grpno = '$GRPNO' and
                        clsno = '$CLSNO'
                ";
                GGsql::exeQuery($query);
                break;
            }
            case self::updateUsernoToTargetForInside:
            {
                $query = "update clslineup2 set userno = '$TARGET' where grpno = '$GRPNO' and userno = '$USERNO'";
                GGsql::exeQuery($query);
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }
        return $clsno;
    }

} /* end class */
?>
