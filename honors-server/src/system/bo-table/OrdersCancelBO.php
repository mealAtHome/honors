<?php

class OrdersCancelBO extends _CommonBO
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
        GGnavi::getOrderingBO();
    }

    /* ========================= */
    /* field */
    /*
    */
    /* ========================= */
    const FIELD__STORENO = "storeno"; /* char(30) */
    const FIELD__ORDERNO = "orderno"; /* char(14) */
    const FIELD__ORDERSTATUS_CANCELER = "orderstatus_canceler"; /* enum('none','system','customer','store') */
    const FIELD__ORDERSTATUS_CANCELPAYER = "orderstatus_cancelpayer"; /* enum('none','system','customer','store','rider') */
    const FIELD__ORDERSTATUS_CANCELETYPE = "orderstatus_canceletype"; /* enum('none','customer','notpaid','noconfirm','reject','cookfailed','wrong') */
    const FIELD__ORDERSTATUS_CANCELERMSG = "orderstatus_cancelermsg"; /* varchar(50) */

    /* ========================= */
    /* enum */
    /*
    */
    /* ========================= */
    /* ORDERSTATUS_CANCELER */      const ORDERSTATUS_CANCELER__NONE                = "none";                  /* 주문취소자 : 해당없음 */
    /* ORDERSTATUS_CANCELER */      const ORDERSTATUS_CANCELER__SYSTEM              = "system";                /* 주문취소자 : 시스템 */
    /* ORDERSTATUS_CANCELER */      const ORDERSTATUS_CANCELER__CUSTOMER            = "customer";              /* 주문취소자 : 고객 */
    /* ORDERSTATUS_CANCELER */      const ORDERSTATUS_CANCELER__STORE               = "store";                 /* 주문취소자 : 매장 */
    /* ORDERSTATUS_CANCELPAYER */   const ORDERSTATUS_CANCELPAYER__NONE             = "none";                  /* 취소책임자 : 해당없음 */
    /* ORDERSTATUS_CANCELPAYER */   const ORDERSTATUS_CANCELPAYER__SYSTEM           = "system";                /* 취소책임자 : 시스템 */
    /* ORDERSTATUS_CANCELPAYER */   const ORDERSTATUS_CANCELPAYER__CUSTOMER         = "customer";              /* 취소책임자 : 고객 */
    /* ORDERSTATUS_CANCELPAYER */   const ORDERSTATUS_CANCELPAYER__STORE            = "store";                 /* 취소책임자 : 매장 */
    /* ORDERSTATUS_CANCELPAYER */   const ORDERSTATUS_CANCELPAYER__RIDER            = "rider";                 /* 취소책임자 : 라이더 */
    /* ORDERSTATUS_CANCELTYPE */    const ORDERSTATUS_CANCELTYPE__NONE              = "none";                  /* 추문취소타입 : 해당없음 */
    /* ORDERSTATUS_CANCELTYPE */    const ORDERSTATUS_CANCELTYPE__CUSTOMER          = "customer";              /* 추문취소타입 : 단순변심 */
    /* ORDERSTATUS_CANCELTYPE */    const ORDERSTATUS_CANCELTYPE__NOTPAID           = "notpaid";               /* 추문취소타입 : 미결제 */
    /* ORDERSTATUS_CANCELTYPE */    const ORDERSTATUS_CANCELTYPE__NOCONFIRM         = "noconfirm";             /* 추문취소타입 : 주문확정누락 */
    /* ORDERSTATUS_CANCELTYPE */    const ORDERSTATUS_CANCELTYPE__REJECT            = "reject";                /* 추문취소타입 : 거절 */
    /* ORDERSTATUS_CANCELTYPE */    const ORDERSTATUS_CANCELTYPE__COOKFAILED        = "cookfailed";            /* 추문취소타입 : 요리 중 취소 */
    /* ORDERSTATUS_CANCELTYPE */    const ORDERSTATUS_CANCELTYPE__WRONG         = "wrong";             /* 추문취소타입 : 요리 미일치 */


    /* ========================= */
    /* select */
    /*
    */
    /* ========================= */
    public function selectByPkForInside($STORENO, $ORDERNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    const selectByPkForInside = "selectByPkForInside";
    protected function select($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        extract($options);
        $OPTION = $option != "" ? $option : $OPTION;

        /* --------------- */
        /* sql body */
        /* --------------- */
        $query  = "";
        $from   = "";
        $select =
        "
              t.storeno
            , t.orderno
            , t.orderstatus_canceler
            , t.orderstatus_cancelpayer
            , t.orderstatus_canceletype
            , t.orderstatus_cancelermsg
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPkForInside : { $from = "(select * from orders_cancel where storeno = '$STORENO' and orderno = '$ORDERNO') o "; break; }
            default:
            {
                throw new GGexception("(system) process failed.");
            }
        } /* end switch($OPTION) */

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
        return GGsql::select($query, $from, $options);
    }

    /* ========================= */
    /* update */
    /*
    */
    /* ========================= */
    public function upsertForInside(
          $STORENO
        , $ORDERNO
        , $ORDERSTATUS_CANCELER    = self::ORDERSTATUS_CANCELER__NONE
        , $ORDERSTATUS_CANCELPAYER = self::ORDERSTATUS_CANCELPAYER__NONE
        , $ORDERSTATUS_CANCELETYPE = self::ORDERSTATUS_CANCELETYPE__NONE
        , $ORDERSTATUS_CANCELERMSG = ""
    )
    { return $this->update(get_defined_vars(), __FUNCTION__); }

    const upsertForInside = "upsertForInside";
    protected function update($options, $option="")
    {
        /* init vars */
        extract($options);
        $OPTION = $option != "" ? $option : $OPTION;

        /* ==================== */
        /* process */
        /* ==================== */
        switch($OPTION)
        {
            case self::upsertForInside:
            {
                $query =
                "
                    insert into orders_cancel
                    (
                          storeno
                        , orderno
                        , orderstatus_canceler
                        , orderstatus_cancelpayer
                        , orderstatus_canceletype
                        , orderstatus_cancelermsg
                    )
                    values
                    (
                          '$STORENO'
                        , '$ORDERNO'
                        , '$ORDERSTATUS_CANCELER'
                        , '$ORDERSTATUS_CANCELPAYER'
                        , '$ORDERSTATUS_CANCELETYPE'
                        , '$ORDERSTATUS_CANCELERMSG'
                    )
                    on duplicate key update
                          orderstatus_canceler    = '$ORDERSTATUS_CANCELER'
                        , orderstatus_cancelpayer = '$ORDERSTATUS_CANCELPAYER'
                        , orderstatus_canceletype = '$ORDERSTATUS_CANCELETYPE'
                        , orderstatus_cancelermsg = '$ORDERSTATUS_CANCELERMSG'
                ";
                break;
            }
            default:
            {
                throw new GGexception("(server) error while updating order info");
            }
        }
        return true;
    }

}
?>
