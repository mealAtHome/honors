<?php

class CartmenuRecommendBO extends _CommonBO
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

    const FIELD__USERNO                 = "userno";                 /* (pk) int(11) */
    const FIELD__STORENO                = "storeno";                /* (pk) int(11) */
    const FIELD__CART_INDEX             = "cart_index";             /* (pk) int(11) */
    const FIELD__RECOMMENDNO            = "recommendno";            /* (pk) int(11) */
    const FIELD__QUANTITY               = "quantity";               /*      int(11) */
    const FIELD__RECOMMEND_NAME         = "recommend_name";         /*      char(50) */
    const FIELD__RECOMMEND_MENU         = "recommend_menu";         /*      int(11) */
    const FIELD__RECOMMEND_PRICE        = "recommend_price";        /*      int(11) */
    const FIELD__RECOMMEND_DISCOUNT     = "recommend_discount";     /*      int(11) */

    /* ========================= */
    /*  */
    /*

    */
    /* ========================= */
    public function selectByCartIndexForInside($USERNO, $STORENO, $CART_INDEX) { return $this->select(get_defined_vars(), __FUNCTION__); }

    const selectByCartIndexForInside = "selectByCartIndexForInside";
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
            cr.userno
            ,cr.storeno
            ,cr.cart_index
            ,cr.recommendno
            ,cr.quantity
            ,cr.recommend_name
            ,cr.recommend_menu
            ,cr.recommend_price
            ,cr.recommend_discount
            ,mr.storeno
            ,mr.menuno
            ,mr.recommend_choosablecnt_max
            ,mr.recommend_comment
            ,mr.recommend_pic
            ,mr.is_display
            ,mr.is_stock
            ,mr.is_deleted
            , p.pic_name
            , p.pic_kind
            , p.pic_path
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByCartIndexForInside:
            {
                $from =
                "
                    (
                        select
                            *
                        from
                            cartmenu_recommend
                        where
                            userno = '$USERNO' and
                            storeno = '$STORENO' and
                            cart_index = $CART_INDEX
                    ) cr
                ";
                break;
            }
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
                left join cartmenu c
                    on
                        cr.userno     = c.userno and
                        cr.storeno    = c.storeno and
                        cr.cart_index     = c.cart_index
                left join menu_recommend mr
                    on
                        c.storeno     = mr.storeno and
                        c.menuno      = mr.menuno and
                        cr.recommendno = mr.recommendno
                left join menu_pic p
                    on
                        mr.storeno    = p.storeno and
                        mr.recommend_pic  = p.pic_index
            order by
                cr.userno,
                cr.storeno,
                cr.cart_index,
                cr.recommendno
        ";
        return GGsql::select($query, $from, $options);
    }

    /* ========================= */
    /* */
    /*
    */
    /* ========================= */
    public function insertCartmenuRecommendForInside    ($USERNO, $CART_INDEX, $QUANTITY, $STORENO, $MENUNO, $RECOMMENDNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function insertFromOrderForInside            ($USERNO, $STORENO, $ORDERNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteByCartIndexForInside          ($USERNO, $STORENO, $CART_INDEX) { return $this->update(get_defined_vars(), __FUNCTION__); }

    const insertCartmenuRecommendForInside = "insertCartmenuRecommendForInside";
    const insertFromOrderForInside = "insertFromOrderForInside";
    const deleteByCartIndexForInside = "deleteByCartIndexForInside";
    protected function update($options, $option="")
    {
        /* -------------- */
        /* vars */
        /* -------------- */
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* result */
        $rslt = Common::getReturn();

        /* ==================== */
        /* process */
        /* ==================== */
        switch($OPTION)
        {
            case self::insertCartmenuRecommendForInside:
            {
                $query =
                "
                    insert into cartmenu_recommend
                    (
                        userno
                        ,storeno
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
                          '$USERNO'
                        ,  storeno
                        ,  $CART_INDEX
                        ,  menuno
                        ,  recommendno
                        ,  $QUANTITY
                        ,  recommend_name
                        ,  recommend_menu
                        ,  recommend_price
                        ,  recommend_discount
                    from
                        menu_recommend
                    where
                        storeno     = '$STORENO' and
                        menuno      =  $MENUNO and
                        recommendno =  $RECOMMENDNO
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::insertFromOrderForInside:
            {
                $query =
                "
                    insert into cartmenu_recommend
                    (
                        userno
                        ,storeno
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
                          '$USERNO'
                        ,  omr.storeno
                        ,  omr.cart_index
                        ,  omr.menuno
                        ,  omr.recommendno
                        ,  omr.quantity
                        ,  mr.recommend_name
                        ,  mr.recommend_menu
                        ,  mr.recommend_price
                        ,  mr.recommend_discount
                    from
                        ordermenu_recommend omr
                        left join menu_recommend mr
                            on
                                omr.storeno = mr.storeno and
                                omr.menuno = mr.menuno and
                                omr.recommendno = mr.recommendno
                    where
                        omr.storeno = '$STORENO' and
                        omr.orderno = '$ORDERNO'
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::deleteByCartIndexForInside:
            {
                $query =
                "
                    delete from cartmenu_recommend
                    where
                        userno     = '$USERNO' and
                        storeno    = '$STORENO' and
                        cart_index =  $CART_INDEX
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
        }
        return $rslt;
    }

} /* end class */
?>
