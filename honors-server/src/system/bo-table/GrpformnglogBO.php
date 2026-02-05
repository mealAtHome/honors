<?php

class GrpformnglogBO extends _CommonBO
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
    const FIELD__GRPNO                              = "grpno";                              /* (PK) char(30) */
    const FIELD__GFMLFIELD                          = "gfmlfield";                          /* (PK) varchar(50) */
    const FIELD__GFMLKEYNO                          = "gfmlkeyno";                          /* (PK) int */
    const FIELD__GFMLHISTNO                         = "gfmlhistno";                         /* (PK) int */
    const FIELD__GFMLTYPE                           = "gfmltype";                           /* (  ) enum('insert','update','delete') */
    const FIELD__GFMLCONTENT                        = "gfmlcontent";                        /* (  ) varchar(50) */
    const FIELD__GFMLSUMMARYPAR                     = "gfmlsummarypar";                     /* (  ) int */
    const FIELD__GFMLSUMMARYREAL                    = "gfmlsummaryreal";                    /* (  ) int */
    const FIELD__GFMLCOMMENT                        = "gfmlcomment";                        /* (  ) varchar(255) */
    const FIELD__REGDT                              = "regdt";                              /* (  ) datetime */

    /* ========================= */
    /* enum */
    /*
    */
    /* ========================= */

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByPkForInside($GRPNO, $GFMLFIELD, $GFMLKEYNO, $GFMLHISTNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /* ========================= */
    const selectByPk = "selectByPk";
    const selectByPkForInside = "selectByPkForInside";
    const selectGrpfncCapitaltotalByGrpno = "selectGrpfncCapitaltotalByGrpno";
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
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
            null as head
            , t.grpno
            , t.gfmlfield
            , t.gfmlkeyno
            , t.gfmlhistno
            , t.gfmltype
            , t.gfmlcontent
            , t.gfmlsummarypar
            , t.gfmlsummaryreal
            , t.gfmlcomment
            , t.regdt
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPk                           : { $from = "(select * from grpformnglog where grpno = '$GRPNO' and gfmlfield = '$GFMLFIELD' and gfmlkeyno = '$GFMLKEYNO' and gfmlhistno = '$GFMLHISTNO') t"; break; }
            case self::selectByPkForInside                  : { $from = "(select * from grpformnglog where grpno = '$GRPNO' and gfmlfield = '$GFMLFIELD' and gfmlkeyno = '$GFMLKEYNO' and gfmlhistno = '$GFMLHISTNO') t"; break; }
            case self::selectGrpfncCapitaltotalByGrpno      : { $from = "(select * from grpformnglog where grpno = '$GRPNO' and gfmlfield = 'GRPFNC_CAPITALTOTAL') t"; break; }
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
                , t.gfmlfield
                , t.gfmlkeyno
                , t.gfmlhistno
        ";
        $result = GGsql::select($query, $from, $options);
        return $result;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    public function insertOfGrpfncCapitaltotalForInside($GRPNO, $GFMLSUMMARYREAL, $GFMLCOMMENT) { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    const insertOfGrpfncCapitaltotalForInside = "insertOfGrpfncCapitaltotalForInside";
    protected function update($options, $option="")
    {
        /* vars */
        $ggAuth = GGauth::getInstance();
        $result = Common::getReturn();

        /* get vars */
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* sql execution */
        switch($OPTION)
        {
            case self::insertOfGrpfncCapitaltotalForInside:
            {
                $gfmlfield = "GRPFNC_CAPITALTOTAL";
                $query =
                "
                    insert into grpformnglog
                    (
                          grpno
                        , gfmlfield
                        , gfmlkeyno
                        , gfmlhistno
                        , gfmltype
                        , gfmlcontent
                        , gfmlsummarypar
                        , gfmlsummaryreal
                        , gfmlcomment
                        , regdt
                    )
                    select
                          '$GRPNO'
                        , '$gfmlfield'
                        ,  1
                        ,  (select ifnull(max(gfmlhistno), 0) + 1 from grpformnglog where grpno = '$GRPNO' and gfmlfield = '$gfmlfield' and gfmlkeyno = 1)
                        , 'update'
                        , ''
                        ,  null
                        ,  $GFMLSUMMARYREAL
                        , '$GFMLCOMMENT'
                        , now()
                    from
                        dual
                ";
                GGsql::exeQuery($query);
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }
        return $result;
    }

}
?>
