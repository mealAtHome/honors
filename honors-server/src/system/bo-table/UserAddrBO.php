<?php

/* au */
class UserAddrBO extends _CommonBO
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

    const FIELD__USERNO               = "userno";
    const FIELD__ADDR_INDEX           = "addr_index";
    const FIELD__ADDR_NAME            = "addr_name";
    const FIELD__ADDR_ZIPCODE         = "addr_zipcode";
    const FIELD__ADDR_SIDO            = "addr_sido";
    const FIELD__ADDR_SIGUNGU         = "addr_sigungu";
    const FIELD__ADDR_EMD             = "addr_emd";
    const FIELD__ADDR_ROAD            = "addr_road";
    const FIELD__ADDR_JIBUN           = "addr_jibun";
    const FIELD__ADDR_ROADENG         = "addr_roadeng";
    const FIELD__ADDR_ADMCD           = "addr_admcd";
    const FIELD__ADDR_RNMGTSN         = "addr_rnmgtsn";
    const FIELD__ADDR_UDRTYN          = "addr_udrtyn";
    const FIELD__ADDR_BULDMNNM        = "addr_buldmnnm";
    const FIELD__ADDR_BULDSLNO        = "addr_buldslno";
    const FIELD__ADDR_GRS80Y          = "addr_grs80y";
    const FIELD__ADDR_GRS80X          = "addr_grs80x";
    const FIELD__ADDR_LATIY           = "addr_latiy";
    const FIELD__ADDR_LONGX           = "addr_longx";
    const FIELD__ADDR_DETAIL          = "addr_detail";
    const FIELD__IS_DEFAULT           = "is_default";
    const FIELD__AT_LASTORDERCOMPLETE = "at_lastordercomplete";
    const FIELD__CNT_ORDERCOMPLETE    = "cnt_ordercomplete";
    const FIELD__IS_DELETED           = "is_deleted";

    /* ========================= */
    /* 유저의 주변 반경 위/경도 쿼리문 반환 */
    /*
        반환형태는 다음과 같음
        return "(위도,경도),(위도,경도),(위도,경도),(위도,경도)";
     */
    /* ========================= */
    public function getLatiyLongxQueryByExecutor($executor)
    {
        $userAddr = Common::getDataOne($this->selectByDefaultYForInside($executor));
        $query = GGsql::makeInQueryByGPS($userAddr[UserAddrBO::FIELD__ADDR_LATIY], $userAddr[UserAddrBO::FIELD__ADDR_LONGX], 0.02);
        return $query;
    }

    /* ========================= */
    /* 조회 */
    /* ========================= */
    public function selectByDefaultYForInside   ($EXECUTOR)                 { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectByAddrIndexForInside  ($EXECUTOR, $ADDR_INDEX)    { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /*  */
    /*
        ■ option
            - executor
    */
    /* ========================= */
    const selectByDefaultY              = "selectByDefaultY";
    const selectByDefaultYForInside     = "selectByDefaultYForInside";
    const selectByExecutor              = "selectByExecutor";
    const selectByAddrIndex             = "selectByAddrIndex";
    const selectByAddrIndexForInside    = "selectByAddrIndexForInside";
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
        /* init vars */
        /* --------------- */
        extract($options);

        /* --------------- */
        /* sql body */
        /* --------------- */
        $query  = "";
        $select = "";
        $from   = "";
        $select =
        "
            au.userno
            ,au.addr_index
            ,au.addr_name
            ,au.addr_zipcode
            ,au.addr_sido
            ,au.addr_sigungu
            ,au.addr_emd
            ,au.addr_road
            ,au.addr_jibun
            ,au.addr_roadeng
            ,au.addr_admcd
            ,au.addr_rnmgtsn
            ,au.addr_udrtyn
            ,au.addr_buldmnnm
            ,au.addr_buldslno
            ,au.addr_grs80y
            ,au.addr_grs80x
            ,au.addr_latiy
            ,au.addr_longx
            ,au.addr_detail
            ,au.is_default
            ,au.is_deleted
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByDefaultY             : { $from = "(select * from user_addr where userno = '$EXECUTOR' and is_default = 'y' ) au"; break; }
            case self::selectByDefaultYForInside    : { $from = "(select * from user_addr where userno = '$EXECUTOR' and is_default = 'y' ) au"; break; }
            case self::selectByExecutor             : { $from = "(select * from user_addr where userno = '$EXECUTOR' and is_deleted = 'n' ) au"; break; }
            case self::selectByAddrIndex            : { $from = "(select * from user_addr where userno = '$EXECUTOR' and addr_index = $ADDR_INDEX ) au"; break; }
            case self::selectByAddrIndexForInside   : { $from = "(select * from user_addr where userno = '$EXECUTOR' and addr_index = $ADDR_INDEX ) au"; break; }
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
                au.userno,
                au.addr_index
        ";
        return GGsql::select($query, $from, $options);
    }

    /* ========================= */
    /* UPDATE / DELETE */
    /* ========================= */
    public function updateByCompletedOrderForInside($USERNO, $ADDR_INDEX) { return $this->updateUserAddr(get_defined_vars(), __FUNCTION__); }

    const updateByCompletedOrderForInside = "updateByCompletedOrderForInside"; /* [USERNO, ADDR_INDEX] */
    const newuser = "newuser"; /* 새로운 유저의 주소등록 */
    function updateUserAddr($options, $option="")
    {
        /* vars */
        $rslt = Common::getReturn();
        try
        {
            /* ==================== */
            /* common validation */
            /* ==================== */
            extract($options);

            /* override option */
            if($option != "")
                $OPTION = $option;

            /* ==================== */
            /* 사전처리 */
            /* ==================== */
            switch($OPTION)
            {
                case self::newuser:
                case _CommonBO::INSERT:
                case _CommonBO::UPDATE:
                {
                    if(!is_numeric($ADDR_BULDMNNM)) $ADDR_BULDMNNM = "null";
                    if(!is_numeric($ADDR_BULDSLNO)) $ADDR_BULDSLNO = "null";
                    if(!is_numeric($ADDR_GRS80Y))   $ADDR_GRS80Y   = "null";
                    if(!is_numeric($ADDR_GRS80X))   $ADDR_GRS80X   = "null";
                    if(!is_numeric($ADDR_LATIY))    $ADDR_LATIY    = "null";
                    if(!is_numeric($ADDR_LONGX))    $ADDR_LONGX    = "null";
                    break;
                }
            }

            /* ==================== */
            /* process */
            /* ==================== */
            switch($OPTION)
            {
                case self::newuser:
                case _CommonBO::INSERT:
                {
                    /* get new index */
                    $addrIndex = $this->getNewIndex($EXECUTOR);

                    /* if newuser, set addr to default */
                    $isDefault = GGF::N;
                    if($OPTION == self::newuser)
                        $isDefault = GGF::Y;

                    /* process */
                    $query =
                    "
                        insert into user_addr
                        (
                            userno
                            ,addr_index
                            ,addr_name
                            ,addr_zipcode
                            ,addr_sido
                            ,addr_sigungu
                            ,addr_emd
                            ,addr_road
                            ,addr_jibun
                            ,addr_roadeng
                            ,addr_admcd
                            ,addr_rnmgtsn
                            ,addr_udrtyn
                            ,addr_buldmnnm
                            ,addr_buldslno
                            ,addr_grs80y
                            ,addr_grs80x
                            ,addr_latiy
                            ,addr_longx
                            ,addr_detail
                            ,is_default
                        )
                        values
                        (
                             '$EXECUTOR'
                            , $addrIndex
                            ,'$ADDR_NAME'
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
                            ,'$ADDR_DETAIL'
                            ,'$isDefault'
                        )
                    ";
                    $result = GGsql::exeQuery($query);
                    break;
                }
                case "updateDefaultUserAddr":
                {
                    /* 현재주소지 해제 */
                    $query =
                    "
                        update
                            user_addr
                        set
                            is_default = 'n'
                        where
                            userno = '$EXECUTOR' and
                            is_default = 'y'
                    ";
                    $result = GGsql::exeQuery($query);

                    /* 현재주소지 설정 */
                    $query =
                    "
                        update
                            user_addr
                        set
                            is_default = 'y'
                        where
                            userno = '$EXECUTOR' and
                            addr_index = $ADDR_INDEX
                    ";
                    $result = GGsql::exeQuery($query);
                    break;
                }
                case _CommonBO::UPDATE:
                {
                    /* process */
                    $query =
                    "
                        update
                            user_addr
                        set
                            addr_name      = '$ADDR_NAME',
                            addr_zipcode   = '$ADDR_ZIPCODE',
                            addr_sido      = '$ADDR_SIDO',
                            addr_sigungu   = '$ADDR_SIGUNGU',
                            addr_emd       = '$ADDR_EMD',
                            addr_road      = '$ADDR_ROAD',
                            addr_jibun     = '$ADDR_JIBUN',
                            addr_roadeng   = '$ADDR_ROADENG',
                            addr_admcd     = '$ADDR_ADMCD',
                            addr_rnmgtsn   = '$ADDR_RNMGTSN',
                            addr_udrtyn    = '$ADDR_UDRTYN',
                            addr_buldmnnm  =  $ADDR_BULDMNNM,
                            addr_buldslno  =  $ADDR_BULDSLNO,
                            addr_grs80y    =  $ADDR_GRS80Y,
                            addr_grs80x    =  $ADDR_GRS80X,
                            addr_latiy     =  $ADDR_LATIY,
                            addr_longx     =  $ADDR_LONGX,
                            addr_detail    = '$ADDR_DETAIL'
                        where
                            userno = '$EXECUTOR' and
                            addr_index = $ADDR_INDEX
                    ";
                    $result = GGsql::exeQuery($query);
                    break;
                }
                case _CommonBO::DELETE:
                {
                    $query =
                    "
                        update
                            user_addr
                        set
                            is_deleted = 'y'
                        where
                            userno = '$EXECUTOR' and
                            addr_index = $ADDR_INDEX
                    ";
                    $result = GGsql::exeQuery($query);
                    break;
                }
                case self::updateByCompletedOrderForInside:
                {
                    // at_lastordercomplete : 최근 주문이 완료된 일자
                    // cnt_ordercomplete : 주문 누적건수
                    $query =
                    "
                        update
                            user_addr
                        set
                            at_lastordercomplete = now(),
                            cnt_ordercomplete = cnt_ordercomplete + 1
                        where
                            userno = '$USERNO' and
                            addr_index = $ADDR_INDEX
                    ";
                    $result = GGsql::exeQuery($query);
                    break;
                }
            }
        }
        catch(Error $e)
        {
            throw $e;
        }
        return $rslt;
    }

    public function getNewIndex($EXECUTOR)
    {
        $query = "select coalesce(max(addr_index),0)+1 max from user_addr where userno = '$EXECUTOR'";
        $maxIndex = intval(GGsql::selectCnt($query));
        return $maxIndex;
    }

} /* end class */
?>
