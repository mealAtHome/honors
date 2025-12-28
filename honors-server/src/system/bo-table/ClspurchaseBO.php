<?php

class ClspurchaseBO extends _CommonBO
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
    const FIELD__GRPNO                      = "grpno";                      /* (PK) char(30) */
    const FIELD__CLSNO                      = "clsno";                      /* (PK) char(14) */
    const FIELD__PURCHASEIDX                = "purchaseidx";                /* (PK) int */
    const FIELD__PRODUCTNAME                = "productname";                /* (  ) int */
    const FIELD__PRODUCTBILL                = "productbill";                /* (  ) int */
    const FIELD__REGDT                      = "regdt";                      /* (  ) datetime */

    /* ========================= */
    /* validation */
    /*
    */
    /* ========================= */
    static public function validProductbill($bill)
    {
        if(!is_numeric($bill))
            throw new GGexception("상품금액은 숫자만 입력가능합니다.");
        if(intval($bill) < 0)
            throw new GGexception("상품금액은 0원 이상만 입력가능합니다.");
    }

    static public function validProductname($name)
    {
        if(mb_strlen($name) > 100)
            throw new GGexception("상품명은 100자 이하로 입력가능합니다.");
        if(mb_strlen($name) == 0)
            throw new GGexception("상품명을 입력하여 주세요.");
    }

    /* ========================= */
    /* enum */
    /*
    */
    /* ========================= */

    /* ========================= */
    /* get consts */
    /* ========================= */
    static public function getConsts()
    {
        $arr = array();
        // $arr['clsstatusEdit']                   = self::CLSSTATUS__EDIT;        /* 일정상태 : 작성중 */
        // $arr['clsstatusIng']                    = self::CLSSTATUS__ING;         /* 일정상태 : 진행중 */
        // $arr['clsstatusEndcls']                 = self::CLSSTATUS__ENDCLS;      /* 일정상태 : 일정완료 */
        // $arr['clsstatusEndsettle']              = self::CLSSTATUS__ENDSETTLE;   /* 일정상태 : 정산완료 */
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
        extract(ClspurchaseBO::getConsts());
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
            case self::selectByClsno : { $from = "(select * from clspurchase where grpno = '$GRPNO' and clsno = '$CLSNO') t"; break; }
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
                t.purchaseidx asc
        ";
        $result = GGsql::select($query, $from, $options);
        return $result;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    // public function updateUsernoToTargetForInside($GRPNO, $USERNO, $TARGET) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteByClsnoForInside($GRPNO, $CLSNO) { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    const insertByArr = "insertByArr";
    const deleteByClsnoForInside = "deleteByClsnoForInside";
    protected function update($options, $option="")
    {
        /* get vars */
        extract(ClspurchaseBO::getConsts());
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* =============== */
        /* auth validation */
        /* =============== */
        switch($OPTION)
        {
            case self::insertByArr:
            {
                /* is grpmanager? */
                $ggAuth = GGauth::getInstance();
                $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true);
                break;
            }
        }

        /* =============== */
        /* sql execution */
        /* =============== */
        switch($OPTION)
        {
            case self::insertByArr:
            {
                /* delete before insert */
                $this->deleteByClsnoForInside($GRPNO, $CLSNO);

                /* process */
                $purchaseidx = 1;
                $arr = json_decode($ARR, true);
                foreach($arr as $dat)
                {
                    $PRODUCTNAME = $dat['PRODUCTNAME'];
                    $PRODUCTBILL = intval($dat['PRODUCTBILL']);

                    /* validation */
                    self::validProductname($PRODUCTNAME);
                    self::validProductbill($PRODUCTBILL);

                    /* insert */
                    $query =
                    "
                        insert into clspurchase
                        (
                              grpno
                            , clsno
                            , purchaseidx
                            , productname
                            , productbill
                            , regdt
                        )
                        values
                        (
                              '$GRPNO'
                            , '$CLSNO'
                            ,  $purchaseidx
                            , '$PRODUCTNAME'
                            ,  $PRODUCTBILL
                            ,  now()
                        )
                    ";
                    GGsql::exeQuery($query);
                }
                break;
            }
            case self::deleteByClsnoForInside:
            {
                $query = "delete from clspurchase where grpno = '$GRPNO' and clsno = '$CLSNO'";
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
