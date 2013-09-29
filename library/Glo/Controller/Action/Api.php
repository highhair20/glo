<?php

abstract class Glo_Controller_Action_Api extends Zend_Controller_Action {
    
	public function assertIsAjaxRequest($errorMessage = NULL) {
        if (!$this->_request->isXmlHttpRequest()) {
        	$msg = $errorMessage ? $errorMessage : "This page was requested improperly. 
            		This page can only be accessed via an ajax request.";
            throw new Exception_Http404($msg);
        }
	}
	
	public function assertIsPost($errorMessage = NULL) {
		if (!$this->_request->isPost()) {
        	$msg = $errorMessage ? $errorMessage : "This page was requested improperly. 
            		This page can only be requested with a POST.";
            throw new Exception_Http404($msg);
		}
	}
	
	public function preDispatch()
	{
        if ($this->getInvokeArg('noViewRenderer'))
        {
            $this->view = new stdClass();
        }
	}
	
    public function init() {
        $data = $this->getRequestJson();
        if (!$data)
        {
            $data = $this->getRequest();
        }
        if (is_numeric($data))
        {
            var_dump($data);
            
        }
        if (!isset($_SESSION))
        {
            if (array_key_exists('session_uuid', $data))
            {
//                $_COOKIE['app'] = $data['session_uuid'];
            }
            elseif (array_key_exists('app', $_COOKIE))
            {
                $data['session_uuid'] = $_COOKIE['app'];
            }
            if (array_key_exists('session_uuid', $data))
            {
                Zend_Session::setId($data['session_uuid']);
                Zend_Session::start();
            }
        }
        
/*         $this->loggedInUser = App_Model_User::getLoggedIn(); */
        
        return parent::init();
/*
        // load the logged in user if there is one
        $this->view->loggedInUser = User::getLoggedIn();
        
        // set the translate adapter
        $this->registerTranslator();
*/
    }

    public function getRequestJson($decoded = true)
    {
        // first try $_POST then look in raw post data
        $frontController = Zend_Controller_Front::getInstance();
        $request = $frontController->getRequest();
        
        // handle bad requests
        $requestData = $this->_request->getPost();       
        if (is_array($requestData))
        {
            $decoded = false;
        }
        if (!$requestData) 
        {
            $requestData = $request->getParams();
        }
        if (!$requestData) 
        {
            $requestData = $request->getRawBody();
        }

        if (!$requestData) 
        {
            throw new Glo_Exception_InvalidRequest('no post data');
            exit;
        }
/*         var_dump($requestData);         */
/*         var_dump($_REQUEST); */
/*         var_dump(fopen("php://input", "rb")); */
        if ($decoded)
        {   
            $requestData = @json_decode($requestData, true);
            // check for an error condition where the request is not parsable
            switch (json_last_error()) {
                case JSON_ERROR_NONE:
                    // no errors
                    break;
                case JSON_ERROR_DEPTH:
                    // Maximum stack depth exceeded
                case JSON_ERROR_STATE_MISMATCH:
                    // Underflow or the modes mismatch
                case JSON_ERROR_CTRL_CHAR:
                    // Unexpected control character found
                case JSON_ERROR_SYNTAX:
                    // Syntax error, malformed JSON
                case JSON_ERROR_UTF8:
                    // Malformed UTF-8 characters, possibly incorrectly encoded
                default:
                    // Unknown error
                    file_put_contents('/tmp/glo/' . $this->_request->getHttpHost() . '-request.err', date('c') . ": " . print_r($_REQUEST, true) . "\n", FILE_APPEND);
                    require_once 'Glo/Exception/InvalidRequest.php';
                    throw new Glo_Exception_InvalidRequest('Sorry.  We are unable to process your request at this time.  Thanks for you patience');
                    break;
            }
        }
        if (isset($_COOKIE['app']))
        {
            $data['session_uuid'] = $_COOKIE['app'];
        }
        return $requestData;
    }
    
    public function setRequestJson($dataArray)
    {
        $_POST = json_encode($dataArray);
    }

    public function registerTranslator() {
/*
        $path = dirname(__FILE__) 
            . '/../../../languages/'
            . $this->_request->getControllerName() . '/'
            . $this->_request->getActionName();
        if (is_dir($path)) {
            try {
                $translate = new Zend_Translate(
                    array(
                        'adapter'           => 'ini',
                        'content'           => $path,
                        'locale'            => 'auto',
                        'scan'              => Zend_Translate::LOCALE_FILENAME,
                        'disableNotices'    => true,
                    )
                );
                if ($translate->isAvailable($this->view->loggedInUser->languagePref)) {
                    $translate->setLocale($this->view->loggedInUser->languagePref);
                } else {
                    $translate->setLocale('en');
                }
                $this->view->translate()->setTranslator($translate);
            } catch (Exception $ex) {
                // so, the language dir didn't exist...what're you gonna do    
                echo $ex->getMessage() . "<br>\n";    
            }
        }
*/
    }

    protected function _roundTripRedirect($url, $options = array()) {
        Session::setParam('_roundTripRedirectUrl', $_SERVER['REQUEST_URI']);
        return parent::_redirect($url, $options);
    }
    
    
} 
