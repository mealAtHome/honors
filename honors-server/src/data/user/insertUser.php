<?php


    /* ========================= */
    /* init */
    /* ========================= */
    include '../../env/env.php';
    GGnavi::getUserBO();

    /* vars */
    $userBO = UserBO::getInstance();

    /* init */
    $rslt = Common::getReturn();

    /* ========================= */
    /* process */
    /* ========================= */
    GGsql::autoCommitFalse();
    try
    {
        $rslt = $userBO->insertForInside($ID, $PW, $NAME, $BIRTHYEAR, $PHONE, $EMAIL, $ADRCVFLG, $HASCARFLG);
        GGsql::commit();
    }
    catch(GGexception $e)
    {
        $rslt = Common::returnError($e->getMessage(), $e);
    }
    catch(Error $e)
    {
        $rslt = Common::returnErrorObj($e);
    }

    /* --------------- */
    /* return result */
    /* --------------- */
    Common::returnRslt($rslt);

?>
