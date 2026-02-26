<?php

/* asd : _address_sido */
class AddressSidoBO extends _CommonBO
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
    function setBO()
    {
        $arr = array();
        $arr['ggAuth'] = GGauth::getInstance();
        return $arr;
    }


    /* ========================= */
    /* fields */
    /*
    */
    /* ========================= */
    const FIELD__SDIDX   = "sdidx";   /* (pk) int      */
    const FIELD__SDNAME  = "sdname";  /* (  ) char(30) */

    /* ========================= */
    /* enum */
    /*
    */
    /* ========================= */
    static public function getConsts()
    {
        $arr = array();
        // $arr['key'] = "value";
        return $arr;
    }

    /* ========================= */
    /*  */
    /* ========================= */
    /* public function selectBankfeeNowForInside()        { return $this->select(get_defined_vars(), __FUNCTION__); } */

    /* ========================= */
    /*  */
    /* ========================= */
    const selectAll = "selectAll"; /* 조회 */
    protected function select($options, $option="")
    {
        /* vars */
        extract(self::getConsts());
        extract($options);

        /* override option */
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
              t.sdidx
            , t.sdname
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        $nowYMD = GGdate::format(GGdate::now(), GGdate::DATEFORMAT__YYYYMMDD);
        switch($OPTION)
        {
            case self::selectAll : $from = "(select * from _address_sido) t"; break;
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
                t.sdidx asc
        ";
        return GGsql::select($query, $from, $options);
    }

} /* end class */
?>
