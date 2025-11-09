<?php

/* roc */
class ReorderpctCalBO extends _CommonBO
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
        // GGnavi::getReorderpctResultBO();
    }

    const FIELD__USERNO              = "userno";                /* pk */
    const FIELD__ADDR_INDEX          = "addr_index";            /* pk */
    const FIELD__STORENO             = "storeno";               /* pk */
    const FIELD__MENUNO              = "menuno";                /* pk */
    const FIELD__CNT_ORDERCOMPLETE   = "cnt_ordercomplete";     /*  */

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
            ,roc.addr_index
            ,roc.storeno
            ,roc.menuno
            ,roc.at_lastordercomplete
            ,roc.cnt_ordercomplete
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
    public function deleteAllForInside                  () { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function insertAfterDeleteFromRolForInside   ($YEARMONTH) { return $this->update(get_defined_vars(), __FUNCTION__); }

    const deleteAllForInside                   = "deleteAllForInside";                   /* 재주문율 계산을 위해 레코드 전체 삭제 */
    const insertAfterDeleteFromRolForInside    = "insertAfterDeleteFromRolForInside";    /* 재주문율 계산을 위해 reorderpct_cal 로부터 데이터 복사 */
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
                case self::deleteAllForInside:
                {
                    $query = "delete from reorderpct_cal";
                    $result = GGsql::exeQuery($query);
                    break;
                }
                case self::insertAfterDeleteFromRolForInside:
                {
                    /* delete */
                    $query = "delete from reorderpct_cal";
                    $result = GGsql::exeQuery($query);

                    /* insert */
                    $query =
                    "
                        insert into reorderpct_cal
                        (
                            userno
                            ,addr_index
                            ,storeno
                            ,menuno
                            ,cnt_ordercomplete
                        )
                        select
                            userno
                            ,addr_index
                            ,storeno
                            ,menuno
                            ,sum(cnt_ordercomplete)
                        from
                            reorderpct_log
                        where
                            yearmonth in ($YEARMONTH)
                        group by
                            userno
                            ,addr_index
                            ,storeno
                            ,menuno
                    ";
                    $result = GGsql::exeQuery($query);

                    /* delete over 3 month from last order */
                    $query =
                    "
                        delete
                            roc
                        from
                            reorderpct_cal roc
                            left join user_addr au
                                on
                                    roc.userno = au.userno and
                                    roc.addr_index = au.addr_index
                        where
                            au.at_lastordercomplete < date_sub(now(), interval 3 month) or
                            au.at_lastordercomplete is null
                    ";
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
