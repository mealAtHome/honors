<?php

    /* ============================ */
    /* init */
    /* ============================ */
    require_once '../../env/env.php';
    GGnavi::getUserBO();

    /* vars */
    $userBO = UserBO::getInstance();

    /* ============================ */
    /* process */
    /* ============================ */
    GGsql::autoCommitFalse();
    try
    {
        $rslt = $userBO->updateByOption($options);
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


    /* print rslt */
    Common::returnRslt($rslt);

?>
