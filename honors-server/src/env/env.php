<?php

/* -------------- */
/* define const */
/* -------------- */

/* HEADER */
header('Access-Control-Allow-Origin: *');
header("Cache-Control: no-cache, must-revalidate");

/* SYSTEM INFO */
define("MAINTENANCE"  , false);
define("LANG"         , "kr");
define("SUCCEED"      , "S-0001"); /* API RESULT */
define("API_KEY"      , "racoonable"); /* 배치실행 키 */
define("VERSIONSV"    , "10010"); /* 버전정보 */
define("VERSIONDB"    , "10009"); /* 버전정보 */

/* DB info */
$documentRoot = $_SERVER['DOCUMENT_ROOT'];
$mode = isset($_POST['MODE']) ? $_POST['MODE'] : false;
if($mode == "true")
{
    /* docker */
    define("DB_IP"     , "db");
    define("DB_USER"   , "sadmin");
    define("DB_PW"     , "svwy@pf!");
    define("DB_NAME"   , "honors");
    define("DB_PORT"   , "3306");
    define("ROOT_RES"  , "$documentRoot/honors-res");
    define("ROOT"      , "$documentRoot/honors-server");
    define("LOG_ROOT"  , "$documentRoot/honors-server/src/.log");

    /* docker + server */
    // define("DB_IP"     , "host.docker.internal");
    // define("DB_USER"   , "sadmin");
    // define("DB_PW"     , "svwy@pf!");
    // define("DB_NAME"   , "honors".VERSIONDB);
    // define("DB_PORT"   , 3307);
    // define("ROOT_RES"  , "$documentRoot/honors-res");
    // define("ROOT"      , "$documentRoot/honors-server");
    // define("LOG_ROOT"  , "$documentRoot/honors-server/src/.log");
    /* ssh -f -N -L 3307:127.0.0.1:3306 -i C:\Users\ghen4\projects\_keys\yogimoim-production.pem ec2-user@yogimoim.com */
}
else
{
    define("DB_IP"     , "localhost");
    define("DB_USER"   , "sadmin");
    define("DB_PW"     , "svwy@pf!");
    define("DB_NAME"   , "honors".VERSIONDB);
    define("DB_PORT"   , 3306);
    define("ROOT_RES"  , "$documentRoot/honors-res");
    define("ROOT"      , "$documentRoot/honors-server-".VERSIONSV);
    define("LOG_ROOT"  , "/var/log/joon");
}

/* ===================== */
/* requries */
/* ===================== */

/* 공통함수 모임 */
require_once ROOT.'/src/system/common/common.php';
require_once ROOT.'/src/system/common/GGrslt.php';
require_once ROOT.'/src/system/common/GGauth.php';
require_once ROOT.'/src/system/common/GGdate.php';
require_once ROOT.'/src/system/common/GGsql.php';
require_once ROOT.'/src/system/common/GGvalid.php';
require_once ROOT.'/src/system/common/GGnavi.php';
require_once ROOT.'/src/system/common/GGF.php';
require_once ROOT.'/src/system/common/GGexception.php';

/* commonBO */
require_once ROOT.'/src/system/bo-table/_CommonBO.php';

/* 유저인증 */
require_once ROOT.'/src/system/bo-table/UserBO.php';

/* -------------- */
/* settings */
/* -------------- */
ini_set('max_execution_time', '60');
set_time_limit(60);

/* -------------- */
/* check system status */
/* -------------- */
GGnavi::getSystemStatusBO();
$systemStatusBO = SystemStatusBO::getInstance();

/* hard maintenance */
if(MAINTENANCE == true)
    Common::returnCode("maintenance", "시스템 정비중입니다.");

/* version */
$versionClientOrigin = isset($_POST['VERSION']) ? $_POST['VERSION'] : "0";
$versionClient = intval($versionClientOrigin);
$versionServer = intval(VERSIONSV);
if($versionClient < $versionServer)
    Common::returnCode("version", "버전이 맞지 않습니다. 최신버전으로 업데이트 해주세요.");

/* -------------- */
/* confirm apikey */
/* -------------- */
$EXECUTOR   = null;
$uriArr     = explode("/",$_SERVER['REQUEST_URI']);
$uri        = $uriArr[count($uriArr)-1];
$uriHypen   = explode("-",$uri);
$isBatch    = isset($uriHypen[0]) && $uriHypen[0] == "per" ? true : false;

/* check key if batch */
if($isBatch)
{
    $KEY = $_POST['key'];
    if($KEY != API_KEY)
        Common::returnCode("A-0002", "auth failed");
}
else
{
    switch($uri)
    {
        case 'login.php':
        case 'insertUser.php':
        case 'deleteUserInfo.php':
        case 'select_refHoliday.php':
        {
            /* no check */
            break;
        }
        default:
        {
            $userBO = UserBO::getInstance();
            if(isset($_POST['APIKEY']) == false)
            {
                Common::returnError("(server)user is not valid please login again");
                return;
            }
            $apikey = $_POST['APIKEY'];
            $EXECUTOR = $userBO->selectUsernoByApikeyForInside($apikey);
            break;
        }
    }
}

/* -------------- */
/* check system status (maintenance) */
/* -------------- */
// $systemStatusBO->checkMaintenance();

/* 리퀘스트 입구 */
require_once ROOT.'/src/env/post.php';
require_once ROOT.'/src/env/postGlobals.php';

?>
