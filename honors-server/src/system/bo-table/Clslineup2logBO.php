<?php

class Clslineup2logBO extends _CommonBO
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
    /*
    */
    /* ========================= */
    const FIELD__GRPNO              = "grpno";              /* (pk) char(30) */
    const FIELD__CLSNO              = "clsno";              /* (pk) char(14) */
    const FIELD__TEAMNAME           = "teamname";           /* (pk) char(10) */
    const FIELD__ORDERNO            = "orderno";            /* (pk) int */
    const FIELD__LOGNO              = "logno";              /* (pk) int */
    const FIELD__LOGTYPE            = "logtype";            /* (  ) enum('insert','delete') */
    const FIELD__USERNO             = "userno";             /* (  ) varchar(30) */
    const FIELD__REGDT              = "regdt";              /* (  ) datetime */

    /* ========================= */
    /* enum */
    /*
    */
    /* ========================= */
    // const CLSSTATUS__EDIT           = "edit";               /* 작성중 */
    // const CLSSTATUS__ING            = "ing";                /* 진행중 */
    // const CLSSTATUS__END         = "end";             /* 일정완료 */

    /* ========================= */
    /* select options */
    /* ========================= */
    static public function getConsts()
    {
        $arr = array();
        // $arr['clsstatusEdit']                   = self::CLSSTATUS__EDIT;        /* 일정상태 : 작성중 */
        // $arr['clsstatusIng']                    = self::CLSSTATUS__ING;         /* 일정상태 : 진행중 */
        // $arr['clsstatusEnd']                 = self::CLSSTATUS__END;      /* 일정상태 : 일정완료 */
        return $arr;
    }


    /* ========================= */
    /* select > sub */
    /* ========================= */
    // public function selectByBiznameForInside ($BIZNAME) { return $this->select(get_defined_vars(), __FUNCTION__); }
    // public function selectByPkForInside                                  ($STORENO)                     { return $this->select(get_defined_vars(), __FUNCTION__); }
    // public function selectByUsernoForInside                              ($USERNO)                      { return $this->select(get_defined_vars(), __FUNCTION__); }
    // public function selectForDeliveryMatchingByRiderGpsForInside         ($RIDER_LATIY,$RIDER_LONGX)    { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /* ========================= */
    const selectByClsno = "selectByClsno";
    // const selectByPkForMng = "selectByPkForMng";
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        extract(Clslineup2logBO::getConsts());
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
              t.grpno
            , t.clsno
            , t.teamname
            , t.orderno
            , t.logno
            , t.logtype
            , t.userno
            , t.regdt
        ";

        /* --------------- */
        /* validation */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByClsno : { break; }
        }

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByClsno : { $from = "(select * from clslineup2 where grpno = '$GRPNO' and clsno = '$CLSNO') t"; break; }
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
                  t.grpno
                , t.clsno
                , t.teamname
                , t.orderno
                , t.logno
        ";
        $result = GGsql::select($query, $from, $options);
        return $result;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    public function deleteByClsnoForInside($GRPNO, $CLSNO) { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    const deleteByClsnoForInside = "deleteByClsnoForInside";
    const updateFromPage = "updateFromPage";
    protected function update($options, $option="")
    {
        /* vars */
        $clsno = null;

        /* get vars */
        extract(Clslineup2logBO::getConsts());
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* =============== */
        /* auth */
        /* =============== */
        switch($OPTION)
        {
            case self::updateFromPage: { /* TODO : is manager of grp? */ break; }
        }

        /* =============== */
        /* validation (common) */
        /* =============== */
        switch($OPTION)
        {
            case self::updateFromPage:
            {
                break;
            }
        }

        /* =============== */
        /* validation (key) */
        /* =============== */
        switch($OPTION)
        {
            case self::updateFromPage:
            {
                if(Common::isEmpty(trim($GRPNO)) ) { throw new GGexception(); }
                if(Common::isEmpty(trim($CLSNO))    ) { throw new GGexception(); }
                break;
            }
        }

        /* =============== */
        /* sql execution */
        /* =============== */
        switch($OPTION)
        {
            case self::deleteByClsnoForInside:
            {
                $query = "delete from clslineup2 where grpno = '$GRPNO' and clsno = '$CLSNO'";
                GGsql::exeQuery($query);
                break;
            }
            case self::updateFromPage:
            {
                /* delete first */
                $this->deleteByClsnoForInside($GRPNO, $CLSNO);

                /* insert */
                $arr = json_decode($ARR, true);
                foreach($arr as $dat)
                {
                    $TEAMNAME   = $dat['TEAMNAME'];
                    $ORDERNO    = $dat['ORDERNO'];
                    $BATTINGFLG = $dat['BATTINGFLG'];
                    $POSITION   = $dat['POSITION'];
                    $USERNO     = $dat['USERNO'];
                    $USERNAME   = $dat['USERNAME'];
                    $BILL       = $dat['BILL'];

                    /* update */
                    $query =
                    "
                        insert into clslineup2
                        (
                              grpno
                            , clsno
                            , teamname
                            , orderno
                            , battingflg
                            , position
                            , userno
                            , username
                            , bill
                        )
                        values
                        (
                              '$GRPNO'
                            , '$CLSNO'
                            , '$TEAMNAME'
                            ,  $ORDERNO
                            , '$BATTINGFLG'
                            , '$POSITION'
                            , '$USERNO'
                            , '$USERNAME'
                            ,  $BILL
                        )
                        on duplicate key update
                              battingflg = '$BATTINGFLG'
                            , position   = '$POSITION'
                            , userno     = '$USERNO'
                            , username   = '$USERNAME'
                            , bill       =  $BILL
                    ";
                    GGsql::exeQuery($query);
                }
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }
        return $clsno;
    }

} /* end class */
?>
