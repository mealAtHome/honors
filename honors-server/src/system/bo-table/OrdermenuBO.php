<?php

class OrdermenuBO extends _CommonBO
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

    const FIELD__STORENO                     = "storeno";
    const FIELD__ORDERNO                     = "orderno";
    const FIELD__CART_INDEX                  = "cart_index";
    const FIELD__MENUNO                      = "menuno";
    const FIELD__MENU_NAME                   = "menu_name";
    const FIELD__MENU_PRICE                  = "menu_price";
    const FIELD__QUANTITY                    = "quantity";
    const FIELD__CARTMENU_SUMMARY_MENU       = "cartmenu_summary_menu";
    const FIELD__CARTMENU_SUMMARY_OPT        = "cartmenu_summary_opt";
    const FIELD__CARTMENU_SUMMARY_RECOMMEND  = "cartmenu_summary_recommend";
    const FIELD__CARTMENU_SUMMARY            = "cartmenu_summary";

    /* ========================= */
    /*  */
    /*
        ■ pk
            - storeno
            - orderno
            - cart_index

        ■ orderno : 주문번호로 조회
            - STORENO
            - ORDERNO


    */
    /* ========================= */
    public function selectByOrdernoForInside($STORENO, $ORDERNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    const selectByOrdernoForInside = "selectByOrdernoForInside";
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
            om.storeno
            ,om.orderno
            ,om.cart_index
            ,om.menuno
            ,om.menu_name
            ,om.menu_price
            ,om.quantity
            ,om.cartmenu_summary_menu
            ,om.cartmenu_summary_opt
            ,om.cartmenu_summary_recommend
            ,om.cartmenu_summary
            ,m.menu_sort
            ,m.menu_comment
            ,m.pic1
            ,m.pic2
            ,m.pic3
            ,m.pic4
            ,m.pic5
            ,m.is_main
            ,m.is_display
            ,m.is_stock
            ,m.is_deleted
            ,m.at_created
            ,m.at_deleted
            ,p1.pic_path pic_path1
            ,p2.pic_path pic_path2
            ,p3.pic_path pic_path3
            ,p4.pic_path pic_path4
            ,p5.pic_path pic_path5
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByOrdernoForInside:
            {
                $from =
                "
                    (
                        select
                            *
                        from
                            ordermenu
                        where
                            storeno = '$STORENO' and
                            orderno = '$ORDERNO'
                    ) om
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
                left join menu m
                    on
                        om.storeno = m.storeno and
                        om.menuno  = m.menuno
                left join menu_pic p1 on m.storeno = p1.storeno and m.pic1 = p1.pic_index
                left join menu_pic p2 on m.storeno = p2.storeno and m.pic2 = p2.pic_index
                left join menu_pic p3 on m.storeno = p3.storeno and m.pic3 = p3.pic_index
                left join menu_pic p4 on m.storeno = p4.storeno and m.pic4 = p4.pic_index
                left join menu_pic p5 on m.storeno = p5.storeno and m.pic5 = p5.pic_index
            order by
                om.storeno,
                om.orderno,
                om.cart_index
        ";
        return GGsql::select($query, $from, $options);
    }

} /* end class */
?>
