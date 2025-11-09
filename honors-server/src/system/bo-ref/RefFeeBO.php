<?php

/* rdd : _ref_fee */
class RefFeeBO extends _CommonBO
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
        // GGnavi::getStoreBO();
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    const FIELD__FEETYPE          = "feetype";    /* (pk) varchar(50) */
    const FIELD__STARTDATE        = "startdate";  /* (pk) date */
    const FIELD__CHARGE           = "charge";     /*      int */

    /* ========================= */
    /*  */
    /* ========================= */
    public function selectDeliverychargeNowForInside() { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectStoreOrderFeeNowForInside()  { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectRiderOrderFeeNowForInside()  { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectBankfeeNowForInside()        { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /*  */
    /* ========================= */
    const selectDeliverychargeNowForInside = "selectDeliverychargeNowForInside"; /* 기본배달료 */
    const selectStoreOrderFeeNowForInside = "selectStoreOrderFeeNowForInside"; /* 매장 주문건당 수수료 */
    const selectRiderOrderFeeNowForInside = "selectRiderOrderFeeNowForInside"; /* 라이더 주문건당 수수료 */
    const selectBankfeeNowForInside = "selectBankfeeNowForInside"; /* 은행수수료 */
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
              t.feetype
            , t.startdate
            , t.charge
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        $nowYMD = GGdate::format(GGdate::now(), GGdate::DATEFORMAT__YYYYMMDD);
        switch($OPTION)
        {
            case self::selectDeliverychargeNowForInside : $from = "(select * from _ref_fee where feetype = 'deliverycharge'  and startdate <= '$nowYMD' order by startdate desc limit 1) t"; break;
            case self::selectStoreOrderFeeNowForInside  : $from = "(select * from _ref_fee where feetype = 'store_order_fee' and startdate <= '$nowYMD' order by startdate desc limit 1) t"; break;
            case self::selectRiderOrderFeeNowForInside  : $from = "(select * from _ref_fee where feetype = 'rider_order_fee' and startdate <= '$nowYMD' order by startdate desc limit 1) t"; break;
            case self::selectBankfeeNowForInside        : $from = "(select * from _ref_fee where feetype = 'bankfee' and startdate <= '$nowYMD' order by startdate desc limit 1) t"; break;
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
        ";
        return GGsql::select($query, $from, $options);
    }

    public function getDeliverychargeNow() { $record = Common::getDataOne($this->selectDeliverychargeNowForInside()); if($record == null) throw new GGexception("(server) no record found"); return $record[self::FIELD__CHARGE]; }
    public function getStoreOrderFeeNow()  { $record = Common::getDataOne($this->selectStoreOrderFeeNowForInside() ); if($record == null) throw new GGexception("(server) no record found"); return $record[self::FIELD__CHARGE]; }
    public function getRiderOrderFeeNow()  { $record = Common::getDataOne($this->selectRiderOrderFeeNowForInside() ); if($record == null) throw new GGexception("(server) no record found"); return $record[self::FIELD__CHARGE]; }
    public function getBankfee()           { $record = Common::getDataOne($this->selectBankfeeNowForInside() ); if($record == null) throw new GGexception("(server) no record found"); return $record[self::FIELD__CHARGE]; }

} /* end class */
?>
