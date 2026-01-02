<?php

class ClssettletmpBO extends _CommonBO
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
    const FIELD__USERNO                     = "userno";                     /* (PK) char(30) */
    const FIELD__BILLSTANDARD               = "billstandard";               /* (  ) int */
    const FIELD__BILLADJUSTMENT             = "billadjustment";             /* (  ) int */
    const FIELD__BILLPOINTED                = "billpointed";                /* (  ) int */
    const FIELD__BILLFINAL                  = "billfinal";                  /* (  ) int */
    const FIELD__BILLMEMO                   = "billmemo";                   /* (  ) varchar(100) */
    const FIELD__MEMBERDEPOSITFLG           = "memberdepositflg";           /* (  ) enum('y','n') */
    const FIELD__MEMBERDEPOSITFLGDT         = "memberdepositflgdt";         /* (  ) datetime */
    const FIELD__MANAGERDEPOSITFLG          = "managerdepositflg";          /* (  ) enum('y','n') */
    const FIELD__MANAGERDEPOSITFLGDT        = "managerdepositflgdt";        /* (  ) datetime */
    const FIELD__REGDT                      = "regdt";                      /* (  ) datetime */

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
        // $arr['clsstatusEdit']                   = self::CLSSTATUS__EDIT;        /* 일정상태 : 작성중 */
        // $arr['clsstatusIng']                    = self::CLSSTATUS__ING;         /* 일정상태 : 진행중 */
        // $arr['clsstatusEnd']                 = self::CLSSTATUS__END;      /* 일정상태 : 일정완료 */
        return $arr;
    }

    /* ========================= */
    /* select > sub > sub */
    /* ========================= */
    // public function getByPk($GRPNO, $CLSNO, $USERNO) { return Common::getDataOne($this->selectByPkForInside($GRPNO, $CLSNO, $USERNO)); }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    // public function selectByPkForInside($GRPNO, $CLSNO, $USERNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /* ========================= */
    const selectByClsno = "selectByClsno";
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        extract(self::getConsts());
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
            , t.userno
            , t.billstandard
            , t.billadjustment
            , t.billpointed
            , t.billfinal
            , t.billmemo
            , t.memberdepositflg
            , t.memberdepositflgdt
            , t.managerdepositflg
            , t.managerdepositflgdt
            , t.regdt
            , u.name as username
            , u.id as userid
            , cls.clstitle
            , cls.clsstartdt
            , cls.clsclosedt
            , cls.clsground
            , grpu.id as grpmanagerid
            , grp.grpname
            , bank.bankname
            , bacc.baccacct
            , bacc.baccname
            , grpm.point as grpm_point
        ";

        /* --------------- */
        /* validation */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByClsno:
            {
                /* is grpmanager? */
                GGauth::getInstance()->isGrpmanager($GRPNO, $EXECUTOR, true);
                break;
            }
        }

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByClsno : { $from = "(select * from clssettletmp where grpno = '$GRPNO' and clsno = '$CLSNO') t"; break; }
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
                        t.userno = u.userno
                left join cls
                    on
                        t.grpno = cls.grpno and
                        t.clsno = cls.clsno
                left join grp
                    on
                        t.grpno = grp.grpno
                left join user grpu
                    on
                        grp.grpmanager = grpu.userno
                left join bankaccount bacc
                    on
                        bacc.bacctype = 'grp' and
                        bacc.bacckey = grp.grpno and
                        bacc.baccno = grp.baccnodefault
                left join _bank bank
                    on
                        bank.bankcode = bacc.bacccode
                left join grp_member grpm
                    on
                        grpm.grpno = t.grpno and
                        grpm.userno = t.userno
            order by
                  t.grpno
                , t.clsno
                , u.name
        ";
        $result = GGsql::select($query, $from, $options);
        return $result;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    // public function insertByArr($GRPNO, $CLSNO, $EXECUTOR, $ARR) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteByClsnoForInside($GRPNO, $CLSNO, $EXECUTOR) { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    const insertByArr = "insertByArr";
    const deleteByClsno = "deleteByClsno";
    const deleteByClsnoForInside = "deleteByClsnoForInside";
    protected function update($options, $option="")
    {
        /* get vars */
        extract(self::getConsts());
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* =============== */
        /* sql execution */
        /* =============== */
        switch($OPTION)
        {
            case self::insertByArr:
            {
                /* TODO : is finance charge */
                /* validation */
                GGauth::getInstance()->isGrpmanager($GRPNO, $EXECUTOR, true);

                /* delete before insert */
                $this->deleteByClsnoForInside($GRPNO, $CLSNO, $EXECUTOR);

                /* process */
                $arr = json_decode($ARR, true);
                foreach($arr as $dat)
                {
                    /* vars */
                    $USERNO         = $dat['USERNO'];
                    $BILLSTANDARD   = intval($dat['BILLSTANDARD']);
                    $BILLADJUSTMENT = intval($dat['BILLADJUSTMENT']);
                    $BILLPOINTED    = intval($dat['BILLPOINTED']);
                    $BILLFINAL      = intval($dat['BILLFINAL']);
                    $BILLMEMO       = $dat['BILLMEMO'];

                    /* if billfinal == 0 ?, deposit complete */
                    $memberdepositflg     = ($BILLFINAL == 0) ? "'y'"   : "'n'";
                    $memberdepositflgdt   = ($BILLFINAL == 0) ? "now()" : "null";
                    $managerdepositflg    = ($BILLFINAL == 0) ? "'y'"   : "'n'";
                    $managerdepositflgdt  = ($BILLFINAL == 0) ? "now()" : "null";

                    /* insert */
                    $query =
                    "
                        insert into clssettletmp
                        (
                              grpno
                            , clsno
                            , userno
                            , billstandard
                            , billadjustment
                            , billpointed
                            , billfinal
                            , billmemo
                            , memberdepositflg
                            , memberdepositflgdt
                            , managerdepositflg
                            , managerdepositflgdt
                            , regdt
                        )
                        values
                        (
                              '$GRPNO'
                            , '$CLSNO'
                            , '$USERNO'
                            ,  $BILLSTANDARD
                            ,  $BILLADJUSTMENT
                            ,  $BILLPOINTED
                            ,  $BILLFINAL
                            , '$BILLMEMO'
                            ,  $memberdepositflg
                            ,  $memberdepositflgdt
                            ,  $managerdepositflg
                            ,  $managerdepositflgdt
                            ,  now()
                        )
                    ";
                    GGsql::exeQuery($query);
                }
                break;
            }
            case self::deleteByClsnoForInside:
            case self::deleteByClsno:
            {
                /* TODO : is finance charge */
                /* validation */
                GGauth::getInstance()->isGrpmanager($GRPNO, $EXECUTOR, true);

                /* process */
                $query =
                "
                    delete from
                        clssettletmp
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
    }

} /* end class */
?>
