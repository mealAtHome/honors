<?php

/* grpmtagb, gmtb */
class GrpmtagbBO extends _CommonBO
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
        GGnavi::getGrpmtagaBO();
        $arr = array();
        $arr['ggAuth'] = GGauth::getInstance();
        $arr['grpmtagaBO'] = GrpmtagaBO::getInstance();
        return $arr;
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    const FIELD__GRPNO  = "grpno";  /* (PK) char(30) */
    const FIELD__TAGIDX = "tagidx"; /* (PK) int */
    const FIELD__USERNO = "userno"; /* (PK) char(30) */
    const FIELD__REGDT  = "regdt";  /* (  ) datetime */

    static public function getConsts()
    {
        $arr = array();
        return $arr;
    }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByGrpnoTagidxForInside ($GRPNO, $TAGIDX) { return $this->select(get_defined_vars(), self::selectByGrpnoTagidx); }
    public function selectByGrpnoUsernoArrForInside ($GRPNO, $USERNO_ARR) { return $this->select(get_defined_vars(), self::selectByGrpnoUsernoArrForInside); }

    /* ========================= */
    /* select */
    /* ========================= */
    const selectByGrpnoTagidx = "selectByGrpnoTagidx";
    const selectByGrpnoUsernoArrForInside = "selectByGrpnoUsernoArrForInside";
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
            , t.tagidx
            , t.userno
            , t.regdt
            , u.name username
            , ta.tagname
            , ta.tagcolorfont
            , ta.tagcolorback
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByGrpnoTagidx : { $from = "(select * from grpmtagb where grpno = '$GRPNO' and tagidx = $TAGIDX) t"; break; }
            case self::selectByGrpnoUsernoArrForInside:
            {
                $usernoInStr = "''";
                if(is_array($USERNO_ARR) && count($USERNO_ARR) > 0)
                    $usernoInStr = "'".implode("','", $USERNO_ARR)."'";
                $from = "(select * from grpmtagb where grpno = '$GRPNO' and userno in ($usernoInStr)) t";
                break;
            }
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
                left join user u
                    on
                        u.userno = t.userno
                left join grpmtaga ta
                    on
                        ta.grpno = t.grpno and ta.tagidx = t.tagidx
            order by
                t.regdt asc
        ";
        $rslt = GGsql::select($query, $from, $options, $OPTION);
        return $rslt;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    public function deleteByGrpnoTagidxForInside($GRPNO, $TAGIDX)
    {
        $query = "delete from grpmtagb where grpno = '$GRPNO' and tagidx = $TAGIDX";
        GGsql::exeQuery($query);
    }

    /* ========================= */
    /* update */
    /* ========================= */
    const bulkSetForMng = "bulkSetForMng";
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
            case self::bulkSetForMng:
            {
                /* 권한체크 : 모임 매니저(부매니저 포함)만 가능 */
                $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true);

                /* validation */
                if(Common::isEmpty($GRPNO))  { throw new GGexception("시스템 오류입니다."); }
                if(Common::isEmpty($TAGIDX)) { throw new GGexception("시스템 오류입니다."); }
                if($ARR == null || is_array($ARR) == false)
                    $ARR = array();
                if(Common::getDataOneField($grpmtagaBO->selectByPkForInside($GRPNO, $TAGIDX), GrpmtagaBO::FIELD__TAGIDX) == null)
                    throw new GGexception("존재하지 않는 태그입니다.");

                /* 현재 등록된 멤버 */
                $currentRslt = $this->selectByGrpnoTagidxForInside($GRPNO, $TAGIDX);
                $currentUsernos = array();
                foreach(Common::getData($currentRslt) as $row)
                    $currentUsernos[] = Common::get($row, self::FIELD__USERNO);

                /* diff */
                $toDelete = array_diff($currentUsernos, $ARR);
                $toInsert = array_diff($ARR, $currentUsernos);

                /* 해제 */
                foreach($toDelete as $userno)
                {
                    $query = "delete from grpmtagb where grpno = '$GRPNO' and tagidx = $TAGIDX and userno = '$userno'";
                    GGsql::exeQuery($query);
                }

                /* 신규 등록 */
                foreach($toInsert as $userno)
                {
                    $query =
                    "
                        insert into grpmtagb (grpno, tagidx, userno, regdt)
                        values ('$GRPNO', $TAGIDX, '$userno', now())
                    ";
                    GGsql::exeQuery($query);
                }

                /* 등록건수 재계산 */
                $grpmtagaBO->recalTagregcntForInside($GRPNO, $TAGIDX);
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
