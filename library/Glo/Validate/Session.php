<?php
/**
 * Glo_alidate_Session
 */
class Glo_Validate_Session extends Zend_Validate_Abstract 
{
        
    const NOT_AUTHORIZED = 'notAuthorized';

    protected $_messageTemplates = array(
        self::NOT_AUTHORIZED => "Your session has expired.  Please login."
    );

    public function isValid($value, $context = null) 
    {
        $value = (string) $value;
        $this->_setValue($value);
        
        if (!$_SESSION)
        {
            throw new Glo_Exception_InvalidSession($this->_messageTemplates[self::NOT_AUTHORIZED]);
        }
        else
        {
            return true;
        }

    }

    
}