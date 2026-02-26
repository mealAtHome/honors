<?php

/* grpfnc_loss, gfls */
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
    function setBO()
    {
        GGnavi::getGrpfncaBO();
        $arr = array();
        $arr['ggAuth'] = GGauth::getInstance();
        $arr['grpfncaBO'] = GrpfncaBO::getInstance();
        return $arr;
    }


    /* ========================= */
    /* fields */
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
    static public function getConsts()
    {
        $arr = array();
        // $arr['key'] = "value";
        return $arr;
    }

    /* ========================= */
    /* select > sub > sub */
    /* ========================= */
    // public function getByPk($GRPNO) { return GGsql::selectOne("select * from grp where grpno = '$GRPNO'"); }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByPkForInside ($GRPNO, $LOSSIDX) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /*
    */
    /* ========================= */
    const selectByPk = "selectByPk";
    const selectByPkForInside = "selectByPkForInside";
    const selectByGrpnoPagenum = "selectByGrpnoPagenum";
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        // extract(GrpMemberBO::getConsts());
        extract($this->setBO());
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
            case self::selectByPkForInside   : { $from = "(select * from grpfnc_loss where grpno = '$GRPNO' and lossidx = $LOSSIDX) t"; break; }
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
        $rslt = GGsql::select($query, $from, $options);
        return $rslt;
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
    const deleteByPk = "deleteByPk";
    /* const updateBaccnodefaultForInside = "updateBaccnodefaultForInside"; */
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

        /* process */
        switch($OPTION)
        {
            case self::insertFromPage:
            {
                /* check auth */
                $ggAuth->hasGrpmfinauth($GRPNO, $EXECUTOR, true);

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
                $rslt = GGsql::exeQuery($query);

                /* recal */
                $grpfncBO->recalGrpfncLosstotalByPkForInside($GRPNO);
                break;
            }
            case self::deleteByPk:
            {
                /* validation */
                if(Common::isEmpty($GRPNO))    { throw new GGexception("시스템 오류입니다."); }
                if(Common::isEmpty($LOSSIDX))  { throw new GGexception("시스템 오류입니다."); }

                /* validation logic */
                $isAdmin = $ggAuth->isAdmin($EXECUTOR, false);
                $hasAuth = $ggAuth->hasGrpmfinauth($GRPNO, $EXECUTOR, true);
                if(!$isAdmin)
                {
                    $regdt = Common::getDataOneField($this->selectByPkForInside($GRPNO, $LOSSIDX), self::FIELD__REGDT);
                    if(GGdate::diffHour($regdt) > 72)
                        throw new GGexception("손실내역은 등록 후 72시간까지만 삭제할 수 있습니다.");
                }

                /* process */
                $query = "delete from grpfnc_loss where grpno = '$GRPNO' and lossidx = $LOSSIDX";
                $rslt = GGsql::exeQuery($query);

                /* recal */
                $grpfncBO->recalGrpfncLosstotalByPkForInside($GRPNO);
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }



        return $rslt;
    }

}
?>
