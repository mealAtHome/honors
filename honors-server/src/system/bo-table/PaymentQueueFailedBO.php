<?php

/* payment_queue_failed : pqf */
class PaymentQueueFailedBO extends _CommonBO
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

    const FIELD__PACC_INDEX  = "pacc_index";   /* (pk) int(11) */
    const FIELD__DEPOSITOR   = "depositor";    /* (pk) varchar(50) */
    const FIELD__DEPOSITED   = "deposited";    /* (pk) int(11) */
    const FIELD__DEPOSITDT   = "depositdt";    /* (pk) datetime */
    const FIELD__REGDT       = "regdt";        /* (pk) datetime */

    /* ========================= */
    /* 조회 */
    /* ========================= */
    public function selectByPaymentMissedreqInfoForInside($PACC_INDEX, $DEPOSITOR, $DEPOSITED, $DEPOSITDT) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /*  */
    /*
        ■ option
            - executor
    */
    /* ========================= */
    const selectByPaymentMissedreqInfoForInside = "selectByPaymentMissedreqInfoForInside"; /* PACC_INDEX, DEPOSITOR, DEPOSITED, DEPOSITDT */
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
            case self::selectByPaymentMissedreqInfoForInside:
            {
                $from =
                "
                    (
                        select
                            *
                        from
                            payment_queue_failed
                        where
                            pacc_index = $PACC_INDEX
                            and depositor = '$DEPOSITOR'
                            and deposited = $DEPOSITED
                            and depositdt <= str_to_date('$DEPOSITDT', '%Y-%m-%d %H:%i:%s')
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
    public function insertForInside($PACC_INDEX, $DEPOSITOR, $DEPOSITED, $DEPOSITDT) { return $this->update(get_defined_vars(), __FUNCTION__); }

    const insertForInside = "insertForInside";
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
            case self::insertForInside:
            {
                $query =
                "
                    insert into payment_queue_failed
                    (
                          pacc_index
                        , depositor
                        , deposited
                        , depositdt
                        , regdt
                    )
                    values
                    (
                           $PACC_INDEX
                        , '$DEPOSITOR'
                        ,  $DEPOSITED
                        , '$DEPOSITDT'
                        ,  now()
                    )
                ";
                $result = GGsql::exeQuery($query);
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
