<?php

class ScheduleallBO extends _CommonBO
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
    const FIELD__SCLYEAR            = "sclyear";            /* (pk) int     / NO */
    const FIELD__SCLMONTH           = "sclmonth";           /* (pk) tinyint / NO */
    const FIELD__SCLWEEK            = "sclweek";            /* (pk) tinyint / NO */
    const FIELD__SCLSTARTDATE       = "sclstartdate";       /* (  ) date    / NO */
    const FIELD__SCLCLOSEDATE       = "sclclosedate";       /* (  ) date    / NO */

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
    const selectBySclyear = "selectBySclyear";
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
              t.sclyear
            , t.sclmonth
            , t.sclweek
            , t.sclstartdate
            , t.sclclosedate
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectBySclyear : { $from = "(select * from scheduleall where sclyear = '$SCLYEAR') t"; break; }
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
                  t.sclyear
                , t.sclmonth
                , t.sclweek
        ";
        $result = GGsql::select($query, $from, $options);
        return $result;
    }

} /* end class */
?>
