<?php

/* soptlog : store_orderproctime_log */
class StoreOrderproctimeLogBO extends _CommonBO
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
        GGnavi::getOrderaBO();
    }

    const TABLENAME = "store_orderproctime_log";

    const FIELD__ORDERDT                = "orderdt";              /* (pk) int */
    const FIELD__ORDERHOUR              = "orderhour";            /* (pk) int */
    const FIELD__STORENO                = "storeno";              /* (pk) int */
    const FIELD__MINCNT                 = "mincnt";               /*      int */
    const FIELD__MINSUM_REG_TO_CON      = "minsum_reg_to_con";    /*      int */
    const FIELD__MINSUM_CON_TO_COM      = "minsum_con_to_com";    /*      int */
    const FIELD__MINSUM_REG_TO_COM      = "minsum_reg_to_com";    /*      int */

    /* ========================= */
    /* 조회 */
    /* ========================= */
    public function selectByPkForInside($ORDERDT, $STORENO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /*  */
    /*
        ■ option
            - executor
    */
    /* ========================= */
    const selectByPkForInside = "selectByPkForInside";
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
              soptlog.orderdt
            , soptlog.orderhour
            , soptlog.storeno
            , soptlog.mincnt
            , soptlog.minsum_reg_to_con
            , soptlog.minsum_con_to_com
            , soptlog.minsum_reg_to_com
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPkForInside : { $from = "(select * from store_orderproctime_log where orderdt = $ORDERDT and storeno = '$STORENO') soptlog"; break; }
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
                soptlog.orderdt,
                soptlog.storeno
        ";
        return GGsql::select($query, $from, $options);
    }

    /* ========================= */
    /* UPDATE / DELETE */
    /* ========================= */
    public function insertFromOrderForInside ($STORENO, $ORDERNO) { return $this->update(get_defined_vars(), __FUNCTION__); }

    const insertFromOrderForInside = "insertFromOrderForInside";
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

            /* ==================== */
            /* 사전처리 */
            /* ==================== */

            /* ==================== */
            /* process */
            /* ==================== */
            switch($OPTION)
            {
                case self::insertFromOrderForInside:
                {
                    /* ----- */
                    /* get orderInfo */
                    /* ----- */
                    $orderaBO = OrderaBO::getInstance();
                    $order = Common::getDataOne($orderaBO->selectByPkForInside($STORENO, $ORDERNO));
                    if($order == null)
                        throw new GGexception("no order");

                    /* ----- */
                    /* insert record */
                    /* ----- */
                    $query =
                    "
                        insert into store_orderproctime_log
                        (
                              orderdt
                            , orderhour
                            , storeno
                            , mincnt
                            , minsum_reg_to_con
                            , minsum_con_to_com
                            , minsum_reg_to_com
                        )
                        select
                            torderdt
                            , torderhour
                            , tstoreno
                            , tmincnt
                            , tminsum_reg_to_con
                            , tminsum_con_to_com
                            , tminsum_reg_to_com
                        from
                            (
                                select
                                      date_format(at_regist, '%Y%m%d') torderdt
                                    , date_format(at_regist, '%H') torderhour
                                    , storeno tstoreno
                                    , 1 tmincnt
                                    , coalesce(TIMESTAMPDIFF(MINUTE, at_orderstatus_confirm, at_orderstatus_cook)    , 0) tminsum_reg_to_con
                                    , coalesce(TIMESTAMPDIFF(MINUTE, at_orderstatus_cook   , at_orderstatus_complete), 0) tminsum_con_to_com
                                    , coalesce(TIMESTAMPDIFF(MINUTE, at_orderstatus_confirm, at_orderstatus_complete), 0) tminsum_reg_to_com
                                from
                                    ordera
                                where
                                    storeno             = '$STORENO' and
                                    orderno                 = '$ORDERNO' and
                                    at_orderstatus_confirm  is not null and
                                    at_orderstatus_cook     is not null and
                                    at_orderstatus_complete is not null
                            ) t
                        on duplicate key update
                             mincnt = mincnt + 1
                            ,minsum_reg_to_con = minsum_reg_to_con + t.tminsum_reg_to_con
                            ,minsum_con_to_com = minsum_con_to_com + t.tminsum_con_to_com
                            ,minsum_reg_to_com = minsum_reg_to_com + t.tminsum_reg_to_com
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
    public function alterAddPartitionForInside ($partitionName, $partitionValue) { return $this->alter(array("PARTITION_NAME"=>$partitionName, "PARTITION_VALUE"=>$partitionValue), self::alterAddPartitionForInside); }
    public function alterDelPartitionForInside ($partitionName                 ) { return $this->alter(array("PARTITION_NAME"=>$partitionName,                                   ), self::alterDelPartitionForInside); }

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
                    $query = "ALTER TABLE store_orderproctime_log ADD PARTITION (PARTITION $PARTITION_NAME VALUES in ($PARTITION_VALUE))";
                    $result = GGsql::exeQuery($query);
                    break;
                }
                case self::alterDelPartitionForInside:
                {
                    $query = "ALTER TABLE store_orderproctime_log DROP PARTITION $PARTITION_NAME";
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
