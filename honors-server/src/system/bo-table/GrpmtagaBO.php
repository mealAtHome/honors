<?php

/* grpmtaga, gmta */
class GrpmtagaBO extends _CommonBO
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
        GGnavi::getGrpmtagbBO();
        $arr = array();
        $arr['ggAuth'] = GGauth::getInstance();
        $arr['grpmtagbBO'] = GrpmtagbBO::getInstance();
        return $arr;
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    const FIELD__GRPNO           = "grpno";           /* (PK) char(30) */
    const FIELD__TAGIDX          = "tagidx";          /* (PK) int */
    const FIELD__TAGNAME         = "tagname";         /* (  ) char(50) */
    const FIELD__TAGCOLORFONT    = "tagcolorfont";    /* (  ) char(6) */
    const FIELD__TAGCOLORBACK    = "tagcolorback";    /* (  ) char(6) */
    const FIELD__TAGREGCNT       = "tagregcnt";       /* (  ) int */
    const FIELD__MODIDT          = "modidt";          /* (  ) datetime */
    const FIELD__REGDT           = "regdt";           /* (  ) datetime */

    /* 그룹당 최대 태그 개수 */
    const TAG_MAXCNT = 5;

    static public function getConsts()
    {
        $arr = array();
        return $arr;
    }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByPkForInside ($GRPNO, $TAGIDX) { return $this->select(get_defined_vars(), self::selectByPk); }

    /* ========================= */
    /* select */
    /* ========================= */
    const selectByPk    = "selectByPk";
    const selectByGrpno = "selectByGrpno";
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
            , t.tagname
            , t.tagcolorfont
            , t.tagcolorback
            , t.tagregcnt
            , t.modidt
            , t.regdt
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPk    : { $from = "(select * from grpmtaga where grpno = '$GRPNO' and tagidx = $TAGIDX) t"; break; }
            case self::selectByGrpno : { $from = "(select * from grpmtaga where grpno = '$GRPNO') t"; break; }
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
                , t.tagidx asc
        ";
        $rslt = GGsql::select($query, $from, $options, $OPTION);
        return $rslt;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    public function recalTagregcntForInside($GRPNO, $TAGIDX)
    {
        $query =
        "
            update grpmtaga
            set tagregcnt = (select count(*) from grpmtagb where grpno = '$GRPNO' and tagidx = $TAGIDX)
            where grpno = '$GRPNO' and tagidx = $TAGIDX
        ";
        GGsql::exeQuery($query);
    }

    /* ========================= */
    /* update */
    /* ========================= */
    const insertFromPage = "insertFromPage";
    const updateFromPage = "updateFromPage";
    const deleteByPk     = "deleteByPk";
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
            case self::insertFromPage:
            {
                /* 권한체크 : 모임 매니저(부매니저 포함)만 가능 */
                $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true);

                /* validation */
                if(Common::isEmpty($GRPNO))         { throw new GGexception("시스템 오류입니다."); }
                if(Common::isEmpty($TAGNAME))       { throw new GGexception("태그명이 입력되지 않았습니다."); }
                if(Common::isEmpty($TAGCOLORFONT))  { throw new GGexception("글자색이 선택되지 않았습니다."); }
                if(Common::isEmpty($TAGCOLORBACK))  { throw new GGexception("배경색이 선택되지 않았습니다."); }

                /* 태그는 그룹당 5개까지만 생성 가능 */
                $tagcnt = GGsql::selectCnt("select count(*) cnt from grpmtaga where grpno = '$GRPNO'");
                if($tagcnt >= self::TAG_MAXCNT)
                    throw new GGexception("태그는 모임당 ".self::TAG_MAXCNT."개까지만 만들 수 있습니다.");

                /* process */
                $query =
                "
                    insert into grpmtaga
                    (
                          grpno
                        , tagidx
                        , tagname
                        , tagcolorfont
                        , tagcolorback
                        , tagregcnt
                        , modidt
                        , regdt
                    )
                    select
                          '$GRPNO'
                        ,  (select ifnull(max(tagidx), 0) + 1 from grpmtaga where grpno = '$GRPNO')
                        , '$TAGNAME'
                        , '$TAGCOLORFONT'
                        , '$TAGCOLORBACK'
                        ,  0
                        ,  now()
                        ,  now()
                    from
                        dual
                ";
                GGsql::exeQuery($query);
                break;
            }
            case self::updateFromPage:
            {
                /* 권한체크 */
                $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true);

                /* validation */
                if(Common::isEmpty($GRPNO))         { throw new GGexception("시스템 오류입니다."); }
                if(Common::isEmpty($TAGIDX))        { throw new GGexception("시스템 오류입니다."); }
                if(Common::isEmpty($TAGNAME))       { throw new GGexception("태그명이 입력되지 않았습니다."); }
                if(Common::isEmpty($TAGCOLORFONT))  { throw new GGexception("글자색이 선택되지 않았습니다."); }
                if(Common::isEmpty($TAGCOLORBACK))  { throw new GGexception("배경색이 선택되지 않았습니다."); }
                if(Common::getDataOneField($this->selectByPkForInside($GRPNO, $TAGIDX), self::FIELD__TAGIDX) == null)
                    throw new GGexception("존재하지 않는 태그입니다.");

                /* process */
                $query =
                "
                    update grpmtaga
                    set
                          tagname      = '$TAGNAME'
                        , tagcolorfont = '$TAGCOLORFONT'
                        , tagcolorback = '$TAGCOLORBACK'
                        , modidt       = now()
                    where
                        grpno = '$GRPNO' and tagidx = $TAGIDX
                ";
                GGsql::exeQuery($query);
                break;
            }
            case self::deleteByPk:
            {
                /* 권한체크 */
                $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true);

                /* validation */
                if(Common::isEmpty($GRPNO))  { throw new GGexception("시스템 오류입니다."); }
                if(Common::isEmpty($TAGIDX)) { throw new GGexception("시스템 오류입니다."); }

                /* 태그에 등록된 멤버 전체 해제 후, 태그 삭제 */
                $grpmtagbBO->deleteByGrpnoTagidxForInside($GRPNO, $TAGIDX);

                $query = "delete from grpmtaga where grpno = '$GRPNO' and tagidx = $TAGIDX";
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

}
?>
