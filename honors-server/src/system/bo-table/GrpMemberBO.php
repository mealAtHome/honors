<?php

class GrpMemberBO extends _CommonBO
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
        GGnavi::getGrpMemberPointhistBO();
        GGnavi::getClslineupbBO();
        GGnavi::getClssettleBO();
        GGnavi::getGrpmPrivacyBO();
        $arr = array();
        $arr['ggAuth'] = GGauth::getInstance();
        $arr['userBO'] = UserBO::getInstance();
        $arr['grpMemberPointhistBO'] = GrpMemberPointhistBO::getInstance();
        $arr['clslineupbBO'] = ClslineupbBO::getInstance();
        $arr['clssettleBO'] = ClssettleBO::getInstance();
        $arr['grpmPrivacyBO'] = GrpmPrivacyBO::getInstance();
        return $arr;
    }

    /* ========================= */
    /* fields */
    /*
    */
    /* ========================= */
    const FIELD__GRPNO          = "grpno";         /* (pk) char(30) */
    const FIELD__USERNO         = "userno";        /* (pk) char(30) */
    const FIELD__GRPMTYPE       = "grpmtype";      /* (  ) enum('mng','mngsub','member') */
    const FIELD__GRPMPOSITION   = "grpmposition";  /* (  ) char(20) */
    const FIELD__GRPMFINAUTH    = "grpmfinauth";   /* (  ) enum('y','n') */
    const FIELD__GRPMSTATUS     = "grpmstatus";    /* (  ) enum('active','delete') */
    const FIELD__POINT          = "point";         /* (  ) int */
    const FIELD__DELETEDT       = "deletedt";      /* (  ) datetime */
    const FIELD__MODIDT         = "modidt";        /* (  ) datetime */
    const FIELD__REGIDT         = "regidt";        /* (  ) datetime */

    /* ========================= */
    /* enum */
    /*
    */
    /* ========================= */
    const GRPMTYPE__MNG         = "mng"; /* manager */
    const GRPMTYPE__MNGSUB      = "mngsub"; /* sub manager */
    const GRPMTYPE__MEMBER      = "member"; /* member */
    const GRPMSTATUS__ACTIVE    = "active"; /* active */
    const GRPMSTATUS__DELETE    = "delete"; /* delete */

    static public function getConsts()
    {
        $arr = array();
        $arr['grpmtypeMng']    = self::GRPMTYPE__MNG; /* 멤버타입 : 관리자 */
        $arr['grpmtypeMngsub'] = self::GRPMTYPE__MNGSUB; /* 멤버타입 : 서브 관리자 */
        $arr['grpmtypeMember'] = self::GRPMTYPE__MEMBER; /* 멤버타입 : 일반 멤버 */
        $arr['grpmstatusActive'] = self::GRPMSTATUS__ACTIVE; /* 멤버상태 : 활성 */
        $arr['grpmstatusDelete'] = self::GRPMSTATUS__DELETE; /* 멤버상태 : 삭제 */
        return $arr;
    }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function getByPk($GRPNO, $USERNO) { return Common::getDataOne($this->selectByPkForInside($GRPNO, $USERNO)); }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByPkForInside($GRPNO, $USERNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /*
    */
    /* ========================= */
    const selectByPkForAll = "selectByPkForAll";
    const selectMeByGrpno = "selectMeByGrpno";
    const selectByExecutorForAll = "selectByExecutorForAll";
    const selectByPkForMng = "selectByPkForMng";
    const selectByPkForInside = "selectByPkForInside";
    const selectByGrpnoForAll = "selectByGrpnoForAll";
    const selectByGrpnoForMng = "selectByGrpnoForMng";
    const selectByKeywordForAll = "selectByKeywordForAll";
    const selectByKeywordWithPageForAll = "selectByKeywordWithPageForAll";
    const selectByGrpnoUsernameSearchtypeForAll = "selectByGrpnoUsernameSearchtypeForAll"; /* [PAGENUM, GRPNO, USENAME, SEARCHTYPE] */
    protected function select($options, $option="")
    {
        /* vars */
        $ggAuth = GGauth::getInstance();
        extract(self::getConsts());
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* set executor */
        $EXECUTOR = isset($EXECUTOR) ? $EXECUTOR : "";

        /* --------------- */
        /* sql body */
        /* --------------- */
        $query  = "";
        $from   = "";
        $where  = "";
        $select =
        "
            null as head
            , t.grpno
            , t.userno
            , t.grpmtype
            , t.grpmposition
            , t.grpmfinauth
            , t.grpmstatus
            , t.point
            , t.deletedt
            , t.regidt
            , u.usertype
            , u.id
            , u.img
            , u.name
            , u.birthyear
            ,
            case
                when grpmprv.priv_phone is null then
                    case
                        when userprv.priv_phone = 'all' then u.phone
                        when userprv.priv_phone = 'grp' then case when execgrpm.grpmtype in ('$grpmtypeMng', '$grpmtypeMngsub', '$grpmtypeMember') then u.phone end
                        when userprv.priv_phone = 'mng' then case when execgrpm.grpmtype in ('$grpmtypeMng', '$grpmtypeMngsub') then u.phone end
                    end
                when grpmprv.priv_phone = 'all' then u.phone
                when grpmprv.priv_phone = 'grp' then case when execgrpm.grpmtype in ('$grpmtypeMng', '$grpmtypeMngsub', '$grpmtypeMember') then u.phone end
                when grpmprv.priv_phone = 'mng' then case when execgrpm.grpmtype in ('$grpmtypeMng', '$grpmtypeMngsub') then u.phone end
            end as phone
            , u.hascarflg
            , u.address
            , u.adminflg
            , grp.grpname
            , grpmngu.id as grpmanagerid
            , grpmprv.priv_phone
        ";

        /* --------------- */
        /* auth */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByGrpnoForMng : { $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true); break; }
            case self::selectByPkForMng    : { $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true); break; }
        }

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPkForAll                         : { $from = "(select * from grp_member where grpno  = '$GRPNO' and userno = '$USERNO') t"; break; }
            case self::selectMeByGrpno                          : { $from = "(select * from grp_member where grpno  = '$GRPNO' and userno = '$EXECUTOR') t"; break; }
            case self::selectByExecutorForAll                   : { $from = "(select * from grp_member where userno = '$EXECUTOR') t"; break; }
            case self::selectByPkForInside                      : { $from = "(select * from grp_member where grpno  = '$GRPNO' and userno = '$USERNO') t"; break; }
            case self::selectByGrpnoForAll                      : { $from = "(select * from grp_member where grpno  = '$GRPNO') t"; break; }
            case self::selectByGrpnoForMng                      : { $from = "(select * from grp_member where grpno  = '$GRPNO') t"; break; }
            case self::selectByPkForMng                         : { $from = "(select * from grp_member where grpno  = '$GRPNO' and userno = '$USERNO') t"; break; }
            case self::selectByKeywordForAll                    : { $from = "(select grpm.* from grp_member grpm left join user u on grpm.userno = u.userno where grpm.grpno = '$GRPNO' and u.name like '%$KEYWORD%') t"; break; }
            case self::selectByKeywordWithPageForAll            : { $from = "(select grpm.* from grp_member grpm left join user u on grpm.userno = u.userno where grpm.grpno = '$GRPNO' and u.name like '%$KEYWORD%') t"; break; }
            case self::selectByGrpnoUsernameSearchtypeForAll:
            {
                switch($SEARCHTYPE)
                {
                    case "all"      : $from = "(select grpm.* from grp_member grpm left join user u on grpm.userno = u.userno where grpm.grpno = '$GRPNO' and u.name like '%$USERNAME%') t"; break;
                    case "manager"  : $from = "(select grpm.* from grp_member grpm left join user u on grpm.userno = u.userno where grpm.grpno = '$GRPNO' and u.name like '%$USERNAME%' and grpm.grpmtype in ('$grpmtypeMng', '$grpmtypeMngsub')) t"; break;
                    case "normem"   : $from = "(select grpm.* from grp_member grpm left join user u on grpm.userno = u.userno where grpm.grpno = '$GRPNO' and u.name like '%$USERNAME%' and grpm.grpmtype in ('$grpmtypeMember') and u.usertype = 'normal') t"; break;
                    case "tmpmem"   : $from = "(select grpm.* from grp_member grpm left join user u on grpm.userno = u.userno where grpm.grpno = '$GRPNO' and u.name like '%$USERNAME%' and grpm.grpmtype in ('$grpmtypeMember') and u.usertype = 'temp') t"; break;
                    default: { throw new Exception("(server) selectByGrpnoUsernameSearchtypeForAll searchtype not defined"); }
                }
                break;
            }
            default:
                throw new GGexception("(server) no option defined");
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
                        u.userno = t.userno
                left join grp
                    on
                        t.grpno = grp.grpno
                left join user grpmngu
                    on
                        grp.grpmanager = grpmngu.userno
                left join grpm_privacy grpmprv
                    on
                        grpmprv.grpno = t.grpno and
                        grpmprv.userno = t.userno
                left join user_privacy userprv
                    on
                        userprv.userno = t.userno
                left join grp_member execgrpm
                    on
                        execgrpm.grpno = t.grpno and
                        execgrpm.userno = '$EXECUTOR'
            $where
            order by
                u.name
        ";
        $rslt = GGsql::select($query, $from, $options, $OPTION);
        return $rslt;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    public function updatePointForInside($GRPNO, $USERNO, $POINT) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function insertTempForInside($USERNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteRecordByPkForInside($GRPNO, $USERNO) { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    /* const insert = "insert"; */
    const updateToDeleteForMng = "updateToDeleteForMng";
    const updatePointForInside = "updatePointForInside";
    const updateInjectPointForMng = "updateInjectPointForMng";
    const insertTempForInside = "insertTempForInside";
    const updateGrpmpositionForMng = "updateGrpmpositionForMng";
    const makeTempUserForMng = "makeTempUserForMng";
    const mergeTempToMemberForMng = "mergeTempToMemberForMng";
    const deleteRecordByPkForInside = "deleteRecordByPkForInside";
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
            case self::updateToDeleteForMng:
            {
                /* is grpmanager? */
                $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true);

                /* execute */
                $query = "update grp_member set grpmstatus = 'delete', deletedt = now() where grpno = '$GRPNO' and userno = '$USERNO'";
                GGsql::exeQuery($query);
                break;
            }
            case self::updatePointForInside:
            {
                /* set var */
                $POINT = intval($POINT);

                /* check member point is minus */
                $grpMember = $this->getByPk($GRPNO, $USERNO);
                $grpmPoint = intval($grpMember[self::FIELD__POINT]);
                if(($grpmPoint + $POINT) < 0)
                    throw new GGexception("멤버 포인트는 마이너스가 될 수 없습니다.");

                /* execute */
                $query = "update grp_member set point = point + $POINT where grpno = '$GRPNO' and userno = '$USERNO'";
                GGsql::exeQuery($query);
                break;
            }
            case self::updateInjectPointForMng:
            {
                /* is grpmanager? */
                $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true);

                /* update point */
                $this->updatePointForInside($GRPNO, $USERNO, $POINT);

                /* insert to history */
                $grpMemberPointhistBO->insertForInside($GRPNO, $USERNO, $POINT, $POINTMEMO, null);
                break;
            }
            case self::insertTempForInside:
            {
                $query =
                "
                    insert into grp_member
                    (
                          grpno
                        , userno
                        , regidt
                    )
                    values
                    (
                          'GCYHTRMLKYLEXMELLYTWCUBWAWALXD'
                        , '$USERNO'
                        ,  now()
                    )
                ";
                GGsql::exeQuery($query);
                break;
            }
            case self::updateGrpmpositionForMng:
            {
                /* is gprowner? */
                $ggAuth->isGrpowner($GRPNO, $EXECUTOR, true);

                /* check var */
                if($ARR == null || count($ARR) == 0)
                    throw new GGexception();

                /* LOOP & UPDATE */
                foreach($ARR as $ROW)
                {
                    $USERNO        = Common::get($ROW, "USERNO", null);
                    $GRPMTYPE      = Common::get($ROW, "GRPMTYPE", null);
                    $GRPMPOSITION  = Common::get($ROW, "GRPMPOSITION", null);
                    $GRPMFINAUTH   = Common::get($ROW, "GRPMFINAUTH", null);

                    /* check */
                    if(Common::isEmpty($USERNO)) { throw new GGexception("시스템 오류입니다."); }
                    if(Common::isEmpty($GRPMTYPE)) { throw new GGexception("시스템 오류입니다."); }
                    if(Common::isEmpty($GRPMPOSITION)) { throw new GGexception("시스템 오류입니다."); }
                    if(Common::isEmpty($GRPMFINAUTH)) { throw new GGexception("시스템 오류입니다."); }

                    /* execute */
                    $query = "update grp_member set grpmtype = '$GRPMTYPE', grpmposition = '$GRPMPOSITION', grpmfinauth = '$GRPMFINAUTH', modidt = now() where grpno = '$GRPNO' and userno = '$USERNO'";
                    GGsql::exeQuery($query);
                }
                break;
            }
            case self::makeTempUserForMng:
            {
                /* is grpmanager? */
                $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true);

                /* make temp user */
                $userInfo = $userBO->insertTempForInside($USERNAME);
                $userno = Common::getField($userInfo, GGF::USERNO);

                /* insert grp_member */
                $query = "insert into grp_member (grpno, userno, regidt) values ('$GRPNO', '$userno', now())";
                GGsql::exeQuery($query);

                /* insert sub tables */
                $grpmPrivacyBO->insertDefaultForInside($GRPNO, $userno);

                /* update point */
                $point = intval($POINT);
                if($point > 0)
                {
                    $this->updatePointForInside($GRPNO, $userno, $POINT);
                    $grpMemberPointhistBO->insertForInside($GRPNO, $userno, $POINT, "임시멤버 등록화면에서 투여", null);
                }

                /* return */
                $rslt[GGF::USERNO] = $userno;
                break;
            }
            case self::mergeTempToMemberForMng:
            {
                /* is grpmanager? */
                $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true);

                /* get point left */
                $pointLeft = Common::getField($this->getByPk($GRPNO, $USERNO), GrpMemberBO::FIELD__POINT);
                if($pointLeft != null)
                {
                    /* point to target */
                    $pointLeft = intval($pointLeft);
                    if($pointLeft > 0)
                    {
                        $this->updatePointForInside($GRPNO, $TARGET, $pointLeft);
                        $grpMemberPointhistBO->insertForInside($GRPNO, $TARGET, $pointLeft, "멤버병합으로 인한 포인트 이전", null);
                    }
                }

                /* update && delete */
                $clslineupbBO->updateUsernoToTargetForInside($GRPNO, $USERNO, $TARGET);
                $clssettleBO->updateUsernoToTargetForInside($GRPNO, $USERNO, $TARGET);
                $grpMemberPointhistBO->deleteRecordByGrpnoUsernoForInside($GRPNO, $USERNO);
                $this->deleteRecordByPkForInside($GRPNO, $USERNO);
                $userBO->deleteRecordByPkForInside($USERNO);
                $grpmPrivacyBO->deleteByUsernoForInside($USERNO);
                break;
            }
            case self::deleteRecordByPkForInside:
            {
                $query = "delete from grp_member where grpno = '$GRPNO' and userno = '$USERNO'";
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
