<?php

/* grpfnc_sponsorship, gfsp */
class GrpfncSponsorshipBO extends _CommonBO
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
    const FIELD__SPONIDX            = "sponidx";            /* (PK) int */
    const FIELD__SPONUSERNO         = "sponuserno";         /* (  ) varchar(30) */
    const FIELD__SPONUSERNAME       = "sponusername";       /* (  ) varchar(30) */
    const FIELD__SPONTYPE           = "spontype";           /* (  ) enum('thing','money') */
    const FIELD__SPONITEM           = "sponitem";           /* (  ) varchar(50) */
    const FIELD__SPONCOST           = "sponcost";           /* (  ) int */
    const FIELD__SPONCOMMENT        = "sponcomment";        /* (  ) varchar(255) */
    const FIELD__REGDT              = "regdt";              /* (  ) datetime */

    /* ========================= */
    /* enum */
    /*
    */
    /* ========================= */
    const SPONTYPE__THING = "thing"; /* 단발형 */
    const SPONTYPE__MONEY = "money"; /* 금전형 */

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
    // public function selectByPkForInside ($GRPNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /*
    */
    /* ========================= */
    const selectByPk = "selectByPk";
    const selectByGrpnoPagenum = "selectByGrpnoPagenum";
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
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
            , t.sponidx
            , t.sponuserno
            , t.sponusername
            , t.spontype
            , t.sponitem
            , t.sponcost
            , t.sponcomment
            , t.regdt
            , u.name username
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPk            : { $from = "(select * from grpfnc_sponsorship where grpno = '$GRPNO' and sponidx = $SPONIDX) t"; break; }
            case self::selectByGrpnoPagenum  : { $from = "(select * from grpfnc_sponsorship where grpno = '$GRPNO') t"; break; }
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
                left join user u
                    on
                        u.userno = t.sponuserno
            order by
                  t.grpno asc
                , t.sponidx desc
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
    const deleteByPk = "deleteByPk";
    /* const updateBaccnodefaultForInside = "updateBaccnodefaultForInside"; */
    protected function update($options, $option="")
    {
        /* vars */
        $result = Common::getReturn();
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
                /* validation */
                if(Common::isEmpty($GRPNO))                                              { throw new GGexception("시스템 오류입니다."); }
                if(Common::isEmpty($SPONSORTYPE))                                        { throw new GGexception("시스템 오류입니다."); }
                if(Common::isEmpty($SPONTYPE))                                           { throw new GGexception("시스템 오류입니다."); }
                if($SPONSORTYPE == "user" && Common::isEmpty($SPONUSERNO))               { throw new GGexception("찬조자가 선택되지 않았습니다."); }
                if($SPONSORTYPE == "name" && Common::isEmpty($SPONUSERNAME))             { throw new GGexception("찬조자 이름이 입력되지 않았습니다."); }
                if($SPONTYPE == self::SPONTYPE__THING && Common::isEmpty($SPONITEM))     { throw new GGexception("찬조품목이 공란입니다."); }
                if($SPONTYPE == self::SPONTYPE__MONEY && Common::isEmpty($SPONCOST))     { throw new GGexception("찬조금액이 공란입니다."); }

                /* process */
                $query =
                "
                    insert into grpfnc_sponsorship
                    (
                          grpno
                        , sponidx
                        , sponuserno
                        , sponusername
                        , spontype
                        , sponitem
                        , sponcost
                        , sponcomment
                        , regdt
                    )
                    select
                          '$GRPNO'
                        ,  (select ifnull(max(sponidx), 0) + 1 from grpfnc_sponsorship where grpno = '$GRPNO')
                        , '$SPONUSERNO'
                        , '$SPONUSERNAME'
                        , '$SPONTYPE'
                        , '$SPONITEM'
                        ,  $SPONCOST
                        , '$SPONCOMMENT'
                        , now()
                    from
                        dual
                ";
                $result = GGsql::exeQuery($query);

                /* recal */
                $grpfncBO->recalGrpfncSponsorshiptotalByPkForInside($GRPNO);
                break;
            }
            case self::deleteByPk:
            {
                /* validation */
                if(Common::isEmpty($GRPNO))    { throw new GGexception("시스템 오류입니다."); }
                if(Common::isEmpty($SPONIDX))  { throw new GGexception("시스템 오류입니다."); }

                /* validation logic */
                $isAdmin = $ggAuth->isAdmin($EXECUTOR, false);
                $hasAuth = $ggAuth->hasGrpmfinauth($GRPNO, $EXECUTOR, true);
                if(!$isAdmin)
                {
                    $regdt = Common::getDataOneField($this->selectByPkForInside($GRPNO, $SPONIDX), self::FIELD__REGDT);
                    if(GGdate::diffHour($regdt) > 72)
                        throw new GGexception("찬조내역은 등록 후 72시간까지만 삭제할 수 있습니다.");
                }

                /* process */
                $query = "delete from grpfnc_sponsorship where grpno = '$GRPNO' and sponidx = $SPONIDX";
                $result = GGsql::exeQuery($query);

                /* recal */
                $grpfncBO->recalGrpfncSponsorshiptotalByPkForInside($GRPNO);
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }
        return $result;
    }

}
?>
