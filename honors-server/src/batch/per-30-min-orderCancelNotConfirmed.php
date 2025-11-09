<?php

    /*
        10분 내 주문확인이 완료되지 않음.
    */

    /* ========================= */
    /* init */
    /* ========================= */
    include '../env/env.php';

    /* get bo */
    GGnavi::getPer30MinOrderCancelNotConfirmed();

    /* init */
    $batchname = basename(__FILE__);
    $rslt = Common::getReturn();

    /* BO */
    $batchBO = Per30MinOrderCancelNotConfirmed::getInstance();

    /* ========================= */
    /* process */
    /* ========================= */
    try
    {
        /* LOCK : start */
        $batchBO->lock($batchname);

        /* transaction : auto commit off */
        GGsql::autoCommitFalse();

        /* process */
        $batchBO->process();

        /* transaction : commit */
        GGsql::commit();
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
        /* transaction : auto commit on */
        GGsql::autoCommitTrue();

        /* unlock process */
        $batchBO->unlock($batchname);
    }

    /* return */
    Common::returnRslt($rslt);
?>