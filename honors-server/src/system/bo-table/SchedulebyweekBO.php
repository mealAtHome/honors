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
    /* select */
    /*
    */
    /* ========================= */

    /* ========================= */
    /* select > sub > sub */
    /* ========================= */
    public function getByPk($USERNO, $SCLYEAR, $SCLMONTH, $SCLWEEK) { return Common::getDataOne($this->selectByPkForInside($USERNO, $SCLYEAR, $SCLMONTH, $SCLWEEK)); }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByPkForInside($USERNO, $SCLYEAR, $SCLMONTH, $SCLWEEK) { return $this->select(get_defined_vars(), __FUNCTION__); }

    // const selectBySclyear = "selectBySclyear";
    // const selectByPM3month = "selectByPM3month";
    const selectByPk = "selectByPk";
    const selectByPkForInside = "selectByPkForInside";
    const selectByPkInsertIfNotExists = "selectByPkInsertIfNotExists";
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
            , scla.sclstartdate
            , scla.sclclosedate
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPk                  : { $from = "(select * from schedulebyweek where userno = '$EXECUTOR' and sclyear = $SCLYEAR and sclmonth = $SCLMONTH and sclweek = $SCLWEEK) t"; break; }
            case self::selectByPkForInside         : { $from = "(select * from schedulebyweek where userno = '$USERNO'   and sclyear = $SCLYEAR and sclmonth = $SCLMONTH and sclweek = $SCLWEEK) t"; break; }
            case self::selectByPkInsertIfNotExists :
            {
                /* TODO : validation */

                /* 데이터가 존재하지 않으면 insert */
                $record = $this->getByPk($EXECUTOR, $SCLYEAR, $SCLMONTH, $SCLWEEK);
                if($record == null)
                    $this->insertByPkForInside($EXECUTOR, $SCLYEAR, $SCLMONTH, $SCLWEEK);

                $from = "(select * from schedulebyweek where userno = '$EXECUTOR' and sclyear = $SCLYEAR and sclmonth = $SCLMONTH and sclweek = $SCLWEEK) t";
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
        ";
        $result = GGsql::select($query, $from, $options);
        return $result;
    }

    /* ========================= */
    /* update */
    /*
    */
    /* ========================= */
    public function insertByPkForInside($USERNO, $SCLYEAR, $SCLMONTH, $SCLWEEK) { return $this->update(get_defined_vars(), __FUNCTION__); }

    const insertByPkForInside = "insertByPkForInside";
    protected function update($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        extract($options);
        // extract(self::getConsts());

        /* orderride option */
        if($option != "")
            $OPTION = $option;

        /* result */
        $rslt = Common::getReturn();

        /* ==================== */
        /* process */
        /* ==================== */
        switch($OPTION)
        {
            case self::insertByPkForInside:
            {
                /* check scheduleall */
                GGnavi::getScheduleallBO();
                $record = ScheduleallBO::getInstance()->getByPk($SCLYEAR, $SCLMONTH, $SCLWEEK);
                if($record == null)
                    throw new GGexception("(server) scheduleall record not found");

                /* insert */
                $query =
                "
                    insert into schedulebyweek
                    (
                          userno
                        , sclyear
                        , sclmonth
                        , sclweek
                        , modidt
                        , regdt
                    )
                    values
                    (
                          '$USERNO'
                        ,  $SCLYEAR
                        ,  $SCLMONTH
                        ,  $SCLWEEK
                        ,  now()
                        ,  now()
                    )
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }
        return $rslt;
    }

} /* end class */
?>
