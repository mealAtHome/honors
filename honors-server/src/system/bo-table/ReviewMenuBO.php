<?php

class ReviewMenuBO extends _CommonBO
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

    const FIELD__STORENO       = "storeno";
    const FIELD__ORDERNO       = "orderno";
    const FIELD__CART_INDEX    = "cart_index";
    const FIELD__MENUNO        = "menuno";
    const FIELD__REVIEW_SCORE  = "review_score";

    /* ========================= */
    /*  */
    /*
        ■
        default
            - [*] SOTRE_INDEX
            - [*] ORDERNO


    */
    /* ========================= */
    public function selectByOrdernoForInside ($STORENO, $ORDERNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    const selectByOrdernoForInside = "selectByOrdernoForInside";
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
             rvm.storeno
            ,rvm.orderno
            ,rvm.cart_index
            ,rvm.menuno
            ,rvm.review_score
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByOrdernoForInside:
            {
                $from = "(select * from review_menu where storeno = '$STORENO' and orderno = '$ORDERNO') rvm";
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
                 rvm.storeno
                ,rvm.orderno
                ,rvm.cart_index
        ";
        return GGsql::select($query, $from, $options);
    }

    /* ==================== */
    /* 업데이트 */
    /*
        ■ 옵션
        regist : 등록
    */
    /* ==================== */
    public function insertForInside($STORENO, $ORDERNO, $CART_INDEX, $MENUNO, $REVIEW_SCORE) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteByOrdernoForInside($STORENO, $ORDERNO) { return $this->update(get_defined_vars(), __FUNCTION__); }

    const insertForInside = "insertForInside";
    const deleteByOrdernoForInside = "deleteByOrdernoForInside";
    protected function update($options, $option="")
    {
        /* get vars */
        extract($options);

        /* orderride option */
        if($option != "")
            $OPTION = $option;

        /* sql execution */
        switch($OPTION)
        {
            case self::insertForInside:
            {
                /* 이미 후기가 완료되지 않았는지 확인 */

                /* insert */
                $query =
                "
                    insert into review_menu
                    (
                          storeno
                        , orderno
                        , cart_index
                        , menuno
                        , review_score
                    )
                    values
                    (
                          '$STORENO'
                        , '$ORDERNO'
                        ,  $CART_INDEX
                        ,  $MENUNO
                        ,  $REVIEW_SCORE
                    )
                    on duplicate key update
                      review_score = $REVIEW_SCORE
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::deleteByOrdernoForInside:
            {
                $query =
                "
                    delete from
                        review_menu
                    where
                        storeno = '$STORENO' and
                        orderno = '$ORDERNO'
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
