<?php

class GrpBO extends _CommonBO
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
    function __construct() {
        GGnavi::getGrpMemberBO();
    }

    /* ========================= */
    /* field */
    /*
    */
    /* ========================= */
    const FIELD__GRPNO         = "grpno";         /* (pk) char(30) */
    const FIELD__GRPMANAGER    = "grpmanager";    /* (  ) char(30) */
    const FIELD__GRPIMG        = "grpimg";        /* (  ) char(10) */
    const FIELD__GRPNAME       = "grpname";       /* (  ) char(50) */
    const FIELD__BACCNODEFAULT = "baccnodefault"; /* (  ) int */
    const FIELD__MODIDT        = "modidt";        /* (  ) datetime */
    const FIELD__REGIDT        = "regidt";        /* (  ) datetime */

    /* ========================= */
    /* enum */
    /*
    */
    /* ========================= */

    /* ========================= */
    /* select > sub > sub */
    /* ========================= */
    public function getByPk($GRPNO) { return GGsql::selectOne("select * from grp where grpno = '$GRPNO'"); }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByPkForInside ($GRPNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /* ========================= */
    const selectByPk = "selectByPk";
    const selectByPkForInside = "selectByPkForInside";
    const selectManaging = "selectManaging"; /* 그룹 : 내 그룹리스트를 가져옴 */
    const selectActiveForUsr = "selectActiveForUsr";
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
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
              t.grpno
            , t.grpmanager
            , t.grpimg
            , t.grpname
            , t.baccnodefault
            , t.modidt
            , t.regidt
            , u.id                  grpmanager_id
            , u.name                grpmanager_name
            , u.phone               grpmanager_phone
            , bacc.bacctype         bacctype
            , bacc.bacckey          bacckey
            , bacc.baccno           baccno
            , bacc.baccnickname     baccnickname
            , bacc.bacccode         bacccode
            , bacc.baccacct         baccacct
            , bacc.baccname         baccname
            , bank.bankname         bankname
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPk          : { $from = "(select * from grp where grpno = '$GRPNO' ) t"; break; }
            case self::selectByPkForInside : { $from = "(select * from grp where grpno = '$GRPNO' ) t"; break; }
            case self::selectManaging      : { $from = "(select * from grp where grpno in (select grpno from grp_member where userno = '$EXECUTOR' and grpmtype in ('$grpmtypeMng', '$grpmtypeMngsub'))) t"; break; }
            case self::selectActiveForUsr  : { $from = "(select * from grp where grpno in (select grpno from grp_member where userno = '$EXECUTOR' and grpmstatus = '$grpmstatusActive')) t"; break; }
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
                        u.userno = t.grpmanager
                left join bankaccount bacc
                    on
                        bacc.bacctype = 'grp' and
                        bacc.bacckey = t.grpno and
                        bacc.baccno = t.baccnodefault
                left join _bank bank
                    on
                        bank.bankcode = bacc.bacccode
            order by
                t.grpname asc
        ";
        $result = GGsql::select($query, $from, $options);
        return $result;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    /* public function changeStoreStatus($STORENO, $STORE_STATUS)     { return $this->update(get_defined_vars(), __FUNCTION__); } */
    public function updateBaccnodefaultForInside($GRPNO, $BACCNODEFAULT) { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    /* const insert = "insert"; */
    const updateBaccnodefaultForInside = "updateBaccnodefaultForInside";
    protected function update($options, $option="")
    {
        /* vars */
        $ggAuth = GGauth::getInstance();
        $storeno = null;

        /* get vars */
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* sql execution */
        switch($OPTION)
        {
            case self::updateBaccnodefaultForInside:
            {
                $query = "update grp set baccnodefault = $BACCNODEFAULT where grpno = '$GRPNO'";
                $result = GGsql::exeQuery($query);
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }
        return $storeno;
    }

}
?>
