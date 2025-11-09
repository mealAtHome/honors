<?php

/* mc : menu_category */
class MenuCategoryBO extends _CommonBO
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
             mc.storeno
            ,mc.menuno
            ,mc.category_index
            ,cg.category_name
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByStorenoMenunoForInside: { $from = "(select * from menu_category where storeno = '$STORENO' and menuno  = $MENUNO) mc"; break; }
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
                left join category cg
                    on
                        mc.category_index = cg.category_index
            order by
                cg.category_index
        ";
        return GGsql::select($query, $from, $options);
    }

    /* ========================= */
    /* */
    /*
    */
    /* ========================= */
    public function deleteByStorenoMenunoForInside       ($STORENO, $MENUNO)                  { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function insertMenuCategoryForInside          ($STORENO, $MENUNO, $CATEGORY_INDEX) { return $this->update(get_defined_vars(), __FUNCTION__); }

    const deleteByStorenoMenunoForInside = "deleteByStorenoMenunoForInside";
    const insertMenuCategoryForInside = "insertMenuCategoryForInside";
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
            case self::insertMenuCategoryForInside:
            {
                $query = "insert into menu_category(storeno, menuno, category_index) values ('$STORENO', $MENUNO, $CATEGORY_INDEX)";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::deleteByStorenoMenunoForInside:
            {
                $query = "delete from menu_category where storeno = '$STORENO' and menuno = $MENUNO";
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
