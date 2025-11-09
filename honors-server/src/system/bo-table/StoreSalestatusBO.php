<?php

class StoreSalestatusBO extends _CommonBO
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
    function __construct() {
        GGnavi::getStoreBO();
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    const FIELD__STORENO               = "storeno";
    const FIELD__AUTOSTATUS_OPEN_FLG       = "autostatus_open_flg";
    const FIELD__AUTOSTATUS_CLOSE_FLG      = "autostatus_close_flg";
    const FIELD__WEEKDAY_OPEN              = "weekday_open";
    const FIELD__WEEKDAY_CLOSE             = "weekday_close";
    const FIELD__WEEKDAY_PAUSE_FLG         = "weekday_pause_flg";
    const FIELD__WEEKDAY_PAUSE_START       = "weekday_pause_start";
    const FIELD__WEEKDAY_PAUSE_END         = "weekday_pause_end";
    const FIELD__HOLIDAY_OPEN              = "holiday_open";
    const FIELD__HOLIDAY_CLOSE             = "holiday_close";
    const FIELD__HOLIDAY_PAUSE_FLGB        = "holiday_pause_flg";
    const FIELD__HOLIDAY_PAUSE_END         = "holiday_pause_end";
    const FIELD__HOLIDAY_FLG               = "holiday_flg";
    const FIELD__HOLIDAY_1TH_SUN           = "holiday_1th_sun";
    const FIELD__HOLIDAY_1TH_MON           = "holiday_1th_mon";
    const FIELD__HOLIDAY_1TH_TUE           = "holiday_1th_tue";
    const FIELD__HOLIDAY_1TH_WED           = "holiday_1th_wed";
    const FIELD__HOLIDAY_1TH_THU           = "holiday_1th_thu";
    const FIELD__HOLIDAY_1TH_FRI           = "holiday_1th_fri";
    const FIELD__HOLIDAY_1TH_SAT           = "holiday_1th_sat";
    const FIELD__HOLIDAY_2TH_SUN           = "holiday_2th_sun";
    const FIELD__HOLIDAY_2TH_MON           = "holiday_2th_mon";
    const FIELD__HOLIDAY_2TH_TUE           = "holiday_2th_tue";
    const FIELD__HOLIDAY_2TH_WED           = "holiday_2th_wed";
    const FIELD__HOLIDAY_2TH_THU           = "holiday_2th_thu";
    const FIELD__HOLIDAY_2TH_FRI           = "holiday_2th_fri";
    const FIELD__HOLIDAY_2TH_SAT           = "holiday_2th_sat";
    const FIELD__HOLIDAY_3TH_SUN           = "holiday_3th_sun";
    const FIELD__HOLIDAY_3TH_MON           = "holiday_3th_mon";
    const FIELD__HOLIDAY_3TH_TUE           = "holiday_3th_tue";
    const FIELD__HOLIDAY_3TH_WED           = "holiday_3th_wed";
    const FIELD__HOLIDAY_3TH_THU           = "holiday_3th_thu";
    const FIELD__HOLIDAY_3TH_FRI           = "holiday_3th_fri";
    const FIELD__HOLIDAY_3TH_SAT           = "holiday_3th_sat";
    const FIELD__HOLIDAY_4TH_SUN           = "holiday_4th_sun";
    const FIELD__HOLIDAY_4TH_MON           = "holiday_4th_mon";
    const FIELD__HOLIDAY_4TH_TUE           = "holiday_4th_tue";
    const FIELD__HOLIDAY_4TH_WED           = "holiday_4th_wed";
    const FIELD__HOLIDAY_4TH_THU           = "holiday_4th_thu";
    const FIELD__HOLIDAY_4TH_FRI           = "holiday_4th_fri";
    const FIELD__HOLIDAY_4TH_SAT           = "holiday_4th_sat";
    const FIELD__HOLIDAY_5TH_SUN           = "holiday_5th_sun";
    const FIELD__HOLIDAY_5TH_MON           = "holiday_5th_mon";
    const FIELD__HOLIDAY_5TH_TUE           = "holiday_5th_tue";
    const FIELD__HOLIDAY_5TH_WED           = "holiday_5th_wed";
    const FIELD__HOLIDAY_5TH_THU           = "holiday_5th_thu";
    const FIELD__HOLIDAY_5TH_FRI           = "holiday_5th_fri";
    const FIELD__HOLIDAY_5TH_SAT           = "holiday_5th_sat";
    const FIELD__HOLIDAY_6TH_SUN           = "holiday_6th_sun";
    const FIELD__HOLIDAY_6TH_MON           = "holiday_6th_mon";
    const FIELD__HOLIDAY_6TH_TUE           = "holiday_6th_tue";
    const FIELD__HOLIDAY_6TH_WED           = "holiday_6th_wed";
    const FIELD__HOLIDAY_6TH_THU           = "holiday_6th_thu";
    const FIELD__HOLIDAY_6TH_FRI           = "holiday_6th_fri";
    const FIELD__HOLIDAY_6TH_SAT           = "holiday_6th_sat";
    const FIELD__SALESTATUS_NOTE           = "salestatus_note";
    const FIELD__TOSTRING_WEEKDAY          = "tostring_weekday";
    const FIELD__TOSTRING_HOLIDAY          = "tostring_holiday";
    const FIELD__MODIDT                    = "modidt";
    const FIELD__REGIDT                    = "regidt";

    /* ========================= */
    /* 휴점여부 확인을 위해 $weeknum(n번째주), $week(0~6)을 사용하여 필드명을 출력 */
    /* "" 만을 출력할 시 에러를 의미 */
    /* ========================= */
    public function makeFieldnameForWeeknum($weeknum, $week)
    {
        $fieldname = "holiday_".$weeknum."th_";
        switch($week)
        {
            case 0: $fieldname .= "sun"; break;
            case 1: $fieldname .= "mon"; break;
            case 2: $fieldname .= "tue"; break;
            case 3: $fieldname .= "wed"; break;
            case 4: $fieldname .= "thu"; break;
            case 5: $fieldname .= "fri"; break;
            case 6: $fieldname .= "sat"; break;
        }

        switch($fieldname)
        {
            case self::FIELD__HOLIDAY_1TH_SUN:
            case self::FIELD__HOLIDAY_1TH_MON:
            case self::FIELD__HOLIDAY_1TH_TUE:
            case self::FIELD__HOLIDAY_1TH_WED:
            case self::FIELD__HOLIDAY_1TH_THU:
            case self::FIELD__HOLIDAY_1TH_FRI:
            case self::FIELD__HOLIDAY_1TH_SAT:
            case self::FIELD__HOLIDAY_2TH_SUN:
            case self::FIELD__HOLIDAY_2TH_MON:
            case self::FIELD__HOLIDAY_2TH_TUE:
            case self::FIELD__HOLIDAY_2TH_WED:
            case self::FIELD__HOLIDAY_2TH_THU:
            case self::FIELD__HOLIDAY_2TH_FRI:
            case self::FIELD__HOLIDAY_2TH_SAT:
            case self::FIELD__HOLIDAY_3TH_SUN:
            case self::FIELD__HOLIDAY_3TH_MON:
            case self::FIELD__HOLIDAY_3TH_TUE:
            case self::FIELD__HOLIDAY_3TH_WED:
            case self::FIELD__HOLIDAY_3TH_THU:
            case self::FIELD__HOLIDAY_3TH_FRI:
            case self::FIELD__HOLIDAY_3TH_SAT:
            case self::FIELD__HOLIDAY_4TH_SUN:
            case self::FIELD__HOLIDAY_4TH_MON:
            case self::FIELD__HOLIDAY_4TH_TUE:
            case self::FIELD__HOLIDAY_4TH_WED:
            case self::FIELD__HOLIDAY_4TH_THU:
            case self::FIELD__HOLIDAY_4TH_FRI:
            case self::FIELD__HOLIDAY_4TH_SAT:
            case self::FIELD__HOLIDAY_5TH_SUN:
            case self::FIELD__HOLIDAY_5TH_MON:
            case self::FIELD__HOLIDAY_5TH_TUE:
            case self::FIELD__HOLIDAY_5TH_WED:
            case self::FIELD__HOLIDAY_5TH_THU:
            case self::FIELD__HOLIDAY_5TH_FRI:
            case self::FIELD__HOLIDAY_5TH_SAT:
            case self::FIELD__HOLIDAY_6TH_SUN:
            case self::FIELD__HOLIDAY_6TH_MON:
            case self::FIELD__HOLIDAY_6TH_TUE:
            case self::FIELD__HOLIDAY_6TH_WED:
            case self::FIELD__HOLIDAY_6TH_THU:
            case self::FIELD__HOLIDAY_6TH_FRI:
            case self::FIELD__HOLIDAY_6TH_SAT:
                break;
            default:
                $fieldname = "";
        }
        return $fieldname;
    }

    /* ========================= */
    /*  */
    /* ========================= */
    public function selectByStorenoForInside                 ($STORENO)   { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectOpenAbleOfWeekdayForInside            ($START, $END)   { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectOpenAbleOfHolidayForInside            ($START, $END)   { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectCloseAbleOfWeekdayForInside           ($START, $END)   { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectCloseAbleOfHolidayForInside           ($START, $END)   { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectPauseStartAbleOfWeekdayForInside      ($START, $END)   { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectPauseStartAbleOfHolidayForInside      ($START, $END)   { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectPauseEndAbleOfWeekdayForInside        ($START, $END)   { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectPauseEndAbleOfHolidayForInside        ($START, $END)   { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /*  */
    /* ========================= */
    const selectMine                               = "selectMine";                                /* [AUTH:OWN]  [STORENO] */
    const selectByStoreno                          = "selectByStoreno";                           /* [AUTH:ALL]  [STORENO] */
    const selectByStorenoForInside                 = "selectByStorenoForInside";                  /* [AUTH:NONE] [STORENO] */
    const selectOpenAbleOfWeekdayForInside         = "selectOpenAbleOfWeekdayForInside";          /* [AUTH:NONE] [START, END] */
    const selectOpenAbleOfHolidayForInside         = "selectOpenAbleOfHolidayForInside";          /* [AUTH:NONE] [START, END] */
    const selectCloseAbleOfWeekdayForInside        = "selectCloseAbleOfWeekdayForInside";         /* [AUTH:NONE] [START, END] */
    const selectCloseAbleOfHolidayForInside        = "selectCloseAbleOfHolidayForInside";         /* [AUTH:NONE] [START, END] */
    const selectPauseStartAbleOfWeekdayForInside   = "selectPauseStartAbleOfWeekdayForInside";    /* [AUTH:NONE] [START, END] */
    const selectPauseStartAbleOfHolidayForInside   = "selectPauseStartAbleOfHolidayForInside";    /* [AUTH:NONE] [START, END] */
    const selectPauseEndAbleOfWeekdayForInside     = "selectPauseEndAbleOfWeekdayForInside";      /* [AUTH:NONE] [START, END] */
    const selectPauseEndAbleOfHolidayForInside     = "selectPauseEndAbleOfHolidayForInside";      /* [AUTH:NONE] [START, END] */
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        $ggAuth = GGauth::getInstance();
        extract($options);

        /* orderride option */
        if($option != "")
            $OPTION = $option;

        $Y = GGF::Y;

        /* --------------- */
        /* sql body */
        /* --------------- */
        $query  = "";
        $select = "";
        $from   = "";
        $select =
        "
             sss.storeno
            ,sss.autostatus_open_flg
            ,sss.autostatus_close_flg
            ,sss.weekday_open
            ,sss.weekday_close
            ,sss.weekday_pause_flg
            ,sss.weekday_pause_start
            ,sss.weekday_pause_end
            ,sss.holiday_open
            ,sss.holiday_close
            ,sss.holiday_pause_flg
            ,sss.holiday_pause_start
            ,sss.holiday_pause_end
            ,sss.holiday_flg
            ,sss.holiday_1th_sun
            ,sss.holiday_1th_mon
            ,sss.holiday_1th_tue
            ,sss.holiday_1th_wed
            ,sss.holiday_1th_thu
            ,sss.holiday_1th_fri
            ,sss.holiday_1th_sat
            ,sss.holiday_2th_sun
            ,sss.holiday_2th_mon
            ,sss.holiday_2th_tue
            ,sss.holiday_2th_wed
            ,sss.holiday_2th_thu
            ,sss.holiday_2th_fri
            ,sss.holiday_2th_sat
            ,sss.holiday_3th_sun
            ,sss.holiday_3th_mon
            ,sss.holiday_3th_tue
            ,sss.holiday_3th_wed
            ,sss.holiday_3th_thu
            ,sss.holiday_3th_fri
            ,sss.holiday_3th_sat
            ,sss.holiday_4th_sun
            ,sss.holiday_4th_mon
            ,sss.holiday_4th_tue
            ,sss.holiday_4th_wed
            ,sss.holiday_4th_thu
            ,sss.holiday_4th_fri
            ,sss.holiday_4th_sat
            ,sss.holiday_5th_sun
            ,sss.holiday_5th_mon
            ,sss.holiday_5th_tue
            ,sss.holiday_5th_wed
            ,sss.holiday_5th_thu
            ,sss.holiday_5th_fri
            ,sss.holiday_5th_sat
            ,sss.holiday_6th_sun
            ,sss.holiday_6th_mon
            ,sss.holiday_6th_tue
            ,sss.holiday_6th_wed
            ,sss.holiday_6th_thu
            ,sss.holiday_6th_fri
            ,sss.holiday_6th_sat
            ,sss.salestatus_note
            ,sss.tostring_weekday
            ,sss.tostring_holiday
            ,sss.modidt
            ,sss.regidt
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectMine                                : { $ggAuth->isStoreOwner($EXECUTOR, $STORENO);    $from = "(select * from store_salestatus where storeno = (select storeno from store where userno = '$EXECUTOR')) sss"; break; }
            case self::selectByStorenoForInside                  : {                                                $from = "(select * from store_salestatus where storeno = '$STORENO') sss"; break; }
            case self::selectByStoreno                           : {                                                $from = "(select * from store_salestatus where storeno = '$STORENO') sss"; break; }
            case self::selectOpenAbleOfWeekdayForInside          : {                                                $from = "(select * from store_salestatus where autostatus_open_flg  = '$Y' and weekday_open        between str_to_date('$START', '%Y-%m-%d %H:%i') and str_to_date('$END', '%Y-%m-%d %H:%i')) sss"; break; }
            case self::selectOpenAbleOfHolidayForInside          : {                                                $from = "(select * from store_salestatus where autostatus_open_flg  = '$Y' and holiday_open        between str_to_date('$START', '%Y-%m-%d %H:%i') and str_to_date('$END', '%Y-%m-%d %H:%i')) sss"; break; }
            case self::selectOpenAbleOfWeekdayForInside          : {                                                $from = "(select * from store_salestatus where autostatus_close_flg = '$Y' and weekday_close       between str_to_date('$START', '%Y-%m-%d %H:%i') and str_to_date('$END', '%Y-%m-%d %H:%i')) sss"; break; }
            case self::selectOpenAbleOfHolidayForInside          : {                                                $from = "(select * from store_salestatus where autostatus_close_flg = '$Y' and holiday_close       between str_to_date('$START', '%Y-%m-%d %H:%i') and str_to_date('$END', '%Y-%m-%d %H:%i')) sss"; break; }
            case self::selectPauseStartAbleOfWeekdayForInside    : {                                                $from = "(select * from store_salestatus where weekday_pause_flg    = '$Y' and weekday_pause_start between str_to_date('$START', '%Y-%m-%d %H:%i') and str_to_date('$END', '%Y-%m-%d %H:%i')) sss"; break; }
            case self::selectPauseStartAbleOfHolidayForInside    : {                                                $from = "(select * from store_salestatus where holiday_pause_flg    = '$Y' and holiday_pause_start between str_to_date('$START', '%Y-%m-%d %H:%i') and str_to_date('$END', '%Y-%m-%d %H:%i')) sss"; break; }
            case self::selectPauseEndAbleOfWeekdayForInside      : {                                                $from = "(select * from store_salestatus where weekday_pause_flg    = '$Y' and weekday_pause_end   between str_to_date('$START', '%Y-%m-%d %H:%i') and str_to_date('$END', '%Y-%m-%d %H:%i')) sss"; break; }
            case self::selectPauseEndAbleOfHolidayForInside      : {                                                $from = "(select * from store_salestatus where holiday_pause_flg    = '$Y' and holiday_pause_end   between str_to_date('$START', '%Y-%m-%d %H:%i') and str_to_date('$END', '%Y-%m-%d %H:%i')) sss"; break; }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }

        /* --------------- */
        /* exe query */
        /* --------------- */
        $query =
        "
            select
                $select
            from
                $from
        ";
        return GGsql::select($query, $from, $options);
    }

    /* ==================== */
    /* ==================== */
    const updateByStoreno = "updateByStoreno"; /* 메인 엔티티 */
    protected function update($options, $option="")
    {
        /* vars */
        $ggAuth = GGauth::getInstance();
        $storeno = null;

        /* get vars */
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* sql execution */
        switch($OPTION)
        {
            case self::updateByStoreno:
            {
                /* get storeno */
                $storeBO = StoreBO::getInstance();
                $storeno = Common::getDataOneField($storeBO->selectByUsernoForInside($EXECUTOR), GGF::STORENO);

                /* auth */
                $ggAuth->isStoreOwner($EXECUTOR, $STORENO);

                /* make string for store card */
                $weekdayOpen   = GGdate::getDateFromString($WEEKDAY_OPEN)  ->format(GGdate::DATEFORMAT__HHII);
                $weekdayClose  = GGdate::getDateFromString($WEEKDAY_CLOSE) ->format(GGdate::DATEFORMAT__HHII);
                $holidayOpen   = GGdate::getDateFromString($HOLIDAY_OPEN)  ->format(GGdate::DATEFORMAT__HHII);
                $holidayClose  = GGdate::getDateFromString($HOLIDAY_CLOSE) ->format(GGdate::DATEFORMAT__HHII);
                $tostringWeekday = "$weekdayOpen~$weekdayClose";
                $tostringHoliday = "$holidayOpen~$holidayClose";

                /* query */
                $query =
                "
                    insert into store_salestatus
                    (
                        storeno
                        ,autostatus_open_flg
                        ,autostatus_close_flg
                        ,weekday_open
                        ,weekday_close
                        ,weekday_pause_flg
                        ,weekday_pause_start
                        ,weekday_pause_end
                        ,holiday_open
                        ,holiday_close
                        ,holiday_pause_flg
                        ,holiday_pause_start
                        ,holiday_pause_end
                        ,holiday_flg
                        ,holiday_1th_sun
                        ,holiday_1th_mon
                        ,holiday_1th_tue
                        ,holiday_1th_wed
                        ,holiday_1th_thu
                        ,holiday_1th_fri
                        ,holiday_1th_sat
                        ,holiday_2th_sun
                        ,holiday_2th_mon
                        ,holiday_2th_tue
                        ,holiday_2th_wed
                        ,holiday_2th_thu
                        ,holiday_2th_fri
                        ,holiday_2th_sat
                        ,holiday_3th_sun
                        ,holiday_3th_mon
                        ,holiday_3th_tue
                        ,holiday_3th_wed
                        ,holiday_3th_thu
                        ,holiday_3th_fri
                        ,holiday_3th_sat
                        ,holiday_4th_sun
                        ,holiday_4th_mon
                        ,holiday_4th_tue
                        ,holiday_4th_wed
                        ,holiday_4th_thu
                        ,holiday_4th_fri
                        ,holiday_4th_sat
                        ,holiday_5th_sun
                        ,holiday_5th_mon
                        ,holiday_5th_tue
                        ,holiday_5th_wed
                        ,holiday_5th_thu
                        ,holiday_5th_fri
                        ,holiday_5th_sat
                        ,holiday_6th_sun
                        ,holiday_6th_mon
                        ,holiday_6th_tue
                        ,holiday_6th_wed
                        ,holiday_6th_thu
                        ,holiday_6th_fri
                        ,holiday_6th_sat
                        ,salestatus_note
                        ,tostring_weekday
                        ,tostring_holiday
                        ,modidt
                        ,regidt
                    )
                    values
                    (
                         '$storeno'
                        ,'$AUTOSTATUS_OPEN_FLG'
                        ,'$AUTOSTATUS_CLOSE_FLG'
                        ,'$WEEKDAY_OPEN'
                        ,'$WEEKDAY_CLOSE'
                        ,'$WEEKDAY_PAUSE_FLG'
                        ,'$WEEKDAY_PAUSE_START'
                        ,'$WEEKDAY_PAUSE_END'
                        ,'$HOLIDAY_OPEN'
                        ,'$HOLIDAY_CLOSE'
                        ,'$HOLIDAY_PAUSE_FLG'
                        ,'$HOLIDAY_PAUSE_START'
                        ,'$HOLIDAY_PAUSE_END'
                        ,'$HOLIDAY_FLG'
                        ,'$HOLIDAY_1TH_SUN'
                        ,'$HOLIDAY_1TH_MON'
                        ,'$HOLIDAY_1TH_TUE'
                        ,'$HOLIDAY_1TH_WED'
                        ,'$HOLIDAY_1TH_THU'
                        ,'$HOLIDAY_1TH_FRI'
                        ,'$HOLIDAY_1TH_SAT'
                        ,'$HOLIDAY_2TH_SUN'
                        ,'$HOLIDAY_2TH_MON'
                        ,'$HOLIDAY_2TH_TUE'
                        ,'$HOLIDAY_2TH_WED'
                        ,'$HOLIDAY_2TH_THU'
                        ,'$HOLIDAY_2TH_FRI'
                        ,'$HOLIDAY_2TH_SAT'
                        ,'$HOLIDAY_3TH_SUN'
                        ,'$HOLIDAY_3TH_MON'
                        ,'$HOLIDAY_3TH_TUE'
                        ,'$HOLIDAY_3TH_WED'
                        ,'$HOLIDAY_3TH_THU'
                        ,'$HOLIDAY_3TH_FRI'
                        ,'$HOLIDAY_3TH_SAT'
                        ,'$HOLIDAY_4TH_SUN'
                        ,'$HOLIDAY_4TH_MON'
                        ,'$HOLIDAY_4TH_TUE'
                        ,'$HOLIDAY_4TH_WED'
                        ,'$HOLIDAY_4TH_THU'
                        ,'$HOLIDAY_4TH_FRI'
                        ,'$HOLIDAY_4TH_SAT'
                        ,'$HOLIDAY_5TH_SUN'
                        ,'$HOLIDAY_5TH_MON'
                        ,'$HOLIDAY_5TH_TUE'
                        ,'$HOLIDAY_5TH_WED'
                        ,'$HOLIDAY_5TH_THU'
                        ,'$HOLIDAY_5TH_FRI'
                        ,'$HOLIDAY_5TH_SAT'
                        ,'$HOLIDAY_6TH_SUN'
                        ,'$HOLIDAY_6TH_MON'
                        ,'$HOLIDAY_6TH_TUE'
                        ,'$HOLIDAY_6TH_WED'
                        ,'$HOLIDAY_6TH_THU'
                        ,'$HOLIDAY_6TH_FRI'
                        ,'$HOLIDAY_6TH_SAT'
                        ,'$SALESTATUS_NOTE'
                        ,'$tostringWeekday'
                        ,'$tostringHoliday'
                        , now()
                        , now()
                    )
                    on duplicate key update
                    autostatus_open_flg  = '$AUTOSTATUS_OPEN_FLG',
                    autostatus_close_flg = '$AUTOSTATUS_CLOSE_FLG',
                    weekday_open         = '$WEEKDAY_OPEN',
                    weekday_close        = '$WEEKDAY_CLOSE',
                    weekday_pause_flg    = '$WEEKDAY_PAUSE_FLG',
                    weekday_pause_start  = '$WEEKDAY_PAUSE_START',
                    weekday_pause_end    = '$WEEKDAY_PAUSE_END',
                    holiday_open         = '$HOLIDAY_OPEN',
                    holiday_close        = '$HOLIDAY_CLOSE',
                    holiday_pause_flg    = '$HOLIDAY_PAUSE_FLG',
                    holiday_pause_start  = '$HOLIDAY_PAUSE_START',
                    holiday_pause_end    = '$HOLIDAY_PAUSE_END',
                    holiday_flg          = '$HOLIDAY_FLG',
                    holiday_1th_sun      = '$HOLIDAY_1TH_SUN',
                    holiday_1th_mon      = '$HOLIDAY_1TH_MON',
                    holiday_1th_tue      = '$HOLIDAY_1TH_TUE',
                    holiday_1th_wed      = '$HOLIDAY_1TH_WED',
                    holiday_1th_thu      = '$HOLIDAY_1TH_THU',
                    holiday_1th_fri      = '$HOLIDAY_1TH_FRI',
                    holiday_1th_sat      = '$HOLIDAY_1TH_SAT',
                    holiday_2th_sun      = '$HOLIDAY_2TH_SUN',
                    holiday_2th_mon      = '$HOLIDAY_2TH_MON',
                    holiday_2th_tue      = '$HOLIDAY_2TH_TUE',
                    holiday_2th_wed      = '$HOLIDAY_2TH_WED',
                    holiday_2th_thu      = '$HOLIDAY_2TH_THU',
                    holiday_2th_fri      = '$HOLIDAY_2TH_FRI',
                    holiday_2th_sat      = '$HOLIDAY_2TH_SAT',
                    holiday_3th_sun      = '$HOLIDAY_3TH_SUN',
                    holiday_3th_mon      = '$HOLIDAY_3TH_MON',
                    holiday_3th_tue      = '$HOLIDAY_3TH_TUE',
                    holiday_3th_wed      = '$HOLIDAY_3TH_WED',
                    holiday_3th_thu      = '$HOLIDAY_3TH_THU',
                    holiday_3th_fri      = '$HOLIDAY_3TH_FRI',
                    holiday_3th_sat      = '$HOLIDAY_3TH_SAT',
                    holiday_4th_sun      = '$HOLIDAY_4TH_SUN',
                    holiday_4th_mon      = '$HOLIDAY_4TH_MON',
                    holiday_4th_tue      = '$HOLIDAY_4TH_TUE',
                    holiday_4th_wed      = '$HOLIDAY_4TH_WED',
                    holiday_4th_thu      = '$HOLIDAY_4TH_THU',
                    holiday_4th_fri      = '$HOLIDAY_4TH_FRI',
                    holiday_4th_sat      = '$HOLIDAY_4TH_SAT',
                    holiday_5th_sun      = '$HOLIDAY_5TH_SUN',
                    holiday_5th_mon      = '$HOLIDAY_5TH_MON',
                    holiday_5th_tue      = '$HOLIDAY_5TH_TUE',
                    holiday_5th_wed      = '$HOLIDAY_5TH_WED',
                    holiday_5th_thu      = '$HOLIDAY_5TH_THU',
                    holiday_5th_fri      = '$HOLIDAY_5TH_FRI',
                    holiday_5th_sat      = '$HOLIDAY_5TH_SAT',
                    holiday_6th_sun      = '$HOLIDAY_6TH_SUN',
                    holiday_6th_mon      = '$HOLIDAY_6TH_MON',
                    holiday_6th_tue      = '$HOLIDAY_6TH_TUE',
                    holiday_6th_wed      = '$HOLIDAY_6TH_WED',
                    holiday_6th_thu      = '$HOLIDAY_6TH_THU',
                    holiday_6th_fri      = '$HOLIDAY_6TH_FRI',
                    holiday_6th_sat      = '$HOLIDAY_6TH_SAT',
                    salestatus_note      = '$SALESTATUS_NOTE',
                    tostring_weekday     = '$tostringWeekday',
                    tostring_holiday     = '$tostringHoliday',
                    modidt               =  now()
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
                exit;
            }
        }
        return $storeno;
    }


} /* end class */
?>
