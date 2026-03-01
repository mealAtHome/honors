<?php

class ClslineupblogBO extends _CommonBO
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
    const FIELD__GRPNO              = "grpno";              /* (pk) char(30) */
    const FIELD__CLSNO              = "clsno";              /* (pk) char(14) */
    const FIELD__LINEUPIDX           = "lineupidx";           /* (pk) char(10) */
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
    /*
    */
    /* ========================= */
    const selectByClsno = "selectByClsno";
    // const selectByPkForMng = "selectByPkForMng";
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
              t.grpno
            , t.clsno
            , t.lineupidx
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
            case self::selectByClsno : { $from = "(select * from clslineupb where grpno = '$GRPNO' and clsno = '$CLSNO') t"; break; }
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
                , t.lineupidx
                , t.orderno
                , t.logno
        ";
        $rslt = GGsql::select($query, $from, $options);
        return $rslt;
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
        $rslt = Common::getReturn();
        extract($this->setBO());
        extract(self::getConsts());
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* =============== */
        /* process */
        /* =============== */
        switch($OPTION)
        {
            case self::deleteByClsnoForInside:
            {
                $query = "delete from clslineupb where grpno = '$GRPNO' and clsno = '$CLSNO'";
                GGsql::exeQuery($query);
                break;
            }
            case self::updateFromPage:
            {
                /* validation */
                $ggAuth->isGrpmanager($GRPNO, $EXECUTOR); /* is grp manager */
                if(Common::isEmpty(trim($GRPNO))) { throw new GGexception(); }
                if(Common::isEmpty(trim($CLSNO))) { throw new GGexception(); }

                /* delete first */
                $this->deleteByClsnoForInside($GRPNO, $CLSNO);

                /* insert */
                $arr = json_decode($ARR, true);
                foreach($arr as $dat)
                {
                    $LINEUPIDX   = $dat['LINEUPIDX'];
                    $ORDERNO    = $dat['ORDERNO'];
                    $BATTINGFLG = $dat['BATTINGFLG'];
                    $POSITION   = $dat['POSITION'];
                    $USERNO     = $dat['USERNO'];
                    $USERNAME   = $dat['USERNAME'];
                    $BILL       = $dat['BILL'];

                    /* update */
                    $query =
                    "
                        insert into clslineupb
                        (
                              grpno
                            , clsno
                            , lineupidx
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
                            , $LINEUPIDX
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
        return $rslt;
    }

} /* end class */
?>
