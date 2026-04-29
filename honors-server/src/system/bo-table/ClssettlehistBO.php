<?php

/* csth */
class ClssettlehistBO extends _CommonBO
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
    const FIELD__GRPNO                      = "grpno";                      /* (PK) char(30) */
    const FIELD__CLSNO                      = "clsno";                      /* (PK) char(14) */
    const FIELD__USERNO                     = "userno";                     /* (PK) char(30) */
    const FIELD__HISTNO                     = "histno";                     /* (PK) int */
    const FIELD__HISTTYPE                   = "histtype";                   /* (  ) enum('update','delete') */
    const FIELD__BILLSTANDARD               = "billstandard";               /* (  ) int */
    const FIELD__BILLPREPAID                = "billprepaid";                /* (  ) int */
    const FIELD__BILLADJUSTMENT             = "billadjustment";             /* (  ) int */
    const FIELD__BILLDISCOUNT               = "billdiscount";               /* (  ) int */
    const FIELD__BILLPOINTED                = "billpointed";                /* (  ) int */
    const FIELD__BILLFINAL                  = "billfinal";                  /* (  ) int */
    const FIELD__BILLMEMO                   = "billmemo";                   /* (  ) varchar(100) */
    const FIELD__BILLFINALAFTER             = "billfinalafter";             /* (  ) int */
    const FIELD__REGDT                      = "regdt";                      /* (  ) datetime */

    /* ========================= */
    /* enum */
    /*
    */
    /* ========================= */
    const HISTTYPE__UPDATE                 = "update";                     /* 이력유형 : 수정 */
    const HISTTYPE__DELETE                 = "delete";                     /* 이력유형 : 삭제 */
    const HISTTYPE__AFTER                  = "after";                      /* 이력유형 : 이후 */

    static public function getConsts()
    {
        $arr = array();
        return $arr;
    }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByPkForInside($GRPNO, $CLSNO, $USERNO, $HISTNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /*
    */
    /* ========================= */
    const selectByPkForInside = "selectByPkForInside";
    const selectByClsno = "selectByClsno";
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
            null as head
            , t.grpno
            , t.clsno
            , t.histno
            , t.histtype
            , t.userno
            , t.billstandard
            , t.billprepaid
            , t.billadjustment
            , t.billdiscount
            , t.billpointed
            , t.billfinal
            , t.billmemo
            , t.billfinalafter
            , t.regdt
            , u.name as username
        ";

        /* --------------- */
        /* validation */
        /* --------------- */
        // switch($OPTION)
        // {
        //     case self::selectByClsnoForMng:
        //     {
        //         /* is grpmanager? */
        //         $ggAuth->isGrpmanager($GRPNO, $EXECUTOR, true);
        //         break;
        //     }
        // }

        /* --------------- */
        /* sql by option */
        /* --------------- */
        // switch($OPTION)
        // {
        //     case self::selectByClsnoForMng:
        //     {
        //         $select .=
        //         "
        //             , grpm.point grpm_point
        //         ";
        //         break;
        //     }
        // }

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPkForInside   : { $from = "(select * from clssettlehist where grpno = '$GRPNO' and clsno  = '$CLSNO' and userno = '$USERNO' and histno = '$HISTNO') t"; break; }
            case self::selectByClsno         : { $from = "(select * from clssettlehist where grpno = '$GRPNO' and clsno  = '$CLSNO') t"; break; }
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
                        t.userno = u.userno
            order by
                  t.grpno
                , t.clsno
                , u.name asc
                , t.histno asc
        ";
        $rslt = GGsql::select($query, $from, $options, $OPTION);
        return $rslt;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    public function copyFromClssettleForInside($GRPNO, $CLSNO, $USERNO, $HISTTYPE, $BILLFINALAFTER) { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    const insertForInside = "insertForInside";
    const copyFromClssettleForInside = "copyFromClssettleForInside";
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
            case self::copyFromClssettleForInside:
            {
                $query =
                "
                    insert into clssettlehist
                    (
                          grpno
                        , clsno
                        , userno
                        , histno
                        , histtype
                        , billstandard
                        , billprepaid
                        , billadjustment
                        , billdiscount
                        , billpointed
                        , billfinal
                        , billmemo
                        , billfinalafter
                        , regdt
                    )
                    select
                           grpno
                        ,  clsno
                        ,  userno
                        ,  (select ifnull(max(clsh.histno), 0) + 1 from clssettlehist clsh where clsh.grpno = clss.grpno and clsh.clsno = clss.clsno and clsh.userno = clss.userno) as histno
                        , '$HISTTYPE' as histtype
                        ,  billstandard
                        ,  billprepaid
                        ,  billadjustment
                        ,  billdiscount
                        ,  billpointed
                        ,  billfinal
                        ,  billmemo
                        ,  $BILLFINALAFTER as billfinalafter
                        ,  now() as regdt
                    from
                        clssettle clss
                    where
                        clss.grpno = '$GRPNO' and
                        clss.clsno = '$CLSNO' and
                        clss.userno = '$USERNO'
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

} /* end class */
?>
