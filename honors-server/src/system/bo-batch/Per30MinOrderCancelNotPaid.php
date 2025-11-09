<?php

/*
    30분 내 입금이 이루어지지 않은 주문을 취소
*/
class Per30MinOrderCancelNotPaid extends Per00BatchBase
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
    public $batchname = "per-30-min-orderCancelNotPaid";

    public function __construct()
    {
        GGnavi::getPaymentQueueBO();
        GGnavi::getOrderingBO();
    }

    public function process()
    {
        /* ========================= */
        /* init */
        /* ========================= */
        $this->beforeProcess();
        $systemBatchBO = SystemBatchBO::getInstance();
        $paymentQueueBO = PaymentQueueBO::getInstance();
        $orderingBO = OrderingBO::getInstance();

        /* ========================= */
        /* process */
        /* ========================= */
        /* get not paid list && loop */
        $dt = GGdate::format(GGdate::subTime(GGdate::now(), "30 minute"), GGdate::DATEFORMAT__YYYYMMDDHHIISS);
        $paymentQueueNotPaidList = Common::getData($paymentQueueBO->selectNotPaidBeforeDateForInside($dt));
        foreach($paymentQueueNotPaidList as $paymentQueue)
        {
            $storeno = Common::get($paymentQueue, PaymentQueueBO::FIELD__STORENO);
            $orderno = Common::get($paymentQueue, PaymentQueueBO::FIELD__ORDERNO);
            $orderingBO->updateOrderstatusToCancelBecauseNotPaidForInside($storeno, $orderno, "30분 내 결제가 완료되지 않았으므로 주문이 취소되었습니다.");
        }
        $this->afterProcess();
        return true;
    }
}
?>