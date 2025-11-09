<?php

/* ========================= */
/* 공통 클래스의 집합 */
/* ========================= */
class Common
{
    const SUCCEED             = "S-0001";
    const FAILED              = "FAILED";
    const DB_FAILED           = "DB-0002";
    const COMMIT_FAILED       = "DB-0003";
    const UNDEXPECTED_ERROR   = "unexpected error";

    /* ========================= */
    /* 리퀘스트 정보 */
    /* ========================= */
    static function getServiceLayer() { return isset($GLOBALS['SERVICE_LAYER']) ? $GLOBALS['SERVICE_LAYER'] : null; }

    /* ========================= */
    /* 기본 반납형 */
    /* ========================= */
    static function getReturn($code=Common::SUCCEED, $count=0, $data=array())
    {
        $rslt = array();
        $rslt[GGF::CODE]  = $code;
        $rslt["COUNT"] = $count;
        $rslt["DATA"]  = $data;
        return $rslt;
    }

    /* ===================== */
    /* ip 습득 */
    /* ===================== */
    static function getIP()
    {
        $ip = "";
        if     (!empty($_SERVER['HTTP_CLIENT_IP']))       $ip = $_SERVER['HTTP_CLIENT_IP'];
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else                                              $ip = $_SERVER['REMOTE_ADDR'];
        return $ip;
    }

    /* ========================= */
    /* 리턴 */
    /* ========================= */
    static function returnCode($code="error", $msg="")
    {
        $rslt = array();
        $rslt[GGF::CODE] = $code;
        $rslt[GGF::MSG]  = $msg;
        $rslt[GGF::DATA] = array();
        Common::returnRslt($rslt);
    }
    static function returnRslt($rslt)
    {
        /* print header */
        header('Content-Type: application/json; charset=utf-8;');

        /* close mysqli */
        GGsql::close();

        /* return result */
        echo json_encode($rslt);
        exit;
    }

    /* ===================== */
    /* error log for debug */
    /* ===================== */
    static function returnErrorObj($e) { return Common::returnError("", $e); }
    static function returnError($msg="", $e=null)
    {
        /* ----- */
        /* get end date */
        /* ----- */
        $date        = GGdate::now();
        $executeTime = GGdate::format($date, GGdate::DATEFORMAT__YYYYMMDDHHIISS);
        $logDate     = GGdate::format($date, GGdate::DATEFORMAT__YYYYMMDD_NOHYPHEN);

        /* ----- */
        /* set exception log */
        /* ----- */
        if($e == null)
            $e = new Exception($msg);
        $content  = "";
        $content .= $e->getMessage()."\n";
        $content .= $e->getTraceAsString();
        self::logExp($content);

        /* ----- */
        /* return error */
        /* ----- */
        GGsql::rollback();

        /* validate $msg */
        if($msg == null || $msg == "")
            $msg = $e->getMessage();

        /* print error */
        $rslt = array();
        $rslt[GGF::CODE] = "NG";
        $rslt[GGF::MSG] = $msg;
        $rslt[GGF::DATA] = array();
        return $rslt;
    }

    /* ===================== */
    /* logging */
    /* ===================== */
    static function logException($e)
    {
        $content  = "";
        $content .= $e->getMessage()."\n";
        $content .= $e->getTraceAsString();
        self::logExp($content);
    }

    static function logDebug      ($log) { self::logging("debug", $log); }
    static function logWarn       ($log) { self::logging("warn", $log); }
    static function logErr        ($log) { self::logging("err", $log); }
    static function logExp        ($log) { self::logging("exp", $log); }
    static function logAll        ($log) { self::logging("all", $log); }
    static function logging($dir, $log)
    {
        /* get end date */
        $date        = GGdate::now();
        $executeTime = GGdate::format($date, GGdate::DATEFORMAT__YYYYMMDDHHIISSU);
        $logDate     = GGdate::format($date, GGdate::DATEFORMAT__YYYYMMDD_NOHYPHEN);

        /* set batch log */
        $logRootFinal = self::getGlobals("LOG_ROOT_FINAL");

        /* set content */
        $content = $executeTime." ".$log."\n";

        /* ===== */
        /* logging to normal */
        /* ===== */
        if($logRootFinal == null)
        {
            /* confirm dir is alive */
            if(!is_dir(LOG_ROOT."/logs"))
                mkdir(LOG_ROOT."/logs", 0777, true);
            file_put_contents(LOG_ROOT."/logs/$logDate-$dir.log", $content, FILE_APPEND | LOCK_EX);
        }

        /* ===== */
        /* logging to final path */
        /* ===== */
        if($logRootFinal != null)
        {
            /* confirm dir is exists */
            if(!is_dir($logRootFinal))
                mkdir($logRootFinal, 0777, true);
            file_put_contents($logRootFinal."/$logDate-$dir.log", $content."\n", FILE_APPEND | LOCK_EX);
        }

        /* log appended */
        if(!is_dir(LOG_ROOT."/_last"))
            mkdir(LOG_ROOT."/_last", 0777, true);
        file_put_contents(LOG_ROOT."/_last/$dir.log", $content, FILE_APPEND | LOCK_EX);
    }

    /* ===================== */
    /* 주어진 길이의 랜덤 문자열을 반환한다. */
    /* ===================== */
    static function getRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++)
        {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    /* ===================== */
    /* API 에서 페이지네이션을 설정할 것인지 문자열 'y' 일 때 true 반환 */
    /* ===================== */
    static function isPagenation($pagenation)
    {
        $rslt = false;
        if($pagenation == 'y')
            $rslt = true;
        return $rslt;
    }

    /* ===================== */
    /*  */
    /* ===================== */
    static function isNotEmpty($str) { return !self::isEmpty($str); }
    static function isEmpty($str)
    {
        if($str == null || trim($str) == "")
            return true;
        return false;
    }

    /* ===================== */
    /* select 결과만 리턴 */
    /* ===================== */
    static function getData    ($selectResult)                  { return isset($selectResult[GGF::DATA])    ? $selectResult[GGF::DATA]     : []; }
    static function getDataOne ($selectResult)                  { return isset($selectResult[GGF::DATA][0]) ? $selectResult[GGF::DATA][0]  : null; }
    static function get        ($options, $field, $ifnull=null) { return isset($options[$field])            ? $options[$field]             : $ifnull; }
    static function getField   ($options, $field, $ifnull=null) { return isset($options[$field])            ? $options[$field]             : $ifnull; }
    static function setField   ($options, $field, $value)       { return $options[$field] = $value; }

    static function getDataOneField($selectResult, $fieldName)
    {
        /* get row */
        $row = null;
        if(isset($selectResult[GGF::DATA][0]))
            $row = $selectResult[GGF::DATA][0];

        /* get field */
        $result = null;
        if(isset($row[$fieldName]))
            $result = $row[$fieldName];
        return $result;
    }

    static function getPost($field, $ifnull="")
    {
        $rslt = "";
        if(isset($_POST[$field]))
            $rslt = $_POST[$field];
        else
            $rslt = $ifnull;
        return $rslt;
    }

    static function getGlobals($field, $ifnull="")
    {
        $rslt = "";
        if(isset($GLOBALS[$field]))
            $rslt = $GLOBALS[$field];
        else
            $rslt = $ifnull;
        return $rslt;
    }

} /* end class */
?>