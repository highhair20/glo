<?php 

class Glo_Bootstrap
{

    public static function bootstrap()
    {        
        //
        // set the include_path
        set_include_path(implode(PATH_SEPARATOR, array(
            '/usr/share/php/libzend-framework-php',
            realpath(APPLICATION_PATH . '/../glo-framework/library'),
            realpath(APPLICATION_PATH . '/../glo-generated'),
            realpath(APPLICATION_PATH . '/../library'),
            realpath(APPLICATION_PATH . '/../vendor'),
            get_include_path(),
        )));
        
        
        //
        // set up the autoloader
        require_once 'Zend/Loader/Autoloader.php';
        require_once 'Glo/Loader.php';
        $autoLoader = Zend_Loader_Autoloader::getInstance();
        $autoLoader->pushAutoloader(array('Glo_Loader', 'loadClass'));
        $autoLoader->setFallbackAutoloader(true);
        
        
        //
        // register the config
        $config = new Zend_Config_Ini(
                APPLICATION_PATH . '/configs/application.ini', 
                Glo_Environment::get(), 
                array('allowModifications' => TRUE));
        $registry = Zend_Registry::getInstance();
        $registry->set('config', $config);
        
        
        //
        // initialize the Zend Application
        require_once 'Zend/Application.php';
        $application = new Zend_Application(
            Glo_Environment::get(),
            APPLICATION_PATH . '/configs/application.ini'
        );
        
        
        //
        // register the database configuration
        $bootstrap = $application->getBootstrap();
        $bootstrap->bootstrap('multidb');
        $resource = $bootstrap->getResource('multidb');
        Zend_Registry::set("conn_read",$resource->getDb('read'));
        Zend_Registry::set("conn_read_volatile",$resource->getDb('read_volatile'));
        Zend_Registry::set("conn_write",$resource->getDb('write'));
        
        return $application;
    }

}

