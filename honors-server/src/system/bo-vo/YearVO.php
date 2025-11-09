<?php

/* YearVO */
class YearVO extends _CommonBO
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
    function __construct()
    {

    }

    const FIELD__YEAR = "year"; /* 연도 */

    /* ========================= */
    /*  */
    /*
        ■ option
            - executor
    */
    /* ========================= */
    /* public function selectTodayAppliedByUsernoAndRegdtForInside($USERNO) { return $this->select(get_defined_vars(), __FUNCTION__); } */

    const selectSettleStoreByStoreno = "selectSettleStoreByStoreno";
    const selectSettleRiderByRiderno = "selectSettleRiderByRiderno";
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
        $from = "";
        $query = "";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectSettleStoreByStoreno : { $query = "select settle_year year, count(*) cnt from settle_store where storeno = '$STORENO' group by settle_year order by settle_year DESC"; break; }
            case self::selectSettleRiderByRiderno : { $query = "select settle_year year, count(*) cnt from settle_rider where riderno = '$RIDERNO' group by settle_year order by settle_year DESC"; break; }
        }
        return GGsql::select($query, $from, $options);
    }

}
?>
