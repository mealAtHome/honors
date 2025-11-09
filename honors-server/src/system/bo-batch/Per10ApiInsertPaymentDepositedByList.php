<?php

/*
    결제완료 데이터를 DB에 입력
*/
class Per10ApiInsertPaymentDepositedByList extends Per00BatchBase
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
    public $batchname = "per-10-api-insertPaymentDepositedByList";

    public function __construct()
    {
        GGnavi::getPaymentDepositedBO();
    }

    /**
     * 결제완료 데이터를 DB에 입력
     * @param array $options 전체데이터
     */
    public function process($options)
    {
        /* ========================= */
        /* before */
        /* ========================= */
        $this->beforeProcess();
        /* vars */
        $rslt = Common::getReturn();

        /* BO */
        $paymentDepositedBO = PaymentDepositedBO::getInstance();

        /* ========================= */
        /* process */
        /* ========================= */
        $rslt = $paymentDepositedBO->insertByList($options);

        $this->afterProcess();
        return true;
    }

}
?>
