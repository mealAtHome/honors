<?php

/* ========================= */
/* 날짜관련 집합 */
/* ========================= */
class GGdate
{
    const TIMEZONE = 'Asia/Tokyo';

    /* ========================= */
    /* 현재 날짜 기준 */
    /* ========================= */
    const DATEFORMAT__YYYYMMDDHHIISSU           = 'Y-m-d H:i:s.u';
    const DATEFORMAT__YYYYMMDDHHIISS            = 'Y-m-d H:i:s';
    const DATEFORMAT__YYYYMMDDHHII              = 'Y-m-d H:i';
    const DATEFORMAT__YYYYMMDD                  = 'Y-m-d';
    const DATEFORMAT__YYYYMM                    = 'Y-m';
    const DATEFORMAT__YYYYMM_NOHYPHEN           = 'Ym';
    const DATEFORMAT__YYYYMMDD_NOHYPHEN         = 'Ymd';
    const DATEFORMAT__YYYY                      = 'Y';
    const DATEFORMAT__MM                        = 'm';
    const DATEFORMAT__DD                        = 'd';
    const DATEFORMAT__HH                        = 'H';
    const DATEFORMAT__W                         = 'w';
    const DATEFORMAT__HHII                      = 'H:i';
    public static function now()
    {
        $tz   = new DateTimeZone(self::TIMEZONE);
        $date = new DateTime("now", $tz);
        return $date;
    }
    public static function getDateFromString($str)
    {
        $tz   = new DateTimeZone(self::TIMEZONE);
        $date = new DateTime($str, $tz);
        return $date;
    }

    public static function getYMDHIS      () { return self::format(self::now(), self::DATEFORMAT__YYYYMMDDHHIISS); }
    public static function getYMD         () { return self::format(self::now(), self::DATEFORMAT__YYYYMMDD_NOHYPHEN); }
    public static function getYMDhyphen   () { return self::format(self::now(), self::DATEFORMAT__YYYYMMDD); }
    public static function format($dateTime, $format=self::DATEFORMAT__YYYYMMDDHHII)
    {
        try
        {
            return $dateTime->format($format);
        }
        catch(Error $e)
        {
            return false;
        }
    }
    public static function addMinute($dateTime, $minute) { return self::addTime($dateTime, "$minute minute"); }
    public static function addTime($dateTime, $addTimeStr)
    {
        $targetDT = clone $dateTime;
        $addedTime = null;
        try
        {
            $interval  = DateInterval::createFromDateString($addTimeStr); /* "1 hour" */
            $addedTime = $targetDT->add($interval);
        }
        catch(Error $e)
        {
            Common::returnError($e->getMessage(), $e);
            return null;
        }
        return $addedTime;
    }
    public static function subTime($dateTime, $addTimeStr)
    {
        $targetDT = clone $dateTime;
        $addedTime = null;
        try
        {
            $interval  = DateInterval::createFromDateString($addTimeStr); /* "1 hour" */
            $addedTime = $targetDT->sub($interval);
        }
        catch(Error $e)
        {
            Common::returnError($e->getMessage(), $e);
            return null;
        }
        return $addedTime;
    }
    public static function getWeekdayStr($dateTime)
    {
        $str = null;
        try
        {
            $weekday = intval(GGdate::format($dateTime, GGdate::DATEFORMAT__W));
            switch($weekday)
            {
                case 0: $str = "sun"; break;
                case 1: $str = "mon"; break;
                case 2: $str = "tue"; break;
                case 3: $str = "wed"; break;
                case 4: $str = "thu"; break;
                case 5: $str = "fri"; break;
                case 6: $str = "sat"; break;
            }
            if($str == null)
            {
                Common::returnError("weekday is not valid");
                return null;
            }
        }
        catch(Error $e)
        {
            Common::returnError($e->getMessage(), $e);
            return null;
        }
        return $str;
    }

    public static function isInPeriod($startDateTime, $endDateTime, $targetDateTime=null)
    {
        /* validation */
        if($startDateTime == null || $endDateTime == null)
            throw new GGexception("start or end date is null");
        if($targetDateTime == null)
            $targetDateTime = self::format(self::now(), self::DATEFORMAT__YYYYMMDDHHIISS);

        /* check */
        if($startDateTime > $targetDateTime || $endDateTime < $targetDateTime)
            return false;
        return true;
    }

} /* end class */
?>