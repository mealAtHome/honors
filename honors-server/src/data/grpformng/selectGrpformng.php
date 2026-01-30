<?php

    /* ============================ */
    /* init */
    /* ============================ */
    include '../../env/env.php';
    GGnavi::getGrpformngBO();

    /* vars */
    $grpformngBO = GrpformngBO::getInstance();
    $rslt = array();

    /* ============================ */
    /* process */
    /* ============================ */
    try
    {
        $rslt = $grpformngBO->selectByOption($options);
    }
    catch(GGexception $e)
    {
        $rslt = Common::returnError($e->getMessage(), $e);
    }
    catch(Error $e)
    {
        $rslt = Common::returnErrorObj($e);
    }

    /* print rslt */
    Common::returnRslt($rslt);

?>