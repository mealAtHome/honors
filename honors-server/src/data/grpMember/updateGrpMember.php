<?php

    /* ============================ */
    /* init */
    /* ============================ */
    include '../../env/env.php';

    /* set bo */
    GGnavi::getGrpMemberBO();
    $grpMemberBO = GrpMemberBO::getInstance();

    /* vars */
    $rslt = Common::getReturn();

    /* ============================ */
    /* process */
    /* ============================ */
    GGsql::autoCommitFalse();
    try
    {
        $rslt = $grpMemberBO->updateByOption($options);
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
