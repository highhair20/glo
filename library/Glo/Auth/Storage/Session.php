<?php


class Glo_Auth_Storage_Session extends Zend_Auth_Storage_Session
{

    private static $_id = null;

    public function __construct($namespace = self::NAMESPACE_DEFAULT, $member = self::MEMBER_DEFAULT)
    {
        session_name($this->_generateSessionName());
        if (!self::$_id)
        {
            self::$_id = Glo_Util_Uuid::generate();
        }
        Zend_Session::setId(self::$_id);
        parent::__construct($namespace, $member);
    }
    
    public static function setId($id)
    {
        self::$_id = $id;
        return;
    }
    
    protected function _generateSessionName()
    {
        $registry = Zend_Registry::getInstance();
        $sessionName = $registry->get('config')->appnamespace;
        $sessionName = strtolower(trim($sessionName, '_ '));
        return $sessionName;
    }
    
}