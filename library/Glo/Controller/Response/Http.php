<?php

class Glo_Controller_Response_Http extends Zend_Controller_Response_Http {

    public function sendResponse() {
    
        // get configs
        $registry = Zend_Registry::getInstance();
        $config = $registry->get('config');
        
        // get front controller
        $frontController = Zend_Controller_Front::getInstance();

        //
        $this->sendHeaders();
                
        //        
        $this->renderExceptions($frontController->getParam('displayExceptions'));
        if ($this->isException() && $this->renderExceptions()) {
            header('HTTP/1.1 400 Bad Request');
            $exceptions = array();
            foreach ($this->getException() as $i => $e) {
                $exceptions['exceptions'][$i] = array(
                    'type' => get_class($e),
                    'message' => $e->getMessage(),
                );
                if ($frontController->getParam('displayExceptionsDetail'))
                {
                    $exceptions['exceptions'][$i]['detail'] = $e->getTrace();
                }
            }
            echo json_encode($exceptions);
            return;
        }
        
        // get the content
        $content = $this->getBody();

        $request = $frontController->getRequest();
        $requestData = json_decode($request->getRawBody());
        
        $logDir = '/tmp/glo/';
        if ($config->log->http_request)
        {
            if (!is_dir($logDir))
            {
                mkdir($logDir);
            }
            file_put_contents($logDir . $request->getHttpHost() . '-request.log', 
                    date('c') . " [REQUEST]: " . $request->getRequestUri() . " - " . json_encode($requestData) . "\n", FILE_APPEND);
        }
        if ($config->log->http_response)
        {
            if (!is_dir($logDir))
            {
                mkdir($logDir);
            }
            file_put_contents($logDir . $request->getHttpHost() . '-response.log', 
                    date('c') . " [RESPONSE]: " . $request->getRequestUri() . " - " . $content . "\n", FILE_APPEND);
        }
        
        // gzip if needed
/*
        if ($config->performance->gzip_enabled 
                && is_object($requestData))
        {
            header('Content-Encoding: gzip');
            header('Content-Type: application/json; charset=UTF-8');
            header("Cache-Control: no-cache");
            header("Pragma: no-cache");
            header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
            $content = gzencode($content);
        }
*/
        
/*         var_dump($content);exit; */
        $this->outputBody();
    }
    
    
}
