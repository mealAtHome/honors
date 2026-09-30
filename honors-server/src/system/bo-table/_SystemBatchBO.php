<?php

class SystemBatchBO extends _CommonBO
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
    const FIELD__BATCHNAME   = "batchname";     /* (PK) varchar(100) */
    const FIELD__PROCESSFLG  = "processflg";    /* (  ) enum('y','n') */
    const FIELD__STARTDT     = "startdt";       /* (  ) datetime */
    const FIELD__PROCEEDCNT  = "proceedcnt";    /* (  ) int(11) */
    const FIELD__FREEFIELD   = "freefield";     /* (  ) char(255) */
    const FIELD__MODIDT      = "modidt";        /* (  ) datetime */
    const FIELD__REGDT       = "regdt";         /* (  ) datetime */

    /* ========================= */
    /* enum */
    /*
    */
    /* ========================= */
    static public function getConsts()
    {
        $arr = array();
        // $arr['clsstatusEdit'] = self::CLSSTATUS__EDIT; /* 일정상태 : 작성중 */
        return $arr;
    }

    /* ========================= */
    /* main functions */
    /*
    */
    /* ========================= */
    public function lock($BATCHNAME)
    {
        /* check batchname is ing */
        $systemBatch = $this->getByPk($BATCHNAME);
        if($systemBatch && Common::get($systemBatch, self::FIELD__PROCESSFLG) === GGF::Y)
            return false;

        /* update to lock */
        $this->upsertToLockForInside($BATCHNAME);
        return true;
    }

    public function updateProceedCnt($BATCHNAME, $PROCEEDCNT)
    {
        /* update proceed count */
        $this->updateProceedcntForInside($BATCHNAME, $PROCEEDCNT);
        return true;
    }

    public function unlock($BATCHNAME)
    {
        /* update to unlock */
        $this->updateToUnlockForInside($BATCHNAME);
        return true;
    }

    /* ========================= */
    /* select > sub > sub */
    /* ========================= */
    public function getByPk($BATCHNAME) { return Common::getDataOne($this->selectByPkForInside($BATCHNAME)); }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByPkForInside($BATCHNAME) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /*
    */
    /* ========================= */
    const selectByPkForInside = "selectByPkForInside";
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
              sb.batchname
            , sb.processflg
            , sb.startdt
            , sb.proceedcnt
            , sb.freefield
            , sb.modidt
            , sb.regdt
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
            case self::selectByPkForInside : { $from = "(select * from _system_batch where batchname = '$BATCHNAME') sb"; break; }
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
        ";
        $rslt = GGsql::select($query, $from, $options, $OPTION);
        return $rslt;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    public function upsertToLockForInside($BATCHNAME) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function updateProceedcntForInside($BATCHNAME, $PROCEEDCNT) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function updateToUnlockForInside($BATCHNAME) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function updateFreefieldForInside($BATCHNAME, $FREEFIELD) { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    const upsertToLockForInside = "upsertToLockForInside"; /* BATCHNAME */
    const updateProceedcntForInside = "updateProceedcntForInside"; /* BATCHNAME, PROCEEDCNT */
    const updateToUnlockForInside = "updateToUnlockForInside"; /* BATCHNAME */
    const updateFreefieldForInside = "updateFreefieldForInside"; /* BATCHNAME, FREEFIELD */
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

        /* =============== */
        /* process */
        /* =============== */
        switch($OPTION)
        {
            case self::upsertToLockForInside:
            {
                /* query */
                $query =
                "
                    insert into _system_batch
                    (
                          batchname
                        , processflg
                        , startdt
                        , proceedcnt
                        , modidt
                        , regdt
                    )
                    values
                    (
                          '$BATCHNAME'
                        , 'y'
                        ,  now()
                        ,  0
                        ,  now()
                        ,  now()
                    )
                    on duplicate key update
                          processflg = 'y'
                        , startdt = now()
                        , proceedcnt = 0
                        , modidt = now()
                ";
                GGsql::exeQuery($query);
                break;
            }
            case self::updateProceedcntForInside:
            {
                $query = "update _system_batch set proceedcnt = $PROCEEDCNT, modidt = now() where batchname = '$BATCHNAME'";
                GGsql::exeQuery($query);
                break;
            }
            case self::updateToUnlockForInside:
            {
                $query = "update _system_batch set processflg = 'n', modidt = now() where batchname = '$BATCHNAME'";
                GGsql::exeQuery($query);
                break;
            }
            case self::updateFreefieldForInside:
            {
                $query = "update _system_batch set freefield = '$FREEFIELD', modidt = now() where batchname = '$BATCHNAME'";
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
