<?php

/**
 * Wrapper for Zend_Auth
 * uses database adapter
 * persists session using php session
 */
class Glo_Auth
{
    /**
     * Singleton instance
     *
     * @var Glo_Auth
     */
    protected static $_instance = null;

    /**
     * Auth adapter
     *
     * @var Zend_Auth_Adapter_Interface
     */
    protected $_adapter = null;
    
    /**
     * Zend auth instance
     *
     * @var Zend_Auth
     */
    protected $_auth = null;

    /**
     * Singleton pattern implementation makes "new" unavailable
     *
     * @return void
     */
    protected function __construct()
    {}

    /**
     * Singleton pattern implementation makes "clone" unavailable
     *
     * @return void
     */
    protected function __clone()
    {}

    /**
     * Returns an instance of Glo_Auth
     *
     * Singleton pattern implementation
     *
     * @return Glo_Auth Provides a fluent interface
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }


    /**
     * Returns the auth adapter
     *
     * @return Zend_Auth_Storage_Interface
     */
    public function getAdapter()
    {
        return $this->_adapter;
    }
    
    
    public function setAdapter(Zend_Auth_Adapter_Interface $adapter)
    {
        $this->_adapter = $adapter;
        return $this;
    }
    
    
    
    /**
     * Authenticates against the supplied adapter
     *
     * @param  string $username
     * @param  string $password
     * @return Zend_Auth_Result
     */
    public function authenticate($username, $password)
    {
        // Get a reference to the singleton instance of Zend_Auth
        $this->_auth = Zend_Auth::getInstance();
         
        // Set the storage interface
        $this->_auth->setStorage(new Glo_Auth_Storage_Session('Glo_Auth'));

        // Set the identity on the adapter
        $this->_adapter->setIdentity($username);
        
        // Set the credential on the adapter
        $this->_adapter->setCredential($password);

        // Attempt authentication, saving the result
        $result = $this->_auth->authenticate($this->_adapter);
         
        if (!$result->isValid()) {
            // Authentication failed
            throw new Glo_Auth_Exception_Failed(array_shift($result->getMessages()));
        }
        else
        {
            $data = $this->_adapter->getResultRowObject(array('user_uuid'));
            $storage = $this->_auth->getStorage();
            $storage->write($data);
        }
        return $result;
    }
    
    
    public function forceAuthenticate($userUuid)
    {
        // Get a reference to the singleton instance of Zend_Auth
        $this->_auth = Zend_Auth::getInstance();
         
        // Set the storage interface
        $this->_auth->setStorage(new Glo_Auth_Storage_Session('Glo_Auth'));
        
        $storage = $this->_auth->getStorage();
        $data = new stdClass();
        $data->user_uuid = $userUuid;
        $storage->write($data);
        return;
    }


    /**
     * Returns true if and only if an identity is available from storage
     *
     * @return boolean
     */
    public function hasIdentity()
    {
        return $this->_auth->hasIdentity();
    }

    /**
     * Returns the identity from storage or null if no identity is available
     *
     * @return mixed|null
     */
    public function getIdentity()
    {
        return $this->_auth->getIdentity();
    }

    /**
     * Clears the identity from persistent storage
     *
     * @return void
     */
    public function clearIdentity()
    {
        // Get a reference to the singleton instance of Zend_Auth
        $this->_auth = Zend_Auth::getInstance();
        return $this->_auth->clearIdentity();
    }
    

}
