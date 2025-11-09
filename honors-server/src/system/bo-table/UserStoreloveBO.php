<?php

class UserStoreloveBO extends _CommonBO
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
    /* 조회 */
    /* ========================= */
    public function selectByUsernoForInside($USERNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    const selectByUsernoForInside = "selectByUsernoForInside";
    const selectByExecutor = "selectByExecutor";
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
             usl.userno
            ,usl.storeno
            ,usl.storeloveflg
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByExecutor        : { $from = "(select * from user_storelove where userno = '$EXECUTOR') usl"; break; }
            case self::selectByUsernoForInside : { $from = "(select * from user_storelove where userno = '$USERNO') usl"; break; }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }

        /* --------------- */
        /* execute query */
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

    /* ==================== */
    /* 갱신 */
    /* ==================== */
    const insert = "insert";
    const delete = "delete";
    function update($options, $option="")
    {
        extract($options);
        switch($OPTION)
        {
            case self::insert:
            {
                $query =
                "
                    insert into user_storelove
                    (
                        userno
                        ,storeno
                        ,storeloveflg
                    )
                    values
                    (
                          '$EXECUTOR'
                        ,  $STORENO
                        , 'y'
                    )
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::delete:
            {
                $query =
                "
                    delete from
                        user_storelove
                    where
                        userno = '$EXECUTOR' and
                        storeno = '$STORENO'
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
