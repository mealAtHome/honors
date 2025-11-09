<?php

/*
    1. 분마다, 3시간 지난 완료/취소된 주문을 ordering 테이블에서 삭제한다.
    2. 이 때, 취소되는 주문을 통계 테이블에 반영한다.

    ※ ordering으로부터 삭제가 요구되기 때문에 트렌젝션을 검. 처리 실패했으면 원위치 필요.
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
        GGnavi::getSummaryStoreorderAllBO();
        GGnavi::getSummaryStoreorderAllMenuBO();
        GGnavi::getSummaryStoreorderAllMenuRecommendBO();
        GGnavi::getSummaryStoreorderAllMenuoptBO();
        GGnavi::getSummaryStoreorderAllMenuoptDetailBO();
        GGnavi::getSummaryStoreorderAllUserBO();
        GGnavi::getSummaryStoreorderBeeYearBO();
        GGnavi::getSummaryStoreorderBeeYearMenuBO();
        GGnavi::getSummaryStoreorderBeeYearMenuRecommendBO();
        GGnavi::getSummaryStoreorderBeeYearMenuoptBO();
        GGnavi::getSummaryStoreorderBeeYearMenuoptDetailBO();
        GGnavi::getSummaryStoreorderCeeMonthBO();
        GGnavi::getSummaryStoreorderCeeMonthMenuBO();
        GGnavi::getSummaryStoreorderCeeMonthMenuRecommendBO();
        GGnavi::getSummaryStoreorderCeeMonthMenuoptBO();
        GGnavi::getSummaryStoreorderCeeMonthMenuoptDetailBO();
        GGnavi::getSummaryStoreorderDeeDayBO();
        GGnavi::getSummaryStoreorderDeeDayMenuBO();
        GGnavi::getSummaryStoreorderDeeDayMenuRecommendBO();
        GGnavi::getSummaryStoreorderDeeDayMenuoptBO();
        GGnavi::getSummaryStoreorderDeeDaymenuoptDetailBO();
        GGnavi::getSummaryUserorderAllBO();
        GGnavi::getSummaryUserorderAllMenuBO();
        GGnavi::getSummaryUserorderAllMenuRecommendBO();
        GGnavi::getSummaryUserorderAllMenuoptBO();
        GGnavi::getSummaryUserorderAllMenuoptDetailBO();
        GGnavi::getSummaryUserorderAllStoreBO();
        GGnavi::getReviewBO();
        GGnavi::getReviewMenuBO();
    }

    public function process()
    {
        /* ========================= */
        /* init */
        /* ========================= */
        $this->beforeProcess();
        $orderingBO = OrderingBO::getInstance();

        /* ========================= */
        /* process */
        /* ========================= */
        /*
            1. 배치에 해당하는 시간을 생성 (완료 후 3시간)
            2. ordering 데이터로 부터 통계
            3. ordering 으로부터 데이터 삭제
        */

        /* --------------- */
        /* 1. 배치에 해당하는 시간을 생성 */
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

        /* --------------- */
        /*
            2. ordering 데이터로 부터 통계
                - ordering 으로부터 데이터를 조회하여 루프로 통계
                - 통계가 완료되면 ordering 에서 삭제
        */
        /* --------------- */
        $orderingModels = Common::getData($orderingBO->selectEndedBefore3hourForInside($summarySearchDateStr));
        foreach($orderingModels as $orderingModel)
        {
            /* vars */
            $storeno                    = Common::get($orderingModel, OrderBO::FIELD__STORENO);
            $orderno                    = Common::get($orderingModel, OrderBO::FIELD__ORDERNO);
            $orderstatus                = Common::get($orderingModel, OrderBO::FIELD__ORDERSTATUS);
            $summaryFlg                 = Common::get($orderingModel, OrderBO::FIELD__SUMMARY_FLG);
            $summaryDatetime            = Common::get($orderingModel, OrderBO::FIELD__SUMMARY_DATETIME);
            $summaryAftercancelFlg      = Common::get($orderingModel, OrderBO::FIELD__SUMMARY_AFTERCANCEL_FLG);
            $summaryAftercancelDatetime = Common::get($orderingModel, OrderBO::FIELD__SUMMARY_AFTERCANCEL_DATETIME);

            switch($orderstatus)
            {
                case OrderBO::ORDERSTATUS__COMPLETE :
                case OrderBO::ORDERSTATUS__CANCEL :
                {
                    $this->countup($summaryYear, $summaryMonth, $summaryDay, $summaryDateStr, $orderingModel);
                    break;
                }
                case OrderBO::ORDERSTATUS__CANCELAFTER :
                {
                    /* 먼저 complete 부터 통계 */
                    if($summaryFlg == GGF::N)
                        $this->countup($summaryYear, $summaryMonth, $summaryDay, $summaryDateStr, $orderingModel, OrderBO::ORDERSTATUS__COMPLETE);

                    /* 이후 cancelafter 처리 */
                    $this->countup($summaryYear, $summaryMonth, $summaryDay, $summaryDateStr, $orderingModel);
                    break;
                }
            }
            /* delete order from ordering table */
            $orderingBO->deleteOrderingForInside($storeno, $orderno);
        }
        return true;
    }

    /* =============== */
    /* countup function */
    /* =============== */
    private function countup($summaryYear, $summaryMonth, $summaryDay, $summaryDateStr, $orderingModel, $orderstatusOverride="")
    {
        $orderaBO                                   = OrderaBO::getInstance();
        $orderingBO                                 = OrderingBO::getInstance();
        $summaryStoreorderAllBO                     = SummaryStoreorderAllBO::getInstance();
        $summaryStoreorderAllMenuBO                 = SummaryStoreorderAllMenuBO::getInstance();
        $summaryStoreorderAllMenuRecommendBO        = SummaryStoreorderAllMenuRecommendBO::getInstance();
        $summaryStoreorderAllMenuoptBO              = SummaryStoreorderAllMenuoptBO::getInstance();
        $summaryStoreorderAllMenuoptDetailBO        = SummaryStoreorderAllMenuoptDetailBO::getInstance();
        $summaryStoreorderAllUserBO                 = SummaryStoreorderAllUserBO::getInstance();
        $summaryStoreorderBeeYearBO                 = SummaryStoreorderBeeYearBO::getInstance();
        $summaryStoreorderBeeYearMenuBO             = SummaryStoreorderBeeYearMenuBO::getInstance();
        $summaryStoreorderBeeYearMenuRecommendBO    = SummaryStoreorderBeeYearMenuRecommendBO::getInstance();
        $summaryStoreorderBeeYearMenuoptBO          = SummaryStoreorderBeeYearMenuoptBO::getInstance();
        $summaryStoreorderBeeYearMenuoptDetailBO    = SummaryStoreorderBeeYearMenuoptDetailBO::getInstance();
        $summaryStoreorderCeeMonthBO                = SummaryStoreorderCeeMonthBO::getInstance();
        $summaryStoreorderCeeMonthMenuBO            = SummaryStoreorderCeeMonthMenuBO::getInstance();
        $summaryStoreorderCeeMonthMenuRecommendBO   = SummaryStoreorderCeeMonthMenuRecommendBO::getInstance();
        $summaryStoreorderCeeMonthMenuoptBO         = SummaryStoreorderCeeMonthMenuoptBO::getInstance();
        $summaryStoreorderCeeMonthMenuoptDetailBO   = SummaryStoreorderCeeMonthMenuoptDetailBO::getInstance();
        $summaryStoreorderDeeDayBO                  = SummaryStoreorderDeeDayBO::getInstance();
        $summaryStoreorderDeeDayMenuBO              = SummaryStoreorderDeeDayMenuBO::getInstance();
        $summaryStoreorderDeeDayMenuRecommendBO     = SummaryStoreorderDeeDayMenuRecommendBO::getInstance();
        $summaryStoreorderDeeDayMenuoptBO           = SummaryStoreorderDeeDayMenuoptBO::getInstance();
        $summaryStoreorderDeeDaymenuoptDetailBO     = SummaryStoreorderDeeDaymenuoptDetailBO::getInstance();
        $summaryUserorderAllBO                      = SummaryUserorderAllBO::getInstance();
        $summaryUserorderAllMenuBO                  = SummaryUserorderAllMenuBO::getInstance();
        $summaryUserorderAllMenuRecommendBO         = SummaryUserorderAllMenuRecommendBO::getInstance();
        $summaryUserorderAllMenuoptBO               = SummaryUserorderAllMenuoptBO::getInstance();
        $summaryUserorderAllMenuoptDetailBO         = SummaryUserorderAllMenuoptDetailBO::getInstance();
        $summaryUserorderAllStoreBO                 = SummaryUserorderAllStoreBO::getInstance();
        $reviewBO                                   = ReviewBO::getInstance();

        /* vars */
        $storeno                    = Common::get($orderingModel, GGF::STORENO);
        $orderno                    = Common::get($orderingModel, OrderBO::FIELD__ORDERNO);
        $orderer                    = Common::get($orderingModel, OrderBO::FIELD__ORDERER);
        $orderstatus                = Common::get($orderingModel, OrderBO::FIELD__ORDERSTATUS);
        $orderstatusOrigin          = Common::get($orderingModel, OrderBO::FIELD__ORDERSTATUS);
        $orderbillFinal             = Common::get($orderingModel, OrderBO::FIELD__ORDERBILL_TOTAL);
        $summaryFlg                 = Common::get($orderingModel, OrderBO::FIELD__SUMMARY_FLG);
        $summaryDatetime            = Common::get($orderingModel, OrderBO::FIELD__SUMMARY_DATETIME);
        $summaryAftercancelFlg      = Common::get($orderingModel, OrderBO::FIELD__SUMMARY_AFTERCANCEL_FLG);
        $summaryAftercancelDatetime = Common::get($orderingModel, OrderBO::FIELD__SUMMARY_AFTERCANCEL_DATETIME);

        /* override orderstatus */
        if($orderstatusOverride != "")
            $orderstatus = $orderstatusOverride;

        /* get order detail */
        $orderDetail = Common::getDataOne($orderaBO->selectOrderDetailOnlyForInside($storeno, $orderno, GGF::SERVICE_LAYER__ADM));

        /* ----- */
        /* get order review score (리뷰데이터가 있고, 최종적으로 완료된 주문이라면) */
        /* ----- */
        $reviewOrder = $orderDetail['reviewOrder'];
        $reviewMenus = [];
        $reviewOrderScore = null;
        if(isset($reviewOrder[0]) && $orderstatusOrigin == OrderBO::ORDERSTATUS__COMPLETE)
        {
            /* 리뷰가 완료되었음 */
            if($reviewOrder[0][ReviewBO::FIELD__REVIEW_STATUS] == ReviewBO::REVIEW_STATUS__COMPLETE)
            {
                $reviewOrderScore = $reviewOrder[0][ReviewBO::FIELD__REVIEW_SCORE];

                /* 리뷰 메뉴 조회 */
                if(isset($reviewOrder[0][ReviewBO::FIELD__REVIEW_MENUS]))
                    $reviewMenus = $reviewOrder[0][ReviewBO::FIELD__REVIEW_MENUS];
            }
        }

        /* ----- */
        /* countup process */
        /* ----- */
        /* summary_storeorder_all */       $summaryStoreorderAllBO->updateCountupForInside($orderstatus, $storeno, $orderbillFinal);
        /* summary_storeorder_all_user */  $summaryStoreorderAllUserBO->updateCountupForInside($orderstatus, $storeno, $orderer, $orderbillFinal);
        /* summary_storeorder_bee_year */  $summaryStoreorderBeeYearBO->updateCountupForInside($orderstatus, $storeno, $summaryYear, $orderbillFinal);
        /* summary_storeorder_cee_month */ $summaryStoreorderCeeMonthBO->updateCountupForInside($orderstatus, $storeno, $summaryYear, $summaryMonth, $orderbillFinal);
        /* summary_storeorder_dee_day */   $summaryStoreorderDeeDayBO->updateCountupForInside($orderstatus, $storeno, $summaryYear, $summaryMonth, $summaryDay, $orderbillFinal);
        /* summary_userorder_all */        $summaryUserorderAllBO->updateCountupForInside($orderstatus, $orderer, $orderbillFinal);
        /* summary_userorder_all_store */  $summaryUserorderAllStoreBO->updateCountupForInside($orderstatus, $orderer, $storeno, $orderbillFinal);

        if($reviewOrderScore != null)
        {
            /* summary_storeorder_all */       $summaryStoreorderAllBO->updateReivewScoreForInside($orderstatus, $storeno, $reviewOrderScore);
            /* summary_storeorder_all_user */  $summaryStoreorderAllUserBO->updateReivewScoreForInside($orderstatus, $storeno, $orderer, $reviewOrderScore);
            /* summary_storeorder_bee_year */  $summaryStoreorderBeeYearBO->updateReivewScoreForInside($orderstatus, $storeno, $summaryYear, $reviewOrderScore);
            /* summary_storeorder_cee_month */ $summaryStoreorderCeeMonthBO->updateReivewScoreForInside($orderstatus, $storeno, $summaryYear, $summaryMonth, $reviewOrderScore);
            /* summary_storeorder_dee_day */   $summaryStoreorderDeeDayBO->updateReivewScoreForInside($orderstatus, $storeno, $summaryYear, $summaryMonth, $summaryDay, $reviewOrderScore);
            /* summary_userorder_all */        $summaryUserorderAllBO->updateReivewScoreForInside($orderstatus, $orderer, $reviewOrderScore);
            /* summary_userorder_all_store */  $summaryUserorderAllStoreBO->updateReivewScoreForInside($orderstatus, $orderer, $storeno, $reviewOrderScore);
        }

        foreach($orderDetail['ordermenu'] as $ordermenu)
        {
            $reviewOrderMenuScore = null;
            $menuno           = Common::get($ordermenu, OrdermenuBO::FIELD__MENUNO);
            $cartIndex        = Common::get($ordermenu, OrdermenuBO::FIELD__CART_INDEX);
            $quantity         = Common::get($ordermenu, OrdermenuBO::FIELD__QUANTITY);
            $cartmenuSummary  = Common::get($ordermenu, OrdermenuBO::FIELD__CARTMENU_SUMMARY);

            /* get menus review menu score */
            if($orderstatusOrigin == OrderBO::ORDERSTATUS__COMPLETE)
            {
                foreach($reviewMenus as $reviewMenu)
                {
                    if(
                        $reviewMenu[GGF::STORENO] == $storeno &&
                        $reviewMenu[ReviewMenuBO::FIELD__ORDERNO]     == $orderno &&
                        $reviewMenu[ReviewMenuBO::FIELD__CART_INDEX]  == $cartIndex
                    )
                    {
                        $reviewOrderMenuScore = $reviewMenu[ReviewMenuBO::FIELD__REVIEW_SCORE];
                    }
                }
            }

            /* summary_storeorder_all_menu */        $summaryStoreorderAllMenuBO->updateCountupForInside($orderstatus, $storeno, $menuno, $quantity, $cartmenuSummary);
            /* summary_storeorder_bee_year_menu */   $summaryStoreorderBeeYearMenuBO->updateCountupForInside($orderstatus, $storeno, $summaryYear, $menuno, $quantity, $cartmenuSummary);
            /* summary_storeorder_cee_month_menu */  $summaryStoreorderCeeMonthMenuBO->updateCountupForInside($orderstatus, $storeno, $summaryYear, $summaryMonth, $menuno, $quantity, $cartmenuSummary);
            /* summary_storeorder_dee_day_menu */    $summaryStoreorderDeeDayMenuBO->updateCountupForInside($orderstatus, $storeno, $summaryYear, $summaryMonth, $summaryDay, $menuno, $quantity, $cartmenuSummary);
            /* summary_userorder_all_menu */         $summaryUserorderAllMenuBO->updateCountupForInside($orderstatus, $orderer,    $storeno,  $menuno, $quantity, $cartmenuSummary);

            /* update reivew menu score */
            if($reviewOrderMenuScore != null)
            {
                /* summary_storeorder_all_menu */        $summaryStoreorderAllMenuBO->updateReivewScoreForInside($orderstatus, $storeno, $menuno, $reviewOrderMenuScore);
                /* summary_storeorder_bee_year_menu */   $summaryStoreorderBeeYearMenuBO->updateReivewScoreForInside($orderstatus, $storeno, $summaryYear, $menuno, $reviewOrderMenuScore);
                /* summary_storeorder_cee_month_menu */  $summaryStoreorderCeeMonthMenuBO->updateReivewScoreForInside($orderstatus, $storeno, $summaryYear, $summaryMonth, $menuno, $reviewOrderMenuScore);
                /* summary_storeorder_dee_day_menu */    $summaryStoreorderDeeDayMenuBO->updateReivewScoreForInside($orderstatus, $storeno, $summaryYear, $summaryMonth, $summaryDay, $menuno, $reviewOrderMenuScore);
                /* summary_userorder_all_menu */         $summaryUserorderAllMenuBO->updateReivewScoreForInside($orderstatus, $orderer,    $storeno,  $menuno, $reviewOrderMenuScore);
            }


            foreach($ordermenu['ordermenuopt'] as $ordermenuopt)
            {
                $menuoptno = Common::get($ordermenuopt, OrdermenuoptBO::FIELD__MENUOPTNO);
                /* summary_storeorder_all_menuopt */       $summaryStoreorderAllMenuoptBO->updateCountupForInside($orderstatus, $storeno, $menuno, $menuoptno);
                /* summary_storeorder_bee_year_menuopt */  $summaryStoreorderBeeYearMenuoptBO->updateCountupForInside($orderstatus, $storeno, $summaryYear, $menuno, $menuoptno);
                /* summary_storeorder_cee_month_menuopt */ $summaryStoreorderCeeMonthMenuoptBO->updateCountupForInside($orderstatus, $storeno, $summaryYear, $summaryMonth, $menuno, $menuoptno);
                /* summary_storeorder_dee_day_menuopt */   $summaryStoreorderDeeDayMenuoptBO->updateCountupForInside($orderstatus, $storeno, $summaryYear, $summaryMonth, $summaryDay, $menuno, $menuoptno);
                /* summary_userorder_all_menuopt */        $summaryUserorderAllMenuoptBO->updateCountupForInside($orderstatus, $orderer, $storeno, $menuno, $menuoptno);

                /* update reivew menu score */
                if($reviewOrderMenuScore != null)
                {
                    /* summary_storeorder_all_menuopt */       $summaryStoreorderAllMenuoptBO->updateReivewScoreForInside($orderstatus, $storeno, $menuno, $menuoptno, $reviewOrderMenuScore);
                    /* summary_storeorder_bee_year_menuopt */  $summaryStoreorderBeeYearMenuoptBO->updateReivewScoreForInside($orderstatus, $storeno, $summaryYear, $menuno, $menuoptno, $reviewOrderMenuScore);
                    /* summary_storeorder_cee_month_menuopt */ $summaryStoreorderCeeMonthMenuoptBO->updateReivewScoreForInside($orderstatus, $storeno, $summaryYear, $summaryMonth, $menuno, $menuoptno, $reviewOrderMenuScore);
                    /* summary_storeorder_dee_day_menuopt */   $summaryStoreorderDeeDayMenuoptBO->updateReivewScoreForInside($orderstatus, $storeno, $summaryYear, $summaryMonth, $summaryDay, $menuno, $menuoptno, $reviewOrderMenuScore);
                    /* summary_userorder_all_menuopt */        $summaryUserorderAllMenuoptBO->updateReivewScoreForInside($orderstatus, $orderer, $storeno, $menuno, $menuoptno, $reviewOrderMenuScore);
                }

                foreach($ordermenuopt['ordermenuopt_detail'] as $ordermenuoptDetail)
                {
                    $optdetailno  = Common::get($ordermenuoptDetail, OrdermenuoptDetailBO::FIELD__OPTDETAILNO);
                    $optdetailPrice = Common::get($ordermenuoptDetail, OrdermenuoptDetailBO::FIELD__OPTDETAIL_PRICE);
                    /* summary_storeorder_all_menuopt_detail */        $summaryStoreorderAllMenuoptDetailBO->updateCountupForInside($orderstatus, $storeno, $menuno, $menuoptno, $optdetailno, $optdetailPrice);
                    /* summary_storeorder_bee_year_menuopt_detail */   $summaryStoreorderBeeYearMenuoptDetailBO->updateCountupForInside($orderstatus, $storeno, $summaryYear, $menuno, $menuoptno, $optdetailno, $optdetailPrice);
                    /* summary_storeorder_cee_month_menuopt_detail */  $summaryStoreorderCeeMonthMenuoptDetailBO->updateCountupForInside($orderstatus, $storeno, $summaryYear, $summaryMonth, $menuno, $menuoptno, $optdetailno, $optdetailPrice);
                    /* summary_storeorder_dee_daymenuopt_detail */     $summaryStoreorderDeeDaymenuoptDetailBO->updateCountupForInside($orderstatus, $storeno, $summaryYear, $summaryMonth, $summaryDay, $menuno, $menuoptno, $optdetailno, $optdetailPrice);
                    /* summary_userorder_all_menuopt_detail */         $summaryUserorderAllMenuoptDetailBO->updateCountupForInside($orderstatus, $orderer, $storeno, $menuno, $menuoptno, $optdetailno, $optdetailPrice);

                    /* update reivew menu score */
                    if($reviewOrderMenuScore != null)
                    {
                        /* summary_storeorder_all_menuopt_detail */        $summaryStoreorderAllMenuoptDetailBO->updateReivewScoreForInside($orderstatus, $storeno, $menuno, $menuoptno, $optdetailno, $reviewOrderMenuScore);
                        /* summary_storeorder_bee_year_menuopt_detail */   $summaryStoreorderBeeYearMenuoptDetailBO->updateReivewScoreForInside($orderstatus, $storeno, $summaryYear, $menuno, $menuoptno, $optdetailno, $reviewOrderMenuScore);
                        /* summary_storeorder_cee_month_menuopt_detail */  $summaryStoreorderCeeMonthMenuoptDetailBO->updateReivewScoreForInside($orderstatus, $storeno, $summaryYear, $summaryMonth, $menuno, $menuoptno, $optdetailno, $reviewOrderMenuScore);
                        /* summary_storeorder_dee_daymenuopt_detail */     $summaryStoreorderDeeDaymenuoptDetailBO->updateReivewScoreForInside($orderstatus, $storeno, $summaryYear, $summaryMonth, $summaryDay, $menuno, $menuoptno, $optdetailno, $reviewOrderMenuScore);
                        /* summary_userorder_all_menuopt_detail */         $summaryUserorderAllMenuoptDetailBO->updateReivewScoreForInside($orderstatus, $orderer, $storeno, $menuno, $menuoptno, $optdetailno, $reviewOrderMenuScore);
                    }
                }
            }

            foreach($ordermenu['ordermenu_recommend'] as $ordermenuRecommend)
            {
                $recommendno     = Common::get($ordermenuRecommend, OrdermenuRecommendBO::FIELD__RECOMMENDNO);
                $recommendQuantity = Common::get($ordermenuRecommend, OrdermenuRecommendBO::FIELD__QUANTITY);
                $recommendDiscount = Common::get($ordermenuRecommend, OrdermenuRecommendBO::FIELD__RECOMMEND_DISCOUNT);
                /* summary_storeorder_all_menu_recommend */        $summaryStoreorderAllMenuRecommendBO->updateCountupForInside($orderstatus, $storeno, $menuno, $recommendno, $recommendQuantity, $recommendDiscount);
                /* summary_storeorder_bee_year_menu_recommend */   $summaryStoreorderBeeYearMenuRecommendBO->updateCountupForInside($orderstatus, $storeno, $summaryYear, $menuno, $recommendno, $recommendQuantity, $recommendDiscount);
                /* summary_storeorder_cee_month_menu_recommend */  $summaryStoreorderCeeMonthMenuRecommendBO->updateCountupForInside($orderstatus, $storeno, $summaryYear, $summaryMonth, $menuno, $recommendno, $recommendQuantity, $recommendDiscount);
                /* summary_storeorder_dee_day_menu_recommend */    $summaryStoreorderDeeDayMenuRecommendBO->updateCountupForInside($orderstatus, $storeno, $summaryYear, $summaryMonth, $summaryDay, $menuno, $recommendno, $recommendQuantity, $recommendDiscount);
                /* summary_userorder_all_menu_recommend */         $summaryUserorderAllMenuRecommendBO->updateCountupForInside($orderstatus, $orderer, $storeno, $menuno, $recommendno, $recommendQuantity, $recommendDiscount);

                /* update reivew menu score */
                if($reviewOrderMenuScore != null)
                {
                    /* summary_storeorder_all_menu_recommend */        $summaryStoreorderAllMenuRecommendBO->updateReivewScoreForInside($orderstatus, $storeno, $menuno, $recommendno, $reviewOrderMenuScore);
                    /* summary_storeorder_bee_year_menu_recommend */   $summaryStoreorderBeeYearMenuRecommendBO->updateReivewScoreForInside($orderstatus, $storeno, $summaryYear, $menuno, $recommendno, $reviewOrderMenuScore);
                    /* summary_storeorder_cee_month_menu_recommend */  $summaryStoreorderCeeMonthMenuRecommendBO->updateReivewScoreForInside($orderstatus, $storeno, $summaryYear, $summaryMonth, $menuno, $recommendno, $reviewOrderMenuScore);
                    /* summary_storeorder_dee_day_menu_recommend */    $summaryStoreorderDeeDayMenuRecommendBO->updateReivewScoreForInside($orderstatus, $storeno, $summaryYear, $summaryMonth, $summaryDay, $menuno, $recommendno, $reviewOrderMenuScore);
                    /* summary_userorder_all_menu_recommend */         $summaryUserorderAllMenuRecommendBO->updateReivewScoreForInside($orderstatus, $orderer, $storeno, $menuno, $recommendno, $reviewOrderMenuScore);
                }
            }
        }

        /* update summary info to ordering / ordera */
        $orderingBO->updateSummaryInfoForInside($storeno, $orderno, $orderstatus, $summaryDateStr);

        /* remove not complete review data (완료되지 않은 리뷰 데이터 삭제) */
        $reviewBO->deleteIfWritingByOrdernoForInside($storeno, $orderno);
        $this->afterProcess();
        return true;
    }

}

?>