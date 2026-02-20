<?php

    /* ============================ */
    /* init */
    /* ============================ */
    include '../../env/env.php';
    GGnavi::getGrpfncSponsorshipBO();

    /* vars */
    $grpfncSponsorshipBO = GrpfncSponsorshipBO::getInstance();
    $rslt = array();

    /* ============================ */
    /* process */
    /* ============================ */
    try
    {
        $rslt = $grpfncSponsorshipBO->selectByOption($options);
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