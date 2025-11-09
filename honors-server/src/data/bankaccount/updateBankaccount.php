<?php

    /* ============================ */
    /* init */
    /* ============================ */
    include '../../env/env.php';

    /* set bo */
    GGnavi::getBankaccountBO();
    $bankaccountBO = BankaccountBO::getInstance();

    /* vars */
    $rslt = Common::getReturn();

    /* ============================ */
    /* process */
    /* ============================ */
    GGsql::autoCommitFalse();
    try
    {
        $baccno = $bankaccountBO->updateByOption($options);
        $rslt[GGF::DATA] = $baccno;
    }
    catch(GGexception $e)
    {
        $rslt = Common::returnError($e->getMessage(), $e);
    }
    catch(Error $e)
    {
        $rslt = Common::returnErrorObj($e);
    }
    GGsql::commit();
    Common::returnRslt($rslt);

?>
