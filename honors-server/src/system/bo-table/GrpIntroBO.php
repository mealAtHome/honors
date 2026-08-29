<?php

/* grp_intro : 모임소개(소개글/운영원칙) */
class GrpIntroBO extends _CommonBO
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
    function readBO()
    {
        GGnavi::getGrpBO();
    }
    function setBO()
    {
        self::readBO();
        $arr = array();
        $arr['grpBO'] = GrpBO::getInstance();
        $arr['ggAuth'] = GGauth::getInstance();
        return $arr;
    }

    /* ========================= */
    /* fields */
    /* ========================= */
    const FIELD__GRPNO         = "grpno";         /* (pk) char(30) */
    const FIELD__GRPINTRODETAIL = "grpintrodetail"; /* (  ) text */
    const FIELD__GRPRULES      = "grprules";      /* (  ) text */
    const FIELD__MODIDT        = "modidt";        /* (  ) datetime */
    const FIELD__REGIDT        = "regidt";        /* (  ) datetime */

    static public function getConsts()
    {
        $arr = array();
        return $arr;
    }

    /* ========================= */
    /* select */
    /* ========================= */
    const selectByPk = "selectByPk";
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
            , t.grpintrodetail
            , t.grprules
            , t.modidt
            , t.regidt
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPk : { $from = "(select * from grp_intro where grpno = '$GRPNO') t"; break; }
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
        ";
        $rslt = GGsql::select($query, $from, $options, $OPTION);
        return $rslt;
    }

    /* ========================= */
    /* update */
    /* ========================= */
    const upsertForMng = "upsertForMng";
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
            case self::upsertForMng:
            {
                /* 권한체크 : 모임 매니저(부매니저 포함)만 가능 */
                $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true);

                /* validation */
                if(Common::isEmpty($GRPINTRO))       { throw new GGexception("한 줄 소개를 입력해주세요."); }
                if(Common::isEmpty($GRPINTRODETAIL)) { throw new GGexception("모임소개글을 입력해주세요."); }
                if(Common::isEmpty($GRPRULES))       { throw new GGexception("운영원칙 및 규칙을 입력해주세요."); }

                /* grp.grpintro 갱신 */
                $grpBO->updateGrpintroForInside($GRPNO, $GRPINTRO);

                /* grp_intro upsert */
                $grpintrodetail = GGsql::realEscapeString(trim($GRPINTRODETAIL));
                $grprules       = GGsql::realEscapeString(trim($GRPRULES));
                $query =
                "
                    insert into grp_intro (grpno, grpintrodetail, grprules, regidt, modidt)
                    values ('$GRPNO', '$grpintrodetail', '$grprules', now(), now())
                    on duplicate key update
                        grpintrodetail = '$grpintrodetail',
                        grprules = '$grprules',
                        modidt = now()
                ";
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
