<?php

class ClslineuptmpaBO extends _CommonBO
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
        GGnavi::getClslineuptmpbBO();
        GGnavi::getClslineuptmpcBO();
        $arr = array();
        $arr['ggAuth']           = GGauth::getInstance();
        $arr['clslineuptmpbBO']  = ClslineuptmpbBO::getInstance();
        $arr['clslineuptmpcBO']  = ClslineuptmpcBO::getInstance();
        return $arr;
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    const FIELD__GRPNO          = "grpno";          /* (pk) char(30)     */
    const FIELD__LINEUPGROUP    = "lineupgroup";    /* (pk) char(14)     */
    const FIELD__LINEUPTITLE    = "lineuptitle";    /* (  ) varchar(30)  */
    const FIELD__REGDT          = "REGDT";          /* (  ) datetime  */

    static public function getConsts()
    {
        return array();
    }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByGrpnoForInside($GRPNO) { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectByPkForInside($GRPNO, $LINEUPGROUP) { return $this->select(get_defined_vars(), __FUNCTION__); }
    public function selectByGrpnoLineuptitleForInside($GRPNO, $LINEUPTITLE) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /* ========================= */
    const selectByGrpno = "selectByGrpno";
    const selectByGrpnoForInside = "selectByGrpnoForInside";
    const selectByPk = "selectByPk";
    const selectByPkForInside = "selectByPkForInside";
    const selectByGrpnoLineuptitle = "selectByGrpnoLineuptitle";
    const selectByGrpnoLineuptitleForInside = "selectByGrpnoLineuptitleForInside";
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
            , t.lineuptitle
            , t.regdt
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByGrpno                        : { $from = "(select * from clslineuptmpa where grpno = '$GRPNO') t"; break; }
            case self::selectByGrpnoForInside               : { $from = "(select * from clslineuptmpa where grpno = '$GRPNO') t"; break; }
            case self::selectByPk                           : { $from = "(select * from clslineuptmpa where grpno = '$GRPNO' and lineupgroup = '$LINEUPGROUP') t"; break; }
            case self::selectByPkForInside                  : { $from = "(select * from clslineuptmpa where grpno = '$GRPNO' and lineupgroup = '$LINEUPGROUP') t"; break; }
            case self::selectByGrpnoLineuptitle             : { $from = "(select * from clslineuptmpa where grpno = '$GRPNO' and lineuptitle = '$LINEUPTITLE') t"; break; }
            case self::selectByGrpnoLineuptitleForInside    : { $from = "(select * from clslineuptmpa where grpno = '$GRPNO' and lineuptitle = '$LINEUPTITLE') t"; break; }
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
        ";
        $rslt = GGsql::select($query, $from, $options);
        return $rslt;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    public function insertOneForInside              ($GRPNO, $LINEUPGROUP, $LINEUPTITLE)    { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteByPkWithSubForInside      ($GRPNO, $LINEUPGROUP)                  { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteByPkForInside             ($GRPNO, $LINEUPGROUP)                  { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    const insertFromPage                = "insertFromPage";
    const insertOneForInside            = "insertOneForInside";
    const updateLineuptitle             = "updateLineuptitle";
    const deleteByPkWithSubForInside    = "deleteByPkWithSubForInside";
    const deleteByPkForInside           = "deleteByPkForInside";
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
            case self::insertFromPage:
            {
                /* validation : 10개 이상은 등록불가 */
                if(Common::isEmpty(trim($GRPNO)))
                    throw new GGexception("잘못된 접근입니다.");
                $records = Common::getData($this->selectByGrpnoForInside($GRPNO));
                if(count($records) >= 10)
                    throw new GGexception("라인업 템플릿은 최대 10개까지 등록 가능합니다.");

                /* validation : input */
                if(Common::isEmpty(trim($LINEUPTITLE)))
                    throw new GGexception("템플릿 이름을 입력해주세요.");

                /* validation */
                $clslineuptmpbArr = json_decode($CLSLINEUPTMPB_ARR, true);
                $clslineuptmpcArr = json_decode($CLSLINEUPTMPC_ARR, true);
                if(empty($clslineuptmpbArr)) throw new GGexception("저장할 라인업이 없습니다.");
                if(empty($clslineuptmpcArr)) throw new GGexception("저장할 라인업의 내용이 없습니다.");

                /* validation - check lineuptitle duplicated */
                $record = Common::getDataOne($this->selectByGrpnoLineuptitleForInside($GRPNO, $LINEUPTITLE));
                if($record != null)
                {
                    $LINEUPGROUP = Common::getField($record, self::FIELD__LINEUPGROUP);
                    $this->deleteByPkWithSubForInside($GRPNO, $LINEUPGROUP);
                }

                /* validation - with check duplicated */
                /* generate lineupgroup (YmdHis = 14 chars) */
                $lineupgroup = "";
                do
                {
                    $lineupgroup = date('YmdHis');
                    $record = Common::getDataOne($this->selectByPkForInside($GRPNO, $lineupgroup));
                    if($record != null)
                        sleep(1); /* wait 1 sec */
                }
                while($record != null);

                /* insert clslineuptmpa */
                $this->insertOneForInside($GRPNO, $lineupgroup, $LINEUPTITLE);

                /* insert clslineuptmpb – one row per unique lineupidx */
                foreach($clslineuptmpbArr as $dat)
                    $clslineuptmpbBO->insertOneForInside($GRPNO, $lineupgroup, $dat['LINEUPIDX'], $dat['LINEUPNAME']);

                /* insert clslineuptmpc – one row per entry */
                foreach($clslineuptmpcArr as $dat)
                {
                    $LINEUPIDX  = $dat['LINEUPIDX'];
                    $ORDERNO    = $dat['ORDERNO'];
                    $BATTINGFLG = isset($dat['BATTINGFLG']) ? $dat['BATTINGFLG'] : 'n';
                    $POSITION   = isset($dat['POSITION'])   ? $dat['POSITION']   : '';
                    $BILL       = isset($dat['BILL'])       ? $dat['BILL'] : 0;
                    $clslineuptmpcBO->insertOneForInside($GRPNO, $lineupgroup, $LINEUPIDX, $ORDERNO, $BATTINGFLG, $POSITION, $BILL);
                }

                /* set data */
                $rslt[GGF::DATA] = $lineupgroup;
                break;
            }
            case self::insertOneForInside:
            {
                $query = "insert into clslineuptmpa (grpno, lineupgroup, lineuptitle, regdt) values ('$GRPNO', '$LINEUPGROUP', '$LINEUPTITLE', now())";
                GGsql::exeQuery($query);
                break;
            }
            case self::updateLineuptitle:
            {
                $query = "update clslineuptmpa set lineuptitle = '$LINEUPTITLE' where grpno = '$GRPNO' and lineupgroup = '$LINEUPGROUP'";
                GGsql::exeQuery($query);
                break;
            }
            case self::deleteByPkWithSubForInside:
            {
                /* delete clslineuptmpb */
                $clslineuptmpbBO->deleteByLineupgroupWithSubForInside($GRPNO, $LINEUPGROUP);

                /* delete clslineuptmpa */
                $this->deleteByPkForInside($GRPNO, $LINEUPGROUP);
                break;
            }
            case self::deleteByPkForInside:
            {
                $query = "delete from clslineuptmpa where grpno = '$GRPNO' and lineupgroup = '$LINEUPGROUP'";
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
