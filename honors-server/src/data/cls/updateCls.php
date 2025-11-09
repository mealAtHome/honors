<?php

    /* ============================ */
    /* init */
    /* ============================ */
    include '../../env/env.php';

    /* set bo */
    GGnavi::getClsBO();
    $clsBO = ClsBO::getInstance();

    /* vars */
    $rslt = Common::getReturn();

    /* ============================ */
    /* process */
    /* ============================ */
    GGsql::autoCommitFalse();
    try
    {
        $rslt = $clsBO->updateByOption($options);
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
