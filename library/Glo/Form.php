<?php
/**
 * Form
 */
abstract class Glo_Form extends Zend_Form 
{

/*
    public function __construct($options = null) {
        parent::__construct($options);
        
        try {
            $path = dirname(__FILE__)
                . '/../../languages/'
                . strtolower(preg_replace('/_/', '/', get_class($this)));        
            $this->_translator = new Zend_Translate(
                array(
                    'adapter'           => 'ini',
                    'content'           => $path,
                    'locale'            => 'auto',
                    'scan'              => Zend_Translate::LOCALE_FILENAME,
                    'disableNotices'    => true,
                )
            );            
            if ($this->_translator->isAvailable($this->view->loggedInUser->languagePref)) {
                $this->_translator->setLocale($this->view->loggedInUser->languagePref);
            } else {
                $this->_translator->setLocale('en');
            }
        } catch (Exception $ex) {
            // so, the language dir didn't exist...what're you gonna do    
            //echo $ex->getMessage();
        }
        
    } 
*/



}
