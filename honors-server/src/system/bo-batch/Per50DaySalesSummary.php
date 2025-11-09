<?php

/*
    일별 / 월별 / 연도별 집계

    ※ 통계용 처리이며, 삭제 후 인서트이기 때문에 트렌젝션 걸지 않음
*/
class Per50DaySalesSummary extends Per00BatchBase
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
    public $batchname = "per-50-day-salesSummary";

    public function __construct()
    {
        GGnavi::getSummaryStoreOrderaBO();
        GGnavi::getSummaryStoreOrderyBO();
        GGnavi::getSummaryStoreOrdermBO();
        GGnavi::getSummaryStoreOrderdBO();
    }

    public function process()
    {
        /* ========================= */
        /* init */
        /* ========================= */
        $this->beforeProcess();

        /* BO */
        $systemBatchBO = SystemBatchBO::getInstance();
        $summaryStoreOrderaBO = SummaryStoreOrderaBO::getInstance();
        $summaryStoreOrderyBO = SummaryStoreOrderyBO::getInstance();
        $summaryStoreOrdermBO = SummaryStoreOrdermBO::getInstance();
        $summaryStoreOrderdBO = SummaryStoreOrderdBO::getInstance();

        /* ========================= */
        /* process */
        /* ========================= */
        /*
            String SUMMARY_DATE = [YYYY-MM-DD]
                1. 집계하고자 하는 날짜임
                2. 정의되어 있지 않으면 어제날짜로 집계

            String SUMMARY_ALL = [y/n]
                - y : 전체집계
                - n / [empty] : 전체집계 아님
        */
        if(!isset($SUMMARY_DATE) || $SUMMARY_DATE == "")
        {
            $date          = GGdate::now();
            $SUMMARY_DATE  = GGdate::subTime($date, "1 day");
        }

        /* --------------- */
        /* 전체 집계인지? */
        /* --------------- */
        if(isset($SUMMARY_ALL) && $SUMMARY_ALL == GGF::Y)
        {
            $summaryStoreOrderdBO->doSummaryAll();
            $summaryStoreOrdermBO->doSummaryAll();
            $summaryStoreOrderyBO->doSummaryAll();
        }
        else
        {
            $summaryStoreOrderdBO->doSummary($SUMMARY_DATE);
            $summaryStoreOrdermBO->doSummary($SUMMARY_DATE);
            $summaryStoreOrderyBO->doSummary($SUMMARY_DATE);
        }
        $this->afterProcess();
        return true;
    }

}

?>