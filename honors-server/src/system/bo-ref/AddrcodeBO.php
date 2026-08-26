<?php

/* addrcode, _addrcode : 법정동코드 마스터 */
class AddrcodeBO extends _CommonBO
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
    function setBO()
    {
        $arr = array();
        $arr['ggAuth'] = GGauth::getInstance();
        return $arr;
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    const FIELD__ADDRCODE    = "addrcode";    /* (pk) bigint    */
    const FIELD__ADDRSTRFULL = "addrstrfull"; /* (  ) varchar(100) */
    const FIELD__ADDRDEPTH   = "addrdepth";   /* (  ) int       */

    static public function getConsts()
    {
        $arr = array();
        return $arr;
    }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByPkForInside($ADDRCODE) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /* ========================= */
    const selectByPkForInside = "selectByPkForInside";
    const searchByKeyword = "searchByKeyword"; /* 지역검색(타이핑 중 검색) */
    protected function select($options, $option="")
    {
        /* vars */
        $ggAuth = GGauth::getInstance();
        extract(self::getConsts());
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* --------------- */
        /* sql body */
        /* --------------- */
        $query  = "";
        $select = "";
        $from   = "";
        $select =
        "
              t.addrcode
            , t.addrstrfull
            , t.addrdepth
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPkForInside : { $from = "(select * from _addrcode where addrcode = $ADDRCODE) t"; break; }
            case self::searchByKeyword :
            {
                if(Common::isEmpty($KEYWORD))
                    throw new GGexception("검색어를 입력해주세요.");
                $keyword = GGsql::realEscapeString(trim($KEYWORD));
                $from = "(select * from _addrcode where addrstrfull like '%$keyword%' order by addrdepth asc, addrstrfull asc limit 20) t";
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }

        /* --------------- */
        /* exe query */
        /* --------------- */
        $query =
        "
            select
                $select
            from
                $from
            order by
                t.addrdepth asc, t.addrstrfull asc
        ";
        $rslt = GGsql::select($query, $from, $options, $OPTION);
        return $rslt;
    }

}
?>
