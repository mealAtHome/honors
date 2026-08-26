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
    function readBO()
    {
        GGnavi::getGrpMemberBO();
        GGnavi::getAddrcodeBO();
    }
    function setBO()
    {
        self::readBO();
        $arr = array();
        $arr['grpMemberBO'] = GrpMemberBO::getInstance();
        $arr['addrcodeBO'] = AddrcodeBO::getInstance();
        $arr['ggAuth'] = GGauth::getInstance();
        return $arr;
    }

    /* ========================= */
    /* fields */
    /*
    */
    /* ========================= */
    const FIELD__GRPNO         = "grpno";         /* (pk) char(30) */
    const FIELD__GRPMANAGER    = "grpmanager";    /* (  ) char(30) */
    const FIELD__GRPIMG        = "grpimg";        /* (  ) char(10) */
    const FIELD__GRPNAME       = "grpname";       /* (  ) char(50) */
    const FIELD__BACCNODEFAULT = "baccnodefault"; /* (  ) int */
    const FIELD__BACKNUMBERLENGTH = "backnumberlength"; /* (  ) int */
    const FIELD__GRPBASEADDRCODE = "grpbaseaddrcode"; /* (  ) bigint */
    const FIELD__GRPBASEPOINT = "grpbasepoint"; /* (  ) point */
    const FIELD__MODIDT        = "modidt";        /* (  ) datetime */
    const FIELD__REGIDT        = "regidt";        /* (  ) datetime */

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
    public function getByPk($GRPNO) { return GGsql::selectOne("select * from grp where grpno = '$GRPNO'"); }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByPkForInside ($GRPNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /*
    */
    /* ========================= */
    const selectByPk = "selectByPk";
    const selectByPkForInside = "selectByPkForInside";
    const selectManaging = "selectManaging"; /* 모임 : 내 모임리스트를 가져옴 */
    const selectActiveForUsr = "selectActiveForUsr";
    protected function select($options, $option="")
    {
        /* vars */
        self::readBO();
        extract(self::getConsts());
        extract(GrpMemberBO::getConsts());
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
              t.grpno
            , t.grpmanager
            , t.grpimg
            , t.grpname
            , t.baccnodefault
            , t.backnumberlength
            , t.grpbaseaddrcode
            , ST_X(t.grpbasepoint) grpbaselat
            , ST_Y(t.grpbasepoint) grpbaselng
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
            , ac.addrstrfull        grpbaseaddrstr
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
                left join _addrcode ac
                    on
                        ac.addrcode = t.grpbaseaddrcode
            order by
                t.grpname asc
        ";
        $rslt = GGsql::select($query, $from, $options, $OPTION);
        return $rslt;
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
    const updateBacknumberlengthForMng = "updateBacknumberlengthForMng";
    const updateBasecampForMng = "updateBasecampForMng";
    const BACKNUMBERLENGTH_MIN = 2; /* 등번호 문자수 최소 */
    const BACKNUMBERLENGTH_MAX = 5; /* 등번호 문자수 최대 */
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
            case self::updateBaccnodefaultForInside:
            {
                $query = "update grp set baccnodefault = $BACCNODEFAULT where grpno = '$GRPNO'";
                GGsql::exeQuery($query);
                break;
            }
            case self::updateBacknumberlengthForMng:
            {
                /* 권한체크 : 모임 매니저(부매니저 포함)만 가능 */
                $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true);

                /* validation */
                if(Common::isEmpty($BACKNUMBERLENGTH))
                    throw new GGexception("등번호 문자수를 입력해주세요.");
                $backnumberlength = intval($BACKNUMBERLENGTH);
                if($backnumberlength < self::BACKNUMBERLENGTH_MIN || $backnumberlength > self::BACKNUMBERLENGTH_MAX)
                    throw new GGexception("등번호 문자수는 ".self::BACKNUMBERLENGTH_MIN."~".self::BACKNUMBERLENGTH_MAX."자 사이로 설정해주세요.");

                /* process */
                $query = "update grp set backnumberlength = $backnumberlength where grpno = '$GRPNO'";
                GGsql::exeQuery($query);
                break;
            }
            case self::updateBasecampForMng:
            {
                /* 권한체크 : 모임 매니저(부매니저 포함)만 가능 */
                $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true);

                /* validation : 지역(법정동) */
                if(Common::isEmpty($GRPBASEADDRCODE))
                    throw new GGexception("아지트 지역을 선택해주세요.");
                $grpbaseaddrcode = intval($GRPBASEADDRCODE);
                if(Common::getDataOneField($addrcodeBO->selectByPkForInside($grpbaseaddrcode), AddrcodeBO::FIELD__ADDRCODE) == null)
                    throw new GGexception("존재하지 않는 지역입니다.");

                /* validation : 위치(GPS) */
                if(Common::isEmpty($GRPBASELAT)) { throw new GGexception("아지트 위치를 선택해주세요."); }
                if(Common::isEmpty($GRPBASELNG)) { throw new GGexception("아지트 위치를 선택해주세요."); }
                $grpbaselat = floatval($GRPBASELAT);
                $grpbaselng = floatval($GRPBASELNG);
                if($grpbaselat < -90 || $grpbaselat > 90)   { throw new GGexception("위도 값이 올바르지 않습니다."); }
                if($grpbaselng < -180 || $grpbaselng > 180) { throw new GGexception("경도 값이 올바르지 않습니다."); }

                /* process */
                $query =
                "
                    update grp set
                        grpbaseaddrcode = $grpbaseaddrcode,
                        grpbasepoint = ST_PointFromText('POINT($grpbaselat $grpbaselng)', 4326)
                    where grpno = '$GRPNO'
                ";
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

}
?>
