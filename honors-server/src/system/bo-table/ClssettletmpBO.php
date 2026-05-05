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
    const FIELD__GRPNO                      = "grpno";                      /* (PK) char(30) */
    const FIELD__CLSNO                      = "clsno";                      /* (PK) char(14) */
    const FIELD__USERNO                     = "userno";                     /* (PK) char(30) */
    const FIELD__BILLSTANDARD               = "billstandard";               /* (  ) int */
    const FIELD__BILLPREPAID                = "billprepaid";                /* (  ) int */
    const FIELD__BILLADJUSTMENT             = "billadjustment";             /* (  ) int */
    const FIELD__BILLDISCOUNT               = "billdiscount";               /* (  ) int */
    const FIELD__BILLPOINTED                = "billpointed";                /* (  ) int */
    const FIELD__BILLFINAL                  = "billfinal";                  /* (  ) int */
    const FIELD__BILLMEMO                   = "billmemo";                   /* (  ) varchar(100) */
    const FIELD__SETTLESTATUS               = "settlestatus";               /* (  ) char(4) */
    const FIELD__SETTLEMEMBDT               = "settlemembdt";               /* (  ) datetime */
    const FIELD__SETTLEDONEDT               = "settledonedt";               /* (  ) datetime */
    const FIELD__SETTLELOSSDT               = "settlelossdt";               /* (  ) datetime */
    const FIELD__REGDT                      = "regdt";                      /* (  ) datetime */

    /* ========================= */
    /* enum */
    /*
    */
    /* ========================= */
    const SETTLESTATUS__WAIT = "wait"; /* 정산상태 : 입금대기 */
    const SETTLESTATUS__MEMB = "memb"; /* 정산상태 : 입금완료 */
    const SETTLESTATUS__DONE = "done"; /* 정산상태 : 확인완료 */
    const SETTLESTATUS__LOSS = "loss"; /* 정산상태 : 손실 */
    static public function getConsts()
    {
        $arr = array();
        $arr['settlestatusWait'] = self::SETTLESTATUS__WAIT; /* 정산상태 : 입금대기 */
        $arr['settlestatusMemb'] = self::SETTLESTATUS__MEMB; /* 정산상태 : 입금완료 */
        $arr['settlestatusDone'] = self::SETTLESTATUS__DONE; /* 정산상태 : 확인완료 */
        $arr['settlestatusLoss'] = self::SETTLESTATUS__LOSS; /* 정산상태 : 손실 */
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
    public function selectByClsnoForInside($GRPNO, $CLSNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /*
    */
    /* ========================= */
    const selectByClsno = "selectByClsno";
    const selectByClsnoForInside = "selectByClsnoForInside";
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
            null as head
            , t.grpno
            , t.clsno
            , t.userno
            , t.billstandard
            , t.billprepaid
            , t.billadjustment
            , t.billdiscount
            , t.billpointed
            , t.billfinal
            , t.billmemo
            , t.settlestatus
            , t.settlemembdt
            , t.settledonedt
            , t.settlelossdt
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
                $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true);
                break;
            }
        }

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByClsno            : { $from = "(select * from clssettletmp where grpno = '$GRPNO' and clsno = '$CLSNO') t"; break; }
            case self::selectByClsnoForInside   : { $from = "(select * from clssettletmp where grpno = '$GRPNO' and clsno = '$CLSNO') t"; break; }
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
        $rslt = GGsql::select($query, $from, $options, $OPTION);
        return $rslt;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    // public function insertByArr($GRPNO, $CLSNO, $EXECUTOR, $ARR) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteByClsnoForInside($GRPNO, $CLSNO) { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    const insertByArr = "insertByArr";
    const deleteByClsno = "deleteByClsno";
    const deleteByClsnoForInside = "deleteByClsnoForInside";
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
            case self::insertByArr:
            {
                /* auth */
                $ggAuth->hasGrpmfinauth($GRPNO, $EXECUTOR, true);

                /* delete before insert */
                $this->deleteByClsnoForInside($GRPNO, $CLSNO);

                /* process */
                $arr = json_decode($ARR, true);
                foreach($arr as $dat)
                {
                    /* vars */
                    $USERNO         = $dat['USERNO'];
                    $BILLSTANDARD   = intval($dat['BILLSTANDARD']);
                    $BILLPREPAID    = intval($dat['BILLPREPAID']);
                    $BILLADJUSTMENT = intval($dat['BILLADJUSTMENT']);
                    $BILLDISCOUNT   = intval($dat['BILLDISCOUNT']);
                    $BILLPOINTED    = intval($dat['BILLPOINTED']);
                    $BILLFINAL      = intval($dat['BILLFINAL']);
                    $BILLMEMO       = $dat['BILLMEMO'];

                    /* insert */
                    $query =
                    "
                        insert into clssettletmp
                        (
                              grpno
                            , clsno
                            , userno
                            , billstandard
                            , billprepaid
                            , billadjustment
                            , billdiscount
                            , billpointed
                            , billfinal
                            , billmemo
                            , regdt
                        )
                        values
                        (
                              '$GRPNO'
                            , '$CLSNO'
                            , '$USERNO'
                            ,  $BILLSTANDARD
                            ,  $BILLPREPAID
                            ,  $BILLADJUSTMENT
                            ,  $BILLDISCOUNT
                            ,  $BILLPOINTED
                            ,  $BILLFINAL
                            , '$BILLMEMO'
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
                /* auth */
                if($OPTION == self::deleteByClsno)
                    $ggAuth->hasGrpmfinauth($GRPNO, $EXECUTOR, true);

                /* process */
                $query = "delete from clssettletmp where grpno = '$GRPNO' and clsno = '$CLSNO'";
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
