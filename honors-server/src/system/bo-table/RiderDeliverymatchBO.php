<?php

class RiderDeliverymatchBO extends _CommonBO
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

    const FIELD__STORENO    = "storeno";        /* (pk) char(30) */
    const FIELD__ORDERNO    = "orderno";        /* (pk) char(14) */
    const FIELD__RIDERNO    = "riderno";        /* (pk) char(30) */
    const FIELD__AT_REGIST  = "at_regist";      /*      datetime */

    /* ========================= */
    /*  */
    /*
    */
    /* ========================= */
    public function selectByPkForInside($STORENO, $ORDERNO, $RIDERNO) { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectForProcess() { return $this->select(get_defined_vars(), __FUNCTION__); }

    const selectByPkForInside = "selectByPkForInside";
    const selectForProcess = "selectForProcess"; /* 배달원 매칭처리를 위한 조회 at_regist 로 100건씩 조회 */
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
        $limit  = "";
        $select =
        "
              t.storeno
            , t.orderno
            , t.riderno
            , t.at_regist
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPkForInside:
            {
                $from =
                "
                    (
                        select
                            *
                        from
                            rider_deliverymatch
                        where
                            storeno = '$STORENO' and
                            orderno = '$ORDERNO' and
                            riderno = '$RIDERNO'
                    ) t
                ";
                break;
            }
            case self::selectForProcess:
            {
                $from =
                "
                    (
                        select
                            *
                        from
                            rider_deliverymatch
                    ) t
                ";
                $limit = "limit 100";
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
            order by
                t.at_regist
            $limit
        ";
        return GGsql::select($query, $from, $options);
    }


    /* ==================== */
    /* 주문 업데이트 */
    /* ==================== */
    public function insertForInside($STORENO, $ORDERNO, $RIDERNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteByPkForInside($STORENO, $ORDERNO, $RIDERNO) { return $this->update(get_defined_vars(), __FUNCTION__); }

    const insertForInside = "insertForInside";
    const deleteByPkForInside = "deleteByPkForInside";
    protected function update($options, $option="")
    {
        /* vars */

        /* data object */

        /* get vars */
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* ==================== */
        /* process */
        /* ==================== */
        switch($OPTION)
        {
            case self::insertForInside:
            {
                $query =
                "
                    insert into rider_deliverymatch
                    (
                         storeno
                        ,orderno
                        ,riderno
                        ,at_regist
                    )
                    values
                    (
                          '$STORENO'
                        , '$ORDERNO'
                        , '$RIDERNO'
                        , now()
                    )
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::deleteByPkForInside:
            {
                $query =
                "
                    delete from rider_deliverymatch
                    where
                        storeno = '$STORENO' and
                        orderno = '$ORDERNO' and
                        riderno = '$RIDERNO'
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            default:
            {
                throw new GGexception("(server) error while updating order info");
            }
        }
        return true;
    }

} /* end class */
?>
