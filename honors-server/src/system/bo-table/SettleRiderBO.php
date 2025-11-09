<?php

class SettleRiderBO extends _CommonBO
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



    const FIELD__RIDERNO                                = "riderno";                                        /* (pk) char(30) */
    const FIELD__SETTLE_YEAR                            = "settle_year";                                    /* (pk) int */
    const FIELD__SETTLE_MONTH                           = "settle_month";                                   /*      tinyint */
    const FIELD__SETTLE_CNT_DELIVERY                    = "settle_cnt_delivery";                            /*      int */
    const FIELD__SETTLE_SUM_SALES_ALL                   = "settle_sum_sales_all";                           /*      int */
    const FIELD__SETTLE_SUM_SALES_SYSTEMFEE             = "settle_sum_sales_systemfee";                     /*      int */
    const FIELD__SETTLE_SUM_SALES_DISTANCE              = "settle_sum_sales_distance";                      /*      int */
    const FIELD__SETTLE_SUM_SALES_NIGHT                 = "settle_sum_sales_night";                         /*      int */
    const FIELD__SETTLE_SUM_SALES_WEATHER               = "settle_sum_sales_weather";                       /*      int */
    const FIELD__SETTLE_SUM_SALES_BANKFEE               = "settle_sum_sales_bankfee";                       /*      int */
    const FIELD__SETTLE_FINAL                           = "settle_final";                                   /*      int */
    const FIELD__SETTLE_PAIDFLG                         = "settle_paidflg";                                 /*      enum('y','n') */
    const FIELD__SETTLE_PAIDDT                          = "settle_paiddt";                                  /*      datetime */
    const FIELD__SETTLE_PAIDBANKCODE                    = "settle_paidbankcode";                            /*      varchar(3) */
    const FIELD__SETTLE_PAIDBANKNAME                    = "settle_paidbankname";                            /*      varchar(20) */
    const FIELD__SETTLE_PAIDBANKACCOUNT                 = "settle_paidbankaccount";                         /*      varchar(50) */
    const FIELD__SETTLE_SYSTEMBANKCODE                  = "settle_systembankcode";                          /*      varchar(3) */
    const FIELD__SETTLE_SYSTEMBANKNAME                  = "settle_systembankname";                          /*      varchar(20) */
    const FIELD__SETTLE_SYSTEMBANKACCOUNT               = "settle_systembankaccount";                       /*      varchar(50) */
    const FIELD__AT_UPDATE                              = "at_update";                                      /*      datetime */
    const FIELD__AT_REGIST                              = "at_regist";                                      /*      datetime */

    /* ========================= */
    /* select */
    /*
    */
    /* ========================= */
    const selectByPk = "selectByPk"; /* 기본키로 조회 */
    const selectByRidernoYear = "selectByRidernoYear"; /* 라이더번호와 연도로 조회 */
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
              t.riderno
            , t.settle_year
            , t.settle_month
            , t.settle_cnt_delivery
            , t.settle_sum_sales_all
            , t.settle_sum_sales_systemfee
            , t.settle_sum_sales_distance
            , t.settle_sum_sales_night
            , t.settle_sum_sales_weather
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
            case self::selectByPk           : { $ggAuth->isRiderOwn($EXECUTOR, $RIDERNO); $from = "(select * from settle_rider where riderno = '$RIDERNO' and settle_year = $SETTLE_YEAR and settle_month = $SETTLE_MONTH) t"; break; }
            case self::selectByRidernoYear  : { $ggAuth->isRiderOwn($EXECUTOR, $RIDERNO); $from = "(select * from settle_rider where riderno = '$RIDERNO' and settle_year = $YEAR) t "; break; }
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
                t.riderno,
                t.settle_year,
                t.settle_month
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
            $storeno          = $mOrder->getStoreno();
            $ordertype        = $mOrder->getOrdertype();
            $orderstatus      = $mOrder->getOrderstatus();
            $deliverystatus   = $mOrder->getDeliverystatus();
            $deliverytype     = $mOrder->getDeliverytype();

            /* --------------- */
            /* validation */
            /* --------------- */
            /* 주문이 배달상태가 아니면 처리하지 않음 */
            if($ordertype != OrderBO::ORDERTYPE__DELIVERY)
                return true;

            /* 주문이 자체배달이면 처리하지 않음 */
            if($deliverytype == OrderBO::DELIVERYTYPE__SELF)
                return true;

            /* 배달상태가 완료가 아니면 처리하지 않음 */
            if($deliverystatus != OrderBO::DELIVERYSTATUS__COMPLETE)
                return true;

            /* --------------- */
            /* make records info */
            /* settleStore 레코드 */
            /* --------------- */
            $riderno                            = $mOrder->getRiderno();
            $settleYear                         = GGdate::format($settleDateTime, GGdate::DATEFORMAT__YYYY);
            $settleMonth                        = GGdate::format($settleDateTime, GGdate::DATEFORMAT__MM);
            $settleCntDelivery                  = 1;
            $settleSumSalesSystemfee            = $mOrder->getOrderbillStoreFee();
            $settleSumSalesDeliveryDefault      = $mOrder->getOrderbillDeliveryDefault();
            $settleSumSalesDeliveryDistance     = $mOrder->getOrderbillDeliveryDistance();
            $settleSumSalesDeliveryNight        = $mOrder->getOrderbillDeliveryNight();
            $settleSumSalesDeliveryWeather      = $mOrder->getOrderbillDeliveryWeather();
            $settleSumSalesBankfee              = $this->bankfee;
            $settleSumSalesAll                  = 0
                + $settleSumSalesDeliveryDefault     /* 기본배달료 */
                + $settleSumSalesDeliveryDistance    /* 거리 할증 */
                + $settleSumSalesDeliveryNight       /* 야간 할증 */
                + $settleSumSalesDeliveryWeather     /* 날씨 할증 */
            ;
            $settleFinal = 0
                + $settleSumSalesAll
                - $settleSumSalesSystemfee;

            /* --------------- */
            /* query */
            /* --------------- */
            $query =
            "
                insert into settle_rider
                (
                      riderno
                    , settle_year
                    , settle_month
                    , settle_cnt_delivery
                    , settle_sum_sales_all
                    , settle_sum_sales_systemfee
                    , settle_sum_sales_distance
                    , settle_sum_sales_night
                    , settle_sum_sales_weather
                    , settle_final
                    , at_update
                    , at_regist
                )
                values
                (
                      $riderno
                    , $settleYear
                    , $settleMonth
                    , $settleCntDelivery
                    , $settleSumSalesAll
                    , $settleSumSalesSystemfee
                    , $settleSumSalesDeliveryDistance
                    , $settleSumSalesDeliveryNight
                    , $settleSumSalesDeliveryWeather
                    , $settleFinal
                    ,  now()
                    ,  now()
                )
                on duplicate key update
                      at_update                    = now()
                    , settle_sum_sales_bankfee     = $settleSumSalesBankfee
                    , settle_cnt_delivery          = settle_cnt_delivery        + $settleCntDelivery
                    , settle_sum_sales_all         = settle_sum_sales_all       + $settleSumSalesAll
                    , settle_sum_sales_systemfee   = settle_sum_sales_systemfee + $settleSumSalesSystemfee
                    , settle_sum_sales_distance    = settle_sum_sales_distance  + $settleSumSalesDeliveryDistance
                    , settle_sum_sales_night       = settle_sum_sales_night     + $settleSumSalesDeliveryNight
                    , settle_sum_sales_weather     = settle_sum_sales_weather   + $settleSumSalesDeliveryWeather
                    , settle_final                 = settle_final               + $settleFinal

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
