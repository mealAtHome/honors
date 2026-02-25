<?php

    /* ============================ */
    /* init */
    /* ============================ */
    include '../../env/env.php';
    GGnavi::getGrpfnclogBO();

    /* vars */
    $grpfnclogBO = GrpfnclogBO::getInstance();
    $rslt = array();

    /* ============================ */
    /* process */
    /* ============================ */
    try
    {
        $rslt = $grpfnclogBO->selectByOption($options);
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