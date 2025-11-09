<?php
class GGrslt
{
    private $code;
    private $count;
    private $data;

    public static function getReturn($code=Common::SUCCEED, $count=0, $data=array())
    {
        $rslt = new self();
        $rslt->code = $code;
        $rslt->count = $count;
        $rslt->data = $data;
        return $rslt;
    }

}
?>