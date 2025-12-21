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
        GGnavi::getClslineup2BO();
        GGnavi::getGrpfSettleBO();
        $arr = array();
        $arr['ggAuth'] = GGauth::getInstance();
        $arr['userBO'] = UserBO::getInstance();
        $arr['grpMemberPointhistBO'] = GrpMemberPointhistBO::getInstance();
        $arr['clslineup2BO'] = Clslineup2BO::getInstance();
        $arr['grpfSettleBO'] = GrpfSettleBO::getInstance();
        return $arr;
    }

    /* ========================= */
    /* field */
    /*
    */
    /* ========================= */
    CONST FIELD__GRPNO          = "grpno";         /* (pk) char(30) */
    CONST FIELD__USERNO         = "userno";        /* (pk) char(30) */
    CONST FIELD__GRPMTYPE       = "grpmtype";      /* (  ) enum('mng','mngsub','member') */
    CONST FIELD__GRPMPOSITION   = "grpmposition";  /* (  ) char(20) */
    CONST FIELD__GRPMSTATUS     = "grpmstatus";    /* (  ) enum('active','delete') */
    CONST FIELD__POINT          = "point";         /* (  ) int */
    CONST FIELD__DELETEDT       = "deletedt";      /* (  ) datetime */
    CONST FIELD__MODIDT         = "modidt";        /* (  ) datetime */
    CONST FIELD__REGIDT         = "regidt";        /* (  ) datetime */

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
    /* ========================= */
    const selectByPkForInside = "selectByPkForInside";
    const selectByGrpnoForMng = "selectByGrpnoForMng";
    const selectByPkForMng = "selectByPkForMng";
    const selectByPkForAll = "selectByPkForAll";
    const selectByGrpnoForAll = "selectByGrpnoForAll";
    const selectByKeywordForAll = "selectByKeywordForAll";
    const selectMeByGrpno = "selectMeByGrpno";
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        extract($this->setBO());
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
        $where  = "";

        /* --------------- */
        /* field by auth */
        /* --------------- */
        switch($OPTION)
        {
            /* for mng */
            case self::selectByPkForInside:
            case self::selectByGrpnoForMng:
            case self::selectByPkForMng:
            {
                $select =
                "
                    t.grpno
                    , t.userno
                    , t.grpmtype
                    , t.grpmposition
                    , t.grpmstatus
                    , t.point
                    , t.deletedt
                    , t.regidt
                    , u.usertype
                    , u.id
                    , u.img
                    , u.name
                    , u.birthyear
                    , u.phone
                    , u.hascarflg
                    , u.address
                    , grpmngu.id as grpmanagerid
                ";
                break;
            }
            /* for all */
            default:
            {
                $select =
                "
                    t.grpno
                    , t.userno
                    , t.grpmtype
                    , t.grpmposition
                    , t.grpmstatus
                    , null as point
                    , t.deletedt
                    , t.regidt
                    , u.usertype
                    , u.id
                    , u.img
                    , u.name
                    , u.birthyear
                    , u.phone
                    , u.hascarflg
                    , u.address
                    , grpmngu.id as grpmanagerid
                ";
                break;
            }
        }

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
            case self::selectByPkForInside        : { $from = "(select * from grp_member where grpno = '$GRPNO' and userno = '$USERNO') t"; break; }
            case self::selectByGrpnoForMng        : { $from = "(select * from grp_member where grpno = '$GRPNO') t"; break; }
            case self::selectByPkForMng           : { $from = "(select * from grp_member where grpno = '$GRPNO' and userno = '$USERNO') t"; break; }
            case self::selectByPkForAll           : { $from = "(select * from grp_member where grpno = '$GRPNO' and userno = '$USERNO') t"; break; }
            case self::selectByGrpnoForAll        : { $from = "(select * from grp_member where grpno = '$GRPNO') t"; break; }
            case self::selectMeByGrpno            : { $from = "(select * from grp_member where grpno = '$GRPNO' and userno = '$EXECUTOR') t"; break; }
            case self::selectByKeywordForAll:
            {
                $from = "(select * from grp_member where grpno = '$GRPNO') t";
                $where = "where u.name like '%$KEYWORD%'";
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
            $where
            order by
                u.name
        ";
        $result = GGsql::select($query, $from, $options);
        return $result;
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

        /* get vars */
        extract($this->setBO());
        extract($options);
        extract(self::getConsts());

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* sql execution */
        switch($OPTION)
        {
            case self::updateToDeleteForMng:
            {
                /* is grpmanager? */
                $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true);

                /* execute */
                $query =
                "
                    update
                        grp_member
                    set
                        grpmstatus = 'delete',
                        deletedt = now()
                    where
                        grpno = '$GRPNO' and
                        userno = '$USERNO'
                ";
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
                $query =
                "
                    update
                        grp_member
                    set
                        point = point + $POINT
                    where
                        grpno = '$GRPNO' and
                        userno = '$USERNO'
                ";
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

                    /* execute */
                    $query =
                    "
                        update
                            grp_member
                        set
                            grpmtype = '$GRPMTYPE',
                            grpmposition = '$GRPMPOSITION',
                            modidt = now()
                        where
                            grpno = '$GRPNO' and
                            userno = '$USERNO'
                    ";
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
                          '$GRPNO'
                        , '$userno'
                        ,  now()
                    )
                ";
                GGsql::exeQuery($query);

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
                $clslineup2BO->updateUsernoToTargetForInside($GRPNO, $USERNO, $TARGET);
                $grpfSettleBO->updateUsernoToTargetForInside($GRPNO, $USERNO, $TARGET);
                $grpMemberPointhistBO->deleteRecordByGrpnoUsernoForInside($GRPNO, $USERNO);
                $this->deleteRecordByPkForInside($GRPNO, $USERNO);
                $userBO->deleteRecordByPkForInside($USERNO);
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
