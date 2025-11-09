<?php

class CartmenuDAO extends _CommonBO
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

    public function makeSelectQuery($from)
    {
        $query =
        "
            select
                  cm.userno
                , cm.storeno
                , cm.cart_index
                , cm.menuno
                , cm.menu_name
                , cm.menu_price
                , cm.quantity
                , cm.cartmenu_summary_menu
                , cm.cartmenu_summary_opt
                , cm.cartmenu_summary_recommend
                , cm.cartmenu_summary
                , m.menu_sort
                , m.menu_comment
                , m.pic1
                , m.pic2
                , m.pic3
                , m.pic4
                , m.pic5
                , m.is_main
                , m.is_display
                , m.is_stock
                , m.is_deleted
                , m.at_created
                , m.at_deleted
                , p1.pic_path pic_path1
                , p2.pic_path pic_path2
                , p3.pic_path pic_path3
                , p4.pic_path pic_path4
                , p5.pic_path pic_path5
            from
                $from
                left join menu m
                    on
                        cm.storeno = m.storeno and
                        cm.menuno  = m.menuno
                left join menu_pic p1 on m.storeno = p1.storeno and m.pic1 = p1.pic_index
                left join menu_pic p2 on m.storeno = p2.storeno and m.pic2 = p2.pic_index
                left join menu_pic p3 on m.storeno = p3.storeno and m.pic3 = p3.pic_index
                left join menu_pic p4 on m.storeno = p4.storeno and m.pic4 = p4.pic_index
                left join menu_pic p5 on m.storeno = p5.storeno and m.pic5 = p5.pic_index
            order by
                cm.userno,
                cm.storeno,
                cm.cart_index
        ";
        return $query;
    }

    /* ========================= */
    /*  */
    /* ========================= */
    public function insertForInside($USERNO, $STORENO, $MENUNO, $CART_INDEX, $QUANTITY) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function insertFromOrderForInside($USERNO, $STORENO, $ORDERNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function updateCartmenuSummaryForInside(
          $USERNO
        , $STORENO
        , $CART_INDEX
        , $CARTMENU_SUMMARY_MENU
        , $CARTMENU_SUMMARY_OPT
        , $CARTMENU_SUMMARY_RECOMMEND
        , $CARTMENU_SUMMARY
    )
    {
        return $this->update(get_defined_vars(), __FUNCTION__);
    }
    public function deleteByPkForInside($USERNO, $STORENO, $CART_INDEX) { return $this->update(get_defined_vars(), __FUNCTION__); }

    const insertForInside = "insertForInside"; /*  */
    const insertFromOrderForInside = "insertFromOrderForInside";  /* 재주문 : 주문으로부터 레코드 삽입 */
    const updateCartmenuSummaryForInside = "updateCartmenuSummaryForInside";
    const deleteByPkForInside = "deleteByPkForInside";
    public function update($options, $option="")
    {
        /* -------------- */
        /* vars */
        /* -------------- */
        $cartIndex = 0;
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* result */
        $rslt = Common::getReturn();

        switch($OPTION)
        {
            case self::insertForInside:
            {
                $cartIndex = $CART_INDEX;
                $query =
                "
                    insert into cartmenu
                    (
                          userno
                        , storeno
                        , cart_index
                        , menuno
                        , menu_name
                        , menu_price
                        , quantity
                    )
                    select
                          '$USERNO'
                        ,  storeno
                        ,  $CART_INDEX
                        ,  menuno
                        ,  menu_name
                        ,  menu_price
                        ,  $QUANTITY
                    from
                        menu
                    where
                        storeno = '$STORENO' and
                        menuno  =  $MENUNO
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::insertFromOrderForInside:
            {
                $query =
                "
                    insert into cartmenu
                    (
                          userno
                        , storeno
                        , cart_index
                        , menuno
                        , menu_name
                        , menu_price
                        , quantity
                    )
                    select
                          '$USERNO'
                        ,  om.storeno
                        ,  om.cart_index
                        ,  m.menuno
                        ,  m.menu_name
                        ,  m.menu_price
                        ,  om.quantity
                    from
                        ordermenu om
                        left join menu m
                            on
                                om.storeno = m.storeno and
                                om.menuno  = m.menuno
                    where
                        om.storeno = '$STORENO' and
                        om.orderno = '$ORDERNO'
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::updateCartmenuSummaryForInside:
            {
                $query =
                "
                    update
                        cartmenu
                    set
                        cartmenu_summary_menu      = $CARTMENU_SUMMARY_MENU,
                        cartmenu_summary_opt       = $CARTMENU_SUMMARY_OPT,
                        cartmenu_summary_recommend = $CARTMENU_SUMMARY_RECOMMEND,
                        cartmenu_summary           = $CARTMENU_SUMMARY
                    where
                        userno     = '$USERNO' and
                        storeno    = '$STORENO' and
                        cart_index =  $CART_INDEX
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::deleteByPkForInside:
            {
                $query = "delete from cartmenu where userno = '$USERNO' and storeno = '$STORENO' and cart_index = $CART_INDEX";
                $result = GGsql::exeQuery($query);
                break;
            }
        }
        return $cartIndex;
    }

} /* end class */
?>
