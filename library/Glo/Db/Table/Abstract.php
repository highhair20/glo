<?php

class Glo_Db_Table_Abstract extends Zend_Db_Table_Abstract
{
    
    
    public function update(array $data, $where)
    {
        $rowCount = parent::update($data, $where);
        if ($rowCount === 0)
        {
            throw new Glo_Db_Table_Row_NotFoundException("no data found for $where");
        }
        return $result;
    }
    
    
    public function replace(array $data, $where)
    {
        $response = null;
        try 
        {
            $this->update($data, $where);
            $pks = $this->info('primary');
            $response = array();
            foreach ($pks as $pk)
            {
                $response[] = $data[$pk];
            }
            $response = count($response) > 1 ? $response : array_shift($response);
        }
        catch (Glo_Db_Table_Row_NotFoundException $ex)
        {
            $response = $this->insert($data);
        }
        return $response;
    }
    
    
    public function generateId()
    {
        return Glo_Util_Uuid::generate();
    }
    
    
}