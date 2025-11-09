<?php

class OrderingBO extends OrderBO
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
        GGnavi::getUserSearchoptBO();
        $this->userSearchoptBO = UserSearchoptBO::getInstance();
    }

    private $userSearchoptBO = null;

    /* ========================= */
    /*  */
    /*
        ■ return : 주문레코드 배열
    */
    /* ========================= */
    public function selectByPkForInside ($USERNO, $ORDERNO) { return $this->select(get_defined_vars(), __FUNCTION__); }


    /* ========================= */
    /*  */
    /* ========================= */
    const selectByPk = "selectByPk";
    const selectByPkForInside = "selectByPkForInside";
    const searchEstmateFromBiz = "searchEstmateFromBiz";
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        $ggAuth = GGauth::getInstance();
        extract($options);
        extract(self::getOrderConsts());

        /* option override */
        $OPTION = $option != "" ? $option : $OPTION;

        /* --------------- */
        /* sql body */
        /* --------------- */
        $priv = "";
        $from = "";
        $orderBy = "";
        $limit = "";

        /* --------------- */
        /* auth */
        /* --------------- */
        // switch($OPTION)
        // {
        //     case self::selectByPk: $ggAuth->orderView($EXECUTOR, $STORENO, $ORDERNO); break;
        // }

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPk           : { $from = "(select * from ordering where userno = '$USERNO' and orderno = '$ORDERNO') t "; break; }
            case self::selectByPkForInside  : { $from = "(select * from ordering where userno = '$USERNO' and orderno = '$ORDERNO') t "; break; }
            case self::searchEstmateFromBiz :
            {
                /* val */
                $userSearchoptdArr = array();
                $areaArr = $AREA_ARR;
                $ordertypeArr = $ORDERTYPE_ARR;

                /* ----- */
                /* area */
                /* ----- */
                $areaQuery = "";
                foreach($areaArr as $val)
                {
                    $areaQuery .= $areaQuery == "" ? "" : ",";
                    $areaQuery .= "(".$val["SDIDX"].",".$val["SGGIDX"].")";

                    /* make arr for insert to userSearchopt */
                    $userSearchoptdArr[] = $this->userSearchoptBO->makeArr(UserSearchoptBO::SEARCHOPTD__AREA, $val["SDIDX"], $val["SDNAME"], $val["SGGIDX"], $val["SGGNAME"]);
                }
                $areaQuery = "and ((o.est_sdidx, o.est_sggidx) in ($areaQuery) or (s.sdidx, s.sggparent) in ($areaQuery))";

                /* ----- */
                /* ordertype */
                /* ----- */
                $etc = false;
                $all = false;
                $ordertypeQuery = "";
                foreach($ordertypeArr as $val)
                {
                    if($val == "all")
                    {
                        $all = true;
                        break;
                    }
                    if($val == "etc")
                    {
                        $etc = true;
                        break;
                    }
                    $ordertypeQuery .= $ordertypeQuery == "" ? "" : ",";
                    $ordertypeQuery .= "'".$val."'";

                    /* make arr for insert to userSearchopt */
                    $userSearchoptdArr[] = $this->userSearchoptBO->makeArr(UserSearchoptBO::SEARCHOPTD__ORDERTYPE, $val);
                }
                $ordertypeQuery = "and o.ordertype in ($ordertypeQuery)";
                if($all == true)
                {
                    $ordertypeQuery = "";

                    /* make arr for insert to userSearchopt */
                    $userSearchoptdArr[] = $this->userSearchoptBO->makeArr(UserSearchoptBO::SEARCHOPTD__ORDERTYPE, "all");
                }

                if($etc == true)
                {
                    $ordertypeQuery = "and o.ordertype = 'etc' and o.ordertypeetc = '$ORDERTYPEETC'";

                    /* make arr for insert to userSearchopt */
                    $userSearchoptdArr[] = $this->userSearchoptBO->makeArr(UserSearchoptBO::SEARCHOPTD__ORDERTYPE, "etc", $ORDERTYPEETC);
                }

                /* ----- */
                /* update UserSearchopt */
                /* ----- */
                if(intval($PAGENUM) == 1)
                {
                    $this->userSearchoptBO->deleteBySearchoptForInside($EXECUTOR, UserSearchoptBO::SEARCHOPT__SEARCH_ESTIMATE_FROM_BIZ);
                    $this->userSearchoptBO->insertForInside($EXECUTOR, UserSearchoptBO::SEARCHOPT__SEARCH_ESTIMATE_FROM_BIZ, $userSearchoptdArr);
                }


                /* ----- */
                /* query */
                /* ----- */
                $from =
                "
                    (
                        select
                            *
                        from
                            ordering o
                            left join _address_sigungu s
                                on
                                    o.est_sdidx = s.sdidx and
                                    o.est_sggidx = s.sggidx
                        where
                            orderstatus = '$orderstatusEstimate'
                            $areaQuery
                            $ordertypeQuery
                    ) t
                "; break;
            }
            default:
            {
                throw new GGexception("(system) process failed.");
            }
        } /* end switch($OPTION) */

        /* --------------- */
        /* execute query */
        /* --------------- */
        $query = $this->makeSelectQuery($from, $orderBy, $limit, $priv);
        return GGsql::select($query, $from, $options);
    }

    /* ==================== */
    /* 주문 업데이트 */
    /* ==================== */
    /* public function updateAtSettleToNowForInside ($STORENO, $ORDERNO) { return $this->update(get_defined_vars(), __FUNCTION__); } */

    const insertApply = "insertApply";
    const updateOrdertype = "updateOrdertype";
    const updateEstAddr = "updateEstAddr";
    const updateEstDate = "updateEstDate";
    const updateEstReq = "updateEstReq";
    const updateEstInfo = "updateEstInfo";
    const updateToEstimate = "updateToEstimate";
    protected function update($options, $option="")
    {
        /* get bo */
        $ggAuth = GGauth::getInstance();

        /* set option */
        extract($options);
        extract(self::getOrderConsts());

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* ==================== */
        /* validation : auth */
        /* ==================== */
        // switch($OPTION)
        // {
        //     case self::updateOrderstatusPayToCancel:
        //         $ggAuth->orderCustomerOwn($EXECUTOR, $STORENO, $ORDERNO);
        //         break;
        //     case self::updateOrderstatusConfirmToCook:
        //     case self::updateOrderstatusConfirmToCancel:
        //     case self::updateOrderstatusCookToCancel:
        //         $ggAuth->orderStoreOwn($EXECUTOR, $STORENO, $ORDERNO);
        //         break;
        //     case self::updateDeliverystatusToComplete:
        //         $ggAuth->orderRiderOwn($EXECUTOR, $STORENO, $ORDERNO);
        //         break;
        //     case self::updateDeliverystatusToDeliverying:
        //         $ggAuth->isRider($EXECUTOR);
        //         break;
        // }

        /* ==================== */
        /* process */
        /* ==================== */
        switch($OPTION)
        {
            case self::insertApply:
            {
                $ORDERNO = $this->makeOrderno($EXECUTOR);
                $query =
                "
                    insert into ordering
                    (
                          userno
                        , orderno
                        , orderstatus
                        , modidt
                        , regdt
                    )
                    values
                    (
                          '$EXECUTOR'
                        , '$ORDERNO'
                        , '$orderstatusApply'
                        ,  now()
                        ,  now()
                    )
                ";
                $result = GGsql::exeQuery($query);
                $USERNO = $EXECUTOR;
                break;
            }
            case self::updateOrdertype:
            {
                /* auth */

                /* validation */
                if($ORDERTYPE == null || $ORDERTYPE == "") throw new GGexception("시공종류를 선택해주세요.");
                if($ORDERTYPE == "etc" && Common::isEmpty($ORDERTYPEETC)) throw new GGexception("시공종류를 입력해주세요.");

                /* query */
                $query = "";
                switch($ORDERTYPE)
                {
                    case "etc":
                    {
                        $query =
                        "
                            update
                                ordering
                            set
                                  ordertype    = '$ORDERTYPE'
                                , ordertypeetc = '$ORDERTYPEETC'
                                , modidt       =  now()
                            where
                                userno = '$EXECUTOR' and
                                orderno = '$ORDERNO'
                        ";
                        $result = GGsql::exeQuery($query);
                        break;
                    }
                    default:
                    {
                        $query =
                        "
                            update
                                ordering
                            set
                                  ordertype    = '$ORDERTYPE'
                                , ordertypeetc =  null
                                , modidt       =  now()
                            where
                                userno = '$EXECUTOR' and
                                orderno = '$ORDERNO'
                        ";
                        $result = GGsql::exeQuery($query);
                        break;
                    }
                }
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::updateEstAddr:
            {
                /* auth */

                /* validation */
                if(Common::isEmpty($EST_SDIDX)) throw new GGexception("시/도를 선택해주세요.");
                if(Common::isEmpty($EST_SGGIDX)) throw new GGexception("시/군/구를 선택해주세요.");
                if(Common::isEmpty($EST_ADDRDETAIL)) throw new GGexception("상세주소를 입력해주세요.");

                /* query */
                $query =
                "
                    update
                        ordering
                    set
                          modidt         =  now()
                        , est_sdidx      =  $EST_SDIDX
                        , est_sggidx     =  $EST_SGGIDX
                        , est_addrdetail = '$EST_ADDRDETAIL'
                    where
                        userno = '$EXECUTOR' and
                        orderno = '$ORDERNO'
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::updateEstDate:
            {
                /* auth */

                /* validation */
                if(Common::isEmpty($EST_DATESTART)) throw new GGexception("시공시작일자를 입력해주세요.");
                if(Common::isEmpty($EST_DATEEND))   throw new GGexception("시공종료일자를 입력해주세요.");

                /* query */
                $query =
                "
                    update
                        ordering
                    set
                          modidt         =  now()
                        , est_datestart  = '$EST_DATESTART'
                        , est_dateend    = '$EST_DATEEND'
                    where
                        userno = '$EXECUTOR' and
                        orderno = '$ORDERNO'
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::updateEstReq:
            {
                /* auth */

                /* validation */

                /* query */
                $query =
                "
                    update
                        ordering
                    set
                          modidt           =  now()
                        , est_specify      = '$EST_SPECIFY'
                        , est_request      = '$EST_REQUEST'
                    where
                        userno = '$EXECUTOR' and
                        orderno = '$ORDERNO'
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::updateEstInfo:
            {
                /* bo */
                GGnavi::getUserBO();
                $userBO = UserBO::getInstance();
                $user = $userBO->getUser($EXECUTOR);

                /* auth */

                /* validation */

                /* update userInfo : phone */
                if(strcmp($PHONE, $user[UserBO::FIELD__PHONE]) !== 0)
                    $userBO->updatePhoneByUsernoForInside($EXECUTOR, $PHONE);

                /* query */
                $query =
                "
                    update
                        ordering
                    set
                          modidt           =  now()
                        , est_phone        = '$PHONE'
                        , est_emgphone1    = '$EST_EMGPHONE1'
                        , est_emgphone2    = '$EST_EMGPHONE2'
                        , est_emgphone3    = '$EST_EMGPHONE3'
                    where
                        userno = '$EXECUTOR' and
                        orderno = '$ORDERNO'
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::updateToEstimate:
            {
                /* auth */

                /* validation */

                /* query */
                $query =
                "
                    update
                        ordering
                    set
                          modidt           = now()
                        , orderstatus      = '$orderstatusEstimate'
                        , est_limitdt      = date_format(date_add(now(), interval 7 day), '%Y-%m-%d 23:59:59')
                    where
                        userno = '$EXECUTOR' and
                        orderno = '$ORDERNO'
                ";
                $result = GGsql::exeQuery($query);
                break;
            }

            default:
            {
                throw new GGexception("(server) error while updating order info");
            }
        }

        /* --------------- */
        /* 업데이트된 정보를 ordera에 복사 */
        /* --------------- */
        $query =
        "
            replace into ordera
            select
                *
            from
                ordering
            where
                userno  = '$USERNO' and
                orderno = '$ORDERNO'
        ";
        $result = GGsql::exeQuery($query);
        return $ORDERNO;
    }

} /* end class */
?>
