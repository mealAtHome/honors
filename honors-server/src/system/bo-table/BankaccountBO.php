<?php

/* ba : bankaccount */
class BankaccountBO extends _CommonBO
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

    public function setBO()
    {
        GGnavi::getUserBO();
        GGnavi::getGrpBO();
        $arr = array();
        $arr['userBO'] = UserBO::getInstance();
        $arr['grpBO'] = GrpBO::getInstance();
        return $arr;
    }

    /* fields */
    const FIELD__BACCTYPE           = "bacctype";           /* (  ) enum('user','grp') */
    const FIELD__BACCKEY            = "bacckey";            /* (  ) char(30) */
    const FIELD__BACCNO             = "baccno";             /* (  ) int */
    const FIELD__BACCNICKNAME       = "baccnickname";       /* (  ) char(20) */
    const FIELD__BACCCODE           = "bacccode";           /* (  ) char(3) */
    const FIELD__BACCACCT           = "baccacct";           /* (  ) char(50) */
    const FIELD__BACCNAME           = "baccname";           /* (  ) char(30) */
    const FIELD__USABLE             = "usable";             /* (  ) enum('y','n') */
    const FIELD__DEFAULTFLG         = "defaultflg";         /* (  ) enum('y','n') */
    const FIELD__MODIDT             = "modidt";             /* (  ) datetime */
    const FIELD__REGIDT             = "regidt";             /* (  ) datetime */

    /* ========================= */
    /* enum */
    /*
    */
    /* ========================= */
    const BACCTYPE__USER            = "user";               /* 계좌타입 : 유저 */
    const BACCTYPE__GRP             = "grp";                /* 계좌타입 : 그룹 */

    /* ========================= */
    /* select options */
    /* ========================= */
    static public function getConsts()
    {
        $arr = array();
        $arr['bacctypeUser']        = self::BACCTYPE__USER;         /* 계좌타입 : 유저 */
        $arr['bacctypeGrp']         = self::BACCTYPE__GRP;          /* 계좌타입 : 그룹 */
        return $arr;
    }

    /* ========================= */
    /*  */
    /*

    */
    /* ========================= */
    public function getByPk($BACCTYPE, $BACCKEY, $BACCNO) { return Common::getDataOne($this->selectByPkForInside($BACCTYPE, $BACCKEY, $BACCNO)); }

    /* ========================= */
    /*  */
    /*

    */
    /* ========================= */
    public function selectByPkForInside($BACCTYPE, $BACCKEY, $BACCNO) { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectByBacckeyUsableForInside($BACCTYPE, $BACCKEY) { return $this->select(get_defined_vars(), __FUNCTION__); }

    const selectByPkForInside = "selectByPkForInside";
    const selectByExecutorForUser = "selectByExecutorForUser";
    const selectByExecutorForGrp = "selectByExecutorForGrp";
    const selectByBacckeyUsableForInside = "selectByBacckeyUsableForInside";
    protected function select($options, $option="")
    {
        /* -------------- */
        /* vars */
        /* -------------- */
        extract($this->setBO());
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
              t.bacctype
            , t.bacckey
            , t.baccno
            , t.baccnickname
            , t.bacccode
            , t.baccacct
            , t.baccname
            , t.usable
            , t.defaultflg
            , t.modidt
            , t.regidt
            , bk.bankname
        ";

        /* --------------- */
        /* validation */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByExecutorForGrp: /* TODO : is executor owner of bacckey(grpno) ? */ break;
        }

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPkForInside              : { $from = "(select * from bankaccount where bacctype = '$BACCTYPE'     and bacckey = '$BACCKEY'  and baccno = $BACCNO) t"; break; }
            case self::selectByExecutorForUser          : { $from = "(select * from bankaccount where bacctype = '$bacctypeUser' and bacckey = '$EXECUTOR' and usable = 'y' ) t"; break; }
            case self::selectByExecutorForGrp           : { $from = "(select * from bankaccount where bacctype = '$bacctypeGrp'  and bacckey = '$BACCKEY'  and usable = 'y' ) t"; break; }
            case self::selectByBacckeyUsableForInside   : { $from = "(select * from bankaccount where bacctype = '$BACCTYPE'     and bacckey = '$BACCKEY'  and usable = 'y' ) t"; break; }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }

        /* --------------- */
        /* query */
        /* --------------- */
        $query =
        "
            select
                $select
            from
                $from
                left join _bank bk
                    on
                        t.bacccode = bk.bankcode
            order by
                  t.bacckey
                , t.baccno
        ";

        /* --------------- */
        /* execute query */
        /* --------------- */
        return GGsql::select($query, $from, $options);
    }

    /* ==================== */
    /*  */
    /*
     */
    /* ==================== */
    const insertForUser = "insertForUser";
    const insertForGrp = "insertForGrp";
    const deleteByPk = "deleteByPk";
    protected function update($options, $option="")
    {
        /* -------------- */
        /* vars */
        /* -------------- */
        extract($this->setBO());
        extract(self::getConsts());
        extract($options);

        /* bo */
        $ggAuth = GGauth::getInstance();

        /* override option */
        $OPTION = $option != "" ? $option : $OPTION;

        /* result */
        $baccno = "";

        try
        {
            /* ==================== */
            /* process */
            /* ==================== */
            switch($OPTION)
            {
                case self::insertForUser:
                case self::insertForGrp:
                {
                    /* bo */
                    $userBO = UserBO::getInstance();
                    $grpBO = GrpBO::getInstance();

                    /* var by option */
                    $bacckey = "";
                    $bacctype = "";
                    if      ($OPTION == self::insertForUser) { $bacckey = $EXECUTOR; $bacctype = $bacctypeUser; }
                    else if ($OPTION == self::insertForGrp)  { $bacckey = $BACCKEY;  $bacctype = $bacctypeGrp; }

                    /* is default */
                    $defaultflg = "";
                    if(count(Common::getData($this->selectByBacckeyUsableForInside($bacctype, $bacckey))) == 0)
                        $defaultflg = "y";
                    else
                        $defaultflg = "n";

                    /* get baccno */
                    $baccno = $this->getNewIndex($bacctype, $bacckey);

                    /* sql */
                    $query =
                    "
                        insert into bankaccount
                        (
                              bacctype
                            , bacckey
                            , baccno
                            , baccnickname
                            , bacccode
                            , baccacct
                            , baccname
                            , defaultflg
                            , modidt
                            , regidt
                        )
                        values
                        (
                              '$bacctype'
                            , '$bacckey'
                            ,  $baccno
                            , '$BACCNICKNAME'
                            , '$BACCCODE'
                            , '$BACCACCT'
                            , '$BACCNAME'
                            , '$defaultflg'
                            ,  now()
                            ,  now()
                        )
                    ";
                    $result = GGsql::exeQuery($query);

                    /* after process */
                    switch($OPTION)
                    {
                        case self::insertForUser:
                        {
                            $userBaccnodefault = Common::getField($userBO->getByPk($bacckey), UserBO::FIELD__BACCNODEFAULT);
                            if($userBaccnodefault == "")
                                $userBO->updateBaccnodefaultForInside($bacckey, $baccno);
                            break;
                        }
                        case self::insertForGrp:
                        {
                            $grpBaccnodefault = Common::getField($grpBO->getByPk($bacckey), GrpBO::FIELD__BACCNODEFAULT);
                            if($grpBaccnodefault == "")
                                $grpBO->updateBaccnodefaultForInside($bacckey, $baccno);
                            break;
                        }
                    }
                    break;
                }
                case self::deleteByPk:
                {
                    /* bo */
                    $userBO = UserBO::getInstance();
                    $grpBO = GrpBO::getInstance();

                    /* validation : is owner? */
                    $ggAuth->isBankaccountOwner($EXECUTOR, $BACCTYPE, $BACCKEY, $BACCNO, true);

                    /* if it default, cannot delete */
                    $defaultflg = Common::getField($this->getByPk($BACCTYPE, $BACCKEY, $BACCNO), self::FIELD__DEFAULTFLG);
                    if($defaultflg == "y")
                        throw new GGexception("기본 계좌는 삭제할 수 없습니다.");

                    /* sql */
                    $query =
                    "
                        update
                            bankaccount
                        set
                            usable = 'n'
                        where
                            bacctype = '$BACCTYPE' and
                            bacckey  = '$BACCKEY' and
                            baccno   =  $BACCNO
                    ";
                    $result = GGsql::exeQuery($query);
                    break;
                }
                default:
                {
                    throw new GGexception("(server) no option defined");
                }
            }
        }
        catch(Error $e)
        {
            throw $e;
        }
        return $baccno;
    }

    public function getNewIndex($BACCTYPE, $EXECUTOR)
    {
        $query  = "select coalesce(max(baccno),0)+1 cnt from bankaccount where bacctype = '$BACCTYPE' and bacckey = '$EXECUTOR'";
        $rslt   = GGsql::selectCnt($query);
        return $rslt;
    }

} /* end class */
?>
