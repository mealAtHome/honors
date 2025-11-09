<?php

class UserEtcBO extends _CommonBO
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

    /* ========================= */
    /* fields */
    /* ========================= */
    const FIELD__USERNO             = "userno";
    const FIELD__DEPOSITORNAME      = "depositorname";
    const FIELD__CASHRECEIPT_TYPE   = "cashreceipt_type";
    const FIELD__CASHRECEIPT_NAME   = "cashreceipt_name";
    const FIELD__CASHRECEIPT_PHONE  = "cashreceipt_phone";
    const FIELD__CASHRECEIPT_CORP   = "cashreceipt_corp";

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectMine() { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /* ========================= */
    const selectByPkForInside = "selectByPkForInside";
    const selectMine = "selectMine";
    protected function select($options, $option="")
    {
        /* --------------- */
        /* get vars */
        /* --------------- */
        extract($options);

        /* overring option */
        if($option != "")
            $OPTION = $option;

        /* --------------- */
        /* make sql */
        /* --------------- */
        $query   = "";
        $select  = "";
        $from    = "";
        $select =
        "
              t.userno
            , t.depositorname
            , t.cashreceipt_type
            , t.cashreceipt_name
            , t.cashreceipt_phone
            , t.cashreceipt_corp
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPkForInside : { $from = "(select * from user_etc where userno = '$USERNO') t"; break; }
            case self::selectMine          : { $from = "(select * from user_etc where userno = '$EXECUTOR') t"; break; }
        }

        /* --------------- */
        /* complete query */
        /* --------------- */
        $query =
        "
            select
                $select
            from
                $from
        ";

        /* --------------- */
        /* execute query */
        /* --------------- */
        return GGsql::select($query, $from, $options);
    }

    /* ========================= */
    /* UPDATE / DELETE */
    /* ========================= */
    public function upsertForInside(
          $USERNO
        , $DEPOSITORNAME
        , $CASHRECEIPT_TYPE
        , $CASHRECEIPT_NAME
        , $CASHRECEIPT_PHONE
        , $CASHRECEIPT_CORP
    )
    {
        return $this->update(get_defined_vars(), __FUNCTION__);
    }

    /* ========================= */
    /* ========================= */
    const upsertForInside = "upsertForInside";
    protected function update($options, $option="")
    {
        /* vars */
        $rslt = Common::getReturn();

        /* ==================== */
        /* common validation */
        /* ==================== */
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* ==================== */
        /* process */
        /* ==================== */
        switch($OPTION)
        {
            case self::upsertForInside:
            {
                /* process */
                $query =
                "
                    insert into user_etc
                    (
                          userno
                        , depositorname
                        , cashreceipt_type
                        , cashreceipt_name
                        , cashreceipt_phone
                        , cashreceipt_corp
                    )
                    values
                    (
                          '$USERNO'
                        , '$DEPOSITORNAME'
                        , '$CASHRECEIPT_TYPE'
                        , '$CASHRECEIPT_NAME'
                        , '$CASHRECEIPT_PHONE'
                        , '$CASHRECEIPT_CORP'
                    )
                    on duplicate key update
                          depositorname      = '$DEPOSITORNAME'
                        , cashreceipt_type   = '$CASHRECEIPT_TYPE'
                        , cashreceipt_name   = '$CASHRECEIPT_NAME'
                        , cashreceipt_phone  = '$CASHRECEIPT_PHONE'
                        , cashreceipt_corp   = '$CASHRECEIPT_CORP'
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
        }
        return $rslt;
    }

} /* end class */
?>
