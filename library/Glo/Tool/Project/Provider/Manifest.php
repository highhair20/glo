<?php

require_once 'Zend/Tool/Framework/Manifest/ProviderManifestable.php';


class Glo_Tool_Project_Provider_Manifest implements
    Zend_Tool_Framework_Manifest_ProviderManifestable
{

    /**
     * getProviders()
     *
     * @return array Array of Providers
     */
    public function getProviders()
    {
        // the order here determines the output when iterating a manifest
/*         var_dump(get_include_path()); */
/*         var_dump('getProviders'); */
/*         require_once 'Glo/Tool/Project/Provider/ProjectProvider.php'; */
        return array(
            // top level project & profile providers
            //'Zend_Tool_Project_Provider_Profile',
            new Glo_Tool_Project_Provider_ProjectProvidder(),

            // app layer provider
            //'Zend_Tool_Project_Provider_Application',

            // MVC layer providers
            //'Zend_Tool_Project_Provider_Model',
            //'Glo_Tool_Project_Provider_Model',
            //'Zend_Tool_Project_Provider_View',
            //'Zend_Tool_Project_Provider_Controller',
            //'Zend_Tool_Project_Provider_Action',

            // hMVC provider
            //'Zend_Tool_Project_Provider_Module',

            // application problem providers
            //'Zend_Tool_Project_Provider_Form',
            //'Zend_Tool_Project_Provider_Layout',
            //'Zend_Tool_Project_Provider_DbAdapter',
            //'Zend_Tool_Project_Provider_DbTable',

            // provider within project provider
            //'Zend_Tool_Project_Provider_ProjectProvider',

        );
    }
}
