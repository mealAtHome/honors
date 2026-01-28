<?php

class ClspurchasehistBO extends _CommonBO
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

    /* ========================= */
    /* field */
    /*
    */
    /* ========================= */
    const FIELD__GRPNO              = "grpno";              /* (PK) char(30) */
    const FIELD__CLSNO              = "clsno";              /* (PK) char(14) */
    const FIELD__HISTNO             = "histno";             /* (PK) int */
    const FIELD__HISTTYPE           = "histtype";           /* (  ) enum('insert','update','delete') */
    const FIELD__PURCHASEIDX        = "purchaseidx";        /* (  ) int */
    const FIELD__PRODUCTNAME        = "productname";        /* (  ) varchar(50) */
    const FIELD__PRODUCTBILL        = "productbill";        /* (  ) int */
    const FIELD__REGDT              = "regdt";              /* (  ) datetime */

    /* ========================= */
    /* enum */
    /*
    */
    /* ========================= */
    const HISTTYPE__INSERT          = "insert";             /* 이력유형 : 등록 */
    const HISTTYPE__UPDATE          = "update";             /* 이력유형 : 수정 */
    const HISTTYPE__DELETE          = "delete";             /* 이력유형 : 삭제 */

    /* ========================= */
    /* validation */
    /*
    */
    /* ========================= */

    /* ========================= */
    /* get consts */
    /* ========================= */
    static public function getConsts()
    {
        $arr = array();
        $arr['histtypeInsert'] = self::HISTTYPE__INSERT;      /* 이력유형 : 등록 */
        $arr['histtypeUpdate'] = self::HISTTYPE__UPDATE;      /* 이력유형 : 수정 */
        $arr['histtypeDelete'] = self::HISTTYPE__DELETE;      /* 이력유형 : 삭제 */
        return $arr;
    }

    /* ========================= */
    /* select > sub > sub */
    /* ========================= */
    // public function selectB($GRPNO, $PURCHASECLSNO, $USERNO) { return Common::getDataOne($this->selectByPkForInside($GRPNO, $PURCHASECLSNO, $USERNO)); }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    // public function selectBy($GRPNO, $PURCHASECLSNO, $USERNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /* ========================= */
    const selectByClsno = "selectByClsno";
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        extract(ClspurchasehistBO::getConsts());
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
            null as head
            , t.grpno
            , t.clsno
            , t.histno
            , t.histtype
            , t.purchaseidx
            , t.productname
            , t.productbill
            , t.regdt
        ";

        /* --------------- */
        /* validation */
        /* --------------- */
        // switch($OPTION)
        // {
        //     case self::selectNotDepositedByUsernoForMng:
        //     case self::selectNotDepositedAllByGrpnoForMng:
        //     case self::selectMemberdepositflgYesByGrpnoForMng:
        //     case self::selectNotDepositedByGrpnoForMng:
        //     {
        //         /* is grpmanager? */
        //         $ggAuth = GGauth::getInstance();
        //         $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true);
        //         break;
        //     }
        // }

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByClsno : { $from = "(select * from clspurchasehist where grpno = '$GRPNO' and clsno = '$CLSNO') t"; break; }
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
                t.grpno,
                t.clsno,
                t.histno asc
        ";
        $result = GGsql::select($query, $from, $options);
        return $result;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    public function copyFromClspurchaseForInside($GRPNO, $CLSNO, $PURCHASEIDX, $HISTTYPE) { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    const copyFromClspurchaseForInside = "copyFromClspurchaseForInside";
    protected function update($options, $option="")
    {
        /* get vars */
        extract(ClspurchasehistBO::getConsts());
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* =============== */
        /* auth validation */
        /* =============== */
        // switch($OPTION)
        // {
        //     case self::insertByArr:
        //     {
        //         /* is grpmanager? */
        //         $ggAuth = GGauth::getInstance();
        //         $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true);
        //         break;
        //     }
        // }

        /* =============== */
        /* sql execution */
        /* =============== */
        switch($OPTION)
        {
            case self::copyFromClspurchaseForInside:
            {
                $query =
                "
                    insert into clspurchasehist
                    (
                          grpno
                        , clsno
                        , histno
                        , histtype
                        , purchaseidx
                        , productname
                        , productbill
                        , regdt
                    )
                    select
                          grpno
                        , clsno
                        , ifnull((select max(histno) from clspurchasehist where grpno = '$GRPNO' and clsno = '$CLSNO'), 0) + 1 as histno
                        , '$HISTTYPE' as histtype
                        , purchaseidx
                        , productname
                        , productbill
                        , now() as regdt
                    from
                    (
                        select
                              grpno
                            , clsno
                            , purchaseidx
                            , productname
                            , productbill
                        from
                            clspurchase
                        where
                              grpno = '$GRPNO' and
                              clsno = '$CLSNO' and
                              purchaseidx = '$PURCHASEIDX'
                    ) t1
                ";
                GGsql::exeQuery($query);
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }
    }

} /* end class */
?>
