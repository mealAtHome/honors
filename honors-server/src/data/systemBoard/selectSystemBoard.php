<?php

    /* ============================ */
    /* init */
    /* ============================ */
    include '../../env/env.php';
    GGnavi::getSystemBoardBO();

    /* vars */
    $systemBoardBO = SystemBoardBO::getInstance();

    /* ============================ */
    /* process */
    /* ============================ */
    try
    {
        $rslt = $systemBoardBO->selectByOption($options);
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
