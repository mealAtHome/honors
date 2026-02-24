<?php

class GrpfncaBO extends _CommonBO
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
            case self::selectByPk          : { $from = "(select * from grpfnca where grpno = '$GRPNO') t"; break; }
            case self::selectByPkForInside : { $from = "(select * from grpfnca where grpno = '$GRPNO') t"; break; }
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
    public function makeRecordsIfNotExistsByPkForInside($GRPNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function recalGrpfncSponsorshiptotalByPkForInside($GRPNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function recalGrpfncPurchasetotalByPkForInside($GRPNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function recalGrpfncLosstotalByPkForInside($GRPNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function recalGrpfncClssalestotalByPkForInside($GRPNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function recalGrpfncClssalesunpaidtotalByPkForInside($GRPNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function recalGrpfncClssaleslosstotalByPkForInside($GRPNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function recalGrpfncClspurchasetotalByPkForInside($GRPNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function recalGrpfncAlltotalByPkForInside($GRPNO) { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    const recalByPk = "recalByPk"; /* GRPNO */
    const updateCapitaltotalByPk = "updateCapitaltotalByPk"; /* GRPNO */
    const recalByPkForInside = "recalByPkForInside"; /* GRPNO */
    const makeRecordsIfNotExistsByPkForInside = "makeRecordsIfNotExistsByPkForInside"; /* GRPNO */
    const recalGrpfncSponsorshiptotalByPkForInside = "recalGrpfncSponsorshiptotalByPkForInside"; /* GRPNO */
    const recalGrpfncPurchasetotalByPkForInside = "recalGrpfncPurchasetotalByPkForInside"; /* GRPNO */
    const recalGrpfncLosstotalByPkForInside = "recalGrpfncLosstotalByPkForInside"; /* GRPNO */
    const recalGrpfncClssalestotalByPkForInside = "recalGrpfncClssalestotalByPkForInside"; /* GRPNO */
    const recalGrpfncClssalesunpaidtotalByPkForInside = "recalGrpfncClssalesunpaidtotalByPkForInside"; /* GRPNO */
    const recalGrpfncClssaleslosstotalByPkForInside = "recalGrpfncClssaleslosstotalByPkForInside"; /* GRPNO */
    const recalGrpfncClspurchasetotalByPkForInside = "recalGrpfncClspurchasetotalByPkForInside"; /* GRPNO */
    const recalGrpfncAlltotalByPkForInside = "recalGrpfncAlltotalByPkForInside"; /* GRPNO */

    protected function update($options, $option="")
    {
        /* vars */
        $ggAuth = GGauth::getInstance();
        $result = Common::getReturn();

        /* get vars */
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* sql execution */
        switch($OPTION)
        {
            case self::recalByPk: { $ggAuth->hasGrpmfinauth($GRPNO, $EXECUTOR, true); $this->recalByPkForInside($GRPNO); break; }
            case self::updateCapitaltotalByPk:
            {
                /* BO */
                GGnavi::getGrpfnclogBO();
                $grpfnclogBO = GrpfnclogBO::getInstance();

                /* check auth */
                $ggAuth->hasGrpmfinauth($GRPNO, $EXECUTOR, true);

                /* process */
                $query =
                "
                    update
                        grpfnca gfnc
                    set
                          grpfnc_capitaltotal = $GRPFNC_CAPITALTOTAL
                        , modidt = now()
                    where
                        gfnc.grpno = '$GRPNO'
                ";
                $result = GGsql::exeQuery($query);

                /* logging */
                $grpfnclogBO->insertOfGrpfncCapitaltotalForInside($GRPNO, $GRPFNC_CAPITALTOTAL, $COMMENT);

                /* recal for grpfncCapitaltotal */
                $this->recalGrpfncAlltotalByPkForInside($GRPNO);
                break;
            }
            case self::recalByPkForInside:
            {
                $this->recalGrpfncSponsorshiptotalByPkForInside($GRPNO);
                $this->recalGrpfncPurchasetotalByPkForInside($GRPNO);
                $this->recalGrpfncLosstotalByPkForInside($GRPNO);
                $this->recalGrpfncClssalestotalByPkForInside($GRPNO);
                $this->recalGrpfncClssalesunpaidtotalByPkForInside($GRPNO);
                $this->recalGrpfncClssaleslosstotalByPkForInside($GRPNO);
                $this->recalGrpfncClspurchasetotalByPkForInside($GRPNO);
                break;
            }
            case self::makeRecordsIfNotExistsByPkForInside:
            {
                /* check has record */
                $record = Common::getDataOne($this->selectByPkForInside($GRPNO));
                if($record == null)
                {
                    $query = "insert into grpfnca (grpno, modidt) values ('$GRPNO', now())";
                    $result = GGsql::exeQuery($query);
                }
                break;
            }
            case self::recalGrpfncSponsorshiptotalByPkForInside      : $this->makeRecordsIfNotExistsByPkForInside($GRPNO); $query = "update grpfnca gfnc set modidt = now(), grpfnc_sponsorshiptotal       = (select ifnull(sum(gfsp.sponcost)      , 0) from grpfnc_sponsorship gfsp where gfsp.grpno = gfnc.grpno and gfsp.spontype = 'money')                                           where gfnc.grpno = '$GRPNO'"; $result = GGsql::exeQuery($query); $this->recalGrpfncAlltotalByPkForInside($GRPNO); break;
            case self::recalGrpfncPurchasetotalByPkForInside         : $this->makeRecordsIfNotExistsByPkForInside($GRPNO); $query = "update grpfnca gfnc set modidt = now(), grpfnc_purchasetotal          = (select ifnull(sum(gfpc.purchasecost)  , 0) from grpfnc_purchase    gfpc where gfpc.grpno = gfnc.grpno)                                                                       where gfnc.grpno = '$GRPNO'"; $result = GGsql::exeQuery($query); $this->recalGrpfncAlltotalByPkForInside($GRPNO); break;
            case self::recalGrpfncLosstotalByPkForInside             : $this->makeRecordsIfNotExistsByPkForInside($GRPNO); $query = "update grpfnca gfnc set modidt = now(), grpfnc_losstotal              = (select ifnull(sum(gfls.losscost)      , 0) from grpfnc_loss        gfls where gfls.grpno = gfnc.grpno)                                                                       where gfnc.grpno = '$GRPNO'"; $result = GGsql::exeQuery($query); $this->recalGrpfncAlltotalByPkForInside($GRPNO); break;
            case self::recalGrpfncClssalestotalByPkForInside         : $this->makeRecordsIfNotExistsByPkForInside($GRPNO); $query = "update grpfnca gfnc set modidt = now(), grpfnc_clssalestotal          = (select ifnull(sum(cls.clsbillsales)   , 0) from cls                     where cls.grpno  = gfnc.grpno and cls.grpfinancereflectflg = 'y')                                    where gfnc.grpno = '$GRPNO'"; $result = GGsql::exeQuery($query); $this->recalGrpfncAlltotalByPkForInside($GRPNO); break;
            case self::recalGrpfncClssalesunpaidtotalByPkForInside   : $this->makeRecordsIfNotExistsByPkForInside($GRPNO); $query = "update grpfnca gfnc set modidt = now(), grpfnc_clssalesunpaidtotal    = (select ifnull(sum(clss.billfinal)     , 0) from clssettle   clss        where clss.grpno = gfnc.grpno and clss.managerdepositflg = 'n')                                      where gfnc.grpno = '$GRPNO'"; $result = GGsql::exeQuery($query); $this->recalGrpfncAlltotalByPkForInside($GRPNO); break;
            case self::recalGrpfncClssaleslosstotalByPkForInside     : $this->makeRecordsIfNotExistsByPkForInside($GRPNO); $query = "update grpfnca gfnc set modidt = now(), grpfnc_clssaleslosstotal      = (select ifnull(sum(clss.billfinal)     , 0) from clssettle   clss        where clss.grpno = gfnc.grpno and clss.managerdepositflg = 'loss')                                   where gfnc.grpno = '$GRPNO'"; $result = GGsql::exeQuery($query); $this->recalGrpfncAlltotalByPkForInside($GRPNO); break;
            case self::recalGrpfncClspurchasetotalByPkForInside      : $this->makeRecordsIfNotExistsByPkForInside($GRPNO); $query = "update grpfnca gfnc set modidt = now(), grpfnc_clspurchasetotal       = (select ifnull(sum(clsp.productbill)   , 0) from clspurchase clsp        where clsp.grpno = gfnc.grpno)                                                                       where gfnc.grpno = '$GRPNO'"; $result = GGsql::exeQuery($query); $this->recalGrpfncAlltotalByPkForInside($GRPNO); break;
            case self::recalGrpfncAlltotalByPkForInside              : $this->makeRecordsIfNotExistsByPkForInside($GRPNO); $query = "update grpfnca gfnc set modidt = now(), grpfnc_alltotal               = grpfnc_sponsorshiptotal - grpfnc_purchasetotal - grpfnc_losstotal + grpfnc_clssalestotal - grpfnc_clssalesunpaidtotal - grpfnc_clssaleslosstotal - grpfnc_clspurchasetotal    where gfnc.grpno = '$GRPNO'"; $result = GGsql::exeQuery($query); break;
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }
        return $result;
    }

}
?>
