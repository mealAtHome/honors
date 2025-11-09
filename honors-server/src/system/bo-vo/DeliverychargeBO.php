<?php

/* DeliverychargeVO */
class DeliverychargeBO extends _CommonBO
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
    function __construct() {

    }

    const FIELD__CHARGE            = "charge";           /* 총배달료 */
    const FIELD__STORE_CHARGE      = "store_charge";     /* 가게부담금 */
    const FIELD__DEFAULT_CHARGE    = "default_charge";   /* 기본배달료 (시스템배달료 - 지역할인) */
    const FIELD__WEATHER_CHARGE    = "weather_charge";   /* 날씨할증 */

    /* ========================= */
    /* select */
    /* ========================= */
    /**
     * @param $EXECUTOR : 주문자
     * @param $ADDR_INDEX : 주문자의 배송장소
     * @param $STORENO : 가게인덱스
     * @param $MENUTOTAL  : 주문총액 (메뉴금액합계)
     */
    public function select($EXECUTOR, $ADDR_INDEX, $STORENO, $MENUTOTAL)
    {
        /* --------------- */
        /* getBO */
        /* --------------- */
        GGnavi::getCartBO();
        GGnavi::getStoreDeliverychargeBO();
        GGnavi::getRefFeeBO();
        GGnavi::getRefDeliverychargeWeatherBO();
        GGnavi::getRefDeliverychargeDiscountBO();

        /* BO */
        $cartBO = CartBO::getInstance();
        $storeDeliverychargeBO = StoreDeliverychargeBO::getInstance();
        $refFeeBO = RefFeeBO::getInstance();
        $refDeliverychargeWeatherBO = RefDeliverychargeWeatherBO::getInstance();
        $refDeliverychargeDiscountBO = RefDeliverychargeDiscountBO::getInstance();

        /* --------------- */
        /* sql body */
        /* --------------- */
        /* 유저 주소 검색 */
        $userAddrBO = UserAddrBO::getInstance();
        $userAddr = Common::getDataOne($userAddrBO->selectByAddrIndexForInside($EXECUTOR, $ADDR_INDEX));
        $addrSido = $userAddr[UserAddrBO::FIELD__ADDR_SIDO];
        $addrSigungu = $userAddr[UserAddrBO::FIELD__ADDR_SIGUNGU];

        /* 조회 */
        $defaultCharge = $refFeeBO->getDeliverychargeNow(); /* 기본배달료 */
        $storeCharge = $defaultCharge - $storeDeliverychargeBO->getChargeByMenutotal($STORENO, $MENUTOTAL, $defaultCharge); /* 가게부담금 */
        $weatherCharge = $refDeliverychargeWeatherBO->getChargeByAddr($addrSido, $addrSigungu); /* 날씨가산 */
        $locationDiscount = $refDeliverychargeDiscountBO->getDiscountByLocation($addrSido); /* 지역할인 */

        $charge = $defaultCharge - $locationDiscount - $storeCharge + $weatherCharge;

        /* 데이터 삽입 */
        $rslt = Common::getReturn();
        $rslt[GGF::DATA][] = array(
            DeliverychargeBO::FIELD__CHARGE          => $charge
           ,DeliverychargeBO::FIELD__STORE_CHARGE    => $storeCharge
           ,DeliverychargeBO::FIELD__DEFAULT_CHARGE  => $defaultCharge - $locationDiscount
           ,DeliverychargeBO::FIELD__WEATHER_CHARGE  => $weatherCharge
       );
        return $rslt;
    }

    /**
     * @param $EXECUTOR : 주문자
     * @param $ADDR_INDEX : 주문자의 배송장소
     * @param $STORENO : 가게인덱스
     * @param $MENUTOTAL  : 주문총액 (메뉴금액합계)
     * @param $ORDERBILL_DELIVERY : 클라이언트로부터 넘어온 배달료->이 값이 정확한지 검증
     */
    public function validateDeliverycharge($EXECUTOR, $ADDR_INDEX, $STORENO, $MENUTOTAL, $ORDERBILL_DELIVERY)
    {
        /* get bo */

        /* bo */

        /* vars */
        $orderbillDelivery = intval($ORDERBILL_DELIVERY);
        $deliverycharge = Common::getDataOneField($this->select($EXECUTOR, $ADDR_INDEX, $STORENO, $MENUTOTAL), self::FIELD__CHARGE);

        /* 에러 있을 시 로직 진행할 수 없음 */
        if($orderbillDelivery != $deliverycharge)
            return false;

        return true;
    }

}
?>
