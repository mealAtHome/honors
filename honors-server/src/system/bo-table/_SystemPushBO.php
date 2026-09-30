<?php

class SystemPushBO extends _CommonBO
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
        // GGnavi::getIdxBO();
        $arr = array();
        // $arr['ggAuth'] = GGauth::getInstance();
        return $arr;
    }
    /* ========================= */
    /* fields */
    /*
    */
    /* ========================= */
    const FIELD__PUSHIDX            = "pushidx";                /* (PK) bigint(20) auto_increment */
    const FIELD__PUSHSTATUS         = "pushstatus";             /* (  ) enum('wait','ing','end') */
    const FIELD__PUSHTYPE           = "pushtype";               /* (  ) varchar(100) */
    const FIELD__PUSHDATAJSON       = "pushdatajson";           /* (  ) text */
    const FIELD__PUSHCNTALL         = "pushcntall";             /* (  ) int(11) */
    const FIELD__PUSHCNTSUCCEED     = "pushcntsucceed";         /* (  ) int(11) */
    const FIELD__PUSHCNTFAILED      = "pushcntfailed";          /* (  ) int(11) */
    const FIELD__PUSHCNTSKIPPED     = "pushcntskipped";         /* (  ) int(11) */
    const FIELD__PUSHRESULT         = "pushresult";             /* (  ) enum('succeed','failed') */
    const FIELD__MODIDT             = "modidt";                 /* (  ) datetime */
    const FIELD__REGDT              = "regdt";                  /* (  ) datetime */

    /* ========================= */
    /* enum */
    /*
    */
    /* ========================= */
    const PUSHTYPE__CLS_OPEN = "clsOpen"; /* 일정이 공개됨 */
    const PUSHTYPE__CLS_CANCEL = "clsCancel"; /* 일정이 취소됨 */
    const PUSHTYPE__CLS_APPLY_START_BEFORE_10_MIN = "clsApplyStartBefore10Min"; /* 모집시작 10분 전 */
    const PUSHTYPE__CLS_APPLY_CLOSE_BEFORE_1_HOUR = "clsApplyCloseBefore1Hour"; /* 모집마감 1시간 전 */

    const PUSHSTATUS__WAIT = "wait";
    const PUSHSTATUS__ING = "ing";
    const PUSHSTATUS__END = "end";
    const PUSHRESULT__SUCCEED = "succeed";
    const PUSHRESULT__FAILED = "failed";
    static public function getConsts()
    {
        $arr = array();
        $arr['pushtypeClsOpen'] = self::PUSHTYPE__CLS_OPEN; /* 푸시타입 : 일정이 공개됨 */
        $arr['pushtypeClsCancel'] = self::PUSHTYPE__CLS_CANCEL; /* 푸시타입 : 일정이 취소됨 */
        $arr['pushtypeClsApplyStartBefore10Min'] = self::PUSHTYPE__CLS_APPLY_START_BEFORE_10_MIN; /* 푸시타입 : 모집시작 10분 전 */
        $arr['pushtypeClsApplyCloseBefore1Hour'] = self::PUSHTYPE__CLS_APPLY_CLOSE_BEFORE_1_HOUR; /* 푸시타입 : 모집마감 1시간 전 */
        $arr['pushstatusWait'] = self::PUSHSTATUS__WAIT; /* 푸시상태 : 대기중 */
        $arr['pushstatusIng'] = self::PUSHSTATUS__ING;   /* 푸시상태 : 진행중 */
        $arr['pushstatusEnd'] = self::PUSHSTATUS__END;   /* 푸시상태 : 완료 */
        $arr['pushresultSucceed'] = self::PUSHRESULT__SUCCEED; /* 푸시결과 : 성공 */
        $arr['pushresultFailed'] = self::PUSHRESULT__FAILED;   /* 푸시결과 : 실패 */
        return $arr;
    }

    /* ========================= */
    /* main functions */
    /*
    */
    /* ========================= */


    /* ========================= */
    /* select > sub > sub */
    /* ========================= */
    public function getByPk($PUSHIDX) { return Common::getDataOne($this->selectByPkForInside($PUSHIDX)); }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByPkForInside($PUSHIDX) { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectWaitIngForInside() { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /*
    */
    /* ========================= */
    const selectByPkForInside = "selectByPkForInside";
    const selectWaitIngForInside = "selectWaitIngForInside";
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
              sp.pushidx
            , sp.pushstatus
            , sp.pushtype
            , sp.pushdatajson
            , sp.pushcntall
            , sp.pushcntsucceed
            , sp.pushcntfailed
            , sp.pushcntskipped
            , sp.pushresult
            , sp.modidt
            , sp.regdt
        ";

        /* --------------- */
        /* validation */
        /* --------------- */

        /* --------------- */
        /* add column to mng */
        /* --------------- */

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPkForInside : { $from = "(select * from _system_push where pushidx = '$PUSHIDX') sp"; break; }
            case self::selectWaitIngForInside : { $from = "(select * from _system_push where pushstatus = '$pushstatusWait' or pushstatus = '$pushstatusIng') sp"; break; }
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
            order by
                sp.pushidx
        ";
        $rslt = GGsql::select($query, $from, $options, $OPTION);
        return $rslt;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    public function insertWaitForInside($PUSHTYPE, $PUSHDATAJSON) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function insertPushtypeClsOpenForInside($GRPNO, $CLSNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function insertPushtypeClsCancelForInside($GRPNO, $CLSNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function insertPushtypeClsApplyStartBefore10MinForInside($GRPNO, $CLSNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function insertPushtypeClsApplyCloseBefore1HourForInside($GRPNO, $CLSNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function updatePushstatusWaitToIngForInside($PUSHIDX) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function updatePushstatusIngToEndForInside($PUSHIDX) { return $this->update(get_defined_vars(), __FUNCTION__); }


    /* ========================= */
    /* update */
    /* ========================= */
    const insertWaitForInside = "insertWaitForInside"; /* PUSHTYPE, PUSHDATAJSON */
    const insertPushtypeClsOpenForInside = "insertPushtypeClsOpenForInside"; /* GRPNO, CLSNO */
    const insertPushtypeClsCancelForInside = "insertPushtypeClsCancelForInside"; /* GRPNO, CLSNO */
    const insertPushtypeClsApplyStartBefore10MinForInside = "insertPushtypeClsApplyStartBefore10MinForInside"; /* GRPNO, CLSNO */
    const insertPushtypeClsApplyCloseBefore1HourForInside = "insertPushtypeClsApplyCloseBefore1HourForInside"; /* GRPNO, CLSNO */
    const updatePushstatusWaitToIngForInside = "updatePushstatusWaitToIngForInside"; /* PUSHIDX */
    const updatePushstatusIngToEndForInside = "updatePushstatusIngToEndForInside"; /* PUSHIDX */
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
        /* validation (common) */
        /* =============== */
        // Common::logDebug("update called with option: $OPTION");

        /* =============== */
        /* process */
        /* =============== */
        switch($OPTION)
        {
            case self::insertWaitForInside:
            {
                $query =
                "
                    insert into _system_push
                    (
                          pushstatus
                        , pushtype
                        , pushdatajson
                        , pushcntall
                        , pushcntsucceed
                        , pushcntfailed
                        , pushcntskipped
                        , pushresult
                        , modidt
                        , regdt
                    )
                    values
                    (
                          '$pushstatusWait'
                        , '$PUSHTYPE'
                        , '$PUSHDATAJSON'
                        , 0
                        , 0
                        , 0
                        , 0
                        , null
                        , now()
                        , now()
                    )";
                GGsql::exeQuery($query);
                break;
            }
            case self::insertPushtypeClsOpenForInside:
            {
                $pushdatajson = array("grpno" => $GRPNO,"clsno" => $CLSNO,);
                $this->insertWaitForInside($pushtypeClsOpen, json_encode($pushdatajson));
                break;
            }
            case self::insertPushtypeClsCancelForInside:
            {
                $pushdatajson = array("grpno" => $GRPNO,"clsno" => $CLSNO,);
                $this->insertWaitForInside($pushtypeClsCancel, json_encode($pushdatajson));
                break;
            }
            case self::insertPushtypeClsApplyStartBefore10MinForInside:
            {
                $pushdatajson = array("grpno" => $GRPNO,"clsno" => $CLSNO,);
                $this->insertWaitForInside($pushtypeClsApplyStartBefore10Min, json_encode($pushdatajson));
                break;
            }
            case self::insertPushtypeClsApplyCloseBefore1HourForInside:
            {
                $pushdatajson = array("grpno" => $GRPNO,"clsno" => $CLSNO,);
                $this->insertWaitForInside($pushtypeClsApplyCloseBefore1Hour, json_encode($pushdatajson));
                break;
            }
            case self::updatePushstatusWaitToIngForInside:
            {
                $query = "update _system_push set pushstatus = '$pushstatusIng', modidt = now() where pushidx = '$PUSHIDX'";
                GGsql::exeQuery($query);
                break;
            }
            case self::updatePushstatusIngToEndForInside:
            {
                $query = "update _system_push set pushstatus = '$pushstatusEnd', modidt = now() where pushidx = '$PUSHIDX'";
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
