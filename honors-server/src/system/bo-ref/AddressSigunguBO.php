<?php

/* asg : _address_sigungu */
class AddressSigunguBO extends _CommonBO
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
        // GGnavi::getStoreBO();
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    const FIELD__SDIDX   = "sdidx";    /* (pk) int */
    const FIELD__SGGIDX  = "sggidx";   /* (pk) int */
    const FIELD__SGGNAME = "sggname";  /* (  ) char(30) */

    /* ========================= */
    /*  */
    /* ========================= */
    /* public function selectBankfeeNowForInside()        { return $this->select(get_defined_vars(), __FUNCTION__); } */

    /* ========================= */
    /*  */
    /* ========================= */
    const selectBySdidx = "selectBySdidx"; /* 조회 */
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
              t.sdidx
            , t.sggidx
            , t.sggname
            , sd.sdname
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        $nowYMD = GGdate::format(GGdate::now(), GGdate::DATEFORMAT__YYYYMMDD);
        switch($OPTION)
        {
            case self::selectBySdidx : $from = "(select * from _address_sigungu where sdidx = $SDIDX) t"; break;
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
                left join _address_sido sd
                    on
                        t.sdidx = sd.sdidx
            order by
                t.sdidx asc
        ";
        return GGsql::select($query, $from, $options);
    }

} /* end class */
?>
