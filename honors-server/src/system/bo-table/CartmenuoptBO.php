<?php

class CartmenuoptBO extends _CommonBO
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

    const FIELD__USERNO        = "userno";         /* (pk) int(11) */
    const FIELD__STORENO       = "storeno";        /* (pk) int(11) */
    const FIELD__CART_INDEX    = "cart_index";     /* (pk) int(11) */
    const FIELD__MENUNO        = "menuno";         /* (pk) int(11) */
    const FIELD__MENUOPTNO     = "menuoptno";      /* (pk) int(11) */
    const FIELD__MENUOPT_NAME  = "menuopt_name";   /*      char(50) */

    /* ========================= */
    /* */
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
              copt.userno
            , copt.storeno
            , copt.cart_index
            , copt.menuno
            , copt.menuoptno
            , copt.menuopt_name
            ,  opt.menuopt_chooseablecnt_min
            ,  opt.menuopt_chooseablecnt_max
            ,  opt.menuopt_comment
            ,  opt.is_display
            ,  opt.is_stock
            ,  opt.is_deleted
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
                            cartmenuopt
                        where
                            userno  = '$USERNO' and
                            storeno = '$STORENO' and
                            cart_index  = $CART_INDEX
                    ) copt
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
                        copt.userno   = c.userno and
                        copt.storeno  = c.storeno and
                        copt.cart_index   = c.cart_index
                left join menuopt opt
                    on
                        copt.storeno  = opt.storeno and
                        copt.menuno   = opt.menuno and
                        copt.menuoptno = opt.menuoptno
            order by
                copt.userno,
                copt.storeno,
                copt.cart_index,
                copt.menuno,
                copt.menuoptno
        ";
        return GGsql::select($query, $from, $options);
    }


    /* ========================= */
    /* */
    /*
    */
    /* ========================= */
    public function insertCartmenuoptForInside              ($USERNO, $STORENO, $CART_INDEX, $MENUNO, $MENUOPTNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function insertFromOrderForInside                ($USERNO, $STORENO, $ORDERNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteWithDetailByCartIndexForInside    ($USERNO, $STORENO, $CART_INDEX) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteWithDetailByPkForInside           ($USERNO, $STORENO, $CART_INDEX, $MENUNO, $MENUOPTNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteByPkForInside                     ($USERNO, $STORENO, $CART_INDEX, $MENUNO, $MENUOPTNO) { return $this->update(get_defined_vars(), __FUNCTION__); }

    const insertCartmenuoptForInside = "insertCartmenuoptForInside";
    const insertFromOrderForInside = "insertFromOrderForInside";
    const deleteWithDetailByCartIndexForInside = "deleteWithDetailByCartIndexForInside";
    const deleteWithDetailByPkForInside = "deleteWithDetailByPkForInside";
    const deleteByPkForInside = "deleteByPkForInside";
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
            case self::insertCartmenuoptForInside:
            {
                $query =
                "
                    insert into cartmenuopt
                    (
                          userno
                        , storeno
                        , cart_index
                        , menuno
                        , menuoptno
                        , menuopt_name
                    )
                    select
                          '$USERNO'
                        ,  storeno
                        ,  $CART_INDEX
                        ,  menuno
                        ,  menuoptno
                        ,  menuopt_name
                    from
                        menuopt
                    where
                        storeno = '$STORENO' and
                        menuno = $MENUNO and
                        menuoptno = $MENUOPTNO
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::insertFromOrderForInside:
            {
                $query =
                "
                    insert into cartmenuopt
                    (
                          userno
                        , storeno
                        , cart_index
                        , menuno
                        , menuoptno
                        , menuopt_name
                    )
                    select
                          '$USERNO'
                        ,  omopt.storeno
                        ,  omopt.cart_index
                        ,  omopt.menuno
                        ,  omopt.menuoptno
                        ,  opt.menuopt_name
                    from
                        ordermenuopt omopt
                        left join menuopt opt
                            on
                                omopt.storeno = opt.storeno and
                                omopt.menuno = opt.menuno and
                                omopt.menuoptno = opt.menuoptno
                    where
                        omopt.storeno = '$STORENO' and
                        omopt.orderno = '$ORDERNO'
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::deleteWithDetailByCartIndexForInside:
            {
                $cartmenuopts = Common::getData($this->selectByCartIndexForInside($USERNO, $STORENO, $CART_INDEX));
                foreach($cartmenuopts as $cartmenuopt)
                {
                    $menuno = $cartmenuopt[self::FIELD__MENUNO];
                    $menuoptno = $cartmenuopt[self::FIELD__MENUOPTNO];
                    $this->deleteWithDetailByPkForInside($USERNO, $STORENO, $CART_INDEX, $menuno, $menuoptno);
                }
                break;
            }
            case self::deleteWithDetailByPkForInside:
            {
                /* get BO */
                GGnavi::getCartmenuoptDetailBO();
                $CartmenuoptDetailBO = CartmenuoptDetailBO::getInstance();

                /* process */
                $this->deleteByPkForInside($USERNO, $STORENO, $CART_INDEX, $MENUNO, $MENUOPTNO);
                $CartmenuoptDetailBO->deleteByMenuoptnoForInside($USERNO, $STORENO, $CART_INDEX, $MENUNO, $MENUOPTNO);
                break;
            }
            case self::deleteByPkForInside:
            {
                $query =
                "
                    delete from
                        cartmenuopt
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
