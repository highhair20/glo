<?php

class Glo_Exception_Abstract extends Zend_Exception
{
    
    public function __construct($message = "", $code = 0, Exception $previous = NULL)
    {
        $message = $message ? $message : get_class($this);
        parent::__construct($message, $code, $previous);
    }

}
