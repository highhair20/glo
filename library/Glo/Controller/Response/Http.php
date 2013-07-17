<?php

class Glo_Controller_Response_Http extends Zend_Controller_Response_Http {

    public function sendResponse() {
        // get the content
        $content = $this->getBody(false);
        
        $registry = Zend_Registry::getInstance();
        $config = $registry->get('config');
        
        $frontController = Zend_Controller_Front::getInstance();
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
            file_put_contents($logDir . $request->getHttpHost() . 'response.log', date('c') . " [RESPONSE]: " . $content . "\n", FILE_APPEND);
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
        
        echo $content;
        return;
    }
    
}
