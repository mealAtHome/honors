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
    const FIELD__GRPINTRO      = "grpintro";      /* (  ) char(30) */
    const FIELD__BACCNODEFAULT = "baccnodefault"; /* (  ) int */
    const FIELD__BACKNUMBERLENGTH = "backnumberlength"; /* (  ) int */
    const FIELD__GRPBASEADDRCODE = "grpbaseaddrcode"; /* (  ) bigint */
    const FIELD__GRPBASEPOINT = "grpbasepoint"; /* (  ) point */
    const FIELD__GRPMCNT = "grpmcnt"; /* (  ) int */
    const FIELD__GRPCLSTERMUNIT = "grpclstermunit"; /* (  ) enum('y','m','w','d') */
    const FIELD__GRPCLSTERMVALUE = "grpclstermvalue"; /* (  ) int */
    const FIELD__GRPLASTCLSREGISTED = "grplastclsregisted"; /* (  ) datetime */
    const FIELD__GRPCLSAPPLYBILLAVG = "grpclsapplybillavg"; /* (  ) int */
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
            , t.grpintro
            , t.baccnodefault
            , t.backnumberlength
            , t.grpbaseaddrcode
            , ST_X(t.grpbasepoint) grpbaselat
            , ST_Y(t.grpbasepoint) grpbaselng
            , t.grpmcnt
            , t.grpclstermunit
            , t.grpclstermvalue
            , t.grplastclsregisted
            , t.grpclsapplybillavg
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
            , ROUND(
                6371 * ACOS(
                    LEAST(1.0, GREATEST(-1.0,
                        COS(RADIANS(ST_X(t.grpbasepoint))) * COS(RADIANS(ST_X(eu.userloginedpoint))) * COS(RADIANS(ST_Y(eu.userloginedpoint)) - RADIANS(ST_Y(t.grpbasepoint)))
                        + SIN(RADIANS(ST_X(t.grpbasepoint))) * SIN(RADIANS(ST_X(eu.userloginedpoint)))
                    ))
                )
              , 1) grpdistancekm
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
                left join user eu
                    on
                        eu.userno = '$EXECUTOR'
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
    public function updateGrpintroForInside($GRPNO, $GRPINTRO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function recalcGrpmcntForInside($GRPNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function recalcClsStatsForInside($GRPNO) { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    /* const insert = "insert"; */
    const updateBaccnodefaultForInside = "updateBaccnodefaultForInside";
    const updateGrpintroForInside = "updateGrpintroForInside";
    const GRPINTRO_MAX = 30; /* 한줄소개 최대 글자수 */
    const recalcGrpmcntForInside = "recalcGrpmcntForInside";
    const recalcClsStatsForInside = "recalcClsStatsForInside";
    const CLSTERM_LOOKBACK_DAYS = 90; /* 활동주기 계산에 사용하는 최근 기간(일) */
    const CLSBILLAVG_LOOKBACK_CNT = 10; /* 일정평균비용 계산에 사용하는 최근 일정 개수 */
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
            case self::updateGrpintroForInside:
            {
                $grpintro = trim($GRPINTRO);
                if(mb_strlen($grpintro) > self::GRPINTRO_MAX)
                    throw new GGexception("한 줄 소개는 ".self::GRPINTRO_MAX."자 이내로 입력해주세요.");
                $grpintro = GGsql::realEscapeString($grpintro);
                $query = "update grp set grpintro = '$grpintro' where grpno = '$GRPNO'";
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
            case self::recalcGrpmcntForInside:
            {
                /* 활성상태이면서 일반(임시아님) 멤버만 카운트 */
                $cnt = intval(GGsql::selectCnt(
                    "
                        select count(*) cnt
                        from grp_member gm
                            inner join user u on u.userno = gm.userno
                        where
                            gm.grpno = '$GRPNO' and
                            gm.grpmstatus = 'active' and
                            u.usertype = 'normal'
                    "
                ));
                $query = "update grp set grpmcnt = $cnt where grpno = '$GRPNO'";
                GGsql::exeQuery($query);
                break;
            }
            case self::recalcClsStatsForInside:
            {
                /* --------------- */
                /* 활동주기 : 최근 90일간 일정건수를 바탕으로 "주 1회" 식의 러프한 주기를 산출 */
                /* --------------- */
                $lookbackDays = self::CLSTERM_LOOKBACK_DAYS;
                $cnt = intval(GGsql::selectCnt(
                    "
                        select count(*) cnt
                        from cls
                        where grpno = '$GRPNO' and clsapplystartdt >= date_sub(now(), interval $lookbackDays day)
                    "
                ));

                $termunit = null;
                $termvalue = null;
                if($cnt > 0)
                {
                    $eventsPerDay = $cnt / $lookbackDays;
                    $perWeek  = (int) round($eventsPerDay * 7);
                    $perMonth = (int) round($eventsPerDay * 30);
                    $perYear  = (int) round($eventsPerDay * 365);

                    if($perWeek > 6)        { $termunit = 'd'; $termvalue = max(1, (int) round($eventsPerDay)); }
                    elseif($perWeek >= 1)   { $termunit = 'w'; $termvalue = max(1, $perWeek); }
                    elseif($perMonth >= 1)  { $termunit = 'm'; $termvalue = max(1, $perMonth); }
                    else                    { $termunit = 'y'; $termvalue = max(1, $perYear); }
                }
                $termunitSql = $termunit == null ? "null" : "'$termunit'";
                $termvalueSql = $termvalue == null ? "null" : $termvalue;

                /* --------------- */
                /* 마지막활동 : 가장 최근에 "등록"된(clsregdt 기준) 일정의 신청시작일(clsapplystartdt) */
                /* --------------- */
                $lastRow = GGsql::selectOne("select clsapplystartdt from cls where grpno = '$GRPNO' order by clsregdt desc limit 1");
                $lastclsregisted = Common::get($lastRow, "clsapplystartdt");
                $lastclsregistedSql = Common::isEmpty($lastclsregisted) ? "null" : "'$lastclsregisted'";

                /* --------------- */
                /* 일정평균비용 : 가장 최근에 등록된 N개 일정의 신청가격 평균 */
                /* --------------- */
                $billLookbackCnt = self::CLSBILLAVG_LOOKBACK_CNT;
                $avgRow = GGsql::selectOne(
                    "
                        select avg(t.clsbillapplyprice) avgbill
                        from (select clsbillapplyprice from cls where grpno = '$GRPNO' order by clsregdt desc limit $billLookbackCnt) t
                    "
                );
                $avgbill = intval(Common::get($avgRow, "avgbill"));

                /* --------------- */
                /* process */
                /* --------------- */
                $query =
                "
                    update grp set
                        grpclstermunit = $termunitSql,
                        grpclstermvalue = $termvalueSql,
                        grplastclsregisted = $lastclsregistedSql,
                        grpclsapplybillavg = $avgbill
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
