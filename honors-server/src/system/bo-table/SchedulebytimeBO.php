<?php

class SchedulebytimeBO extends _CommonBO
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

    /* ========================= */
    /* field */
    /*
    */
    /* ========================= */
    const FIELD__USERNO             = "userno";             /* (PK) char(30)        / NO */
    const FIELD__SCLYEAR            = "sclyear";            /* (PK) int             / NO */
    const FIELD__SCLMONTH           = "sclmonth";           /* (PK) tinyint         / NO */
    const FIELD__SCLWEEK            = "sclweek";            /* (PK) tinyint         / NO */
    const FIELD__SCLDATE            = "scldate";            /* (PK) date            / NO */
    const FIELD__SCLSTARTTIME       = "sclstarttime";       /* (PK) time            / NO */
    const FIELD__SCLCLOSETIME       = "sclclosetime";       /* (PK) time            / NO */
    const FIELD__SCLFREELEVEL       = "sclfreelevel";       /* (  ) tinyint         / YES */
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
    const selectByYMW = "selectByYMW";
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
            , t.scldate
            , t.sclstarttime
            , t.sclclosetime
            , t.sclfreelevel
            , t.modidt
            , t.regdt
            , scla.sclstartdate
            , scla.sclclosedate
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByYMW : { $from = "(select * from schedulebytime where sclyear = $SCLYEAR and sclmonth = $SCLMONTH and sclweek = $SCLWEEK) t"; break; }
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
                left join scheduleall scla
                    on
                        t.sclyear  = scla.sclyear and
                        t.sclmonth = scla.sclmonth and
                        t.sclweek  = scla.sclweek
            order by
                  t.userno
                , t.sclyear
                , t.sclmonth
                , t.sclweek
                , t.scldate
                , t.sclstarttime
                , t.sclclosetime
        ";
        $result = GGsql::select($query, $from, $options);
        return $result;
    }

} /* end class */
?>
