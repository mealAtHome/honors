<?php

/* m */
class MenuBO extends _CommonBO
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

    const FIELD__STORENO           = "storeno";                 /* (pk) char(30) */
    const FIELD__MENUNO            = "menuno";                  /* (pk) int */
    const FIELD__MENU_SORT         = "menu_sort";               /*      int */
    const FIELD__MENU_NAME         = "menu_name";               /*      char(100) */
    const FIELD__MENU_PRICE        = "menu_price";              /*      int */
    const FIELD__MENU_COMMENT      = "menu_comment";            /*      text */
    const FIELD__PIC1              = "pic1";                    /*      int */
    const FIELD__PIC2              = "pic2";                    /*      int */
    const FIELD__PIC3              = "pic3";                    /*      int */
    const FIELD__PIC4              = "pic4";                    /*      int */
    const FIELD__PIC5              = "pic5";                    /*      int */
    const FIELD__REORDERPCT        = "reorderpct";              /*      int */
    const FIELD__IS_MAIN           = "is_main";                 /*      enum('y','n') */
    const FIELD__IS_DISPLAY        = "is_display";              /*      enum('y','n') */
    const FIELD__IS_STOCK          = "is_stock";                /*      enum('y','n') */
    const FIELD__IS_DELETED        = "is_deleted";              /*      enum('y','n') */
    const FIELD__MENUOPTNO_USED    = "menuoptno_used";          /*      int */
    const FIELD__RECOMMENDNO_USED  = "recommendno_used";        /*      int */
    const FIELD__AT_CREATED        = "at_created";              /*      datetime */
    const FIELD__AT_DELETED        = "at_deleted";              /*      datetime */

    /* ========================= */
    /*  */
    /*
    */
    /* ========================= */
    public function selectMenuByPkForInside          ($STORENO, $MENUNO )     { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectMenuByPkForOrderForInside  ($STORENO)               { return $this->select(get_defined_vars(), __FUNCTION__); }

    const selectMenuByPkForInside          = "selectMenuByPkForInside";
    const selectMenuByPkForOrderForInside  = "selectMenuByPkForOrderForInside";
    const selectMenuForStoreMain           = "selectMenuForStoreMain";
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        $ggAuth = GGauth::getInstance();
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
        $additionalSelect = "";
        $additionalJoin   = "";
        $select =
        "
             m.storeno
            ,m.menuno
            ,m.menu_sort
            ,m.menu_name
            ,m.menu_price
            ,m.menu_comment
            ,m.pic1
            ,m.pic2
            ,m.pic3
            ,m.pic4
            ,m.pic5
            ,m.is_main
            ,m.is_display
            ,m.is_stock
            ,m.is_deleted
            ,m.menuoptno_used
            ,m.recommendno_used
            ,m.at_created
            ,m.at_deleted
            ,p1.pic_path pic_path1
            ,p2.pic_path pic_path2
            ,p3.pic_path pic_path3
            ,p4.pic_path pic_path4
            ,p5.pic_path pic_path5
        ";

        switch(Common::getServiceLayer())
        {
            case GGF::SERVICE_LAYER__CUS:
            {
                break;
            }
            case GGF::SERVICE_LAYER__BIZ:
            {
                $additionalSelect =
                "
                    ,ssor030m.cnt_complete      ssor030m_cnt_complete
                    ,ssor030m.cnt_cancel        ssor030m_cnt_cancel
                    ,ssor030m.cnt_cancelafter   ssor030m_cnt_cancelafter
                    ,ssor030m.sale_complete     ssor030m_sale_complete
                    ,ssor030m.sale_cancel       ssor030m_sale_cancel
                    ,ssor030m.sale_cancelafter  ssor030m_sale_cancelafter
                    ,ssor030m.review_cnt        ssor030m_review_cnt
                    ,ssor030m.review_sum        ssor030m_review_sum
                    ,ssor030m.review_avg        ssor030m_review_avg
                    ,ssor060m.cnt_complete      ssor060m_cnt_complete
                    ,ssor060m.cnt_cancel        ssor060m_cnt_cancel
                    ,ssor060m.cnt_cancelafter   ssor060m_cnt_cancelafter
                    ,ssor060m.sale_complete     ssor060m_sale_complete
                    ,ssor060m.sale_cancel       ssor060m_sale_cancel
                    ,ssor060m.sale_cancelafter  ssor060m_sale_cancelafter
                    ,ssor060m.review_cnt        ssor060m_review_cnt
                    ,ssor060m.review_sum        ssor060m_review_sum
                    ,ssor060m.review_avg        ssor060m_review_avg
                    ,ssor090m.cnt_complete      ssor090m_cnt_complete
                    ,ssor090m.cnt_cancel        ssor090m_cnt_cancel
                    ,ssor090m.cnt_cancelafter   ssor090m_cnt_cancelafter
                    ,ssor090m.sale_complete     ssor090m_sale_complete
                    ,ssor090m.sale_cancel       ssor090m_sale_cancel
                    ,ssor090m.sale_cancelafter  ssor090m_sale_cancelafter
                    ,ssor090m.review_cnt        ssor090m_review_cnt
                    ,ssor090m.review_sum        ssor090m_review_sum
                    ,ssor090m.review_avg        ssor090m_review_avg
                    ,ssor180m.cnt_complete      ssor180m_cnt_complete
                    ,ssor180m.cnt_cancel        ssor180m_cnt_cancel
                    ,ssor180m.cnt_cancelafter   ssor180m_cnt_cancelafter
                    ,ssor180m.sale_complete     ssor180m_sale_complete
                    ,ssor180m.sale_cancel       ssor180m_sale_cancel
                    ,ssor180m.sale_cancelafter  ssor180m_sale_cancelafter
                    ,ssor180m.review_cnt        ssor180m_review_cnt
                    ,ssor180m.review_sum        ssor180m_review_sum
                    ,ssor180m.review_avg        ssor180m_review_avg
                ";

                $additionalJoin =
                "
                    left join summary_storeorder_eee_recent030_menu ssor030m on m.storeno = ssor030m.storeno and m.menuno = ssor030m.menuno
                    left join summary_storeorder_eee_recent060_menu ssor060m on m.storeno = ssor060m.storeno and m.menuno = ssor060m.menuno
                    left join summary_storeorder_eee_recent090_menu ssor090m on m.storeno = ssor090m.storeno and m.menuno = ssor090m.menuno
                    left join summary_storeorder_eee_recent180_menu ssor180m on m.storeno = ssor180m.storeno and m.menuno = ssor180m.menuno
                ";
                break;
            }
            case GGF::SERVICE_LAYER__ADM:
            {
                break;
            }
        }

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectMenuByPkForInside         : { $from = "(select * from menu where storeno = '$STORENO' and menuno = $MENUNO ) m"; break; }
            case self::selectMenuByPkForOrderForInside : { $from = "(select * from menu where storeno = '$STORENO'                       and is_display = '$Y' and is_stock = '$Y' and is_deleted = '$N') m "; break; }
            case self::selectMenuForStoreMain:
            {
                $ggAuth->isStoreOwner($EXECUTOR, $STORENO);

                $where = "";
                switch($STATUS)
                {
                    case "all"            : $where = "                                          and is_deleted = '$N'"; break;
                    case "displayY"       : $where = "and is_display = '$Y'                     and is_deleted = '$N'"; break;
                    case "displayYstockN" : $where = "and is_display = '$Y' and is_stock = '$N' and is_deleted = '$N'"; break;
                    case "displayN"       : $where = "and is_display = '$N'                     and is_deleted = '$N'"; break;
                }
                $from =
                "
                    (
                        select
                            *
                        from
                            menu
                        where
                            storeno = '$STORENO' and
                            menu_name like '%${MENU_NAME}%'
                            $where
                    ) m
                ";
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        } /* end switch($OPTION) */

        /* --------------- */
        /* exe query */
        /* --------------- */
        $query =
        "
            select
                $select
                $additionalSelect
            from
                $from
                left join menu_pic p1 on m.storeno = p1.storeno and m.pic1 = p1.pic_index
                left join menu_pic p2 on m.storeno = p2.storeno and m.pic2 = p2.pic_index
                left join menu_pic p3 on m.storeno = p3.storeno and m.pic3 = p3.pic_index
                left join menu_pic p4 on m.storeno = p4.storeno and m.pic4 = p4.pic_index
                left join menu_pic p5 on m.storeno = p5.storeno and m.pic5 = p5.pic_index
                $additionalJoin
            order by
                m.storeno,
                m.menu_name
        ";
        return GGsql::select($query, $from, $options);
    }

    /* ==================== */
    /* 메뉴 업데이트 */
    /* ==================== */
    public function updateMenuByOption        ($options) { return $this->update($options); }
    public function updateReorderpctForInside ()         { return $this->update(get_defined_vars(), __FUNCTION__); }


    const deleteMenuByPk                    = "deleteMenuByPk";
    const insertMenu                        = "insertMenu";
    const copyMenu                          = "copyMenu";
    const updateMenu                        = "updateMenu";
    const updateIsStockY                    = "updateIsStockY";
    const updateIsStockN                    = "updateIsStockN";
    const updateReorderpctForInside         = "updateReorderpctForInside";
    protected function update($options, $option="")
    {
        /* vars */
        $ggAuth = GGauth::getInstance();
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
            case self::updateIsStockY : { $ggAuth->isStoreOwner($EXECUTOR, $STORENO); $to = GGF::Y; $query = "update menu set is_stock = '$to' where storeno = '$STORENO' and menuno = $MENUNO"; $result = GGsql::exeQuery($query); break; }
            case self::updateIsStockN : { $ggAuth->isStoreOwner($EXECUTOR, $STORENO); $to = GGF::N; $query = "update menu set is_stock = '$to' where storeno = '$STORENO' and menuno = $MENUNO"; $result = GGsql::exeQuery($query); break; }
            case self::deleteMenuByPk:
            {
                $ggAuth->isStoreOwner($EXECUTOR, $STORENO);

                GGsql::exeQuery("delete from menu where storeno = '$STORENO' and menuno = $MENUNO");
                $menuCategoryBO->deleteByStorenoMenunoForInside           ($STORENO, $MENUNO); /* MenuCategory */
                $menuCategoryBO->deleteMenuoptByStorenoMenunoForInside    ($STORENO, $MENUNO); /* Menuopt */
                $menuCategoryBO->deleteByStorenoMenunoForInside           ($STORENO, $MENUNO); /* MenuoptDetail */
                $menuCategoryBO->deleteByStorenoMenunoForInside           ($STORENO, $MENUNO); /* MenuRecommend */
                break;
            }
            case self::insertMenu:
            case self::copyMenu:
            case self::updateMenu:
            {
                $ggAuth->isStoreOwner($EXECUTOR, $STORENO); /* auth */

                /* --------------- */
                /* vars */
                /* --------------- */
                /* get BO */
                GGnavi::getMenuCategoryBO();
                GGnavi::getMenuoptBO();
                GGnavi::getMenuoptDetailBO();
                GGnavi::getMenuRecommendBO();
                $menuCategoryBO   = MenuCategoryBO::getInstance();
                $menuoptBO        = MenuoptBO::getInstance();
                $menuoptDetailBO  = MenuoptDetailBO::getInstance();
                $menuRecommendBO  = MenuRecommendBO::getInstance();

                /* json decode */
                $categoryArr  = json_decode($CATEGORY, true);
                $menuoptArr   = json_decode($MENUOPT, true);
                $recommendArr = json_decode($RECOMMEND, true);

                /* validation & set default */
                if($PIC1       == "") $PIC1       = "null";
                if($PIC2       == "") $PIC2       = "null";
                if($PIC3       == "") $PIC3       = "null";
                if($PIC4       == "") $PIC4       = "null";
                if($PIC5       == "") $PIC5       = "null";
                if($IS_MAIN    == "") $IS_MAIN    = GGF::N;
                if($IS_DISPLAY == "") $IS_DISPLAY = GGF::N;
                if($IS_STOCK   == "") $IS_STOCK   = GGF::Y;
                if($IS_DELETED == "") $IS_DELETED = GGF::N;

                /* get new menuno */
                $menuno = null;
                switch($OPTION)
                {
                    case self::insertMenu :
                    case self::copyMenu   :
                    {
                        $menuno = $this->getNewIndex($STORENO);
                        $query =
                        "
                            insert into menu
                            (
                                storeno
                                ,menuno
                                ,menu_sort
                                ,menu_name
                                ,menu_price
                                ,menu_comment
                                ,pic1
                                ,pic2
                                ,pic3
                                ,pic4
                                ,pic5
                                ,is_main
                                ,is_display
                                ,is_stock
                                ,is_deleted
                                ,at_created
                            )
                            values
                            (
                                 '$STORENO'
                                , $menuno
                                , null
                                ,'$MENU_NAME'
                                , $MENU_PRICE
                                ,'$MENU_COMMENT'
                                , $PIC1
                                , $PIC2
                                , $PIC3
                                , $PIC4
                                , $PIC5
                                ,'$IS_MAIN'
                                ,'$IS_DISPLAY'
                                ,'$IS_STOCK'
                                ,'$IS_DELETED'
                                , now()
                            )
                        ";
                        $result = GGsql::exeQuery($query);
                        break;
                    }
                    case self::updateMenu:
                    {
                        $menuno = $MENUNO;
                        $query =
                        "
                            update
                                menu
                            set
                                 menu_sort     =  null
                                ,menu_name     = '$MENU_NAME'
                                ,menu_price    =  $MENU_PRICE
                                ,menu_comment  = '$MENU_COMMENT'
                                ,pic1          =  $PIC1
                                ,pic2          =  $PIC2
                                ,pic3          =  $PIC3
                                ,pic4          =  $PIC4
                                ,pic5          =  $PIC5
                                ,is_main       = '$IS_MAIN'
                                ,is_display    = '$IS_DISPLAY'
                            where
                                storeno = '$STORENO' and
                                menuno  =  $menuno

                        ";
                        $result = GGsql::exeQuery($query);

                        /* delete child */
                        $menuCategoryBO  ->deleteByStorenoMenunoForInside ($STORENO, $menuno); /* MenuCategory */
                        $menuoptBO       ->deleteByStorenoMenunoForInside ($STORENO, $menuno); /* Menuopt */
                        $menuoptDetailBO ->deleteByStorenoMenunoForInside ($STORENO, $menuno); /* MenuoptDetail */
                        $menuRecommendBO ->deleteByStorenoMenunoForInside ($STORENO, $menuno); /* MenuRecommend */
                        break;
                    }
                }

                /* --------------- */
                /* child process */
                /* --------------- */

                /* menuCategory */
                foreach($categoryArr as $category)
                    $menuCategoryBO->insertMenuCategoryForInside($STORENO, $menuno, $category);

                /* menuopt */
                foreach($menuoptArr as $menuopt)
                {
                    $menuoptno = $menuopt['MENUOPTNO'];
                    $menuoptBO->insertMenuoptForInside(
                        $STORENO,
                        $menuno,
                        $menuoptno,
                        $menuopt[MenuoptBO::POST__MENUOPT_SORT],
                        $menuopt[MenuoptBO::POST__MENUOPT_NAME],
                        $menuopt[MenuoptBO::POST__MENUOPT_CHOOSEABLECNT_MIN],
                        $menuopt[MenuoptBO::POST__MENUOPT_CHOOSEABLECNT_MAX],
                        $menuopt[MenuoptBO::POST__MENUOPT_COMMENT],
                        $IS_DISPLAY
                    );

                    /* menuoptDetail */
                    $optdetailArr = $menuopt['OPTDETAIL'];
                    foreach($optdetailArr as $optdetail)
                    {
                        $menuoptDetailBO->insertMenuoptDetailForInside(
                            $STORENO,
                            $menuno,
                            $menuoptno,
                            $optdetail[MenuoptDetailBO::POST__OPTDETAILNO],
                            $optdetail[MenuoptDetailBO::POST__OPTDETAIL_SORT],
                            $optdetail[MenuoptDetailBO::POST__OPTDETAIL_NAME],
                            $optdetail[MenuoptDetailBO::POST__OPTDETAIL_MENU],
                            $optdetail[MenuoptDetailBO::POST__OPTDETAIL_PRICE],
                            $optdetail[MenuoptDetailBO::POST__OPTDETAIL_PIC],
                            $optdetail[MenuoptDetailBO::POST__OPTDETAIL_COMMENT],
                            $IS_DISPLAY
                        );
                    }
                }

                /* menuRecommend */
                foreach($recommendArr as $recommend)
                {
                    $menuRecommendBO->insertMenuRecommendForInside(
                        $STORENO,
                        $menuno,
                        $recommend[MenuRecommendBO::POST__RECOMMENDNO],
                        $recommend[MenuRecommendBO::POST__RECOMMEND_SORT],
                        $recommend[MenuRecommendBO::POST__RECOMMEND_NAME],
                        $recommend[MenuRecommendBO::POST__RECOMMEND_MENU],
                        $recommend[MenuRecommendBO::POST__RECOMMEND_PRICE],
                        $recommend[MenuRecommendBO::POST__RECOMMEND_DISCOUNT],
                        $recommend[MenuRecommendBO::POST__RECOMMEND_CHOOSABLECNT_MAX],
                        $recommend[MenuRecommendBO::POST__RECOMMEND_COMMENT],
                        $recommend[MenuRecommendBO::POST__RECOMMEND_PIC],
                        $IS_DISPLAY,
                        $IS_STOCK,
                        $IS_DELETED
                    );
                }
                break;
            }
            case self::updateReorderpctForInside:
            {
                $query =
                "
                    update
                        menu m
                        left join reorderpct_result rot
                            on
                                rot.storeno = m.storeno and
                                rot.menuno = m.menuno
                    set
                        m.reorderpct = coalesce(rot.reorderpct, 0)
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        } /* end switch */
        return $menuno;
    }

    private function getNewIndex($storeno)
    {
        $query    = "select coalesce(max(menuno),0) cnt from menu where storeno = '$storeno'";
        $maxIndex = intval(GGsql::selectCnt($query)) + 1;
        return $maxIndex;
    }

} /* end class */
?>
