<?php

class SystemCheckBO extends _CommonBO
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
    /* select */
    /* ========================= */
    const checkDuplicateOfUserId = "checkDuplicateOfUserId";
    protected function check($options, $option="")
    {
        /* --------------- */
        /* init vars */
        /* --------------- */
        $result = Common::getReturn();
        extract($options);

        /* orderride option */
        if($option != "")
            $OPTION = $option;

        /* --------------- */
        /* from */
        /* --------------- */
        switch($OPTION)
        {
            case self::checkDuplicateOfUserId:
            {
                $sql = "select * from user where id = '$VALUE'";
                $cnt = GGsql::selectCnt($sql);
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