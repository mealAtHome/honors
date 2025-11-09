<?php

class OrdersAddrBO extends _CommonBO
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
    public function __construct()
    {
    }

    /* FIELD */
    const FIELD__STORENO                        = "storeno";       /* (pk) char(30) */
    const FIELD__ORDERNO                        = "orderno";       /* (pk) char(14) */
    const FIELD__ADDR_INDEX                     = "addr_index";    /*      int */
    const FIELD__ADDR_NAME                      = "addr_name";     /*      varchar(30) */
    const FIELD__ADDR_ZIPCODE                   = "addr_zipcode";  /*      varchar(30) */
    const FIELD__ADDR_SIDO                      = "addr_sido";     /*      varchar(30) */
    const FIELD__ADDR_SIGUNGU                   = "addr_sigungu";  /*      varchar(30) */
    const FIELD__ADDR_EMD                       = "addr_emd";      /*      varchar(30) */
    const FIELD__ADDR_ROAD                      = "addr_road";     /*      varchar(255) */
    const FIELD__ADDR_JIBUN                     = "addr_jibun";    /*      varchar(255) */
    const FIELD__ADDR_ROADENG                   = "addr_roadeng";  /*      varchar(255) */
    const FIELD__ADDR_DETAIL                    = "addr_detail";   /*      varchar(255) */
    const FIELD__ADDR_LATIY                     = "addr_latiy";    /*      double */
    const FIELD__ADDR_LONGX                     = "addr_longx";    /*      double */

    /* POST */
    const POST__STORENO                         = "storeno";
    const POST__ORDERNO                         = "orderno";
    const POST__ADDR_INDEX                      = "addr_index";
    const POST__ADDR_NAME                       = "addr_name";
    const POST__ADDR_ZIPCODE                    = "addr_zipcode";
    const POST__ADDR_SIDO                       = "addr_sido";
    const POST__ADDR_SIGUNGU                    = "addr_sigungu";
    const POST__ADDR_EMD                        = "addr_emd";
    const POST__ADDR_ROAD                       = "addr_road";
    const POST__ADDR_JIBUN                      = "addr_jibun";
    const POST__ADDR_ROADENG                    = "addr_roadeng";
    const POST__ADDR_DETAIL                     = "addr_detail";

    /* ========================= */
    /* */
    /*
    */
    /* ========================= */
    public function selectByPk($STORENO, $ORDERNO) { return $this->select(get_defined_vars()); }

    const selectByPk = "selectByPk";
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        extract($options);

        /* option override */
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
             t.storeno
            ,t.orderno
            ,t.addr_index
            ,t.addr_name
            ,t.addr_zipcode
            ,t.addr_sido
            ,t.addr_sigungu
            ,t.addr_emd
            ,t.addr_road
            ,t.addr_jibun
            ,t.addr_roadeng
            ,t.addr_detail
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPk:
            {
                $from =
                "
                    (
                        select
                            *
                        from
                            orders_address
                        where
                            orderno = '$ORDERNO' and
                            storeno = '$STORENO'
                    ) t
                ";
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
        ";
        return GGsql::select($query, $from, $options);
    }


    /* ========================= */
    /* */
    /*
    */
    /* ========================= */
    public function insertForInside(
          $STORENO
        , $ORDERNO
        , $ADDR_INDEX
        , $ADDR_NAME
        , $ADDR_ZIPCODE
        , $ADDR_SIDO
        , $ADDR_SIGUNGU
        , $ADDR_EMD
        , $ADDR_ROAD
        , $ADDR_JIBUN
        , $ADDR_ROADENG
        , $ADDR_DETAIL
        , $ADDR_LATIY
        , $ADDR_LONGX
    )
    {
        return $this->update(get_defined_vars(), __FUNCTION__);
    }

    const insertForInside = "insertForInside";
    protected function update($options, $option="")
    {
        /* vars */
        $menuno = null;

        /* option override */
        if($option != "")
            $OPTION = $option;

        /* get vars */
        extract($options);

        /* ==================== */
        /* process */
        /* ==================== */
        switch($OPTION)
        {
            case self::insertForInside:
            {
                $query =
                "
                    insert into orders_address
                    (
                         storeno
                        ,orderno
                        ,addr_index
                        ,addr_name
                        ,addr_zipcode
                        ,addr_sido
                        ,addr_sigungu
                        ,addr_emd
                        ,addr_road
                        ,addr_jibun
                        ,addr_roadeng
                        ,addr_detail
                        ,addr_latiy
                        ,addr_longx
                    )
                    values
                    (
                          '$STORENO'
                        , '$ORDERNO'
                        ,  $ADDR_INDEX
                        , '$ADDR_NAME'
                        , '$ADDR_ZIPCODE'
                        , '$ADDR_SIDO'
                        , '$ADDR_SIGUNGU'
                        , '$ADDR_EMD'
                        , '$ADDR_ROAD'
                        , '$ADDR_JIBUN'
                        , '$ADDR_ROADENG'
                        , '$ADDR_DETAIL'
                        ,  $ADDR_LATIY
                        ,  $ADDR_LONGX
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
        return true;
    }


} /* end class */
?>
