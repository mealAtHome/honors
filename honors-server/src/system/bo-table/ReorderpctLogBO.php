<?php

/* rol : reorderpct_log */
class ReorderpctLogBO extends _CommonBO
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
    public function __construct()
    {
    }

    const TABLENAME = "reorderpct_log";

    const FIELD__YEARMONTH              = "yearmonth";               /* pk */
    const FIELD__USERNO                 = "userno";                  /* pk */
    const FIELD__ADDR_INDEX             = "addr_index";              /* pk */
    const FIELD__STORENO                = "storeno";                 /* pk */
    const FIELD__MENUNO                 = "menuno";                  /* pk */
    const FIELD__AT_LASTORDERCOMPLETE   = "at_lastordercomplete";    /*  */
    const FIELD__CNT_ORDERCOMPLETE      = "cnt_ordercomplete";       /*  */

    /* ========================= */
    /* 조회 */
    /* ========================= */

    /* ========================= */
    /*  */
    /*
        ■ option
            - executor
    */
    /* ========================= */
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
            rol.yearmonth
            ,rol.userno
            ,rol.addr_index
            ,rol.storeno
            ,rol.menuno
            ,rol.at_lastordercomplete
            ,rol.cnt_ordercomplete
        ";

        /* --------------- */
        /* from */
        /* --------------- */

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
                au.userno,
                au.addr_index
        ";
        return GGsql::select($query, $from, $options);
    }

    /* ========================= */
    /* UPDATE / DELETE */
    /* ========================= */
    public function upsertCountUpByPkForInside($YEARMONTH, $USERNO, $ADDR_INDEX, $STORENO, $MENUNO) { return $this->updateReorderpctLog(get_defined_vars(), __FUNCTION__); }

    const upsertCountUpByPkForInside = "upsertCountUpByPkForInside";
    function updateReorderpctLog($options, $option="")
    {
        /* vars */
        $rslt = Common::getReturn();
        try
        {
            /* ==================== */
            /* common validation */
            /* ==================== */
            extract($options);

            /* override option */
            if($option != "")
                $OPTION = $option;

            /* ==================== */
            /* 사전처리 */
            /* ==================== */


            /* ==================== */
            /* process */
            /* ==================== */
            switch($OPTION)
            {
                case self::upsertCountUpByPkForInside:
                {
                    /* process */
                    $query =
                    "
                        insert into reorderpct_log
                        (
                            yearmonth
                            ,userno
                            ,addr_index
                            ,storeno
                            ,menuno
                            ,at_lastordercomplete
                            ,cnt_ordercomplete
                        )
                        values
                        (
                               $YEARMONTH
                            , '$USERNO'
                            ,  $ADDR_INDEX
                            ,  $STORENO
                            ,  $MENUNO
                            ,  now()
                            ,  1
                        )
                        on duplicate key update
                            at_lastordercomplete = now()
                            ,cnt_ordercomplete = cnt_ordercomplete + 1
                    ";
                    $result = GGsql::exeQuery($query);
                    break;
                }
                default:
                {
                    throw new GGexception("(server) no option defined");
                }
            } /* switch : 옵션별 처리 */
        }
        catch(Error $e)
        {
            throw $e;
        }
        return $rslt;
    }


    /* ========================= */
    /* PARTITION */
    /* ========================= */
    public function alterAddPartitionForInside ($PARTITION_NAME, $PARTITION_VALUE) { return $this->alter(get_defined_vars(), __FUNCTION__); }
    public function alterDelPartitionForInside ($PARTITION_NAME                  ) { return $this->alter(get_defined_vars(), __FUNCTION__); }

    const alterAddPartitionForInside = "alterAddPartitionForInside";
    const alterDelPartitionForInside = "alterDelPartitionForInside";
    private function alter($options, $option="")
    {
        /* vars */
        $rslt = Common::getReturn();
        try
        {
            /* ==================== */
            /* common validation */
            /* ==================== */
            extract($options);

            /* override option */
            if($option != "")
                $OPTION = $option;

            /* ==================== */
            /* 사전처리 */
            /* ==================== */

            /* ==================== */
            /* process */
            /* ==================== */
            switch($OPTION)
            {
                case self::alterAddPartitionForInside:
                {
                    $query = "ALTER TABLE reorderpct_log ADD PARTITION (PARTITION $PARTITION_NAME VALUES in ($PARTITION_VALUE))";
                    $result = GGsql::exeQuery($query);
                    break;
                }
                case self::alterDelPartitionForInside:
                {
                    $query = "ALTER TABLE reorderpct_log DROP PARTITION $PARTITION_NAME";
                    $result = GGsql::exeQuery($query);
                    break;
                }
                default:
                {
                    throw new GGexception("(server) no option defined");
                }
            } /* switch : 옵션별 처리 */
        }
        catch(Error $e)
        {
            throw $e;
        }
        return $rslt;
    }

} /* end class */
?>
