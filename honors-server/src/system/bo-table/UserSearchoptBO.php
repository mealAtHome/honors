<?php

class UserSearchoptBO extends _CommonBO
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
    function __construct() {
    }

    /* ========================= */
    /* field */
    /*
    */
    /* ========================= */
    const FIELD__USERNO          = "userno";          /* (  ) char(30) not null */
    const FIELD__SEARCHOPT       = "searchopt";       /* (  ) char(30) not null */
    const FIELD__SEARCHOPTD      = "searchoptd";      /* (  ) char(30) not null */
    const FIELD__SEARCHOPTDVAL1  = "searchoptdval1";  /* (  ) varchar(255) */
    const FIELD__SEARCHOPTDVAL2  = "searchoptdval2";  /* (  ) varchar(255) */
    const FIELD__SEARCHOPTDVAL3  = "searchoptdval3";  /* (  ) varchar(255) */
    const FIELD__SEARCHOPTDVAL4  = "searchoptdval4";  /* (  ) varchar(255) */
    const FIELD__SEARCHOPTDVAL5  = "searchoptdval5";  /* (  ) varchar(255) */

    /* ========================= */
    /* enum */
    /*
    */
    /* ========================= */
    const SEARCHOPT__SEARCH_ESTIMATE_FROM_BIZ = "searchEstimateFromBiz"; /* 업체에서 "견적요청중" 검색 */
    const SEARCHOPTD__AREA = "area";
    const SEARCHOPTD__ORDERTYPE = "ordertype";


    /* ========================= */
    /* select > sub */
    /* ========================= */
    // public function selectByBiznameForInside ($BIZNAME) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /* ========================= */
    const selectBySearchopt = "selectBySearchopt";
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        extract($options);

        /* orderride option */
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
              t.userno
            , t.searchopt
            , t.searchoptd
            , t.searchoptdval1
            , t.searchoptdval2
            , t.searchoptdval3
            , t.searchoptdval4
            , t.searchoptdval5
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectBySearchopt: { $from = "(select * from user_searchopt where userno = '$EXECUTOR' and searchopt = '$SEARCHOPT') t"; break; }
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
        ";
        $result = GGsql::select($query, $from, $options);
        return $result;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    public function insertForInside            ($USERNO, $SEARCHOPT, $SEARCHOPTD_ARR) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteBySearchoptForInside ($USERNO, $SEARCHOPT) { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    const insertForInside = "insertForInside";
    const deleteBySearchoptForInside = "deleteBySearchoptForInside";
    protected function update($options, $option="")
    {
        /* vars */
        $ggAuth = GGauth::getInstance();
        $userno = null;

        /* get vars */
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* sql execution */
        switch($OPTION)
        {
            case self::deleteBySearchoptForInside:
            {
                $query = " delete from user_searchopt where userno = '$USERNO' and searchopt = '$SEARCHOPT'";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::insertForInside:
            {
                foreach($SEARCHOPTD_ARR as $key => $val)
                {
                    $SEARCHOPTD = $val[self::FIELD__SEARCHOPTD];
                    $SEARCHOPTDVAL1 = $val[self::FIELD__SEARCHOPTDVAL1];
                    $SEARCHOPTDVAL2 = $val[self::FIELD__SEARCHOPTDVAL2];
                    $SEARCHOPTDVAL3 = $val[self::FIELD__SEARCHOPTDVAL3];
                    $SEARCHOPTDVAL4 = $val[self::FIELD__SEARCHOPTDVAL4];
                    $SEARCHOPTDVAL5 = $val[self::FIELD__SEARCHOPTDVAL5];

                    $query =
                    "
                        insert into user_searchopt
                        (
                              userno
                            , searchopt
                            , searchoptd
                            , searchoptdval1
                            , searchoptdval2
                            , searchoptdval3
                            , searchoptdval4
                            , searchoptdval5
                        )
                        values
                        (
                              '$USERNO'
                            , '$SEARCHOPT'
                            , '$SEARCHOPTD'
                            , '$SEARCHOPTDVAL1'
                            , '$SEARCHOPTDVAL2'
                            , '$SEARCHOPTDVAL3'
                            , '$SEARCHOPTDVAL4'
                            , '$SEARCHOPTDVAL5'
                        )
                    ";
                    $result = GGsql::exeQuery($query);

                } /* foreach */
                break;
            }
            case self::insertForInside:
            {
                /* =============== */
                /* validation */
                /* =============== */

                /* ----- */
                /* 기본 입력확인 */
                /* ----- */
                $BIZNAME    = trim($BIZNAME);
                $BIZNUM     = trim($BIZNUM);
                $BIZREP     = trim($BIZREP);
                $BIZPHONE1  = trim($BIZPHONE1);
                $BIZPHONE2  = trim($BIZPHONE2);
                $BIZPHONE3  = trim($BIZPHONE3);
                if($BIZNAME    == "")  { throw new GGexception("업체 이름을 입력해주세요."); }
                if($BIZNUM     == "")  { throw new GGexception("사업자등록번호를 입력해주세요."); }
                if($BIZREP     == "")  { throw new GGexception("업체대표자명을 입력해주세요."); }
                if($BIZPHONE1  == "")  { throw new GGexception("업체 전화번호를 입력해주세요."); }

                /* 업체 이름 중복 확인 */
                $bizFromBizname = Common::getDataOneField($this->selectByBiznameForInside($BIZNAME), UserSearchoptBO::FIELD__BIZNAME);
                if($bizFromBizname != null)
                    throw new GGexception("업체명이 이미 등록되어있습니다.");

                /* ----- */
                /* addr */
                /* ----- */
                $bizaddrno = $addrBO->insertByInside(
                      AddrBO::ADDRTYPE__BIZ
                    , $ADDR_ZIPCODE
                    , $ADDR_SIDO
                    , $ADDR_SIGUNGU
                    , $ADDR_EMD
                    , $ADDR_ROAD
                    , $ADDR_JIBUN
                    , $ADDR_ROADENG
                    , $ADDR_ADMCD
                    , $ADDR_RNMGTSN
                    , $ADDR_UDRTYN
                    , $ADDR_BULDMNNM
                    , $ADDR_BULDSLNO
                    , $ADDR_DETAIL
                );

                /* ----- */
                /* insert into table */
                /* ----- */
                $userno = $idxBO->makeuserno();
                $query =
                "
                    insert into biz
                    (
                          userno
                        , bizuserno
                        , bizname
                        , biznum
                        , bizrep
                        , bizaddrno
                        , bizphone1
                        , bizphone2
                        , bizphone3
                        , modidt
                        , regidt
                    )
                    values
                    (
                          '$userno'
                        , '$EXECUTOR'
                        , '$BIZNAME'
                        , '$BIZNUM'
                        , '$BIZREP'
                        , '$bizaddrno'
                        , '$BIZPHONE1'
                        , '$BIZPHONE2'
                        , '$BIZPHONE3'
                        ,  now()
                        ,  now()
                    )
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }
        return $userno;
    }

    /* ========================= */
    /* make arr for insertForInside */
    /*
    */
    /* ========================= */
    public function makeArr(
          $SEARCHOPTD
        , $SEARCHOPTDVAL1
        , $SEARCHOPTDVAL2 = ""
        , $SEARCHOPTDVAL3 = ""
        , $SEARCHOPTDVAL4 = ""
        , $SEARCHOPTDVAL5 = ""
    )
    {
        return array(
              self::FIELD__SEARCHOPTD      => $SEARCHOPTD
            , self::FIELD__SEARCHOPTDVAL1  => $SEARCHOPTDVAL1
            , self::FIELD__SEARCHOPTDVAL2  => $SEARCHOPTDVAL2
            , self::FIELD__SEARCHOPTDVAL3  => $SEARCHOPTDVAL3
            , self::FIELD__SEARCHOPTDVAL4  => $SEARCHOPTDVAL4
            , self::FIELD__SEARCHOPTDVAL5  => $SEARCHOPTDVAL5
        );
    }

} /* end class */
?>
