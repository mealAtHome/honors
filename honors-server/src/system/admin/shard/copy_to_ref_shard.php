<?php

    /* common에서 shard 에 데이터 복사 (삭제 후 적용) */

    /* ========================= */
    /* init */
    /* ========================= */
    include '../../env/env.php';

    /* --------------- */
    /* vars */
    /* --------------- */
    $refShardBO  = RefShardBO::getInstance();

    /* 모든 샤드 정보 조회 */
    $refShardRecords = $refShardBO->subSelectAll();



    /* 각 샤드를 루프하여, 해당 샤드의 모든 테이블 데이터를 삭제한다. */


    /* 각 샤드에 해당하는 데이터를 1000건 씩 조회하여, 복사해넣는다. */


    /* print rslt */
    $rslt[GGF::CODE] = 'S-0001';
    Common::returnRslt($rslt);

?>