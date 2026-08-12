<?php

    /* ============================ */
    /* init */
    /* ============================ */
    include '../../env/env.php';
    GGnavi::getGrpmtagbBO();

    /* vars */
    $grpmtagbBO = GrpmtagbBO::getInstance();
    $rslt = array();

    /* ============================ */
    /* process */
    /* ============================ */
    try
    {
        $rslt = $grpmtagbBO->selectByOption($options);
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
