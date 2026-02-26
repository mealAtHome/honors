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
    function setBO()
    {
        $arr = array();
        $arr['ggAuth'] = GGauth::getInstance();
        return $arr;
    }

    static public function getConsts()
    {
        $arr = array();
        // $arr['key'] = "value";
        return $arr;
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
        /* vars */
        $rslt = Common::getReturn();
        extract($this->setBO());
        extract(self::getConsts());
        extract($options);

        /* override option */
        if($option != "")
            $OPTION = $option;

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
                $rslt = GGsql::exeQuery($query);
                break;
            }
            case self::updateCartSummaryForInside:
            {
                $query = "update cart set cart_summary = $CART_SUMMARY, at_update = now() where userno = '$USERNO' and storeno = '$STORENO'";
                $rslt = GGsql::exeQuery($query);
                break;
            }
            case self::deleteByPkForInside:
            {
                $query = "delete from cart where userno = '$USERNO' and storeno = '$STORENO'";
                $rslt = GGsql::exeQuery($query);
                break;
            }
        }
        return $rslt;
    }

} /* end class */
?>
