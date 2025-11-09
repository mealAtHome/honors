<?php
/**
 * 유저의 예약금액에 대한 정보
 */
class UserAuthedBO extends _CommonBO
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
    function __construct() {
        GGnavi::getIdxBO();
    }

    /* ========================= */
    /* field */
    /*
    */
    /* ========================= */
    const FIELD__GRPNO              = "grpno";              /* (PK) char(30)       / NO  */
    const FIELD__CLSNO              = "clsno";              /* (PK) char(14)       / NO  */
    const FIELD__CLSSTATUS          = "clsstatus";          /* (  ) char(30)       / NO  */
    const FIELD__CLSTYPE            = "clstype";            /* (  ) char(30)       / NO  */
    const FIELD__CLSTITLE           = "clstitle";           /* (  ) char(50)       / NO  */
    const FIELD__CLSCONTENT         = "clscontent";         /* (  ) varchar(255)   / YES  */
    const FIELD__CLSSTARTDT         = "clsstartdt";         /* (  ) datetime       / NO  */
    const FIELD__CLSCLOSEDT         = "clsclosedt";         /* (  ) datetime       / NO  */
    const FIELD__CLSGROUND          = "clsground";          /* (  ) char(50)       / NO  */
    const FIELD__CLSGROUNDADDR      = "clsgroundaddr";      /* (  ) char(50)       / YES */
    const FIELD__CLSBILLAPPLYPRICE       = "clsbillapplyprice";       /* (  ) int            / NO  */
    const FIELD__CLSAPPLYSTARTDT    = "clsapplystartdt";    /* (  ) datetime       / NO  */
    const FIELD__CLSAPPLYCLOSEDT    = "clsapplyclosedt";    /* (  ) datetime       / NO  */
    const FIELD__CLSSETTLEFLG       = "clssettleflg";       /* (  ) enum('y','n')  / YES */
    const FIELD__CLSMODIDT          = "clsmodidt";          /* (  ) datetime       / YES */
    const FIELD__CLSREGDT           = "clsregdt";           /* (  ) datetime       / YES */

    /* ========================= */
    /* enum */
    /*
    */
    /* ========================= */
    const CLSSTATUS__EDIT           = "edit";               /* 작성중 */
    const CLSSTATUS__ING            = "ing";                /* 진행중 */
    const CLSSTATUS__ENDCLS         = "endcls";             /* 일정완료 */
    const CLSSTATUS__ENDSETTLE      = "endsettle";          /* 정산완료 */

    /* ========================= */
    /* select options */
    /* ========================= */
    static public function getConsts()
    {
        $arr = array();
        $arr['clsstatusEdit']                   = self::CLSSTATUS__EDIT;        /* 일정상태 : 작성중 */
        $arr['clsstatusIng']                    = self::CLSSTATUS__ING;         /* 일정상태 : 진행중 */
        $arr['clsstatusEndcls']                 = self::CLSSTATUS__ENDCLS;      /* 일정상태 : 일정완료 */
        $arr['clsstatusEndsettle']              = self::CLSSTATUS__ENDSETTLE;   /* 일정상태 : 정산완료 */
        return $arr;
    }


    /* ========================= */
    /* select > sub > sub */
    /* ========================= */
    public function getByPk($GRPNO, $CLSNO) { return Common::getDataOne($this->selectByPkForInside($GRPNO, $CLSNO)); }

    /* ========================= */
    /* select > sub */
    /* ========================= */
    public function selectByPkForInside($GRPNO, $CLSNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* select */
    /* ========================= */
    const selectByPkForInside = "selectByPkForInside";
    const selectByClsstatusForMng = "selectByClsstatusForMng";
    const selectByPkForMng = "selectByPkForMng";
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        extract(UserAuthedBO::getConsts());
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
            , t.clsno
            , t.clsstatus
            , t.clstype
            , t.clstitle
            , t.clscontent
            , t.clsstartdt
            , t.clsclosedt
            , t.clsground
            , t.clsgroundaddr
            , t.clsbillapplyprice
            , t.clsapplystartdt
            , t.clsapplyclosedt
            , t.clssettleflg
            , t.clsmodidt
            , t.clsregdt
        ";

        /* --------------- */
        /* validation */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByClsstatusForMng :
            case self::selectByPkForMng :
            {
                /* TODO : is executor manager equals? */
                /* TODO : is correct clsstatus */
                break;
            }
        }

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPkForInside         : { $from = "(select * from cls where grpno = '$GRPNO' and clsno     = '$CLSNO') t"; break; }
            case self::selectByClsstatusForMng : { $from = "(select * from cls where grpno = '$GRPNO' and clsstatus = '$CLSSTATUS') t"; break; }
            case self::selectByPkForMng        : { $from = "(select * from cls where grpno = '$GRPNO' and clsno     = '$CLSNO') t"; break; }
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
        $result = GGsql::select($query, $from, $options);
        return $result;
    }

    /* ========================= */
    /* update (sub) */
    /* ========================= */
    // public function changeStoreStatus ($STORENO, $STORE_STATUS) { return $this->update(get_defined_vars(), __FUNCTION__); }

    /* ========================= */
    /* update */
    /* ========================= */
    const insert = "insert";
    const update = "update";
    const updateClsstatusEditToIng = "updateClsstatusEditToIng"; /* 매니저 : 일정상태를 진행중으로 변경 */

    protected function update($options, $option="")
    {
        /* get bo */
        $idxBO = IdxBO::getInstance();

        /* vars */
        $clsno = null;

        /* get vars */
        extract(UserAuthedBO::getConsts());
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* =============== */
        /* auth */
        /* =============== */
        switch($OPTION)
        {
            case self::insert: { break; }
            case self::update: { /* TODO : is manager of grp? */ break; }
        }

        /* =============== */
        /* validation (common) */
        /* =============== */
        switch($OPTION)
        {
            case self::insert:
            case self::update:
            {
                if(Common::isEmpty(trim($CLSTYPE))           ) { throw new GGexception("입력되지 않은 항목이 있습니다. (일정유형)"); }
                if(Common::isEmpty(trim($CLSTITLE))          ) { throw new GGexception("입력되지 않은 항목이 있습니다. (일정제목)"); }
                if(Common::isEmpty(trim($CLSCONTENT))        ) { throw new GGexception("입력되지 않은 항목이 있습니다. (일정상세)"); }
                if(Common::isEmpty(trim($CLSSTARTDT))        ) { throw new GGexception("입력되지 않은 항목이 있습니다. (일정시작일시)"); }
                if(Common::isEmpty(trim($CLSCLOSEDT))        ) { throw new GGexception("입력되지 않은 항목이 있습니다. (일정종료일시)"); }
                if(Common::isEmpty(trim($CLSGROUND))         ) { throw new GGexception("입력되지 않은 항목이 있습니다. (일정장소)"); }
                if(Common::isEmpty(trim($CLSGROUNDADDR))     ) { throw new GGexception("입력되지 않은 항목이 있습니다. (상세주소)"); }
                if(Common::isEmpty(trim($CLSBILLAPPLYPRICE))      ) { throw new GGexception("입력되지 않은 항목이 있습니다. (참가비)"); }
                if(Common::isEmpty(trim($CLSAPPLYSTARTDT))   ) { throw new GGexception("입력되지 않은 항목이 있습니다. (모집시작일시)"); }
                if(Common::isEmpty(trim($CLSAPPLYCLOSEDT))   ) { throw new GGexception("입력되지 않은 항목이 있습니다. (모집종료일시)"); }
                break;
            }
            case self::updateClsstatusEditToIng:
            {
                /* am i manager? */
                /* is edit status? */
                break;
            }
        }

        /* =============== */
        /* validation (key) */
        /* =============== */
        switch($OPTION)
        {
            case self::insert: break;
            case self::update:
            case self::updateClsstatusEditToIng:
            {
                if(Common::isEmpty(trim($GRPNO))) { throw new GGexception(); }
                if(Common::isEmpty(trim($CLSNO))) { throw new GGexception(); }
                break;
            }
        }

        /* =============== */
        /* sql execution */
        /* =============== */
        switch($OPTION)
        {
            case self::insert:
            {
                $clsno = $idxBO->makeClsno();
                $query =
                "
                    insert into cls
                    (
                          grpno
                        , clsno
                        , clsstatus
                        , clstype
                        , clstitle
                        , clscontent
                        , clsstartdt
                        , clsclosedt
                        , clsground
                        , clsgroundaddr
                        , clsbillapplyprice
                        , clsapplystartdt
                        , clsapplyclosedt
                        , clssettleflg
                        , clsmodidt
                        , clsregdt
                    )
                    values
                    (
                          '$GRPNO'
                        , '$clsno'
                        , '$clsstatusEdit'
                        , '$CLSTYPE'
                        , '$CLSTITLE'
                        , '$CLSCONTENT'
                        , '$CLSSTARTDT'
                        , '$CLSCLOSEDT'
                        , '$CLSGROUND'
                        , '$CLSGROUNDADDR'
                        ,  $CLSBILLAPPLYPRICE
                        , '$CLSAPPLYSTARTDT'
                        , '$CLSAPPLYCLOSEDT'
                        , 'n'
                        ,  now()
                        ,  now()
                    )
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::update:
            {
                $query   =
                "
                    update
                        cls
                    set
                          clsmodidt        =  now()
                        , clstype          = '$CLSTYPE'
                        , clstitle         = '$CLSTITLE'
                        , clscontent       = '$CLSCONTENT'
                        , clsstartdt       = '$CLSSTARTDT'
                        , clsclosedt       = '$CLSCLOSEDT'
                        , clsground        = '$CLSGROUND'
                        , clsgroundaddr    = '$CLSGROUNDADDR'
                        , clsbillapplyprice     =  $CLSBILLAPPLYPRICE
                        , clsapplystartdt  = '$CLSAPPLYSTARTDT'
                        , clsapplyclosedt  = '$CLSAPPLYCLOSEDT'
                    where
                        grpno = '$GRPNO' and
                        clsno = '$CLSNO'
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::updateClsstatusEditToIng:
            {
                $query =
                "
                    update
                        cls
                    set
                          clsstatus = '$clsstatusIng'
                        , clsmodidt = now()
                    where
                        grpno = '$GRPNO' and
                        clsno = '$CLSNO'
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            default:
            {
                throw new GGexception("(server) no option defined");
            }
        }
        return $clsno;
    }

} /* end class */
?>
