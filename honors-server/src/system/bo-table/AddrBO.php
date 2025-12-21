<?php
class AddrBO extends _CommonBO
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
    /* fields */
    /*
    */
    /* ========================= */
    const FIELD__ADDRNO          = "addrno";         /* (pk) char(30) */
    const FIELD__ADDRTYPE        = "addrtype";       /* (  ) char(30) */
    const FIELD__ADDR_ZIPCODE    = "addr_zipcode";   /* (  ) varchar(30) */
    const FIELD__ADDR_SIDO       = "addr_sido";      /* (  ) varchar(30) */
    const FIELD__ADDR_SIGUNGU    = "addr_sigungu";   /* (  ) varchar(30) */
    const FIELD__ADDR_EMD        = "addr_emd";       /* (  ) varchar(30) */
    const FIELD__ADDR_ROAD       = "addr_road";      /* (  ) varchar(255) */
    const FIELD__ADDR_JIBUN      = "addr_jibun";     /* (  ) varchar(255) */
    const FIELD__ADDR_ROADENG    = "addr_roadeng";   /* (  ) varchar(255) */
    const FIELD__ADDR_ADMCD      = "addr_admcd";     /* (  ) varchar(50) */
    const FIELD__ADDR_RNMGTSN    = "addr_rnmgtsn";   /* (  ) varchar(50) */
    const FIELD__ADDR_UDRTYN     = "addr_udrtyn";    /* (  ) varchar(1) */
    const FIELD__ADDR_BULDMNNM   = "addr_buldmnnm";  /* (  ) bigint */
    const FIELD__ADDR_BULDSLNO   = "addr_buldslno";  /* (  ) bigint */
    const FIELD__ADDR_GRS80Y     = "addr_grs80y";    /* (  ) double */
    const FIELD__ADDR_GRS80X     = "addr_grs80x";    /* (  ) double */
    const FIELD__ADDR_LATIY      = "addr_latiy";     /* (  ) double */
    const FIELD__ADDR_LONGX      = "addr_longx";     /* (  ) double */
    const FIELD__ADDR_LATIYS     = "addr_latiys";    /* (  ) double */
    const FIELD__ADDR_LONGXS     = "addr_longxs";    /* (  ) double */
    const FIELD__ADDR_DETAIL     = "addr_detail";    /* (  ) char(100) */

    /* ========================= */
    /* fields */
    /*
    */
    /* ========================= */
    const ADDRTYPE__BIZ = "biz";


    /* ========================= */
    /*  */
    /*

    */
    /* ========================= */
    // public function selectByPkForInside($USERNO, $BACCNO) { return $this->select(get_defined_vars(), __FUNCTION__); }
    // public function selecBankaccountByPkNotDeletedForInside($USERNO, $BACCNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    // const selectBankaccountByPk = "selectBankaccountByPk";
    protected function select($options, $option="")
    {
        /* -------------- */
        /* vars */
        /* -------------- */
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
              t.addrno
            , t.addrtype
            , t.addr_zipcode
            , t.addr_sido
            , t.addr_sigungu
            , t.addr_emd
            , t.addr_road
            , t.addr_jibun
            , t.addr_roadeng
            , t.addr_admcd
            , t.addr_rnmgtsn
            , t.addr_udrtyn
            , t.addr_buldmnnm
            , t.addr_buldslno
            , t.addr_grs80y
            , t.addr_grs80x
            , t.addr_latiy
            , t.addr_longx
            , t.addr_latiys
            , t.addr_longxs
            , t.addr_detail
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            // case self::selectAbleByUserno : { $from = "(select * from bankaccount where userno = '$EXECUTOR' and is_deleted = 'n' ) ba"; break; }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        } /* end switch($OPTION) */

        /* --------------- */
        /* make query */
        /* --------------- */
        $query =
        "
            select
                $select
            from
                $from
        ";

        /* --------------- */
        /* execute query */
        /* --------------- */
        return GGsql::select($query, $from, $options);
    }

    /* ==================== */
    /*  */
    /*
     */
    /* ==================== */
    public function insertByInside(
          $ADDRTYPE
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
    )
    {
        return $this->update(get_defined_vars(), __FUNCTION__);
    }


    const insertByInside = "insertByInside";
    protected function update($options, $option="")
    {
        /* -------------- */
        /* vars */
        /* -------------- */
        extract($options);
        $addrno = null;

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* result */
        $rslt = Common::getReturn();

        try
        {
            /* ==================== */
            /* process */
            /* ==================== */
            switch($OPTION)
            {
                case self::insertByInside:
                {
                    /* ----- */
                    /* get BO */
                    /* ----- */
                    GGnavi::getGGcoordinate();
                    GGnavi::getIdxBO();
                    $ggCoordinate = GGcoordinate::getInstance();
                    $idxBO = IdxBO::getInstance();

                    /* ----- */
                    /* validation */
                    /* ----- */

                    /* get coordinate */
                    $coordinate = $ggCoordinate->getCoordinate(
                        $ADDR_ADMCD,
                        $ADDR_RNMGTSN,
                        $ADDR_UDRTYN,
                        $ADDR_BULDMNNM,
                        $ADDR_BULDSLNO
                    );
                    $ADDR_GRS80Y = $coordinate['ADDR_GRS80Y'];
                    $ADDR_GRS80X = $coordinate['ADDR_GRS80X'];
                    $ADDR_LATIY  = $coordinate['ADDR_LATIY'];
                    $ADDR_LONGX  = $coordinate['ADDR_LONGX'];

                    /* 주소 유효확인 */
                    GGvalid::isValidAddr($options);

                    /* 지역정보 */
                    $ADDR_LATIYS = round($ADDR_LATIY, 2);
                    $ADDR_LONGXS = round($ADDR_LONGX, 2);

                    /* ----- */
                    /* insert */
                    /* ----- */

                    /* make addrno */
                    $addrno = $idxBO->makeAddrno();
                    $query =
                    "
                        insert into addr
                        (
                              addrno
                            , addrtype
                            , addr_zipcode
                            , addr_sido
                            , addr_sigungu
                            , addr_emd
                            , addr_road
                            , addr_jibun
                            , addr_roadeng
                            , addr_admcd
                            , addr_rnmgtsn
                            , addr_udrtyn
                            , addr_buldmnnm
                            , addr_buldslno
                            , addr_grs80y
                            , addr_grs80x
                            , addr_latiy
                            , addr_longx
                            , addr_latiys
                            , addr_longxs
                            , addr_detail
                        )
                        values
                        (
                             '$addrno'
                            ,'$ADDRTYPE'
                            ,'$ADDR_ZIPCODE'
                            ,'$ADDR_SIDO'
                            ,'$ADDR_SIGUNGU'
                            ,'$ADDR_EMD'
                            ,'$ADDR_ROAD'
                            ,'$ADDR_JIBUN'
                            ,'$ADDR_ROADENG'
                            ,'$ADDR_ADMCD'
                            ,'$ADDR_RNMGTSN'
                            ,'$ADDR_UDRTYN'
                            , $ADDR_BULDMNNM
                            , $ADDR_BULDSLNO
                            , $ADDR_GRS80Y
                            , $ADDR_GRS80X
                            , $ADDR_LATIY
                            , $ADDR_LONGX
                            , $ADDR_LATIYS
                            , $ADDR_LONGXS
                            ,'$ADDR_DETAIL'
                        )
                    ";
                    $result = GGsql::exeQuery($query);
                    break;
                }
            } /* switch : 옵션별 처리 */
        }
        catch(Error $e)
        {
            throw $e;
        }
        return $addrno;
    }

} /* end class */
?>
