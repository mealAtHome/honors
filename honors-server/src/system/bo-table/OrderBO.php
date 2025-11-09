<?php

class OrderBO extends _CommonBO
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
    public static function getFromGGnavi()
    {
        GGnavi::getOrdersCancelBO();
    }
    public function __construct()
    {
        self::getFromGGnavi();
    }

    /* ========================= */
    /* field */
    /* ========================= */
    const FIELD__USERNO                            = "userno";                                  /* (  ) char(30) */
    const FIELD__ORDERNO                           = "orderno";                                 /* (PK) char(14) */
    const FIELD__COMPANYNO                         = "companyno";                               /* (  ) char(30) */
    const FIELD__ORDERSTATUS                       = "orderstatus";                             /* (  ) char(30) */
    const FIELD__ORDERTYPE                         = "ordertype";                               /* (  ) char(30) */
    const FIELD__ORDERTYPEETC                      = "ordertypeetc";                            /* (  ) varchar(50) */
    const FIELD__EST_SDIDX                         = "est_sdidx";                               /* (  ) int */
    const FIELD__EST_SGGIDX                        = "est_sggidx";                              /* (  ) int */
    const FIELD__EST_ADDRDETAIL                    = "est_addrdetail";                          /* (  ) varchar(255) */
    const FIELD__EST_DATESTART                     = "est_datestart";                           /* (  ) date */
    const FIELD__EST_DATEEND                       = "est_dateend";                             /* (  ) date */
    const FIELD__EST_SPECIFY                       = "est_specify";                             /* (  ) varchar(255) */
    const FIELD__EST_REQUEST                       = "est_request";                             /* (  ) varchar(255) */
    const FIELD__EST_PHONE                         = "est_phone";                               /* (  ) varchar(30) */
    const FIELD__EST_EMGPHONE1                     = "est_emgphone1";                           /* (  ) varchar(30) */
    const FIELD__EST_EMGPHONE2                     = "est_emgphone2";                           /* (  ) varchar(30) */
    const FIELD__EST_EMGPHONE3                     = "est_emgphone3";                           /* (  ) varchar(30) */
    const FIELD__EST_LIMITDT                       = "est_limitdt";                             /* (  ) date */
    const FIELD__MODIDT                            = "modidt";                                  /* (  ) datetime */
    const FIELD__REGDT                             = "regdt";                                   /* (  ) datetime */

    /* ========================= */
    /* enums */
    /* ========================= */
    /* ordertype */     const ORDERTYPE_HVACDUCT                = "hvacduct";               /* 공조덕트 */
    /* ordertype */     const ORDERTYPE_REQUIDATION             = "requidation";            /* 철거 */
    /* ordertype */     const ORDERTYPE_HOUSECLEAN              = "houseclean";             /* 입주청소 */
    /* ordertype */     const ORDERTYPE_SHOPCLEAN               = "shopclean";              /* 상가청소 */
    /* ordertype */     const ORDERTYPE_OPENCLEAN               = "openclean";              /* 오픈청소 */
    /* ordertype */     const ORDERTYPE_ETC                     = "etc";                    /* 기타 */
    /* orderstatus */   const ORDERSTATUS_APPLY                 = "apply";                  /* 견적의뢰작성중 */
    /* orderstatus */   const ORDERSTATUS_ESTIMATE              = "estimate";               /* 견적중 */
    /* orderstatus */   const ORDERSTATUS_DECIDING              = "deciding";               /* 견적마감/업체확정중 */
    /* orderstatus */   const ORDERSTATUS_DECIDED               = "decided";                /* 업체확정/작업대기중 */
    /* orderstatus */   const ORDERSTATUS_WORKING               = "working";                /* 공사중 */
    /* orderstatus */   const ORDERSTATUS_WORKEND               = "workend";                /* 공사완료/고객확인 중 */
    /* orderstatus */   const ORDERSTATUS_CLAIM                 = "claim";                  /* 클레임확인대기 */
    /* orderstatus */   const ORDERSTATUS_ADMINWORKING          = "adminworking";           /* 관리자공사중 */
    /* orderstatus */   const ORDERSTATUS_REWORKING             = "reworking";              /* 재공사중 */
    /* orderstatus */   const ORDERSTATUS_RECONFIRM             = "reconfirm";              /* 고객재확인중 */
    /* orderstatus */   const ORDERSTATUS_COMPLETE              = "complete";               /* 확인완료/종료 */
    /* orderstatus */   const ORDERSTATUS_CANCEL                = "cancel";                 /* 취소(환불) */

    /* ========================= */
    /* select options */
    /* ========================= */
    static public function getOrderConsts()
    {
        self::getFromGGnavi();
        $arr = array();
        $arr['ordertypeHvacduct']              = self::ORDERTYPE_HVACDUCT;                   /* 공조덕트 */
        $arr['ordertypeRequidation']           = self::ORDERTYPE_REQUIDATION;                /* 철거 */
        $arr['ordertypeHouseclean']            = self::ORDERTYPE_HOUSECLEAN;                 /* 입주청소 */
        $arr['ordertypeShopclean']             = self::ORDERTYPE_SHOPCLEAN;                  /* 상가청소 */
        $arr['ordertypeOpenclean']             = self::ORDERTYPE_OPENCLEAN;                  /* 오픈청소 */
        $arr['ordertypeEtc']                   = self::ORDERTYPE_ETC;                        /* 기타 */
        $arr['orderstatusApply']               = self::ORDERSTATUS_APPLY;                    /* 견적의뢰작성중 */
        $arr['orderstatusEstimate']            = self::ORDERSTATUS_ESTIMATE;                 /* 견적중 */
        $arr['orderstatusDeciding']            = self::ORDERSTATUS_DECIDING;                 /* 견적마감/업체확정중 */
        $arr['orderstatusDecided']             = self::ORDERSTATUS_DECIDED;                  /* 업체확정/작업대기중 */
        $arr['orderstatusWorking']             = self::ORDERSTATUS_WORKING;                  /* 공사중 */
        $arr['orderstatusWorkend']             = self::ORDERSTATUS_WORKEND;                  /* 공사완료/고객확인 중 */
        $arr['orderstatusClaim']               = self::ORDERSTATUS_CLAIM;                    /* 클레임확인대기 */
        $arr['orderstatusAdminworking']        = self::ORDERSTATUS_ADMINWORKING;             /* 관리자공사중 */
        $arr['orderstatusReworking']           = self::ORDERSTATUS_REWORKING;                /* 재공사중 */
        $arr['orderstatusReconfirm']           = self::ORDERSTATUS_RECONFIRM;                /* 고객재확인중 */
        $arr['orderstatusComplete']            = self::ORDERSTATUS_COMPLETE;                 /* 확인완료/종료 */
        $arr['orderstatusCancel']              = self::ORDERSTATUS_CANCEL;                   /* 취소(환불) */
        return $arr;
    }

    public function makeSelectQuery($from="", $orderBy="", $limit="", $priv="")
    {
        /* order by override */
        if($orderBy == "")
        {
            $orderBy =
            "
                order by
                    t.regdt asc
            ";
        }

        /* select */
        $select = "";
        switch($priv)
        {
            case GGF::PRIA:
            {
                $select =
                "
                      '$priv' as priv
                    , t.userno
                    , t.orderno
                    , t.companyno
                    , t.orderstatus
                    , t.ordertype
                    , t.est_sdidx
                    , t.est_sggidx
                    , sd.sdname as est_sdname
                    , sgg.sggname as est_sggname
                    , t.est_addrdetail
                    , t.est_datestart
                    , t.est_dateend
                    , t.est_specify
                    , t.est_request
                    , t.est_phone
                    , t.est_emgphone1
                    , t.est_emgphone2
                    , t.est_emgphone3
                    , t.est_limitdt
                    , t.modidt
                    , t.regdt
                ";
                break;
            }
            default:
            {
                $select =
                "
                            '$priv' as priv
                    ,       t.userno
                    ,       t.orderno
                    ,       t.companyno
                    ,       t.orderstatus
                    ,       t.ordertype
                    ,       t.est_sdidx
                    ,       t.est_sggidx
                    ,       sd.sdname as est_sdname
                    ,       sgg.sggname as est_sggname
                    , null  est_addrdetail
                    ,       t.est_datestart
                    ,       t.est_dateend
                    ,       t.est_specify
                    ,       t.est_request
                    , null  est_phone
                    , null  est_emgphone1
                    , null  est_emgphone2
                    , null  est_emgphone3
                    ,       t.est_limitdt
                    ,       t.modidt
                    ,       t.regdt
                ";
                break;
            }
        }

        $query =
        "
            select
                $select
            from
                $from
                left join _address_sido sd
                    on
                        t.est_sdidx  = sd.sdidx
                left join _address_sigungu sgg
                    on
                        t.est_sdidx  = sgg.sdidx and
                        t.est_sggidx = sgg.sggidx
            $orderBy
            $limit
        ";
        return $query;
    }

} /* end class */
?>
