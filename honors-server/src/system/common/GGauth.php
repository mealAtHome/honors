<?php

class GGauth
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
    /* get record by key */
    /* ========================= */
    public function getUser             ($USERNO)                      { GGnavi::getUserBO();        $userBO        = UserBO::getInstance();         $user        = $userBO->getByPk($USERNO);                              if($user         != null) { return $user;        } else { throw new GGexception("존재하지 않는 유저입니다."); } }
    public function getGrp              ($GRPNO)                       { GGnavi::getGrpBO();         $grpBO         = GrpBO::getInstance();          $grp         = $grpBO->getByPk($GRPNO);                                if($grp          != null) { return $grp;         } else { throw new GGexception("존재하지 않는 모임입니다."); } }
    public function getCls              ($GRPNO, $CLSNO)               { GGnavi::getClsBO();         $clsBO         = ClsBO::getInstance();          $cls         = $clsBO->getByPk($GRPNO, $CLSNO);                        if($cls          != null) { return $cls;         } else { throw new GGexception("존재하지 않는 일정입니다."); } }
    public function getBankaccount      ($BACCTYPE, $BACCKEY, $BACCNO) { GGnavi::getBankaccountBO(); $bankaccountBO = BankaccountBO::getInstance();  $bankaccount = $bankaccountBO->getByPk($BACCTYPE, $BACCKEY, $BACCNO);  if($bankaccount  != null) { return $bankaccount; } else { throw new GGexception("존재하지 않는 계좌입니다."); } }

    /* ========================= */
    /* auth functions */
    /* ========================= */
    public function isGrpmanager($GRPNO, $USERNO, $errorflg=false)
    {
        /* get grpMember */
        GGnavi::getUserBO();
        GGnavi::getGrpMemberBO();
        $grpMemberBO = GrpMemberBO::getInstance();
        $userBO = UserBO::getInstance();

        /* records */
        $grpmtype = Common::getField($grpMemberBO->getByPk($GRPNO, $USERNO), GrpMemberBO::FIELD__GRPMTYPE);
        $adminflg = Common::getField($userBO->getByPk($USERNO), UserBO::FIELD__ADMINFLG);

        /* check grpmtype */
        if($grpmtype == GrpMemberBO::GRPMTYPE__MNG)    return true;
        if($grpmtype == GrpMemberBO::GRPMTYPE__MNGSUB) return true;
        if($adminflg == GGF::Y) return true;

        /* return */
        if($errorflg)
            throw new GGexception("모임 관리자만 접근가능합니다.");
        return false;
    }
    public function isGrpowner($GRPNO, $USERNO, $errorflg=false)
    {
        /* bo */
        GGnavi::getUserBO();
        GGnavi::getGrpMemberBO();
        $grpMemberBO = GrpMemberBO::getInstance();
        $userBO = UserBO::getInstance();

        /* vars */
        $grpmtype = Common::getField($grpMemberBO->getByPk($GRPNO, $USERNO), GrpMemberBO::FIELD__GRPMTYPE);
        $adminflg = Common::getField($userBO->getByPk($USERNO), UserBO::FIELD__ADMINFLG);

        /* check */
        if($grpmtype == GrpMemberBO::GRPMTYPE__MNG) return true;
        if($adminflg == GGF::Y) return true;

        /* return */
        if($errorflg)
            throw new GGexception("모임 소유자만 접근가능합니다.");
        return false;
    }
    public function hasGrpmfinauth($GRPNO, $USERNO, $errorflg=false)
    {
        /* bo */
        GGnavi::getUserBO();
        GGnavi::getGrpMemberBO();
        $grpMemberBO = GrpMemberBO::getInstance();
        $userBO = UserBO::getInstance();

        /* vars */
        $grpmfinauth = Common::getField($grpMemberBO->getByPk($GRPNO, $USERNO), GrpMemberBO::FIELD__GRPMFINAUTH);
        $adminflg   = Common::getField($userBO->getByPk($USERNO), UserBO::FIELD__ADMINFLG);

        /* check */
        if($grpmfinauth == GGF::Y) return true;
        if($adminflg == GGF::Y) return true;

        /* return */
        if($errorflg)
            throw new GGexception("모임 재정 권한이 있는 사용자만 접근가능합니다.");
        return false;
    }

    /* ========================= */
    /* cls */
    /* ========================= */
    public function throwIfClsCancel($GRPNO, $CLSNO)
    {
        $cls = $this->getCls($GRPNO, $CLSNO);
        $clsstatus = Common::getField($cls, ClsBO::FIELD__CLSSTATUS);
        if($clsstatus == ClsBO::CLSSTATUS__CANCEL)
            throw new GGexception("취소된 일정입니다.");
        return true;
    }

    public function isClsEdit                   ($GRPNO, $CLSNO, $errorflg=false) { return $this->checkIqual($this->getCls($GRPNO, $CLSNO), ClsBO::FIELD__CLSSTATUS              , ClsBO::CLSSTATUS__EDIT    , ($errorflg == false ? null : "일정 수정중일 때만 가능합니다.")); }
    public function isClsIng                    ($GRPNO, $CLSNO, $errorflg=false) { return $this->checkIqual($this->getCls($GRPNO, $CLSNO), ClsBO::FIELD__CLSSTATUS              , ClsBO::CLSSTATUS__ING     , ($errorflg == false ? null : "일정이 진행중일 때만 가능합니다.")); }
    public function isClsEnd                    ($GRPNO, $CLSNO, $errorflg=false) { return $this->checkIqual($this->getCls($GRPNO, $CLSNO), ClsBO::FIELD__CLSSTATUS              , ClsBO::CLSSTATUS__END     , ($errorflg == false ? null : "일정종료 상태에서만 가능합니다.")); }
    public function isClsCancel                 ($GRPNO, $CLSNO, $errorflg=false) { return $this->checkIqual($this->getCls($GRPNO, $CLSNO), ClsBO::FIELD__CLSSTATUS              , ClsBO::CLSSTATUS__CANCEL  , ($errorflg == false ? null : "일정취소 상태에서만 가능합니다.")); }
    public function isClssetleflgEdit           ($GRPNO, $CLSNO, $errorflg=false) { return $this->checkIqual($this->getCls($GRPNO, $CLSNO), ClsBO::FIELD__CLSSETTLEFLG           , ClsBO::CLSSETTLEFLG__EDIT , ($errorflg == false ? null : "정산 대기 상태에서만 가능합니다.")); }
    public function isGrpfinancereflectflgY     ($GRPNO, $CLSNO, $errorflg=false) { return $this->checkIqual($this->getCls($GRPNO, $CLSNO), ClsBO::FIELD__GRPFINANCEREFLECTFLG   , GGF::Y                    , ($errorflg == false ? null : "모임 재정 반영이 설정되어 있지 않습니다.")); }

    public function isClsInApplyDt($GRPNO, $CLSNO)
    {
        $cls = $this->getCls($GRPNO, $CLSNO);
        $applystartdt = Common::getField($cls, ClsBO::FIELD__CLSAPPLYSTARTDT);
        $applyclosedt = Common::getField($cls, ClsBO::FIELD__CLSAPPLYCLOSEDT);
        if(GGdate::isInPeriod($applystartdt, $applyclosedt) == false)
            throw new GGexception("일정 신청기간이 아닙니다.");
        return true;
    }



    /* ========================= */
    /* user */
    /* ========================= */
    public function isBankaccountOwner($USERNO, $BACCTYPE, $BACCKEY, $BACCNO, $errorflg=false)
    {
        $bankaccount = $this->getBankaccount($BACCTYPE, $BACCKEY, $BACCNO);
        if(Common::getField($bankaccount, BankaccountBO::FIELD__BACCKEY) != $USERNO)
        {
            if($errorflg)
                throw new GGexception("계좌 소유자만 접근가능합니다.");
            return false;
        }
        return true;
    }

    public function isAdmin($EXECUTOR, $errorflg=false) { if(Common::getField($this->getUser($EXECUTOR), UserBO::FIELD__ADMINFLG) != GGF::Y) { if($errorflg) throw new GGexception("관리자만 접근가능합니다."); return false; } return true; }



    /* ========================= */
    /* common */
    /* ========================= */
    public function checkIqual($record, $field, $value, $errormsg=null)
    {
        if(Common::getField($record, $field) != $value)
        {
            if($errormsg != null)
                throw new GGexception($errormsg);
            return false;
        }
        return true;
    }

    public function checkExist($record, $errormsg=null)
    {
        if($record == null)
        {
            if($errormsg != null)
                throw new GGexception($errormsg);
            return false;
        }
        return $record;
    }

}


?>