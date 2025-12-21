<?php

/*
    점포가 설정한 영업시간에 따라 자동으로 OPEN/CLOSE 상태로 업데이트
*/
class Per30MinStoreSalestatusAutoProcess extends Per00BatchBase
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
    public $batchname = "per-30-min-storeSalestatusAutoProcess";

    public function setBO()
    {
        GGnavi::getStoreSalestatusBO();
        GGnavi::getSystemBatchBO();
        GGnavi::getRefHolidayBO();
        GGnavi::getStoreBO();
        $arr = array();
        $arr['storeSalestatusBO'] = StoreSalestatusBO::getInstance();
        $arr['systemBatchBO'] = SystemBatchBO::getInstance();
        $arr['refHolidayBO'] = RefHolidayBO::getInstance();
        $arr['storeBO'] = StoreBO::getInstance();
        return $arr;
    }

    public function process()
    {
        /* ========================= */
        /* init */
        /* ========================= */
        $this->beforeProcess();

        /* BO */
        $systemBatchBO = SystemBatchBO::getInstance();
        $storeSalestatusBO = StoreSalestatusBO::getInstance();
        $refHolidayBO = RefHolidayBO::getInstance();

        /* ========================= */
        /* process */
        /* ========================= */

        /* --------------- */
        /* 처리를 위한 현재시간 습득 */
        /* --------------- */
        $now = GGdate::now();
        $dateTimeStart = GGdate::format($now, GGDate::DATEFORMAT__YYYYMMDDHHII);
        $dateTimeEnd   = GGdate::format($now, GGDate::DATEFORMAT__YYYYMMDDHHII);

        /* --------------- */
        /* 처리대상의 시작시간범위가 있다면 설정 */
        /* --------------- */
        $systemBatchRecord = Common::getDataOne($systemBatchBO->selectByPkForInside($this->batchname));
        $freefield1 = Common::get($systemBatchRecord, SystemBatchBO::FIELD__FREEFIELD1);
        if($freefield1 != null)
            $dateTimeStart = $freefield1;

        /* --------------- */
        /* 처리대상일시가 이틀에 걸쳐서 있다면 end를 start당일의 23:59 로 제한 */
        /* --------------- */

        /* DateTime 오브젝트로 변환 */
        $dateTimeStartObj = GGdate::getDateFromString($dateTimeStart);
        $dateTimeEndObj   = GGdate::getDateFromString($dateTimeStart);

        /* YYYY-MM-DD 로 포맷 */
        $dateTimeStartYMD = GGdate::format($dateTimeStartObj , GGdate::DATEFORMAT__YYYYMMDD);
        $dateTimeEndYMD   = GGdate::format($dateTimeEndObj   , GGdate::DATEFORMAT__YYYYMMDD);

        /* 일자가 다른지 체크 */
        if($dateTimeStartYMD != $dateTimeEndYMD)
            $dateTimeEnd = "$dateTimeStart 00:00";

        /* --------------- */
        /* 처리대상일자가 휴일(주말, 공휴일)인지 확인 */
        /* --------------- */

        /* vars */
        $isHoliday = false; /* 주말/공휴일 인지? */

        /* 주말인지? */
        $weekday = GGdate::format($dateTimeStartObj, GGdate::DATEFORMAT__W);
        switch($weekday)
        {
            case 0:
            case 1:
                $isHoliday = true;
        }

        /* 지정공휴일인지? */
        $isPublicHoliday = $refHolidayBO->isHoliday($dateTimeStartYMD);

        /* 영업시간 상에서는 주말과 지정공휴일의 오픈/마감은 holiday_open, holiday_close 를 이용한다. */
        if($isHoliday == false)
            $isHoliday = $isPublicHoliday; /* 공휴일 값이 false 일 경우, 지정공휴일의 상태로 오버라이트한다. */

        /* 처리대상일자가 몇 번째 주인지? */
        $date         = intval(GGdate::format($dateTimeStartObj, GGdate::DATEFORMAT__DD));
        $dateOf1st    =        GGdate::getDateFromString(GGdate::format($dateTimeStartObj, GGdate::DATEFORMAT__YYYYMM)."-01");
        $weekdayOf1st = intval(GGdate::format($dateOf1st, GGdate::DATEFORMAT__W));
        $sunday = 1 - $weekdayOf1st;
        $satday = 7 - $weekdayOf1st;
        $weeknum = 0;
        if     ($sunday+(0*7) <= $date && $satday+(0*7) >= $date) $weeknum = 1;
        else if($sunday+(1*7) <= $date && $satday+(1*7) >= $date) $weeknum = 2;
        else if($sunday+(2*7) <= $date && $satday+(2*7) >= $date) $weeknum = 3;
        else if($sunday+(3*7) <= $date && $satday+(3*7) >= $date) $weeknum = 4;
        else if($sunday+(4*7) <= $date && $satday+(4*7) >= $date) $weeknum = 5;
        else if($sunday+(5*7) <= $date && $satday+(5*7) >= $date) $weeknum = 6;

        /* 처리일자의 요일번호(0~6) */
        $weekday = intval(GGdate::format($dateTimeStartObj, GGdate::DATEFORMAT__W));

        /* --------------- */
        /* 오픈처리 */
        /*
            > 사전조건
                - 처리대상일시가 휴일인지?

            > SQL조건
                - 자동처리를 사용하고 있는가
                - 처리시간에 해당하는가

            > 소스조건
                1. 처리대상일시의 weekday와 n번째주를 확인하여 휴점이면 오픈처리 하지않음.
                2. 휴일휴점이면 오픈처리 하지않음.
                3. 오픈처리 (이미 오픈되어 있으면 오픈처리하지 않음.)
        */
        /* --------------- */
        /* weekday holiay 번갈아서 실행 */
        for($i = 0; $i <= 2; $i++)
        {
            /* get list */
            $storeList = array();
            switch($i)
            {
                case 0: $storeList = Common::getData($storeSalestatusBO->selectOpenAbleOfWeekdayForInside($dateTimeStart, $dateTimeEnd)); break;
                case 1: $storeList = Common::getData($storeSalestatusBO->selectOpenAbleOfHolidayForInside($dateTimeStart, $dateTimeEnd)); break;
            }

            /* process */
            foreach($storeList as $store)
            {
                /* ----- */
                /* 1. 처리대상일시의 weekday와 n번째주를 확인하여 휴점이면 오픈처리 하지않음. */
                /* ----- */
                $fieldnameOfMonth = $storeSalestatusBO->makeFieldnameForWeeknum($weeknum, $weekday);
                if(isset($store[$fieldnameOfMonth]) == false)
                {
                    /* 필드 자체가 존재하지 않는 다는 뜻으로, 무엇인가 잘못되었음을 의미 */
                    continue;
                }
                else
                {
                    /* 휴점여부 확인 */
                    $holidayFlg = $store[$fieldnameOfMonth];
                    if($holidayFlg == GGF::Y)
                        continue;
                }

                /* ----- */
                /* 2. 휴일휴점이면 오픈처리 하지않음. */
                /* ----- */
                if(isset($store[StoreSalestatusBO::FIELD__HOLIDAY_FLG]))
                {
                    $holidayFlg = $store[StoreSalestatusBO::FIELD__HOLIDAY_FLG];
                    if($isPublicHoliday && $holidayFlg == GGF::Y)
                        continue;
                }

                /* ----- */
                /* 3. 오픈처리 (이미 오픈되어 있으면 오픈처리하지 않음.) */
                /* ----- */
                try
                {
                    $storeno = $store[GGF::STORENO];
                    $storeBO->changeStoreStatus($storeno, StoreBO::STORE_STATUS__OPEN);
                }
                catch(Error $e)
                {
                    continue;
                }
            }
        }

        /* --------------- */
        /* 휴식시작 */
        /*
            > SQL조건
                - 자동처리를 사용하고 있는가
                - 처리시간에 해당하는가
        */
        /* --------------- */
        /* weekday holiay 번갈아서 실행 */
        for($i = 0; $i <= 2; $i++)
        {
            /* get list */
            $storeList = array();
            switch($i)
            {
                case 0: $storeList = Common::getData($storeSalestatusBO->selectPauseStartAbleOfWeekdayForInside($dateTimeStart, $dateTimeEnd)); break;
                case 1: $storeList = Common::getData($storeSalestatusBO->selectPauseStartAbleOfHolidayForInside($dateTimeStart, $dateTimeEnd)); break;
            }

            /* process */
            foreach($storeList as $store)
            {
                /* 3. 휴식시작처리 (오픈되어 있지 않으면 할 수 없음) */
                try
                {
                    $storeno = $store[GGF::STORENO];
                    $storeBO->changeStoreStatus($storeno, StoreBO::STORE_STATUS__PAUSE);
                }
                catch(Error $e)
                {
                    continue;
                }
            }
        }

        /* --------------- */
        /* 휴식끝 */
        /*
            > SQL조건
                - 자동처리를 사용하고 있는가
                - 처리시간에 해당하는가
        */
        /* --------------- */
        /* weekday holiay 번갈아서 실행 */
        for($i = 0; $i <= 2; $i++)
        {
            /* get list */
            $storeList = array();
            switch($i)
            {
                case 0: $storeList = Common::getData($storeSalestatusBO->selectPauseEndAbleOfWeekdayForInside($dateTimeStart, $dateTimeEnd)); break;
                case 1: $storeList = Common::getData($storeSalestatusBO->selectPauseEndAbleOfHolidayForInside($dateTimeStart, $dateTimeEnd)); break;
            }

            /* process */
            foreach($storeList as $store)
            {
                /* 3. 휴식시작처리 (오픈되어 있지 않으면 할 수 없음) */
                try
                {
                    $storeno = $store[GGF::STORENO];
                    $storeBO->changeStoreStatus($storeno, StoreBO::STORE_STATUS__OPEN);
                }
                catch(Error $e)
                {
                    continue;
                }
            }
        }

        /* --------------- */
        /* 마감처리 */
        /*
            > SQL조건
                - 자동처리를 사용하고 있는가
                - 처리시간에 해당하는가

            > 소스조건
                - 이미 마감되어 있으면 마감하지 않음.
        /* --------------- */
        /* weekday holiay 번갈아서 실행 */
        for($i = 0; $i <= 2; $i++)
        {
            /* get list */
            $storeList = array();
            switch($i)
            {
                case 0: $storeList = Common::getData($storeSalestatusBO->selectPauseEndAbleOfWeekdayForInside($dateTimeStart, $dateTimeEnd)); break;
                case 1: $storeList = Common::getData($storeSalestatusBO->selectPauseEndAbleOfHolidayForInside($dateTimeStart, $dateTimeEnd)); break;
            }

            /* process */
            foreach($storeList as $store)
            {
                /* 3. 휴식시작처리 (오픈되어 있지 않으면 할 수 없음) */
                try
                {
                    $storeno = $store[GGF::STORENO];
                    $storeBO->changeStoreStatus($storeno, StoreBO::STORE_STATUS__CLOSE);
                }
                catch(Error $e)
                {
                    continue;
                }
            }
        }

        /* ========================= */
        /* process end */
        /* ========================= */

        /* 첫번째 프리필드에 end 시간을 넣어준다. */
        $dateTimeEndObj = GGdate::getDateFromString($dateTimeEnd);
        $dateTimeEndObj = GGdate::addTime($dateTimeEndObj, "1 minute");
        $dateTimeEndStr = GGdate::format($dateTimeEndObj, GGdate::DATEFORMAT__YYYYMMDDHHII);
        $systemBatchBO->updateFreeField1ForInside($this->batchname ,$dateTimeEndStr);

        /* after process */
        $this->afterProcess();
        return true;
    }

}

?>