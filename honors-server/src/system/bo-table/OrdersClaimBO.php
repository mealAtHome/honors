<?php

/* orders_claim */
class OrdersClaimBO extends _CommonBO
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
    /* const */
    /*
    */
    /* ========================= */
    const FIELD__STORENO            = "storeno";            /* (pk) char(30) */
    const FIELD__ORDERNO            = "orderno";            /* (pk) char(14) */
    const FIELD__CLAIM_STATUS       = "claim_status";       /*      enum('none','request','process','complete') */
    const FIELD__CLAIM_TYPE         = "claim_type";         /*      enum('notaste','something','gobad','broken','wrongfood','other') */
    const FIELD__CLAIM_COMMENT      = "claim_comment";      /*      varchar(255) */
    const FIELD__CLAIM_PIC1         = "claim_pic1";         /*      varchar(10) */
    const FIELD__CLAIM_PIC2         = "claim_pic2";         /*      varchar(10) */
    const FIELD__CLAIM_PHONE1       = "claim_phone1";       /*      varchar(20) */
    const FIELD__CLAIM_PHONE2       = "claim_phone2";       /*      varchar(20) */
    const FIELD__CLAIM_REFUNDFLG    = "claim_refundflg";    /*      enum('y','n') */
    const FIELD__CLAIM_SYSCOMMENT   = "claim_syscomment";   /*      varchar(255) */

    /* ========================= */
    /* fields */
    /*
    */
    /* ========================= */
    const CLAIM_STATUS__NONE            = "none";        /* claim_status : 해당없음 */
    const CLAIM_STATUS__REQUEST         = "request";     /* claim_status : 요청 */
    const CLAIM_STATUS__PROCESS         = "process";     /* claim_status : 처리중 */
    const CLAIM_STATUS__COMPLETE        = "complete";    /* claim_status : 완료 */

    /* ========================= */
    /* get */
    /*
    */
    /* ========================= */
    public function getByPk($STORENO, $ORDERNO, $throwIfNull=true)
    {
        $ordersClaim = Common::getDataOne($this->selectByPkForInside($STORENO, $ORDERNO));
        if($ordersClaim == null && $throwIfNull)
            throw new GGexception("(server) no orders_claim found");
        return $ordersClaim;
    }

    /* ========================= */
    /* select */
    /*
    */
    /* ========================= */
    public function selectByPkForInside($STORENO, $ORDERNO) { return $this->select(get_defined_vars(), __FUNCTION__); }

    const selectByPk = "selectByPk";
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
            , t.claim_status
            , t.claim_type
            , t.claim_comment
            , t.claim_pic1
            , t.claim_pic2
            , t.claim_refundflg
            , t.claim_syscomment
            , t.claim_phone1
            , t.claim_phone2
        ";

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::selectByPk           : { $from = "(select * from orders_claim where storeno = '$STORENO' and orderno = '$ORDERNO') o "; break; }
            case self::selectByPkForInside  : { $from = "(select * from orders_claim where storeno = '$STORENO' and orderno = '$ORDERNO') o "; break; }
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
    public function insertClaim                 ($STORENO, $ORDERNO, $CLAIM_TYPE, $CLAIM_COMMENT, $CLAIM_PHONE1, $CLAIM_PHONE2) { return $this->insert(get_defined_vars(), __FUNCTION__); }
    public function updateClaimPicForInside     ($STORENO, $ORDERNO, $CLAIM_PIC) { return $this->update(get_defined_vars(), __FUNCTION__); }

    protected function update($options, $option="")
    {
        /* init vars */
        $ggAuth = GGAuth::getInstance();
        extract($options);
        $OPTION = $option != "" ? $option : $OPTION;

        /* ==================== */
        /* process */
        /* ==================== */
        switch($OPTION)
        {
            case self::insertClaim:
            {
                /* auth */
                $ggAuth->isOrderer($EXECUTOR, $STORENO, $ORDERNO);

                $claimStatus = self::CLAIM_STATUS__REQUEST;
                $query =
                "
                    insert into orders_claim
                    (
                          storeno
                        , orderno
                        , claim_status
                        , claim_type
                        , claim_comment
                        , claim_phone1
                        , claim_phone2
                    )
                    values
                    (
                          '$STORENO'
                        , '$ORDERNO'
                        , '$claimStatus'
                        , '$CLAIM_TYPE'
                        , '$CLAIM_COMMENT'
                        , '$CLAIM_PHONE1'
                        , '$CLAIM_PHONE2'
                    )
                ";
                GGsql::exeQuery($query);

                /* 주문상태를 클레임으로 변경 */
                $orderingBO = OrderingBO::getInstance();
                $orderingBO->updateOrderstatusToClaimForInside($STORENO, $ORDERNO);
                break;
            }
            case self::updateClaimPicForInside:
            {
                /* get field for update pic */
                $ordersClaim = $this->getByPk($STORENO, $ORDERNO);
                $claimPic1 = Common::get($ordersClaim, OrdersClaimBO::FIELD__CLAIM_PIC1, null);
                $updateField = $claimPic1 == null ? "claim_pic1" : "claim_pic2";

                /* update */
                $query =
                "
                    update
                        orders_claim
                    set
                        $updateField = '$CLAIM_PIC'
                    where
                        storeno = '$STORENO' and
                        orderno = '$ORDERNO'
                ";
                GGsql::exeQuery($query);
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
