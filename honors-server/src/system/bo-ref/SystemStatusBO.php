<?php

class SystemStatusBO extends _CommonBO
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

    /* ========================= */
    /* field */
    /* ========================= */
    const FIELD__STATUSKEY1      = "statuskey1";     /* (pk) char(20) */
    const FIELD__STATUSKEY2      = "statuskey2";     /* (pk) char(20) */
    const FIELD__STATUSVALUE     = "statusvalue";    /* (  ) enum('pass','fail') */
    const FIELD__STATUSFAILMSG   = "statusfailmsg";  /* (  ) char(50) */
    const FIELD__REGDT           = "regdt";          /* (  ) datetime */

    const STATUSKEY1__VERSION = "version"; /* 버전값 */
    const STATUSKEY1__MAINTENANCE = "maintenance"; /* 점검여부 */
    const STATUSKEY2__DEF = "def"; /* 기본값 */
    const STATUSVALUE__PASS = "pass"; /* 통과 */
    const STATUSVALUE__FAIL = "fail"; /* 실패 */

    /* ========================= */
    /* functions */
    /* ========================= */
    public function checkVersion($version)
    {
        /* return */
        $rslt = true;

        /* get record */
        $ss = $this->getByPkForInside(self::STATUSKEY1__VERSION, $version);
        if($ss == null)
            $rslt = false;

        /* check value */
        $ssValue = Common::getField($ss, self::FIELD__STATUSVALUE);
        if($ssValue != self::STATUSVALUE__PASS)
            $rslt = false;

        if($rslt == false)
            Common::returnCode("version", "지원하지 않는 버전입니다. 앱을 최신버전으로 업데이트 해주세요.");

        return $rslt;
    }

    public function checkMaintenance()
    {
        /* return */
        $rslt = true;

        /* get record */
        $ss = $this->getByPkForInside(self::STATUSKEY1__MAINTENANCE, self::STATUSKEY2__DEF);
        if($ss == null)
            $rslt = false;

        /* check value */
        $ssValue = Common::getField($ss, self::FIELD__STATUSVALUE);
        $ssFailmsg = Common::getField($ss, self::FIELD__STATUSFAILMSG);
        if($ssValue != self::STATUSVALUE__PASS)
            $rslt = false;

        if($rslt == false)
            Common::returnCode("maintenance", $ssFailmsg);

        return $rslt;
    }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function getByPkForInside($STATUSKEY1, $STATUSKEY2) { return Common::getDataOne($this->selectByPkForInside($STATUSKEY1, $STATUSKEY2)); }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByPkForInside($STATUSKEY1, $STATUSKEY2) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /* ========================= */
    const selectByPkForInside = "selectByPkForInside"; /* 메인에 표시할 공지사항 */
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
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
              t.statuskey1
            , t.statuskey2
            , t.statusvalue
            , t.statusfailmsg
            , t.regdt
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPkForInside : { $from = "(select * from _system_status where statuskey1 = '$STATUSKEY1' and statuskey2 = '$STATUSKEY2') t"; break; }
            default: { throw new GGexception("(server) no option defined"); }
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
        return GGsql::select($query, $from, $options);
    }

}
?>
