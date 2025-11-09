<?php

/* soptrslt : store_orderproctime_result */
class StoreOrderproctimeResultBO extends _CommonBO
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
        GGnavi::getStoreBO();
    }

    const FIELD__STORENO                = "storeno";             /* (pk) int */
    const FIELD__RANGE_TERM             = "range_term";          /* (pk) enum('today', 'month') */
    const FIELD__RANGE_HOUR             = "range_hour";          /* (pk) int */
    const FIELD__MINCNT                 = "mincnt";              /*      int */
    const FIELD__MINSUM_REG_TO_CON      = "minsum_reg_to_con";   /*      int */
    const FIELD__MINSUM_CON_TO_COM      = "minsum_con_to_com";   /*      int */
    const FIELD__MINSUM_REG_TO_COM      = "minsum_reg_to_com";   /*      int */
    const FIELD__MINAVG_REG_TO_CON      = "minavg_reg_to_con";   /*      int */
    const FIELD__MINAVG_CON_TO_COM      = "minavg_con_to_com";   /*      int */
    const FIELD__MINAVG_REG_TO_COM      = "minavg_reg_to_com";   /*      int */
    const FIELD__REGIDT                 = "regidt";              /*      datetime */

    /* ========================= */
    /* 조회 */
    /* ========================= */
    public function selectByPkForInside           ($STORENO, $RANGE_TERM, $RANGE_HOUR) { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectByStorenoForInside      ($STORENO                          ) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /*  */
    /*

    */
    /* ========================= */
    const selectByPkForInside = "selectByPkForInside";
    const selectByStorenoForInside = "selectByStorenoForInside";
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
              soptrslt.storeno
            , soptrslt.range_term
            , soptrslt.range_hour
            , soptrslt.mincnt
            , soptrslt.minsum_reg_to_con
            , soptrslt.minsum_con_to_com
            , soptrslt.minsum_reg_to_com
            , soptrslt.minavg_reg_to_con
            , soptrslt.minavg_con_to_com
            , soptrslt.minavg_reg_to_com
            , soptrslt.regidt
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPkForInside           : { $from = "(select * from store_orderproctime_result where storeno = '$STORENO' and range_term = '$RAGNE_TERM' and range_hour = $RANGE_HOUR) soptrslt"; break; }
            case self::selectByStorenoForInside   : { $from = "(select * from store_orderproctime_result where storeno = '$STORENO'                                                            ) soptrslt"; break; }
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
                  soptrslt.storeno
                , soptrslt.range_term
                , soptrslt.range_hour
        ";
        return GGsql::select($query, $from, $options);
    }

    /* ========================= */
    /* UPDATE / DELETE */
    /* ========================= */
    public function insertTodayAfterDeleteForSummaryForInside () { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function insertMonthAfterDeleteForSummaryForInside () { return $this->update(get_defined_vars(), __FUNCTION__); }

    const insertTodayAfterDeleteForSummaryForInside = "insertTodayAfterDeleteForSummaryForInside";
    const insertMonthAfterDeleteForSummaryForInside = "insertMonthAfterDeleteForSummaryForInside";
    protected function update($options, $option="")
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

            /* get BO */
            $storeBO = StoreBO::getInstance();

            /* ==================== */
            /* 사전처리 */
            /* ==================== */

            /* ==================== */
            /* process */
            /* ==================== */
            switch($OPTION)
            {
                case self::insertMonthAfterDeleteForSummaryForInside:
                {
                    /* (month) delete */
                    $query = "delete from store_orderproctime_result where range_term = 'month'";
                    $result = GGsql::exeQuery($query);

                    /* (month)(day) insert  */
                    $query =
                    "
                        insert into store_orderproctime_result
                        (
                            storeno
                            ,range_term
                            ,range_hour
                            ,mincnt
                            ,minsum_reg_to_con
                            ,minsum_con_to_com
                            ,minsum_reg_to_com
                            ,minavg_reg_to_con
                            ,minavg_con_to_com
                            ,minavg_reg_to_com
                            ,regidt
                        )
                        select
                               storeno
                            , '1month'
                            ,  24
                            ,  count(*) mincnt
                            ,  sum(minsum_reg_to_con)
                            ,  sum(minsum_con_to_com)
                            ,  sum(minsum_reg_to_com)
                            ,  avg(minsum_reg_to_con)
                            ,  avg(minsum_con_to_com)
                            ,  avg(minsum_reg_to_com)
                            ,  now()
                        from
                            store_orderproctime_log
                        group by
                            storeno
                    ";
                    $result = GGsql::exeQuery($query);

                    /* (month)(hour) insert  */
                    $query =
                    "
                        insert into store_orderproctime_result
                        (
                            storeno
                            ,range_term
                            ,range_hour
                            ,mincnt
                            ,minsum_reg_to_con
                            ,minsum_con_to_com
                            ,minsum_reg_to_com
                            ,minavg_reg_to_con
                            ,minavg_con_to_com
                            ,minavg_reg_to_com
                            ,regidt
                        )
                        select
                               storeno
                            , '1month'
                            ,  orderhour
                            ,  count(*) mincnt
                            ,  sum(minsum_reg_to_con)
                            ,  sum(minsum_con_to_com)
                            ,  sum(minsum_reg_to_com)
                            ,  avg(minsum_reg_to_con)
                            ,  avg(minsum_con_to_com)
                            ,  avg(minsum_reg_to_com)
                            ,  now()
                        from
                            store_orderproctime_log
                        group by
                              storeno
                            , orderhour
                    ";
                    $result = GGsql::exeQuery($query);

                    /* update store table */
                    $storeBO->updateFromStoreOrderproctimeResultMonthForInside();
                }
                case self::insertTodayAfterDeleteForSummaryForInside:
                {
                    /* get today */
                    $date = GGdate::now();
                    $todayYMD = GGdate::format($date, GGdate::DATEFORMAT__YYYYMMDD_NOHYPHEN);

                    /* (today) delete */
                    $query = "delete from store_orderproctime_result where range_term = 'today'";
                    $result = GGsql::exeQuery($query);

                    /* (today)(day) insert */
                    $query =
                    "
                        insert into store_orderproctime_result
                        (
                            storeno
                            ,range_term
                            ,range_hour
                            ,mincnt
                            ,minsum_reg_to_con
                            ,minsum_con_to_com
                            ,minsum_reg_to_com
                            ,minavg_reg_to_con
                            ,minavg_con_to_com
                            ,minavg_reg_to_com
                            ,regidt
                        )
                        select
                               storeno
                            , 'today'
                            ,  24
                            ,  count(*) mincnt
                            ,  sum(minsum_reg_to_con)
                            ,  sum(minsum_con_to_com)
                            ,  sum(minsum_reg_to_com)
                            ,  avg(minsum_reg_to_con)
                            ,  avg(minsum_con_to_com)
                            ,  avg(minsum_reg_to_com)
                            ,  now()
                        from
                            store_orderproctime_log
                        where
                            orderdt = $todayYMD
                        group by
                            storeno
                    ";

                    /* (today)(hour) insert */
                    $query =
                    "
                        insert into store_orderproctime_result
                        (
                            storeno
                            ,range_term
                            ,range_hour
                            ,mincnt
                            ,minsum_reg_to_con
                            ,minsum_con_to_com
                            ,minsum_reg_to_com
                            ,minavg_reg_to_con
                            ,minavg_con_to_com
                            ,minavg_reg_to_com
                            ,regidt
                        )
                        select
                               storeno
                            , 'today'
                            ,  orderhour
                            ,  count(*) mincnt
                            ,  sum(minsum_reg_to_con)
                            ,  sum(minsum_con_to_com)
                            ,  sum(minsum_reg_to_com)
                            ,  avg(minsum_reg_to_con)
                            ,  avg(minsum_con_to_com)
                            ,  avg(minsum_reg_to_com)
                            ,  now()
                        from
                            store_orderproctime_log
                        where
                            orderdt = $todayYMD
                        group by
                              storeno
                            , orderhour
                    ";
                    $result = GGsql::exeQuery($query);

                    /* update store */
                    $storeBO->updateFromStoreOrderproctimeResultTodayForInside();
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
