<?php

class Glo_Loader extends Zend_Loader
{
 
    public static function loadClass($class, $dirs = null)
    {
/*
        require_once 'Glo/SplitTest.php';
        $class = Glo_SplitTest::getClassname($class);
*/
        parent::loadClass($class, $dirs);
    }
    
}