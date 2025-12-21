<?php

/*
    매장의 주문처리 시간의 오늘분을 통계함.
    (매장의 매일마다 영업 컨디션에 따라 고객이 참고할 수 있도록)

    ※ 삭제 후, 인서트 처리이기 때문에 트렌젝션 설정 안 함
*/
class Per40HouStoreOrderproctimeSummaryToday extends Per00BatchBase
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
    public $batchname = "per-40-hou-storeOrderproctimeSummaryToday";

    public function setBO()
    {
        GGnavi::getSystemBatchBO();
        GGnavi::getStoreOrderproctimeResultBO();
    }

    public function process()
    {
        /* ========================= */
        /* init */
        /* ========================= */
        $this->beforeProcess();
        $storeOrderproctimeResultBO = StoreOrderproctimeResultBO::getInstance();

        /* ========================= */
        /* process */
        /* ========================= */
        $storeOrderproctimeResultBO = StoreOrderproctimeResultBO::getInstance();
        $storeOrderproctimeResultBO->insertTodayAfterDeleteForSummaryForInside();

        $this->afterProcess();
        return true;
    }
}

?>