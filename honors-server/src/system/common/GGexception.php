<?php

class GGexception extends Exception
{
    public function __construct($message="", $code = 0, Throwable $previous = null)
    {
        if($message == "")
            $message = "(system) error";
        parent::__construct($message, $code, $previous);
    }
}

class GGexceptionAuth extends GGexception
{
    public function __construct($message="", $code = 0, Throwable $previous = null)
    {
        $message = "(system) auth failed";
        parent::__construct($message, $code, $previous);
    }

}

?>