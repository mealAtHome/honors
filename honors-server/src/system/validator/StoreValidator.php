<?php

class StoreValidator
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
        GGnavi::getStoreBO();
        $arr = array();
        $arr['storeBO'] = StoreBO::getInstance();
        return $arr;
    }

    function validation($fieldname, $value="")
    {
        extract($this->setBO());

        switch($fieldname)
        {
            case StoreBO::FIELD__PAYMENTTYPE_PICKUP_CASH :
            case StoreBO::FIELD__PAYMENTTYPE_PICKUP_ACCT :
            case StoreBO::FIELD__PAYMENTTYPE_DELIVERY_CASH :
            case StoreBO::FIELD__PAYMENTTYPE_DELIVERY_ACCT :
            case StoreBO::FIELD__ORDERTYPE_PICKUP :
            case StoreBO::FIELD__ORDERTYPE_DELIVERY :
            {
                if($value != GGF::Y && $value != GGF::N)
                    throw new GGexception("(server) validation failed");
                break;
            }
            case StoreBO::FIELD__BACCNO_SETTLE:
            {
                if($value == null || is_int($value*1) == false)
                    throw new GGexception("(server) validation failed");
            }
        }
        return true;
    }

}

?>