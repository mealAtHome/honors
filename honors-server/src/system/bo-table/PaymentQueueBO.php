<?php

/* payment_queue : pq */
class PaymentQueueBO extends _CommonBO
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

    const FIELD__STORENO     = "storeno";      /* (UQ) int       (11)                            */
    const FIELD__ORDERNO     = "orderno";      /* (UQ) int       (11)                            */
    const FIELD__PACC_INDEX  = "pacc_index";   /* (pk) int       (11)                            */
    const FIELD__ORDERER     = "orderer";      /*      int       (11)                            */
    const FIELD__DEPOSITOR   = "depositor";    /* (pk) varchar   (50)                            */
    const FIELD__BILLAMOUNT  = "billamount";   /* (pk) int       (11)                            */
    const FIELD__STATUS      = "status";       /*      enum      ('wait','shortfall','complete') */
    const FIELD__DEPOSITED   = "deposited";    /*      int       (11)                            */
    const FIELD__REGDT       = "regdt";        /*      datetime                                  */

    /* ========================= */
    /* 조회 */
    /* ========================= */
    public function selectByPkForInside                          ($PACC_INDEX,                     $DEPOSITOR, $BILLAMOUNT         ) { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectByStorenoOrdernoForInside              (             $STORENO, $ORDERNO                                  ) { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectNotPaidBeforeDateForInside             (                                                          $REGDT ) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /*  */
    /*
        ■ option
            - executor
    */
    /* ========================= */
    const selectByPkForInside = "selectByPkForInside";
    const selectByStorenoOrderno = "selectByStorenoOrderno";
    const selectByStorenoOrdernoForInside = "selectByStorenoOrdernoForInside";
    const selectWaitByDepositorForInside = "selectWaitByDepositorForInside";
    const selectNotPaidBeforeDateForInside = "selectNotPaidBeforeDateForInside";
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        $ggAuth = GGauth::getInstance();
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
              t.storeno
            , t.orderno
            , t.pacc_index
            , t.orderer
            , t.depositor
            , t.billamount
            , t.status
            , t.deposited
            , t.regdt
            , pacc.pacc_name
            , pacc.pacc_bankname
            , pacc.pacc_bankcode
            , pacc.pacc_accountno
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByStorenoOrderno             : { $ggAuth->orderView($EXECUTOR, $STORENO, $ORDERNO); $from = "(select * from payment_queue where storeno = '$STORENO' and orderno = '$ORDERNO') t"; break; }
            case self::selectByStorenoOrdernoForInside    : {                                                    $from = "(select * from payment_queue where storeno = '$STORENO' and orderno = '$ORDERNO') t"; break; }
            case self::selectNotPaidBeforeDateForInside:
            {
                $from =
                "
                    (
                        select
                            *
                        from
                            payment_queue
                        where
                            status = 'wait' and
                            regdt < str_to_date('$REGDT', '%Y-%m-%d %H:%i:%s')
                    ) t
                ";
                break;
            }
            case self::selectByPkForInside :
            {
                $from =
                "
                    (
                        select
                            *
                        from
                            payment_queue
                        where
                            pacc_index = $PACC_INDEX and
                            depositor = '$DEPOSITOR' and
                            billamount = $BILLAMOUNT
                    ) t
                ";
                break;
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
                left join payment_account pacc
                    on
                        t.pacc_index = pacc.pacc_index
            order by
                t.pacc_index,
                t.depositor
        ";
        return GGsql::select($query, $from, $options);
    }

    /* ========================= */
    /* UPDATE / DELETE */
    /* ========================= */
    public function insertFromOrderForInside      ($STORENO, $ORDERNO, $PACC_INDEX, $ORDERER, $DEPOSITOR, $BILLAMOUNT) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function updateDepositForInside        ($PACC_INDEX, $DEPOSITOR, $DEPOSITED, $DEPOSITDT) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteByPkForInside           ($PACC_INDEX, $DEPOSITOR, $BILLAMOUNT) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteForOrderCancelForInside ($STORENO, $ORDERNO) { return $this->update(get_defined_vars(), __FUNCTION__); }

    const insertFromOrderForInside = "insertFromOrderForInside"; /* 주문 시, 레코드 삽입 */
    const updateDepositForInside = "updateDepositForInside"; /* 입금 시, 입금처리 */
    const deleteByPkForInside = "deleteByPkForInside"; /* 입금완료 시, 삭제 */
    const deleteForOrderCancelForInside = "deleteForOrderCancelForInside"; /* 주문취소 시 삭제 */
    protected function update($options, $option="")
    {
        /* vars */
        $ggAuth = GGauth::getInstance();
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
            case self::insertFromOrderForInside:
            {
                $query =
                "
                    insert into payment_queue
                    (
                          storeno
                        , orderno
                        , pacc_index
                        , orderer
                        , depositor
                        , billamount
                        , regdt
                    )
                    values
                    (
                          '$STORENO'
                        , '$ORDERNO'
                        ,  $PACC_INDEX
                        , '$ORDERER'
                        , '$DEPOSITOR'
                        ,  $BILLAMOUNT
                        ,  now()
                    )
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::updateDepositForInside:
            {
                /* 입금처리 */
                $query =
                "
                    update
                        payment_queue
                    set
                        deposited = $DEPOSITED
                    where
                        pacc_index =  $PACC_INDEX and
                        depositor  = '$DEPOSITOR' and
                        billamount =  $DEPOSITED
                ";
                $result = GGsql::exeQuery($query);

                /* 당해 레코드로 입금이 완료되었는지 확인 */
                $paymentQueue = Common::getDataOne($this->selectByPkForInside($PACC_INDEX, $DEPOSITOR, $DEPOSITED));
                if($paymentQueue == null)
                {
                    /* 일치하는 레코드가 없으면, 실패리스트에 등록 */
                    GGnavi::getPaymentQueueFailedBO();
                    $paymentQueueFailedBO = PaymentQueueFailedBO::getInstance();
                    $paymentQueueFailedBO->insertForInside($PACC_INDEX, $DEPOSITOR, $DEPOSITED, $DEPOSITDT);
                }
                else
                {
                    /* 입금금액과 결제 금액이 일치할경우, "입금대기"에서 "주문확인"으로 */
                    $storeno    = $paymentQueue[self::FIELD__STORENO];
                    $orderno    = $paymentQueue[self::FIELD__ORDERNO];
                    $deposited  = $paymentQueue[self::FIELD__DEPOSITED];
                    $billamount = $paymentQueue[self::FIELD__BILLAMOUNT];

                    GGnavi::getOrderingBO();
                    $orderingBO = OrderingBO::getInstance();
                    $orderingBO->updateOrderstatusPayToConfirmForInside($storeno, $orderno);

                    /* 큐에서 삭제 */
                    $this->deleteByPkForInside($PACC_INDEX, $DEPOSITOR, $DEPOSITED);
                }
                break;
            }
            case self::deleteByPkForInside:
            {
                $query =
                "
                    delete from
                        payment_queue
                    where
                        pacc_index =  $PACC_INDEX  and
                        depositor  = '$DEPOSITOR'  and
                        billamount =  $BILLAMOUNT
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::deleteForOrderCancelForInside: { $query = "delete from payment_queue where storeno = '$STORENO' and orderno = '$ORDERNO' "; $result = GGsql::exeQuery($query); break; }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }
        return $rslt;
    }

} /* end class */
?>
