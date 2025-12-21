<?php

class GrpMemberPointhistBO extends _CommonBO
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
    function setBO() {
        $ggAuth = GGauth::getInstance();
        $grpMemberBO = GrpMemberBO::getInstance();
        $arr = array();
        $arr['ggAuth'] = $ggAuth;
        $arr['grpMemberBO'] = $grpMemberBO;
        return $arr;
    }

    /* ========================= */
    /* field */
    /*
    */
    /* ========================= */
    const FIELD__GRPNO              = "grpno";              /* (pk) char(30) */
    const FIELD__USERNO             = "userno";             /* (pk) char(30) */
    const FIELD__POINTHISTDT        = "pointhistdt";        /* (pk) date */
    const FIELD__POINTHISTNO        = "pointhistno";        /* (pk) int */
    const FIELD__POINT              = "point";              /* (  ) int */
    const FIELD__POINTLEFT          = "pointleft";          /* (  ) int */
    const FIELD__POINTMEMO          = "pointmemo";          /* (  ) varchar(255) */
    const FIELD__RELCLSNO           = "relclsno";           /* (  ) char(14) */
    const FIELD__REGIDT             = "regidt";             /* (  ) datetime */

    /* ========================= */
    /* enum */
    /*
    */
    /* ========================= */
    // const CLSSTATUS__EDIT = "edit"; /* 작성중 */

    /* ========================= */
    /* select options */
    /* ========================= */
    static public function getConsts()
    {
        $arr = array();
        // $arr['clsstatusEdit'] = self::CLSSTATUS__EDIT; /* 일정상태 : 작성중 */
        return $arr;
    }

    /* ========================= */
    /* select > sub > sub */
    /* ========================= */
    // public function selectByBiznameForInside ($BIZNAME) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    // public function selectByBiznameForInside ($BIZNAME) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /* ========================= */
    // const selectByClsno = "selectByClsno";
    const selectLast3mByUserno = "selectLast3mByUserno";
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        extract($this->setBO());
        extract(GrpMemberPointhistBO::getConsts());
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
            , t.userno
            , t.pointhistdt
            , t.pointhistno
            , t.point
            , t.pointleft
            , t.pointmemo
            , t.relclsno
            , t.regidt
        ";

        /* --------------- */
        /* validation */
        /* --------------- */
        switch($OPTION)
        {
            // case self::selectByClsno : { break; }
            // default: throw new GGexception("(server) no option defined");
        }

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectLast3mByUserno : { $from = "(select * from grp_member_pointhist where grpno = '$GRPNO' and userno = '$USERNO' and pointhistdt >= date_sub(now(), interval 3 month)) t"; break; }
            default: throw new GGexception("(server) no option defined");
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
                , t.userno
                , t.pointhistdt desc
                , t.pointhistno desc
        ";
        $result = GGsql::select($query, $from, $options);
        return $result;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    public function insertForInside($GRPNO, $USERNO, $POINT, $POINTMEMO, $RELCLSNO) { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteRecordByGrpnoUsernoForInside($GRPNO, $USERNO) { return $this->update(get_defined_vars(), __FUNCTION__); }


    /* ========================= */
    /* update */
    /* ========================= */
    const insertForInside = "insertForInside";
    const deleteRecordByGrpnoUsernoForInside = "deleteRecordByGrpnoUsernoForInside";
    // const deleteByClsnoForInside = "deleteByClsnoForInside";
    // const updateFromPage = "updateFromPage";
    protected function update($options, $option="")
    {
        /* vars */
        $pointhistno = null;

        /* get vars */
        extract($this->setBO());
        extract(GrpMemberPointhistBO::getConsts());
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* =============== */
        /* auth */
        /* =============== */
        switch($OPTION)
        {
            case self::insertForInside: { break; }
        }

        /* =============== */
        /* sql execution */
        /* =============== */
        switch($OPTION)
        {
            case self::insertForInside:
            {
                /* get pointleft */
                $grpmPoint = Common::getField($grpMemberBO->getByPk($GRPNO, $USERNO), GrpMemberBO::FIELD__POINT);

                /* get pointhistdt */
                $pointhistdt = GGdate::getYMDhyphen();
                $pointhistno = $this->getNextPointhistno($GRPNO, $USERNO, $pointhistdt);

                /* set relclsno */
                $RELCLSNO = Common::isEmpty($RELCLSNO) ? "null" : "'$RELCLSNO'";

                /* execute */
                $query =
                "
                    insert into grp_member_pointhist
                    (
                          grpno
                        , userno
                        , pointhistdt
                        , pointhistno
                        , point
                        , pointleft
                        , pointmemo
                        , relclsno
                        , regidt
                    )
                    values
                    (
                          '$GRPNO'
                        , '$USERNO'
                        , '$pointhistdt'
                        ,  $pointhistno
                        ,  $POINT
                        ,  $grpmPoint
                        , '$POINTMEMO'
                        ,  $RELCLSNO
                        ,  now()
                    )
                ";
                GGsql::exeQuery($query);
                break;
            }
            case self::deleteRecordByGrpnoUsernoForInside:
            {
                $query = "delete from grp_member_pointhist where grpno = '$GRPNO' and userno = '$USERNO'";
                GGsql::exeQuery($query);
                break;
            }
            default: throw new GGexception("(server) no option defined");
        }
        return $pointhistno;
    }

    public function getNextPointhistno($GRPNO, $USERNO, $POINTHISTDT)
    {
        $query =
        "
            select
                coalesce(max(pointhistno)+1, 1) cnt
            from
                grp_member_pointhist
            where
               grpno       = '$GRPNO' and
               userno      = '$USERNO' and
               pointhistdt = '$POINTHISTDT'
        ";
        $cnt = GGsql::selectCnt($query);
        return $cnt;
    }

} /* end class */
?>
