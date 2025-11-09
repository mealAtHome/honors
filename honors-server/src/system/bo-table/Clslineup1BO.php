<?php

class Clslineup1BO extends _CommonBO
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
    function __construct() {
        GGnavi::getUserBO();
        GGnavi::getPaymentABO();
        GGnavi::getClsBO();
    }
    function setBO()
    {
        $this->ggAuth = GGauth::getInstance();
        $this->userBO = UserBO::getInstance();
        $this->paymentABO = PaymentABO::getInstance();
        $this->clsBO = ClsBO::getInstance();
    }
    private $ggAuth;
    private $userBO;
    private $paymentABO;
    private $clsBO;

    /* ========================= */
    /* field */
    /*
    */
    /* ========================= */
    const FIELD__GRPNO              = "grpno";              /* (pk) char(30)        */
    const FIELD__CLSNO              = "clsno";              /* (pk) char(14)        */
    const FIELD__ORDERNO            = "orderno";            /* (pk) int             */
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
    public function getByPk($GRPNO, $CLSNO, $ORDERNO) { return Common::getDataOne($this->selectByPkForInside($GRPNO, $CLSNO, $ORDERNO)); }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByClsnoForInside              ($GRPNO, $CLSNO)                        { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectByPkForInside                 ($GRPNO, $CLSNO, $ORDERNO)              { return $this->select(get_defined_vars(), __FUNCTION__); }
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
        $this->setBO();
        extract(Clslineup1BO::getConsts());
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
            null as head
            , t.grpno
            , t.clsno
            , t.orderno
            , t.position
            , t.userno
            , t.username
            , t.userregdt
            , t.bill
            , t.etc
            , cls.clsstatus
            , u.name as applyername
        ";
        $groupby = "";
        $orderby =
        "
              t.grpno
            , t.clsno
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
            case self::selectByClsno                        : { $from = "(select * from clslineup1 where grpno = '$GRPNO' and clsno = '$CLSNO') t"; break; }
            case self::selectByClsnoForInside               : { $from = "(select * from clslineup1 where grpno = '$GRPNO' and clsno = '$CLSNO') t"; break; }
            case self::selectByPkForInside                  : { $from = "(select * from clslineup1 where grpno = '$GRPNO' and clsno = '$CLSNO' and orderno = $ORDERNO) t"; break; }
            case self::selectDuplicateApplyForInside        : { $from = "(select * from clslineup1 where grpno = '$GRPNO' and clsno = '$CLSNO' and userno = '$USERNO') t"; break; }
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
                $from = "(select * from clslineup1 where grpno = '$GRPNO' and clsno = '$CLSNO' and (userno is not null and userno <> '')) t";
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
        $this->setBO();
        extract(Clslineup1BO::getConsts());
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
                $query = "delete from clslineup1 where grpno = '$GRPNO' and clsno = '$CLSNO'";
                GGsql::exeQuery($query);
                break;
            }
            case self::updateFromPage:
            {
                /* delete first */
                $this->deleteByClsnoForInside($GRPNO, $CLSNO);

                /* get cls info */
                $cls = $this->clsBO->getByPk($GRPNO, $CLSNO);
                if($cls == null)
                    throw new GGexception("존재하지 않는 일정입니다. 다시 시도하여 주세요.");
                $clsstatus = Common::getField($cls, ClsBO::FIELD__CLSSTATUS);

                /* insert */
                $arr = json_decode($ARR, true);
                foreach($arr as $dat)
                {
                    $ORDERNO    = $dat['ORDERNO'];
                    $POSITION   = $dat['POSITION'];
                    $USERNO     = $dat['USERNO'];
                    $USERNAME   = $dat['USERNAME'];
                    $BILL       = $dat['BILL'];
                    $RECOVERED  = $dat['RECOVERED'];

                    /* if userno empty && username is not empty, userno = executor */
                    if(Common::isEmpty($USERNO) && !Common::isEmpty($USERNAME))
                        $USERNO = $EXECUTOR;

                    /* 중단 : 유저의 계좌 직접관리로 인한 중단 */
                    /* user point minus, if clsstatus is ing */
                    // if($clsstatus == ClsBO::CLSSTATUS__ING && $RECOVERED == "n")
                    //     $this->paymentABO->payForCls($USERNO, $GRPNO, $CLSNO, $ORDERNO, $BILL);

                    /* update */
                    $userno   = Common::isEmpty($USERNO)   ? "null" : "'$USERNO'";
                    $username = Common::isEmpty($USERNAME) ? "null" : "'$USERNAME'";
                    $query =
                    "
                        insert into clslineup1
                        (
                              grpno
                            , clsno
                            , orderno
                            , position
                            , userno
                            , username
                            , bill
                        )
                        values
                        (
                              '$GRPNO'
                            , '$CLSNO'
                            ,  $ORDERNO
                            , '$POSITION'
                            ,  $userno
                            ,  $username
                            ,  $BILL
                        )
                    ";
                    GGsql::exeQuery($query);
                }
                break;
            }
            case self::updateApplyRegist:
            {
                /* is grp manager */
                $isGrpmanager = $this->ggAuth->isGrpmanager($GRPNO, $EXECUTOR);

                /* check cls info */
                /* is cls ing */
                $this->ggAuth->isClsIng($GRPNO, $CLSNO);

                /* is in cls apply period */
                /* 관리자의 경우, 예외 처리 없음 */
                if($isGrpmanager != true) {
                    $this->ggAuth->isClsInApplyDt($GRPNO, $CLSNO);
                }

                /* 해당 포지션에 이미 다른 유저가 기입하였는지 확인 */
                $clslineup1 = Common::getDataOne($this->selectByPkForInside($GRPNO, $CLSNO, $ORDERNO));
                $userno = Common::getField($clslineup1, self::FIELD__USERNO);
                if(Common::isNotEmpty($userno))
                    throw new GGexception("이미 신청된 일정입니다.");

                /* 유저가 이미 다른 포지션에 기명하였는지 확인 */
                /* 매니저는 중복등록 가능 */
                // if($this->ggAuth->isGrpmanager($GRPNO, $EXECUTOR) == false)
                // {
                //     if(Common::isNotEmpty($clslineup1Dup))
                //         throw new GGexception("이미 다른 포지션에 신청하였습니다. 중복등록 시 매니저를 통해 처리 부탁드립니다.");
                // }

                /* ----- */
                /* check username */
                /* 중복등록의 경우, USERNAME 을 지정하도록 명령 */
                /* ----- */
                // $clslineup1Dup = Common::getDataOne($this->selectDuplicateApplyForInside($GRPNO, $CLSNO, $EXECUTOR));
                // if($clslineup1Dup != null && Common::isEmpty($USERNAME))
                //     throw new GGexception("같은 일정에 중복등록의 경우, 참가자명을 지정하여 주시기 바랍니다.");

                /* get username if not exists */
                $username = $this->userBO->getUsername($EXECUTOR);
                $USERNAME = Common::isEmpty($USERNAME) ? $username : $USERNAME;

                /* 중단 : 유저의 계좌 직접관리로 인한 중단 */
                /* do payment */
                // $this->paymentABO->payForCls($EXECUTOR, $GRPNO, $CLSNO, $ORDERNO, $BILL);

                /* update */
                $query =
                "
                    update
                        clslineup1
                    set
                          userno = '$EXECUTOR'
                        , username = '$USERNAME'
                        , userregdt = now()
                        , etc = '$ETC'
                    where
                        grpno = '$GRPNO' and
                        clsno = '$CLSNO' and
                        orderno = $ORDERNO
                ";
                GGsql::exeQuery($query);
                break;
            }
            case self::updateApplyRegistStead:
            {
                /* is grp manager */
                $throwIfNotManager = true;
                $isGrpmanager = $this->ggAuth->isGrpmanager($GRPNO, $EXECUTOR, $throwIfNotManager);

                /* check cls info */
                /* is cls ing */
                $this->ggAuth->isClsIng($GRPNO, $CLSNO);

                /* is in cls apply period */
                /* 관리자의 경우, 예외 처리 없음 */
                if($isGrpmanager != true) {
                    $this->ggAuth->isClsInApplyDt($GRPNO, $CLSNO);
                }

                /* 해당 포지션에 이미 다른 유저가 기입하였는지 확인 */
                $clslineup1 = Common::getDataOne($this->selectByPkForInside($GRPNO, $CLSNO, $ORDERNO));
                $userno = Common::getField($clslineup1, self::FIELD__USERNO);
                if(Common::isNotEmpty($userno))
                    throw new GGexception("이미 신청된 일정입니다.");

                /* get username from userno */
                $USERNAME = $this->userBO->getUsername($USERNO);

                /* update */
                $query =
                "
                    update
                        clslineup1
                    set
                          userno = '$USERNO'
                        , username = '$USERNAME'
                        , userregdt = now()
                        , etc = '$ETC'
                    where
                        grpno = '$GRPNO' and
                        clsno = '$CLSNO' and
                        orderno = $ORDERNO
                ";
                GGsql::exeQuery($query);
                break;
            }
            case self::updateApplyCancel:
            {
                /* is grpmanager */
                $isGrpmanager = $this->ggAuth->isGrpmanager($GRPNO, $EXECUTOR);
                if($isGrpmanager == false)
                {
                    /* check userno */
                    $clslineup1 = $this->getByPk($GRPNO, $CLSNO, $ORDERNO);
                    if(Common::getField($clslineup1, self::FIELD__USERNO) != $EXECUTOR)
                        throw new GGexception("본인이 신청한 일정만 취소할 수 있습니다.");
                }

                /* check cls info */
                /* is cls ing */
                $this->ggAuth->isClsIng($GRPNO, $CLSNO);

                /* is in cls apply period */
                /* 관리자의 경우, 예외 처리 없음 */
                if($isGrpmanager != true) {
                    $this->ggAuth->isClsInApplyDt($GRPNO, $CLSNO);
                }

                /* 중단 : 유저의 계좌 직접관리로 인한 중단 */
                /* do payment */
                // $this->paymentABO->refundForCls($EXECUTOR, $GRPNO, $CLSNO, $ORDERNO, $BILL);

                /* update */
                $query =
                "
                    update
                        clslineup1
                    set
                          userno = ''
                        , username = ''
                        , userregdt = null
                    where
                        grpno = '$GRPNO' and
                        clsno = '$CLSNO' and
                        orderno = $ORDERNO
                ";
                GGsql::exeQuery($query);
                break;
            }
            case self::copyFromClsnoForInside:
            {
                $query =
                "
                    insert into clslineup1
                    (
                          grpno
                        , clsno
                        , orderno
                        , position
                        , userno
                        , username
                        , userregdt
                        , bill
                    )
                    select
                           grpno
                        , '$CLSNONEW'
                        ,  orderno
                        ,  position
                        ,  null
                        ,  null
                        ,  null
                        ,  bill
                    from
                        clslineup1
                    where
                        grpno = '$GRPNO' and
                        clsno = '$CLSNO'
                ";
                GGsql::exeQuery($query);
                break;
            }
            case self::updateUsernoToTargetForInside:
            {
                $query = "update clslineup1 set userno = '$TARGET' where grpno = '$GRPNO' and userno = '$USERNO'";
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
