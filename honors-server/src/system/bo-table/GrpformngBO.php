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
    const FIELD__GRPNO                              = "grpno";                              /* (PK) char(30) */
    const FIELD__GRPFNC_CAPITALTOTAL                = "grpfnc_capitaltotal";                /* (  ) bigint */
    const FIELD__GRPFNC_SPONSORSHIPTOTAL            = "grpfnc_sponsorshiptotal";            /* (  ) bigint */
    const FIELD__GRPFNC_PURCHASETOTAL               = "grpfnc_purchasetotal";               /* (  ) bigint */
    const FIELD__GRPFNC_LOSSTOTAL                   = "grpfnc_losstotal";                   /* (  ) bigint */
    const FIELD__GRPFNC_CLSSALESTOTAL               = "grpfnc_clssalestotal";               /* (  ) bigint */
    const FIELD__GRPFNC_CLSSALESUNPAIDTOTAL         = "grpfnc_clssalesunpaidtotal";         /* (  ) bigint */
    const FIELD__GRPFNC_CLSSALESLOSSTOTAL           = "grpfnc_clssaleslosstotal";           /* (  ) bigint */
    const FIELD__GRPFNC_CLSPURCHASETOTAL            = "grpfnc_clspurchasetotal";            /* (  ) bigint */
    const FIELD__GRPFNC_ALLTOTAL                    = "grpfnc_alltotal";                    /* (  ) bigint */
    const FIELD__MODIDT                             = "modidt";                             /* (  ) datetime */

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
            , t.grpfnc_capitaltotal
            , t.grpfnc_sponsorshiptotal
            , t.grpfnc_purchasetotal
            , t.grpfnc_losstotal
            , t.grpfnc_clssalestotal
            , t.grpfnc_clssalesunpaidtotal
            , t.grpfnc_clssaleslosstotal
            , t.grpfnc_clspurchasetotal
            , t.grpfnc_alltotal
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
    public function recalByPkForInside($GRPNO) { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    const recalByPkForInside = "recalByPkForInside";
    const updateCapitaltotalByPk = "updateCapitaltotalByPk";
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
            case self::recalByPkForInside:
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
                        grpformng gfm
                    set
                          grpfnc_clssalestotal     = (select ifnull(sum(cls.clsbillsales), 0)    from cls where cls.grpno = gfm.grpno and cls.grpfinancereflectflg = 'y')
                        , grpfnc_clspurchasetotal  = (select ifnull(sum(cls.clsbillpurchase), 0) from cls where cls.grpno = gfm.grpno and cls.grpfinancereflectflg = 'y')
                        , grpfnc_alltotal          = grpfnc_clssalestotal - grpfnc_clspurchasetotal
                    where
                        gfm.grpno = '$GRPNO'
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::updateCapitaltotalByPk:
            {
                /* BO */
                GGnavi::getGrpformnglogBO();
                $grpformnglogBO = GrpformnglogBO::getInstance();

                /* process */
                $query =
                "
                    update
                        grpformng gfm
                    set
                          grpfnc_capitaltotal = $GRPFNC_CAPITALTOTAL
                        , modidt = now()
                    where
                        gfm.grpno = '$GRPNO'
                ";
                $result = GGsql::exeQuery($query);

                /* logging */
                $grpformnglogBO->insertOfGrpfncCapitaltotalForInside($GRPNO, $GRPFNC_CAPITALTOTAL, $COMMENT);
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
