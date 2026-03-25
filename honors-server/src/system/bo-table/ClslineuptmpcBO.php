<?php

class ClslineuptmpcBO extends _CommonBO
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
        return $arr;
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    const FIELD__GRPNO                      = "grpno";                          /* (pk) char(30)        */
    const FIELD__LINEUPGROUP                = "lineupgroup";                    /* (pk) char(14)        */
    const FIELD__LINEUPIDX                  = "lineupidx";                      /* (pk) char(10)        */
    const FIELD__ORDERNO                    = "orderno";                        /* (pk) int             */
    const FIELD__BATTINGFLG                 = "battingflg";                     /* (  ) enum('n','y')   */
    const FIELD__POSITION                   = "position";                       /* (  ) char(10)        */
    const FIELD__ISFOLLOWSTANDARDBILL       = "isfollowstandardbill";           /* (  ) enum('n','y')   */
    const FIELD__BILL                       = "bill";                           /* (  ) int             */

    static public function getConsts()
    {
        return array();
    }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByLineupidxForInside($GRPNO, $LINEUPGROUP, $LINEUPIDX) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /* ========================= */
    const selectByLineupgroup = "selectByLineupgroup";
    const selectByLineupidx = "selectByLineupidx";
    const selectByLineupidxForInside = "selectByLineupidxForInside";
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
        $from  = "";
        $query = "";
        $select =
        "
            null as head
            , t.grpno
            , t.lineupgroup
            , t.lineupidx
            , t.orderno
            , t.battingflg
            , t.position
            , t.isfollowstandardbill
            , t.bill
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByLineupgroup          : { $from = "(select * from clslineuptmpc where grpno = '$GRPNO' and lineupgroup = '$LINEUPGROUP') t"; break; }
            case self::selectByLineupidx            : { $from = "(select * from clslineuptmpc where grpno = '$GRPNO' and lineupgroup = '$LINEUPGROUP' and lineupidx = $LINEUPIDX) t"; break; }
            case self::selectByLineupidxForInside   : { $from = "(select * from clslineuptmpc where grpno = '$GRPNO' and lineupgroup = '$LINEUPGROUP' and lineupidx = $LINEUPIDX) t"; break; }
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
                , t.lineupgroup
                , t.lineupidx
                , t.orderno
        ";
        $rslt = GGsql::select($query, $from, $options);
        return $rslt;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    public function insertOneForInside($GRPNO, $LINEUPGROUP, $LINEUPIDX, $ORDERNO, $BATTINGFLG, $POSITION, $BILL)  { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteByLineupidxWithSubForInside($GRPNO, $LINEUPGROUP, $LINEUPIDX) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteByPkForInside($GRPNO, $LINEUPGROUP, $LINEUPIDX, $ORDERNO) { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    const insertOneForInside = "insertOneForInside";
    const deleteByLineupidxWithSubForInside = "deleteByLineupidxWithSubForInside";
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
            case self::insertOneForInside:
            {
                $bill = intval($BILL);
                $query =
                "
                    insert into clslineuptmpc
                    (
                          grpno
                        , lineupgroup
                        , lineupidx
                        , orderno
                        , battingflg
                        , position
                        , isfollowstandardbill
                        , bill
                    )
                    values
                    (
                          '$GRPNO'
                        , '$LINEUPGROUP'
                        ,  $LINEUPIDX
                        ,  $ORDERNO
                        , '$BATTINGFLG'
                        , '$POSITION'
                        , 'n'
                        ,  $bill
                    )
                ";
                GGsql::exeQuery($query);
                break;
            }
            case self::deleteByLineupidxWithSubForInside:
            {
                if($GRPNO       == "") throw new Exception("GRPNO is empty");
                if($LINEUPGROUP == "") throw new Exception("LINEUPGROUP is empty");
                if($LINEUPIDX   == "") throw new Exception("LINEUPIDX is empty");

                /* delete sub */
                /* no sub */

                /* loop */
                $records = Common::getData($this->selectByLineupidxForInside($GRPNO, $LINEUPGROUP, $LINEUPIDX));
                foreach($records as $record)
                {
                    $ORDERNO = Common::getField($record, self::FIELD__ORDERNO);
                    $this->deleteByPkForInside($GRPNO, $LINEUPGROUP, $LINEUPIDX, $ORDERNO);
                }
                break;
            }
            case self::deleteByPkForInside:
            {
                if($GRPNO       == "") throw new Exception("GRPNO is empty");
                if($LINEUPGROUP == "") throw new Exception("LINEUPGROUP is empty");
                if($LINEUPIDX   == "") throw new Exception("LINEUPIDX is empty");
                if($ORDERNO     == "") throw new Exception("ORDERNO is empty");
                $query = "delete from clslineuptmpc where grpno = '$GRPNO' and lineupgroup = '$LINEUPGROUP' and lineupidx = $LINEUPIDX and orderno = $ORDERNO";
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
