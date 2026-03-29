<?php

/* user_privacy, userp */
class UserPrivacyBO extends _CommonBO
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
        $arr = array();
        $arr['ggAuth'] = GGauth::getInstance();
        return $arr;
    }

    /* ========================= */
    /* fields */
    /*
    */
    /* ========================= */
    const FIELD__GRPNO              = "grpno";              /* (PK) char(30) */
    const FIELD__USERNO             = "userno";             /* (PK) char(30) */
    const FIELD__PRIV_PHONE         = "priv_phone";         /* (  ) enum('all','grp','mng','any') */
    const FIELD__MODIDT             = "modidt";             /* (  ) datetime */

    /* ========================= */
    /* enum */
    /*
    */
    /* ========================= */
    const PRIVECY__ALL = "all";
    const PRIVECY__GRP = "grp";
    const PRIVECY__MNG = "mng";
    const PRIVECY__ANY = "any";
    static public function getConsts()
    {
        $arr = array();
        // $arr['key'] = "value";
        return $arr;
    }

    /* ========================= */
    /* select > sub > sub */
    /* ========================= */
    // public function getByPk($GRPNO) { return GGsql::selectOne("select * from grp where grpno = '$GRPNO'"); }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByPkForInside ($GRPNO, $USERNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /*
    */
    /* ========================= */
    const selectByPk = "selectByPk";
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
            null as head
            , t.userno
            , t.priv_phone
            , t.modidt
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPk            : { $from = "(select * from user_privacy where userno = '$USERNO') t"; break; }
            case self::selectByPkForInside   : { $from = "(select * from user_privacy where userno = '$USERNO') t"; break; }
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
                t.userno asc
        ";
        $rslt = GGsql::select($query, $from, $options, $OPTION);
        return $rslt;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    public function insertDefaultForInside($USERNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function upsertByPkForInside($USERNO, $PRIV_PHONE) { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    const insertDefaultForInside = "insertDefaultForInside"; /* USERNO */
    const upsertByPkForInside = "upsertByPkForInside"; /* USERNO, PRIV_PHONE */
    /* const updateBaccnodefaultForInside = "updateBaccnodefaultForInside"; */
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
            case self::insertDefaultForInside:
            {
                /* validation */
                if(Common::isEmpty($USERNO)) { throw new GGexception("시스템 오류입니다."); }

                /* process */
                $query = "insert into user_privacy (userno, modidt) values ('$USERNO', now())";
                $rslt = GGsql::exeQuery($query);
                break;
            }
            case self::upsertByPkForInside:
            {
                /* validation */
                if(Common::isEmpty($USERNO))     { throw new GGexception("시스템 오류입니다."); }
                if(Common::isEmpty($PRIV_PHONE)) { throw new GGexception("시스템 오류입니다."); }

                /* process */
                $query =
                "
                    insert into user_privacy
                    (
                          userno
                        , priv_phone
                        , modidt
                    )
                    values
                    (
                          '$USERNO'
                        , '$PRIV_PHONE'
                        , now()
                    )
                    on duplicate key update
                          priv_phone = '$PRIV_PHONE'
                        , modidt = now()
                ";
                $rslt = GGsql::exeQuery($query);
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
