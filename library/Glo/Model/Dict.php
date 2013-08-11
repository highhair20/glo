<?php

/**
 * Glo_Model_Dict
 * 
 *
 */
abstract class Glo_Model_Dict
{

    protected $_data = null;
    
    protected $_hiddenProperties = null;

    //////////////////////////////////////////////////////////////////////
    // MAGIC METHODS
    //////////////////////////////////////////////////////////////////////

    public function __construct(Zend_Db_Table_Row $data = null, $hiddenProperties = array())
    {
        if ($data instanceof Zend_Db_Table_Row)
        {
            foreach ($data as $key => $value)
            {
                $this->_data[$key] = $value;
            }
        }
        $this->_hiddenProperties = $hiddenProperties;
    }
    
    
    public function __get($k)
    {
        if (isset($this->_data[$k]))
        {
            return $this->_data[$k];
        }
        else
        {
            return null;
        }
    }
    
    
    public function __set($k, $v)
    {
        $this->_data[$k] = $v;
        return;
    }
    

    //////////////////////////////////////////////////////////////////////
    // PUBLIC METHODS
    //////////////////////////////////////////////////////////////////////
    
    public function toJson()
    {
        return Zend_Json::encode($this->toArray());
    }
    
    public function toArray()
    {
        $data = $this->_data;
        foreach ($this->_hiddenProperties as $prop) 
        {
            unset($data[$prop]);
        }
/*
        $user = App_Model_User::getLoggedIn();
        if ($user)
        {
            foreach ($data as $k => $v)
            {
                if (strstr($k, 'ts_'))
                {
                    $data[$k] = Util_Date::fromDbTime($v, $user->timezone);
                }
                elseif (is_object($v))
                {
                    $data[$k] = $v->toArray();
                }
                elseif (is_array($v))
                {
                    foreach ($v as $i => $item)
                    {
                        if (is_object($item))
                        {
                            $data[$k][$i] = $item->toArray();
                        }
                    }
                }
            }
        }
*/
        return $data;
    }
    
    
    //////////////////////////////////////////////////////////////////////
    // PROTECTED METHODS
    //////////////////////////////////////////////////////////////////////

    
    //////////////////////////////////////////////////////////////////////
    // PRIVATE METHODS
    //////////////////////////////////////////////////////////////////////
    
    
    //////////////////////////////////////////////////////////////////////
    // STATIC METHODS
    //////////////////////////////////////////////////////////////////////
    
    
}

