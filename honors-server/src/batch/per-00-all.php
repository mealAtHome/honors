<?php

    /* ========================= */
    /* init */
    /* ========================= */
    include '../env/env.php';
    GGnavi::getPer10ApiInsertPaymentDepositedByList();
    GGnavi::getPer20SecRiderDeliverymatch();
    GGnavi::getPer30MinOrderCancelNotConfirmed();
    GGnavi::getPer30MinOrderCancelNotPaid();
    GGnavi::getPer30MinOrderingToOrdera();
    GGnavi::getPer30MinStoreSalestatusAutoProcess();
    GGnavi::getPer40HouPartitions();
    GGnavi::getPer40HouStoreOrderproctimeSummaryToday();
    /* GGnavi::getPer50DaySalesSummary(); */
    GGnavi::getPer50DayStoreOrderproctimeSummaryMonth();
    GGnavi::getPer50DaySummaryStoreorderRecent();
    GGnavi::getPer50DayUpdateReorderpct();

    /* vars */
    $rslt = Common::getReturn();

    /* BO */
    $per20SecRiderDeliverymatch = Per20SecRiderDeliverymatch::getInstance();
    $per30MinOrderCancelNotConfirmed = Per30MinOrderCancelNotConfirmed::getInstance();
    $per30MinOrderCancelNotPaid = Per30MinOrderCancelNotPaid::getInstance();
    $per30MinOrderingToOrdera = Per30MinOrderingToOrdera::getInstance();
    $per30MinStoreSalestatusAutoProcess = Per30MinStoreSalestatusAutoProcess::getInstance();
    $per40HouPartitions = Per40HouPartitions::getInstance();
    $per40HouStoreOrderproctimeSummaryToday = Per40HouStoreOrderproctimeSummaryToday::getInstance();
    $per50DayStoreOrderproctimeSummaryMonth = Per50DayStoreOrderproctimeSummaryMonth::getInstance();
    $per50DaySummaryStoreorderRecent = Per50DaySummaryStoreorderRecent::getInstance();
    $per50DayUpdateReorderpct = Per50DayUpdateReorderpct::getInstance();

    /* ========================= */
    /* process */
    /* ========================= */
    try
    {
        GGsql::autoCommitFalse();

        /* process */
        $per20SecRiderDeliverymatch->process();
        $per30MinOrderCancelNotConfirmed->process();
        $per30MinOrderCancelNotPaid->process();
        $per30MinOrderingToOrdera->process();
        $per30MinStoreSalestatusAutoProcess->process();
        $per40HouPartitions->process();
        $per40HouStoreOrderproctimeSummaryToday->process();
        $per50DayStoreOrderproctimeSummaryMonth->process();
        $per50DaySummaryStoreorderRecent->process();
        $per50DayUpdateReorderpct->process();
        /* $per50DaySalesSummary->process(); */

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
        GGsql::autoCommitTrue();
    }


    Common::returnRslt($rslt);

?>
