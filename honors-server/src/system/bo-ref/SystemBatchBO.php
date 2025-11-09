<?php

/* sysb */
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

    public function __construct()
    {
    }

    /* fields */
    const FIELD__BATCHNAME         = "batchname";         /* char(50) */
    const FIELD__ISLOCK            = "islock";            /* enum('y', 'n') default 'n' */
    const FIELD__AT_START          = "at_start";          /* datetime */
    const FIELD__AT_END            = "at_end";            /* datetime */
    const FIELD__BATCHCNT_ALL      = "batchcnt_all";      /* int default 0 */
    const FIELD__BATCHCNT_SUCCEED  = "batchcnt_succeed";  /* int default 0 */
    const FIELD__BATCHCNT_FAILED   = "batchcnt_failed";   /* int default 0 */
    const FIELD__BATCHCNT_SKIP     = "batchcnt_skip";     /* int default 0 */
    const FIELD__AT_HEALTHCHECK    = "at_healthcheck";    /* datetime */
    const FIELD__FREEFIELD1        = "freefield1";        /* char(50) default '' */
    const FIELD__FREEFIELD2        = "freefield2";        /* char(50) default '' */
    const FIELD__FREEFIELD3        = "freefield3";        /* char(50) default '' */
    const FIELD__FREEFIELD4        = "freefield4";        /* char(50) default '' */
    const FIELD__FREEFIELD5        = "freefield5";        /* char(50) default '' */
    const FIELD__AT_UPDATE         = "at_update";         /* datetime */
    const FIELD__AT_REGIST         = "at_regist";         /* datetime */

    /* ========================= */
    /* 중복실행방지 */
    /* ========================= */
    public function doLock($BATCHNAME)
    {
        $record = Common::getDataOne($this->selectByPkForInside($BATCHNAME));
        $isLock = Common::get($record, self::FIELD__ISLOCK);

        if($isLock == GGF::Y)
            return false;

        $this->upsertStartLockForInside($BATCHNAME);
        return true;
    }

    /* ========================= */
    /*  */
    /*

    */
    /* ========================= */
    public function selectByPkForInside ($BATCHNAME) { return $this->select(get_defined_vars(), __FUNCTION__); }

    const selectByPkForInside = "selectByPkForInside";
    protected function select($options, $option="")
    {
        /* -------------- */
        /* vars */
        /* -------------- */
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
             sysb.batchname
            ,sysb.islock
            ,sysb.at_start
            ,sysb.at_end
            ,sysb.batchcnt_all
            ,sysb.batchcnt_succeed
            ,sysb.batchcnt_failed
            ,sysb.batchcnt_skip
            ,sysb.at_healthcheck
            ,sysb.freefield1
            ,sysb.freefield2
            ,sysb.freefield3
            ,sysb.freefield4
            ,sysb.freefield5
            ,sysb.at_update
            ,sysb.at_regist
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPkForInside:
            {
                $from = "(select * from system_batch where batchname = '$BATCHNAME') sysb";
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        } /* end switch($OPTION) */

        /* --------------- */
        /* make query */
        /* --------------- */
        $query =
        "
            select
                $select
            from
                $from
        ";

        /* --------------- */
        /* execute query */
        /* --------------- */
        return GGsql::select($query, $from, $options);
    }

    /* ========================= */
    /* */
    /*

    */
    /* ========================= */
    public function insertByBatchnameForInside ($BATCHNAME) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function upsertStartLockForInside   ($BATCHNAME) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function updateUnlockForInside      ($BATCHNAME) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function updateFreeField1ForInside  ($BATCHNAME, $FREEFIELD1) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function updateForInside(
          $BATCHNAME
        , $ISLOCK=""
        , $AT_START=""
        , $AT_END=""
        , $BATCHCNT_ALL=""
        , $BATCHCNT_SUCCEED=""
        , $BATCHCNT_FAILED=""
        , $BATCHCNT_SKIP=""
        , $FREEFIELD1=""
        , $FREEFIELD2=""
        , $FREEFIELD3=""
        , $FREEFIELD4=""
        , $FREEFIELD5=""
    )
    {
        return $this->update(get_defined_vars(), __FUNCTION__);
    }


    const insertByBatchnameForInside        = "insertByBatchnameForInside"; /* 배치 이름만으로 레코드 등록 */
    const upsertStartLockForInside          = "upsertStartLockForInside"; /* lock 처리 */
    const updateUnlockForInside             = "updateUnlockForInside"; /* unlock 처리 */
    const updateForInside                   = "updateForInside"; /* 레코드 업데이트 */
    const updateFreeField1ForInside         = "updateFreeField1ForInside"; /* freefield1 업데이트 */
    protected function update($options, $option)
    {
        /* vars */
        $rslt = Common::getReturn();
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* ==================== */
        /* process */
        /* ==================== */
        switch($OPTION)
        {
            case self::insertByBatchnameForInside:
            {
                /* SQL */
                $query =
                "
                    insert into system_batch (batchname, at_regist) values ('$BATCHNAME', now())
                    on duplicate key update
                         at_update = now()
                        ,at_healthcheck = now()
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::upsertStartLockForInside:
            {
                $systemBatch = Common::getDataOne($this->selectByPkForInside($BATCHNAME));
                if($systemBatch == null)
                    $this->insertByBatchnameForInside($BATCHNAME);

                /* SQL */
                $query =
                "
                    update
                        system_batch
                    set
                         islock = 'y'
                        ,at_start = now()
                        ,at_update = now()
                        ,at_healthcheck = now()
                    where
                        batchname = '$BATCHNAME'
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::updateForInside:
            {
                $query =
                "
                    update
                        system_batch
                    set
                         at_update = now()
                        ,at_healthcheck = now()
                ";
                if(isset($ISLOCK)           == true && $ISLOCK           != "") $query .= " ,islock            = '$ISLOCK' ";
                if(isset($AT_START)         == true && $AT_START         != "") $query .= " ,at_start          = '$AT_START' ";
                if(isset($AT_END)           == true && $AT_END           != "") $query .= " ,at_end            = '$AT_END' ";
                if(isset($BATCHCNT_ALL)     == true && $BATCHCNT_ALL     != "") $query .= " ,batchcnt_all      =  $BATCHCNT_ALL ";
                if(isset($BATCHCNT_SUCCEED) == true && $BATCHCNT_SUCCEED != "") $query .= " ,batchcnt_succeed  =  $BATCHCNT_SUCCEED ";
                if(isset($BATCHCNT_FAILED)  == true && $BATCHCNT_FAILED  != "") $query .= " ,batchcnt_failed   =  $BATCHCNT_FAILED ";
                if(isset($BATCHCNT_SKIP)    == true && $BATCHCNT_SKIP    != "") $query .= " ,batchcnt_skip     =  $BATCHCNT_SKIP ";
                if(isset($AT_HEALTHCHECK)   == true && $AT_HEALTHCHECK   != "") $query .= " ,at_healthcheck    =  $AT_HEALTHCHECK ";
                if(isset($FREEFIELD1)       == true && $FREEFIELD1       != "") $query .= " ,freefield1        = '$FREEFIELD1'";
                if(isset($FREEFIELD2)       == true && $FREEFIELD2       != "") $query .= " ,freefield2        = '$FREEFIELD2'";
                if(isset($FREEFIELD3)       == true && $FREEFIELD3       != "") $query .= " ,freefield3        = '$FREEFIELD3'";
                if(isset($FREEFIELD4)       == true && $FREEFIELD4       != "") $query .= " ,freefield4        = '$FREEFIELD4'";
                if(isset($FREEFIELD5)       == true && $FREEFIELD5       != "") $query .= " ,freefield5        = '$FREEFIELD5'";
                $query .= " where batchname = '$BATCHNAME'";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::updateFreeField1ForInside:
            {
                $query =
                "
                    update
                        system_batch
                    set
                         at_update = now()
                        ,at_healthcheck = now()
                        ,freefield1 = '$FREEFIELD1'
                    where
                        batchname = '$BATCHNAME'
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::updateUnlockForInside:
            {
                $query =
                "
                    update
                        system_batch
                    set
                         at_update = now()
                        ,at_end = now()
                        ,islock = 'n'
                    where
                        batchname = '$BATCHNAME'
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        } /* end switch */
        return $rslt;
    }

} /* end class */
?>
