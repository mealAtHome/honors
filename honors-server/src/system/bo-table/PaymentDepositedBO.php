<?php

/* payment_deposited */
class PaymentDepositedBO extends _CommonBO
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

    const FIELD__PACC_INDEX  = "pacc_index"; /* (pk) int(11) */
    const FIELD__DEPOSITOR   = "depositor";  /* (pk) varchar(50) */
    const FIELD__DEPOSITED   = "deposited";  /* (pk) int(11) */
    const FIELD__DEPOSITDT   = "depositdt";  /* (pk) datetime */

    /* ========================= */
    /* 조회 */
    /* ========================= */
    public function selectByPkForInside($PACC_INDEX, $DEPOSITOR, $DEPOSITED, $DEPOSITDT) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /*  */
    /*
        ■ option
            - executor
    */
    /* ========================= */
    const selectByPkForInside = "selectByPkForInside";
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
              t.pacc_index
            , t.depositor
            , t.deposited
            , t.depositdt
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPkForInside :
            {
                $from =
                "
                    (
                        select
                            *
                        from
                            payment_deposited
                        where
                            pacc_index =  $PACC_INDEX  and
                            depositor  = '$DEPOSITOR'  and
                            deposited  =  $DEPOSITED   and
                            depositdt  = '$DEPOSITDT'
                    ) t
                ";
                break;
            }
            case self::selectOneRandomForPaymentForInside :
            {
                $from =
                "
                    (
                        select
                            *
                        from
                            payment_deposited
                        where
                            pacc_index =  $PACC_INDEX  and
                            depositor  = '$DEPOSITOR'  and
                            deposited  =  $DEPOSITED   and
                            depositdt  = '$DEPOSITDT'
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
            order by
                t.pacc_index
        ";
        return GGsql::select($query, $from, $options);
    }

    /* ========================= */
    /* UPDATE / DELETE */
    /* ========================= */
    public function insertPaymentDepositedForInside ($PACC_INDEX, $DEPOSITOR, $DEPOSITED, $DEPOSITDT) { return $this->update(get_defined_vars(), __FUNCTION__); }

    const insertPaymentDepositedForInside = "insertPaymentDepositedForInside"; /* 입금 시, 레코드 삽입 */
    protected function update($options, $option="")
    {
        /* vars */
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
            case self::insertPaymentDepositedForInside:
            {
                /* ----- */
                /* validation : 이미 입금 내역이 있는지 확인 */
                /* ----- */
                $paymentDepositedAlready = Common::getData($this->selectByPkForInside(
                    $PACC_INDEX,
                    $DEPOSITOR,
                    $DEPOSITED,
                    $DEPOSITDT
                ));
                if(count($paymentDepositedAlready) > 0)
                    break;

                /* ----- */
                /* 입금처리 */
                /* ----- */
                /* process */
                $query =
                "
                    insert into payment_deposited
                    (
                          pacc_index
                        , depositor
                        , deposited
                        , depositdt
                    )
                    values
                    (
                           $PACC_INDEX
                        , '$DEPOSITOR'
                        ,  $DEPOSITED
                        , '$DEPOSITDT'
                    )
                ";
                $result = GGsql::exeQuery($query);

                /* get bo */
                GGnavi::getPaymentQueueBO();
                $paymentQueueBO = PaymentQueueBO::getInstance();

                /* 입금한 금액 만큼, paymentQueue 에 반영 */
                $paymentQueueBO->updateDepositForInside(
                    $PACC_INDEX,
                    $DEPOSITOR,
                    $DEPOSITED,
                    $DEPOSITDT
                );
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }
        return $rslt;
    }


    /* ========================= */
    /* 입금처리 API */
    /* ========================= */
    public function insertByList($options)
    {
        /* --------------- */
        /* vars */
        /* --------------- */
        $rslt = Common::getReturn();
        extract($options);

        /* --------------- */
        /* loop data */
        /* --------------- */
        foreach($PAYMENT_DATA as $paymentDat)
        {
            try
            {
                $paccIndex = $paymentDat['PACC_INDEX'];
                $depositor = $paymentDat['DEPOSITOR'];
                $deposited = $paymentDat['DEPOSITED'];
                $depositdt = $paymentDat['DEPOSITDT'];
                $this->insertPaymentDepositedForInside(
                    $paccIndex,
                    $depositor,
                    $deposited,
                    $depositdt);
            }
            catch(Exception $e)
            {

            }
        }
        return $rslt;
    }


} /* end class */
?>
