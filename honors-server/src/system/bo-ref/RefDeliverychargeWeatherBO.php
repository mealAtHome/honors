<?php

/* rdw : _ref_deliverycharge_weather */
class RefDeliverychargeWeatherBO extends _CommonBO
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
    function setBO() {
        GGnavi::getUserAddrBO();
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    const FIELD__LOCATION         = "location";         /* (pk) char(20) */
    const FIELD__WEATHER          = "weather";          /*      char(20) */
    const FIELD__WEATHER_COMMENT  = "weather_comment";  /*      char(50) */
    const FIELD__STARTDATETIME    = "startdatetime";    /*      datetime */
    const FIELD__ENDDATETIME      = "enddatetime";      /*      datetime */
    const FIELD__CHARGE           = "charge";           /*      int */

    /* ========================= */
    /*  */
    /* ========================= */
    public function selectByLocationForInside($ADDR_SIDO, $ADDR_SIGUNGU) { return $this->select(get_defined_vars(), __FUNCTION__); }

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
            , t.weather
            , t.weather_comment
            , t.startdatetime
            , t.enddatetime
            , t.charge
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByLocationForInside :
            {
                $nowYMDHIS = GGdate::format(GGdate::now(), GGdate::DATEFORMAT__YYYYMMDDHHIISS);
                $from =
                "
                    (
                        select
                            *
                        from
                            _ref_deliverycharge_weather
                        where
                            (
                                location = '$ADDR_SIDO' or
                                location = '$ADDR_SIGUNGU'
                            ) and
                            startdatetime <= str_to_date('$nowYMDHIS', '%Y-%m-%d') and
                            enddatetime   >= str_to_date('$nowYMDHIS', '%Y-%m-%d')
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


    public function getChargeByAddrIndex($EXECUTOR, $ADDR_INDEX)
    {
        $userAddrBO = UserAddrBO::getInstance();
        $userAddr = Common::getDataOne($userAddrBO->selectByAddrIndexForInside($EXECUTOR, $ADDR_INDEX));
        if($userAddr == null)
            throw new GGexception("(server) no userAddr");

        /* get location */
        $addrSido    = $userAddr[UserAddrBO::FIELD__ADDR_SIDO];
        $addrSigungu = $userAddr[UserAddrBO::FIELD__ADDR_SIGUNGU];
        $record = Common::getDataOne($this->selectByLocationForInside($addrSido, $addrSigungu));
        if($record == null)
            return 0;
        return $record[self::FIELD__CHARGE];
    }

    public function getChargeByAddr($ADDR_SIDO, $ADDR_SIGUNGU)
    {
        $record = Common::getDataOne($this->selectByLocationForInside($ADDR_SIDO, $ADDR_SIGUNGU));
        if($record == null)
            return 0;
        return $record[self::FIELD__CHARGE];
    }

} /* end class */
?>
