<?php

    /* ============================ */
    /* init */
    /* ============================ */
    include '../../env/env.php';
    GGnavi::getGrpJoinreqBO();

    /* vars */
    $grpJoinreqBO = GrpJoinreqBO::getInstance();
    $rslt = Common::getReturn();

    /* ============================ */
    /* process */
    /* ============================ */
    GGsql::autoCommitFalse();
    try
    {
        $data = $grpJoinreqBO->updateByOption($options);
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
