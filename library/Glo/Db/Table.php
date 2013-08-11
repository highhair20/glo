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


    protected function _addHiddenCol($colName)
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
        $rowCount = parent::update($data, $where);
        if ($rowCount === 0)
        {
            throw new Glo_Db_Table_Row_NotFoundException("no data found for $where");
        }
        return $rowCount;
    }
    
    
    public function insertOrUpdate(array $data)
    {
        $response = null;
        $sql = "INSERT INTO " . $this->_name . " ("
                    . implode(', ', array_keys($data)) 
                . ") VALUES (" 
                    . implode(', ', array_fill(0, count($data), '?'))
                . ") ON DUPLICATE KEY UPDATE ";
        $params = array();
        foreach ($data as $k => $v)
        {
            $params[] = $k . ' = ?';
        }
        $sql .= implode(', ', $params);
        $response = $this->_db->query($sql, array_merge(array_values($data), array_values($data)));
        return $response;
    }
    
    
    public function generateId()
    {
        return Glo_Util_Uuid::generate();
    }
    
    
    protected function _init() {}
    
}