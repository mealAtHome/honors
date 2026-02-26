<?php

class ClzcancelBO extends _CommonBO
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
        $arr['ggAuth'] = GGauth::getInstance();
        return $arr;
    }

    /* ========================= */
    /* fields */
    /*
    */
    /* ========================= */
    const FIELD__GRPNO              = "grpno";              /* (PK) char(30) */
    const FIELD__CLSNO              = "clsno";              /* (PK) char(14) */
    const FIELD__CLSCANCELREASON    = "clscancelreason";    /* (  ) varchar(50) */
    const FIELD__REGDT              = "regdt";              /* (  ) datetime */

    /* ========================= */
    /* enum */
    /*
    */
    /* ========================= */
    static public function getConsts()
    {
        $arr = array();
        // $arr['key'] = "value";
        return $arr;
    }

    /* ========================= */
    /* select > sub > sub */
    /* ========================= */
    public function getByPk($GRPNO, $CLSNO) { return Common::getDataOne($this->selectByPkForInside($GRPNO, $CLSNO)); }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByPkForInside($GRPNO, $CLSNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /*
    */
    /* ========================= */
    const selectByPkForInside = "selectByPkForInside";
    protected function select($options, $option="")
    {
        /* vars */
        $ggAuth = GGauth::getInstance();
        extract(self::getConsts());
        extract($options);

        /* override option */
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
              t.grpno
            , t.clsno
            , t.clscancelreason
            , t.regdt
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPkForInside: { $from = "(select * from clzcancel where grpno = '$GRPNO' and clsno = '$CLSNO') t"; break; }
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
        ";
        $rslt = GGsql::select($query, $from, $options);
        return $rslt;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    public function insertForInside($GRPNO, $CLSNO, $CLSCANCELREASON) { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    const insertForInside = "insertForInside";
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
            case self::insertForInside:
            {
                /* validation */
                if(Common::isEmpty(trim($GRPNO))             ) { throw new GGexception("입력되지 않은 항목이 있습니다."); }
                if(Common::isEmpty(trim($CLSNO))             ) { throw new GGexception("입력되지 않은 항목이 있습니다."); }
                if(Common::isEmpty(trim($CLSCANCELREASON))   ) { throw new GGexception("입력되지 않은 항목이 있습니다. (취소사유)"); }

                $query =
                "
                    insert into clzcancel
                    (
                          grpno
                        , clsno
                        , clscancelreason
                        , regdt
                    )
                    values
                    (
                          '$GRPNO'
                        , '$CLSNO'
                        , '$CLSCANCELREASON'
                        ,  now()
                    )
                ";
                GGsql::exeQuery($query);
                $rslt[GGF::DATA] = $CLSNO;
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
