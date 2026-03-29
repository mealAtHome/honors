<?php

class ClslineuptmpbBO extends _CommonBO
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
        GGnavi::getClslineuptmpcBO();
        $arr = array();
        $arr['ggAuth']           = GGauth::getInstance();
        $arr['clslineuptmpcBO']  = ClslineuptmpcBO::getInstance();
        return $arr;
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    const FIELD__GRPNO       = "grpno";       /* (pk) char(30)     */
    const FIELD__LINEUPGROUP = "lineupgroup"; /* (pk) char(14)     */
    const FIELD__LINEUPIDX   = "lineupidx";   /* (pk) int          */
    const FIELD__LINEUPNAME  = "lineupname";  /* (  ) varchar(20)  */

    static public function getConsts()
    {
        return array();
    }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByLineupgroupForInside($GRPNO, $LINEUPGROUP) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /* ========================= */
    const selectByGrpno   = "selectByGrpno";
    const selectByLineupgroup = "selectByLineupgroup";
    const selectByLineupgroupForInside = "selectByLineupgroupForInside";
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
        $from = "";
        $select =
        "
            null as head
            , t.grpno
            , t.lineupgroup
            , t.lineupidx
            , t.lineupname
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByGrpno                     : { $from = "(select * from clslineuptmpb where grpno = '$GRPNO') t"; break; }
            case self::selectByLineupgroup               : { $from = "(select * from clslineuptmpb where grpno = '$GRPNO' and lineupgroup = '$LINEUPGROUP') t"; break; }
            case self::selectByLineupgroupForInside      : { $from = "(select * from clslineuptmpb where grpno = '$GRPNO' and lineupgroup = '$LINEUPGROUP') t"; break; }
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
        ";
        $rslt = GGsql::select($query, $from, $options, $OPTION);
        return $rslt;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    public function insertOneForInside($GRPNO, $LINEUPGROUP, $LINEUPIDX, $LINEUPNAME)  { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteByLineupgroupWithSubForInside($GRPNO, $LINEUPGROUP) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteByPkForInside($GRPNO, $LINEUPGROUP, $LINEUPIDX) { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    const insertOneForInside = "insertOneForInside";
    const deleteByLineupgroupWithSubForInside = "deleteByLineupgroupWithSubForInside";
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
                $query =
                "
                    insert into clslineuptmpb
                    (
                          grpno
                        , lineupgroup
                        , lineupidx
                        , lineupname
                    )
                    values
                    (
                          '$GRPNO'
                        , '$LINEUPGROUP'
                        ,  $LINEUPIDX
                        , '$LINEUPNAME'
                    )
                ";
                GGsql::exeQuery($query);
                break;
            }
            case self::deleteByLineupgroupWithSubForInside:
            {
                if($GRPNO       == "") throw new Exception("GRPNO is empty");
                if($LINEUPGROUP == "") throw new Exception("LINEUPGROUP is empty");

                /* loop */
                $records = Common::getData($this->selectByLineupgroupForInside($GRPNO, $LINEUPGROUP));
                foreach($records as $record)
                {
                    $LINEUPIDX = Common::getField($record, self::FIELD__LINEUPIDX);
                    $clslineuptmpcBO->deleteByLineupidxWithSubForInside($GRPNO, $LINEUPGROUP, $LINEUPIDX);
                    $this->deleteByPkForInside($GRPNO, $LINEUPGROUP, $LINEUPIDX);
                }
                break;
            }
            case self::deleteByPkForInside:
            {
                if($GRPNO       == "") throw new Exception("GRPNO is empty");
                if($LINEUPGROUP == "") throw new Exception("LINEUPGROUP is empty");
                if($LINEUPIDX   == "") throw new Exception("LINEUPIDX is empty");
                $query = "delete from clslineuptmpb where grpno = '$GRPNO' and lineupgroup = '$LINEUPGROUP' and lineupidx = $LINEUPIDX";
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
