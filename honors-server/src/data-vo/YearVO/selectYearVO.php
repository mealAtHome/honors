<?php

    /**
     * 특정 테이블에 대한 연도를 그룹화하여 리턴
     */

    /* ============================ */
    /* init */
    /* ============================ */
    include '../../env/env.php';
    GGnavi::getYearVO();

    /* get BO */
    $yearVO = YearVO::getInstance();

    /* return */
    $rslt = Common::getReturn();

    /* ============================ */
    /* process */
    /* ============================ */
    try
    {
        $rslt = $yearVO->selectByOption($options);
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