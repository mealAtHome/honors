<?php

class ImageUtils
{
    public function __construct() {

    }

    /* ===================== */
    /* res 이미지폴더 세팅 */
    /* ===================== */
    static function setImgDir()
    {
        shell_exec("mkdir -p ".ROOT_RES."/user");
        shell_exec("mkdir -p ".ROOT_RES."/store");
    }

    /* ===================== */
    /*
        res 폴더에 이미지 세팅
    */
    /* ===================== */
    static public function setImgUser ($USERNO, $img="") { return self::setImgByOption("user", $USERNO, null, $img); }
    static public function setImgByOption($entity, $index1=null, $index2=null, $img=null)
    {
        /* 폴더 생성 */
        self::setImgDir();

        /* --------------- */
        /* validation */
        /* --------------- */
        /* index1 is null */

        /* --------------- */
        /* set vars by entity */
        /* --------------- */
        $ROOT_RES = ROOT_RES;
        $defaultImg = "";
        switch($entity)
        {
            case "user" : $defaultImg = ROOT."/src/z-res/_system/default.png"; break;
            default :
                return "";
        }

        /* --------------- */
        /* confirm random string is unique */
        /* --------------- */
        /* set default img && update table */
        do
        {
            $imgName        = Common::getRandomString(10);
            $dirName        = $ROOT_RES."/".$entity."/".$index1;
            $dirName        = $index2 != null ? $dirName."/".$index2 : $dirName; /* if index2 exists */
            $newfile        = $dirName."/".$imgName.".png";
            $newfileOrigin  = $dirName."/".$imgName."-origin.png";
        }
        while(file_exists($newfile));

        /* --------------- */
        /* 이미지 생성 */
        /* --------------- */
        /* 유저용 폴더 생성 */
        shell_exec("mkdir -p ".$dirName);
        if($img == null)
        {
            copy($defaultImg, $newfile);
            copy($defaultImg, $newfileOrigin);
        }
        else
        {
            move_uploaded_file($img, $newfileOrigin);

            /* 파일크기 축소 */
            $resizePx = 100;
            $beforeResize = imagecreatefromjpeg($newfileOrigin);
            $afterResize  = imagecreatetruecolor($resizePx, $resizePx);
            imagecopyresampled($afterResize, $beforeResize, 0, 0, 0, 0, $resizePx, $resizePx, imagesx($beforeResize), imagesy($beforeResize));
            imagejpeg($afterResize, $newfile);
        }

        /* --------------- */
        /* 이미지 경로 업로드 */
        /* --------------- */
        $queryForUpdate = "";
        switch($entity)
        {
            case "user" : $queryForUpdate = "update user set img = '$imgName', modidt = now() where userno = '$index1'"; break;
        }
        $result = GGsql::exeQuery($queryForUpdate);
        return $imgName;
    } /* setImg */



}

?>