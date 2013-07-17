<?php

class Glo_Db_MySqli_Stmt extends mysqli_stmt 
{
    
    public $hostInfo = null;
    
    private $_tsStart = null;
    private $_query = null;
    private $_bindParams = null;
    
    public function __construct($link, $query) {
        $this->_tsStart = microtime(true);
        $this->_query = $query;
        parent::__construct($link, $query);
    }
    
    public function bind_param()
    {
        $this->_bindParams = func_get_args();
        return call_user_func_array(array($this, 'parent::bind_param'), func_get_args());
    }
    
    public function bind_param_list(array $bindParams)
    {
        $types = '';
        $vars = array();
        foreach ($bindParams as $param)
        {
            if (!($param instanceof Glo_Db_MySqli_Stmt_BindParam))
            {
                throw new Exception('Invalid parameter expecting Db_Mysli_Stmt_BindParam');
            }
            else
            {
                $types .= $param->type;
                $vars[] = $param->var;
            }
        }
        $params = array_merge(array($types), $vars);
        $refs = array();
        foreach($params as $key => $value)
        {
            $refs[$key] = &$params[$key];
        }
        return call_user_func_array(array($this, 'parent::bind_param'), $params);
    }
    
    public function execute()
    {
        $response = parent::execute();
        Glo_Db_Debug::add($this->hostInfo, $this->_query, $this->_bindParams, $this->_tsStart, microtime(true));
        return $response;
    }
}

