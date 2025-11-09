<?php

/* ========================= */
/* 필드 다루기 */
/* ========================= */
class GGvalid
{
    static function isNum($val)
    {
        if(!is_numeric($val))
            return "null";
        return $val;
    }

    static function isEmpty($val)
    {
        if(
            isset($val) == false
            || $val == ""
            || $val == null
        )
            return true;
        return false;
    }

    static function isValidAddr($options)
    {
        extract($options);
        if(
            $ADDR_ZIPCODE == "" ||
            $ADDR_SIDO    == "" ||
            $ADDR_SIGUNGU == "" ||
            $ADDR_EMD     == "" ||
            $ADDR_ROAD    == "" ||
            $ADDR_JIBUN   == "" ||
            $ADDR_ROADENG == "" ||
            $ADDR_ADMCD   == "" ||
            $ADDR_RNMGTSN == "" ||
            $ADDR_UDRTYN  == ""
        )
        {
            throw new GGexception("(server-validation) addr is not valid");
        }
    }

} /* end class */
?>