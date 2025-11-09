<?php

class CartmenuBO extends _CommonBO
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
        GGnavi::getCartmenuDAO();
        GGnavi::getCartBO();
        GGnavi::getCartmenuoptBO();
        GGnavi::getCartmenuoptDetailBO();
        GGnavi::getCartmenuRecommendBO();
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    const FIELD__USERNO                         = "userno";                           /* (pk) int(11) */
    const FIELD__STORENO                        = "storeno";                          /* (pk) int(11) */
    const FIELD__CART_INDEX                     = "cart_index";                       /* (pk) int(11) */
    const FIELD__MENUNO                         = "menuno";                           /*      int(11) */
    const FIELD__MENU_NAME                      = "menu_name";                        /*      char(100) */
    const FIELD__MENU_PRICE                     = "menu_price";                       /*      int(11) */
    const FIELD__QUANTITY                       = "quantity";                         /*      int(11) */
    const FIELD__CARTMENU_SUMMARY_MENU          = "cartmenu_summary_menu";            /*      int(11) */
    const FIELD__CARTMENU_SUMMARY_OPT           = "cartmenu_summary_opt";             /*      int(11) */
    const FIELD__CARTMENU_SUMMARY_RECOMMEND     = "cartmenu_summary_recommend";       /*      int(11) */
    const FIELD__CARTMENU_SUMMARY               = "cartmenu_summary";                 /*      int(11) */

    public function selectCartmenuDetail($userno, $storeno, $cartIndex)
    {
        /* -------------- */
        /* vars */
        /* -------------- */
        $rslt = Common::getReturn();

        /* BO */
        $cartmenuoptBO       = CartmenuoptBO::getInstance();
        $cartmenuoptDetailBO = CartmenuoptDetailBO::getInstance();
        $cartmenuRecommendBO = CartmenuRecommendBO::getInstance();

        /* ============================ */
        /* process */
        /* ============================ */

        /* 카트메뉴 */
        $cart[GGF::CARTMENU] = Common::getData($this->selectByPkForInside($userno, $storeno, $cartIndex));

        /* 카트 루프 */
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
        $rslt[GGF::DATA] = $cart[GGF::CARTMENU];
        return $rslt;
    }


    /* ========================= */
    /*  */
    /*
    */
    /* ========================= */
    public function selectByStorenoForInside ($USERNO, $STORENO)                 { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectByPkForInside      ($USERNO, $STORENO, $CART_INDEX)    { return $this->select(get_defined_vars(), __FUNCTION__); }

    const selectByStorenoForInside = "selectByStorenoForInside";
    const selectByPkForInside = "selectByPkForInside";
    protected function select($options, $option="")
    {
        /* -------------- */
        /* vars */
        /* -------------- */
        /* get bo */
        $cartmenuDAO = CartmenuDAO::getInstance();

        /* override option */
        extract($options);
        if($option != "")
            $OPTION = $option;

        /* --------------- */
        /* from */
        /* --------------- */
        $from = "";
        switch($OPTION)
        {
            case self::selectByStorenoForInside  : { $from = "(select * from cartmenu where userno = '$USERNO' and storeno = '$STORENO') cm "; break; }
            case self::selectByPkForInside       : { $from = "(select * from cartmenu where userno = '$USERNO' and storeno = '$STORENO' and cart_index = $CART_INDEX) cm"; break; }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }

        /* --------------- */
        /* exe query */
        /* --------------- */
        $query = $cartmenuDAO->makeSelectQuery($from);
        return GGsql::select($query, $from, $options);
    }


    /* ==================== */
    /*  */
    /* ==================== */
    public function deleteWithDetailByStorenoForInside  ($USERNO, $STORENO)              { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteWithDetailByPkForInside       ($USERNO, $STORENO, $CART_INDEX) { return $this->update(get_defined_vars(), __FUNCTION__); }

    const insertWithDetail = "insertWithDetail";
    const updateWithDetail = "updateWithDetail";
    const deleteWithDetailByStorenoForInside = "deleteWithDetailByStorenoForInside";
    const deleteWithDetailByPk = "deleteWithDetailByPk";
    const deleteWithDetailByPkForInside = "deleteWithDetailByPkForInside";

    protected function update($options, $option="")
    {
        /* -------------- */
        /* get bean */
        /* -------------- */
        $cartmenuDAO = CartmenuDAO::getInstance();

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
        /* before process */
        /* ==================== */
        switch($OPTION)
        {
            case self::insertWithDetail:
            {
                /* get BO */
                GGnavi::getCartDAO();
                $cartDAO = CartDAO::getInstance();
                $cartBO = CartBO::getInstance();
                $cartmenuBO = CartmenuBO::getInstance();

                /* delete cart who has other storenos */
                $carts = Common::getData($cartBO->selectOtherCartByStorenoForInside($EXECUTOR, $STORENO));
                foreach($carts as $cart)
                {
                    $storeno = $cart[GGF::STORENO];
                    $cartmenuBO->deleteCartmenuWithDetailByStorenoForInside($EXECUTOR, $storeno);
                    $cartDAO->deleteByPkForInside($EXECUTOR, $storeno);
                }

                /* get cartIndex */
                $CART_INDEX = $this->getNewCartIndex($EXECUTOR, $STORENO);
                break;
            }
            case self::updateWithDetail:
            {
                /* delete existing model */
                $this->deleteWithDetailByPkForInside($EXECUTOR, $STORENO, $CART_INDEX);
                break;
            }
        }

        /* ==================== */
        /* process */
        /* ==================== */
        switch($OPTION)
        {
            case self::updateWithDetail:
            case self::insertWithDetail:
            {
                /* make bo */
                $cartmenuDAO = CartmenuDAO::getInstance();
                $cartBO = CartBO::getInstance();
                $cartmenuBO = CartmenuBO::getInstance();
                $cartmenuoptBO = CartmenuoptBO::getInstance();
                $cartmenuoptDetailBO = CartmenuoptDetailBO::getInstance();
                $cartmenuRecommendBO = CartmenuRecommendBO::getInstance();

                /* json decode */
                $cartmenuoptArr       = json_decode($CARTMENUOPT, true);
                $cartmenuoptDetailArr = json_decode($CARTMENUOPT_DETAIL, true);
                $cartmenuRecommendArr = json_decode($CARTMENU_RECOMMEND, true);

                /* insert into cart / cartmenu */
                $cartBO->insertCartForInside($EXECUTOR, $STORENO);
                $cartmenuDAO->insertForInside($EXECUTOR, $STORENO, $MENUNO, $CART_INDEX, $QUANTITY);

                /* insert into cartmenuopt */
                foreach($cartmenuoptArr as $arr)
                {
                    $MENUOPTNO = isset($arr['MENUOPTNO']) ? $arr['MENUOPTNO'] : "";
                    $cartmenuoptBO->insertCartmenuoptForInside($EXECUTOR, $STORENO, $CART_INDEX, $MENUNO, $MENUOPTNO);
                }

                /* insert into cartmenuopt_detail */
                foreach($cartmenuoptDetailArr as $arr)
                {
                    $MENUOPTNO   = isset($arr['MENUOPTNO'])   ? $arr['MENUOPTNO']   : "";
                    $OPTDETAILNO = isset($arr['OPTDETAILNO']) ? $arr['OPTDETAILNO'] : "";
                    $cartmenuoptDetailBO->insertCartmenuoptDetailForInside($EXECUTOR, $STORENO, $CART_INDEX, $MENUNO, $MENUOPTNO, $OPTDETAILNO);
                }

                /* insert into cartmenu_recommend */
                foreach($cartmenuRecommendArr as $arr)
                {
                    $RECOMMENDNO = isset($arr['RECOMMENDNO']) ? $arr['RECOMMENDNO'] : "";
                    $QUANTITY    = isset($arr['QUANTITY'])    ? $arr['QUANTITY']    : "";
                    $cartmenuRecommendBO->insertCartmenuRecommendForInside($EXECUTOR, $STORENO, $CART_INDEX, $QUANTITY, $MENUNO, $RECOMMENDNO);
                }

                /* 카트 가격계산 */
                $cartBO->recalCartSummary($EXECUTOR);
                break;
            }
            case self::deleteWithDetailByStorenoForInside:
            {
                $cartmenus = Common::getData($this->selectByStorenoForInside($USERNO, $STORENO));
                foreach($cartmenus as $cartmenu)
                {
                    $cartIndex = $cartmenu[CartmenuBO::FIELD__CART_INDEX];
                    $this->deleteWithDetailByPkForInside($USERNO, $STORENO, $cartIndex);
                }
                break;
            }
            case self::deleteWithDetailByPk:
            {
                $rslt = $this->deleteWithDetailByPkForInside($EXECUTOR, $STORENO, $CART_INDEX);
                break;
            }
            case self::deleteWithDetailByPkForInside:
            {
                /* get BO */
                GGnavi::getCartmenuoptBO();
                GGnavi::getCartmenuRecommendBO();
                $cartmenuoptBO = CartmenuoptBO::getInstance();
                $cartmenuRecommendBO = CartmenuRecommendBO::getInstance();

                /* process */
                $cartmenuoptBO->deleteWithDetailByCartIndexForInside($USERNO, $STORENO, $CART_INDEX);
                $cartmenuRecommendBO->deleteByCartIndexForInside($USERNO, $STORENO, $CART_INDEX);
                $cartmenuDAO->deleteByPkForInside($USERNO, $STORENO, $CART_INDEX);
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }
        return $rslt;
    }

    public function getNewCartIndex($EXECUTOR, $STORENO)
    {
        $query  = "select coalesce(max(cart_index),0)+1 cnt from cartmenu where userno = '$EXECUTOR' and storeno = '$STORENO'";
        $result = GGsql::selectCnt($query);
        return $result;
    }

}
?>