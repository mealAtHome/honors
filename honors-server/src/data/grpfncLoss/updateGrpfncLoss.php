<?php

    /* ============================ */
    /* init */
    /* ============================ */
    include '../../env/env.php';
    GGnavi::getGrpfncLossBO();

    /* vars */
    $grpfncLossBO = GrpfncLossBO::getInstance();
    $rslt = Common::getReturn();

    /* ============================ */
    /* process */
    /* ============================ */
    GGsql::autoCommitFalse();
    try
    {
        $data = $grpfncLossBO->updateByOption($options);
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
