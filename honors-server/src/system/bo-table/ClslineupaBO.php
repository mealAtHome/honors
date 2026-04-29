<?php

class ClslineupaBO extends _CommonBO
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
        GGnavi::getClsBO();
        GGnavi::getClslineupbBO();
        $arr = array();
        $arr['ggAuth'] = GGauth::getInstance();
        $arr['clsBO'] = ClsBO::getInstance();
        $arr['clslineupbBO'] = ClslineupbBO::getInstance();
        return $arr;
    }

    /* ========================= */
    /* fields */
    /*
    */
    /* ========================= */
    const FIELD__GRPNO      = "grpno";      /* (pk) char(30)     */
    const FIELD__CLSNO      = "clsno";      /* (pk) char(14)     */
    const FIELD__LINEUPIDX  = "lineupidx";  /* (pk) int          */
    const FIELD__LINEUPNAME = "lineupname"; /* (  ) varchar(20)  */

    static public function getConsts()
    {
        $arr = array();
        return $arr;
    }


    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByClsnoForInside($GRPNO, $CLSNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /*
    */
    /* ========================= */
    const selectByClsno                = "selectByClsno";
    const selectByClsnoForInside       = "selectByClsnoForInside";
    protected function select($options, $option="")
    {
        /* vars */
        extract(self::getConsts());
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* --------------- */
        /* sql body */
        /* --------------- */
        $select =
        "
              t.grpno
            , t.clsno
            , t.lineupidx
            , t.lineupname
        ";
        $from = "";
        $orderby =
        "
              t.grpno
            , t.clsno
            , t.lineupidx
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByClsno         : { $from = "(select * from clslineupa where grpno = '$GRPNO' and clsno = '$CLSNO') t"; break; }
            case self::selectByClsnoForInside: { $from = "(select * from clslineupa where grpno = '$GRPNO' and clsno = '$CLSNO') t"; break; }
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
                $orderby
        ";
        $rslt = GGsql::select($query, $from, $options, $OPTION);
        return $rslt;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    public function updateFromPage($GRPNO, $CLSNO, $ARR, $ARR2, $ORDERNO) { throw new GGexceptionRule(); }
    public function copyFromClsnoWithSubForInside($GRPNO, $CLSNO, $CLSNONEW) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteByClsnoWithSubForInside($GRPNO, $CLSNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteByPkForInside($GRPNO, $CLSNO, $LINEUPIDX) { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    const updateFromPage = "updateFromPage";
    const copyFromClsnoWithSubForInside = "copyFromClsnoWithSubForInside";
    const deleteByClsnoWithSubForInside = "deleteByClsnoWithSubForInside";
    const deleteByPkForInside = "deleteByPkForInside";
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
            case self::updateFromPage:
            {
                /* validation */
                if(Common::isEmpty(trim($GRPNO))) { throw new Exception(); }
                if(Common::isEmpty(trim($CLSNO))) { throw new Exception(); }

                /* validation : auth */
                $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true);
                $ggAuth->isClsCancel($GRPNO, $CLSNO, true);

                /* get cls info */
                $cls = $clsBO->getByPk($GRPNO, $CLSNO);
                if($cls == null)
                    throw new Exception();

                /* delete lineupa by clsno */
                $this->deleteByClsnoWithSubForInside($GRPNO, $CLSNO);

                /* insert into clslineupa */
                /* insert into clslineupb */
                $arr = json_decode($ARR, true);
                foreach($arr as $dat)
                {
                    $LINEUPIDX   = Common::getField($dat, 'LINEUPIDX');
                    $LINEUPNAME  = Common::getField($dat, 'LINEUPNAME');
                    $ORDERNO     = Common::getField($dat, 'ORDERNO');
                    $BATTINGFLG  = Common::getField($dat, 'BATTINGFLG');
                    $POSITION    = Common::getField($dat, 'POSITION');
                    $USERNO      = Common::getField($dat, 'USERNO');
                    $USERNAME    = Common::getField($dat, 'USERNAME');
                    $BILL        = Common::getField($dat, 'BILL');

                    /* clslineupa */
                    $query =
                    "
                        insert into clslineupa
                        (
                              grpno
                            , clsno
                            , lineupidx
                            , lineupname
                        )
                        values
                        (
                              '$GRPNO'
                            , '$CLSNO'
                            ,  $LINEUPIDX
                            , '$LINEUPNAME'
                        )
                        on duplicate key update
                            lineupname = '$LINEUPNAME'
                    ";
                    GGsql::exeQuery($query);

                    /* clslineupb */
                    $clslineupbBO->insertOneForInside($GRPNO, $CLSNO, $LINEUPIDX, $ORDERNO, $BATTINGFLG, $POSITION, $USERNO, $USERNAME, $BILL);
                }
                break;
            }
            case self::copyFromClsnoWithSubForInside:
            {
                /* copy sub (clslineupb) */
                $clslineupbBO->copyFromClsnoWithSubForInside($GRPNO, $CLSNO, $CLSNONEW);

                /* copy main (clslineupa) */
                $query =
                "
                    insert into clslineupa
                    (
                          grpno
                        , clsno
                        , lineupidx
                        , lineupname
                    )
                    select
                           grpno
                        , '$CLSNONEW'
                        ,  lineupidx
                        ,  lineupname
                    from
                        clslineupa
                    where
                        grpno = '$GRPNO' and
                        clsno = '$CLSNO'
                ";
                GGsql::exeQuery($query);
                break;
            }
            case self::deleteByClsnoWithSubForInside:
            {
                /* validation */
                if(Common::isEmpty(trim($GRPNO))) { throw new Exception(); }
                if(Common::isEmpty(trim($CLSNO))) { throw new Exception(); }

                /* select && delete */
                $clslineupas = Common::getData($this->selectByClsnoForInside($GRPNO, $CLSNO));
                foreach($clslineupas as $clslineupa)
                {
                    $LINEUPIDX = Common::getField($clslineupa, self::FIELD__LINEUPIDX);
                    $clslineupbBO->deleteByLineupidxWithSubForInside($GRPNO, $CLSNO, $LINEUPIDX); /* delete sub table */
                    $this->deleteByPkForInside($GRPNO, $CLSNO, $LINEUPIDX);
                }

                /* check sub */
                $clslineupbs = Common::getData($clslineupbBO->selectByClsnoForInside($GRPNO, $CLSNO));
                foreach($clslineupbs as $clslineupb)
                {
                    $lineupidx = Common::getField($clslineupb, ClslineupbBO::FIELD__LINEUPIDX);
                    $orderno = Common::getField($clslineupb, ClslineupbBO::FIELD__ORDERNO);
                    $clslineupbBO->deleteByPkForInside($GRPNO, $CLSNO, $lineupidx, $orderno); /* delete main table */
                }
                break;
            }
            case self::deleteByPkForInside:
            {
                /* validation */
                if(Common::isEmpty(trim($GRPNO))) { throw new Exception(); }
                if(Common::isEmpty(trim($CLSNO))) { throw new Exception(); }
                if(Common::isEmpty(trim($LINEUPIDX))) { throw new Exception(); }

                /* process */
                $query = "delete from clslineupa where grpno = '$GRPNO' and clsno = '$CLSNO' and lineupidx = $LINEUPIDX";
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
