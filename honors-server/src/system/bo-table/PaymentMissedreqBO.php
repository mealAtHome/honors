<?php

/* payment_missedreq */
class PaymentMissedreqBO extends _CommonBO
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
    public function __construct()
    {
    }

    /* ========================= */
    /* field */
    /* ========================= */
    const FIELD__USERNO                = "userno";                /* (pk) char(30) */
    const FIELD__REQNO                 = "reqno";                 /* (pk) char(14) */
    const FIELD__STATUS                = "status";                /*      enum('req','reqcancel','ing','ng','okwait','ok') */
    const FIELD__PACC_INDEX            = "pacc_index";            /*      int */
    const FIELD__DEPOSITOR             = "depositor";             /*      varchar(50) */
    const FIELD__DEPOSITED             = "deposited";             /*      int */
    const FIELD__REFUND_BACCNO         = "refund_baccno";         /*      int */
    const FIELD__REFUND_BACCCODE       = "refund_bacccode";       /*      varchar(10) */
    const FIELD__REFUND_BACCACCT       = "refund_baccacct";       /*      varchar(30) */
    const FIELD__REFUND_BACCNAME       = "refund_baccname";       /*      varchar(255) */
    const FIELD__SYSMSG                = "sysmsg";                /*      varchar(255) */
    const FIELD__ADMINNO               = "adminno";               /*      char(30) */
    const FIELD__COMPLETEDT            = "completedt";            /*      datetime */
    const FIELD__QUEUEFAILEDEXISTS     = "queuefailedexists";     /*      enum('y','n') */
    const FIELD__REGDT                 = "regdt";                 /*      datetime */

    /* ========================= */
    /* const */
    /* ========================= */
    const STATUS__REQ       = 'req';
    const STATUS__REQCANCEL = 'reqcancel';
    const STATUS__ING       = 'ing';
    const STATUS__NG        = 'ng';
    const STATUS__OKWAIT    = 'okwait';
    const STATUS__OK        = 'ok';

    /* ========================= */
    /*  */
    /*
        ■ option
            - executor
    */
    /* ========================= */
    public function selectTodayAppliedByUsernoAndRegdtForInside($USERNO) { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectByPkForInside($USERNO, $REQNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    const selectByStatusForAdmin = "selectByStatusForAdmin";
    const selectByStatusForFront = "selectByStatusForFront";
    const selectByPkForInside = "selectByPkForInside";
    const selectByPk = "selectByPk";
    const selectTodayAppliedByUsernoAndRegdtForInside = "selectTodayAppliedByUsernoAndRegdtForInside";

    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
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
              t.userno
            , t.reqno
            , t.status
            , t.pacc_index
            , t.depositor
            , t.deposited
            , t.refund_baccno
            , t.refund_bacccode
            , t.refund_baccacct
            , t.refund_baccname
            , t.sysmsg
            , t.adminno
            , t.completedt
            , t.queuefailedexists
            , t.regdt
            , pacc.pacc_name
            , pacc.pacc_bankname
            , pacc.pacc_bankcode
            , pacc.pacc_accountno
            , refundbacc.baccnickname refund_baccnickname
            , bank.bankname refund_bankname
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByStatusForAdmin :
            {
                switch($STATUS)
                {
                    case "req"    : $from = "(select * from payment_missedreq where status in ('req')    order by regdt desc) t"; break;
                    case "ing"    : $from = "(select * from payment_missedreq where status in ('ing')    order by regdt desc) t"; break;
                    case "ok"     : $from = "(select * from payment_missedreq where status in ('ok')     order by regdt desc) t"; break;
                    case "okwait" : $from = "(select * from payment_missedreq where status in ('okwait') order by regdt desc) t"; break;
                    case "ng"     : $from = "(select * from payment_missedreq where status in ('ng')     order by regdt desc) t"; break;
                    default:
                    {
                        throw new GGexception("잘못된 상태값입니다.");
                        break;
                    }
                }
                break;
            }
            case self::selectByStatusForFront :
            {
                switch($STATUS)
                {
                    case "reqing" : $from = "(select * from payment_missedreq where userno = '$EXECUTOR' and status in ('req', 'ing')   order by regdt desc limit 50) t"; break;
                    case "ok"     : $from = "(select * from payment_missedreq where userno = '$EXECUTOR' and status in ('ok', 'okwait') order by regdt desc limit 50) t"; break;
                    case "ng"     : $from = "(select * from payment_missedreq where userno = '$EXECUTOR' and status in ('ng')           order by regdt desc limit 50) t"; break;
                    default:
                    {
                        throw new GGexception("잘못된 상태값입니다.");
                        break;
                    }
                }
                break;
            }
            case self::selectTodayAppliedByUsernoAndRegdtForInside  : { $from = "(select * from payment_missedreq where userno = '$USERNO' and date(regdt) = date(now())) t"; break; }
            case self::selectByPk                                   : { $from = "(select * from payment_missedreq where userno = '$USERNO' and reqno = '$REQNO') t"; break; }
            case self::selectByPkForInside                          : { $from = "(select * from payment_missedreq where userno = '$USERNO' and reqno = '$REQNO') t"; break; }
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
                left join payment_account pacc
                    on
                        t.pacc_index = pacc.pacc_index
                left join bank bank
                    on
                        t.refund_bacccode = bank.bankcode
                left join bankaccount refundbacc
                    on
                        t.refund_baccno = refundbacc.baccno
            order by
                regdt desc
        ";
        return GGsql::select($query, $from, $options);
    }

    /* ========================= */
    /* UPDATE / DELETE */
    /* ========================= */
    // public function insertFromOrderForInside      ($STORENO, $ORDERNO, $PACC_INDEX, $ORDERER, $DEPOSITOR, $BILLAMOUNT             ) { return $this->update(get_defined_vars(), __FUNCTION__); }
    // public function updateDepositForInside        (                    $PACC_INDEX,           $DEPOSITOR,              $DEPOSITED ) { return $this->update(get_defined_vars(), __FUNCTION__); }
    // public function deleteByPkForInside           ($PACC_INDEX,                               $DEPOSITOR, $BILLAMOUNT             ) { return $this->update(get_defined_vars(), __FUNCTION__); }
    // public function deleteForOrderCancelForInside ($STORENO, $ORDERNO                                                             ) { return $this->update(get_defined_vars(), __FUNCTION__); }

    const insert = "insert"; /* 일반 삽입 */
    const updateByAdmin = "updateByAdmin"; /* [어드민] 업데이트 */
    // const updateDepositForInside = "updateDepositForInside"; /* 입금 시, 입금처리 */
    // const deleteByPkForInside = "deleteByPkForInside"; /* 입금완료 시, 삭제 */
    // const deleteForOrderCancelForInside = "deleteForOrderCancelForInside"; /* 주문취소 시 삭제 */
    protected function update($options, $option="")
    {
        /* vars */
        $ggAuth = GGAuth::getInstance();
        $rslt = Common::getReturn();

        /* ==================== */
        /* common validation */
        /* ==================== */
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* ==================== */
        /* 사전처리 */
        /* ==================== */

        /* ==================== */
        /* process */
        /* ==================== */
        switch($OPTION)
        {
            case self::insert:
            {
                /* get bo */
                GGnavi::getPaymentQueueFailedBO();
                GGnavi::getBankaccountBO();
                $paymentQueueFailedBO = PaymentQueueFailedBO::getInstance();
                $bankaccountBO = BankaccountBO::getInstance();

                /* set var */
                $reqno = $this->makeRandIndex("paymentMissedreq", $EXECUTOR);
                $status = self::STATUS__REQ;

                /* validation : 하루에 세 건 까지만 신청할 수 있도록 함 */
                $paymentMissedreqTodayApplied = Common::getData($this->selectTodayAppliedByUsernoAndRegdtForInside($EXECUTOR));
                if(count($paymentMissedreqTodayApplied) >= 3)
                    throw new GGexception("하루에 세 건까지만 신청할 수 있습니다.");

                /* is exist in paymentQueueFailed List */
                $queuefailedexists = 'n';
                $paymentQueueFailedDat = Common::getData($paymentQueueFailedBO->selectByPaymentMissedreqInfoForInside($PACC_INDEX, $DEPOSITOR, $DEPOSITED, GGdate::getYMDHIS()));
                if(count($paymentQueueFailedDat) > 0)
                    $queuefailedexists = 'y';

                /* get from bankaccount */
                $bankaccount = Common::getDataOne($bankaccountBO->selectByPkForInside($EXECUTOR, $REFUND_BACCNO));
                if($bankaccount == null)
                    throw new GGexception("반환받을 계좌정보가 존재하지 않습니다.");
                $refundBankcode = $bankaccount[BankaccountBO::FIELD__BACCCODE];
                $refundNumber = $bankaccount[BankaccountBO::FIELD__BACCACCT];
                $refundName = $bankaccount[BankaccountBO::FIELD__BACCNAME];

                /* --------------- */
                /* query */
                /* --------------- */
                $query =
                "
                    insert into payment_missedreq
                    (
                          userno
                        , reqno
                        , status
                        , pacc_index
                        , depositor
                        , deposited
                        , refund_baccno
                        , refund_bacccode
                        , refund_baccacct
                        , refund_baccname
                        , queuefailedexists
                        , regdt
                    )
                    values
                    (
                          '$EXECUTOR'
                        , '$reqno'
                        , '$status'
                        ,  $PACC_INDEX
                        , '$DEPOSITOR'
                        ,  $DEPOSITED
                        ,  $REFUND_BACCNO
                        , '$refundBankcode'
                        , '$refundNumber'
                        , '$refundName'
                        , '$queuefailedexists'
                        , now()
                    )
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::updateByAdmin:
            {
                $ggAuth->isAdmin($EXECUTOR);

                $completedt = "null";
                switch($STATUS)
                {
                    case self::STATUS__OK : $completedt = "now()"; break;
                    case self::STATUS__NG : $completedt = "now()"; break;
                }

                $query =
                "
                    update
                        payment_missedreq
                    set
                        status = '$STATUS',
                        sysmsg = '$SYSMSG',
                        completedt = $completedt
                    where
                        userno = '$USERNO' and
                        reqno = '$REQNO'
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
        }
        return $rslt;
    }


} /* end class */
?>
