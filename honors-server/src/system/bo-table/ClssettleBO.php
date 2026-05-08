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
    const FIELD__BILLPREPAID                = "billprepaid";                /* (  ) int */
    const FIELD__BILLADJUSTMENT             = "billadjustment";             /* (  ) int */
    const FIELD__BILLDISCOUNT               = "billdiscount";               /* (  ) int */
    const FIELD__BILLPOINTED                = "billpointed";                /* (  ) int */
    const FIELD__BILLFINAL                  = "billfinal";                  /* (  ) int */
    const FIELD__BILLMEMO                   = "billmemo";                   /* (  ) varchar(100) */
    const FIELD__SETTLESTATUS               = "settlestatus";               /* (  ) enum('wait','loss','done') */
    const FIELD__SETTLEMEMBDT               = "settlemembdt";               /* (  ) datetime */
    const FIELD__SETTLEDONEDT               = "settledonedt";               /* (  ) datetime */
    const FIELD__SETTLELOSSDT               = "settlelossdt";               /* (  ) datetime */
    const FIELD__REGDT                      = "regdt";                      /* (  ) datetime */

    /* ========================= */
    /* enum */
    /*
    */
    /* ========================= */
    const SETTLESTATUS__WAIT = "wait"; /* 정산상태 : 입금대기 */
    const SETTLESTATUS__MEMB = "memb"; /* 정산상태 : 입금완료 */
    const SETTLESTATUS__DONE = "done"; /* 정산상태 : 확인완료 */
    const SETTLESTATUS__LOSS = "loss"; /* 정산상태 : 손실 */
    static public function getConsts()
    {
        $arr = array();
        $arr['settlestatusWait'] = self::SETTLESTATUS__WAIT; /* 정산상태 : 입금대기 */
        $arr['settlestatusMemb'] = self::SETTLESTATUS__MEMB; /* 정산상태 : 입금완료 */
        $arr['settlestatusDone'] = self::SETTLESTATUS__DONE; /* 정산상태 : 확인완료 */
        $arr['settlestatusLoss'] = self::SETTLESTATUS__LOSS; /* 정산상태 : 손실 */
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
    const selectByPkForMng = "selectByPkForMng";
    const selectByClsno = "selectByClsno";
    const selectByClsnoForMng = "selectByClsnoForMng";
    const selectSettlestatusOpenByUsernoForMng = "selectSettlestatusOpenByUsernoForMng";
    const selectSettlestatusOpenByGrpnoForMng = "selectSettlestatusOpenByGrpnoForMng";
    const selectSettlestatusDoneByGrpnoForMng = "selectSettlestatusDoneByGrpnoForMng";
    const selectSettlestatusDoneByGrpnoWithPageForMng = "selectSettlestatusDoneByGrpnoWithPageForMng";
    const selectSettlestatusLossByGrpnoForMng = "selectSettlestatusLossByGrpnoForMng";
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
            , t.billprepaid
            , t.billadjustment
            , t.billdiscount
            , t.billpointed
            , t.billfinal
            , t.billmemo
            , t.settlestatus
            , t.settlemembdt
            , t.settledonedt
            , t.settlelossdt
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
            case self::selectByPkForMng                             : { $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true); $from = "(select * from clssettle where grpno = '$GRPNO' and clsno  = '$CLSNO'    and userno = '$USERNO') t"; break; }
            case self::selectByClsno                                : {                                                 $from = "(select * from clssettle where grpno = '$GRPNO' and clsno  = '$CLSNO') t"; break; }
            case self::selectByClsnoForMng                          : { $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true); $from = "(select * from clssettle where grpno = '$GRPNO' and clsno  = '$CLSNO') t"; break; }
            case self::selectSettlestatusOpenByUsernoForMng         : { $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true); $from = "(select * from clssettle where grpno = '$GRPNO' and userno = '$USERNO'   and settlestatus in ('$settlestatusWait', '$settlestatusMemb') ) t"; break; }
            case self::selectSettlestatusOpenByGrpnoForMng          : { $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true); $from = "(select * from clssettle where grpno = '$GRPNO'                          and settlestatus in ('$settlestatusWait', '$settlestatusMemb') ) t"; break; }
            case self::selectSettlestatusDoneByGrpnoForMng          : { $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true); $from = "(select * from clssettle where grpno = '$GRPNO'                          and settlestatus = '$settlestatusDone') t"; break; }
            case self::selectSettlestatusDoneByGrpnoWithPageForMng  : { $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true); $from = "(select * from clssettle where grpno = '$GRPNO'                          and settlestatus = '$settlestatusDone') t"; break; }
            case self::selectSettlestatusLossByGrpnoForMng          : { $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true); $from = "(select * from clssettle where grpno = '$GRPNO'                          and settlestatus = '$settlestatusLoss') t"; break; }
            case self::selectYetByUsernoForUsr                      : {                                                 $from = "(select * from clssettle where                      userno = '$EXECUTOR' and settlestatus = '$settlestatusWait') t"; break; }
            case self::selectTmpByUsernoForUsr                      : {                                                 $from = "(select * from clssettle where                      userno = '$EXECUTOR' and settlestatus = '$settlestatusMemb') t"; break; }
            case self::selectCmpByUsernoForUsr                      : {                                                 $from = "(select * from clssettle where                      userno = '$EXECUTOR' and settlestatus = '$settlestatusDone' and regdt >= date_sub(now(), interval 1 year)) t"; break; }
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
        $rslt = GGsql::select($query, $from, $options, $OPTION);
        return $rslt;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    public function completeSettlementFromTmp($GRPNO, $CLSNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function updateUsernoToTargetForInside($GRPNO, $USERNO, $TARGET) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteByPkForInside($GRPNO, $CLSNO, $USERNO) { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    const completeSettlementFromTmp = "completeSettlementFromTmp";
    const updateSettlestatusToMembForUsr = "updateSettlestatusToMembForUsr";
    const updateSettlestatusToDoneForFnc = "updateSettlestatusToDoneForFnc";
    const updateSettlestatusToLossForFnc = "updateSettlestatusToLossForFnc";
    const updateSettlestatusToWaitForFnc = "updateSettlestatusToWaitForFnc";
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
        /* validation */
        /* =============== */
        switch($OPTION)
        {
            case self::completeSettlementFromTmp: break;
            case self::updateSettlestatusToMembForUsr: $ggAuth->checkMe($EXECUTOR, $USERNO, true); break;
            case self::updateSettlestatusToDoneForFnc: $ggAuth->hasGrpmfinauth($GRPNO, $EXECUTOR, true); break;
            case self::updateSettlestatusToLossForFnc: $ggAuth->hasGrpmfinauth($GRPNO, $EXECUTOR, true); break;
            case self::updateSettlestatusToWaitForFnc: $ggAuth->hasGrpmfinauth($GRPNO, $EXECUTOR, true); break;
            case self::updateUsernoToTargetForInside: break;
            case self::deleteByPkForInside: break;
        }


        /* =============== */
        /* process */
        /* =============== */
        switch($OPTION)
        {
            case self::completeSettlementFromTmp:
            {
                /* get clssettletmp */
                $clssettletmps = Common::getData($clssettletmpBO->selectByClsnoForInside($GRPNO, $CLSNO));

                /* insert into clssettle */
                foreach($clssettletmps as $record)
                {
                    /* vars */
                    $GRPNO                =        Common::getField($record, ClssettletmpBO::FIELD__GRPNO);
                    $CLSNO                =        Common::getField($record, ClssettletmpBO::FIELD__CLSNO);
                    $USERNO               =        Common::getField($record, ClssettletmpBO::FIELD__USERNO);
                    $BILLSTANDARD         = intval(Common::getField($record, ClssettletmpBO::FIELD__BILLSTANDARD));
                    $BILLPREPAID          = intval(Common::getField($record, ClssettletmpBO::FIELD__BILLPREPAID));
                    $BILLADJUSTMENT       = intval(Common::getField($record, ClssettletmpBO::FIELD__BILLADJUSTMENT));
                    $BILLDISCOUNT         = intval(Common::getField($record, ClssettletmpBO::FIELD__BILLDISCOUNT));
                    $BILLPOINTED          = intval(Common::getField($record, ClssettletmpBO::FIELD__BILLPOINTED));
                    $BILLFINAL            = intval(Common::getField($record, ClssettletmpBO::FIELD__BILLFINAL));
                    $BILLMEMO             =        Common::getField($record, ClssettletmpBO::FIELD__BILLMEMO);

                    /* is pointed? */
                    if($BILLPOINTED > 0)
                        $grpMemberBO->updatePointForInside($GRPNO, $USERNO, (-$BILLPOINTED), "일정정산으로 인한 차감", $CLSNO);

                    /* if billfinal == 0 ?, deposit complete */
                    $settlestatus        = ($BILLFINAL == 0) ? self::SETTLESTATUS__DONE : self::SETTLESTATUS__WAIT;
                    $settledonedt = ($BILLFINAL == 0) ? "now()" : "null";

                    /* insert */
                    $query =
                    "
                        insert into clssettle
                        (
                              grpno
                            , clsno
                            , userno
                            , billstandard
                            , billprepaid
                            , billadjustment
                            , billdiscount
                            , billpointed
                            , billfinal
                            , billmemo
                            , settlestatus
                            , settledonedt
                            , regdt
                        )
                        values
                        (
                              '$GRPNO'
                            , '$CLSNO'
                            , '$USERNO'
                            ,  $BILLSTANDARD
                            ,  $BILLPREPAID
                            ,  $BILLADJUSTMENT
                            ,  $BILLDISCOUNT
                            ,  $BILLPOINTED
                            ,  $BILLFINAL
                            , '$BILLMEMO'
                            , '$settlestatus'
                            ,  $settledonedt
                            ,  now()
                        )
                    ";
                    GGsql::exeQuery($query);
                }

                /* delete tmp */
                $clssettletmpBO->deleteByClsnoForInside($GRPNO, $CLSNO);

                /* update clsbillsales */
                $clsBO->updateBillByPkForInside($GRPNO, $CLSNO);
                break;
            }
            case self::updateSettlestatusToMembForUsr: { $query = "update clssettle set settlestatus = '$settlestatusMemb', settlemembdt = now() where grpno = '$GRPNO' and clsno = '$CLSNO' and userno = '$USERNO'"; GGsql::exeQuery($query); break; }
            case self::updateSettlestatusToDoneForFnc: { $query = "update clssettle set settlestatus = '$settlestatusDone', settledonedt = now() where grpno = '$GRPNO' and clsno = '$CLSNO' and userno = '$USERNO'"; GGsql::exeQuery($query); break; }
            case self::updateSettlestatusToLossForFnc: { $query = "update clssettle set settlestatus = '$settlestatusLoss', settlelossdt = now() where grpno = '$GRPNO' and clsno = '$CLSNO' and userno = '$USERNO'"; GGsql::exeQuery($query); break; }
            case self::updateSettlestatusToWaitForFnc:
            {
                /* validation : in 5 minutes from now */
                $clssettle = $this->getByPk($GRPNO, $CLSNO, $USERNO);
                $settledonedt = Common::getField($clssettle, self::FIELD__SETTLEDONEDT);
                if($settledonedt == null)
                    throw new GGexception("예기치 못한 에러가 발생하였습니다.");

                if(GGdate::isIn5MinFromNow($settledonedt) == false)
                    throw new GGexception("5분이 지나면 취소할 수 없습니다.");

                /* update */
                $query = "update clssettle set settlestatus = '$settlestatusWait', settledonedt = null where grpno = '$GRPNO' and clsno = '$CLSNO' and userno = '$USERNO'";
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
