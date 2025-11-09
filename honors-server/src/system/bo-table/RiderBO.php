<?php

class RiderBO extends _CommonBO
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
    /* fields */
    /* ========================= */
    const FIELD__RIDERNO               = "riderno";              /* (pk) char(30) */
    const FIELD__REALNAME              = "realname";             /*      char(50) */
    const FIELD__PERSONALNUMBER        = "personalnumber";       /*      char(14) */
    const FIELD__DRIVINGLICENSE_NO     = "drivinglicense_no";    /*      char(20) */
    const FIELD__DRIVINGLICENSE_PIC    = "drivinglicense_pic";   /*      char(10) */
    const FIELD__FACEPIC               = "facepic";              /*      char(10) */
    const FIELD__PHONE1                = "phone1";               /*      char(20) */
    const FIELD__PHONE2                = "phone2";               /*      char(20) */
    const FIELD__PHONE3                = "phone3";               /*      char(20) */
    const FIELD__RIDERSTATUS           = "riderstatus";          /*      enum('authwait','authfailed','logout','wait','matching','ing') */
    const FIELD__BACCNO_SETTLE         = "baccno_settle";        /*      int */
    const FIELD__IS_DELETED            = "is_deleted";           /*      enum('y','n') */
    const FIELD__AT_AUTH               = "at_auth";              /*      datetime */
    const FIELD__AT_UPDATE             = "at_update";            /*      datetime */
    const FIELD__AT_REGIST             = "at_regist";            /*      datetime */

    const RIDERSTATUS__AUTHWAIT        = "authwait";             /* riderstatus : 인증대기중 */
    const RIDERSTATUS__AUTHFAILED      = "authfailed";           /* riderstatus : 인증실패 */
    const RIDERSTATUS__LOGOUT          = "logout";               /* riderstatus : 미운행중 */
    const RIDERSTATUS__WAIT            = "wait";                 /* riderstatus : 대기중 */
    const RIDERSTATUS__MATCHING        = "matching";             /* riderstatus : 매칭중 */
    const RIDERSTATUS__ING             = "ing";                  /* riderstatus : 배달중 */

    /* ========================= */
    /* detail */
    /* ========================= */
    public function selectDetail($executor, $riderno)
    {
        /* return */
        $rslt = Common::getReturn();

        // /* get bo */
        // GGnavi::getOrdermenuBO();
        // GGnavi::getOrdermenuoptBO();
        // GGnavi::getOrdermenuoptDetailBO();
        // GGnavi::getOrdermenuRecommendBO();
        // GGnavi::getUserBO();
        // GGnavi::getStoreOrderproctimeResultBO();
        // GGnavi::getReviewBO();
        // GGnavi::getSummaryStoreorderAllUserBO();
        // GGnavi::getSummaryUserorderAllBO();
        // GGnavi::getPaymentQueueBO();
        // GGnavi::getStoreBO();

        // /* BO */
        // $ordermenuBO                    = OrdermenuBO::getInstance();
        // $ordermenuoptBO                 = OrdermenuoptBO::getInstance();
        // $ordermenuoptDetailBO           = OrdermenuoptDetailBO::getInstance();
        // $ordermenuRecommendBO           = OrdermenuRecommendBO::getInstance();
        // $userBO                         = UserBO::getInstance();
        // $storeOrderproctimeResultBO     = StoreOrderproctimeResultBO::getInstance();
        // $reviewBO                       = ReviewBO::getInstance();
        // $summaryStoreorderAllUserBO     = SummaryStoreorderAllUserBO::getInstance();
        // $summaryUserorderAllBO          = SummaryUserorderAllBO::getInstance();
        // $paymentQueueBO                 = PaymentQueueBO::getInstance();
        // $storeBO                        = StoreBO::getInstance();

        /* -------------- */
        /* TODO : AUTH */
        /* -------------- */

        /* -------------- */
        /* process */
        /* -------------- */

        /* rider */
        $rider = Common::getDataOne($this->selectByRidernoForInside($STORENO, $ORDERNO));
        $rslt[GGF::DATA] = $rider;
        return $rslt;
    }


    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByUsernoForInside     ($USERNO)   { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectByRidernoForInside    ($RIDERNO)  { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectByPkForInside         ($RIDERNO)  { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /* ========================= */
    const selectMe = "selectMe";
    const selectByPkForInside = "selectByPkForInside";
    const selectByRiderno = "selectByRiderno";
    const selectByRidernoForInside = "selectByRidernoForInside";
    const selectByUsernoForInside = "selectByUsernoForInside";
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
              t.riderno
            , t.realname
            , t.personalnumber
            , t.drivinglicense_no
            , t.drivinglicense_pic
            , t.facepic
            , t.phone1
            , t.phone2
            , t.phone3
            , t.riderstatus
            , t.baccno_settle
            , t.is_deleted
            , t.at_auth
            , t.at_update
            , t.at_regist
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPkForInside        : { $from = "(select * from rider where riderno = '$RIDERNO') t"; break; }
            case self::selectByRiderno            : { $from = "(select * from rider where riderno = '$RIDERNO') t"; break; }
            case self::selectByRidernoForInside   : { $from = "(select * from rider where riderno = '$RIDERNO') t"; break; }
            case self::selectByUsernoForInside    : { $from = "(select * from rider where userno  = '$USERNO') t"; break; }
            case self::selectMe                   : { $from = "(select * from rider where userno  = '$EXECUTOR') t"; break; }
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
    /* update (sub) */
    /* ========================= */

    /* ========================= */
    /* update */
    /* ========================= */
    const insert = "insert";
    const updateBaccnoSettle = "updateBaccnoSettle";
    protected function update($options, $option="")
    {
        /* riderno */
        $riderno = "";

        /* get vars */
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* sql execution */
        switch($OPTION)
        {
            case self::insert:
            {
                /* 중복등록확인 */
                $rider = Common::getDataOne($this->selectByPkForInside($EXECUTOR));
                if($rider != null)
                    throw new GGexception("(server) already registered");

                /* trim 처리 */
                $REALNAME       = trim($REALNAME);
                $PERSONALNUMBER = trim($PERSONALNUMBER);
                $PHONE1         = trim($PHONE1);
                $PHONE2         = trim($PHONE2);
                $PHONE3         = trim($PHONE3);

                /* 입력확인 */
                if($REALNAME          == "")  { throw new GGexception("(server) empty REALNAME"); }
                if($PERSONALNUMBER    == "")  { throw new GGexception("(server) empty PERSONALNUMBER"); }
                if($PHONE1            == "")  { throw new GGexception("(server) empty PHONE1"); }

                /* get bo */
                GGnavi::getIdxBO();
                GGnavi::getImageUtils();
                $idxBO = IdxBO::getInstance();

                /* riderno 생성 */
                $riderno = $idxBO->makeRiderno();

                /* 라이더 등록 */
                $query =
                "
                    insert into rider
                    (
                          riderno
                        , realname
                        , personalnumber
                        , phone1
                        , phone2
                        , phone3
                        , at_update
                        , at_regist
                    )
                    values
                    (
                          '$riderno'
                        , '$REALNAME'
                        , '$PERSONALNUMBER'
                        , '$PHONE1'
                        , '$PHONE2'
                        , '$PHONE3'
                        ,  now()
                        ,  now()
                    )
                ";
                $result = GGsql::exeQuery($query);
                $riderno = $EXECUTOR;

                /* set default image */
                ImageUtils::setImgRiderDrivinglicense($riderno);
                ImageUtils::setImgRiderFacepic($riderno);
                break;
            }
            case self::updateBaccnoSettle:
            {
                $riderno = Common::getDataOneField($this->selectByUsernoForInside($EXECUTOR), self::FIELD__RIDERNO);
                $query =
                "
                    update
                        rider
                    set
                        baccno_settle = $BACCNO_SETTLE
                    where
                        riderno = '$riderno'
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }
        return $riderno;
    }

} /* end class */
?>
