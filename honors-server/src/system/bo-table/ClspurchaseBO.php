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
    const FIELD__PRODUCTQTTY                = "productqtty";                /* (  ) int */
    const FIELD__PRODUCTBILL                = "productbill";                /* (  ) int */
    const FIELD__REGDT                      = "regdt";                      /* (  ) datetime */

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
        //     case self::selectNotDepositedByUsernoForMng             : { $from = "(select * from clspurchase where grpno = '$GRPNO' and userno = '$USERNO' and managerdepositflg = 'n') t"; break; }
        //     case self::selectNotDepositedAllByGrpnoForMng           : { $from = "(select * from clspurchase where grpno = '$GRPNO' and managerdepositflg = 'n') t"; break; }
        //     case self::selectMemberdepositflgYesByGrpnoForMng       : { $from = "(select * from clspurchase where grpno = '$GRPNO' and managerdepositflg = 'n' and memberdepositflg = 'y') t"; break; }
        //     case self::selectNotDepositedByGrpnoForMng              : { $from = "(select * from clspurchase where grpno = '$GRPNO' and managerdepositflg = 'n' and memberdepositflg = 'n') t"; break; }
        //     case self::selectYetByUsernoForUsr                      : { $from = "(select * from clspurchase where                      userno = '$EXECUTOR' and managerdepositflg = 'n' and memberdepositflg = 'n') t"; break; }
        //     case self::selectTmpByUsernoForUsr                      : { $from = "(select * from clspurchase where                      userno = '$EXECUTOR' and managerdepositflg = 'n' and memberdepositflg = 'y') t"; break; }
        //     case self::selectCmpByUsernoForUsr                      : { $from = "(select * from clspurchase where                      userno = '$EXECUTOR' and managerdepositflg = 'y' and regdt >= date_sub(now(), interval 1 year)) t"; break; }
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
        extract(ClspurchaseBO::getConsts());
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
                    $BILLSTANDARD   = intval($dat['BILLSTANDARD']);
                    $BILLADJUSTMENT   = intval($dat['BILLADJUSTMENT']);
                    $BILLPOINTED    = intval($dat['BILLPOINTED']);
                    $BILLFINAL      = intval($dat['BILLFINAL']);
                    $BILLMEMO       = $dat['BILLMEMO'];

                    /* is pointed? */
                    if($BILLPOINTED > 0)
                    {
                        $grpMemberBO->updatePointForInside($GRPNO, $USERNO, (-$BILLPOINTED));
                        $grpMemberPointhistBO->insertForInside($GRPNO, $USERNO, (-$BILLPOINTED), "일정정산으로 인한 차감", $CLSNO);
                    }

                    /* if billfinal == 0 ?, deposit complete */
                    $memberdepositflg     = ($BILLFINAL == 0) ? "'y'"   : "'n'";
                    $memberdepositflgdt   = ($BILLFINAL == 0) ? "now()" : "null";
                    $managerdepositflg    = ($BILLFINAL == 0) ? "'y'"   : "'n'";
                    $managerdepositflgdt  = ($BILLFINAL == 0) ? "now()" : "null";

                    /* insert */
                    $query =
                    "
                        insert into clspurchase
                        (
                              grpno
                            , userno
                            , settledate
                            , settleidx
                            , clsno
                            , billstandard
                            , billadjustment
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
                            , '$CLSNO'
                            ,  $BILLSTANDARD
                            ,  $BILLADJUSTMENT
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
