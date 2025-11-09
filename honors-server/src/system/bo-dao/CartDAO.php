<?php

class CartDAO extends _CommonBO
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
    /*  */
    /* ========================= */
    public function insertCartForInside          ($USERNO, $STORENO                )  { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function updateCartSummaryForInside   ($USERNO, $STORENO, $CART_SUMMARY )  { return $this->update(get_defined_vars(), __FUNCTION__); }
    public function deleteByPkForInside          ($USERNO, $STORENO                )  { return $this->update(get_defined_vars(), __FUNCTION__); }

    const insertCartForInside = "insertCartForInside";
    const updateCartSummaryForInside = "updateCartSummaryForInside";
    const deleteByPkForInside = "deleteByPkForInside";
    protected function update($options, $option="")
    {
        /* -------------- */
        /* vars */
        /* -------------- */
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

        /* result */
        $rslt = Common::getReturn();

        /* ==================== */
        /* process */
        /* ==================== */
        switch($OPTION)
        {
            case self::insertCartForInside:
            {
                $query =
                "
                    insert into cart
                    (
                        userno
                        ,storeno
                        ,cart_summary
                        ,at_startorder
                        ,at_update
                        ,at_create
                    )
                    values
                    (
                           $USERNO
                        , '$STORENO'
                        ,  0
                        ,  null
                        ,  now()
                        ,  now()
                    )
                    on duplicate key update
                        at_update = now()
                ";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::updateCartSummaryForInside:
            {
                $query = "update cart set cart_summary = $CART_SUMMARY, at_update = now() where userno = '$USERNO' and storeno = '$STORENO'";
                $result = GGsql::exeQuery($query);
                break;
            }
            case self::deleteByPkForInside:
            {
                $query = "delete from cart where userno = '$USERNO' and storeno = '$STORENO'";
                $result = GGsql::exeQuery($query);
                break;
            }
        }
        return $rslt;
    }

} /* end class */
?>
