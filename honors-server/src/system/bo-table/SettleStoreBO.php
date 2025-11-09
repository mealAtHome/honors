<?php

class SettleStoreBO extends _CommonBO
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

    private $refFeeBO;
    private $bankfee;
    public function __construct()
    {
        GGnavi::getMOrder();
        GGnavi::getRefFeeBO();
        $this->refFeeBO = RefFeeBO::getInstance();
        $this->bankfee = $this->refFeeBO->getBankfee();
    }



    const FIELD__STORENO                                       = "storeno";                                         /* (pk) char(30) */
    const FIELD__SETTLE_DATE                                   = "settle_date";                                     /* (pk) date */
    const FIELD__SETTLE_YEAR                                   = "settle_year";                                     /*      int */
    const FIELD__SETTLE_MONTH                                  = "settle_month";                                    /*      tinyint */
    const FIELD__SETTLE_DAY                                    = "settle_day";                                      /*      tinyint */
    const FIELD__SETTLE_CNT_SALES_ALL                          = "settle_cnt_sales_all";                            /*      int */
    const FIELD__SETTLE_CNT_SALES_COMPLETE                     = "settle_cnt_sales_complete";                       /*      int */
    const FIELD__SETTLE_CNT_SALES_CANCEL                       = "settle_cnt_sales_cancel";                         /*      int */
    const FIELD__SETTLE_CNT_SALES_CANCELAFTER                  = "settle_cnt_sales_cancelafter";                    /*      int */
    const FIELD__SETTLE_CNT_SALES_REFUND                       = "settle_cnt_sales_refund";                         /*      int */
    const FIELD__SETTLE_CNT_SALES_PICKUP                       = "settle_cnt_sales_pickup";                         /*      int */
    const FIELD__SETTLE_CNT_SALES_DELIVERY                     = "settle_cnt_sales_delivery";                       /*      int */
    const FIELD__SETTLE_CNT_SALES_DELIVERYSELF                 = "settle_cnt_sales_deliveryself";                   /*      int */
    const FIELD__SETTLE_CNT_SALES_DELIVERYRIDER                = "settle_cnt_sales_deliveryrider";                  /*      int */
    const FIELD__SETTLE_SUM_SALES_COMPLETE                     = "settle_sum_sales_complete";                       /*      int */
    const FIELD__SETTLE_SUM_SALES_SYSTEMFEE                    = "settle_sum_sales_systemfee";                      /*      int */
    const FIELD__SETTLE_SUM_SALES_CUSTOMERDELIVERYFEE          = "settle_sum_sales_customerdeliveryfee";            /*      int */
    const FIELD__SETTLE_SUM_SALES_STOREDELIVERYFEE             = "settle_sum_sales_storedeliveryfee";               /*      int */
    const FIELD__SETTLE_SUM_SALES_DELIVERYFEEBYREFUND          = "settle_sum_sales_deliveryfeebyrefund";            /*      int */
    const FIELD__SETTLE_SUM_SALES_BANKFEE                      = "settle_sum_sales_bankfee";                        /*      int */
    const FIELD__SETTLE_FINAL                                  = "settle_final";                                    /*      int */
    const FIELD__SETTLE_PAIDFLG                                = "settle_paidflg";                                  /*      enum('y','n') */
    const FIELD__SETTLE_PAIDDT                                 = "settle_paiddt";                                   /*      datetime */
    const FIELD__SETTLE_PAIDBANKCODE                           = "settle_paidbankcode";                             /*      varchar(3) */
    const FIELD__SETTLE_PAIDBANKNAME                           = "settle_paidbankname";                             /*      varchar(20) */
    const FIELD__SETTLE_PAIDBANKACCOUNT                        = "settle_paidbankaccount";                          /*      varchar(50) */
    const FIELD__SETTLE_SYSTEMBANKCODE                         = "settle_systembankcode";                           /*      varchar(3) */
    const FIELD__SETTLE_SYSTEMBANKNAME                         = "settle_systembankname";                           /*      varchar(20) */
    const FIELD__SETTLE_SYSTEMBANKACCOUNT                      = "settle_systembankaccount";                        /*      varchar(50) */
    const FIELD__AT_UPDATE                                     = "at_update";                                       /*      datetime */
    const FIELD__AT_REGIST                                     = "at_regist";                                       /*      datetime */


    /* ========================= */
    /* select */
    /*
    */
    /* ========================= */
    const selectByPk = "selectByPk"; /* 기본 키로 조회 */
    const selectByStorenoYear = "selectByStorenoYear"; /* storeno, year */
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        $ggAuth = GGauth::getInstance();
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
              t.storeno
            , t.settle_date
            , t.settle_year
            , t.settle_month
            , t.settle_day
            , t.settle_cnt_sales_all
            , t.settle_cnt_sales_complete
            , t.settle_cnt_sales_cancel
            , t.settle_cnt_sales_cancelafter
            , t.settle_cnt_sales_refund
            , t.settle_cnt_sales_pickup
            , t.settle_cnt_sales_delivery
            , t.settle_cnt_sales_deliveryself
            , t.settle_cnt_sales_deliveryrider
            , t.settle_sum_sales_complete
            , t.settle_sum_sales_systemfee
            , t.settle_sum_sales_customerdeliveryfee
            , t.settle_sum_sales_storedeliveryfee
            , t.settle_sum_sales_deliveryfeebyrefund
            , t.settle_sum_sales_bankfee
            , t.settle_final
            , t.settle_paidflg
            , t.settle_paiddt
            , t.settle_paidbankcode
            , t.settle_paidbankname
            , t.settle_paidbankaccount
            , t.settle_systembankcode
            , t.settle_systembankname
            , t.settle_systembankaccount
            , t.at_update
            , t.at_regist
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPk:          { $ggAuth->isStoreOwner($EXECUTOR, $STORENO); $from = "(select * from settle_store where storeno = '$STORENO' and settle_date = str_to_date('$SETTLE_DATE', '%Y-%m-%d')) t "; break; }
            case self::selectByStorenoYear: { $ggAuth->isStoreOwner($EXECUTOR, $STORENO); $from = "(select * from settle_store where storeno = '$STORENO' and settle_year = $YEAR) t "; break; }
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
            order by
                 t.storeno
                ,t.settle_date
        ";
        return GGsql::select($query, $from, $options);
    }

    /* ========================= */
    /**
     * 주문 레코드로 카운트 업
     * @param $order : 주문 레코드
     */
    /* ========================= */
    public function settleByMOrder(MOrder $mOrder)
    {
        try
        {
            /* --------------- */
            /* init vars */
            /* --------------- */

            /* 실행일자를 기준으로 정산 */
            $settleDateTime  = GGdate::now();

            /* 주문정보 */
            $storeno                    = $mOrder->getStoreno();
            $orderstatus                = $mOrder->getOrderstatus();
            $deliverystatus             = $mOrder->getDeliverystatus();
            $ordertype                  = $mOrder->getOrdertype();
            $deliverytype               = $mOrder->getDeliverytype();
            $isRefund                   = $mOrder->getAtRefundreq() == null ? false : true; /* 환불인가? */
            $isCancelafterAndRefund     = $mOrder->isCancelafterAndRefund(); /* 후취소 (+시스템환불) 인가? */

            /* --------------- */
            /* validation */
            /* --------------- */
            /* 완료, 취소, 후취소 이외에는 처리하지 않음 */
            if(
                $orderstatus != OrderBO::ORDERSTATUS__COMPLETE &&
                $orderstatus != OrderBO::ORDERSTATUS__CANCEL   &&
                $orderstatus != OrderBO::ORDERSTATUS__CANCELAFTER
            )
                return true;

            /* --------------- */
            /* make records info */
            /* settleStore 레코드 */
            /* --------------- */
            $settleDate                          = GGdate::format($settleDateTime, GGdate::DATEFORMAT__YYYYMMDD);
            $settleYear                          = GGdate::format($settleDateTime, GGdate::DATEFORMAT__YYYY);
            $settleMonth                         = GGdate::format($settleDateTime, GGdate::DATEFORMAT__MM);
            $settleDay                           = GGdate::format($settleDateTime, GGdate::DATEFORMAT__DD);
            $settleCntSalesAll                   = 1;
            $settleCntSalesComplete              = $orderstatus  == OrderBO::ORDERSTATUS__COMPLETE       ? 1 : 0;
            $settleCntSalesCancel                = $orderstatus  == OrderBO::ORDERSTATUS__CANCEL         ? 1 : 0;
            $settleCntSalesCancelafter           = $orderstatus  == OrderBO::ORDERSTATUS__CANCELAFTER    ? 1 : 0;
            $settleCntSalesRefund                = $isRefund                                             ? 1 : 0;
            $settleCntSalesPickup                = $ordertype    == OrderBO::ORDERTYPE__PICKUP           ? 1 : 0;
            $settleCntSalesDelivery              = $ordertype    == OrderBO::ORDERTYPE__DELIVERY         ? 1 : 0;
            $settleCntSalesDeliveryself          = $deliverytype == OrderBO::DELIVERYTYPE__SELF          ? 1 : 0;
            $settleCntSalesDeliveryrider         = $deliverytype == OrderBO::DELIVERYTYPE__RIDER         ? 1 : 0;
            $settleSumSalesComplete              = 0;
            $settleSumSalesSystemfee             = 0;
            $settleSumSalesCustomerdeliveryfee   = 0;
            $settleSumSalesStoredeliveryfee      = 0;
            $settleSumSalesDeliveryfeebyrefund   = 0;
            $settleSumSalesBankfee               = $this->bankfee;
            $settleFinal                         = 0;

            switch($orderstatus)
            {
                case OrderBO::ORDERSTATUS__COMPLETE:
                {
                    $settleSumSalesComplete              = $mOrder->getOrderbillTotal();
                    $settleSumSalesSystemfee             = $mOrder->getOrderbillStoreFee();
                    $settleSumSalesCustomerdeliveryfee   = $mOrder->getOrderbillDelivery();
                    $settleSumSalesStoredeliveryfee      = $mOrder->getOrderbillDeliveryStore();
                    break;
                }
                case OrderBO::ORDERSTATUS__CANCEL:
                case OrderBO::ORDERSTATUS__CANCELAFTER:
                {
                    /* 주문 취소한 경우, 시스템 이용료는 받지 않음 */
                    /* 배송완료 후, 환불한 경우, 배달료는 예정대로 라이더에게 지급하기 위해, 정산금에서 제외함 */
                    if($isRefund == true && $deliverystatus == OrderBO::DELIVERYSTATUS__COMPLETE)
                    {
                        $settleSumSalesDeliveryfeebyrefund += $mOrder->getOrderbillDelivery();
                        $settleSumSalesDeliveryfeebyrefund += $mOrder->getOrderbillDeliveryStore();
                    }
                    break;
                }
            }

            /* 최종정산금 */
            $settleFinal = 0
                + $settleSumSalesComplete /* 완료 매출 */
                - $settleSumSalesSystemfee /* 시스템 이용료 */
                - $settleSumSalesCustomerdeliveryfee /* 고객부담 배달료 */
                - $settleSumSalesStoredeliveryfee /* 점포부담 배달료 */
                - $settleSumSalesDeliveryfeebyrefund /* 환불로 인한 배달료 */
            ;

            /* 점포직접배달인 경우, 배달료는 settleFinal 에 합해준다. */
            if($settleCntSalesDeliveryself == 1)
            {
                $settleFinal += $mOrder->getOrderbillDelivery();       /* 고객이 지불한 배달료 */
                $settleFinal += $mOrder->getOrderbillDeliveryStore();  /* 점포부담금 */
            }

            /* --------------- */
            /* query */
            /* --------------- */
            $query =
            "
                insert into settle_store
                (
                      storeno
                    , settle_date
                    , settle_year
                    , settle_month
                    , settle_day
                    , settle_cnt_sales_all
                    , settle_cnt_sales_complete
                    , settle_cnt_sales_cancel
                    , settle_cnt_sales_cancelafter
                    , settle_cnt_sales_refund
                    , settle_cnt_sales_pickup
                    , settle_cnt_sales_delivery
                    , settle_cnt_sales_deliveryself
                    , settle_cnt_sales_deliveryrider
                    , settle_sum_sales_complete
                    , settle_sum_sales_systemfee
                    , settle_sum_sales_customerdeliveryfee
                    , settle_sum_sales_storedeliveryfee
                    , settle_sum_sales_deliveryfeebyrefund
                    , settle_sum_sales_bankfee
                    , settle_final
                    , at_update
                    , at_regist
                )
                values
                (
                       $storeno
                    , '$settleDate'
                    ,  $settleYear
                    ,  $settleMonth
                    ,  $settleDay
                    ,  $settleCntSalesAll
                    ,  $settleCntSalesComplete
                    ,  $settleCntSalesCancel
                    ,  $settleCntSalesCancelafter
                    ,  $settleCntSalesRefund
                    ,  $settleCntSalesPickup
                    ,  $settleCntSalesDelivery
                    ,  $settleCntSalesDeliveryself
                    ,  $settleCntSalesDeliveryrider
                    ,  $settleSumSalesComplete
                    ,  $settleSumSalesSystemfee
                    ,  $settleSumSalesCustomerdeliveryfee
                    ,  $settleSumSalesStoredeliveryfee
                    ,  $settleSumSalesDeliveryfeebyrefund
                    ,  $settleSumSalesBankfee
                    ,  $settleFinal
                    ,  now()
                    ,  now()
                )
                on duplicate key update
                      at_update                              = now()
                    , settle_sum_sales_bankfee               = $settleSumSalesBankfee
                    , settle_cnt_sales_all                   = settle_cnt_sales_all                    + $settleCntSalesAll
                    , settle_cnt_sales_complete              = settle_cnt_sales_complete               + $settleCntSalesComplete
                    , settle_cnt_sales_cancel                = settle_cnt_sales_cancel                 + $settleCntSalesCancel
                    , settle_cnt_sales_cancelafter           = settle_cnt_sales_cancelafter            + $settleCntSalesCancelafter
                    , settle_cnt_sales_refund                = settle_cnt_sales_refund                 + $settleCntSalesRefund
                    , settle_cnt_sales_pickup                = settle_cnt_sales_pickup                 + $settleCntSalesPickup
                    , settle_cnt_sales_delivery              = settle_cnt_sales_delivery               + $settleCntSalesDelivery
                    , settle_cnt_sales_deliveryself          = settle_cnt_sales_deliveryself           + $settleCntSalesDeliveryself
                    , settle_cnt_sales_deliveryrider         = settle_cnt_sales_deliveryrider          + $settleCntSalesDeliveryrider
                    , settle_sum_sales_complete              = settle_sum_sales_complete               + $settleSumSalesComplete
                    , settle_sum_sales_systemfee             = settle_sum_sales_systemfee              + $settleSumSalesSystemfee
                    , settle_sum_sales_customerdeliveryfee   = settle_sum_sales_customerdeliveryfee    + $settleSumSalesCustomerdeliveryfee
                    , settle_sum_sales_storedeliveryfee      = settle_sum_sales_storedeliveryfee       + $settleSumSalesStoredeliveryfee
                    , settle_sum_sales_deliveryfeebyrefund   = settle_sum_sales_deliveryfeebyrefund    + $settleSumSalesDeliveryfeebyrefund
                    , settle_final                           = settle_final                            + $settleFinal
            ";
            $result = GGsql::exeQuery($query);
        }
        catch(Exception $e)
        {
            throw $e;
        }
        return true;
    }


} /* end class */
?>
