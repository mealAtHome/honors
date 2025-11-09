<?php

/* OrderDeliverystatusCntForRiderVO */
class OrderDeliverystatusCntForRiderBO extends _CommonBO
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
    function __construct()
    {

    }

    const FIELD__MATCHING = "matching"; /* 매칭가능한 주문 수 */
    const FIELD__DELIVERY = "delivery"; /* 배달중인 주문 수 */
    const FIELD__COMPLETE = "complete"; /* 완료한 배달 수 */

    /* ========================= */
    /* select */
    /* ========================= */
    public function select($EXECUTOR, $DELIVERYSTATUS, $RIDER_LATIY, $RIDER_LONGX)
    {
        /* --------------- */
        /* validation */
        /* --------------- */

        /* --------------- */
        /* getBO */
        /* --------------- */
        GGnavi::getOrderingBO();

        /* BO */
        $orderingBO = OrderingBO::getInstance();

        /* --------------- */
        /* sql body */
        /* --------------- */
        /* $matching = Common::getData($orderingBO->selectByDeliverystatusForInside("matching", $RIDER_LATIY, $RIDER_LONGX)); */
        $delivery = count(Common::getData($orderingBO->selectByDeliverystatusForInside($EXECUTOR, "delivery", $RIDER_LATIY, $RIDER_LONGX)));
        $complete = count(Common::getData($orderingBO->selectByDeliverystatusForInside($EXECUTOR, "complete", $RIDER_LATIY, $RIDER_LONGX)));

        /* 데이터 삽입 */
        $rslt = Common::getReturn();
        $rslt[GGF::DATA][] = array(
             self::FIELD__MATCHING => 0
            ,self::FIELD__DELIVERY => $delivery
            ,self::FIELD__COMPLETE => $complete
       );
        return $rslt;
    }

}
?>
