<?php

    /* ========================= */
    /* init */
    /* ========================= */
    include '../../env/env.php';

    /* BO */
    GGnavi::getUserBO();
    GGnavi::getGrpBO();

    $userBO = UserBO::getInstance();
    $grpBO = GrpBO::getInstance();

    /* vars */
    $rslt = Common::getReturn();

    /* ========================= */
    /* process */
    /* ========================= */
    $userno  = "";
    $apikey = "";
    $storeno = "";
    $riderno = "";

    GGsql::autoCommitFalse();
    try
    {
        $query = "";

        /* get user */
        $user = $userBO->getUserById($ID);
        if($user == null)
            throw new GGexception("아이디와 비밀번호를 확인해주세요.");

        /* get field */
        $pw     = Common::getField($user, UserBO::FIELD__PW);
        $userno = Common::getField($user, UserBO::FIELD__USERNO);

        /* check pw */
        if(password_verify($PW, $pw) == false)
            throw new GGexception("아이디와 비밀번호를 확인해주세요.");

        /* check is delete */
        $deletedataRqstdt = Common::getField($user, UserBO::FIELD__DELETEDATA_RQSTDT);
        if($deletedataRqstdt != null)
            throw new GGexception("탈퇴한 계정입니다.");

        /* ---------------------- */
        /* 2. update user device info / autologin key */
        /* ---------------------- */

        /* update user device info / autologin key */
        $userBO->updateDeviceInfoByInside($userno, $PLATFORM, $TOKEN);

        /* update user device info / autologin key */
        $apikey = $userBO->generateApikey($userno);
    }
    catch(GGexception $e)
    {
        $rslt = Common::returnError($e->getMessage(), $e);
    }
    catch(Error $e)
    {
        $rslt = Common::returnErrorObj($e);
    }

    /* return result */
    $rslt[GGF::ID]     = $ID;
    $rslt[GGF::USERNO] = $userno;
    $rslt[GGF::APIKEY] = $apikey;
    Common::returnRslt($rslt);

?>