<?php

/* grp_joinreq : 모임 가입신청 (모임의 가입 방식 grp.grpjoinpolicy에 따라 처리) */
class GrpJoinreqBO extends _CommonBO
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
        GGnavi::getGrpBO();
        GGnavi::getGrpMemberBO();
        GGnavi::getSystemPushBO();
    }
    function setBO()
    {
        self::readBO();
        $arr = array();
        $arr['grpBO'] = GrpBO::getInstance();
        $arr['grpMemberBO'] = GrpMemberBO::getInstance();
        $arr['systemPushBO'] = SystemPushBO::getInstance();
        $arr['ggAuth'] = GGauth::getInstance();
        return $arr;
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    const FIELD__GRPNO       = "grpno";       /* (pk) char(30) */
    const FIELD__USERNO      = "userno";      /* (pk) char(30) */
    const FIELD__REQMSG      = "reqmsg";      /* (  ) varchar(200) */
    const FIELD__REQSTATUS   = "reqstatus";   /* (  ) enum('wait','approved','rejected','canceled') */
    const FIELD__PROCUSERNO  = "procuserno";  /* (  ) char(30) : 처리한 매니저 */
    const FIELD__PROCDT      = "procdt";      /* (  ) datetime */
    const FIELD__REGDT       = "regdt";       /* (  ) datetime */

    /* ========================= */
    /* enum */
    /* ========================= */
    const REQSTATUS__WAIT       = "wait";
    const REQSTATUS__APPROVED   = "approved";
    const REQSTATUS__REJECTED   = "rejected";
    const REQSTATUS__CANCELED   = "canceled";
    const REQMSG_MAX = 200;
    static public function getConsts()
    {
        $arr = array();
        $arr['reqstatusWait'] = self::REQSTATUS__WAIT;
        return $arr;
    }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function getByPk($GRPNO, $USERNO) { return GGsql::selectOne("select * from grp_joinreq where grpno = '$GRPNO' and userno = '$USERNO'"); }

    /* ========================= */
    /* select */
    /* ========================= */
    const selectMineByGrpnoForUsr = "selectMineByGrpnoForUsr";  /* 내 가입신청 상태 */
    const selectWaitByGrpnoForMng = "selectWaitByGrpnoForMng";  /* 승인 대기 목록 */
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
        /* validation */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectWaitByGrpnoForMng: { $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true); break; }
        }

        /* --------------- */
        /* from */
        /* --------------- */
        $from = "";
        switch($OPTION)
        {
            case self::selectMineByGrpnoForUsr : { $from = "(select * from grp_joinreq where grpno = '$GRPNO' and userno = '$EXECUTOR') t"; break; }
            case self::selectWaitByGrpnoForMng : { $from = "(select * from grp_joinreq where grpno = '$GRPNO' and reqstatus = '$reqstatusWait') t"; break; }
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
                  t.grpno
                , t.userno
                , t.reqmsg
                , t.reqstatus
                , t.procdt
                , t.regdt
                , u.name username
                , u.birthyear
                , u.hascarflg
            from
                $from
                left join user u on u.userno = t.userno
            order by
                t.regdt asc
        ";
        return GGsql::select($query, $from, $options, $OPTION);
    }

    /* ========================= */
    /* update */
    /* ========================= */
    const requestForUsr = "requestForUsr";  /* 가입신청 (가입 방식이 free면 바로 멤버) */
    const cancelForUsr  = "cancelForUsr";   /* 가입신청 취소 */
    const approveForMng = "approveForMng";  /* 승인 → 멤버 등록 */
    const rejectForMng  = "rejectForMng";   /* 거절 */
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
            case self::requestForUsr:
            {
                /* validation */
                $grp = $grpBO->getByPk($GRPNO);
                if($grp == null) throw new GGexception("존재하지 않는 모임입니다.");
                if($ggAuth->isGrpmember($GRPNO, $EXECUTOR)) throw new GGexception("이미 이 모임의 멤버입니다.");
                $joinpolicy = Common::getField($grp, GrpBO::FIELD__GRPJOINPOLICY);
                if($joinpolicy == GrpBO::JOINPOLICY__NONE) throw new GGexception("이 모임은 가입을 받지 않습니다. 용병 모집 일정에 참가해주세요.");

                $reqmsg = trim($REQMSG);
                if(mb_strlen($reqmsg) > self::REQMSG_MAX) throw new GGexception("가입인사는 ".self::REQMSG_MAX."자 이내로 입력해주세요.");
                $reqmsg = GGsql::realEscapeString($reqmsg);

                /* free : 바로 멤버. approve : 대기 */
                $isFree = ($joinpolicy == GrpBO::JOINPOLICY__FREE);
                $status = $isFree ? self::REQSTATUS__APPROVED : self::REQSTATUS__WAIT;
                $query =
                "
                    insert into grp_joinreq (grpno, userno, reqmsg, reqstatus, procuserno, procdt, regdt)
                    values ('$GRPNO', '$EXECUTOR', '$reqmsg', '$status', null, ".($isFree ? "now()" : "null").", now())
                    on duplicate key update reqmsg = values(reqmsg), reqstatus = values(reqstatus), procuserno = null, procdt = values(procdt), regdt = now()
                ";
                GGsql::exeQuery($query);
                if($isFree)
                    $grpMemberBO->joinForInside($GRPNO, $EXECUTOR, GrpMemberBO::GRPMTYPE__MEMBER);
                else
                    $systemPushBO->insertPushtypeGrpJoinreqForInside($GRPNO, $EXECUTOR); /* 운영진에게 승인 요청 알림 */

                $rslt[self::FIELD__REQSTATUS] = $status;
                break;
            }
            case self::cancelForUsr:
            {
                $query = "update grp_joinreq set reqstatus = '".self::REQSTATUS__CANCELED."', procdt = now() where grpno = '$GRPNO' and userno = '$EXECUTOR' and reqstatus = '$reqstatusWait'";
                GGsql::exeQuery($query);
                break;
            }
            case self::approveForMng:
            case self::rejectForMng:
            {
                $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true);
                $req = $this->getByPk($GRPNO, $USERNO);
                if($req == null || Common::getField($req, self::FIELD__REQSTATUS) != self::REQSTATUS__WAIT)
                    throw new GGexception("처리할 가입신청이 없습니다.");

                $status = ($OPTION == self::approveForMng) ? self::REQSTATUS__APPROVED : self::REQSTATUS__REJECTED;
                $query = "update grp_joinreq set reqstatus = '$status', procuserno = '$EXECUTOR', procdt = now() where grpno = '$GRPNO' and userno = '$USERNO'";
                GGsql::exeQuery($query);
                if($OPTION == self::approveForMng)
                    $grpMemberBO->joinForInside($GRPNO, $USERNO, GrpMemberBO::GRPMTYPE__MEMBER);
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
