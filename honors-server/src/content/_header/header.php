<?php

function getParam($param, $ifnull="")
{
    /* get / post */
    $result = "";
    if(isset($_GET[$param]))
        $result = $_GET[$param];
    else if(isset($_POST[$param]))
        $result = $_POST[$param];

    /* if param null */
    if($result == "")
        $result = $ifnull;

    /* print */
    if(is_numeric($result))
        echo $result;
    else
        echo "'".$result."'";
}

function returnParam($param, $ifnull="")
{
    /* get / post */
    $result = "";
    if(isset($_GET[$param]))
        $result = $_GET[$param];
    else if(isset($_POST[$param]))
        $result = $_POST[$param];

    /* if param null */
    if($result == "")
        $result = $ifnull;

    /* print */
    return $result;
}

if(returnParam("viewMode", "") != "dialog")
{
    $scriptVersion=1;
    $scriptServer="http://localhost:8084/honors-script";
    echo
    "
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>
        <meta http-equiv='Content-Security-Policy'>

        <title>집집밥밥</title>

        <script src='$scriptServer/lib/jquery-3.3.1.min.js'></script>
        <script src='$scriptServer/lib/i18n/src/jquery.i18n.js'></script>
        <script src='$scriptServer/lib/i18n/src/jquery.i18n.messagestore.js'></script>
        <script src='$scriptServer/lib/i18n/src/jquery.i18n.fallbacks.js'></script>
        <script src='$scriptServer/lib/i18n/src/jquery.i18n.parser.js'></script>
        <script src='$scriptServer/lib/i18n/src/jquery.i18n.emitter.js'></script>
        <script src='$scriptServer/lib/i18n/src/jquery.i18n.language.js'></script>

        <link rel='stylesheet' type='text/css' href='$scriptServer/lib/jquery-toast-plugin-master/src/jquery.toast.css'/>
        <script src='$scriptServer/lib/jquery-toast-plugin-master/src/jquery.toast.js'></script>

        <link rel='stylesheet' type='text/css' href='$scriptServer/lib/jquery-confirm-master/css/jquery-confirm.css'/>
        <script src='$scriptServer/lib/jquery-confirm-master/js/jquery-confirm.js'></script>

        <script src='$scriptServer/source/common/script/base/GGF.js?$scriptVersion'></script>
        <script src='$scriptServer/source/common/script/base/serverinfo.js?$scriptVersion'></script>
        <script src='$scriptServer/source/common/script/base/gg-storage.js?$scriptVersion'></script>
        <script src='$scriptServer/source/common/script/base/gg-base.js?$scriptVersion'></script>
        <script src='$scriptServer/source/pc/script/model/model-system/MFrame.js?$scriptVersion'></script>
    ";
}

?>