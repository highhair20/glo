<?php
/**
 * @copyright Copyright Glo Framework, Inc.
 */
class Glo_Model_Set implements Iterator 
{
    
    protected $_array = array();
    private $_valid = false;
    private $_hasNextSet = false;
    private $_hasPrevSet = false;
    private $_pageIndex = 0;


    
    /**
     * Return the array "pointer" to the first element
     * PHP's reset() returns false if the array has no elements
     */
    public function rewind()
    {
        $this->_valid = (FALSE !== reset($this->_array));
    }
    
    
    /**
     * Return the current array element
     */
    public function current()
    {
        return current($this->_array);
    }
    
    
    /**
     * Return the key of the current array element
     */
    public function key()
    {
        return key($this->_array);
    }
    
    
    /**
     * Move forward by one
     * PHP's next() returns false if there are no more elements
     */
    public function next()
    {
        $this->_valid = (false !== next($this->_array));
    }
    
    
    /**
     * Is the current element valid?
     */
    public function valid()
    {
        return $this->_valid;
    } 
    
    
    /**
     * Add an element to this collection
     * 
     * $return void
     */
    public function add($itemData) 
    {
        $this->_array[] = $itemData;
        return;
    }
    
    
    /**
     * Get the number of elements in this collection
     *
     * @return int the number of moodHits
     */
    public function count() 
    {
        return count($this->_array);
    }
    
    
    /**
     * Get the first element from the collection
     * 
     * @return MoodHit the first moodHit
     */
    public function first() 
    {
        return $this->_array[0];
    }
    
    
    /**
     * Get the last element from the collection
     * 
     * @return MoodHit the last moodHit
     */
    public function last() 
    {
        return $this->_array[count($this->_array) - 1];
    }
    
    
    /**
     * Determine is a next set available
     *
     * @return bool TRUE if there is a next set available, otherwise FALSE
     */
    public function hasNextSet() 
    {
        return $this->_hasNextSet;
    }
    
    
    /**
     * Determine is a previous set available
     *
     * @return bool TRUE if there is a previous set available, otherwise FALSE
     */
    public function hasPrevSet() 
    {
        return $this->_hasPrevSet;
    }   
    
    
    /**
     * Get the index of this set in the entire set based on 
     * number of potential items in the set and the offset.
     * 
     * @return int the index
     */
    public function pageIndex() 
    {
        return $this->_pageIndex;
    }
    
    
    public function toArray()
    {
        $arr = array();
        $vars = get_object_vars($this);
        foreach ($vars as $var => $val)
        {
            switch ($var)
            {
                case '_hasNextSet':
                    $arr['hasNextSet'] = $val;
                    break;
                case '_hasPrevSet':
                    $arr['hasPrevSet'] = $val;
                    break;
                case '_array':
                    $arr['data'] = array();
                    foreach ($val as $item)
                    {
                        $arr['data'][] = $item->toArray();
                    }
                    break;
                case '_valid':
                case '_pageIndex':
                    break;
                default:
                    $arr[$var] = $val;
            }
        }
        return $arr;
    }
    
    
    public function toJson()
    {
         return Zend_Json::encode($this->toArray());
    }
    
    
/*     abstract protected function _init($collectionData); */
    
}
