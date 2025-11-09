<?php

class CartmenuoptDetailBO extends _CommonBO
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

    const FIELD__USERNO             = "userno";              /* (pk) int(11) */
    const FIELD__STORENO            = "storeno";             /* (pk) int(11) */
    const FIELD__CART_INDEX         = "cart_index";          /* (pk) int(11) */
    const FIELD__MENUNO             = "menuno";              /* (pk) int(11) */
    const FIELD__MENUOPTNO          = "menuoptno";           /* (pk) int(11) */
    const FIELD__OPTDETAILNO        = "optdetailno";         /* (pk) int(11) */
    const FIELD__OPTDETAIL_NAME     = "optdetail_name";      /*      char(50) */
    const FIELD__OPTDETAIL_MENU     = "optdetail_menu";      /*      int(11) */
    const FIELD__OPTDETAIL_PRICE    = "optdetail_price";     /*      int(11) */

    /* ========================= */
    /*  */
    /*
    */
    /* ========================= */

    public function selectByMenuoptnoForInside ($USERNO, $STORENO, $CART_INDEX, $MENUOPTNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    const selectByMenuoptnoForInside = "selectByMenuoptnoForInside";
    protected function select($options, $option="")
    {
        /* -------------- */
        /* vars */
        /* -------------- */
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* -------------- */
        /* vars */
        /* -------------- */
        $Y = GGF::Y;
        $N = GGF::N;

        /* --------------- */
        /* sql body */
        /* --------------- */
        $query  = "";
        $select = "";
        $from   = "";

        $select =
        "
             coptd.userno
            ,coptd.cart_index
            ,coptd.menuno
            ,coptd.menuoptno
            ,coptd.optdetailno
            ,coptd.optdetail_name
            ,coptd.optdetail_menu
            ,coptd.optdetail_price
            , optd.storeno
            , optd.optdetail_pic
            , optd.optdetail_comment
            , optd.is_display
            , optd.is_stock
            , optd.is_deleted
            ,    p.pic_name
            ,    p.pic_kind
            ,    p.pic_path
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByMenuoptnoForInside:
            {
                $from =
                "
                    (
                        select
                            *
                        from
                            cartmenuopt_detail
                        where
                            userno       = '$USERNO' and
                            storeno      = '$STORENO' and
                            cart_index   =  $CART_INDEX and
                            menuoptno    =  $MENUOPTNO
                    ) coptd
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
                left join cartmenu c
                    on
                        coptd.userno  = c.userno and
                        coptd.storeno = c.storeno and
                        coptd.cart_index  = c.cart_index
                left join menuopt_detail optd
                    on
                        coptd.storeno    = optd.storeno and
                        coptd.menuno     = optd.menuno and
                        coptd.menuoptno   = optd.menuoptno and
                        coptd.optdetailno = optd.optdetailno
                left join menu_pic p
                    on
                        optd.storeno   = p.storeno and
                        optd.optdetail_pic = p.pic_index
            order by
                coptd.userno,
                coptd.cart_index,
                coptd.menuno,
                coptd.menuoptno,
                coptd.optdetailno
        ";
        return GGsql::select($query, $from, $options);
    }

    /* ========================= */
    /* */
    /*
    */
    /* ========================= */
    public function insertCartmenuoptDetailForInside    ($USERNO, $CART_INDEX, $STORENO, $MENUNO, $MENUOPTNO, $OPTDETAILNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function insertFromOrderForInside            ($USERNO, $STORENO, $ORDERNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteByMenuoptnoForInside          ($USERNO, $STORENO, $CART_INDEX, $MENUNO, $MENUOPTNO) { return $this->update(get_defined_vars(), __FUNCTION__); }

    const insertCartmenuoptDetailForInside = "insertCartmenuoptDetailForInside";
    const insertFromOrderForInside = "insertFromOrderForInside";
    const deleteByMenuoptnoForInside = "deleteByMenuoptnoForInside";
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
            case self::insertCartmenuoptDetailForInside:
            {
                $query =
                "
                    insert into cartmenuopt_detail
                    (
                        userno
                        ,storeno
                        ,cart_index
                        ,menuno
                        ,menuoptno
                        ,optdetailno
                        ,optdetail_name
                        ,optdetail_menu
                        ,optdetail_price
                    )
                    select
                          '$USERNO'
                        ,  storeno
                        ,  $CART_INDEX
                        ,  menuno
                        ,  menuoptno
                        ,  optdetailno
                        ,  optdetail_name
                        ,  optdetail_menu
                        ,  optdetail_price
                    from
                        menuopt_detail
                    where
                        storeno     = '$STORENO' and
                        menuno      =  $MENUNO and
                        menuoptno   =  $MENUOPTNO and
                        optdetailno =  $OPTDETAILNO
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::insertFromOrderForInside:
            {
                $query =
                "
                    insert into cartmenuopt_detail
                    (
                          userno
                        , storeno
                        , cart_index
                        , menuno
                        , menuoptno
                        , optdetailno
                        , optdetail_name
                        , optdetail_menu
                        , optdetail_price
                    )
                    select
                          '$USERNO'
                        ,  omoptd.storeno
                        ,  omoptd.cart_index
                        ,  omoptd.menuno
                        ,  omoptd.menuoptno
                        ,  omoptd.optdetailno
                        ,  optd.optdetail_name
                        ,  optd.optdetail_menu
                        ,  optd.optdetail_price
                    from
                        ordermenuopt_detail omoptd
                        left join menuopt_detail optd
                            on
                                omoptd.storeno     = optd.storeno and
                                omoptd.menuno      = optd.menuno and
                                omoptd.menuoptno   = optd.menuoptno and
                                omoptd.optdetailno = optd.optdetailno
                    where
                        omoptd.storeno = '$STORENO' and
                        omoptd.orderno = '$ORDERNO'
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::deleteByMenuoptnoForInside:
            {
                $query =
                "
                    delete from
                        cartmenuopt_detail
                    where
                        userno     = '$USERNO' and
                        storeno    = '$STORENO' and
                        cart_index =  $CART_INDEX and
                        menuno     =  $MENUNO and
                        menuoptno  =  $MENUOPTNO
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
        }
        return $rslt;
    }

} /* end class */
?>
