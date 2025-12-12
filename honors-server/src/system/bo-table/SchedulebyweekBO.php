<?php

class SchedulebyweekBO extends _CommonBO
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
    function __construct()
    {
        // GGnavi::getIdxBO();
        // GGnavi::getGrpBO();
        // GGnavi::getClslineup2BO();
        // GGnavi::getPaymentABO();
        // GGnavi::getGrpMemberBO();
        // GGnavi::getClssettleBO();
        // GGnavi::getClzcancelBO();
    }

    /* ========================= */
    /* field */
    /*
    */
    /* ========================= */
    const FIELD__USERNO             = "userno";             /* (PK) char(30)        / NO */
    const FIELD__SCLYEAR            = "sclyear";            /* (PK) int             / NO */
    const FIELD__SCLMONTH           = "sclmonth";           /* (PK) tinyint         / NO */
    const FIELD__SCLWEEK            = "sclweek";            /* (PK) tinyint         / NO */
    const FIELD__SCLETC             = "scletc";             /* (  ) varchar(255)    / YES */
    const FIELD__SCLSUBMIT          = "sclsubmit";          /* (  ) enum('n','y')   / YES */
    const FIELD__MODIDT             = "modidt";             /* (  ) datetime        / YES */
    const FIELD__REGDT              = "regdt";              /* (  ) datetime        / YES */

    /* ========================= */
    /* select > sub > sub */
    /* ========================= */
    // public function getByPk($GRPNO, $CLSNO) { return Common::getDataOne($this->selectByPkForInside($GRPNO, $CLSNO)); }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    // public function selectByPkForInside($GRPNO, $CLSNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /* ========================= */
    // const selectBySclyear = "selectBySclyear";
    // const selectByPM3month = "selectByPM3month";
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
              t.userno
            , t.sclyear
            , t.sclmonth
            , t.sclweek
            , t.scletc
            , t.sclsubmit
            , t.modidt
            , t.regdt
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            // case self::selectBy : { $from = "(select * from schedulebyweek where sclyear = $SCLYEAR) t"; break; }
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
                  t.userno
                , t.sclyear
                , t.sclmonth
                , t.sclweek
        ";
        $result = GGsql::select($query, $from, $options);
        return $result;
    }

} /* end class */
?>
