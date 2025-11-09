<?php

/* csos : cnt_store_orderstatus */
class CntStoreOrderstatusBO extends _CommonBO
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
    public function __construct() {
        GGnavi::getOrderBO();
    }

    /* ========================= */
    /*  */
    /* ========================= */

    const selectByStoreno = "selectByStoreno";
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        $ggAuth = GGauth::getInstance();
        extract($options);

        /* override option */
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
            csos.storeno
            ,csos.cnt_pay
            ,csos.cnt_confirm
            ,csos.cnt_paying
            ,csos.cnt_cook
            ,csos.cnt_complete
            ,csos.cnt_cancel
            ,csos.cnt_cancelafter
            ,csos.at_update
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByStoreno:
            {
                $ggAuth->isStoreOwner($EXECUTOR, $STORENO);
                $from = "(select * from cnt_store_orderstatus where storeno = '$STORENO') csos";
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }

        /* --------------- */
        /* execute */
        /* --------------- */
        $query =
        "
            select
                $select
            from
                $from
        ";
        return GGsql::select($query, $from, $options);
    }


    /* ========================= */
    /* 집계시작 */
    /* ========================= */
    public function doSummary($EXECUTOR, $STORENO)
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        $ggAuth = GGauth::getInstance();
        extract(OrderBO::getOrderConsts());

        /* --------------- */
        /* auth */
        /* --------------- */
        $ggAuth->isStoreOwner($EXECUTOR, $STORENO);

        /* --------------- */
        /* process */
        /* --------------- */

        /* delete all data */
        $query = "delete from cnt_store_orderstatus where storeno = '$STORENO'";
        GGsql::exeQuery($query);

        /* insert store */
        $query = "insert into cnt_store_orderstatus (storeno) values ('$STORENO')";
        GGsql::exeQuery($query);

        /* update record */
        $query =
        "
            update
                cnt_store_orderstatus csos
            set
                 csos.cnt_pay          = (select count(*) from ordering o where o.storeno = csos.storeno and o.orderstatus = '$orderstatusPay')
                ,csos.cnt_confirm      = (select count(*) from ordering o where o.storeno = csos.storeno and o.orderstatus = '$orderstatusConfirm')
                ,csos.cnt_paying       = (select count(*) from ordering o where o.storeno = csos.storeno and o.orderstatus = '$orderstatusPaying')
                ,csos.cnt_cook         = (select count(*) from ordering o where o.storeno = csos.storeno and o.orderstatus = '$orderstatusCook')
                ,csos.cnt_complete     = (select count(*) from ordering o where o.storeno = csos.storeno and o.orderstatus = '$orderstatusComplete')
                ,csos.cnt_cancel       = (select count(*) from ordering o where o.storeno = csos.storeno and o.orderstatus = '$orderstatusCancel')
                ,csos.cnt_cancelafter  = (select count(*) from ordering o where o.storeno = csos.storeno and o.orderstatus = '$orderstatusCancelafter')
                ,csos.at_update        = now()
            where
                csos.storeno = '$STORENO'
        ";
        GGsql::exeQuery($query);
    }

} /* end class */
?>
