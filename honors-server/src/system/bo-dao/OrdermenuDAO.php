<?php

class OrdermenuDAO extends _CommonBO
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

    public function copyCartmenuToOrdermenu($userno, $storeno, $orderno)
    {
        /* vars */

        /* --------------- */
        /* copy record - cartmenu > ordermenu */
        /* --------------- */
        $query =
        "
            insert into ordermenu
            (
                storeno
                ,orderno
                ,cart_index
                ,menuno
                ,menu_name
                ,menu_price
                ,quantity
                ,cartmenu_summary_menu
                ,cartmenu_summary_opt
                ,cartmenu_summary_recommend
                ,cartmenu_summary
            )
            select
                storeno
                ,'$orderno'
                ,cart_index
                ,menuno
                ,menu_name
                ,menu_price
                ,quantity
                ,cartmenu_summary_menu
                ,cartmenu_summary_opt
                ,cartmenu_summary_recommend
                ,cartmenu_summary
            from
                cartmenu
            where
                userno  = '$userno' and
                storeno = '$storeno'
        ";
        $result = GGsql::exeQuery($query);

        /* --------------- */
        /* copy record - cartmenuopt > ordermenuopt */
        /* --------------- */
        $query =
        "
            insert into ordermenuopt
            (
                storeno
                ,orderno
                ,cart_index
                ,menuno
                ,menuoptno
                ,menuopt_name
            )
            select
                storeno
                ,'$orderno'
                ,cart_index
                ,menuno
                ,menuoptno
                ,menuopt_name
            from
                cartmenuopt
            where
                userno  = '$userno' and
                storeno = '$storeno'
        ";
        $result = GGsql::exeQuery($query);

        /* --------------- */
        /* copy record - cartmenuopt_detail > ordermenuopt_detail */
        /* --------------- */
        $query =
        "
            insert into ordermenuopt_detail
            (
                storeno
                ,orderno
                ,cart_index
                ,menuno
                ,menuoptno
                ,optdetailno
                ,optdetail_name
                ,optdetail_menu
                ,optdetail_price
            )
            select
                storeno
                ,'$orderno'
                ,cart_index
                ,menuno
                ,menuoptno
                ,optdetailno
                ,optdetail_name
                ,optdetail_menu
                ,optdetail_price
            from
                cartmenuopt_detail
            where
                userno  = '$userno' and
                storeno = '$storeno'
        ";
        $result = GGsql::exeQuery($query);

        /* --------------- */
        /* copy record - cartmenu_recommend > ordermenu_recommend */
        /* --------------- */
        $query =
        "
            insert into ordermenu_recommend
            (
                storeno
                ,orderno
                ,cart_index
                ,menuno
                ,recommendno
                ,quantity
                ,recommend_name
                ,recommend_menu
                ,recommend_price
                ,recommend_discount
            )
            select
                storeno
                ,'$orderno'
                ,cart_index
                ,menuno
                ,recommendno
                ,quantity
                ,recommend_name
                ,recommend_menu
                ,recommend_price
                ,recommend_discount
            from
                cartmenu_recommend
            where
                userno  = '$userno' and
                storeno = '$storeno'
        ";
        $result = GGsql::exeQuery($query);

        return true;
    }

} /* end class */
?>
