<?php

/* rdc : _ref_deliverycharge_discount */
class RefDeliverychargeDiscountBO extends _CommonBO
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
    function setBO() {}

    /* ========================= */
    /* fields */
    /* ========================= */
    const FIELD__LOCATION         = "location";         /* (pk) char(20) */
    const FIELD__DISCOUNT         = "discount";         /*      int */

    /* ========================= */
    /*  */
    /* ========================= */
    public function selectByLocationForInside($LOCATION) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /*  */
    /* ========================= */
    const selectByLocationForInside = "selectByLocationForInside";
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
              t.location
            , t.discount
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByLocationForInside :
            {
                $from =
                "
                    (
                        select
                            *
                        from
                            _ref_deliverycharge_discount
                        where
                            location = '$LOCATION'
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
        ";
        return GGsql::select($query, $from, $options);
    }

    public function getDiscountByLocation($LOCATION)
    {
        $record = Common::getDataOne($this->selectByLocationForInside($LOCATION));
        if($record == null)
            return 0;
        return $record[self::FIELD__DISCOUNT];
    }

} /* end class */
?>
