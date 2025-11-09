<?php

    /*
        매장의 주문처리 시간의 오늘분을 통계함.
        (매장의 매일마다 영업 컨디션에 따라 고객이 참고할 수 있도록)

        ※ 삭제 후, 인서트 처리이기 때문에 트렌젝션 설정 안 함
    */

    /* ========================= */
    /* init */
    /* ========================= */
    include '../env/env.php';

    /* get bo */
    GGnavi::getPer40HouStoreOrderproctimeSummaryToday();

    /* init */
    $batchname = basename(__FILE__);
    $rslt = Common::getReturn();

    /* BO */
    $batchBO = Per40HouStoreOrderproctimeSummaryToday::getInstance();

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