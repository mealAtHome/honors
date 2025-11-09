<?php

/*
    매장의 주문처리 시간의 한 달 분을 통계함.
    오늘분 : 매장의 매일마다 영업 컨디션에 따라 고객이 참고할 수 있도록
    한달분 : 매장의 평소 컨디션을 고객이 알 수 있도록 함

    ※ 삭제 후, 인서트 처리이기 때문에 트렌젝션 설정 안 함
*/
class Per50DayStoreOrderproctimeSummaryMonth extends Per00BatchBase
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
    public $batchname = "per-50-day-storeOrderproctimeSummaryMonth";

    public function __construct()
    {
        GGnavi::getStoreOrderproctimeResultBO();
    }

    public function process()
    {
        $this->beforeProcess();

        /* ========================= */
        /* init */
        /* ========================= */
        $storeOrderproctimeResultBO = StoreOrderproctimeResultBO::getInstance();

        /* ========================= */
        /* process */
        /* ========================= */
        $storeOrderproctimeResultBO->insertMonthAfterDeleteForSummaryForInside();

        $this->afterProcess();
        return true;
    }
}
?>