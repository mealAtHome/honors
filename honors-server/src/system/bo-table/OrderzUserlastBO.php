<?php

/* orderz_userlast */
class OrderzUserlastBO extends _CommonBO
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
        GGnavi::getOrderingBO();
    }

    /* ========================= */
    /* const */
    /*
    */
    /* ========================= */
    const FIELD__ORDERER    = "orderer";    /* (PK)char(30) */
    const FIELD__AT_REGIST  = "at_regist";  /* (  )datetime */
    const FIELD__STORENO    = "storeno";    /* (  )char(30) */
    const FIELD__ORDERNO    = "orderno";    /* (  )char(14) */

    /* ========================= */
    /* select */
    /*
    */
    /* ========================= */
    const selectByUserno = "selectByUserno";
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        extract($options);
        $OPTION = $option != "" ? $option : $OPTION;

        /* --------------- */
        /* sql body */
        /* --------------- */
        $query  = "";
        $from   = "";
        $select =
        "
              t.orderer
            , t.at_regist
            , t.storeno
            , t.orderno
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByUserno: { $from = "(select * from orderz_userlast where userno = '$EXECUTOR') o "; break; }
            default:
            {
                throw new GGexception("(system) process failed.");
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
                  t.userno
                , t.at_regist desc
        ";
        return GGsql::select($query, $from, $options);
    }

    /* ========================= */
    /* update */
    /*
    */
    /* ========================= */
    public function insertForInside($ORDERER, $AT_REGIST, $STORENO, $ORDERNO) { return $this->insert(get_defined_vars(), __FUNCTION__); }

    const insertForInside = "insertForInside";
    protected function update($options, $option="")
    {
        /* init vars */
        $ggAuth = GGAuth::getInstance();
        extract($options);
        $OPTION = $option != "" ? $option : $OPTION;

        /* ==================== */
        /* process */
        /* ==================== */
        switch($OPTION)
        {
            case self::insertForInside:
            {
                $query =
                "
                    insert into orderz_userlast
                    (
                          orderer
                        , at_regist
                        , storeno
                        , orderno
                    )
                    values
                    (
                          '$ORDERER'
                        , '$AT_REGIST'
                        , '$STORENO'
                        , '$ORDERNO'
                    )
                ";
                GGsql::exeQuery($query);
                break;
            }
            default:
            {
                throw new GGexception("(server) error while updating order info");
            }
        }
        return true;
    }
}
?>
