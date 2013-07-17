<?php

class Glo_Db_MySqli_Stmt_BindParam {

    private $type;
    private $var;

    public function __construct($type, $var)
    {
        // 
        if (!preg_match('|[idsb]|', $type))
        {
            throw new Exception('Found invalid bind param type: ' . $type);
        }
        else
        {
            $this->type = $type;
            $this->var = $var;
        }
    }
    
    public function __get($k)
    {
        return $this->$k;
    }

}
