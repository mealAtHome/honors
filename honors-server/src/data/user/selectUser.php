<?php

    /* ============================= */
    /* init */
    /* ============================= */
    include '../../env/env.php';

    /* vars */
    $userBO = UserBO::getInstance();

    /* ============================= */
    /* process */
    /* ============================= */
    try
    {
        $rslt = $userBO->selectByOption($options);
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
