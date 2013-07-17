<?php

class Glo_Form_Element extends Zend_Form_Element
{

    public function isValid($value, $context = null)
    {
        if ($this->isRequired())
        {
            if (!array_key_exists($this->getName(), $context))
            {
                throw new Exception_MissingParameter($this->getName() . " is a required parameter");
            }
            elseif (!$value)
            {
                throw new Exception_BadData($this->getName() . " cannot be null");
            }
        }
        return parent::isValid($value, $context);
    }
    
}