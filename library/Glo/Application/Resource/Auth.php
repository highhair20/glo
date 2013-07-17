<?php

class Glo_Application_Resource_Auth extends Zend_Application_Resource_ResourceAbstract
{

    /**
     * Glo_Application_Resource_Auth
     *
     * @return Glo_Application_Resource_Auth
     */
    public function init()
    {
        $options = $this->getOptions();
        
        $dbAdapter = Zend_Registry::get(Glo_Db::CONN_WRITE);
        $authAdapter = new Zend_Auth_Adapter_DbTable(
                $dbAdapter, 
                $options['options']['authTable'],
                $options['options']['usernameColumn'],
                $options['options']['passwordColumn'],
                $options['options']['passwordTreatment']);
        
        $auth = Glo_Auth::getInstance();
        $auth->setAdapter($authAdapter);
    }
    
}