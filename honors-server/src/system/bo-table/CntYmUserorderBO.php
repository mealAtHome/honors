<?php

/* cnt_ym_userorder : cou */
class CntYmUserorderBO extends _CommonBO
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
    /* ========================= */
    /* select */
    /* ========================= */
    const selectByExecutor = "selectByExecutor";
    protected function select($options, $option="")
    {
        /* -------------- */
        /* vars */
        /* -------------- */
        extract($options);

        /* override option */
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
            cou.userno
            ,cou.year
            ,cou.month
            ,cou.cnt
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByExecutor: { $from = "(select * from cnt_ym_userorder where userno = '$EXECUTOR') cou"; break; }
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
            order by
                cou.year desc,
                cou.month desc
        ";
        return GGsql::select($query, $from, $options);
    }

}
?>
