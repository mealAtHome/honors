<?php

class StoreDeliverychargeBO extends _CommonBO
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

    const FIELD__STORENO                  = "storeno";                 /* (pk) char(30) */
    const FIELD__DELIVERYCHARGE_ROWNUM    = "deliverycharge_rownum";   /* (pk) int(11) */
    const FIELD__DELIVERYCHARGE_FROM      = "deliverycharge_from";     /*      int(11) */
    const FIELD__DELIVERYCHARGE_TO        = "deliverycharge_to";       /*      int(11) */
    const FIELD__DELIVERYCHARGE_CHARGE    = "deliverycharge_charge";   /*      int(11) */
    const FIELD__UPDATED_AT               = "updated_at";              /*      datetime */
    const FIELD__CREATED_AT               = "created_at";              /*      datetime */

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByStoreno          ($STORENO) { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectByStorenoForInside ($STORENO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ==================== */
    /* (return : record) 금액에 해당하는 배달료를 반환 */
    /* ==================== */
    public function selectStoreDeliverychargeByMenutotal($storeno, $menutotal)
    {
        /* get records of storeno */
        $storeDeliverycharges = Common::getData($this->selectByStorenoForInside($storeno));

        /* loop and get deliverycharge */
        $rslt = null;
        foreach($storeDeliverycharges as $model)
        {
            $deliveryChargeFrom = intval($model[self::FIELD__DELIVERYCHARGE_FROM]);
            $deliveryChargeTo   = intval($model[self::FIELD__DELIVERYCHARGE_TO]);

            if($deliveryChargeFrom <= $menutotal && $deliveryChargeTo >= $menutotal)
                $rslt = $model;
            else if($deliveryChargeFrom <= $menutotal && $deliveryChargeTo == null)
                $rslt = $model;
            else if($deliveryChargeFrom > $menutotal)
                $rslt = $model;

            if($rslt != null)
                return $rslt;
        }
        return $rslt;
    }

    /* ========================= */
    /* select */
    /* ========================= */
    const selectByStoreno = "selectByStoreno";
    const selectByStorenoForInside = "selectByStorenoForInside";
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
             sdc.storeno
            ,sdc.deliverycharge_rownum
            ,sdc.deliverycharge_from
            ,sdc.deliverycharge_to
            ,sdc.deliverycharge_charge
            ,sdc.updated_at
            ,sdc.created_at
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByStoreno          : { $from = "(select * from store_deliverycharge where storeno = '$STORENO') sdc"; break; }
            case self::selectByStorenoForInside : { $from = "(select * from store_deliverycharge where storeno = '$STORENO') sdc"; break; }
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
            order by
                sdc.storeno
                ,sdc.deliverycharge_rownum
        ";
        return GGsql::select($query, $from, $options);
    }

    /* ==================== */
    /* 업데이트 */
    /* ==================== */
    protected function update($options, $option="")
    {
        /* -------------- */
        /* vars */
        /* -------------- */
        $ggAuth = GGauth::getInstance();
        $ggAuth->isStoreOwner($EXECUTOR, $STORENO);

        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* vars */
        $storeno = null;

        /* --------------- */
        /* get vars */
        /* --------------- */
        extract($options);

        /* --------------- */
        /* delete rows */
        /* --------------- */
        $query = "delete from store_deliverycharge where storeno = '$STORENO'";
        $result = GGsql::exeQuery($query);

        /* --------------- */
        /* insert rows */
        /* --------------- */
        $sdcArr = json_decode($STORE_DELIVERYCHARGE, true);
        foreach($sdcArr as $sdc)
        {
            $DELIVERYCHARGE_ROWNUM     = $sdc['DELIVERYCHARGE_ROWNUM'];
            $DELIVERYCHARGE_FROM       = $sdc['DELIVERYCHARGE_FROM'];
            $DELIVERYCHARGE_TO         = $sdc['DELIVERYCHARGE_TO'];
            $DELIVERYCHARGE_CHARGE     = $sdc['DELIVERYCHARGE_CHARGE'];

            $query =
            "
                insert into store_deliverycharge
                (
                    storeno
                    ,deliverycharge_rownum
                    ,deliverycharge_from
                    ,deliverycharge_to
                    ,deliverycharge_charge
                    ,updated_at
                    ,created_at
                )
                values
                (
                     '$STORENO'
                    , $DELIVERYCHARGE_ROWNUM
                    , $DELIVERYCHARGE_FROM
                    , $DELIVERYCHARGE_TO
                    , $DELIVERYCHARGE_CHARGE
                    , now()
                    , now()
                )
            ";
            $result = GGsql::exeQuery($query);
        }
        return true;
    }

    /* ==================== */
    /* (return : int) 금액에 해당하는 배달료를 반환 */
    /* ==================== */
    public function getChargeByMenutotal($storeno, $menutotal, $defaultCharge)
    {
        /* get storeDeliverycharge record by menutotal */
        $record = $this->selectStoreDeliverychargeByMenutotal($storeno, $menutotal);
        if($record == null)
            return $defaultCharge;

        /* return charge */
        return $record[self::FIELD__DELIVERYCHARGE_CHARGE];
    }

} /* end class */
?>
