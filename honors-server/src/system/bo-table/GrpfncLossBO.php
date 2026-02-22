<?php

class GrpfncLossBO extends _CommonBO
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
    function setBO() {
        // GGnavi::getGrpMemberBO();
        $arr = array();
        // $arr['grpMemberBO'] = GrpMemberBO::getInstance();
        return $arr;
    }

    /* ========================= */
    /* field */
    /*
    */
    /* ========================= */
    const FIELD__GRPNO              = "grpno";              /* (PK) char(30) */
    const FIELD__LOSSIDX            = "lossidx";            /* (PK) int */
    const FIELD__LOSSITEM           = "lossitem";           /* (  ) varchar(50) */
    const FIELD__LOSSCOST           = "losscost";           /* (  ) int */
    const FIELD__LOSSCOMMENT        = "losscomment";        /* (  ) varchar(255) */
    const FIELD__REGDT              = "regdt";              /* (  ) datetime */

    /* ========================= */
    /* enum */
    /*
    */
    /* ========================= */

    /* ========================= */
    /* select > sub > sub */
    /* ========================= */
    // public function getByPk($GRPNO) { return GGsql::selectOne("select * from grp where grpno = '$GRPNO'"); }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    // public function selectByPkForInside ($GRPNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /* ========================= */
    const selectByPk = "selectByPk";
    const selectByGrpnoPagenum = "selectByGrpnoPagenum";
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        extract($this->setBO());
        extract(GrpMemberBO::getConsts());
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
            , t.lossidx
            , t.lossitem
            , t.losscost
            , t.losscomment
            , t.regdt
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPk            : { $from = "(select * from grpfnc_loss where grpno = '$GRPNO' and lossidx = $LOSSIDX) t"; break; }
            case self::selectByGrpnoPagenum  : { $from = "(select * from grpfnc_loss where grpno = '$GRPNO') t"; break; }
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
                  t.grpno asc
                , t.lossidx desc
        ";
        $result = GGsql::select($query, $from, $options);
        return $result;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    /* public function changeStoreStatus($STORENO, $STORE_STATUS)     { return $this->update(get_defined_vars(), __FUNCTION__); } */
    /* public function updateBaccnodefaultForInside($GRPNO, $BACCNODEFAULT) { return $this->update(get_defined_vars(), __FUNCTION__); } */

    /* ========================= */
    /* update */
    /* ========================= */
    const insertFromPage = "insertFromPage";
    /* const updateBaccnodefaultForInside = "updateBaccnodefaultForInside"; */
    protected function update($options, $option="")
    {
        /* set BO */
        extract($this->setBO());

        /* vars */
        $ggAuth = GGauth::getInstance();
        $data = null;

        /* get vars */
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* sql execution */
        switch($OPTION)
        {
            case self::insertFromPage:
            {
                /* validation */
                if(Common::isEmpty($GRPNO))        { throw new GGexception("시스템 오류입니다."); }
                if(Common::isEmpty($LOSSITEM))     { throw new GGexception("손실품목이 공란입니다."); }
                if(Common::isEmpty($LOSSCOST))     { throw new GGexception("손실금액이 공란입니다."); }

                $LOSSCOST = intval($LOSSCOST) * -1; /* 시스템에서 자동으로 음수처리 */
                if($LOSSCOST > 0) { throw new GGexception("손실금액은 0 이하이어야 합니다. 시스템에서 자동으로 음수처리합니다."); }

                /* process */
                $query =
                "
                    insert into grpfnc_loss
                    (
                          grpno
                        , lossidx
                        , lossitem
                        , losscost
                        , losscomment
                        , regdt
                    )
                    select
                          '$GRPNO'
                        ,  (select ifnull(max(lossidx), 0) + 1 from grpfnc_loss where grpno = '$GRPNO')
                        , '$LOSSITEM'
                        ,  $LOSSCOST
                        , '$LOSSCOMMENT'
                        , now()
                    from
                        dual
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }
        return $data;
    }

}
?>
