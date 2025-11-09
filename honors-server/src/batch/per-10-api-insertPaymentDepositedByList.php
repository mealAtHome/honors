<?php

    /* ========================= */
    /* init */
    /* ========================= */
    include '../env/env.php';
    GGnavi::getPer10ApiInsertPaymentDepositedByList();

    /* vars */
    $rslt = Common::getReturn();

    /* BO */
    $batchBO = Per10ApiInsertPaymentDepositedByList::getInstance();

    /* ========================= */
    /* process */
    /* ========================= */
    try
    {
        /* process */
        $batchBO->process($options);
    }
    catch(GGexception $e)
    {
        $rslt = Common::returnError($e->getMessage(), $e);
    }
    catch(Error $e)
    {
        $rslt = Common::returnErrorObj($e);
    }
    Common::returnRslt($rslt);

?>
