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
    function __construct()
    {
    }
    function setBO()
    {
    }

    /* ========================= */
    /* field */
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


    /* ========================= */
    /* select options */
    /* ========================= */
    static public function getConsts()
    {
        $arr = array();
        // $arr['clstypeLineup1']                  = self::CLSTYPE__LINEUP1;       /* 일반참가 */
        // $arr['clstypeLineup2']                  = self::CLSTYPE__LINEUP2;       /* 라인업1시합 */
        // $arr['clstypeLineup4']                  = self::CLSTYPE__LINEUP4;       /* 라인업2시합 */
        // $arr['clsstatusEdit']                   = self::CLSSTATUS__EDIT;        /* 일정상태 : 작성중 */
        // $arr['clsstatusIng']                    = self::CLSSTATUS__ING;         /* 일정상태 : 진행중 */
        // $arr['clsstatusEndcls']                 = self::CLSSTATUS__ENDCLS;      /* 일정상태 : 일정완료 */
        // $arr['clsstatusEndsettle']              = self::CLSSTATUS__ENDSETTLE;   /* 일정상태 : 정산완료 */
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
    /* ========================= */
    const selectByPkForInside = "selectByPkForInside";
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        $this->setBO();
        extract(ClzcancelBO::getConsts());
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

        /* get vars */
        $this->setBO();
        extract(ClzcancelBO::getConsts());
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* =============== */
        /* validation (common) */
        /* =============== */
        switch($OPTION)
        {
            case self::insertForInside:
            {
                if(Common::isEmpty(trim($GRPNO))             ) { throw new GGexception("입력되지 않은 항목이 있습니다."); }
                if(Common::isEmpty(trim($CLSNO))             ) { throw new GGexception("입력되지 않은 항목이 있습니다."); }
                if(Common::isEmpty(trim($CLSCANCELREASON))   ) { throw new GGexception("입력되지 않은 항목이 있습니다. (취소사유)"); }
                break;
            }
        }

        /* =============== */
        /* sql execution */
        /* =============== */
        switch($OPTION)
        {
            case self::insertForInside:
            {
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
