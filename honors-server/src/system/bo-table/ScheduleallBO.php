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
    public function getByPk($SCLYEAR, $SCLMONTH, $SCLWEEK) { return Common::getDataOne($this->selectByPkForInside($SCLYEAR, $SCLMONTH, $SCLWEEK)); }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByPkForInside($SCLYEAR, $SCLMONTH, $SCLWEEK) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /* ========================= */
    const selectByPk = "selectByPk";
    const selectByPkForInside = "selectByPkForInside";
    const selectByPM3month = "selectByPM3month";
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
        $additionalSelect = "";
        $additionalJoin = "";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPk          : { $from = "(select * from scheduleall where sclyear = $SCLYEAR and sclmonth = $SCLMONTH and sclweek = $SCLWEEK) t"; break; }
            case self::selectByPkForInside : { $from = "(select * from scheduleall where sclyear = $SCLYEAR and sclmonth = $SCLMONTH and sclweek = $SCLWEEK) t"; break; }
            case self::selectByPM3month :
            {
                $additionalSelect =
                "
                    , sclw.sclsubmit
                ";
                $additionalJoin =
                "
                    left join schedulebyweek sclw
                        on
                            sclw.userno = '$EXECUTOR' and
                            t.sclyear = sclw.sclyear and
                            t.sclmonth = sclw.sclmonth and
                            t.sclweek  = sclw.sclweek
                ";
                $from = "(select * from scheduleall where sclstartdate > DATE_SUB(CURDATE(), INTERVAL 3 MONTH) and sclclosedate < DATE_ADD(CURDATE(), INTERVAL 3 MONTH)) t";
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
                $additionalSelect
            from
                $from
                $additionalJoin
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
