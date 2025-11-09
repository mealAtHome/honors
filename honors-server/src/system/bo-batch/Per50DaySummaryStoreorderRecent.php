<?php

/*
    하루에 한 번, 아래 최근 X일의 데이터를 종합한다.
    - 30
    - 60
    - 90
    - 180
*/
class Per50DaySummaryStoreorderRecent extends Per00BatchBase
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
    public $batchname = "per-50-day-summaryStoreorderRecent";

    public function __construct()
    {
        GGnavi::getSummaryStoreorderEeeRecent030BO();
        GGnavi::getSummaryStoreorderEeeRecent030MenuBO();
        GGnavi::getSummaryStoreorderEeeRecent030MenuRecommendBO();
        GGnavi::getSummaryStoreorderEeeRecent030MenuoptBO();
        GGnavi::getSummaryStoreorderEeeRecent030MenuoptDetailBO();
        GGnavi::getSummaryStoreorderEeeRecent060BO();
        GGnavi::getSummaryStoreorderEeeRecent060MenuBO();
        GGnavi::getSummaryStoreorderEeeRecent060MenuRecommendBO();
        GGnavi::getSummaryStoreorderEeeRecent060MenuoptBO();
        GGnavi::getSummaryStoreorderEeeRecent060MenuoptDetailBO();
        GGnavi::getSummaryStoreorderEeeRecent090BO();
        GGnavi::getSummaryStoreorderEeeRecent090MenuBO();
        GGnavi::getSummaryStoreorderEeeRecent090MenuRecommendBO();
        GGnavi::getSummaryStoreorderEeeRecent090MenuoptBO();
        GGnavi::getSummaryStoreorderEeeRecent090MenuoptDetailBO();
        GGnavi::getSummaryStoreorderEeeRecent180BO();
        GGnavi::getSummaryStoreorderEeeRecent180MenuBO();
        GGnavi::getSummaryStoreorderEeeRecent180MenuRecommendBO();
        GGnavi::getSummaryStoreorderEeeRecent180MenuoptBO();
        GGnavi::getSummaryStoreorderEeeRecent180MenuoptDetailBO();
    }

    public function process()
    {
        /* ========================= */
        /* init */
        /* ========================= */
        $this->beforeProcess();

        /* BO */
        $systemBatchBO = SystemBatchBO::getInstance();
        $summaryStoreorderEeeRecent030BO = SummaryStoreorderEeeRecent030BO::getInstance();
        $summaryStoreorderEeeRecent030MenuBO = SummaryStoreorderEeeRecent030MenuBO::getInstance();
        $summaryStoreorderEeeRecent030MenuRecommendBO = SummaryStoreorderEeeRecent030MenuRecommendBO::getInstance();
        $summaryStoreorderEeeRecent030MenuoptBO = SummaryStoreorderEeeRecent030MenuoptBO::getInstance();
        $summaryStoreorderEeeRecent030MenuoptDetailBO = SummaryStoreorderEeeRecent030MenuoptDetailBO::getInstance();
        $summaryStoreorderEeeRecent060BO = SummaryStoreorderEeeRecent060BO::getInstance();
        $summaryStoreorderEeeRecent060MenuBO = SummaryStoreorderEeeRecent060MenuBO::getInstance();
        $summaryStoreorderEeeRecent060MenuRecommendBO = SummaryStoreorderEeeRecent060MenuRecommendBO::getInstance();
        $summaryStoreorderEeeRecent060MenuoptBO = SummaryStoreorderEeeRecent060MenuoptBO::getInstance();
        $summaryStoreorderEeeRecent060MenuoptDetailBO = SummaryStoreorderEeeRecent060MenuoptDetailBO::getInstance();
        $summaryStoreorderEeeRecent090BO = SummaryStoreorderEeeRecent090BO::getInstance();
        $summaryStoreorderEeeRecent090MenuBO = SummaryStoreorderEeeRecent090MenuBO::getInstance();
        $summaryStoreorderEeeRecent090MenuRecommendBO = SummaryStoreorderEeeRecent090MenuRecommendBO::getInstance();
        $summaryStoreorderEeeRecent090MenuoptBO = SummaryStoreorderEeeRecent090MenuoptBO::getInstance();
        $summaryStoreorderEeeRecent090MenuoptDetailBO = SummaryStoreorderEeeRecent090MenuoptDetailBO::getInstance();
        $summaryStoreorderEeeRecent180BO = SummaryStoreorderEeeRecent180BO::getInstance();
        $summaryStoreorderEeeRecent180MenuBO = SummaryStoreorderEeeRecent180MenuBO::getInstance();
        $summaryStoreorderEeeRecent180MenuRecommendBO = SummaryStoreorderEeeRecent180MenuRecommendBO::getInstance();
        $summaryStoreorderEeeRecent180MenuoptBO = SummaryStoreorderEeeRecent180MenuoptBO::getInstance();
        $summaryStoreorderEeeRecent180MenuoptDetailBO = SummaryStoreorderEeeRecent180MenuoptDetailBO::getInstance();

        /* ========================= */
        /* process */
        /* ========================= */

        /* --------------- */
        /* 1. 배치에 해당하는 시간을 생성 */
        /* --------------- */
        /* 배치 기준일자 생성 (어제 일자로 생성) */
        $summaryDate        = GGdate::subTime(GGdate::now(), "1 day");
        $summaryDateFrom030 = GGdate::subTime($summaryDate, "30 day");
        $summaryDateFrom060 = GGdate::subTime($summaryDate, "60 day");
        $summaryDateFrom090 = GGdate::subTime($summaryDate, "90 day");
        $summaryDateFrom180 = GGdate::subTime($summaryDate, "180 day");

        $summaryDateStr        = GGdate::format($summaryDate, GGdate::DATEFORMAT__YYYYMMDD);
        $summaryDateFrom030Str = GGdate::format($summaryDateFrom030, GGdate::DATEFORMAT__YYYYMMDD);
        $summaryDateFrom060Str = GGdate::format($summaryDateFrom060, GGdate::DATEFORMAT__YYYYMMDD);
        $summaryDateFrom090Str = GGdate::format($summaryDateFrom090, GGdate::DATEFORMAT__YYYYMMDD);
        $summaryDateFrom180Str = GGdate::format($summaryDateFrom180, GGdate::DATEFORMAT__YYYYMMDD);

        /* --------------- */
        /* 2. 통계시작 */
        /* --------------- */
        $summaryStoreorderEeeRecent030BO->doSummaryForInside($summaryDateFrom030Str, $summaryDateStr); /* 30일 */
        $summaryStoreorderEeeRecent030MenuBO->doSummaryForInside($summaryDateFrom030Str, $summaryDateStr); /* 30일 */
        $summaryStoreorderEeeRecent030MenuoptBO->doSummaryForInside($summaryDateFrom030Str, $summaryDateStr); /* 30일 */
        $summaryStoreorderEeeRecent030MenuoptDetailBO->doSummaryForInside($summaryDateFrom030Str, $summaryDateStr); /* 30일 */
        $summaryStoreorderEeeRecent030MenuRecommendBO->doSummaryForInside($summaryDateFrom030Str, $summaryDateStr); /* 30일 */
        $summaryStoreorderEeeRecent060BO->doSummaryForInside($summaryDateFrom060Str, $summaryDateStr); /* 60일 */
        $summaryStoreorderEeeRecent060MenuBO->doSummaryForInside($summaryDateFrom060Str, $summaryDateStr); /* 60일 */
        $summaryStoreorderEeeRecent060MenuoptBO->doSummaryForInside($summaryDateFrom060Str, $summaryDateStr); /* 60일 */
        $summaryStoreorderEeeRecent060MenuoptDetailBO->doSummaryForInside($summaryDateFrom060Str, $summaryDateStr); /* 60일 */
        $summaryStoreorderEeeRecent060MenuRecommendBO->doSummaryForInside($summaryDateFrom060Str, $summaryDateStr); /* 60일 */
        $summaryStoreorderEeeRecent090BO->doSummaryForInside($summaryDateFrom090Str, $summaryDateStr); /* 90일 */
        $summaryStoreorderEeeRecent090MenuBO->doSummaryForInside($summaryDateFrom090Str, $summaryDateStr); /* 90일 */
        $summaryStoreorderEeeRecent090MenuoptBO->doSummaryForInside($summaryDateFrom090Str, $summaryDateStr); /* 90일 */
        $summaryStoreorderEeeRecent090MenuoptDetailBO->doSummaryForInside($summaryDateFrom090Str, $summaryDateStr); /* 90일 */
        $summaryStoreorderEeeRecent090MenuRecommendBO->doSummaryForInside($summaryDateFrom090Str, $summaryDateStr); /* 90일 */
        $summaryStoreorderEeeRecent180BO->doSummaryForInside($summaryDateFrom180Str, $summaryDateStr); /* 180일 */
        $summaryStoreorderEeeRecent180MenuBO->doSummaryForInside($summaryDateFrom180Str, $summaryDateStr); /* 180일 */
        $summaryStoreorderEeeRecent180MenuoptBO->doSummaryForInside($summaryDateFrom180Str, $summaryDateStr); /* 180일 */
        $summaryStoreorderEeeRecent180MenuoptDetailBO->doSummaryForInside($summaryDateFrom180Str, $summaryDateStr); /* 180일 */
        $summaryStoreorderEeeRecent180MenuRecommendBO->doSummaryForInside($summaryDateFrom180Str, $summaryDateStr); /* 180일 */

        /* ========================= */
        /* after process */
        /* ========================= */
        $this->afterProcess();
        return true;
    }
}


?>