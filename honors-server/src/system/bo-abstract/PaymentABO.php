<?php

/**
 * PaymentABO.php
 * 결제 관련 Abstract BO
 */
class PaymentABO extends _CommonBO
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
    public function setBO()
    {
        GGnavi::getPaymentLogBO();
        GGnavi::getUserBO();
        $arr = array();
        $arr['paymentLogBO'] = PaymentLogBO::getInstance();
        $arr['userBO'] = UserBO::getInstance();
        return $arr;
    }

    public function payForCls($USERNO, $RELGRPNO, $RELCLSNO, $RELORDERNO, $POINT)
    {
        /* set BO */
        extract($this->setBO());

        /* check var */
        $POINT = intval($POINT);

        /* check user balance */
        $user = $userBO->getUser($USERNO);
        if($user == null)
            throw new GGexception("존재하지 않는 사용자입니다. 다시 시도하여 주세요.");
        $pointleft = intval(Common::getField($user, UserBO::FIELD__POINT, 0)) - $POINT;
        if($pointleft < 0)
            throw new GGexception("잔액이 부족합니다. 현재잔액: " . Common::getField($user, UserBO::FIELD__POINT, 0));

        /* update user point */
        $userBO->addPointForInside($USERNO, -$POINT);

        /* insert payment log */
        $paymentLogBO->insertForInside(
              $USERNO
            , PaymentLogBO::PLOGTYPE_OUT
            , PaymentLogBO::POINTTYPE_CLS
            , $RELGRPNO
            , $RELCLSNO
            , $RELORDERNO
            , $POINT
            , "일정참가비 지불"
            , "");
    }

    public function refundForCls($USERNO, $RELGRPNO, $RELCLSNO, $RELORDERNO, $POINT)
    {
        /* set BO */
        extract($this->setBO());

        /* check var */
        $POINT = intval($POINT);

        /* check user balance */
        $user = $userBO->getUser($USERNO);
        if($user == null)
            throw new GGexception("존재하지 않는 사용자입니다. 다시 시도하여 주세요.");

        /* update user point */
        $userBO->addPointForInside($USERNO, $POINT);

        /* insert payment log */
        $paymentLogBO->insertForInside(
              $USERNO
            , PaymentLogBO::PLOGTYPE_IN
            , PaymentLogBO::POINTTYPE_CLS
            , $RELGRPNO
            , $RELCLSNO
            , $RELORDERNO
            , $POINT
            , "일정참가비 반환"
            , "");
    }



} /* end class */
?>
