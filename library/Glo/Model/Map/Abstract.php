<?php

abstract class Glo_Model_Map_Abstract
{

    public function save($data)
    {
        $response = array();
        $dbAdapter = Zend_Registry::get(Glo_Db::CONN_WRITE);
        $table = $this->_getDbTable($dbAdapter);
        
        // scrub the data
        $cols = $table->info('cols');
        foreach ($data as $k => $v)
        {
            if (!in_array($k, $cols))
            {
                unset($data[$k]);
            }
        }
        
        //
        $pks = $table->info('primary');
        $isSequence = $table->info('sequence');
        if ($isSequence)
        {
            // if isSequence then it is assumed that the first 
            $pk = array_pop($pks);
            if (!isset($data[$pk]) || null === ($id = $data[$pk])) 
            {
                try
                {
                    $response = $this->_getDbTable($dbAdapter)->insert($data);
                }
                catch (Zend_Db_Statement_Exception $e)
                {
                    if ($e->getCode() == 23000 && strstr($e->getMessage(), '1062'))
                    {
                        throw new Glo_Exception_DuplicateData(array_pop(explode('1062', $e->getMessage())));
                    }
                }
            } 
            else 
            {
                $id = $data[$pk];
                $response = $this->_getDbTable($dbAdapter)->update($data, array($pk . ' = ?' => $id));
            }
        }
        else
        {
            if (count($pks) > 1)
            {
                // this is not a sequence and has a compound primary key
                // so all members of the pk must be set
                throw new Exception('Not Implemented');
/*
                $params = array();
                $where = array();
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
                $response = $this->_getDbTable($dbAdapter)->insertOrUpdate($data, $where);
*/
            }
            else
            {
                $pk = array_shift($pks);
                if (!isset($data[$pk]) || !$data[$pk]) 
                {
                    $data[$pk] = $this->_getDbTable($dbAdapter)->generateId();
                    try
                    {
                        $response = $this->_getDbTable($dbAdapter)->insert($data);
                    }
                    catch (Zend_Db_Statement_Exception $e)
                    {
                        if ($e->getCode() == 23000 && strstr($e->getMessage(), '1062'))
                        {
                            throw new Glo_Exception_DuplicateData(array_pop(explode('1062', $e->getMessage())));
                        }
                    }
                } 
                else            
                {
                    // we don't know if this is new data or not
                    $where = $dbAdapter->quoteInto($pk . ' = ?', $data[$pk]);
                    $response = $this->_getDbTable($dbAdapter)->insertOrUpdate($data, $where);
                }
            }
        }
        return $response;
    }

    
    public function find($id, $dbAdapterType = Glo_Db::CONN_READ)
    {
        $dbAdapter = Zend_Registry::get($dbAdapterType);
        $dbTable = $this->_getDbTable($dbAdapter);
        $result = $dbTable->find($id);
        if (0 == count($result)) 
        {
            return;
        }
        $row = $result->current();
        $classname = $this->_modelDictionary;
        $model = new $classname($row, $dbTable->getHiddenCols());
        return $model;
    }
 
 
    public function fetchAll($count = 50, $offset = 0, $dbAdapterType = Glo_Db::CONN_READ)
    {
        $dbAdapter = Zend_Registry::get($dbAdapterType);
        $dbTable = $this->_getDbTable($dbAdapter);
        $resultSet = $dbTable->fetchAll();
        $set = new Glo_Model_Set();
        foreach ($resultSet as $row) 
        {
            $classname = $this->_modelDictionary;
            $set->add(new $classname($row, $dbTable->getHiddenCols()));
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

