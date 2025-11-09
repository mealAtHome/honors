<?php

/*
    (1) 3시간 지난 완료/취소된 주문을 조회한다.
    (2) 정산 (settle_store, settle_rider)
    (3) 주문삭제 (delete from ordering)
    ※ ordering으로부터 삭제가 요구되기 때문에 트렌젝션을 검. 처리 실패했으면 rollback 필요.
*/
class Per30MinOrderingToOrdera extends Per00BatchBase
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
    public $batchname = "per-30-min-orderingToOrdera";

    public function __construct()
    {
        GGnavi::getOrderaBO();
        GGnavi::getOrderingBO();
        GGnavi::getReviewBO();
        GGnavi::getReviewMenuBO();
        GGnavi::getSettleStoreBO();
        GGnavi::getSettleRiderBO();
        GGnavi::getMOrder();
        GGnavi::getUserAddrBO();
        GGnavi::getReorderpctLogBO();
        GGnavi::getOrdermenuBO();
    }

    public function process()
    {
        /* ========================= */
        /* init */
        /* ========================= */
        $this->beforeProcess();
        $orderingBO = OrderingBO::getInstance();
        $ordermenuBO = OrdermenuBO::getInstance();
        $userAddrBO = UserAddrBO::getInstance();
        $settleStoreBO = SettleStoreBO::getInstance();
        $settleRiderBO = SettleRiderBO::getInstance();
        $reorderpctLogBO = ReorderpctLogBO::getInstance();

        /* set autoCommit false */
        GGsql::autoCommitFalse();

        /* ========================= */
        /* process */
        /* ========================= */

        /* --------------- */
        /* 현재시간 보다 3시간 전으로 기준일자 생성 */
        /* --------------- */
        /* 배치 기준일자 생성 */
        $summaryDate    = GGdate::now();
        $summaryDateStr = GGdate::format($summaryDate, GGdate::DATEFORMAT__YYYYMMDDHHIISS);
        $summaryYear    = GGdate::format($summaryDate, GGdate::DATEFORMAT__YYYY);
        $summaryMonth   = GGdate::format($summaryDate, GGdate::DATEFORMAT__MM);
        $summaryDay     = GGdate::format($summaryDate, GGdate::DATEFORMAT__DD);

        /* 주문 검색 시간 */
        $summarySearchDate    = GGdate::subTime($summaryDate, "3 hour");
        $summarySearchDateStr = GGdate::format($summarySearchDate, GGdate::DATEFORMAT__YYYYMMDDHHIISS);

        /* ========================= */
        /*
            (1) 3시간 지난 완료/취소된 주문을 조회한다.
                - 100건씩 조회하여 처리
        */
        /* ========================= */
        $limitCount = 500;
        $orderingModels = []; /* 주문리스트 */
        do
        {
            /* =============== */
            /* loop orders */
            /* =============== */
            $orderingModels = Common::getData($orderingBO->selectEndedOverVartimeLimitXForInside($summarySearchDateStr, $limitCount));
            foreach($orderingModels as $orderingModel)
            {
                try
                {
                    $order = new MOrder($orderingModel);

                    /* vars */
                    $storeno           = $order->getStoreno();
                    $orderno           = $order->getOrderno();
                    $orderer           = $order->getOrderer();
                    $datetimeYear      = $order->getDatetimeYear();
                    $datetimeMonth     = $order->getDatetimeMonth();
                    $orderstatus       = $order->getOrderstatus();
                    $addrIndex         = $order->getAddrIndex();

                    /* custom vars */
                    $yearmonth = $datetimeYear.str_pad($datetimeMonth, 2, "0", STR_PAD_LEFT);

                    /* --------------- */
                    /* by orderstatus */
                    /* --------------- */
                    switch($orderstatus)
                    {
                        case OrderBO::ORDERSTATUS__COMPLETE :
                        {
                            if($addrIndex != null && $orderer != null)
                            {
                                /* user_addr 업데이트 */
                                $userAddrBO->updateByCompletedOrderForInside($orderer, $addrIndex);

                                /* reorderpct_log 업데이트 */
                                $reorderpctLogBO->upsertCountUpByPkForInside($yearmonth, $orderer, $addrIndex, $storeno, 0);
                                $ordermenus = Common::getData($ordermenuBO->selectByOrdernoForInside($storeno, $orderno));
                                foreach($ordermenus as $ordermenu)
                                {
                                    $menuno = Common::get($ordermenu, OrdermenuBO::FIELD__MENUNO);
                                    $reorderpctLogBO->upsertCountUpByPkForInside($yearmonth, $orderer, $addrIndex, $storeno, $menuno);
                                }
                            }
                            break;
                        }
                        case OrderBO::ORDERSTATUS__CANCEL :
                        {
                            break;
                        }
                        case OrderBO::ORDERSTATUS__CANCELAFTER :
                        {
                            break;
                        }
                    }

                    /* ========================= */
                    /* (2) 정산 (settle_store, settle_rider) */
                    /**
                     * upsert settle_store
                     * upsert settle_rider
                     * update ordering, ordera
                     */
                    /* ========================= */
                    $settleStoreBO->settleByMOrder($order);
                    $settleRiderBO->settleByMOrder($order);
                    $orderingBO->updateAtSettleToNowForInside($storeno, $orderno);

                    /* ========================= */
                    /* (3) 주문삭제 (delete from ordering) */
                    /* ========================= */
                    $orderingBO->deleteOrderingForInside($storeno, $orderno);

                    GGsql::commit();
                }
                catch(Exception $e)
                {
                    Common::logException($e);
                    GGsql::rollback();
                    break 2;
                }
            }
        }
        while($orderingModels != null && count($orderingModels) > 0);

        /* set autoCommit true */
        GGsql::autoCommitTrue();

        $this->afterProcess();
        return true;
    }

}

?>