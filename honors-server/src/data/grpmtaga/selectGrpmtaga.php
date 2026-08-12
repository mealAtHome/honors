<?php

    /* ============================ */
    /* init */
    /* ============================ */
    include '../../env/env.php';
    GGnavi::getGrpmtagaBO();

    /* vars */
    $grpmtagaBO = GrpmtagaBO::getInstance();
    $rslt = array();

    /* ============================ */
    /* process */
    /* ============================ */
    try
    {
        $rslt = $grpmtagaBO->selectByOption($options);
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
