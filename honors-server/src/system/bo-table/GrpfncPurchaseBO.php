<?php

/* grpfnc_purchase, gfpc */
class GrpfncPurchaseBO extends _CommonBO
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
    function setBO()
    {
        GGnavi::getGrpfncaBO();
        $arr = array();
        $arr['ggAuth'] = GGauth::getInstance();
        $arr['grpfncaBO'] = GrpfncaBO::getInstance();
        return $arr;
    }

    /* ========================= */
    /* fields */
    /*
    */
    /* ========================= */
    const FIELD__GRPNO              = "grpno";              /* (PK) char(30) */
    const FIELD__PURCHASEIDX        = "purchaseidx";        /* (PK) int */
    const FIELD__PURCHASEITEM       = "purchaseitem";       /* (  ) varchar(50) */
    const FIELD__PURCHASECOST       = "purchasecost";       /* (  ) int */
    const FIELD__PURCHASECOMMENT    = "purchasecomment";    /* (  ) varchar(255) */
    const FIELD__REGDT              = "regdt";              /* (  ) datetime */

    /* ========================= */
    /* enum */
    /*
    */
    /* ========================= */
    static public function getConsts()
    {
        $arr = array();
        // $arr['key'] = "value";
        return $arr;
    }

    /* ========================= */
    /* select > sub > sub */
    /* ========================= */
    // public function getByPk($GRPNO) { return GGsql::selectOne("select * from grp where grpno = '$GRPNO'"); }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    // public function selectByPkForInside ($GRPNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /*
    */
    /* ========================= */
    const selectByPk = "selectByPk";
    const selectByGrpnoPagenum = "selectByGrpnoPagenum";
    protected function select($options, $option="")
    {
        /* vars */
        $rslt = Common::getReturn();
        extract($this->setBO());
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
            null as head
            , t.grpno
            , t.purchaseidx
            , t.purchaseitem
            , t.purchasecost
            , t.purchasecomment
            , t.regdt
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPk            : { $from = "(select * from grpfnc_purchase where grpno = '$GRPNO' and purchaseidx = $PURCHASEIDX) t"; break; }
            case self::selectByGrpnoPagenum  : { $from = "(select * from grpfnc_purchase where grpno = '$GRPNO') t"; break; }
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
                , t.purchaseidx desc
        ";
        $rslt = GGsql::select($query, $from, $options);
        return $rslt;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    /* public function changeStoreStatus($STORENO, $STORE_STATUS)     { return $this->update(get_defined_vars(), __FUNCTION__); } */
    /* public function updateBaccnodefaultForInside($GRPNO, $BACCNODEFAULT) { return $this->update(get_defined_vars(), __FUNCTION__); } */

    /* ========================= */
    /* update */
    /* ========================= */
    const insertFromPage = "insertFromPage";
    const deleteByPk = "deleteByPk";
    /* const updateBaccnodefaultForInside = "updateBaccnodefaultForInside"; */
    protected function update($options, $option="")
    {
        /* vars */
        $rslt = Common::getReturn();
        extract($this->setBO());
        extract(self::getConsts());
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* process */
        switch($OPTION)
        {
            case self::insertFromPage:
            {
                /* validation */
                if(Common::isEmpty($GRPNO))        { throw new GGexception("시스템 오류입니다."); }
                if(Common::isEmpty($PURCHASEITEM))     { throw new GGexception("구매품목이 공란입니다."); }
                if(Common::isEmpty($PURCHASECOST))     { throw new GGexception("구매금액이 공란입니다."); }

                /* process */
                $query =
                "
                    insert into grpfnc_purchase
                    (
                          grpno
                        , purchaseidx
                        , purchaseitem
                        , purchasecost
                        , purchasecomment
                        , regdt
                    )
                    select
                          '$GRPNO'
                        ,  (select ifnull(max(purchaseidx), 0) + 1 from grpfnc_purchase where grpno = '$GRPNO')
                        , '$PURCHASEITEM'
                        ,  $PURCHASECOST
                        , '$PURCHASECOMMENT'
                        , now()
                    from
                        dual
                ";
                $rslt = GGsql::exeQuery($query);

                /* recal */
                $grpfncBO->recalGrpfncPurchasetotalByPkForInside($GRPNO);
                break;
            }
            case self::deleteByPk:
            {
                /* validation */
                if(Common::isEmpty($GRPNO))        { throw new GGexception("시스템 오류입니다."); }
                if(Common::isEmpty($PURCHASEIDX))  { throw new GGexception("시스템 오류입니다."); }

                /* validation logic */
                $isAdmin = $ggAuth->isAdmin($EXECUTOR, false);
                $hasAuth = $ggAuth->hasGrpmfinauth($GRPNO, $EXECUTOR, true);
                if(!$isAdmin)
                {
                    $regdt = Common::getDataOneField($this->selectByPkForInside($GRPNO, $PURCHASEIDX), self::FIELD__REGDT);
                    if(GGdate::diffHour($regdt) > 72)
                        throw new GGexception("구매내역은 등록 후 72시간까지만 삭제할 수 있습니다.");
                }

                /* process */
                $query = "delete from grpfnc_purchase where grpno = '$GRPNO' and purchaseidx = $PURCHASEIDX";
                $rslt = GGsql::exeQuery($query);

                /* recal */
                $grpfncBO->recalGrpfncPurchasetotalByPkForInside($GRPNO);
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }
        return $rslt;
    }

}
?>
