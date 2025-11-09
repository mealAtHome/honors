<?php

class OrderaBO extends OrderBO
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

    /* ========================= */
    /*
    */
    /* ========================= */
    public function selectOrderDetailOnlyForInside($STORENO, $ORDERNO, $SERVICE_LAYER) { return $this->selectOrderDetail(get_defined_vars(), __FUNCTION__); }
    public function selectOrderDetail($options, $skipAuth=false)
    {
        /* -------------- */
        /* vars */
        /* -------------- */
        extract($options);
        $rslt = Common::getReturn();

        /* get bo */
        GGnavi::getOrdermenuBO();
        GGnavi::getOrdermenuoptBO();
        GGnavi::getOrdermenuoptDetailBO();
        GGnavi::getOrdermenuRecommendBO();
        GGnavi::getUserBO();
        GGnavi::getStoreOrderproctimeResultBO();
        GGnavi::getReviewBO();
        GGnavi::getSummaryStoreorderAllUserBO();
        GGnavi::getSummaryUserorderAllBO();
        GGnavi::getPaymentQueueBO();
        GGnavi::getStoreBO();
        GGnavi::getRiderBO();

        /* BO */
        $ordermenuBO                    = OrdermenuBO::getInstance();
        $ordermenuoptBO                 = OrdermenuoptBO::getInstance();
        $ordermenuoptDetailBO           = OrdermenuoptDetailBO::getInstance();
        $ordermenuRecommendBO           = OrdermenuRecommendBO::getInstance();
        $userBO                         = UserBO::getInstance();
        $storeOrderproctimeResultBO     = StoreOrderproctimeResultBO::getInstance();
        $reviewBO                       = ReviewBO::getInstance();
        $summaryStoreorderAllUserBO     = SummaryStoreorderAllUserBO::getInstance();
        $summaryUserorderAllBO          = SummaryUserorderAllBO::getInstance();
        $paymentQueueBO                 = PaymentQueueBO::getInstance();
        $storeBO                        = StoreBO::getInstance();
        $riderBO                        = RiderBO::getInstance();

        /* -------------- */
        /* TODO : AUTH */
        /* -------------- */

        /* -------------- */
        /* process */
        /* -------------- */

        /* order */
        $order = Common::getDataOne($this->selectByPkForInside($STORENO, $ORDERNO));
        if($order == null)
            return $rslt;

        /* order > ordermenu */
        $order['ordermenu'] = Common::getData($ordermenuBO->selectByOrdernoForInside($STORENO, $ORDERNO));
        foreach($order['ordermenu'] as &$ordermenu)
        {
            /* order > ordermenu > ordermenuopt */
            $cartIndex = Common::get($ordermenu, OrdermenuBO::FIELD__CART_INDEX);
            $ordermenu['ordermenuopt'] = Common::getData($ordermenuoptBO->selectByCartIndexForInside($STORENO, $ORDERNO, $cartIndex));

            /* order > ordermenu > ordermenuopt > ordermenuopt_detail */
            foreach($ordermenu['ordermenuopt'] as &$ordermenuopt)
            {
                $menuoptno = Common::get($ordermenuopt, OrdermenuoptBO::FIELD__MENUOPTNO);
                $ordermenuopt['ordermenuopt_detail'] = Common::getData($ordermenuoptDetailBO->selectByPkForInside($STORENO, $ORDERNO, $cartIndex, $menuoptno));
            }

            /* order > ordermenu > order_recommend */
            $ordermenu['ordermenu_recommend'] = Common::getData($ordermenuRecommendBO->selectByCartIndexForInside($STORENO, $ORDERNO, $cartIndex));
        }

        /* 매장정보 */
        $order["mStore"] = Common::getData($storeBO->selectByPkForInside($STORENO));

        /* 고객정보 */
        $order["mCustomer"] = Common::getData($userBO->selectByPkForInside($order[OrderBO::FIELD__ORDERER]));

        /* 배달원정보 */
        $order["mRider"] = null;
        if($order[OrderBO::FIELD__RIDERNO] != null)
            $order["mRider"] = Common::getData($riderBO->selectByPkForInside($order[OrderBO::FIELD__RIDERNO]));

        /* [주문완료까지 걸리는 시간] store > storeOrderproctimeResult */
        $order['store_orderproctime_result'] = Common::getData($storeOrderproctimeResultBO->selectByStorenoForInside($STORENO));

        /* [입금정보] */
        if($SERVICE_LAYER == GGF::SERVICE_LAYER__ADM || $SERVICE_LAYER == GGF::SERVICE_LAYER__CUS)
            $order['payment_queue'] = Common::getData($paymentQueueBO->selectByStorenoOrdernoForInside($STORENO, $ORDERNO));

        /* ===== */
        /* TODO : auth */
        /* ===== */
        $orderer = Common::get($order, self::FIELD__ORDERER);
        if($SERVICE_LAYER == GGF::SERVICE_LAYER__BIZ)
        {
            $order['summaryStoreorderAllUsers'] = Common::getData($summaryStoreorderAllUserBO->selectByPkForInside($STORENO, $orderer));
            $order['summaryUserorderAlls']      = Common::getData($summaryUserorderAllBO->selectByPkForInside($orderer));
            $order['orderers']                  = Common::getData($userBO->selectByPkForInside($orderer));
            $order['reviewDelivery']            = Common::getData($reviewBO->selectByPkForInside($STORENO, $ORDERNO, ReviewBO::REVIEW_TYPE__DELIVERY));
            $order['reviewOrder']               = Common::getData($reviewBO->selectByPkForInside($STORENO, $ORDERNO, ReviewBO::REVIEW_TYPE__ORDER));
        }
        if($SERVICE_LAYER == GGF::SERVICE_LAYER__ADM)
        {
            $order['summaryStoreorderAllUsers'] = Common::getData($summaryStoreorderAllUserBO->selectByPkForInside($STORENO, $orderer));
            $order['summaryUserorderAlls']      = Common::getData($summaryUserorderAllBO->selectByPkForInside($orderer));
            $order['orderers']                  = Common::getData($userBO->selectByPkForInside($orderer));
            $order['reviewDelivery']            = Common::getData($reviewBO->selectByPkForInside($STORENO, $ORDERNO, ReviewBO::REVIEW_TYPE__DELIVERY));
            $order['reviewOrder']               = Common::getData($reviewBO->selectByPkForInside($STORENO, $ORDERNO, ReviewBO::REVIEW_TYPE__ORDER));
            $order['reviewService']             = Common::getData($reviewBO->selectByPkForInside($STORENO, $ORDERNO, ReviewBO::REVIEW_TYPE__SERVICE));
        }

        $orderArr = array();
        $orderArr[] = $order;
        $rslt[GGF::DATA] = $orderArr;
        return $rslt;
    }


    /* ========================= */
    /*  */
    /* ========================= */

    public function selectByPkForInside ($STORENO, $ORDERNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    const selectMyByPk        = "selectMyByPk";
    const selectMine          = "selectMine";
    const selectByPkForInside = "selectByPkForInside";
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        $ggAuth = GGauth::getInstance();
        extract($options);

        /* option override */
        if($option != "")
        $OPTION = $option;

        /* -------------- */
        /* vars */
        /* -------------- */
        extract(self::getOrderConsts());
        $Y = GGF::Y;
        $N = GGF::N;

        /* --------------- */
        /* sql body */
        /* --------------- */
        $priv = GGF::PRIZ;
        $from = "";
        $orderBy = "";
        $limit = "";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectMyByPk         : { $priv = GGF::PRIA; $from = "(select * from ordera where userno = '$EXECUTOR' and orderno = '$ORDERNO') t"; break; }
            case self::selectByPkForInside  : { $priv = GGF::PRIA; $from = "(select * from ordera where userno = '$USERNO'   and orderno = '$ORDERNO') t"; break; }
            case self::selectMine           : { $priv = GGF::PRIZ; $from = "(select * from ordera where userno = '$EXECUTOR') t"; break; }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        } /* end switch($OPTION) */

        /* --------------- */
        /* execute query */
        /* --------------- */
        $query = $this->makeSelectQuery($from, $orderBy, $limit, $priv);
        return GGsql::select($query, $from, $options);
    }


    /* ========================= */
    /* update */
    /* ========================= */
    protected function update($options, $option="")
    {
        /* vars */

        /* data object */
        $ordermenuDAO = OrdermenuDAO::getInstance();

        /* get vars */
        extract($options);
        extract(self::getOrderConsts());

        /* override option */
        $OPTION = $option != "" ? $option : $OPTION;

        /* ==================== */
        /* process */
        /* ==================== */
        return $ORDERNO;
    }

} /* end class */
?>
