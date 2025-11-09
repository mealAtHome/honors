<?php

class BankBO extends _CommonBO
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
    public function __construct()
    {
    }

    /* ========================= */
    /*  */
    /*

    */
    /* ========================= */
    const selectAll = "selectAll";
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
              t.bankcode
            , t.bankname
            , t.maintenance_start
            , t.maintenance_end
            , t.maintenance_hecto_start
            , t.maintenance_hecto_end
            , t.maintenance_fixedterm
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectAll:
            {
                $from = "(select * from _bank) t";
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }

        /* --------------- */
        /* execute select */
        /* --------------- */
        $query =
        "
            select
                $select
            from
                $from
            order by
                t.bankcode
        ";
        return GGsql::select($query, $from, $options);
    }

} /* end class */
?>
