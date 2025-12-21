<?php

class GrpfSettleBO extends _CommonBO
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
    const FIELD__GRPNO                      = "grpno";                      /* (PK) char(30)       /  NO */
    const FIELD__USERNO                     = "userno";                     /* (PK) char(30)       /  NO */
    const FIELD__SETTLEDATE                 = "settledate";                 /* (PK) date           /  NO */
    const FIELD__SETTLEIDX                  = "settleidx";                  /* (PK) int            /  NO */
    const FIELD__SETTLECLSNO                = "settleclsno";                /* (  ) char(14)       /  YES */
    const FIELD__BILLSTARDARD               = "billstardard";               /* (  ) int            /  YES */
    const FIELD__BILLDISCOUNT               = "billdiscount";               /* (  ) int            /  YES */
    const FIELD__BILLPOINTED                = "billpointed";                /* (  ) int            /  YES */
    const FIELD__BILLFINAL                  = "billfinal";                  /* (  ) int            /  YES */
    const FIELD__BILLMEMO                   = "billmemo";                   /* (  ) varchar(100)   /  YES */
    const FIELD__MEMBERDEPOSITFLG           = "memberdepositflg";           /* (  ) enum('y','n')  /  YES */
    const FIELD__MEMBERDEPOSITFLGDT         = "memberdepositflgdt";         /* (  ) datetime       /  YES */
    const FIELD__MANAGERDEPOSITFLG          = "managerdepositflg";          /* (  ) enum('y','n')  /  YES */
    const FIELD__MANAGERDEPOSITFLGDT        = "managerdepositflgdt";        /* (  ) datetime       /  YES */
    const FIELD__REGDT                      = "regdt";                      /* (  ) datetime       /  YES */

    /* ========================= */
    /* enum */
    /*
    */
    /* ========================= */

    /* ========================= */
    /* select options */
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
    public function getByPk($GRPNO, $SETTLECLSNO, $USERNO) { return Common::getDataOne($this->selectByPkForInside($GRPNO, $SETTLECLSNO, $USERNO)); }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByPkForInside($GRPNO, $SETTLECLSNO, $USERNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /* ========================= */
    const selectByPkForInside = "selectByPkForInside";
    const selectByClsno = "selectByClsno";
    const selectNotDepositedByUsernoForMng = "selectNotDepositedByUsernoForMng";
    const selectNotDepositedAllByGrpnoForMng = "selectNotDepositedAllByGrpnoForMng";
    const selectMemberdepositflgYesByGrpnoForMng = "selectMemberdepositflgYesByGrpnoForMng";
    const selectNotDepositedByGrpnoForMng = "selectNotDepositedByGrpnoForMng";
    const selectYetByUsernoForUsr = "selectYetByUsernoForUsr"; /* 유저메인 : 미입금전체 */
    const selectTmpByUsernoForUsr = "selectTmpByUsernoForUsr"; /* 유저메인 : 임시완료만 */
    const selectCmpByUsernoForUsr = "selectCmpByUsernoForUsr"; /* 유저메인 : 완료 */
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        extract(GrpfSettleBO::getConsts());
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
            , t.userno
            , t.settledate
            , t.settleidx
            , t.settleclsno
            , t.billstardard
            , t.billdiscount
            , t.billpointed
            , t.billfinal
            , t.billmemo
            , t.memberdepositflg
            , t.memberdepositflgdt
            , t.managerdepositflg
            , t.managerdepositflgdt
            , t.regdt
            , u.name as username
            , u.id as userid
            , cls.clstitle
            , cls.clsstartdt
            , cls.clsclosedt
            , cls.clsground
            , grpu.id as grpmanagerid
            , grp.grpname
            , bank.bankname
            , bacc.baccacct
            , bacc.baccname
        ";

        /* --------------- */
        /* validation */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectNotDepositedByUsernoForMng:
            case self::selectNotDepositedAllByGrpnoForMng:
            case self::selectMemberdepositflgYesByGrpnoForMng:
            case self::selectNotDepositedByGrpnoForMng:
            {
                /* is grpmanager? */
                GGauth::getInstance()->isGrpmanager($GRPNO, $EXECUTOR, true);
                break;
            }
        }

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPkForInside                          : { $from = "(select * from grpf_settle where grpno = '$GRPNO' and settleclsno = '$SETTLECLSNO' and userno = '$USERNO') t"; break; }
            case self::selectByClsno                                : { $from = "(select * from grpf_settle where grpno = '$GRPNO' and settleclsno = '$SETTLECLSNO') t"; break; }
            case self::selectNotDepositedByUsernoForMng             : { $from = "(select * from grpf_settle where grpno = '$GRPNO' and userno = '$USERNO' and managerdepositflg = 'n') t"; break; }
            case self::selectNotDepositedAllByGrpnoForMng           : { $from = "(select * from grpf_settle where grpno = '$GRPNO' and managerdepositflg = 'n') t"; break; }
            case self::selectMemberdepositflgYesByGrpnoForMng       : { $from = "(select * from grpf_settle where grpno = '$GRPNO' and managerdepositflg = 'n' and memberdepositflg = 'y') t"; break; }
            case self::selectNotDepositedByGrpnoForMng              : { $from = "(select * from grpf_settle where grpno = '$GRPNO' and managerdepositflg = 'n' and memberdepositflg = 'n') t"; break; }
            case self::selectYetByUsernoForUsr                      : { $from = "(select * from grpf_settle where                      userno = '$EXECUTOR' and managerdepositflg = 'n' and memberdepositflg = 'n') t"; break; }
            case self::selectTmpByUsernoForUsr                      : { $from = "(select * from grpf_settle where                      userno = '$EXECUTOR' and managerdepositflg = 'n' and memberdepositflg = 'y') t"; break; }
            case self::selectCmpByUsernoForUsr                      : { $from = "(select * from grpf_settle where                      userno = '$EXECUTOR' and managerdepositflg = 'y' and regdt >= date_sub(now(), interval 1 year)) t"; break; }
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
                left join user u
                    on
                        t.userno = u.userno
                left join cls
                    on
                        t.grpno = cls.grpno and
                        t.settleclsno = cls.clsno
                left join grp
                    on
                        t.grpno = grp.grpno
                left join user grpu
                    on
                        grp.grpmanager = grpu.userno
                left join bankaccount bacc
                    on
                        bacc.bacctype = 'grp' and
                        bacc.bacckey = grp.grpno and
                        bacc.baccno = grp.baccnodefault
                left join _bank bank
                    on
                        bank.bankcode = bacc.bacccode
            order by
                  t.grpno
                , t.settleclsno
                , t.userno
                , t.settledate
                , t.settleidx
        ";
        $result = GGsql::select($query, $from, $options);
        return $result;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    public function insertForInside($GRPNO, $SETTLECLSNO, $EXECUTOR, $ARR) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function updateUsernoToTargetForInside($GRPNO, $USERNO, $TARGET) { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    const insertForInside = "insertForInside";
    const updateMemberdepositflgYesForUsr = "updateMemberdepositflgYesForUsr";
    const updateManagerdepositflgYesForMng = "updateManagerdepositflgYesForMng";
    const updateUsernoToTargetForInside = "updateUsernoToTargetForInside";
    protected function update($options, $option="")
    {
        /* get vars */
        extract(GrpfSettleBO::getConsts());
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
                /* set bo */
                GGnavi::getGrpMemberBO();
                GGnavi::getGrpMemberPointhistBO();
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
            case self::updateMemberdepositflgYesForUsr:
            {
                /* is user and executor is same? */
                if($EXECUTOR != $USERNO)
                    throw new GGexception("예기치 못한 에러가 발생하였습니다.");

                /* update */
                $query =
                "
                    update
                        grpf_settle
                    set
                        memberdepositflg = 'y',
                        memberdepositflgdt = now()
                    where
                        grpno = '$GRPNO' and
                        userno = '$USERNO' and
                        settleclsno = '$SETTLECLSNO'
                ";
                GGsql::exeQuery($query);
                break;
            }
            case self::updateManagerdepositflgYesForMng:
            {
                /* is manager? */
                GGauth::getInstance()->isGrpmanager($GRPNO, $EXECUTOR, true);

                /* update */
                $query =
                "
                    update
                        grpf_settle
                    set
                        managerdepositflg = 'y',
                        managerdepositflgdt = now()
                    where
                        grpno = '$GRPNO' and
                        userno = '$USERNO' and
                        settleclsno = '$SETTLECLSNO'
                ";
                GGsql::exeQuery($query);
                break;
            }
            case self::updateUsernoToTargetForInside:
            {
                $query = "update grpf_settle set userno = '$TARGET' where grpno = '$GRPNO' and userno = '$USERNO'";
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
