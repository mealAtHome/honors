<?php

    /*
        rider_deliverymatch 테이블에 입력되어 있는 모든 데이터를 처리함 (매칭처리)
    */

    /* ========================= */
    /* init */
    /* ========================= */
    include '../env/env.php';

    /* get bo */
    GGnavi::getPer20SecRiderDeliverymatch();

    /* init */
    $batchname = basename(__FILE__);
    $rslt = Common::getReturn();

    /* BO */
    $batchBO = Per20SecRiderDeliverymatch::getInstance();

    /* ========================= */
    /* process */
    /* ========================= */

    try
    {
        /* LOCK : start */
        $batchBO->lock($batchname);

        /* process */
        $batchBO->process();
    }
    catch(GGexception $e)
    {
        $rslt = Common::returnError($e->getMessage(), $e);
    }
    catch(Error $e)
    {
        $rslt = Common::returnErrorObj($e);
    }
    finally
    {
        /* unlock process */
        $batchBO->unlock($batchname);
    }

    /* return */
    Common::returnRslt($rslt);
?>