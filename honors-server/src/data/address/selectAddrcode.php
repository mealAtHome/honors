<?php

    /* ============================ */
    /* init */
    /* ============================ */
    include '../../env/env.php';
    GGnavi::getAddrcodeBO();

    /* vars */
    $addrcodeBO = AddrcodeBO::getInstance();
    $rslt = Common::getReturn();

    /* ============================ */
    /* process */
    /* ============================ */
    try
    {
        $rslt = $addrcodeBO->selectByOption($options);
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
