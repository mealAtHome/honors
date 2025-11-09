<?php

class MOrder
{
    public $storeno = null;
    public $orderno = null;
    public $ordernoWord = null;
    public $orderer = null;
    public $riderno = null;
    public $datetimeYmd = null;
    public $datetimeYear = null;
    public $datetimeMonth = null;
    public $datetimeDay = null;
    public $datetimeHour = null;
    public $datetimeWeekday = null;
    public $datetimeHolidayflg = null;
    public $ordertype = null;
    public $deliverytype = null;
    public $orderstatus = null;
    public $deliverystatus = null;
    public $isDisposable = null;
    public $requestStore = null;
    public $requestDelivery = null;
    public $paymenttype = null;
    public $paymenttypeAcctDepositor = null;
    public $cashreceiptType = null;
    public $cashreceiptName = null;
    public $cashreceiptPhone = null;
    public $cashreceiptCorp = null;
    public $orderbillTotal = null;
    public $orderbillMenu = null;
    public $orderbillDelivery = null;
    public $orderbillDeliveryDefault = null;
    public $orderbillDeliveryDistance = null;
    public $orderbillDeliveryNight = null;
    public $orderbillDeliveryWeather = null;
    public $orderbillDeliveryStore = null;
    public $orderbillStoreFee = null;
    public $orderbillRiderFee = null;
    public $atReview = null;
    public $atRefundreq = null;
    public $atRefundcom = null;
    public $atSettlestore = null;
    public $atSettlerider = null;
    public $atOrderstatusPay = null;
    public $atOrderstatusConfirm = null;
    public $atOrderstatusPaying = null;
    public $atOrderstatusCook = null;
    public $atOrderstatusDone = null;
    public $atOrderstatusComplete = null;
    public $atOrderstatusCancel = null;
    public $atOrderstatusCancelafter = null;
    public $atOrderstatusClaim = null;
    public $atDeliverystatusMatching = null;
    public $atDeliverystatusTostore = null;
    public $atDeliverystatusDeliverying = null;
    public $atDeliverystatusComplete = null;
    public $atUpdate = null;
    public $atRegist = null;
    public $addrIndex = null;
    public $addrName = null;
    public $addrZipcode = null;
    public $addrSido = null;
    public $addrSigungu = null;
    public $addrEmd = null;
    public $addrRoad = null;
    public $addrJibun = null;
    public $addrRoadeng = null;
    public $addrDetail = null;
    public $orderstatusCanceler = null;
    public $orderstatusCancelpayer = null;
    public $orderstatusCanceletype = null;
    public $orderstatusCancelermsg = null;
    public $claimStatus = null;
    public $claimComment = null;
    public $claimPic1 = null;
    public $claimPic2 = null;
    public $claimRefundflg = null;
    public $claimSyscomment = null;
    public $storeName = null;
    public $storeImg = null;

    public function getStoreno() { return $this->storeno; }
    public function getOrderno() { return $this->orderno; }
    public function getOrdernoWord() { return $this->ordernoWord; }
    public function getOrderer() { return $this->orderer; }
    public function getRiderno() { return $this->riderno; }
    public function getDatetimeYmd() { return $this->datetimeYmd; }
    public function getDatetimeYear() { return $this->datetimeYear; }
    public function getDatetimeMonth() { return $this->datetimeMonth; }
    public function getDatetimeDay() { return $this->datetimeDay; }
    public function getDatetimeHour() { return $this->datetimeHour; }
    public function getDatetimeWeekday() { return $this->datetimeWeekday; }
    public function getDatetimeHolidayflg() { return $this->datetimeHolidayflg; }
    public function getOrdertype() { return $this->ordertype; }
    public function getDeliverytype() { return $this->deliverytype; }
    public function getOrderstatus() { return $this->orderstatus; }
    public function getDeliverystatus() { return $this->deliverystatus; }
    public function getIsDisposable() { return $this->isDisposable; }
    public function getRequestStore() { return $this->requestStore; }
    public function getRequestDelivery() { return $this->requestDelivery; }
    public function getPaymenttype() { return $this->paymenttype; }
    public function getPaymenttypeAcctDepositor() { return $this->paymenttypeAcctDepositor; }
    public function getCashreceiptType() { return $this->cashreceiptType; }
    public function getCashreceiptName() { return $this->cashreceiptName; }
    public function getCashreceiptPhone() { return $this->cashreceiptPhone; }
    public function getCashreceiptCorp() { return $this->cashreceiptCorp; }
    public function getOrderbillTotal() { return $this->orderbillTotal; }
    public function getOrderbillMenu() { return $this->orderbillMenu; }
    public function getOrderbillDelivery() { return $this->orderbillDelivery; }
    public function getOrderbillDeliveryDefault() { return $this->orderbillDeliveryDefault; }
    public function getOrderbillDeliveryDistance() { return $this->orderbillDeliveryDistance; }
    public function getOrderbillDeliveryNight() { return $this->orderbillDeliveryNight; }
    public function getOrderbillDeliveryWeather() { return $this->orderbillDeliveryWeather; }
    public function getOrderbillDeliveryStore() { return $this->orderbillDeliveryStore; }
    public function getOrderbillStoreFee() { return $this->orderbillStoreFee; }
    public function getOrderbillRiderFee() { return $this->orderbillRiderFee; }
    public function getAtReview() { return $this->atReview; }
    public function getAtRefundreq() { return $this->atRefundreq; }
    public function getAtRefundcom() { return $this->atRefundcom; }
    public function getAtSettlestore() { return $this->atSettlestore; }
    public function getAtSettlerider() { return $this->atSettlerider; }
    public function getAtOrderstatusPay() { return $this->atOrderstatusPay; }
    public function getAtOrderstatusConfirm() { return $this->atOrderstatusConfirm; }
    public function getAtOrderstatusPaying() { return $this->atOrderstatusPaying; }
    public function getAtOrderstatusCook() { return $this->atOrderstatusCook; }
    public function getAtOrderstatusDone() { return $this->atOrderstatusDone; }
    public function getAtOrderstatusComplete() { return $this->atOrderstatusComplete; }
    public function getAtOrderstatusCancel() { return $this->atOrderstatusCancel; }
    public function getAtOrderstatusCancelafter() { return $this->atOrderstatusCancelafter; }
    public function getAtOrderstatusClaim() { return $this->atOrderstatusClaim; }
    public function getAtDeliverystatusMatching() { return $this->atDeliverystatusMatching; }
    public function getAtDeliverystatusTostore() { return $this->atDeliverystatusTostore; }
    public function getAtDeliverystatusDeliverying() { return $this->atDeliverystatusDeliverying; }
    public function getAtDeliverystatusComplete() { return $this->atDeliverystatusComplete; }
    public function getAtUpdate() { return $this->atUpdate; }
    public function getAtRegist() { return $this->atRegist; }
    public function getAddrIndex() { return $this->addrIndex; }
    public function getAddrName() { return $this->addrName; }
    public function getAddrZipcode() { return $this->addrZipcode; }
    public function getAddrSido() { return $this->addrSido; }
    public function getAddrSigungu() { return $this->addrSigungu; }
    public function getAddrEmd() { return $this->addrEmd; }
    public function getAddrRoad() { return $this->addrRoad; }
    public function getAddrJibun() { return $this->addrJibun; }
    public function getAddrRoadeng() { return $this->addrRoadeng; }
    public function getAddrDetail() { return $this->addrDetail; }
    public function getOrderstatusCanceler() { return $this->orderstatusCanceler; }
    public function getOrderstatusCancelpayer() { return $this->orderstatusCancelpayer; }
    public function getOrderstatusCanceletype() { return $this->orderstatusCanceletype; }
    public function getOrderstatusCancelermsg() { return $this->orderstatusCancelermsg; }
    public function getClaimStatus() { return $this->claimStatus; }
    public function getClaimComment() { return $this->claimComment; }
    public function getClaimPic1() { return $this->claimPic1; }
    public function getClaimPic2() { return $this->claimPic2; }
    public function getClaimRefundflg() { return $this->claimRefundflg; }
    public function getClaimSyscomment() { return $this->claimSyscomment; }
    public function getStoreName() { return $this->storeName; }
    public function getStoreImg() { return $this->storeImg; }


    public function __construct($order)
    {
        /* get BO */
        GGnavi::getStoreBO();
        GGnavi::getOrderingBO();
        GGnavi::getOrdersAddrBO();
        GGnavi::getOrdersCancelBO();
        GGnavi::getOrdersClaimBO();

        /* get fields */
        $this->storeno = Common::get($order, OrderBO::FIELD__STORENO);
        $this->orderno = Common::get($order, OrderBO::FIELD__ORDERNO);
        $this->ordernoWord = Common::get($order, OrderBO::FIELD__ORDERNO_WORD);
        $this->orderer = Common::get($order, OrderBO::FIELD__ORDERER);
        $this->riderno = Common::get($order, OrderBO::FIELD__RIDERNO);
        $this->datetimeYmd = Common::get($order, OrderBO::FIELD__DATETIME_YMD);
        $this->datetimeYear = Common::get($order, OrderBO::FIELD__DATETIME_YEAR);
        $this->datetimeMonth = Common::get($order, OrderBO::FIELD__DATETIME_MONTH);
        $this->datetimeDay = Common::get($order, OrderBO::FIELD__DATETIME_DAY);
        $this->datetimeHour = Common::get($order, OrderBO::FIELD__DATETIME_HOUR);
        $this->datetimeWeekday = Common::get($order, OrderBO::FIELD__DATETIME_WEEKDAY);
        $this->datetimeHolidayflg = Common::get($order, OrderBO::FIELD__DATETIME_HOLIDAYFLG);
        $this->ordertype = Common::get($order, OrderBO::FIELD__ORDERTYPE);
        $this->deliverytype = Common::get($order, OrderBO::FIELD__DELIVERYTYPE);
        $this->orderstatus = Common::get($order, OrderBO::FIELD__ORDERSTATUS);
        $this->deliverystatus = Common::get($order, OrderBO::FIELD__DELIVERYSTATUS);
        $this->isDisposable = Common::get($order, OrderBO::FIELD__IS_DISPOSABLE);
        $this->requestStore = Common::get($order, OrderBO::FIELD__REQUEST_STORE);
        $this->requestDelivery = Common::get($order, OrderBO::FIELD__REQUEST_DELIVERY);
        $this->paymenttype = Common::get($order, OrderBO::FIELD__PAYMENTTYPE);
        $this->paymenttypeAcctDepositor = Common::get($order, OrderBO::FIELD__PAYMENTTYPE_ACCT_DEPOSITOR);
        $this->cashreceiptType = Common::get($order, OrderBO::FIELD__CASHRECEIPT_TYPE);
        $this->cashreceiptName = Common::get($order, OrderBO::FIELD__CASHRECEIPT_NAME);
        $this->cashreceiptPhone = Common::get($order, OrderBO::FIELD__CASHRECEIPT_PHONE);
        $this->cashreceiptCorp = Common::get($order, OrderBO::FIELD__CASHRECEIPT_CORP);
        $this->orderbillTotal = Common::get($order, OrderBO::FIELD__ORDERBILL_TOTAL);
        $this->orderbillMenu = Common::get($order, OrderBO::FIELD__ORDERBILL_MENU);
        $this->orderbillDelivery = Common::get($order, OrderBO::FIELD__ORDERBILL_DELIVERY);
        $this->orderbillDeliveryDefault = Common::get($order, OrderBO::FIELD__ORDERBILL_DELIVERY_DEFAULT);
        $this->orderbillDeliveryDistance = Common::get($order, OrderBO::FIELD__ORDERBILL_DELIVERY_DISTANCE);
        $this->orderbillDeliveryNight = Common::get($order, OrderBO::FIELD__ORDERBILL_DELIVERY_NIGHT);
        $this->orderbillDeliveryWeather = Common::get($order, OrderBO::FIELD__ORDERBILL_DELIVERY_WEATHER);
        $this->orderbillDeliveryStore = Common::get($order, OrderBO::FIELD__ORDERBILL_DELIVERY_STORE);
        $this->orderbillStoreFee = Common::get($order, OrderBO::FIELD__ORDERBILL_STORE_FEE);
        $this->orderbillRiderFee = Common::get($order, OrderBO::FIELD__ORDERBILL_RIDER_FEE);
        $this->atReview = Common::get($order, OrderBO::FIELD__AT_REVIEW);
        $this->atRefundreq = Common::get($order, OrderBO::FIELD__AT_REFUNDREQ);
        $this->atRefundcom = Common::get($order, OrderBO::FIELD__AT_REFUNDCOM);
        $this->atSettlestore = Common::get($order, OrderBO::FIELD__AT_SETTLESTORE);
        $this->atSettlerider = Common::get($order, OrderBO::FIELD__AT_SETTLERIDER);
        $this->atOrderstatusPay = Common::get($order, OrderBO::FIELD__AT_ORDERSTATUS_PAY);
        $this->atOrderstatusConfirm = Common::get($order, OrderBO::FIELD__AT_ORDERSTATUS_CONFIRM);
        $this->atOrderstatusPaying = Common::get($order, OrderBO::FIELD__AT_ORDERSTATUS_PAYING);
        $this->atOrderstatusCook = Common::get($order, OrderBO::FIELD__AT_ORDERSTATUS_COOK);
        $this->atOrderstatusDone = Common::get($order, OrderBO::FIELD__AT_ORDERSTATUS_DONE);
        $this->atOrderstatusComplete = Common::get($order, OrderBO::FIELD__AT_ORDERSTATUS_COMPLETE);
        $this->atOrderstatusCancel = Common::get($order, OrderBO::FIELD__AT_ORDERSTATUS_CANCEL);
        $this->atOrderstatusCancelafter = Common::get($order, OrderBO::FIELD__AT_ORDERSTATUS_CANCELAFTER);
        $this->atOrderstatusClaim = Common::get($order, OrderBO::FIELD__AT_ORDERSTATUS_CLAIM);
        $this->atDeliverystatusMatching = Common::get($order, OrderBO::FIELD__AT_DELIVERYSTATUS_MATCHING);
        $this->atDeliverystatusTostore = Common::get($order, OrderBO::FIELD__AT_DELIVERYSTATUS_TOSTORE);
        $this->atDeliverystatusDeliverying = Common::get($order, OrderBO::FIELD__AT_DELIVERYSTATUS_DELIVERYING);
        $this->atDeliverystatusComplete = Common::get($order, OrderBO::FIELD__AT_DELIVERYSTATUS_COMPLETE);
        $this->atUpdate = Common::get($order, OrderBO::FIELD__AT_UPDATE);
        $this->atRegist = Common::get($order, OrderBO::FIELD__AT_REGIST);
        $this->addrIndex = Common::get($order, OrdersAddrBO::FIELD__ADDR_INDEX);
        $this->addrName = Common::get($order, OrdersAddrBO::FIELD__ADDR_NAME);
        $this->addrZipcode = Common::get($order, OrdersAddrBO::FIELD__ADDR_ZIPCODE);
        $this->addrSido = Common::get($order, OrdersAddrBO::FIELD__ADDR_SIDO);
        $this->addrSigungu = Common::get($order, OrdersAddrBO::FIELD__ADDR_SIGUNGU);
        $this->addrEmd = Common::get($order, OrdersAddrBO::FIELD__ADDR_EMD);
        $this->addrRoad = Common::get($order, OrdersAddrBO::FIELD__ADDR_ROAD);
        $this->addrJibun = Common::get($order, OrdersAddrBO::FIELD__ADDR_JIBUN);
        $this->addrRoadeng = Common::get($order, OrdersAddrBO::FIELD__ADDR_ROADENG);
        $this->addrDetail = Common::get($order, OrdersAddrBO::FIELD__ADDR_DETAIL);
        $this->orderstatusCanceler = Common::get($order, OrdersCancelBO::FIELD__ORDERSTATUS_CANCELER);
        $this->orderstatusCancelpayer = Common::get($order, OrdersCancelBO::FIELD__ORDERSTATUS_CANCELPAYER);
        $this->orderstatusCanceletype = Common::get($order, OrdersCancelBO::FIELD__ORDERSTATUS_CANCELETYPE);
        $this->orderstatusCancelermsg = Common::get($order, OrdersCancelBO::FIELD__ORDERSTATUS_CANCELERMSG);
        $this->claimStatus = Common::get($order, OrdersClaimBO::FIELD__CLAIM_STATUS);
        $this->claimComment = Common::get($order, OrdersClaimBO::FIELD__CLAIM_COMMENT);
        $this->claimPic1 = Common::get($order, OrdersClaimBO::FIELD__CLAIM_PIC1);
        $this->claimPic2 = Common::get($order, OrdersClaimBO::FIELD__CLAIM_PIC2);
        $this->claimRefundflg = Common::get($order, OrdersClaimBO::FIELD__CLAIM_REFUNDFLG);
        $this->claimSyscomment = Common::get($order, OrdersClaimBO::FIELD__CLAIM_SYSCOMMENT);
        $this->storeName = Common::get($order, StoreBO::FIELD__STORE_NAME);
        $this->storeImg = Common::get($order, StoreBO::FIELD__STORE_IMG);
    }

    public function isCancelafterAndRefund()
    {
        $rslt = false;
        if($this->orderstatus == OrderBO::ORDERSTATUS__CANCELAFTER && $this->atCancelrefund != null)
            $rslt = true;
        return $rslt;
    }


}


?>