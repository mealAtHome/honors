<?php

/* payment_account */
class PaymentAccountBO extends _CommonBO
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

    const FIELD__PACC_INDEX       = "pacc_index";      /* (pk) int(11) */
    const FIELD__PACC_NAME        = "pacc_name";       /*      varchar(30) */
    const FIELD__PACC_BANKNAME    = "pacc_bankname";   /*      varchar(20) */
    const FIELD__PACC_BANKCODE    = "pacc_bankcode";   /*      varchar(3) */
    const FIELD__PACC_ACCOUNTNO   = "pacc_accountno";  /*      varchar(255) */
    const FIELD__PACC_USABLE      = "pacc_usable";     /*      enum('y','n') */

    /* ========================= */
    /*  */
    /*
        ■ option
            - executor
    */
    /* ========================= */
    public function selectUsableForInside() { return $this->select(get_defined_vars(), __FUNCTION__); }

    const selectUsableForInside = "selectUsableForInside";
    const selectAll = "selectAll";
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
            , t.pacc_name
            , t.pacc_bankname
            , t.pacc_bankcode
            , t.pacc_accountno
            , t.pacc_usable
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectAll              : { $from = "(select * from payment_account where pacc_usable = 'y') t"; break; }
            case self::selectUsableForInside  : { $from = "(select * from payment_account where pacc_usable = 'y' order by rand() limit 1) t"; break; }
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
        ";
        return GGsql::select($query, $from, $options);
    }

    /* ========================= */
    /* 사용가능한 계좌 반환 */
    /* ========================= */
    public function getAbleAccountForOrder($depositor, $billamount)
    {
        /* vars */
        GGnavi::getPaymentQueueBO();
        $paymentQueueBO = PaymentQueueBO::getInstance();

        /* 사용 가능한 계좌 리스트 조회 */
        $paymentAccounts = Common::getData($this->selectUsableForInside());

        /* 입금자명, 금액이 Queue 에서 존재하는지 확인 */
        foreach($paymentAccounts as $paymentAccount)
        {
            $paccIndex = Common::get($paymentAccount, self::FIELD__PACC_INDEX);
            $paymentQueues = Common::getData($paymentQueueBO->selectByPkForInside($paccIndex, $depositor, $billamount));
            if(count($paymentQueues) == 0)
                return $paccIndex;
        }
        return null;
    }

} /* end class */
?>
