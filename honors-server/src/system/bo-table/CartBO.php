<?php

class CartBO extends _CommonBO
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
    const FIELD__USERNO                  = "userno";                         /* (pk)     int(11) */
    const FIELD__STORENO                 = "storeno";                        /* (pk)     int(11) */
    const FIELD__CART_SUMMARY            = "cart_summary";                   /*          int(11) */
    const FIELD__AT_STARTORDER           = "at_startorder";                  /*          datetime */
    const FIELD__AT_UPDATE               = "at_update";                      /*          datetime */
    const FIELD__AT_CREATE               = "at_create";                      /*          datetime */

    public function __construct()
    {
    }

    public function selectDetail($EXECUTOR)
    {
        /* -------------- */
        /* vars */
        /* -------------- */
        $rslt = Common::getReturn();

        /* get BO */
        GGnavi::getCartmenuBO();
        GGnavi::getCartmenuoptBO();
        GGnavi::getCartmenuoptDetailBO();
        GGnavi::getCartmenuRecommendBO();

        /* BO */
        $cartmenuBO = CartmenuBO::getInstance();
        $cartmenuoptBO = CartmenuoptBO::getInstance();
        $cartmenuoptDetailBO = CartmenuoptDetailBO::getInstance();
        $cartmenuRecommendBO = CartmenuRecommendBO::getInstance();

        /* -------------- */
        /* process */
        /* -------------- */

        /* cart */
        $cartArr = Common::getData($this->selectByUsernoForInside($EXECUTOR));
        foreach($cartArr as &$cart)
        {
            /* cart > cartmenu */
            $userno   = $cart[GGF::USERNO];
            $storeno  = $cart[GGF::STORENO];
            $cart[GGF::CARTMENU] = Common::getData($cartmenuBO->selectByStorenoForInside($userno, $storeno));
            foreach($cart[GGF::CARTMENU] as &$cartmenu)
            {
                /* cart > cartmenu > cartmenuopt */
                $cartIndex = $cartmenu[CartmenuBO::FIELD__CART_INDEX];
                $cartmenu[GGF::CARTMENUOPT] = Common::getData($cartmenuoptBO->selectByCartIndexForInside($userno, $storeno, $cartIndex));
                foreach($cartmenu[GGF::CARTMENUOPT] as &$cartmenuopt)
                {
                    /* cart > cartmenu > cartmenuopt > cartmenuopt_detail */
                    $menuoptno = $cartmenuopt[CartmenuoptDetailBO::FIELD__MENUOPTNO];
                    $cartmenuopt[GGF::CARTMENUOPT_DETAIL] = Common::getData($cartmenuoptDetailBO->selectByMenuoptnoForInside($userno, $storeno, $cartIndex, $menuoptno));
                }
                /* cart > cartmenu > cart_recommend */
                $cartmenu[GGF::CARTMENU_RECOMMEND] = Common::getData($cartmenuRecommendBO->selectByCartIndexForInside($userno, $storeno, $cartIndex));
            }
        }
        $rslt[GGF::DATA] = $cartArr;
        return $rslt;
    }

    /* ========================= */
    /*  */
    /*
    */
    /* ========================= */

    /* select > sub */
    public function selectByPkForInside               ($EXECUTOR, $STORENO)  { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectByUsernoForInside           ($USERNO)              { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectOtherCartByStorenoForInside ($EXECUTOR, $STORENO)  { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* select */
    const selectByPkForInside = "selectByPkForInside";
    const selectByExecutor = "selectByExecutor";
    const selectByUsernoForInside = "selectByUsernoForInside";
    const selectOtherCartByStoreno = "selectOtherCartByStoreno"; /* 이미 다른 카트가 존재하는지 */
    const selectOtherCartByStorenoForInside = "selectOtherCartByStorenoForInside"; /* 이미 다른 카트가 존재하는지 */
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
            c.userno
            ,c.storeno
            ,c.cart_summary
            ,c.at_startorder
            ,c.at_update
            ,c.at_create
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPkForInside                  : { $from = "(select * from cart where userno = '$EXECUTOR' and storeno = '$STORENO') c"; break; }
            case self::selectByExecutor                     : { $from = "(select * from cart where userno = '$EXECUTOR' ) c"; break; }
            case self::selectByUsernoForInside              : { $from = "(select * from cart where userno = '$USERNO' ) c"; break; }
            case self::selectOtherCartByStoreno             : { $from = "(select * from cart where userno = '$EXECUTOR' and storeno <> '$STORENO') c"; break; }
            case self::selectOtherCartByStorenoForInside    : { $from = "(select * from cart where userno = '$EXECUTOR' and storeno <> '$STORENO') c"; break; }
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
            order by
                c.userno,
                c.storeno
        ";
        return GGsql::select($query, $from, $options);
    }

    /* ==================== */
    /*  */
    /*

     */
    /* ==================== */
    public function insertCartForInside               ($USERNO, $STORENO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteWithDetailByUsernoForInside ($USERNO)             { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteWithDetailByPkForInside     ($USERNO, $STORENO)   { return $this->update(get_defined_vars(), __FUNCTION__); }

    const insertCartForInside = "insertCartForInside";
    const insertForReorder = "insertForReorder";
    const deleteWithDetailByUsernoForInside = "deleteWithDetailByUsernoForInside";
    const deleteWithDetailByPk = "deleteWithDetailByPk";
    const deleteWithDetailByPkForInside = "deleteWithDetailByPkForInside";
    protected function update($options, $option="")
    {
        /* -------------- */
        /* get bean */
        /* -------------- */
        GGnavi::getCartDAO();
        $cartDAO = CartDAO::getInstance();

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
            case self::insertCartForInside:
            {
                return $cartDAO->insertCartForInside($USERNO, $STORENO);
                break;
            }
            case self::deleteWithDetailByUsernoForInside:
            {
                $cartArr = Common::getData($this->selectByUsernoForInside($USERNO));
                foreach($cartArr as $cart)
                {
                    $userno = $cart[GGF::USERNO];
                    $storeno = $cart[GGF::STORENO];
                    $this->deleteWithDetailByPkForInside($userno, $storeno);
                }
                break;
            }
            case self::deleteWithDetailByPk:
            {
                $rslt = $this->deleteWithDetailByPkForInside($EXECUTOR, $STORENO);
                break;
            }
            case self::deleteWithDetailByPkForInside:
            {
                /* get BO */
                GGnavi::getCartmenuBO();
                $cartmenuBO = CartmenuBO::getInstance();

                /* process */
                $cartmenuBO->deleteWithDetailByStorenoForInside($USERNO, $STORENO);
                $cartDAO->deleteByPkForInside($USERNO, $STORENO);
                break;
            }
            case self::insertForReorder:
            {
                /* get BO */
                GGnavi::getCartmenuDAO();
                GGnavi::getCartmenuoptBO();
                GGnavi::getCartmenuoptDetailBO();
                GGnavi::getCartmenuRecommendBO();
                $cartmenuDAO = CartmenuDAO::getInstance();
                $cartmenuoptBO = CartmenuoptBO::getInstance();
                $cartmenuoptDetailBO = CartmenuoptDetailBO::getInstance();
                $cartmenuRecommendBO = CartmenuRecommendBO::getInstance();

                /* clear cart all */
                /* TODO : 나중에는 여러 스토어에서 한 번에 주문할 수 있도록 함. */
                $this->deleteWithDetailByUsernoForInside($EXECUTOR);

                /* cart */               $cartDAO->insertCartForInside($EXECUTOR, $STORENO);
                /* cartmenu */           $cartmenuDAO->insertFromOrderForInside($EXECUTOR, $STORENO, $ORDERNO);
                /* cartmenuopt */        $cartmenuoptBO->insertFromOrderForInside($EXECUTOR, $STORENO, $ORDERNO);
                /* cartmenuoptDetail */  $cartmenuoptDetailBO->insertFromOrderForInside($EXECUTOR, $STORENO, $ORDERNO);
                /* cartmenuRecommend */  $cartmenuRecommendBO->insertFromOrderForInside($EXECUTOR, $STORENO, $ORDERNO);

                /* 카트 가격계산 */
                $this->recalCartSummary($EXECUTOR);
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
                break;
            }
        }
        return $rslt;
    }

    public function recalCartSummary($USERNO, $throw=false)
    {
        /* get bo */
        GGnavi::getCartDAO();
        GGnavi::getCartmenuBO();
        GGnavi::getCartmenuDAO();

        /* bo */
        $cartDAO = CartDAO::getInstance();
        $cartmenuBO = CartmenuBO::getInstance();
        $cartmenuDAO = CartmenuDAO::getInstance();

        /* option data */
        $cartArr = Common::getData($this->selectDetail($USERNO));

        /* --------------- */
        /* loop cart */
        /* --------------- */
        foreach($cartArr as $cart)
        {
            /* vars */
            $userno = $cart[GGF::USERNO];
            $storeno = $cart[GGF::STORENO];
            $cartSummary = 0; /* 금액총합 */

            /* --------------- */
            /* 메뉴루프 */
            /* --------------- */
            foreach($cart[GGF::CARTMENU] as $cartmenu)
            {
                /* vars */
                $cartIndex = $cartmenu[CartmenuBO::FIELD__CART_INDEX];
                $quantity  = $cartmenu[CartmenuBO::FIELD__QUANTITY];
                $menuPrice = $cartmenu[CartmenuBO::FIELD__MENU_PRICE];

                /* summary */
                $cartmenuSummaryMenu      = $quantity * $menuPrice;
                $cartmenuSummaryOpt       = 0;
                $cartmenuSummaryRecommend = 0;
                $cartmenuSummary          = 0;

                /* --------------- */
                /* 메뉴옵션가격합계 */
                /* --------------- */
                foreach($cartmenu[GGF::CARTMENUOPT] as $cartmenuopt)
                {
                    foreach($cartmenuopt[GGF::CARTMENUOPT_DETAIL] as $cartmenuoptDetail)
                    {
                        $optdetailPrice = $cartmenuoptDetail[CartmenuoptDetailBO::FIELD__OPTDETAIL_PRICE];
                        $cartmenuSummaryOpt += ($quantity * $optdetailPrice);
                    }
                }

                /* --------------- */
                /* 추천메뉴루프 */
                /* --------------- */
                foreach($cartmenu[GGF::CARTMENU_RECOMMEND] as $cartmenuRecommend)
                {
                    $cartmenuRecommendQuantity = $cartmenuRecommend[CartmenuBO::FIELD__QUANTITY];
                    $cartmenuRecommendPrice    = $cartmenuRecommend[CartmenuRecommendBO::FIELD__RECOMMEND_DISCOUNT];
                    $cartmenuSummaryRecommend += $quantity * ($cartmenuRecommendQuantity * $cartmenuRecommendPrice);
                }

                /* cart menu summary */
                $cartmenuSummary += $cartmenuSummaryMenu + $cartmenuSummaryOpt + $cartmenuSummaryRecommend;

                /* +++++++++++++++ */
                /* update cart_summary */
                /* +++++++++++++++ */
                $cartmenuDAO->updateCartmenuSummaryForInside(
                    $userno,
                    $storeno,
                    $cartIndex,
                    $cartmenuSummaryMenu,
                    $cartmenuSummaryOpt,
                    $cartmenuSummaryRecommend,
                    $cartmenuSummary
                );

                /* cal cartSummary */
                $cartSummary += $cartmenuSummary;
            }

            /* update cart */
            $cartDAO->updateCartSummaryForInside($userno, $storeno, $cartSummary);
        }
    }

    public function validateCartSummary($USERNO, $STORENO)
    {
        /* get bo */
        GGnavi::getCartDAO();
        GGnavi::getMenuBO();
        GGnavi::getMenuoptBO();
        GGnavi::getMenuoptDetailBO();
        GGnavi::getMenuRecommendBO();
        GGnavi::getCartmenuBO();
        GGnavi::getCartmenuoptBO();
        GGnavi::getCartmenuoptDetailBO();
        GGnavi::getCartmenuRecommendBO();

        /* bo */
        $cartDAO = CartDAO::getInstance();
        $cartmenuDAO = CartmenuDAO::getInstance();

        /* vars */
        $menuPriceChanged    = false;
        $menuPriceChangedMsg = "";

        /* option data */
        $cartArr = Common::getData($this->selectDetail($USERNO));

        /* --------------- */
        /* loop cart */
        /* --------------- */
        try
        {
            foreach($cartArr as $cart)
            {
                /* vars */
                $userno = $cart[GGF::USERNO];
                $storeno = $cart[GGF::STORENO];
                $cartSummary = 0; /* 금액총합 */

                /* --------------- */
                /* 메뉴루프 */
                /* --------------- */
                foreach($cart[GGF::CARTMENU] as $cartmenu)
                {
                    /* vars */
                    $menuName              = $cartmenu[CartmenuBO::FIELD__MENU_NAME];
                    $cartIndex             = $cartmenu[CartmenuBO::FIELD__CART_INDEX];
                    $quantity              = $cartmenu[CartmenuBO::FIELD__QUANTITY];
                    $menuPrice             = $cartmenu[CartmenuBO::FIELD__MENU_PRICE];
                    $cartmenuSummaryOrigin = $cartmenu[CartmenuBO::FIELD__CARTMENU_SUMMARY];

                    /* validate : is menu available */
                    if($cartmenu[MenuBO::FIELD__IS_DISPLAY]  == GGF::N) { throw new GGexception("$menuName 은(는) 현재 판매중인 상품이 아닙니다."); }
                    if($cartmenu[MenuBO::FIELD__IS_STOCK]    == GGF::N) { throw new GGexception("$menuName 은(는) 현재 재고가 없습니다."); }
                    if($cartmenu[MenuBO::FIELD__IS_DELETED]  == GGF::Y) { throw new GGexception("$menuName 은(는) 삭제된 메뉴입니다."); }

                    /* summary */
                    $cartmenuSummaryMenu      = $quantity * $menuPrice;
                    $cartmenuSummaryOpt       = 0;
                    $cartmenuSummaryRecommend = 0;
                    $cartmenuSummary          = 0;

                    /* --------------- */
                    /* 메뉴옵션가격합계 */
                    /* --------------- */
                    foreach($cartmenu[GGF::CARTMENUOPT] as $cartmenuopt)
                    {
                        /* validate : is menu available */
                        $menuoptName = $cartmenuopt[CartmenuoptBO::FIELD__MENUOPT_NAME];
                        if($cartmenuopt[MenuoptBO::FIELD__IS_DISPLAY]  == GGF::N) { throw new GGexception("$menuName > $menuoptName 은(는) 현재 판매중인 상품이 아닙니다."); }
                        if($cartmenuopt[MenuoptBO::FIELD__IS_STOCK]    == GGF::N) { throw new GGexception("$menuName > $menuoptName 은(는) 현재 재고가 없습니다."); }
                        if($cartmenuopt[MenuoptBO::FIELD__IS_DELETED]  == GGF::Y) { throw new GGexception("$menuName > $menuoptName 은(는) 삭제된 메뉴입니다."); }

                        foreach($cartmenuopt[GGF::CARTMENUOPT_DETAIL] as $cartmenuoptDetail)
                        {
                            /* validate : is menu available */
                            $menuoptDetailName = $cartmenuoptDetail[CartmenuoptDetailBO::FIELD__OPTDETAIL_NAME];
                            if($cartmenuoptDetail[MenuoptBO::FIELD__IS_DISPLAY]  == GGF::N) { throw new GGexception("$menuName > $menuoptName > $menuoptDetailName 은(는) 현재 판매중인 상품이 아닙니다."); }
                            if($cartmenuoptDetail[MenuoptBO::FIELD__IS_STOCK]    == GGF::N) { throw new GGexception("$menuName > $menuoptName > $menuoptDetailName 은(는) 현재 재고가 없습니다."); }
                            if($cartmenuoptDetail[MenuoptBO::FIELD__IS_DELETED]  == GGF::Y) { throw new GGexception("$menuName > $menuoptName > $menuoptDetailName 은(는) 삭제된 메뉴입니다."); }

                            /* summary */
                            $optdetailPrice = $cartmenuoptDetail[CartmenuoptDetailBO::FIELD__OPTDETAIL_PRICE];
                            $cartmenuSummaryOpt += ($quantity * $optdetailPrice);
                        }
                    }

                    /* --------------- */
                    /* 추천메뉴루프 */
                    /* --------------- */
                    foreach($cartmenu[GGF::CARTMENU_RECOMMEND] as $cartmenuRecommend)
                    {
                        $cartmenuRecommendQuantity = $cartmenuRecommend[CartmenuBO::FIELD__QUANTITY];
                        $cartmenuRecommendPrice    = $cartmenuRecommend[CartmenuRecommendBO::FIELD__RECOMMEND_DISCOUNT];
                        $cartmenuSummaryRecommend += $quantity * ($cartmenuRecommendQuantity * $cartmenuRecommendPrice);
                    }

                    /* cart menu summary */
                    $cartmenuSummary += $cartmenuSummaryMenu + $cartmenuSummaryOpt + $cartmenuSummaryRecommend;

                    if($cartmenuSummary != $cartmenuSummaryOrigin)
                    {
                        $menuPriceChanged = true;
                        $menuPriceChangedMsg = "카트에 계산된 가격과 메뉴의 가격이 일치하지 않기에, 카트 가격을 다시 계산하였습니다. 가격을 확인하시고 다시 주문하기 버튼을 눌러주세요.";
                    }

                    /* 카트정보 업데이트 */
                    $cartmenuDAO->updateCartmenuSummaryForInside(
                        $userno,
                        $storeno,
                        $cartIndex,
                        $cartmenuSummaryMenu,
                        $cartmenuSummaryOpt,
                        $cartmenuSummaryRecommend,
                        $cartmenuSummary
                    );

                    /* cal cartSummary */
                    $cartSummary += $cartmenuSummary;
                }

                /* update cart */
                $cartDAO->updateCartSummaryForInside($userno, $storeno, $cartSummary);
            }

            /* --------------- */
            /* 에러 있을 시 반환 */
            /* --------------- */
            if($menuPriceChanged == true)
                throw new GGexception($menuPriceChangedMsg);
        }
        catch(GGexception $e)
        {
            throw $e;
        }
        return true;
    }

} /* end class */
?>
