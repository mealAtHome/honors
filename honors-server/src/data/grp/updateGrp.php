<?php

    /* ============================ */
    /* init */
    /* ============================ */
    include '../../env/env.php';
    GGnavi::getGrpBO();

    /* vars */
    $grpBO = GrpBO::getInstance();
    $rslt = Common::getReturn();

    /* ============================ */
    /* process */
    /* ============================ */
    GGsql::autoCommitFalse();
    try
    {
        $data = $grpBO->updateByOption($options);
        $rslt[GGF::DATA] = $data;
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
