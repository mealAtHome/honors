<?php

class GrpformngBO extends _CommonBO
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

    /* ========================= */
    /* field */
    /*
    */
    /* ========================= */
    const FIELD__GRPNO                  = "grpno";                  /* (PK) char(30) */
    const FIELD__GRPFNC_CAPITAL         = "grpfnc_capital";         /* (  ) int */
    const FIELD__GRPFNC_SALES           = "grpfnc_sales";           /* (  ) int */
    const FIELD__GRPFNC_PURCHASE        = "grpfnc_purchase";        /* (  ) int */
    const FIELD__GRPFNC_PROFIT          = "grpfnc_profit";          /* (  ) int */
    const FIELD__MODIDT                 = "modidt";                 /* (  ) datetime */

    /* ========================= */
    /* enum */
    /*
    */
    /* ========================= */

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByPkForInside ($GRPNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /* ========================= */
    const selectByPk = "selectByPk";
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
            null as head
            , t.grpno
            , t.grpfnc_capital
            , t.grpfnc_sales
            , t.grpfnc_purchase
            , t.grpfnc_profit
            , t.modidt
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPk          : { $from = "(select * from grpformng where grpno = '$GRPNO') t"; break; }
            case self::selectByPkForInside : { $from = "(select * from grpformng where grpno = '$GRPNO') t"; break; }
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
                t.grpno asc
        ";
        $result = GGsql::select($query, $from, $options);
        return $result;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    public function recalGrpfncByPkForInside($GRPNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function addGrpfncSalesByPkForInside($GRPNO, $SALES) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function addGrpfncPurchaseByPkForInside($GRPNO, $PURCHASE) { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    const recalGrpfncByPkForInside = "recalGrpfncByPkForInside";
    const addGrpfncSalesByPkForInside = "addGrpfncSalesByPkForInside";
    const addGrpfncPurchaseByPkForInside = "addGrpfncPurchaseByPkForInside";
    protected function update($options, $option="")
    {
        /* vars */
        $ggAuth = GGauth::getInstance();
        $result = null;

        /* get vars */
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* sql execution */
        switch($OPTION)
        {
            case self::recalGrpfncByPkForInside:
            {
                /* check has record */
                $record = Common::getDataOne($this->selectByPkForInside($GRPNO));
                if($record == null)
                {
                    $query = "insert into grpformng (grpno, modidt) values ('$GRPNO', now())";
                    $result = GGsql::exeQuery($query);
                }

                /* recal sales, purchase, profit */
                $query =
                "
                    update
                        grpformng
                    set
                          grpfnc_sales     = (select ifnull(sum(cls.clsbillsales), 0)    from cls where cls.grpno = grpformng.grpno and cls.grpfinancereflectflg = 'y')
                        , grpfnc_purchase  = (select ifnull(sum(cls.clsbillpurchase), 0) from cls where cls.grpno = grpformng.grpno and cls.grpfinancereflectflg = 'y')
                        , grpfnc_profit    = grpfnc_sales - grpfnc_purchase
                    where
                        grpno = '$GRPNO'
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::addGrpfncSalesByPkForInside:
            {
                $query = "update grpformng set grpfnc_sales = grpfnc_sales + $SALES where grpno = '$GRPNO'";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::addGrpfncPurchaseByPkForInside:
            {
                $query = "update grpformng set grpfnc_purchase = grpfnc_purchase + $PURCHASE where grpno = '$GRPNO'";
                $result = GGsql::exeQuery($query);
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }
        return $result;
    }

}
?>
