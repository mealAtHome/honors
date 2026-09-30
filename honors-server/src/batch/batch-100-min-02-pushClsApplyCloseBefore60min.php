<?php

    /* ========================= */
    /* init */
    /* ========================= */
    include '../env/env.php';

    /* filename */
    $filename = basename(__FILE__, ".php");

    /* BO */
    GGnavi::getSystemBatchBO();
    GGnavi::getSystemPushBO();
    GGnavi::getGrpBO();
    GGnavi::getClsBO();
    $systemBatchBO = SystemBatchBO::getInstance();
    $systemPushBO = SystemPushBO::getInstance();
    $grpBO = GrpBO::getInstance();
    $clsBO = ClsBO::getInstance();

    /* lock start */
    $lockResult = $systemBatchBO->lock($filename);
    if(!$lockResult)
        Common::returnLockFailed();

    /* vars */
    $rslt = Common::getReturn();

    /* ========================= */
    /* process */
    /* ========================= */
    try
    {
        /* 프리필드에 기록된 시간을 기준으로한다. (레코드가 없거나 프리필드가 비어있다면 현재시간을 기록 후, 종료) */
        $systemBatchModel = $systemBatchBO->getByPk($filename);
        $freefield = Common::get($systemBatchModel, SystemBatchBO::FIELD__FREEFIELD);
        if(empty($freefield))
        {
            $systemBatchBO->updateFreefieldForInside($filename, date("Y-m-d H:i:s", time()));
            $systemBatchBO->unlock($filename);
            Common::returnRslt($rslt);
        }
        $lastprocTime = strtotime($freefield);
        $thisprocTime = time();

        /* YYYY-MM-DD HH:MM:SS 형식으로 현재 시간을 저장 */
        $lastprocTimeFormatted = date("Y-m-d H:i:s", $lastprocTime + (60 * 60));
        $thisprocTimeFormatted = date("Y-m-d H:i:s", $thisprocTime + (60 * 60));

        /* 유효한 grpno 를 루프 */
        $grpList = Common::getData($grpBO->selectActiveAllForInside());
        foreach($grpList as $grp)
        {
            $grpno = Common::get($grp, GrpBO::FIELD__GRPNO);

            /* 두 시간을 기준으로 각각 60분 뒤에 기명이 마감되는 클래스를 조회 */
            $clsList = Common::getData($clsBO->selectApplyCloseBetweenByGrpnoForInside($grpno, $lastprocTimeFormatted, $thisprocTimeFormatted));
            foreach($clsList as $cls)
                $systemPushBO->insertPushtypeClsApplyCloseBefore60MinForInside($grpno, $cls);
        }

        /* 마지막으로 처리한 시간을 현재 시간으로 업데이트 */
        $systemBatchBO->updateFreefieldForInside($filename, date("Y-m-d H:i:s", $thisprocTime));
    }
    catch(Throwable $e)
    {
        Common::returnError("error", $e);
    }
    finally
    {
        /* unlock */
        $systemBatchBO->unlock($filename);
    }
    Common::returnRslt($rslt);

?>
