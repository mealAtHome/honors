<?php

class GGsql
{
    private static $GGsql = null;

    /* ========================= */
    /* common db 전용 */
    /* ========================= */
    static private function getConnection()
    {
        if(self::$GGsql == null)
            self::setConnectionAsNormal();
        return self::$GGsql;
    }

    static public function setConnectionAsNormal()
    {
        $mysqli = mysqli_connect(DB_IP, DB_USER, DB_PW, DB_NAME, DB_PORT);
        if (!$mysqli)
            throw new GGexception("연결에 실패하였습니다.");
        $mysqli->set_charset("utf8mb4");
        self::$GGsql = $mysqli;
    }

    static public function setConnectionAsInformationSchema()
    {
        $mysqli = mysqli_connect(DB_IP, DB_USER, DB_PW, "information_schema", DB_PORT);
        if (!$mysqli)
            throw new GGexception("연결에 실패하였습니다.");
        $mysqli->set_charset("utf8mb4");
        self::$GGsql = $mysqli;
    }

    /* ========================= */
    /* mysqli 전용 함수 모음 */
    /* ========================= */
    public static function realEscapeString($string)   { return self::getConnection()->real_escape_string($string); }
    public static function getInsertId()               { return self::getConnection()->insert_id; }
    public static function autoCommitFalse()           { self::getConnection()->autocommit(false); }
    public static function autoCommitTrue()            { self::getConnection()->autocommit(true); }
    public static function commit()                    { self::getConnection()->commit(); }
    public static function rollback()                  { self::getConnection()->rollback(); }
    public static function close()                     { self::getConnection()->close(); }

    /* ===================== */
    /* exeQuery + writelog + returnSelect */
    /* ===================== */
    public static function select($query, $from="", $options=[])
    {
        /* set result */
        $rslt = Common::getReturn();

        /* get pagenation */
        $PAGEFLG =        Common::get($options, "PAGEFLG", "n");
        $PAGENUM = intval(Common::get($options, "PAGENUM", 1));
        $PERPAGE = intval(Common::get($options, "PERPAGE", 50));
        $limit   = "";
        $cnt     = 0;
        if(isset($PAGEFLG) && Common::isPagenation($PAGEFLG) && $from != "")
        {
            /* set limit point */
            $startPoint = ($PAGENUM-1) * $PERPAGE;
            $limit = "limit $startPoint, $PERPAGE";

            $queryForCount =  "select count(*) cnt from $from";
            $cnt = GGsql::selectCnt($queryForCount);
        }

        /* make final query */
        $query = $query." ".$limit;

        /* execute query */
        $cnt = 0;
        $result = self::exeQuery($query);
        while($r = mysqli_fetch_assoc($result)) {
            $rslt[GGF::DATA][] = $r;
            $cnt++;
        }

        /* add result */
        $rslt['PAGEFLG'] = $PAGEFLG;
        $rslt['PAGENUM'] = $PAGENUM; /* 현재 페이지 번호 */
        $rslt['PERPAGE'] = intval($PERPAGE); /* 페이지 당 컨텐츠 수 */
        $rslt['ALLCNT']  = intval($cnt); /* 총 컨텐츠 수 */
        $rslt['PAGECNT'] = intval(ceil($rslt['ALLCNT'] / $PERPAGE)); /* 총 페이지 수 */
        $rslt['CNT']     = $cnt; /* 조회된 레코드 수 */

        /* return result */
        return $rslt;
    }

    /* ===================== */
    /* exeQuery + writelog + returnSelect */
    /* ===================== */
    public static function selectOnly($query)
    {
        /* set result */
        $rslt = Common::getReturn();

        /* execute query */
        $result = self::exeQuery($query);
        while($r = mysqli_fetch_assoc($result))
            $rslt[GGF::DATA][] = $r;

        /* return result */
        return $rslt;
    }

    public static function selectCnt($query)
    {
        $cnt = 0;
        $result = self::exeQuery($query);
        $record = mysqli_fetch_assoc($result);
        if(isset($record['cnt']))
            $cnt = intval($record['cnt']);
        return $cnt;
    }

    public static function selectOne($query)
    {
        $result = self::exeQuery($query);
        $record = mysqli_fetch_assoc($result);
        return $record;
    }

    /* =====================
    /* exeQuery + writelog */
    /* ===================== */
    public static function exeQuery($query)
    {
        /* get end date */
        $dateP = DateTime::createFromFormat('U', time());
        $dateP->setTimeZone(new DateTimeZone('Asia/Tokyo'));
        $executeTime = $dateP->format('Y-m-d H:i:s');
        $logDate     = $dateP->format('Ymd');

        /* log : make log */
        Common::logAll($query);

        /* ------------ */
        /* execute query */
        /* ------------ */
        $mysqli = self::getConnection();
        if (!$result = $mysqli->query($query))
        {
            Common::logErr($query);
            throw new GGexception("(server) server failed.");
        }
        return $result;
    }

    /* ========================= */
    /* 주어진 위/경도의 주변 반경 위/경도 in 쿼리문 반환 */
    /*
        반환형태는 다음과 같음
        return "(위도,경도),(위도,경도),(위도,경도),(위도,경도)";
     */
    /* ========================= */
    public static function makeInQueryByGPS($latiy, $longx, $scope=0.02)
    {
        $latiy = round($latiy, 2);
        $longx = round($longx, 2);

        $queryIn = "";
        $scopeY = $scope;
        $scopeX = $scope;
        $isFirst = true;
        for($i = $latiy - $scopeY; $i <= $latiy + $scopeY; $i += 0.01)
        {
            for($j = $longx - $scopeX; $j <= $longx + $scopeX; $j += 0.01)
            {
                if($isFirst == true)
                {
                    $queryIn .= "($i, $j)";
                    $isFirst = false;
                }
                $queryIn .= ",($i, $j)";
            }
        }
        return $queryIn;
    }

    /* ========================= */
    /* 주어진 데이터의 필드값을 in 쿼리문 반환 */
    /*
        반환형태는 다음과 같음
        return "'값','값','값','값'";
     */
    /* ========================= */
    static function makeInQuery($data, $field)
    {
        $rslt = "";
        foreach($data as $row)
        {
            $rslt .= "'".$row[$field]."',";
        }
        $rslt = substr($rslt, 0, -1);
        return $rslt;
    }

} /* end class */
?>