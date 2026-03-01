<?php

class ClslineupaBO extends _CommonBO
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
    function setBO()
    {
        $arr = array();
        return $arr;
    }

    /* ========================= */
    /* fields */
    /*
    */
    /* ========================= */
    const FIELD__GRPNO      = "grpno";      /* (pk) char(30)     */
    const FIELD__CLSNO      = "clsno";      /* (pk) char(14)     */
    const FIELD__LINEUPIDX  = "lineupidx";  /* (pk) int          */
    const FIELD__LINEUPNAME = "lineupname"; /* (  ) varchar(20)  */

    static public function getConsts()
    {
        $arr = array();
        return $arr;
    }


    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByClsnoForInside($GRPNO, $CLSNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /*
    */
    /* ========================= */
    const selectByClsno           = "selectByClsno";
    const selectByClsnoForInside  = "selectByClsnoForInside";
    protected function select($options, $option="")
    {
        /* vars */
        extract(self::getConsts());
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* --------------- */
        /* sql body */
        /* --------------- */
        $select =
        "
              t.grpno
            , t.clsno
            , t.lineupidx
            , t.lineupname
        ";
        $from = "";
        $orderby =
        "
              t.grpno
            , t.clsno
            , t.lineupidx
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByClsno         : { $from = "(select * from clslineupa where grpno = '$GRPNO' and clsno = '$CLSNO') t"; break; }
            case self::selectByClsnoForInside: { $from = "(select * from clslineupa where grpno = '$GRPNO' and clsno = '$CLSNO') t"; break; }
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
                $orderby
        ";
        $rslt = GGsql::select($query, $from, $options);
        return $rslt;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    public function deleteByClsnoForInside($GRPNO, $CLSNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function insertOneForInside($GRPNO, $CLSNO, $LINEUPIDX, $LINEUPNAME) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function copyFromClsnoForInside($GRPNO, $CLSNO, $CLSNONEW) { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    const deleteByClsnoForInside  = "deleteByClsnoForInside";
    const insertOneForInside      = "insertOneForInside";
    const copyFromClsnoForInside  = "copyFromClsnoForInside";
    protected function update($options, $option="")
    {
        /* vars */
        $rslt = Common::getReturn();
        extract($this->setBO());
        extract(self::getConsts());
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* =============== */
        /* process */
        /* =============== */
        switch($OPTION)
        {
            case self::deleteByClsnoForInside:
            {
                $query = "delete from clslineupa where grpno = '$GRPNO' and clsno = '$CLSNO'";
                GGsql::exeQuery($query);
                break;
            }
            case self::insertOneForInside:
            {
                $query =
                "
                    insert into clslineupa
                    (
                          grpno
                        , clsno
                        , lineupidx
                        , lineupname
                    )
                    values
                    (
                          '$GRPNO'
                        , '$CLSNO'
                        ,  $LINEUPIDX
                        , '$LINEUPNAME'
                    )
                ";
                GGsql::exeQuery($query);
                break;
            }
            case self::copyFromClsnoForInside:
            {
                $query =
                "
                    insert into clslineupa
                    (
                          grpno
                        , clsno
                        , lineupidx
                        , lineupname
                    )
                    select
                           grpno
                        , '$CLSNONEW'
                        ,  lineupidx
                        ,  lineupname
                    from
                        clslineupa
                    where
                        grpno = '$GRPNO' and
                        clsno = '$CLSNO'
                ";
                GGsql::exeQuery($query);
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }
        return $rslt;
    }

} /* end class */
?>
