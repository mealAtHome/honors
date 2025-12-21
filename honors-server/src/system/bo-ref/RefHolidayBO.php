<?php

class RefHolidayBO extends _CommonBO
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
    function setBO() {
        // GGnavi::getStoreBO();
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    const FIELD__IDX         = "idx";
    const FIELD__YEAR        = "year";
    const FIELD__HOLIDAY     = "holiday";
    const FIELD__HOLIDAYNAME = "holidayname";

    /* ========================= */
    /*  */
    /* ========================= */
    public function selectByHolidayForInside($HOLIDAY) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /*  */
    /* ========================= */
    const selectByHolidayForInside = "selectByHolidayForInside"; /* [HOLIDAY] */
    const selectAll = "selectAll";
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        extract($options);

        /* orderride option */
        if($option != "")
            $OPTION = $option;

        /* --------------- */
        /* sql body */
        /* --------------- */
        $query  = "";
        $select = "";
        $from   = "";
        $select =
        "
            rholi.idx,
            rholi.year,
            rholi.holiday,
            rholi.holidayname
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectAll                : { $from = "(select * from _ref_holiday) rholi"; break; }
            case self::selectByHolidayForInside : { $from = "(select * from _ref_holiday where holiday = '$HOLIDAY') rholi"; break; }

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

    /**
     * @param $dateStr : "2021-01-01"
     */
    public function isHoliday($dateStr)
    {
        $record = Common::getDataOne($this->selectByHolidayForInside($dateStr));
        if($record == null)
            return false;
        return true;
    }

} /* end class */
?>
