<?php

    /* 이미지 업로드 */

    /* ========================= */
    /* init */
    /* ========================= */
    include '../../env/env.php';
    GGnavi::getImageUtils();

    /* vars */
    $rslt = Common::getReturn();

    /* get file */
    $FILE = isset($_FILES["file"]["tmp_name"]) ? $_FILES["file"]["tmp_name"] : "";

    /* ========================= */
    /* process */
    /* ========================= */

    /* 이미지 세팅 */
    ImageUtils::setImgByOption($OPTION, $INDEX1, $INDEX2, $FILE);

    /* 결과값 리턴 */
    Common::returnRslt($rslt);

?>
