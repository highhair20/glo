<?php

abstract class Glo_Model_Map_Abstract
{

    public function save($data)
    {
        $response = null;
        $dbAdapter = Zend_Registry::get(Glo_Db::CONN_WRITE);
        $table = $this->_getDbTable($dbAdapter);
        $pks = $table->info('primary');
        $isSequence = $table->info('sequence');
        if ($isSequence)
        {
            // if isSequence then it is assumed that the first 
/*
            if (null === ($id = $cleanData[$pk])) 
            {
                unset($cleanData[$pk]);
                $response = $this->_getDbTable($dbAdapter)->insert($cleanData);
            } 
            else 
            {
                $response = $this->_getDbTable($dbAdapter)->update($data, array($pk . ' = ?' => $id));
            }
*/
        }
        else
        {
            if (count($pks) > 1)
            {
                // this is not a sequence and has a compound primary key
                // so all members of the pk must be set
                $params = array();
                foreach ($pks as $pk)
                {
                    if (!isset($data[$pk]))
                    {
                        throw new Zend_Db_Table_Row_Exception('All members of a compound primary key must be set.');
                    }
                    else
                    {
                        $params[$pk . ' = ?'] = $data[$pk];
                    }
                }
                $response = $this->_getDbTable($dbAdapter)->replace($data, $params);
            }
            else
            {
                $pk = array_shift($pks);
                if (!isset($data[$pk]) || !$data[$pk]) 
                {
                    $data[$pk] = $this->_getDbTable($dbAdapter)->generateId();
                    $response = $this->_getDbTable($dbAdapter)->insert($data);
                } 
                else            
                {
                    // we don't know if this is new data or not
                    $response = $this->_getDbTable($dbAdapter)->replace($data, array($pk . ' = ?' => $data[$pk]));
                }
            }
        }
        return $response;
    }
    
    
    public function find($id, $dbAdapterType = Glo_Db::CONN_READ)
    {
        $dbAdapter = Zend_Registry::get($dbAdapterType);
        $result = $this->_getDbTable($dbAdapter)->find($id);
        if (0 == count($result)) 
        {
            return;
        }
        $row = $result->current();
        $classname = $this->_modelDictionary;
        $model = new $classname($row);
        return $model;
    }
 
 
    public function fetchAll($count = 50, $offset = 0, $dbAdapterType = Glo_Db::CONN_READ)
    {
        $dbAdapter = Zend_Registry::get($dbAdapterType);
        $resultSet = $this->_getDbTable($dbAdapter)->fetchAll();
        $set = new Glo_Model_Set();
        foreach ($resultSet as $row) 
        {
            $classname = $this->_modelDictionary;
            $set->add(new $classname($row));
        }
        return $set;
    }
    
    
    protected function _getDbTable($dbAdapter = null)
    {
        if (!$this->_dbTable) 
        {
            throw new Exception('Missing parameter ' . get_class($this) . '::_getDbTable');
        }
        elseif (!($this->_dbTable instanceof Glo_Db_Table_Abstract)) 
        {
            $this->_dbTable = new $this->_dbTable($dbAdapter);
        }
        return $this->_dbTable;
    }
    
    
    
}

