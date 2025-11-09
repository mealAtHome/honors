<?php

/* ooptd : ordermenuopt_detail */
class OrdermenuoptDetailBO extends _CommonBO
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

    const FIELD__STORENO          = "storeno";           /* pk */
    const FIELD__ORDERNO          = "orderno";           /* pk */
    const FIELD__CART_INDEX       = "cart_index";        /* pk */
    const FIELD__MENUNO           = "menuno";            /* pk */
    const FIELD__MENUOPTNO        = "menuoptno";         /* pk */
    const FIELD__OPTDETAILNO      = "optdetailno";       /* pk */
    const FIELD__OPTDETAIL_NAME   = "optdetail_name";    /*  */
    const FIELD__OPTDETAIL_MENU   = "optdetail_menu";    /*  */
    const FIELD__OPTDETAIL_PRICE  = "optdetail_price";   /*  */

    /* ========================= */
    /*  */
    /*
        ■ pk
            - storeno
            - orderno
            - cart_index
            - menuoptno
            - optdetailno
    */
    /* ========================= */
    public function selectByPkForInside($STORENO, $ORDERNO, $CART_INDEX, $MENUOPTNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    const selectByPkForInside = "selectByPkForInside";
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
              ooptd.storeno
            , ooptd.orderno
            , ooptd.cart_index
            , ooptd.menuno
            , ooptd.menuoptno
            , ooptd.optdetailno
            , ooptd.optdetail_name
            , ooptd.optdetail_menu
            , ooptd.optdetail_price
            ,  optd.optdetail_pic
            ,  optd.optdetail_comment
            ,  optd.is_display
            ,  optd.is_stock
            ,  optd.is_deleted
            ,     p.pic_name
            ,     p.pic_kind
            ,     p.pic_path
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPkForInside:
            {
                $from =
                "
                    (
                        select
                            *
                        from
                            ordermenuopt_detail
                        where
                            storeno     = '$STORENO' and
                            orderno     = '$ORDERNO' and
                            cart_index  =  $CART_INDEX and
                            menuoptno   =  $MENUOPTNO
                    ) ooptd
                ";
                break;
            }
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
                        ooptd.storeno = om.storeno and
                        ooptd.orderno     = om.orderno and
                        ooptd.cart_index  = om.cart_index and
                        ooptd.menuno  = om.menuno
                left join menuopt_detail optd
                    on
                        ooptd.storeno     = optd.storeno and
                        ooptd.menuno      = optd.menuno and
                        ooptd.menuoptno    = optd.menuoptno and
                        ooptd.optdetailno  = optd.optdetailno
                left join menu_pic p
                    on
                        optd.storeno   = p.storeno and
                        optd.optdetail_pic = p.pic_index
            order by
                ooptd.storeno,
                ooptd.orderno,
                ooptd.cart_index,
                ooptd.menuno,
                ooptd.menuoptno,
                ooptd.optdetailno
        ";
        return GGsql::select($query, $from, $options);
    }

} /* end class */
?>
