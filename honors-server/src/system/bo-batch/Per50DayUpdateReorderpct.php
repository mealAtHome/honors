<?php

/*
    재주문율 계산 및 업데이트

    ※ 통계용 처리이며, 삭제 후 인서트이기 때문에 트렌젝션 걸지 않음
*/
class Per50DayUpdateReorderpct extends Per00BatchBase
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
    public $batchname = "per-50-day-updateReorderpct";

    public function __construct()
    {
        GGnavi::getStoreBO();
        GGnavi::getMenuBO();
        GGnavi::getReorderpctCalBO();
        GGnavi::getReorderpctResultBO();
    }

    public function process()
    {
        /* ========================= */
        /* init */
        /* ========================= */
        $this->beforeProcess();

        /* BO */
        $storeBO = StoreBO::getInstance();
        $menuBO = MenuBO::getInstance();
        $reorderpctCalBO = ReorderpctCalBO::getInstance();
        $reorderpctResultBO = ReorderpctResultBO::getInstance();

        /* ========================= */
        /* process */
        /* ========================= */

        /* ----- */
        /* 재주문율 통계 시작 */
        /*
            3개월 재주문율 : 현 시점 이번달 ~ 3개월 전 데이터를 집계
            집계대상의 예시:
                2024.  1 : 현재 (2024. 1. 2)
                2023. 12 : -1개월
                2023. 11 : -2개월
                2023. 10 : -3개월
        */
        /* ----- */

        /* ----- */
        /* 처리대상의 ym을 추출 */
        /* ----- */
        $now = GGdate::now();
        $ym00 = GGdate::format($now                              , GGDate::DATEFORMAT__YYYYMM_NOHYPHEN); /*  3개월분 */
        $ym01 = GGdate::format(GGdate::subTime($now, "1 month")  , GGDate::DATEFORMAT__YYYYMM_NOHYPHEN); /*  3개월분 */
        $ym02 = GGdate::format(GGdate::subTime($now, "2 month")  , GGDate::DATEFORMAT__YYYYMM_NOHYPHEN); /*  3개월분 */
        $ym03 = GGdate::format(GGdate::subTime($now, "3 month")  , GGDate::DATEFORMAT__YYYYMM_NOHYPHEN); /*  3개월분 */
        $ym04 = GGdate::format(GGdate::subTime($now, "4 month")  , GGDate::DATEFORMAT__YYYYMM_NOHYPHEN); /*  6개월분 */
        $ym05 = GGdate::format(GGdate::subTime($now, "5 month")  , GGDate::DATEFORMAT__YYYYMM_NOHYPHEN); /*  6개월분 */
        $ym06 = GGdate::format(GGdate::subTime($now, "6 month")  , GGDate::DATEFORMAT__YYYYMM_NOHYPHEN); /*  6개월분 */
        $ym07 = GGdate::format(GGdate::subTime($now, "7 month")  , GGDate::DATEFORMAT__YYYYMM_NOHYPHEN); /*  9개월분 */
        $ym08 = GGdate::format(GGdate::subTime($now, "8 month")  , GGDate::DATEFORMAT__YYYYMM_NOHYPHEN); /*  9개월분 */
        $ym09 = GGdate::format(GGdate::subTime($now, "9 month")  , GGDate::DATEFORMAT__YYYYMM_NOHYPHEN); /*  9개월분 */
        $ym10 = GGdate::format(GGdate::subTime($now, "10 month") , GGDate::DATEFORMAT__YYYYMM_NOHYPHEN); /* 12개월분 */
        $ym11 = GGdate::format(GGdate::subTime($now, "11 month") , GGDate::DATEFORMAT__YYYYMM_NOHYPHEN); /* 12개월분 */
        $ym12 = GGdate::format(GGdate::subTime($now, "12 month") , GGDate::DATEFORMAT__YYYYMM_NOHYPHEN); /* 12개월분 */

        $month03 = "$ym00, $ym01, $ym02, $ym03";
        $month06 = "$ym00, $ym01, $ym02, $ym03, $ym04, $ym05, $ym06";
        $month09 = "$ym00, $ym01, $ym02, $ym03, $ym04, $ym05, $ym06, $ym07, $ym08, $ym09";
        $month12 = "$ym00, $ym01, $ym02, $ym03, $ym04, $ym05, $ym06, $ym07, $ym08, $ym09, $ym10, $ym11, $ym12";

        /* ----- */
        /* n개월분 데이터 처리 */
        /*
            1. 테이블 리셋 및, n개월분 데이터 복제
            2. 주문한지 3개월이 넘어가는 주소는 비활성데이터이므로 삭제해준다.
            3. 테이블 내에서 재주문율을 계산해준다.
            4. 계산된 재주문율을 각 테이블에 업데이트 (store, menu)
        */
        /* ----- */

        /* 1. */
        /* 2. */
        $reorderpctCalBO->insertAfterDeleteFromRolForInside($month03);

        /* 3. */
        $reorderpctResultBO->insertAfterDeleteFromRocForInside();

        /* 4. */
        $storeBO->updateFromReorderpctResultForInside();
        $menuBO->updateReorderpctForInside();

        /* ========================= */
        /* after process */
        /* ========================= */
        $this->afterProcess();
        return true;
    }
}



?>