<?php


class Glo_Controller_Action_Helper_ActionStackOverride extends Zend_Controller_Action_Helper_ActionStack
{

    public function __construct()
    {
        parent::__construct();
        $this->_actionStack->setClearRequestParams(true);
    }


}