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
        /* check teamname */
        if(($TEAMNAME == "당근" || $TEAMNAME == "아너스") == false)
            throw new GGexception("죄송합니다. 현재 필드테스트 중으로, 특정 팀만 사용이 가능합니다.");

        $rslt = $userBO->insertForInside($ID, $PW, $NAME, $BIRTHYEAR, $PHONE, $EMAIL, $ADRCVFLG, $HASCARFLG, $ADDRESS);
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
