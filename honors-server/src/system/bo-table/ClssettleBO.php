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
    function setBO()
    {
        GGnavi::getClsBO();
        GGnavi::getGrpMemberBO();
        GGnavi::getGrpMemberPointhistBO();
        GGnavi::getClssettlehistBO();
        GGnavi::getClssettletmpBO();
        $arr = array();
        $arr['ggAuth'] = GGauth::getInstance();
        $arr['clsBO'] = ClsBO::getInstance();
        $arr['grpMemberBO'] = GrpMemberBO::getInstance();
        $arr['grpMemberPointhistBO'] = GrpMemberPointhistBO::getInstance();
        $arr['clssettlehistBO'] = ClssettlehistBO::getInstance();
        $arr['clssettletmpBO'] = ClssettletmpBO::getInstance();
        return $arr;
    }

    /* ========================= */
    /* fields */
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
    static public function getConsts()
    {
        $arr = array();
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
    /*
    */
    /* ========================= */
    const selectByPkForInside = "selectByPkForInside";
    const selectByClsno = "selectByClsno";
    const selectByClsnoForMng = "selectByClsnoForMng";
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
        /* vars */
        $ggAuth = GGauth::getInstance();
        extract(self::getConsts());
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
        /* sql by option */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByClsnoForMng: { $select .= ", grpm.point grpm_point"; break; }
        }

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPkForInside                          : {                                                 $from = "(select * from clssettle where grpno = '$GRPNO' and clsno  = '$CLSNO'    and userno = '$USERNO') t"; break; }
            case self::selectByClsno                                : {                                                 $from = "(select * from clssettle where grpno = '$GRPNO' and clsno  = '$CLSNO') t"; break; }
            case self::selectByClsnoForMng                          : { $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true); $from = "(select * from clssettle where grpno = '$GRPNO' and clsno  = '$CLSNO') t"; break; }
            case self::selectNotDepositedByUsernoForMng             : { $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true); $from = "(select * from clssettle where grpno = '$GRPNO' and userno = '$USERNO'   and managerdepositflg = 'n') t"; break; }
            case self::selectNotDepositedAllByGrpnoForMng           : { $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true); $from = "(select * from clssettle where grpno = '$GRPNO'                          and managerdepositflg = 'n') t"; break; }
            case self::selectMemberdepositflgYesByGrpnoForMng       : { $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true); $from = "(select * from clssettle where grpno = '$GRPNO'                          and managerdepositflg = 'n' and memberdepositflg = 'y') t"; break; }
            case self::selectNotDepositedByGrpnoForMng              : { $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true); $from = "(select * from clssettle where grpno = '$GRPNO'                          and managerdepositflg = 'n' and memberdepositflg = 'n') t"; break; }
            case self::selectNotDepositedAllByGrpnoClsnoForMng      : { $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true); $from = "(select * from clssettle where grpno = '$GRPNO' and clsno  = '$CLSNO'    and managerdepositflg = 'n') t"; break; }
            case self::selectMemberdepositflgYesByGrpnoClsnoForMng  : { $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true); $from = "(select * from clssettle where grpno = '$GRPNO' and clsno  = '$CLSNO'    and managerdepositflg = 'n' and memberdepositflg = 'y') t"; break; }
            case self::selectNotDepositedByGrpnoClsnoForMng         : { $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true); $from = "(select * from clssettle where grpno = '$GRPNO' and clsno  = '$CLSNO'    and managerdepositflg = 'n' and memberdepositflg = 'n') t"; break; }
            case self::selectYetByUsernoForUsr                      : {                                                 $from = "(select * from clssettle where                      userno = '$EXECUTOR' and managerdepositflg = 'n' and memberdepositflg = 'n') t"; break; }
            case self::selectTmpByUsernoForUsr                      : {                                                 $from = "(select * from clssettle where                      userno = '$EXECUTOR' and managerdepositflg = 'n' and memberdepositflg = 'y') t"; break; }
            case self::selectCmpByUsernoForUsr                      : {                                                 $from = "(select * from clssettle where                      userno = '$EXECUTOR' and managerdepositflg = 'y' and regdt >= date_sub(now(), interval 1 year)) t"; break; }
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
                left join grp_member grpm
                    on
                        t.grpno = grpm.grpno and
                        t.userno = grpm.userno
            order by
                  t.grpno
                , t.clsno
                , u.name
        ";
        $rslt = GGsql::select($query, $from, $options);
        return $rslt;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    public function upsertForInside($GRPNO, $CLSNO, $EXECUTOR, $ARR) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function updateUsernoToTargetForInside($GRPNO, $USERNO, $TARGET) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteByPkForInside($GRPNO, $CLSNO, $USERNO) { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    const upsertForInside = "upsertForInside";
    const updateMemberdepositflgYesForUsr = "updateMemberdepositflgYesForUsr";
    const updateManagerdepositflgYesForMng = "updateManagerdepositflgYesForMng";
    const updateUsernoToTargetForInside = "updateUsernoToTargetForInside";
    const deleteByPkForInside = "deleteByPkForInside";
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

        /* =============== */
        /* process */
        /* =============== */
        switch($OPTION)
        {
            case self::upsertForInside:
            {
                /* get cls info */
                $clsInfo = $clsBO->getByPk($GRPNO, $CLSNO);
                if($clsInfo == null)
                    throw new GGexception("존재하지 않는 일정정보입니다.");
                $clssettleflg = Common::getField($clsInfo, ClsBO::FIELD__CLSSETTLEFLG);
                $isAfterSettle = ($clssettleflg == ClsBO::CLSSETTLEFLG__DONE) ? true : false;

                /* insert */
                $arr = json_decode($ARR, true);
                foreach($arr as $dat)
                {
                    /* vars */
                    $USERNO          = $dat['USERNO'];
                    $ROW_STATUS      = $dat['ROW_STATUS'];
                    $BILLSTANDARD    = intval($dat['BILLSTANDARD']);
                    $BILLADJUSTMENT  = intval($dat['BILLADJUSTMENT']);
                    $BILLPOINTED     = intval($dat['BILLPOINTED']);
                    $BILLFINAL       = intval($dat['BILLFINAL']);
                    $BILLMEMO        = $dat['BILLMEMO'];

                    /* ===== */
                    /* 이미 등록된 레코드의 삭제 */
                    /* ===== */
                    if($ROW_STATUS == "deleted")
                    {
                        $clssettlehistBO->copyFromClssettleForInside($GRPNO, $CLSNO, $USERNO, ClssettlehistBO::HISTTYPE__DELETE, 0);
                        $this->deleteByPkForInside($GRPNO, $CLSNO, $USERNO);
                        continue;
                    }

                    /* ===== */
                    /* 기존 정보를 조회하여, 존재한다면 비교하여 업데이트 할지 판단 */
                    /* ===== */
                    $existingSettle = $this->getByPk($GRPNO, $CLSNO, $USERNO);
                    if($existingSettle != null)
                    {
                        Common::logDebug("existing settle found for GRPNO[$GRPNO] CLSNO[$CLSNO] USERNO[$USERNO]");

                        /* 내용에 변경이 있는지 확인, 메모는 따로 비교 안함 */
                        $existingBillAdjustment = intval(Common::getField($existingSettle, ClssettleBO::FIELD__BILLADJUSTMENT));
                        $existingBillPointed    = intval(Common::getField($existingSettle, ClssettleBO::FIELD__BILLPOINTED));
                        $existingBillFinal      = intval(Common::getField($existingSettle, ClssettleBO::FIELD__BILLFINAL));
                        $existingBillmemo       =   trim(Common::getField($existingSettle, ClssettleBO::FIELD__BILLMEMO));

                        /* 변경이 있다면, 업데이트 적용 */
                        if(
                            $BILLADJUSTMENT != $existingBillAdjustment ||
                            $BILLPOINTED    != $existingBillPointed    ||
                            $BILLFINAL      != $existingBillFinal      ||
                            $BILLMEMO       != $existingBillmemo
                        )
                        {
                            $clssettlehistBO->copyFromClssettleForInside($GRPNO, $CLSNO, $USERNO, ClssettlehistBO::HISTTYPE__UPDATE, $BILLFINAL);
                            $query =
                            "
                                update
                                    clssettle
                                set
                                      billstandard          =  $BILLSTANDARD
                                    , billadjustment        =  $BILLADJUSTMENT
                                    , billpointed           =  $BILLPOINTED
                                    , billfinal             =  $BILLFINAL
                                    , billmemo              = '$BILLMEMO'
                                    , memberdepositflg      = 'n'
                                    , memberdepositflgdt    =  now()
                                    , managerdepositflg     = 'n'
                                    , managerdepositflgdt   =  now()
                                where
                                    grpno = '$GRPNO' and
                                    clsno = '$CLSNO' and
                                    userno = '$USERNO'
                            ";
                            GGsql::exeQuery($query);

                            /* 포인트 사용 */
                            $pointUsed = $existingBillPointed - $BILLPOINTED;
                            if($pointUsed != 0)
                            {
                                $grpMemberBO->updatePointForInside($GRPNO, $USERNO, (-$pointUsed));
                                $grpMemberPointhistBO->insertForInside($GRPNO, $USERNO, (-$pointUsed), "일정정산으로 인한 변동", $CLSNO);
                            }
                        }

                        /* 레코드가 존재했었다면, 반복할 필요 없음 */
                        continue;
                    }

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

                    /* after settle 일 경우, 이후 이력 등록 */
                    if($isAfterSettle)
                        $clssettlehistBO->copyFromClssettleForInside($GRPNO, $CLSNO, $USERNO, ClssettlehistBO::HISTTYPE__AFTER, $BILLFINAL);

                } /* loop ARR */

                /* delete tmp */
                $clssettletmpBO->deleteByClsnoForInside($GRPNO, $CLSNO, $EXECUTOR);

                /* update clsbillsales */
                $clsBO->updateBillByPkForInside($GRPNO, $CLSNO);
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
                $ggAuth->hasGrpmfinauth($GRPNO, $EXECUTOR, true);

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
            case self::deleteByPkForInside:
            {
                $query = "delete from clssettle where grpno = '$GRPNO' and clsno = '$CLSNO' and userno = '$USERNO'";
                GGsql::exeQuery($query);
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }
        return $rslt;
    }

} /* end class */
?>
