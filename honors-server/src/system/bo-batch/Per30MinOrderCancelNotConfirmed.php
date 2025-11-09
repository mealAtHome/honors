<?php

/*
    10분 내 주문확인이 완료되지 않은 주문을 취소
*/
class Per30MinOrderCancelNotConfirmed extends Per00BatchBase
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
    public $batchname = "per-30-min-orderCancelNotConfirmed";

    public function __construct()
    {
        GGnavi::getSystemBatchBO();
        GGnavi::getOrderingBO();
    }

    public function process()
    {
        /* ========================= */
        /* init */
        /* ========================= */
        $this->beforeProcess();
        $orderingBO = OrderingBO::getInstance();

        /* ========================= */
        /* process */
        /* ========================= */
        /* get not paid list && loop */
        $dt = GGdate::format(GGdate::subTime(GGdate::now(), "10 minute"), GGdate::DATEFORMAT__YYYYMMDDHHIISS);
        $orderingList = Common::getData($orderingBO->selectConfirmOverXXminForInside($dt));
        foreach($orderingList as $ordering)
        {
            $storeno = Common::get($ordering, OrderingBO::FIELD__STORENO);
            $orderno = Common::get($ordering, OrderingBO::FIELD__ORDERNO);
            $orderingBO->updateOrderstatusConfirmToCancelForInside($storeno, $orderno, "10분 내 주문확인이 완료되지 않았으므로 주문이 취소되었습니다.");
        }

        $this->afterProcess();
        return true;
    }

}

?>