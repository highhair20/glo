<?php

require_once 'Zend/Tool/Project/Provider/Abstract.php';


class Glo_Tool_Project_Provider_ProjectProvider implements Zend_Tool_Framework_Provider_Interface
{

    /**
     * createResource()
     *
     * @param Zend_Tool_Project_Profile $profile
     * @param string $projectProviderName
     * @param string $actionNames
     * @return Zend_Tool_Project_Profile_Resource
     */
    public static function createResource(Zend_Tool_Project_Profile $profile, $projectProviderName, $actionNames = null)
    {

        if (!is_string($projectProviderName)) {
            /**
             * @see Zend_Tool_Project_Provider_Exception
             */
            require_once 'Zend/Tool/Project/Provider/Exception.php';
            throw new Zend_Tool_Project_Provider_Exception('Zend_Tool_Project_Provider_Controller::createResource() expects \"projectProviderName\" is the name of a project provider resource to create.');
        }

        $profileSearchParams = array();
        $profileSearchParams[] = 'projectProvidersDirectory';

        $projectProvider = $profile->createResourceAt($profileSearchParams, 'projectProviderFile', array('projectProviderName' => $projectProviderName, 'actionNames' => $actionNames));

        return $projectProvider;
    }

    /**
     * getName()
     *
     * @return string
     */
    public function getName()
    {
        return 'ProjectProvider';
    }

    /**
     * Create stub for Zend_Tool Project Provider
     *
     * @var string       $name            class name for new Zend_Tool Project Provider
     * @var array|string $actions         list of provider methods
     * @throws Zend_Tool_Project_Provider_Exception
     */
    public function create($name, $actions = null)
    {
        $profile = $this->_loadProfileRequired();

        $projectProvider = self::createResource($profile, $name, $actions);

        if ($this->_registry->getRequest()->isPretend()) {
            $this->_registry->getResponse()->appendContent('Would create a project provider named ' . $name
                . ' in location ' . $projectProvider->getPath()
                );
        } else {
            $this->_registry->getResponse()->appendContent('Creating a project provider named ' . $name
                . ' in location ' . $projectProvider->getPath()
                );
            $projectProvider->create();
            $this->_storeProfile();
        }

    }
}
