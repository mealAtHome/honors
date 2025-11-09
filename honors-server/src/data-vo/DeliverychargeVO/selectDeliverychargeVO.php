<?php

    /**
     * @param STORENO
     * @param MENUTOTAL : 메뉴금액 합계
     * @param ADDR_INDEX : 배달하려는 주소의 인덱스
     */

    /* ============================ */
    /* init */
    /* ============================ */
    include '../../env/env.php';
    GGnavi::getDeliverychargeBO();

    /* get BO */
    $deliverychargeBO = DeliverychargeBO::getInstance();

    /* return */
    $rslt = Common::getReturn();

    /* ============================ */
    /* process */
    /* ============================ */
    try
    {
        $rslt = $deliverychargeBO->select($EXECUTOR, $ADDR_INDEX, $STORENO, $MENUTOTAL);
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