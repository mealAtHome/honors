<?php

/* payment_log */
class PaymentLogBO extends _CommonBO
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
    public function __construct()
    {
    }

    /* ========================= */
    /* field */
    /* ========================= */
    const FIELD__USERNO                 = "userno";                 /* (pk) char(30) */
    const FIELD__PLOGD                  = "plogd";                  /* (pk) date */
    const FIELD__PLOGIDX                = "plogidx";                /* (pk) int */
    const FIELD__PLOGTYPE               = "plogtype";               /* (  ) enum('in','out') */
    const FIELD__POINTTYPE              = "pointtype";              /* (  ) enum('cls') */
    const FIELD__RELGRPNO               = "relgrpno";               /* (  ) char(30) */
    const FIELD__RELCLSNO               = "relclsno";               /* (  ) char(30) */
    const FIELD__RELORDERNO             = "relorderno";             /* (  ) int */
    const FIELD__POINT                  = "point";                  /* (  ) int */
    const FIELD__POINTLEFT              = "pointleft";              /* (  ) int */
    const FIELD__MEMO                   = "memo";                   /* (  ) varchar(50) */
    const FIELD__MEMOSYS                = "memosys";                /* (  ) varchar(50) */
    const FIELD__REGDT                  = "regdt";                  /* (  ) datetime */

    /* ========================= */
    /* const */
    /* ========================= */
    const PLOGTYPE_IN       = 'in';
    const PLOGTYPE_OUT      = 'out';
    const POINTTYPE_CLS     = 'cls';

    static public function getConsts()
    {
        $arr = array();
        $arr['plogtypeIn']                  = self::PLOGTYPE_IN;             /* 로그타입 : 입금 */
        $arr['plogtypeOut']                 = self::PLOGTYPE_OUT;            /* 로그타입 : 출금 */
        $arr['pointtypeCls']                = self::POINTTYPE_CLS;           /* 포인트타입 : 일정 */
        return $arr;
    }

    /* ========================= */
    /*  */
    /*
        ■ option
            - executor
    */
    /* ========================= */
    // public function selectTodayAppliedByUsernoAndRegdtForInside($USERNO) { return $this->select(get_defined_vars(), __FUNCTION__); }
    // public function selectByPkForInside($USERNO, $REQNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    // const selectByStatusForAdmin = "selectByStatusForAdmin";
    // const selectByStatusForFront = "selectByStatusForFront";
    // const selectByPkForInside = "selectByPkForInside";
    // const selectByPk = "selectByPk";
    // const selectTodayAppliedByUsernoAndRegdtForInside = "selectTodayAppliedByUsernoAndRegdtForInside";
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        extract(self::getConsts());
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
              t.userno
            , t.plogd
            , t.plogidx
            , t.plogtype
            , t.pointtype
            , t.relgrpno
            , t.relclsno
            , t.relorderno
            , t.point
            , t.pointleft
            , t.memo
            , t.memosys
            , t.regdt
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPk               : { $from = "(select * from payment_log where userno = '$USERNO' and reqno = '$REQNO') t"; break; }
            case self::selectByPkForInside      : { $from = "(select * from payment_log where userno = '$USERNO' and reqno = '$REQNO') t"; break; }
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
                  t.userno
                , t.plogd desc
                , t.plogidx
        ";
        return GGsql::select($query, $from, $options);
    }

    /* ========================= */
    /* UPDATE / DELETE */
    /* ========================= */
    public function insertForInside($USERNO, $PLOGTYPE, $POINTTYPE, $RELGRPNO, $RELCLSNO, $RELORDERNO, $POINT, $MEMO, $MEMOSYS) { return $this->update(get_defined_vars(), __FUNCTION__); }

    const insertForInside = "insertForInside";
    protected function update($options, $option="")
    {
        /* ==================== */
        /* init */
        /* ==================== */
        $rslt = Common::getReturn();
        $ggAuth = GGAuth::getInstance();

        /* vars */
        extract(self::getConsts());
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* ==================== */
        /* 사전처리 */
        /* ==================== */

        /* ==================== */
        /* process */
        /* ==================== */
        switch($OPTION)
        {
            case self::insertForInside:
            {
                /* get bo */
                $idxBO = IdxBO::getInstance();
                $userBO = UserBO::getInstance();

                /* get var */
                $plogd = GGdate::getYMDhyphen();
                $plogidx = $idxBO->makePlogidx($USERNO, $plogd);

                /* get point */
                $user = $userBO->getUser($USERNO);
                if($user == null)
                    throw new GGexception("존재하지 않는 사용자입니다. 다시 시도하여 주세요.");
                $pointleft = intval(Common::getField($user, UserBO::FIELD__POINT, 0));

                /* query */
                $query =
                "
                    insert into payment_log
                    (
                          userno
                        , plogd
                        , plogidx
                        , plogtype
                        , pointtype
                        , relgrpno
                        , relclsno
                        , relorderno
                        , point
                        , pointleft
                        , memo
                        , memosys
                        , regdt
                    )
                    values
                    (
                          '$USERNO'
                        , '$plogd'
                        ,  $plogidx
                        , '$PLOGTYPE'
                        , '$POINTTYPE'
                        , '$RELGRPNO'
                        , '$RELCLSNO'
                        ,  $RELORDERNO
                        ,  $POINT
                        ,  $pointleft
                        , '$MEMO'
                        , '$MEMOSYS'
                        ,  now()
                    )
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::insertOutForInside:
            {
                break;
            }
        }
        return $rslt;
    }


} /* end class */
?>
