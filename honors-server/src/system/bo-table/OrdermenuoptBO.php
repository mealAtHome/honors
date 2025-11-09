<?php

/* oopt : ordermenuopt */
class OrdermenuoptBO extends _CommonBO
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

    const FIELD__STORENO       = "storeno";        /* PK */
    const FIELD__ORDERNO       = "orderno";        /* PK */
    const FIELD__CART_INDEX    = "cart_index";     /* PK */
    const FIELD__MENUNO        = "menuno";         /* PK */
    const FIELD__MENUOPTNO     = "menuoptno";      /* PK */
    const FIELD__MENUOPT_NAME  = "menuopt_name";   /*  */

    /* ========================= */
    /*  */
    /*
        ■ pk
            - storeno
            - orderno
            - cart_index
            - menuoptno
    */
    /* ========================= */
    public function selectByCartIndexForInside  ($STORENO, $ORDERNO, $CART_INDEX) { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectByOrdernoForInside    ($STORENO, $ORDERNO)              { return $this->select(get_defined_vars(), __FUNCTION__); }

    const selectByCartIndexForInside = "selectByCartIndexForInside";
    const selectByOrdernoForInside   = "selectByOrdernoForInside";
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
             oopt.storeno
            ,oopt.orderno
            ,oopt.cart_index
            ,oopt.menuno
            ,oopt.menuoptno
            ,oopt.menuopt_name
            , opt.menuopt_chooseablecnt_min
            , opt.menuopt_chooseablecnt_max
            , opt.menuopt_comment
            , opt.is_display
            , opt.is_stock
            , opt.is_deleted
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByCartIndexForInside : { $from = "(select * from ordermenuopt where storeno = '$STORENO' and orderno = '$ORDERNO' and cart_index = $CART_INDEX ) oopt"; break; }
            case self::selectByOrdernoForInside   : { $from = "(select * from ordermenuopt where storeno = '$STORENO' and orderno = '$ORDERNO'                              ) oopt"; break; }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        } /* end switch($OPTION) */

        /* --------------- */
        /* exe query */
        /* --------------- */
        $query =
        "
            select
                $select
            from
                $from
                left join ordermenu om
                    on
                        oopt.storeno = om.storeno and
                        oopt.orderno     = om.orderno and
                        oopt.cart_index  = om.cart_index and
                        oopt.menuno  = om.menuno
                left join menuopt opt
                    on
                        oopt.storeno    = opt.storeno and
                        oopt.menuno     = opt.menuno and
                        oopt.menuoptno   = opt.menuoptno
            order by
                 oopt.storeno
                ,oopt.orderno
                ,oopt.cart_index
                ,oopt.menuoptno
        ";
        return GGsql::select($query, $from, $options);
    }

} /* end class */
?>
