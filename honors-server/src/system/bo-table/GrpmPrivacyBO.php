<?php

/* grpm_privacy, grpmp */
class GrpmPrivacyBO extends _CommonBO
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
            , t.grpno
            , t.userno
            , t.priv_phone
            , t.modidt
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPk            : { $from = "(select * from grpm_privacy where grpno = '$GRPNO' and userno = '$USERNO') t"; break; }
            case self::selectByPkForInside   : { $from = "(select * from grpm_privacy where grpno = '$GRPNO' and userno = '$USERNO') t"; break; }
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
                  t.grpno asc
                , t.userno desc
        ";
        $rslt = GGsql::select($query, $from, $options);
        return $rslt;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    public function insertDefaultForInside($GRPNO, $USERNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function upsertByGrpmArrForInside($USERNO, $PRIV_PHONE_GRPM) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteByUsernoForInside($USERNO) { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    const insertDefaultForInside = "insertDefaultForInside"; /* GRPNO, USERNO */
    const upsertByGrpmArrForInside = "upsertByGrpmArrForInside"; /* USERNO, PRIV_PHONE_GRPM */
    const deleteByUsernoForInside = "deleteByUsernoForInside"; /* USERNO */
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
                if(Common::isEmpty($GRPNO))   { throw new GGexception("시스템 오류입니다."); }
                if(Common::isEmpty($USERNO))  { throw new GGexception("시스템 오류입니다."); }

                /* insert */
                $query = "insert ignore into grpm_privacy (grpno, userno, priv_phone, modidt) values ('$GRPNO', '$USERNO', null, now())";
                $rslt = GGsql::exeQuery($query);
                break;
            }
            case self::upsertByGrpmArrForInside:
            {
                /* validation */
                if(Common::isEmpty($USERNO))   { throw new GGexception("시스템 오류입니다."); }
                if(!is_array($PRIV_PHONE_GRPM))       { throw new GGexception("시스템 오류입니다."); }

                /* insert */
                $arr = $PRIV_PHONE_GRPM;
                foreach($arr as $dat)
                {
                    $GRPNO      = $dat['GRPNO'];
                    $PRIV_PHONE = $dat['PRIV_PHONE'];
                    $PRIV_PHONE = $PRIV_PHONE == "not" ? "null" : "'$PRIV_PHONE'";

                    $query =
                    "
                        insert into grpm_privacy
                        (
                              grpno
                            , userno
                            , priv_phone
                            , modidt
                        )
                        values
                        (
                              '$GRPNO'
                            , '$USERNO'
                            ,  $PRIV_PHONE
                            , now()
                        )
                        on duplicate key update
                              priv_phone = $PRIV_PHONE
                            , modidt = now()
                    ";
                    $rslt = GGsql::exeQuery($query);
                }
                break;
            }
            case self::deleteByUsernoForInside: { $query = "delete from grpm_privacy where userno = '$USERNO'"; $rslt = GGsql::exeQuery($query); break; }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }
        return $rslt;
    }

}
?>
