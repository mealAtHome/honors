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

    public function __construct()
    {
        GGnavi::getGrpBO();
        GGnavi::getClsBO();
        GGnavi::getUserBO();
        GGnavi::getBankaccountBO();
        GGnavi::getGrpMemberBO();
    }
    private $grpBO;
    private $clsBO;
    private $userBO;
    private $bankaccountBO;
    private $grpMemberBO;

    public function getGrpBO()         { GGnavi::getGrpBO();             $this->grpBO = GrpBO::getInstance(); }
    public function getClsBO()         { GGnavi::getClsBO();             $this->clsBO = ClsBO::getInstance(); }
    public function getUserBO()        { GGnavi::getUserBO();            $this->userBO = UserBO::getInstance(); }
    public function getBankaccountBO() { GGnavi::getBankaccountBO();     $this->bankaccountBO = BankaccountBO::getInstance(); }
    public function getGrpMemberBO()   { GGnavi::getGrpMemberBO();       $this->grpMemberBO = GrpMemberBO::getInstance(); }


    /* ========================= */
    /* get record by key */
    /* ========================= */
    public function getUser             ($USERNO)                      { $this->getUserBO();         $user        = $this->userBO->getByPk($USERNO);                              if($user         != null) { return $user;        } else { throw new GGexception("존재하지 않는 유저입니다."); } }
    public function getGrp              ($GRPNO)                       { $this->getGrpBO();          $grp         = $this->grpBO->getByPk($GRPNO);                                if($grp          != null) { return $grp;         } else { throw new GGexception("존재하지 않는 그룹입니다."); } }
    public function getCls              ($GRPNO, $CLSNO)               { $this->getClsBO();          $cls         = $this->clsBO->getByPk($GRPNO, $CLSNO);                        if($cls          != null) { return $cls;         } else { throw new GGexception("존재하지 않는 일정입니다."); } }
    public function getBankaccount      ($BACCTYPE, $BACCKEY, $BACCNO) { $this->getBankaccountBO();  $bankaccount = $this->bankaccountBO->getByPk($BACCTYPE, $BACCKEY, $BACCNO);  if($bankaccount  != null) { return $bankaccount; } else { throw new GGexception("존재하지 않는 계좌입니다."); } }

    /* ========================= */
    /* auth functions */
    /* ========================= */
    public function isGrpmanager($GRPNO, $USERNO, $errorflg=false)
    {
        /* get grpMember */
        $this->getGrpMemberBO();
        $grpMember = $this->grpMemberBO->getByPk($GRPNO, $USERNO);

        /* check grpmtype */
        $grpmtype = Common::getField($grpMember, GrpMemberBO::FIELD__GRPMTYPE);
        if($grpmtype == GrpMemberBO::GRPMTYPE__MNG)    return true;
        if($grpmtype == GrpMemberBO::GRPMTYPE__MNGSUB) return true;

        /* return */
        if($errorflg)
            throw new GGexception("그룹 관리자만 접근가능합니다.");
        return false;
    }
    public function isGrpowner($GRPNO, $USERNO, $errorflg=false)
    {
        /* get grpMember */
        $this->getGrpMemberBO();
        $grpMember = $this->grpMemberBO->getByPk($GRPNO, $USERNO);

        /* check grpmtype */
        $grpmtype = Common::getField($grpMember, GrpMemberBO::FIELD__GRPMTYPE);
        if($grpmtype == GrpMemberBO::GRPMTYPE__MNG) return true;

        /* return */
        if($errorflg)
            throw new GGexception("그룹 소유자만 접근가능합니다.");
        return false;
    }

    public function throwIfClsCancel($GRPNO, $CLSNO)
    {
        $cls = $this->getCls($GRPNO, $CLSNO);
        $clsstatus = Common::getField($cls, ClsBO::FIELD__CLSSTATUS);
        if($clsstatus == ClsBO::CLSSTATUS__CANCEL)
            throw new GGexception("취소된 일정입니다.");
        return true;
    }

    public function isClsEdit    ($GRPNO, $CLSNO, $errorflg=false) { return $this->isClsstatus($GRPNO, $CLSNO, ClsBO::CLSSTATUS__EDIT, $errorflg); }
    public function isClsIng     ($GRPNO, $CLSNO, $errorflg=false) { return $this->isClsstatus($GRPNO, $CLSNO, ClsBO::CLSSTATUS__ING, $errorflg); }
    public function isClsEndcls  ($GRPNO, $CLSNO, $errorflg=false) { return $this->isClsstatus($GRPNO, $CLSNO, ClsBO::CLSSTATUS__ENDCLS, $errorflg); }
    public function isClsCancel  ($GRPNO, $CLSNO, $errorflg=false) { return $this->isClsstatus($GRPNO, $CLSNO, ClsBO::CLSSTATUS__CANCEL, $errorflg); }
    public function isClsstatus($GRPNO, $CLSNO, $CLSSTATUS, $errorflg=false)
    {
        $cls = $this->getCls($GRPNO, $CLSNO);
        $clsstatus = Common::getField($cls, ClsBO::FIELD__CLSSTATUS);
        if($clsstatus != $CLSSTATUS)
        {
            if($errorflg)
            {
                switch($CLSSTATUS)
                {
                    case ClsBO::CLSSTATUS__EDIT   : throw new GGexception("일정 수정중일 때만 가능합니다.");
                    case ClsBO::CLSSTATUS__ING    : throw new GGexception("일정이 진행중일 때만 가능합니다.");
                    case ClsBO::CLSSTATUS__ENDCLS : throw new GGexception("일정종료 상태에서만 가능합니다.");
                    case ClsBO::CLSSTATUS__CANCEL : throw new GGexception("일정취소 상태에서만 가능합니다.");
                }
            }
            return false;
        }
        return true;
    }


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



    public function isClsInApplyDt($GRPNO, $CLSNO)
    {
        $cls = $this->getCls($GRPNO, $CLSNO);
        $applystartdt = Common::getField($cls, ClsBO::FIELD__CLSAPPLYSTARTDT);
        $applyclosedt = Common::getField($cls, ClsBO::FIELD__CLSAPPLYCLOSEDT);
        if(GGdate::isInPeriod($applystartdt, $applyclosedt) == false)
            throw new GGexception("일정 신청기간이 아닙니다.");
        return true;
    }

    public function isAdmin       ($EXECUTOR) { if(Common::getDataOneField($this->getUser($EXECUTOR), UserBO::FIELD__IS_ADMIN) != GGF::Y) throw new GGexception("관리자만 접근가능합니다."); return true; }
    public function isAdminYN     ($EXECUTOR) { if(Common::getDataOneField($this->getUser($EXECUTOR), UserBO::FIELD__IS_ADMIN) != GGF::Y) return false; return true; }

}


?>