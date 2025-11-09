<?php

class MenuRecommendBO extends _CommonBO
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
    const FIELD__STORENO                        = "storeno";                            /* int(11) (pk) */
    const FIELD__MENUNO                         = "menuno";                             /* int(11) (pk) */
    const FIELD__RECOMMENDNO                    = "recommendno";                        /* int(11) (pk) */
    const FIELD__RECOMMEND_SORT                 = "recommend_sort";                     /* int(11) */
    const FIELD__RECOMMEND_NAME                 = "recommend_name";                     /* char(50) */
    const FIELD__RECOMMEND_MENU                 = "recommend_menu";                     /* int(11) */
    const FIELD__RECOMMEND_PRICE                = "recommend_price";                    /* int(11) */
    const FIELD__RECOMMEND_DISCOUNT             = "recommend_discount";                 /* int(11) */
    const FIELD__RECOMMEND_CHOOSABLECNT_MAX     = "recommend_choosablecnt_max";         /* int(11) */
    const FIELD__RECOMMEND_COMMENT              = "recommend_comment";                  /* char(100) */
    const FIELD__RECOMMEND_PIC                  = "recommend_pic";                      /* int(11) */
    const FIELD__IS_DISPLAY                     = "is_display";                         /* enum('y','n') */
    const FIELD__IS_STOCK                       = "is_stock";                           /* enum('y','n') */
    const FIELD__IS_DELETED                     = "is_deleted";                         /* enum('y','n') */

    /* POST */
    const POST__STORENO                         = "STORENO";                            /* int(11) (pk) */
    const POST__MENUNO                          = "MENUNO";                             /* int(11) (pk) */
    const POST__RECOMMENDNO                     = "RECOMMENDNO";                        /* int(11) (pk) */
    const POST__RECOMMEND_SORT                  = "RECOMMEND_SORT";                     /* int(11) */
    const POST__RECOMMEND_NAME                  = "RECOMMEND_NAME";                     /* char(50) */
    const POST__RECOMMEND_MENU                  = "RECOMMEND_MENU";                     /* int(11) */
    const POST__RECOMMEND_PRICE                 = "RECOMMEND_PRICE";                    /* int(11) */
    const POST__RECOMMEND_DISCOUNT              = "RECOMMEND_DISCOUNT";                 /* int(11) */
    const POST__RECOMMEND_CHOOSABLECNT_MAX      = "RECOMMEND_CHOOSABLECNT_MAX";         /* int(11) */
    const POST__RECOMMEND_COMMENT               = "RECOMMEND_COMMENT";                  /* char(100) */
    const POST__RECOMMEND_PIC                   = "RECOMMEND_PIC";                      /* int(11) */
    const POST__IS_DISPLAY                      = "IS_DISPLAY";                         /* enum('y','n') */
    const POST__IS_STOCK                        = "IS_STOCK";                           /* enum('y','n') */
    const POST__IS_DELETED                      = "IS_DELETED";                         /* enum('y','n') */

    /* ========================= */
    /* */
    /*
    */
    /* ========================= */

    public function selectByStorenoMenunoForInside ($STORENO, $MENUNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    const selectByStorenoMenunoForInside = "selectByStorenoMenunoForInside";
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        extract($options);

        /* option override */
        if($option != "")
        $OPTION = $option;

        /* -------------- */
        /* vars */
        /* -------------- */
        $Y = GGF::Y;
        $N = GGF::N;

        /* --------------- */
        /* sql body */
        /* --------------- */
        $query  = "";
        $select = "";
        $from   = "";
        $select =
        "
              mr.storeno
            , mr.menuno
            , mr.recommendno
            , mr.recommend_name
            , mr.recommend_menu
            , mr.recommend_price
            , mr.recommend_discount
            , mr.recommend_choosablecnt_max
            , mr.recommend_comment
            , mr.recommend_pic
            , mr.is_display
            , mr.is_stock
            , mr.is_deleted
            ,  p.pic_name
            ,  p.pic_kind
            ,  p.pic_path
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByStorenoMenunoForInside:
            {
                $from =
                "
                    (
                        select
                            *
                        from
                            menu_recommend
                        where
                            storeno  = '$STORENO' and
                            menuno   = $MENUNO
                    ) mr
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
                left join menu_pic p
                    on
                        mr.storeno   = p.storeno and
                        mr.recommend_pic = p.pic_index
            order by
                 mr.storeno
                ,mr.menuno
                ,mr.recommendno
        ";
        return GGsql::select($query, $from, $options);
    }


    /* ========================= */
    /* */
    /*
    */
    /* ========================= */
    public function deleteByStorenoMenunoForInside ($STORENO, $MENUNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function insertMenuRecommendForInside(
        $STORENO,
        $MENUNO,
        $RECOMMENDNO,
        $RECOMMEND_SORT,
        $RECOMMEND_NAME,
        $RECOMMEND_MENU,
        $RECOMMEND_PRICE,
        $RECOMMEND_DISCOUNT,
        $RECOMMEND_CHOOSABLECNT_MAX,
        $RECOMMEND_COMMENT,
        $RECOMMEND_PIC,
        $IS_DISPLAY,
        $IS_STOCK,
        $IS_DELETED
    )
    {
        return $this->update(get_defined_vars(), __FUNCTION__);
    }

    const deleteByStorenoMenunoForInside = "deleteByStorenoMenunoForInside";
    const insertMenuRecommendForInside = "insertMenuRecommendForInside";
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
            case self::insertMenuRecommendForInside:
            {
                /* validate */
                if($RECOMMENDNO == "")
                    throw new GGexception("추천메뉴의 순서정보가 누락되었습니다.");

                if($RECOMMEND_MENU == "") $RECOMMEND_MENU  = "null";
                if($RECOMMEND_PIC  == "") $RECOMMEND_PIC   = "null";

                /* sql execute */
                $query =
                "
                    insert into menu_recommend
                    (
                        storeno
                        ,menuno
                        ,recommendno
                        ,recommend_sort
                        ,recommend_name
                        ,recommend_menu
                        ,recommend_price
                        ,recommend_discount
                        ,recommend_choosablecnt_max
                        ,recommend_comment
                        ,recommend_pic
                        ,is_display
                        ,is_stock
                        ,is_deleted
                    )
                    values
                    (
                          '$STORENO'
                        ,  $MENUNO
                        ,  $RECOMMENDNO
                        ,  $RECOMMEND_SORT
                        , '$RECOMMEND_NAME'
                        ,  $RECOMMEND_MENU
                        ,  $RECOMMEND_PRICE
                        ,  $RECOMMEND_DISCOUNT
                        ,  $RECOMMEND_CHOOSABLECNT_MAX
                        , '$RECOMMEND_COMMENT'
                        ,  $RECOMMEND_PIC
                        , '$IS_DISPLAY'
                        , '$IS_STOCK'
                        , '$IS_DELETED'
                    )
                    on duplicate key update
                          recommend_sort             =  $RECOMMEND_SORT
                        , recommend_name             = '$RECOMMEND_NAME'
                        , recommend_menu             =  $RECOMMEND_MENU
                        , recommend_price            =  $RECOMMEND_PRICE
                        , recommend_discount         =  $RECOMMEND_DISCOUNT
                        , recommend_choosablecnt_max =  $RECOMMEND_CHOOSABLECNT_MAX
                        , recommend_comment          = '$RECOMMEND_COMMENT'
                        , recommend_pic              =  $RECOMMEND_PIC
                        , is_display                 = '$IS_DISPLAY'
                        , is_stock                   = '$IS_STOCK'
                        , is_deleted                 = '$IS_DELETED'
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::deleteByStorenoMenunoForInside:
            {
                $query =
                "
                    update
                        menu_recommend
                    set
                        is_deleted = 'y'
                    where
                        storeno = '$STORENO' and
                        menuno = $MENUNO
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
