<?php
/**
 * Glo_Validate_Uuid
 */
class Glo_Validate_Uuid extends Zend_Validate_Abstract {
        
    const INVALID_FORMAT     = 'invalidFormat';

    protected $_messageTemplates = array(
        self::INVALID_FORMAT    => "'%value%' is not a properly formatted uuid",
    );

    /**
     * isValid
     *
     * @param  string $value
     * @return boolean
     */
    public function isValid($value, $context = null) {

        if (strlen($value) != 36)
        {
            throw new Glo_Exception_BadData($this->_createMessage(self::INVALID_FORMAT, $value));
        }
        else
        {
            // check the format
            //AAAE017A-AF85-11DE-98BE-228156D89593
            $pattern = "^([a-fA-F0-9]{8}-)([a-fA-F0-9]{4}-)([a-fA-F0-9]{4}-)([a-fA-F0-9]{4}-)([a-fA-F0-9]{12})$";            
            if (!preg_match("/$pattern/", $value, $matches)) {
                throw new Glo_Exception_BadData($this->_createMessage(self::INVALID_FORMAT, $value));
            }
            
        }
        return true;
    }
    
}