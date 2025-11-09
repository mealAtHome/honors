<?php

/*
    파티션 생성 / 삭제
    (트렌젝션 설정 없음)

    매장 주문처리시간 로그
    재주문율
*/
class Per40HouPartitions extends Per00BatchBase
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
    public $batchname = "per-40-hou-partitions";

    public function __construct()
    {
        GGnavi::getInformationSchemaPartitionsBO();
        GGnavi::getStoreOrderproctimeLogBO();
        GGnavi::getReorderpctLogBO();
    }

    public function process()
    {
        /* ========================= */
        /* init */
        /* ========================= */
        $this->beforeProcess();

        /* set BO */
        $informationSchemaPartitionsBO = InformationSchemaPartitionsBO::getInstance();
        $storeOrderproctimeLogBO = StoreOrderproctimeLogBO::getInstance();
        $reorderpctLogBO = ReorderpctLogBO::getInstance();

        /* ========================= */
        /* process */
        /* ========================= */
        /*
            작업순서

            1. 이번 파티션의 이름 추출
            2. 이번 파티션의 이름을 조회하고, 파티션이 존재하지 않으면 새로운 파티션 생성

            4. 다음 파티션의 이름 추출
            5. 다음 파티션의 이름을 조회하고, 파티션이 존재하지 않으면 새로운 파티션 생성
            6. 파티션을 리스팅하여 보관일수를 벗어난 파티션은 삭제
        */

        /* ========================= */
        /* 현재 날짜 */
        /* ========================= */

        /* get registDate */
        $date = GGdate::now();

        /* ========================= */
        /* store_orderproctime_log / 일별 / 1개월 */
        /* ========================= */

        /* 1 */
        $thisPartitionDateStr = GGdate::format($date, GGdate::DATEFORMAT__YYYYMMDD_NOHYPHEN);
        $thisPartitionName    = "p".$thisPartitionDateStr;

        /* 2 */
        $informationSchemaPartitions = Common::getDataOne($informationSchemaPartitionsBO->selectByPartitionNameForInside(StoreOrderproctimeLogBO::TABLENAME, $thisPartitionName));
        if($informationSchemaPartitions == null)
            $storeOrderproctimeLogBO->alterAddPartitionForInside($thisPartitionName, $thisPartitionDateStr);

        /* 4 */
        $nextPartitionDateObj = GGdate::addTime($date, "1 day");
        $nextPartitionDateStr = GGdate::format($nextPartitionDateObj, GGdate::DATEFORMAT__YYYYMMDD_NOHYPHEN);
        $nextPartitionName    = "p".$nextPartitionDateStr;

        /* 5 */
        $informationSchemaPartitions = Common::getDataOne($informationSchemaPartitionsBO->selectByPartitionNameForInside(StoreOrderproctimeLogBO::TABLENAME, $nextPartitionName));
        if($informationSchemaPartitions == null)
            $storeOrderproctimeLogBO->alterAddPartitionForInside($nextPartitionName, $nextPartitionDateStr);

        /* 6 */
        $delPartitionDateObj = GGdate::subTime($date, "1 month");
        $delPartitionDateObj = GGdate::subTime($delPartitionDateObj, "1 day");
        $delPartitionDateStr = GGdate::format($delPartitionDateObj, GGdate::DATEFORMAT__YYYYMMDD_NOHYPHEN);
        $delPartitionName    = "p".$delPartitionDateStr;
        $informationSchemaPartitions = Common::getDataOne($informationSchemaPartitionsBO->selectByPartitionNameForInside(StoreOrderproctimeLogBO::TABLENAME, $delPartitionName));
        if($informationSchemaPartitions != null)
            $storeOrderproctimeLogBO->alterDelPartitionForInside($delPartitionName);


        /* ========================= */
        /* reorderpct_log / 월별 / 12개월 */
        /* ========================= */

        /* 1 */
        $thisPartitionDateStr = GGdate::format($date, GGdate::DATEFORMAT__YYYYMM_NOHYPHEN);
        $thisPartitionName    = "p".$thisPartitionDateStr;

        /* 2 */
        $informationSchemaPartitions = Common::getDataOne($informationSchemaPartitionsBO->selectByPartitionNameForInside(ReorderpctLogBO::TABLENAME, $thisPartitionName));
        if($informationSchemaPartitions == null)
            $reorderpctLogBO->alterAddPartitionForInside($thisPartitionName, $thisPartitionDateStr);

        /* 4 */
        $nextPartitionDateObj = GGdate::addTime($date, "1 month");
        $nextPartitionDateStr = GGdate::format($nextPartitionDateObj, GGdate::DATEFORMAT__YYYYMM_NOHYPHEN);
        $nextPartitionName    = "p".$nextPartitionDateStr;

        /* 5 */
        $informationSchemaPartitions = Common::getDataOne($informationSchemaPartitionsBO->selectByPartitionNameForInside(ReorderpctLogBO::TABLENAME, $nextPartitionName));
        if($informationSchemaPartitions == null)
            $reorderpctLogBO->alterAddPartitionForInside($nextPartitionName, $nextPartitionDateStr);

        /* 6 */
        $delPartitionDateObj = GGdate::subTime($date, "13 month");
        $delPartitionDateStr = GGdate::format($delPartitionDateObj, GGdate::DATEFORMAT__YYYYMM_NOHYPHEN);
        $delPartitionName    = "p".$delPartitionDateStr;
        $informationSchemaPartitions = Common::getDataOne($informationSchemaPartitionsBO->selectByPartitionNameForInside(ReorderpctLogBO::TABLENAME, $delPartitionName));
        if($informationSchemaPartitions != null)
            $reorderpctLogBO->alterDelPartitionForInside($delPartitionName);

        /* ========================= */
        /* after process */
        /* ========================= */
        $this->afterProcess();
        return true;
    }
}

?>