<?php

/* ========================= */
/* 위도 경도를 구하는 클래스 */
/* ========================= */

/* =============== */
/* require pro4j */
/* =============== */
// $path = ROOT.'/lib/proj4php-master/src/defs';
// $a = scandir($path);
// foreach ($a as $f)
// {
//     $explode = explode(".", $f);
//     if(end($explode) == "php")
//         require_once("$path/$f");
// }
require_once ROOT.'/lib/proj4php-master/src/defs/EPSG5179.php';
require_once ROOT.'/lib/proj4php-master/src/defs/EPSG4326.php';

$path = ROOT.'/lib/proj4php-master/src/projCode';
$a = scandir($path);
foreach ($a as $f)
{
    $explode = explode(".", $f);
    if(end($explode) == "php")
        require_once("$path/$f");
}
require_once ROOT.'/lib/proj4php-master/src/Common.php';
require_once ROOT.'/lib/proj4php-master/src/Datum.php';
require_once ROOT.'/lib/proj4php-master/src/LongLat.php';
require_once ROOT.'/lib/proj4php-master/src/Point.php';
require_once ROOT.'/lib/proj4php-master/src/Proj.php';
require_once ROOT.'/lib/proj4php-master/src/Wkt.php';
require_once ROOT.'/lib/proj4php-master/src/Proj4php.php';

use proj4php\Proj4php;
use proj4php\Proj;
use proj4php\Point;

class GGcoordinate
{
    /* ----- */
    /* singleton */
    /* ----- */
    private static $bo;
    public static function getInstance()
    {
        if(self::$bo == null)
            self::$bo = new static();
        return self::$bo;
    }

    /* ========================= */
    /* 주소검색 */
    /* ========================= */
    public function searchAddress($searchKeyword, $pagenum)
    {
        $rslt = Common::getReturn();

        /* =============== */
        /* validate parameter */
        /* =============== */

        /* ----- */
        /* searchKeyword */
        /* ----- */
        if(strlen($searchKeyword) >= 2)
        {
            /* 특수문자 제거 */
            $expText = "/[%=><'\"]/";
            if(preg_match($expText, $searchKeyword) != false)
            {
                /* 프론트에서 제어하고 있으므로 상세히 알리지 않음 */
                /* 특수문자를 입력 할수 없습니다. */
                throw new GGexception("(server) invalid input");
            }

            /* 특정문자열(sql예약어의 앞뒤공백포함) 제거 */
            $sqlArray = array("OR","SELECT","INSERT","DELETE","UPDATE","CREATE","DROP","EXEC","UNION","FETCH","DECLARE","TRUNCATE");
            for($i = 0; $i < count($sqlArray); $i++)
            {
                $sqlWord = $sqlArray[$i];
                if(strpos($searchKeyword, $sqlWord) !== false)
                {
                    /* 프론트에서 제어하고 있으므로 상세히 알리지 않음 */
                    /* "\"".$sqlWord."\"와(과) 같은 특정문자로 검색할 수 없습니다." */
                    throw new GGexception("(server) invalid input");
                }
            }
        }
        else
        {
            /* 프론트에서 제어하고 있으므로 상세히 알리지 않음 */
            /* 주소값이 없습니다. */
            throw new GGexception("(server) invalid input");
        }

        /* ----- */
        /* currentPage */
        /* ----- */
        if(is_numeric($pagenum) != true)
        {
            /* 프론트에서 제어하고 있으므로 상세히 알리지 않음 */
            /* 현재 페이지 값이 숫자가 아님 */
            throw new GGexception("(server) invalid input");
        }

        /* =============== */
        /* curl */
        /* =============== */
        /* get pagenation */
        $PERPAGE = intval(Common::get($_POST, "PERPAGE", 50));

        /* curl */
        $url = "https://www.juso.go.kr/addrlink/addrLinkApiJsonp.do";
        $data = array(
            "confmKey"     => "U01TX0FVVEgyMDIzMDMyNTE2MzI1MDExMzYyNjI=",
            "resultType"   => "json",
            "countPerPage" => $PERPAGE,
            "currentPage"  => $pagenum,
            "keyword"      => $searchKeyword,
        );
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $resultA = curl_exec($curl);
        $resultB = trim($resultA, '()');
        $resultX = json_decode($resultB, true);
        curl_close($curl);

        $juso       = isset($resultX['results']['juso'])     ? $resultX['results']['juso']    : [];
        $common     = isset($resultX['results']['common'])   ? $resultX['results']['common']  : [];
        $totalCount = isset($common['totalCount'])           ? $common['totalCount']          : [];
        $errorCode  = isset($common['errorCode'])            ? $common['errorCode']           : [];

        $rslt[GGF::DATA]    = $juso;
        $rslt['ERROR']   = $errorCode;
        $rslt['PAGEFLG'] = GGF::Y;
        $rslt['PAGENUM'] = intval($pagenum); /* 현재 페이지 번호 */
        $rslt['PERPAGE'] = intval($PERPAGE); /* 페이지 당 컨텐츠 수 */
        $rslt['ALLCNT']  = intval($totalCount); /* 총 컨텐츠 수 */
        $rslt['PAGECNT'] = intval(ceil($rslt['ALLCNT'] / $PERPAGE)); /* 총 페이지 수 */
        return $rslt;
    }

    /* ========================= */
    /* 좌표습득 */
    /* ========================= */
    function getCoordinate(
        $ADDR_ADMCD,
        $ADDR_RNMGTSN,
        $ADDR_UDRTYN,
        $ADDR_BULDMNNM,
        $ADDR_BULDSLNO
    )
    {
        /* =============== */
        /* 좌표습득 */
        /* =============== */
        $url = 'https://business.juso.go.kr/addrlink/addrCoordApi.do';
        $data = array(
            "confmKey"   => "U01TX0FVVEgyMDIzMDMyNTE4NTUwMjExMzYyNjQ=",
            "admCd"      => "$ADDR_ADMCD",
            "rnMgtSn"    => "$ADDR_RNMGTSN",
            "udrtYn"     => "$ADDR_UDRTYN",
            "buldMnnm"   =>  $ADDR_BULDMNNM,
            "buldSlno"   =>  $ADDR_BULDSLNO,
            "resultType" => "json",
        );
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        $korAddrJson = curl_exec($curl);
        curl_close($curl);

        /* return example */
        /*
            {
                "results":
                {
                    "common":
                    {
                        "errorMessage": "정상",
                        "totalCount": "1",
                        "errorCode": "0"
                    },
                    "juso":
                    [
                        {
                            "buldMnnm": "23",
                            "rnMgtSn": "471304715342",
                            "bdNm": "",
                            "entX": "1159575.7290500598",
                            "entY": "1768246.1872305921",
                            "admCd": "4713038025",
                            "bdMgtSn": "4713038025104120007000001",
                            "buldSlno": "59",
                            "udrtYn": "0"
                        }
                    ]
                }
            }
        */

        /* =============== */
        /* GRS80 to latitude,longitute */
        /* =============== */
        $ADDR_GRS80X = 0;
        $ADDR_GRS80Y = 0;
        $ADDR_LONGX  = 0;
        $ADDR_LATIY  = 0;

        /* --------------- */
        /* get entX, entY */
        /* --------------- */
        $korAddr = json_decode($korAddrJson, true);
        if(
            !isset($korAddr['results']['juso'][0]['entX']) ||
            !isset($korAddr['results']['juso'][0]['entY'])
        )
        {
            throw new GGexception("no result");
        }
        $ADDR_GRS80X = $entX = $korAddr['results']['juso'][0]['entX'];
        $ADDR_GRS80Y = $entY = $korAddr['results']['juso'][0]['entY'];

        /* --------------- */
        /* get latitude, longitude */
        /* --------------- */

        // Initialise Proj4
        $proj4 = new Proj4php();

        // Create two different projections.
        $projGRS80  = new Proj('EPSG:5179', $proj4);
        $projWGS84  = new Proj('EPSG:4326', $proj4);

        // Create a point.
        $pointSrc = new Point($entX, $entY, $projGRS80);
        // echo "Source: " . $pointSrc->toShortString() . " in L93 <br>";

        // Transform the point between datums.
        $pointDest = $proj4->transform($projWGS84, $pointSrc);
        // echo "Conversion: " . $pointDest->toShortString() . " in WGS84<br><br>";

        $ADDR_LONGX = $pointDest->x;
        $ADDR_LATIY = $pointDest->y;

        /* --------------- */
        /* return result */
        /* --------------- */
        $result['ADDR_GRS80X']  = $ADDR_GRS80X;
        $result['ADDR_GRS80Y']  = $ADDR_GRS80Y;
        $result['ADDR_LONGX']   = $ADDR_LONGX;
        $result['ADDR_LATIY']   = $ADDR_LATIY;
        return $result;
    }

}
?>