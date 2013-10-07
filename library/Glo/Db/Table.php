<?php

class Glo_Db_Table extends Zend_Db_Table
{
    
    /**
     * Optional list of columns which will not be included in results.
     *
     * @var array
     */
    protected $_hiddenCols = array();
    
    
    protected $_immutableCols = array();
    
    
    public function __construct($config = array(), $definition = null)
    {
        $this->_init();
        parent::__construct($config, $definition);
    }


    public function addHiddenCol($colName)
    {
        $this->_hiddenCols[] = $colName;
    }
    
    
    public function getHiddenCols()
    {
        return $this->_hiddenCols;
    }
    
    
    protected function _addImmutableCol($colName)
    {
        $this->_immutableCols[] = $colName;
    }
    
    
    protected function _cleanData(array $data)
    {
        foreach ($this->_immutableCols as $col)
        {
            unset($data[$col]);
        }
        return $data;
    }
    
    
    public function insert(array $data)
    {
        $data = $this->_cleanData($data);
        return parent::insert($data);
    }
    
    
    public function update(array $data, $where)
    {
        $data = $this->_cleanData($data);
        $response = parent::update($data, $where);
        return $response;
    }
    
    
    public function insertOrUpdate(array $data, $where)
    {
        $response = null;
        try
        {
            $response = $this->update($data, $where);
            $pks = $this->info('primary');
            $response = array();
            foreach ($pks as $pk)
            {
                $response[$pk] = $data[$pk];
                if (count($response) == 1)
                {
                    $response = array_pop($response);
                }
            }
        }
        catch (Zend_Db_Statement_Exception $e)
        {
            if ($e->getCode() == 23000 && strstr($e->getMessage(), '1062'))
            {
                $response = $this->insert($data);
            }
        }
        return $response;
    }
    
    
    public function generateId()
    {
        return Glo_Util_Uuid::generate();
    }
    
    
    protected function _init() {}
    
}
