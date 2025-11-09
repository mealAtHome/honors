<?php

/* rot */
class ReorderpctResultBO extends _CommonBO
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

    const FIELD__STORENO        = "storeno";       /* (pk) int */
    const FIELD__MENUNO         = "menuno";        /* (pk) int */
    const FIELD__CNT_ONETIME    = "cnt_onetime";   /*      int default 0 */
    const FIELD__CNT_MULTIPLE   = "cnt_multiple";  /*      int default 0 */
    const FIELD__REORDERPCT     = "reorderpct";    /*      int default 0 */

    /* ========================= */
    /* 조회 */
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
            rot.storeno
            ,rot.menuno
            ,rot.cnt_onetime
            ,rot.cnt_multiple
            ,rot.reorderpct
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
    public function insertAfterDeleteFromRocForInside() { return $this->update(get_defined_vars(), __FUNCTION__); }

    const insertAfterDeleteFromRocForInside = "insertAfterDeleteFromRocForInside";    /* 재주문율 계산을 위해 reorderpct_result 로부터 데이터 복사 */
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
                case self::insertAfterDeleteFromRocForInside:
                {
                    /* delete */
                    $query = "delete from reorderpct_result";
                    $result = GGsql::exeQuery($query);

                    /* insert */
                    $query =
                    "
                        insert into reorderpct_result
                        (
                            storeno
                            ,menuno
                        )
                        select
                            storeno
                            ,menuno
                        from
                            reorderpct_cal
                        group by
                            storeno
                            ,menuno
                    ";
                    $result = GGsql::exeQuery($query);

                    /* update (cnt_onetime) */
                    $query =
                    "
                        update
                            reorderpct_result rot
                            left join
                            (
                                select
                                    storeno
                                    ,menuno
                                    ,count(*) cnt
                                from
                                    reorderpct_cal
                                where
                                    cnt_ordercomplete = 1
                                group by
                                    storeno
                                    ,menuno
                            ) roc
                                on
                                    rot.storeno = roc.storeno and
                                    rot.menuno  = roc.menuno
                        set
                            rot.cnt_onetime = roc.cnt
                    ";
                    $result = GGsql::exeQuery($query);

                    /* update (cnt_multiple) */
                    $query =
                    "
                        update
                            reorderpct_result rot
                            left join
                            (
                                select
                                    storeno
                                    ,menuno
                                    ,count(*) cnt
                                from
                                    reorderpct_cal
                                where
                                    cnt_ordercomplete > 1
                                group by
                                    storeno
                                    ,menuno
                            ) roc
                                on
                                    rot.storeno = roc.storeno and
                                    rot.menuno  = roc.menuno
                        set
                            rot.cnt_multiple = roc.cnt
                    ";
                    $result = GGsql::exeQuery($query);

                    /* update (reorderpct) */
                    $query =
                    "
                        update
                            reorderpct_result
                        set
                            reorderpct = round(cnt_multiple / (cnt_onetime + cnt_multiple) * 100, 0)
                        where
                            cnt_onetime <> 0 and
                            cnt_multiple <> 0
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


} /* end class */
?>
