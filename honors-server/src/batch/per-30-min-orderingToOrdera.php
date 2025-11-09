<?php

    /*
        1. 분마다, 3시간 지난 완료/취소된 주문을 ordering 테이블에서 삭제한다.
        2. 이 때, 취소되는 주문을 통계 테이블에 반영한다.

        ※ ordering으로부터 삭제가 요구되기 때문에 트렌젝션을 검. 처리 실패했으면 원위치 필요.
    */

    /* ========================= */
    /* init */
    /* ========================= */
    include '../env/env.php';

    /* get bo */
    GGnavi::getPer30MinOrderingToOrdera();

    /* init */
    $batchname = basename(__FILE__);
    $rslt = Common::getReturn();

    /* BO */
    $batchBO = Per30MinOrderingToOrdera::getInstance();

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