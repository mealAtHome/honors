<?php

    /**
     * @param RIDERNO
     * @param RIDER_LATIY : 현재 위도
     * @param RIDER_LONGX : 현재 경도
     */

    /* ============================ */
    /* init */
    /* ============================ */
    include '../../env/env.php';
    GGnavi::getOrderDeliverystatusCntForRiderBO();

    /* get BO */
    $orderDeliverystatusCntForRiderBO = OrderDeliverystatusCntForRiderBO::getInstance();

    /* return */
    $rslt = Common::getReturn();

    /* ============================ */
    /* process */
    /* ============================ */
    try
    {
        $rslt = $orderDeliverystatusCntForRiderBO->select($EXECUTOR, $DELIVERYSTATUS, $RIDER_LATIY, $RIDER_LONGX);
    }
    catch(GGexception $e)
    {
        $rslt = Common::returnError($e->getMessage(), $e);
    }
    catch(Error $e)
    {
        $rslt = Common::returnErrorObj($e);
    }

    /* print rslt */
    Common::returnRslt($rslt);

?>