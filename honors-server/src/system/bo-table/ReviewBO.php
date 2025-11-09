<?php

class ReviewBO extends _CommonBO
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
    const FIELD__STORENO            = "storeno";            /* int */
    const FIELD__ORDERNO            = "orderno";            /* char(14) */
    const FIELD__REVIEW_TYPE        = "review_type";        /* enum('delivery', 'order', 'service') */
    const FIELD__REVIEW_WRITER      = "review_writer";      /* int */
    const FIELD__REVIEW_STATUS      = "review_status";      /* enum('writing', 'complete') */
    const FIELD__REVIEW_READFLG     = "review_readflg";     /* enum('y', 'n') */
    const FIELD__REVIEW_SCORE       = "review_score";       /* tinyint */
    const FIELD__REVIEW_COMMENT     = "review_comment";     /* varchar(255) */
    const FIELD__AT_REGIST          = "at_regist";          /* datetime */
    const FIELD__REVIEW_MENUS       = "reviewMenus";        /* customField : list of reviewMenu */

    /* enums */
    const REVIEW_TYPE__DELIVERY        = "delivery";
    const REVIEW_TYPE__ORDER           = "order";
    const REVIEW_TYPE__SERVICE         = "service";
    const REVIEW_STATUS__WRITING       = "writing";
    const REVIEW_STATUS__COMPLETE      = "complete";

    static public function getEnumConst()
    {
        $arr = array();
        $arr['reviewTypeDelivery']        = self::REVIEW_TYPE__DELIVERY;
        $arr['reviewTypeOrder']           = self::REVIEW_TYPE__ORDER;
        $arr['reviewTypeService']         = self::REVIEW_TYPE__SERVICE;
        $arr['reviewStatusWriting']       = self::REVIEW_STATUS__WRITING;
        $arr['reviewStatusComplete']      = self::REVIEW_STATUS__COMPLETE;
        return $arr;
    }

    public function __construct()
    {
        GGnavi::getReviewMenuBO();
    }

    /* ========================= */
    /*  */
    /*
        ■
        default
            - [*] SOTRE_INDEX
            - [*] ORDERNO


    */
    /* ========================= */
    public function selectByPkForInside                         ($STORENO, $ORDERNO, $REVIEW_TYPE) { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectByOrdernoForInside                    ($STORENO, $ORDERNO)               { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectReviewByStorenoAndExecutorForInside   ($STORENO, $EXECUTOR)              { return $this->select(get_defined_vars(), __FUNCTION__); }

    const selectForStore = "selectForStore";
    const selectByPk = "selectByPk";
    const selectByPkForInside = "selectByPkForInside";
    const selectReviewByStorenoAndExecutorForInside = "selectReviewByStorenoAndExecutorForInside";
    const selectByOrdernoForInside = "selectByOrdernoForInside";
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        $ggAuth = GGauth::getInstance();
        $enumConst = self::getEnumConst();
        extract($enumConst);
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
              rv.storeno
            , rv.orderno
            , rv.review_type
            , rv.review_writer
            , rv.review_status
            , rv.review_readflg
            , rv.review_score
            , rv.review_comment
            , rv.at_regist
            , oa.at_regist 'order_at_regist'
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPk                                        : { $ggAuth->orderView($EXECUTOR, $STORENO, $ORDERNO); $from = "(select * from review where storeno = '$STORENO' and orderno = '$ORDERNO' and review_type = '$REVIEW_TYPE') rv"; break; }
            case self::selectByPkForInside                               : {                                                    $from = "(select * from review where storeno = '$STORENO' and orderno = '$ORDERNO' and review_type = '$REVIEW_TYPE') rv"; break; }
            case self::selectByOrdernoForInside                          : {                                                    $from = "(select * from review where storeno = '$STORENO' and orderno = '$ORDERNO') rv"; break; }
            case self::selectReviewByStorenoAndExecutorForInside         :
            {
                $from =
                "
                    (
                        select
                            *
                        from
                            review
                        where
                            storeno = '$STORENO' and
                            review_writer = '$EXECUTOR' and
                            review_type = '$reviewTypeOrder'
                    ) rv
                ";
                break;
            }
            case self::selectForStore:
            {
                /* check auth */
                $ggAuth->isStoreOwner($EXECUTOR, $STORENO);

                /* check date */
                $sqlYear  = "";
                $sqlMonth = "";
                $sqlDay   = "";
                if(intval($DATETIME_YEAR)  != 0) $sqlYear  = "and oa.datetime_year    = $DATETIME_YEAR";
                if(intval($DATETIME_MONTH) != 0) $sqlMonth = "and oa.datetime_month   = $DATETIME_MONTH";
                if(intval($DATETIME_DAY)   != 0) $sqlDay   = "and oa.datetime_day     = $DATETIME_DAY";

                /* sql */
                $from =
                "
                    (
                        select
                            rv.*
                        from
                            review rv
                            left join ordera oa
                                on
                                    rv.storeno = oa.storeno and
                                    rv.orderno = oa.orderno
                        where
                                rv.storeno          = '$STORENO'
                            and rv.review_type      = '$reviewTypeOrder'
                            and rv.review_status    = '$reviewStatusComplete'
                            $sqlYear
                            $sqlMonth
                            $sqlDay
                    ) rv
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
                left join ordera oa
                    on
                        rv.storeno = oa.storeno and
                        rv.orderno = oa.orderno
            order by
                 rv.storeno
                ,rv.orderno
        ";
        $result = GGsql::select($query, $from, $options);

        /* --------------- */
        /* get additional data */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPk:
            case self::selectByPkForInside:
            {
                /* set reviewMenu array */
                $reviewMenuBO = ReviewMenuBO::getInstance();
                $mainData = Common::getData($result);
                foreach($mainData as &$model)
                {
                    $subRslt = $reviewMenuBO->selectByOrdernoForInside(
                        $model[GGF::STORENO],
                        $model[self::FIELD__ORDERNO]);
                    $model[self::FIELD__REVIEW_MENUS] = Common::getData($subRslt);
                }
                $result[GGF::DATA] = $mainData;
                break;
            }
            default:
            {
                /* set empty array */
                $mainData = Common::getData($result);
                foreach($mainData as &$model)
                    $model[self::FIELD__REVIEW_MENUS] = [];
                $result[GGF::DATA] = $mainData;
                break;
            }
        }
        return $result;
    }

    /* ==================== */
    /*
    */
    /* ==================== */
    public function deleteIfWritingByOrdernoForInside($STORENO, $ORDERNO) { return $this->update(get_defined_vars(), __FUNCTION__); }

    const updateToReadflgY = "updateToReadflgY";
    const regist = "regist";
    const updateToComplete = "updateToComplete";
    const deleteIfWritingByOrdernoForInside = "deleteIfWritingByOrdernoForInside";
    protected function update($options, $option="")
    {
        /* get vars */
        GGnavi::getOrderingBO();
        $ggAuth = GGauth::getInstance();
        $orderingBO = OrderingBO::getInstance();
        extract($options);

        /* orderride option */
        if($option != "")
            $OPTION = $option;

        /* sql execution */
        switch($OPTION)
        {
            case self::regist:
            {
                /* AUTH : executor 가 해당 주문의 주문자인지 확인 */
                $ggAuth->isOrderer($EXECUTOR, $STORENO, $ORDERNO);

                /* 리뷰가 완료된 상태인지 확인 */
                $orderingBO->isReviewCompleted($STORENO, $ORDERNO);

                /* ----- */
                /* insert */
                /* ----- */
                $query =
                "
                    insert into review
                    (
                          storeno
                        , orderno
                        , review_type
                        , review_writer
                        , review_score
                        , review_comment
                        , at_regist
                    )
                    values
                    (
                          '$STORENO'
                        , '$ORDERNO'
                        , '$REVIEW_TYPE'
                        , '$EXECUTOR'
                        ,  $REVIEW_SCORE
                        , '$REVIEW_COMMENT'
                        , now()
                    )
                    on duplicate key update
                      review_score     =  $REVIEW_SCORE
                    , review_comment   = '$REVIEW_COMMENT'
                ";
                $result = GGsql::exeQuery($query);

                /* review_type 이 order 인 경우, review_menu 에 레코드 삽입 */
                if($REVIEW_TYPE == ReviewBO::REVIEW_TYPE__ORDER)
                {
                    $reviewMenuBO = ReviewMenuBO::getInstance();
                    $reviewOrdermenus = $REVIEW_ORDERMENU;
                    foreach($reviewOrdermenus as $model)
                    {
                        if(
                            isset($model["STORENO"])  == false ||
                            isset($model["ORDERNO"])      == false ||
                            isset($model["CART_INDEX"])   == false ||
                            isset($model["MENUNO"])   == false ||
                            isset($model["REVIEW_SCORE"]) == false
                        )
                        {
                            continue;
                        }
                        $STORENO       = $model["STORENO"];
                        $ORDERNO       = $model["ORDERNO"];
                        $CART_INDEX    = $model["CART_INDEX"];
                        $MENUNO        = $model["MENUNO"];
                        $REVIEW_SCORE  = $model["REVIEW_SCORE"];
                        $reviewMenuBO->insertForInside($STORENO, $ORDERNO, $CART_INDEX, $MENUNO, $REVIEW_SCORE);
                    }
                }
                break;
            }
            case self::updateToComplete:
            {
                /* AUTH : executor 가 해당 주문의 주문자인지 확인 */
                $ggAuth->isOrderer($EXECUTOR, $STORENO, $ORDERNO);

                /* 리뷰가 완료된 상태인지 확인 */
                $orderingBO->isReviewCompleted($STORENO, $ORDERNO);

                /* update */
                $query = "update review set review_status = 'complete' where storeno = '$STORENO' and orderno = '$ORDERNO'";
                $result = GGsql::exeQuery($query);

                /* 주문에도 리뷰작성이 완료되었음을 알람 */
                $orderingBO->updateAtReviewToYForInside($STORENO, $ORDERNO);
                break;
            }
            case self::updateToReadflgY:
            {
                /* AUTH : storeno 가 해당 리뷰의 storeno 권한이 있는지 확인 */
                $ggAuth->isStoreOwner($EXECUTOR, $STORENO);

                /* update */
                $query =
                "
                    update
                        review
                    set
                        review_readflg = 'y'
                    where
                        storeno = '$STORENO'
                        and orderno = '$ORDERNO'
                        and review_type = '$REVIEW_TYPE'
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::deleteIfWritingByOrdernoForInside:
            {
                $reviewStatus = Common::getDataOneField($this->selectByOrdernoForInside($STORENO, $ORDERNO), self::FIELD__REVIEW_STATUS);
                if($reviewStatus == self::REVIEW_STATUS__WRITING)
                {
                    /* get bk */
                    $reviewMenuBO = ReviewMenuBO::getInstance();

                    /* delete */
                    $reviewStatusWriting = ReviewBO::REVIEW_STATUS__WRITING;
                    $query =
                    "
                        delete from
                            review
                        where
                            storeno = '$STORENO'
                            and orderno = '$ORDERNO'
                            and review_status = '$reviewStatusWriting'
                    ";
                    $result = GGsql::exeQuery($query);
                    $reviewMenuBO->deleteByOrdernoForInside($STORENO, $ORDERNO);
                }

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
