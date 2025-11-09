<?php

class BizBO extends _CommonBO
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
    }

    /* ========================= */
    /* field */
    /*
    */
    /* ========================= */
    const FIELD__TEAMNO       = "teamno";       /* (pk) char(30) / NO */
    const FIELD__LEADERNO     = "leaderno";     /* (pk) char(30) / NO */
    const FIELD__IMG          = "img";          /* (  ) char(10) / YES */
    const FIELD__TEAMNAME     = "teamname";     /* (  ) char(50) / NO */
    const FIELD__MODIDT       = "modidt";       /* (  ) datetime / YES */
    const FIELD__REGIDT       = "regidt";       /* (  ) datetime / YES */

    /* ========================= */
    /* enum */
    /*
    */
    /* ========================= */

    public function getTeamnoByLeaderno($leaderno) { return Common::getDataOneField($this->selectByLeadernoForInside($leaderno), self::FIELD__TEAMNO); }


    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByLeadernoForInside ($LEADERNO) { return $this->select(get_defined_vars(), __FUNCTION__); }
    // public function selectByPkForInside                                  ($STORENO)                     { return $this->select(get_defined_vars(), __FUNCTION__); }
    // public function selectByUsernoForInside                              ($USERNO)                      { return $this->select(get_defined_vars(), __FUNCTION__); }
    // public function selectForDeliveryMatchingByRiderGpsForInside         ($RIDER_LATIY,$RIDER_LONGX)    { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /* ========================= */
    const selectMe = "selectMe";
    const selectByLeadernoForInside = "selectByLeadernoForInside";
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
              t.teamno
            , t.leaderno
            , t.img
            , t.teamname
            , t.modidt
            , t.regidt
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectMe                  : { $from = "(select * from team where leaderno  = '$EXECUTOR') t"; break; }
            case self::selectByLeadernoForInside : { $from = "(select * from team where leaderno  = '$LEADERNO') t"; break; }
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
        $result = GGsql::select($query, $from, $options);
        return $result;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    // public function changeStoreStatus ($STORENO, $STORE_STATUS) { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    const insert = "insert";
    protected function update($options, $option="")
    {
        /* vars */
        $ggAuth = GGauth::getInstance();
        $bizno = null;

        /* get vars */
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* sql execution */
        switch($OPTION)
        {
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }
        return $bizno;
    }

} /* end class */
?>
