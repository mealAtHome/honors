<?php

    /*
        일별 / 월별 / 연도별 집계

        ※ 통계용 처리이며, 삭제 후 인서트이기 때문에 트렌젝션 걸지 않음
    */

    /* ========================= */
    /* init */
    /* ========================= */
    include '../env/env.php';

    /* get bo */
    GGnavi::getPer50DaySalesSummary();

    /* init */
    $batchname = basename(__FILE__);
    $rslt = Common::getReturn();

    /* BO */
    $batchBO = Per50DaySalesSummary::getInstance();

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