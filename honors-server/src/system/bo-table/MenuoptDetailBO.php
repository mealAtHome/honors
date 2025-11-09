<?php

class MenuoptDetailBO extends _CommonBO
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

    const FIELD__STORENO              = "storeno";              /* int(11) (pk) */
    const FIELD__MENUNO               = "menuno";               /* int(11) (pk) */
    const FIELD__MENUOPTNO            = "menuoptno";            /* int(11) (pk) */
    const FIELD__OPTDETAILNO          = "optdetailno";          /* int(11) (pk) */
    const FIELD__OPTDETAIL_SORT       = "optdetail_sort";       /* int(11) */
    const FIELD__OPTDETAIL_NAME       = "optdetail_name";       /* char(50) */
    const FIELD__OPTDETAIL_MENU       = "optdetail_menu";       /* int(11) */
    const FIELD__OPTDETAIL_PRICE      = "optdetail_price";      /* int(11) */
    const FIELD__OPTDETAIL_PIC        = "optdetail_pic";        /* int(11) */
    const FIELD__OPTDETAIL_COMMENT    = "optdetail_comment";    /* char(100) */
    const FIELD__IS_DISPLAY           = "is_display";           /* enum('y','n') */
    const FIELD__IS_STOCK             = "is_stock";             /* enum('y','n') */
    const FIELD__IS_DELETED           = "is_deleted";           /* enum('y','n') */

    const POST__STORENO               = "STORENO";
    const POST__MENUNO                = "MENUNO";
    const POST__MENUOPTNO             = "MENUOPTNO";
    const POST__OPTDETAILNO           = "OPTDETAILNO";
    const POST__OPTDETAIL_SORT        = "OPTDETAIL_SORT";
    const POST__OPTDETAIL_NAME        = "OPTDETAIL_NAME";
    const POST__OPTDETAIL_MENU        = "OPTDETAIL_MENU";
    const POST__OPTDETAIL_PRICE       = "OPTDETAIL_PRICE";
    const POST__OPTDETAIL_PIC         = "OPTDETAIL_PIC";
    const POST__OPTDETAIL_COMMENT     = "OPTDETAIL_COMMENT";
    const POST__IS_DISPLAY            = "IS_DISPLAY";
    const POST__IS_STOCK              = "IS_STOCK";
    const POST__IS_DELETED            = "IS_DELETED";

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
              optd.storeno
            , optd.menuno
            , optd.menuoptno
            , optd.optdetailno
            , optd.optdetail_sort
            , optd.optdetail_name
            , optd.optdetail_menu
            , optd.optdetail_price
            , optd.optdetail_pic
            , optd.optdetail_comment
            , optd.is_display
            , optd.is_stock
            , optd.is_deleted
            ,    p.pic_name
            ,    p.pic_kind
            ,    p.pic_path
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
                            menuopt_detail
                        where
                            storeno  = '$STORENO' and
                            menuno   = $MENUNO
                    ) optd
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
                        optd.storeno   = p.storeno and
                        optd.optdetail_pic = p.pic_index
            order by
                optd.storeno
                ,optd.menuno
                ,optd.menuoptno
                ,optd.optdetail_sort
        ";
        return GGsql::select($query, $from, $options);
    }


    /* ========================= */
    /* */
    /*
    */
    /* ========================= */
    public function deleteByStorenoMenunoForInside ($STORENO, $MENUNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function insertMenuoptDetailForInside(
        $STORENO,
        $MENUNO,
        $MENUOPTNO,
        $OPTDETAILNO,
        $OPTDETAIL_SORT,
        $OPTDETAIL_NAME,
        $OPTDETAIL_MENU,
        $OPTDETAIL_PRICE,
        $OPTDETAIL_PIC,
        $OPTDETAIL_COMMENT,
        $IS_DISPLAY,
        $IS_DELETED=GGF::N
    )
    {
        return $this->update(get_defined_vars(), __FUNCTION__);
    }


    const deleteByStorenoMenunoForInside = "deleteByStorenoMenunoForInside";
    const insertMenuoptDetailForInside = "insertMenuoptDetailForInside";
    protected function update($options, $option="")
    {
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
            case self::insertMenuoptDetailForInside:
            {
                /* empty to null */
                if($OPTDETAIL_PIC  == "") $OPTDETAIL_PIC  = "null";
                if($OPTDETAIL_MENU == "") $OPTDETAIL_MENU = "null";

                /* query */
                $query =
                "
                    insert into menuopt_detail
                    (
                          storeno
                        , menuno
                        , menuoptno
                        , optdetailno
                        , optdetail_sort
                        , optdetail_name
                        , optdetail_menu
                        , optdetail_price
                        , optdetail_pic
                        , optdetail_comment
                        , is_display
                        , is_deleted
                    )
                    values
                    (
                          '$STORENO'
                        ,  $MENUNO
                        ,  $MENUOPTNO
                        ,  $OPTDETAILNO
                        ,  $OPTDETAIL_SORT
                        , '$OPTDETAIL_NAME'
                        ,  $OPTDETAIL_MENU
                        ,  $OPTDETAIL_PRICE
                        ,  $OPTDETAIL_PIC
                        , '$OPTDETAIL_COMMENT'
                        , '$IS_DISPLAY'
                        , '$IS_DELETED'
                    )
                    on duplicate key update
                          optdetail_sort    =  $OPTDETAIL_SORT
                        , optdetail_name    = '$OPTDETAIL_NAME'
                        , optdetail_menu    =  $OPTDETAIL_MENU
                        , optdetail_price   =  $OPTDETAIL_PRICE
                        , optdetail_pic     =  $OPTDETAIL_PIC
                        , optdetail_comment = '$OPTDETAIL_COMMENT'
                        , is_display        = '$IS_DISPLAY'
                        , is_deleted        = '$IS_DELETED'
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::deleteByStorenoMenunoForInside:
            {
                $query =
                "
                    update
                        menuopt_detail
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
