<?php

/* omr : ordermenu_recommend */
class OrdermenuRecommendBO extends _CommonBO
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

    const FIELD__STORENO             = "storeno";               /* pk */
    const FIELD__ORDERNO             = "orderno";               /* pk */
    const FIELD__CART_INDEX          = "cart_index";            /* pk */
    const FIELD__MENUNO              = "menuno";                /* pk */
    const FIELD__RECOMMENDNO         = "recommendno";           /* pk */
    const FIELD__QUANTITY            = "quantity";              /*  */
    const FIELD__RECOMMEND_NAME      = "recommend_name";        /*  */
    const FIELD__RECOMMEND_MENU      = "recommend_menu";        /*  */
    const FIELD__RECOMMEND_PRICE     = "recommend_price";       /*  */
    const FIELD__RECOMMEND_DISCOUNT  = "recommend_discount";    /*  */

    /* ========================= */
    /*  */
    /*
        ■ pk
            - storeno
            - orderno
            - cart_index
            - recommendno

        ■ cartIndex : 카트 인덱스로 조회
            - STORENO
            - ORDERNO
            - CART_INDEX


    */
    /* ========================= */
    public function selectByCartIndexForInside($STORENO, $ORDERNO, $CART_INDEX) { return $this->select(get_defined_vars(), __FUNCTION__); }

    const selectByCartIndexForInside = "selectByCartIndexForInside";
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
             omr.storeno
            ,omr.orderno
            ,omr.cart_index
            ,omr.menuno
            ,omr.recommendno
            ,omr.quantity
            ,omr.recommend_name
            ,omr.recommend_menu
            ,omr.recommend_price
            ,omr.recommend_discount
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
            case self::selectByCartIndexForInside: { $from = "(select * from ordermenu_recommend where storeno = '$STORENO' and orderno = '$ORDERNO' and cart_index = $CART_INDEX) omr "; break; }
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
                left join ordermenu om
                    on
                        omr.storeno     = om.storeno and
                        omr.orderno         = om.orderno and
                        omr.cart_index      = om.cart_index
                left join menu_recommend mr
                    on
                        omr.storeno     = mr.storeno and
                        omr.menuno      = mr.menuno and
                        omr.recommendno  = mr.recommendno
                left join menu_pic p
                    on
                        mr.storeno    = p.storeno and
                        mr.recommend_pic  = p.pic_index
            order by
                omr.storeno,
                omr.orderno,
                omr.cart_index,
                omr.menuno,
                omr.recommendno
        ";
        return GGsql::select($query, $from, $options);
    }

} /* end class */
?>
