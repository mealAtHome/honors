<?php

class GrpfPurchaseBO extends _CommonBO
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
    const FIELD__GRPNO                      = "grpno";                      /* (PK) char(30)  / NO */
    const FIELD__PURCHASEDATE               = "purchasedate";               /* (PK) date      / NO */
    const FIELD__PURCHASEIDX                = "purchaseidx";                /* (PK) int       / NO */
    const FIELD__PURCHASECLSNO              = "purchaseclsno";              /* (  ) char(14)  / YES */
    const FIELD__PRODUCTNAME                = "productname";                /* (  ) int       / YES */
    const FIELD__PRODUCTQTTY                = "productqtty";                /* (  ) int       / YES */
    const FIELD__PRODUCTBILL                = "productbill";                /* (  ) int       / YES */
    const FIELD__REGDT                      = "regdt";                      /* (  ) datetime  / YES */

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
        // $arr['clsstatusEndcls']                 = self::CLSSTATUS__ENDCLS;      /* 일정상태 : 일정완료 */
        // $arr['clsstatusEndsettle']              = self::CLSSTATUS__ENDSETTLE;   /* 일정상태 : 정산완료 */
        return $arr;
    }

    /* ========================= */
    /* select > sub > sub */
    /* ========================= */
    // public function getByPk($GRPNO, $PURCHASECLSNO, $USERNO) { return Common::getDataOne($this->selectByPkForInside($GRPNO, $PURCHASECLSNO, $USERNO)); }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    // public function selectByPkForInside($GRPNO, $PURCHASECLSNO, $USERNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /* ========================= */
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        extract(GrpfPurchaseBO::getConsts());
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
            , t.purchasedate
            , t.purchaseidx
            , t.purchaseclsno
            , t.productname
            , t.productqtty
            , t.productbill
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
        // switch($OPTION)
        // {
        //     case self::selectByPkForInside                          : { $from = "(select * from grpf_settle where grpno = '$GRPNO' and settleclsno = '$SETTLECLSNO' and userno = '$USERNO') t"; break; }
        //     case self::selectByClsno                                : { $from = "(select * from grpf_settle where grpno = '$GRPNO' and settleclsno = '$SETTLECLSNO') t"; break; }
        //     case self::selectNotDepositedByUsernoForMng             : { $from = "(select * from grpf_settle where grpno = '$GRPNO' and userno = '$USERNO' and managerdepositflg = 'n') t"; break; }
        //     case self::selectNotDepositedAllByGrpnoForMng           : { $from = "(select * from grpf_settle where grpno = '$GRPNO' and managerdepositflg = 'n') t"; break; }
        //     case self::selectMemberdepositflgYesByGrpnoForMng       : { $from = "(select * from grpf_settle where grpno = '$GRPNO' and managerdepositflg = 'n' and memberdepositflg = 'y') t"; break; }
        //     case self::selectNotDepositedByGrpnoForMng              : { $from = "(select * from grpf_settle where grpno = '$GRPNO' and managerdepositflg = 'n' and memberdepositflg = 'n') t"; break; }
        //     case self::selectYetByUsernoForUsr                      : { $from = "(select * from grpf_settle where                      userno = '$EXECUTOR' and managerdepositflg = 'n' and memberdepositflg = 'n') t"; break; }
        //     case self::selectTmpByUsernoForUsr                      : { $from = "(select * from grpf_settle where                      userno = '$EXECUTOR' and managerdepositflg = 'n' and memberdepositflg = 'y') t"; break; }
        //     case self::selectCmpByUsernoForUsr                      : { $from = "(select * from grpf_settle where                      userno = '$EXECUTOR' and managerdepositflg = 'y' and regdt >= date_sub(now(), interval 1 year)) t"; break; }
        //     default:
        //     {
        //         throw new GGexception("(server) no option defined");
        //     }
        // }

        /* --------------- */
        /* exe query */
        /* --------------- */
        $query =
        "
            select
                $select
            from
                $from
        ";
        $result = GGsql::select($query, $from, $options);
        return $result;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    // public function insertForInside($GRPNO, $SETTLECLSNO, $EXECUTOR, $ARR) { return $this->update(get_defined_vars(), __FUNCTION__); }
    // public function updateUsernoToTargetForInside($GRPNO, $USERNO, $TARGET) { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    // const insertForInside = "insertForInside";
    // const updateMemberdepositflgYesForUsr = "updateMemberdepositflgYesForUsr";
    // const updateManagerdepositflgYesForMng = "updateManagerdepositflgYesForMng";
    // const updateUsernoToTargetForInside = "updateUsernoToTargetForInside";
    protected function update($options, $option="")
    {
        /* get vars */
        extract(GrpfPurchaseBO::getConsts());
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* =============== */
        /* sql execution */
        /* =============== */
        switch($OPTION)
        {
            case self::insertForInside:
            {
                /* get BO */
                $grpMemberBO = GrpMemberBO::getInstance();
                $grpMemberPointhistBO = GrpMemberPointhistBO::getInstance();

                /* insert */
                $arr = json_decode($ARR, true);
                foreach($arr as $dat)
                {
                    /* vars */
                    $USERNO         = $dat['USERNO'];
                    $BILLSTARDARD   = intval($dat['BILLSTARDARD']);
                    $BILLDISCOUNT   = intval($dat['BILLDISCOUNT']);
                    $BILLPOINTED    = intval($dat['BILLPOINTED']);
                    $BILLFINAL      = intval($dat['BILLFINAL']);
                    $BILLMEMO       = $dat['BILLMEMO'];

                    /* is pointed? */
                    if($BILLPOINTED > 0)
                    {
                        $grpMemberBO->updatePointForInside($GRPNO, $USERNO, (-$BILLPOINTED));
                        $grpMemberPointhistBO->insertForInside($GRPNO, $USERNO, (-$BILLPOINTED), "일정정산으로 인한 차감", $SETTLECLSNO);
                    }

                    /* if billfinal == 0 ?, deposit complete */
                    $memberdepositflg     = ($BILLFINAL == 0) ? "'y'"   : "'n'";
                    $memberdepositflgdt   = ($BILLFINAL == 0) ? "now()" : "null";
                    $managerdepositflg    = ($BILLFINAL == 0) ? "'y'"   : "'n'";
                    $managerdepositflgdt  = ($BILLFINAL == 0) ? "now()" : "null";

                    /* insert */
                    $query =
                    "
                        insert into grpf_settle
                        (
                              grpno
                            , userno
                            , settledate
                            , settleidx
                            , settleclsno
                            , billstardard
                            , billdiscount
                            , billpointed
                            , billfinal
                            , billmemo
                            , memberdepositflg
                            , memberdepositflgdt
                            , managerdepositflg
                            , managerdepositflgdt
                            , regdt
                        )
                        values
                        (
                              '$GRPNO'
                            , '$USERNO'
                            ,  now()
                            ,  1
                            , '$SETTLECLSNO'
                            ,  $BILLSTARDARD
                            ,  $BILLDISCOUNT
                            ,  $BILLPOINTED
                            ,  $BILLFINAL
                            , '$BILLMEMO'
                            ,  $memberdepositflg
                            ,  $memberdepositflgdt
                            ,  $managerdepositflg
                            ,  $managerdepositflgdt
                            ,  now()
                        )
                    ";
                    GGsql::exeQuery($query);
                }
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
