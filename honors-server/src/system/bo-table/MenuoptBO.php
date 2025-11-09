<?php

/* opt : menuopt */
class MenuoptBO extends _CommonBO
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

    const FIELD__STORENO                        = "storeno";                        /* int(11) */
    const FIELD__MENUNO                         = "menuno";                         /* int(11) */
    const FIELD__MENUOPTNO                      = "menuoptno";                      /* int(11) */
    const FIELD__MENUOPT_SORT                   = "menuopt_sort";                   /* int(11) */
    const FIELD__MENUOPT_NAME                   = "menuopt_name";                   /* char(255) */
    const FIELD__MENUOPT_CHOOSEABLECNT_MIN      = "menuopt_chooseablecnt_min";      /* int(11) */
    const FIELD__MENUOPT_CHOOSEABLECNT_MAX      = "menuopt_chooseablecnt_max";      /* int(11) */
    const FIELD__MENUOPT_COMMENT                = "menuopt_comment";                /* char(255) */
    const FIELD__IS_DISPLAY                     = "is_display";                     /* enum('y','n') */
    const FIELD__IS_STOCK                       = "is_stock";                       /* enum('y','n') */
    const FIELD__IS_DELETED                     = "is_deleted";                     /* enum('y','n') */

    const POST__STORENO                         = "STORENO";                        /* int(11) */
    const POST__MENUNO                          = "MENUNO";                         /* int(11) */
    const POST__MENUOPTNO                       = "MENUOPTNO";                      /* int(11) */
    const POST__MENUOPT_SORT                    = "MENUOPT_SORT";                   /* int(11) */
    const POST__MENUOPT_NAME                    = "MENUOPT_NAME";                   /* char(255) */
    const POST__MENUOPT_CHOOSEABLECNT_MIN       = "MENUOPT_CHOOSEABLECNT_MIN";      /* int(11) */
    const POST__MENUOPT_CHOOSEABLECNT_MAX       = "MENUOPT_CHOOSEABLECNT_MAX";      /* int(11) */
    const POST__MENUOPT_COMMENT                 = "MENUOPT_COMMENT";                /* char(255) */
    const POST__IS_DISPLAY                      = "IS_DISPLAY";                     /* enum('y','n') */
    const POST__IS_STOCK                        = "IS_STOCK";                       /* enum('y','n') */
    const POST__IS_DELETED                      = "IS_DELETED";                     /* enum('y','n') */

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
             opt.storeno
            ,opt.menuno
            ,opt.menuoptno
            ,opt.menuopt_sort
            ,opt.menuopt_name
            ,opt.menuopt_chooseablecnt_min
            ,opt.menuopt_chooseablecnt_max
            ,opt.menuopt_comment
            ,opt.is_display
            ,opt.is_stock
            ,opt.is_deleted
            ,opt.optdetailno_used
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
                            menuopt
                        where
                            storeno = '$STORENO' and
                            menuno = $MENUNO
                    ) opt
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
            order by
                 opt.storeno
                ,opt.menuno
                ,opt.menuopt_sort
        ";
        return GGsql::select($query, $from, $options);
    }

    /* ========================= */
    /* update public */
    /*
    */
    /* ========================= */
    public function deleteByStorenoMenunoForInside ($STORENO, $MENUNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function insertMenuoptForInside(
        $STORENO,
        $MENUNO,
        $MENUOPTNO,
        $MENUOPT_SORT,
        $MENUOPT_NAME,
        $MENUOPT_CHOOSEABLECNT_MIN,
        $MENUOPT_CHOOSEABLECNT_MAX,
        $MENUOPT_COMMENT,
        $IS_DISPLAY,
        $IS_DELETED=GGF::N
    )
    {
        $options = array(
            "STORENO"                   => $STORENO,
            "MENUNO"                    => $MENUNO,
            "MENUOPTNO"                 => $MENUOPTNO,
            "MENUOPT_SORT"              => $MENUOPT_SORT,
            "MENUOPT_NAME"              => $MENUOPT_NAME,
            "MENUOPT_CHOOSEABLECNT_MIN" => $MENUOPT_CHOOSEABLECNT_MIN,
            "MENUOPT_CHOOSEABLECNT_MAX" => $MENUOPT_CHOOSEABLECNT_MAX,
            "MENUOPT_COMMENT"           => $MENUOPT_COMMENT,
            "IS_DISPLAY"                => $IS_DISPLAY,
            "IS_DELETED"                => $IS_DELETED,
        );
        return $this->update($options, self::insertMenuoptForInside);
    }


    /* ========================= */
    /* update private */
    /*
    */
    /* ========================= */
    const insertMenuoptForInside = "insertMenuoptForInside";
    const deleteByStorenoMenunoForInside = "deleteByStorenoMenunoForInside";
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
            case self::insertMenuoptForInside:
            {
                $query =
                "
                    insert into menuopt
                    (
                        storeno
                        ,menuno
                        ,menuoptno
                        ,menuopt_sort
                        ,menuopt_name
                        ,menuopt_chooseablecnt_min
                        ,menuopt_chooseablecnt_max
                        ,menuopt_comment
                        ,is_display
                        ,is_deleted
                    )
                    values
                    (
                         '$STORENO'
                        , $MENUNO
                        , $MENUOPTNO
                        , $MENUOPT_SORT
                        ,'$MENUOPT_NAME'
                        , $MENUOPT_CHOOSEABLECNT_MIN
                        , $MENUOPT_CHOOSEABLECNT_MAX
                        ,'$MENUOPT_COMMENT'
                        ,'$IS_DISPLAY'
                        ,'$IS_DELETED'
                    )
                    on duplicate key update
                         menuopt_sort              =  $MENUOPT_SORT
                        ,menuopt_name              = '$MENUOPT_NAME'
                        ,menuopt_chooseablecnt_min =  $MENUOPT_CHOOSEABLECNT_MIN
                        ,menuopt_chooseablecnt_max =  $MENUOPT_CHOOSEABLECNT_MAX
                        ,menuopt_comment           = '$MENUOPT_COMMENT'
                        ,is_display                = '$IS_DISPLAY'
                        ,is_deleted                = '$IS_DELETED'
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::deleteByStorenoMenunoForInside:
            {
                $query =
                "
                    update
                        menuopt
                    set
                        is_deleted = 'y'
                    where
                        storeno = '$STORENO' and
                        menuno = $MENUNO
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
        }
        return true;
    }

} /* end class */
?>
