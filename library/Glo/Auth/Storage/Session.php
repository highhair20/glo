<?php


class Glo_Auth_Storage_Session extends Zend_Auth_Storage_Session
{

    public function __construct($namespace = self::NAMESPACE_DEFAULT, $member = self::MEMBER_DEFAULT)
    {
        session_name($this->_generateSessionName());
        Zend_Session::setId(Glo_Util_Uuid::generate());
        parent::__construct($namespace, $member);
    }
    
    protected function _generateSessionName()
    {
        $registry = Zend_Registry::getInstance();
        $sessionName = $registry->get('config')->appnamespace;
        $sessionName = strtolower(trim($sessionName, '_ '));
        return $sessionName;
    }
    
}