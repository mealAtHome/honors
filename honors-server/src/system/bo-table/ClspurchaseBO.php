<?php

class ClspurchaseBO extends _CommonBO
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
    const FIELD__GRPNO                      = "grpno";                      /* (PK) char(30) */
    const FIELD__CLSNO                      = "clsno";                      /* (PK) char(14) */
    const FIELD__PURCHASEIDX                = "purchaseidx";                /* (PK) int */
    const FIELD__PRODUCTNAME                = "productname";                /* (  ) int */
    const FIELD__PRODUCTBILL                = "productbill";                /* (  ) int */
    const FIELD__PURCHASEMEMO               = "purchasememo";               /* (  ) varchar(100) */
    const FIELD__REGDT                      = "regdt";                      /* (  ) datetime */

    /* ========================= */
    /* validation */
    /*
    */
    /* ========================= */
    static public function validProductbill($bill)
    {
        if(!is_numeric($bill))
            throw new GGexception("상품금액은 숫자만 입력가능합니다.");
        if(intval($bill) < 0)
            throw new GGexception("상품금액은 0원 이상만 입력가능합니다.");
    }

    static public function validProductname($name)
    {
        if(mb_strlen($name) > 50)
            throw new GGexception("상품명은 50자 이하로 입력가능합니다.");
        if(mb_strlen($name) == 0)
            throw new GGexception("상품명을 입력하여 주세요.");
    }

    /* ========================= */
    /* enum */
    /*
    */
    /* ========================= */

    /* ========================= */
    /* get consts */
    /* ========================= */
    static public function getConsts()
    {
        $arr = array();
        // $arr['clsstatusEdit']                   = self::CLSSTATUS__EDIT;        /* 일정상태 : 작성중 */
        // $arr['clsstatusIng']                    = self::CLSSTATUS__ING;         /* 일정상태 : 진행중 */
        // $arr['clsstatusEnd']                 = self::CLSSTATUS__END;      /* 일정상태 : 일정완료 */
        return $arr;
    }

    /* ========================= */
    /* select > sub > sub */
    /* ========================= */
    // public function selectB($GRPNO, $PURCHASECLSNO, $USERNO) { return Common::getDataOne($this->selectByPkForInside($GRPNO, $PURCHASECLSNO, $USERNO)); }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByPkForInside($GRPNO, $CLSNO, $PURCHASEIDX) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /* ========================= */
    const selectByPkForInside = "selectByPkForInside";
    const selectByClsno = "selectByClsno";
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        extract(ClspurchaseBO::getConsts());
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
            , t.clsno
            , t.purchaseidx
            , t.productname
            , t.productbill
            , t.purchasememo
            , t.regdt
        ";

        /* --------------- */
        /* validation */
        /* --------------- */
        // switch($OPTION)
        // {
        //     case self::selectNotDepositedByUsernoForMng:
        //     case self::selectNotDepositedAllByGrpnoForMng:
        //     case self::selectMemberdepositflgYesByGrpnoForMng:
        //     case self::selectNotDepositedByGrpnoForMng:
        //     {
        //         /* is grpmanager? */
        //         $ggAuth = GGauth::getInstance();
        //         $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true);
        //         break;
        //     }
        // }

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPkForInside      : { $from = "(select * from clspurchase where grpno = '$GRPNO' and clsno = '$CLSNO' and purchaseidx = '$PURCHASEIDX') t"; break; }
            case self::selectByClsno            : { $from = "(select * from clspurchase where grpno = '$GRPNO' and clsno = '$CLSNO') t"; break; }
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
                t.grpno,
                t.clsno,
                t.purchaseidx asc
        ";
        $result = GGsql::select($query, $from, $options);
        return $result;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    // public function updateUsernoToTargetForInside($GRPNO, $USERNO, $TARGET) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteByClsnoForInside($GRPNO, $CLSNO) { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    const insertByArr = "insertByArr";
    const deleteByClsnoForInside = "deleteByClsnoForInside";
    protected function update($options, $option="")
    {
        /* get vars */
        extract(ClspurchaseBO::getConsts());
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* =============== */
        /* auth validation */
        /* =============== */
        switch($OPTION)
        {
            case self::insertByArr:
            {
                /* is grpmanager? */
                $ggAuth = GGauth::getInstance();
                $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true);
                break;
            }
        }

        /* =============== */
        /* sql execution */
        /* =============== */
        switch($OPTION)
        {
            case self::insertByArr:
            {
                /* bo */
                GGnavi::getClsBO();
                GGnavi::getClspurchasehistBO();
                $clsBO = ClsBO::getInstance();
                $clspurchasehistBO = ClspurchasehistBO::getInstance();

                /* var */
                $arr = json_decode($ARR, true);

                /* get max */
                $cls = $clsBO->getByPk($GRPNO, $CLSNO);
                $purchaseidxMaxused = intval(Common::getField($cls, ClsBO::FIELD__PURCHASEIDX_MAXUSED));

                /* process */
                foreach($arr as $dat)
                {
                    $PURCHASEIDX = intval($dat['PURCHASEIDX']);
                    $DELETEFLG   = $dat['DELETEFLG'];
                    $PRODUCTNAME = $dat['PRODUCTNAME'];
                    $PRODUCTBILL = intval($dat['PRODUCTBILL']);
                    $PURCHASEMEMO = $dat['PURCHASEMEMO'];

                    /* validation */
                    self::validProductname($PRODUCTNAME);
                    self::validProductbill($PRODUCTBILL);

                    /* check clspurchase is exists */
                    if($PURCHASEIDX > 0)
                    {
                        $clspurchaseOrigin = Common::getDataOne($this->selectByPkForInside($GRPNO, $CLSNO, $PURCHASEIDX));
                        $clspurchaseOriginProductname =        Common::getField($clspurchaseOrigin, self::FIELD__PRODUCTNAME);
                        $clspurchaseOriginProductbill = intval(Common::getField($clspurchaseOrigin, self::FIELD__PRODUCTBILL));
                        $clspurchaseOriginPurchasememo =       Common::getField($clspurchaseOrigin, self::FIELD__PURCHASEMEMO);
                        if($clspurchaseOrigin != null)
                        {
                            switch($DELETEFLG)
                            {
                                case "y":
                                {
                                    /* delete */
                                    $clspurchasehistBO->copyFromClspurchaseForInside($GRPNO, $CLSNO, $PURCHASEIDX, ClspurchasehistBO::HISTTYPE__DELETE, 0);
                                    $query = "delete from clspurchase where grpno = '$GRPNO' and clsno = '$CLSNO' and purchaseidx =  $PURCHASEIDX";
                                    GGsql::exeQuery($query);
                                    break;
                                }
                                case "n":
                                {
                                    /* check content is changed */
                                    if($clspurchaseOriginProductname == $PRODUCTNAME && $clspurchaseOriginProductbill == $PRODUCTBILL && $clspurchaseOriginPurchasememo == $PURCHASEMEMO)
                                        continue;

                                    /* update */
                                    $clspurchasehistBO->copyFromClspurchaseForInside($GRPNO, $CLSNO, $PURCHASEIDX, ClspurchasehistBO::HISTTYPE__UPDATE, $PRODUCTBILL);
                                    $query = "update clspurchase set productname = '$PRODUCTNAME', productbill = $PRODUCTBILL, purchasememo = '$PURCHASEMEMO' where grpno = '$GRPNO' and clsno = '$CLSNO' and purchaseidx =  $PURCHASEIDX";
                                    GGsql::exeQuery($query);
                                    break;
                                }
                            }
                            /* 삽입처리 하지 않음 */
                            continue;
                        }
                    }

                    /* skip if deleteflg == y */
                    if($DELETEFLG == "y")
                        continue;

                    /* get purchaseidx lastest */
                    $purchaseidxMaxused++;

                    /* insert */
                    $query =
                    "
                        insert into clspurchase
                        (
                              grpno
                            , clsno
                            , purchaseidx
                            , productname
                            , productbill
                            , purchasememo
                            , regdt
                        )
                        values
                        (
                              '$GRPNO'
                            , '$CLSNO'
                            ,  $purchaseidxMaxused
                            , '$PRODUCTNAME'
                            ,  $PRODUCTBILL
                            , '$PURCHASEMEMO'
                            ,  now()
                        )
                    ";
                    GGsql::exeQuery($query);

                    /* regist hist */
                    $clspurchasehistBO->copyFromClspurchaseForInside($GRPNO, $CLSNO, $purchaseidxMaxused, ClspurchasehistBO::HISTTYPE__INSERT, $PRODUCTBILL);

                    /* update purchaseidx_maxused */
                    $clsBO->updatePurchaseidxMaxused($GRPNO, $CLSNO, $purchaseidxMaxused);
                }
                break;
            }
            case self::deleteByClsnoForInside:
            {
                $query = "delete from clspurchase where grpno = '$GRPNO' and clsno = '$CLSNO'";
                GGsql::exeQuery($query);
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }
    }

} /* end class */
?>
