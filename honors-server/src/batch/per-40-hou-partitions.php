<?php

    /*
        파티션 생성 / 삭제
        (트렌젝션 설정 없음)
    */

    /* ========================= */
    /* init */
    /* ========================= */
    include '../env/env.php';

    /* get bo */
    GGnavi::getPer40HouPartitions();

    /* init */
    $batchname = basename(__FILE__);
    $rslt = Common::getReturn();

    /* BO */
    $batchBO = Per40HouPartitions::getInstance();

    /* ========================= */
    /* process */
    /* ========================= */
    try
    {
        /* LOCK : start */
        $batchBO->lock($batchname);

        /* transaction : auto commit off */
        // GGsql::autoCommitFalse();

        /* process */
        $batchBO->process();

        /* transaction : commit */
        // GGsql::commit();
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
        // GGsql::autoCommitTrue();

        /* unlock process */
        $batchBO->unlock($batchname);
    }

    /* return */
    Common::returnRslt($rslt);
?>