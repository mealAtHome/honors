<?php

class ClssettleBO extends _CommonBO
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
    const FIELD__USERNO                     = "userno";                     /* (PK) char(30) */
    const FIELD__BILLSTANDARD               = "billstandard";               /* (  ) int */
    const FIELD__BILLADJUSTMENT             = "billadjustment";             /* (  ) int */
    const FIELD__BILLPOINTED                = "billpointed";                /* (  ) int */
    const FIELD__BILLFINAL                  = "billfinal";                  /* (  ) int */
    const FIELD__BILLMEMO                   = "billmemo";                   /* (  ) varchar(100) */
    const FIELD__MEMBERDEPOSITFLG           = "memberdepositflg";           /* (  ) enum('y','n') */
    const FIELD__MEMBERDEPOSITFLGDT         = "memberdepositflgdt";         /* (  ) datetime */
    const FIELD__MANAGERDEPOSITFLG          = "managerdepositflg";          /* (  ) enum('y','n') */
    const FIELD__MANAGERDEPOSITFLGDT        = "managerdepositflgdt";        /* (  ) datetime */
    const FIELD__REGDT                      = "regdt";                      /* (  ) datetime */

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
    public function getByPk($GRPNO, $CLSNO, $USERNO) { return Common::getDataOne($this->selectByPkForInside($GRPNO, $CLSNO, $USERNO)); }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByPkForInside($GRPNO, $CLSNO, $USERNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /* ========================= */
    const selectByPkForInside = "selectByPkForInside";
    const selectByClsno = "selectByClsno";
    const selectNotDepositedByUsernoForMng = "selectNotDepositedByUsernoForMng";
    const selectNotDepositedAllByGrpnoForMng = "selectNotDepositedAllByGrpnoForMng";
    const selectMemberdepositflgYesByGrpnoForMng = "selectMemberdepositflgYesByGrpnoForMng";
    const selectNotDepositedByGrpnoForMng = "selectNotDepositedByGrpnoForMng";
    const selectNotDepositedAllByGrpnoClsnoForMng = "selectNotDepositedAllByGrpnoClsnoForMng"; /* [GRPNO, CLSNO] */
    const selectMemberdepositflgYesByGrpnoClsnoForMng = "selectMemberdepositflgYesByGrpnoClsnoForMng"; /* [GRPNO, CLSNO] */
    const selectNotDepositedByGrpnoClsnoForMng = "selectNotDepositedByGrpnoClsnoForMng"; /* [GRPNO, CLSNO] */

    const selectYetByUsernoForUsr = "selectYetByUsernoForUsr"; /* 유저메인 : 미입금전체 */
    const selectTmpByUsernoForUsr = "selectTmpByUsernoForUsr"; /* 유저메인 : 임시완료만 */
    const selectCmpByUsernoForUsr = "selectCmpByUsernoForUsr"; /* 유저메인 : 완료 */
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        extract(ClssettleBO::getConsts());
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
            , t.userno
            , t.billstandard
            , t.billadjustment
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
            case self::selectNotDepositedAllByGrpnoClsnoForMng:
            case self::selectMemberdepositflgYesByGrpnoClsnoForMng:
            case self::selectNotDepositedByGrpnoClsnoForMng:
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
            case self::selectByPkForInside                          : { $from = "(select * from clssettle where grpno = '$GRPNO' and clsno  = '$CLSNO'    and userno = '$USERNO') t"; break; }
            case self::selectByClsno                                : { $from = "(select * from clssettle where grpno = '$GRPNO' and clsno  = '$CLSNO') t"; break; }
            case self::selectNotDepositedByUsernoForMng             : { $from = "(select * from clssettle where grpno = '$GRPNO' and userno = '$USERNO'   and managerdepositflg = 'n') t"; break; }
            case self::selectNotDepositedAllByGrpnoForMng           : { $from = "(select * from clssettle where grpno = '$GRPNO'                          and managerdepositflg = 'n') t"; break; }
            case self::selectMemberdepositflgYesByGrpnoForMng       : { $from = "(select * from clssettle where grpno = '$GRPNO'                          and managerdepositflg = 'n' and memberdepositflg = 'y') t"; break; }
            case self::selectNotDepositedByGrpnoForMng              : { $from = "(select * from clssettle where grpno = '$GRPNO'                          and managerdepositflg = 'n' and memberdepositflg = 'n') t"; break; }
            case self::selectNotDepositedAllByGrpnoClsnoForMng      : { $from = "(select * from clssettle where grpno = '$GRPNO' and clsno  = '$CLSNO'    and managerdepositflg = 'n') t"; break; }
            case self::selectMemberdepositflgYesByGrpnoClsnoForMng  : { $from = "(select * from clssettle where grpno = '$GRPNO' and clsno  = '$CLSNO'    and managerdepositflg = 'n' and memberdepositflg = 'y') t"; break; }
            case self::selectNotDepositedByGrpnoClsnoForMng         : { $from = "(select * from clssettle where grpno = '$GRPNO' and clsno  = '$CLSNO'    and managerdepositflg = 'n' and memberdepositflg = 'n') t"; break; }
            case self::selectYetByUsernoForUsr                      : { $from = "(select * from clssettle where                      userno = '$EXECUTOR' and managerdepositflg = 'n' and memberdepositflg = 'n') t"; break; }
            case self::selectTmpByUsernoForUsr                      : { $from = "(select * from clssettle where                      userno = '$EXECUTOR' and managerdepositflg = 'n' and memberdepositflg = 'y') t"; break; }
            case self::selectCmpByUsernoForUsr                      : { $from = "(select * from clssettle where                      userno = '$EXECUTOR' and managerdepositflg = 'y' and regdt >= date_sub(now(), interval 1 year)) t"; break; }
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
                        t.clsno = cls.clsno
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
                , t.clsno
                , u.name
        ";
        $result = GGsql::select($query, $from, $options);
        return $result;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    public function insertForInside($GRPNO, $CLSNO, $EXECUTOR, $ARR) { return $this->update(get_defined_vars(), __FUNCTION__); }
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
        extract(ClssettleBO::getConsts());
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
                        insert into clssettle
                        (
                              grpno
                            , clsno
                            , userno
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
                            , '$CLSNO'
                            , '$USERNO'
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
            case self::updateMemberdepositflgYesForUsr:
            {
                /* is user and executor is same? */
                if($EXECUTOR != $USERNO)
                    throw new GGexception("예기치 못한 에러가 발생하였습니다.");

                /* update */
                $query =
                "
                    update
                        clssettle
                    set
                        memberdepositflg = 'y',
                        memberdepositflgdt = now()
                    where
                        grpno = '$GRPNO' and
                        clsno = '$CLSNO' and
                        userno = '$USERNO'
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
                        clssettle
                    set
                        managerdepositflg = 'y',
                        managerdepositflgdt = now()
                    where
                        grpno = '$GRPNO' and
                        clsno = '$CLSNO' and
                        userno = '$USERNO'
                ";
                GGsql::exeQuery($query);
                break;
            }
            case self::updateUsernoToTargetForInside:
            {
                $query = "update clssettle set userno = '$TARGET' where grpno = '$GRPNO' and userno = '$USERNO'";
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
