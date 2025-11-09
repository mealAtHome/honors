<?php

    /*
        매장 자동 영업시간 설정
    */

    /* ========================= */
    /* init */
    /* ========================= */
    include '../env/env.php';

    /* get bo */
    GGnavi::getPer30MinStoreSalestatusAutoProcess();

    /* init */
    $batchname = basename(__FILE__);
    $rslt = Common::getReturn();

    /* BO */
    $batchBO = Per30MinStoreSalestatusAutoProcess::getInstance();

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