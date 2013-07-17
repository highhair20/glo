<?php

/**
 * Glo_Cli
 *
 * All scripts and crons should extend this class.  Allows the developer of a
 * script to easily create a help menu for the script as well as enforce the 
 * requirement that an environment be specified when running the script.
 */
abstract class Glo_Cli
{

    private $_switches = array();
    private $_requiredSwitches = array();
    private $_validSwitches = array();
    protected $_params = array();

    /**
     *
     *
     */
    public function __construct()
    {
        $this->_addSwitches();
        
        // add help switch
        $description = 'this help';
        $this->_addSwitch('help', $description, false);
                
        // add env switch
        $description = 'environment you are running the script in';
        require_once __DIR__ . '/Util/Server.php';
        $this->_addSwitch('env', $description, true, Glo_Util_Server::getEnvironments());
                
        $this->_setParams();
        try
        {
            $this->_validateSwitches();
            Glo_Util_Server::setEnvironment($this->_params['env']);
            require_once __DIR__ . '/Bootstrap.php';
        }
        catch (Exception $ex)
        {
            echo "\n" . $ex->getMessage() . "\n";
            $this->_showHelp();
        }
    }
    

    protected function _addSwitches() {}
    
    
    /**
     *
     */
    public function _addSwitch($name, $description, $isRequired = true, $options = array(), $isBoolean = false)
    {
        $this->_switches[$name] = array(
            'description'   => $description, 
            'is_required'   => $isRequired,
            'options'       => $options,
            'is_bool'       => $isBoolean,
        );
        if ($isRequired)
        {
            $this->_requiredSwitches[] = $name;
        }
        return;
    }

    
    /**
     *
     */
    private function _setParams()
    {
        global $argv;
        $args = $argv;
                
        array_shift($args);
        $args = explode('--', implode(' ', $args));
        array_shift($args);
        
        foreach ($args as $arg) 
        {
            if (!trim($arg))
            {
                continue;
            }
            
            $arg = trim($arg);
            $argParts = explode(' ', $arg);
            $switch = trim($argParts[0]);
            $value = '';
            if (count($argParts) == 2)
            {
                $value = trim($argParts[1]);
            }
            
            $this->_params[$switch] = $value;            
        }
        return;
    }
    

    /**
     *
     *
     */
    private function _validateSwitches()
    {
        // show help if it's specified
        if (array_key_exists('help', $this->_params))
        {
            throw new Exception();
        }
    
        $validSwitches = array_keys($this->_switches);
        $requiredSwitches = $this->_requiredSwitches;
        $passedSwitches = array_keys($this->_params);
        
        // were all required switches passed
        foreach ($requiredSwitches as $switch)
        {            
            if (!in_array($switch, $passedSwitches))
            {
                throw new Exception("Required switch '--$switch' not passed.");
            }
        }        
        

        foreach ($this->_params as $switch => $value) 
        {
            // were any switches passed that are not valid
            if (!in_array($switch, $validSwitches))
            {
                throw new Exception("Invalid switch '--$switch' was passed.");
            }
            
            // is this switch boolean but has a value
            if ($this->_switches[$switch]['is_bool'] && $value)
            {
                throw new Exception("Switch '--$switch' is boolean but value '$value' was passed.");
            }
            
            // is this switch non boolean but has no value
            if (!($this->_switches[$switch]['is_bool']) && !$value)
            {
                throw new Exception("Switch '--$switch' requires a value.");
            }
            
            // if options were specified, is this value a valid option
            if (count($this->_switches[$switch]['options']) && !in_array($value, $this->_switches[$switch]['options']))
            {
                throw new Exception("Invalid option '$value' was specified for switch '--$switch'.");
            }
        }
    }
    
    
    /**
     *
     *
     */
    protected function _showHelp() 
    {
        echo "\n";
        echo "php " . get_class($this) . ".php [options]\n";
        echo "\n";
        $lines = array();
        $lengths = array();
        foreach ($this->_switches as $name => $params)
        {   
            $len = 8;
            $str = str_repeat(' ', 8);
            if ($params['is_required'])
            {
                $str .= " "; 
            }
            else
            {
                $str .= "[";
            }
            $len++;
            $str .= "--$name";
            $len += 2 + strlen($name);
            if ($params['is_required'])
            {
                $str .= " "; 
            }
            else
            {
                $str .= "]";
            }
            $len++;
            $lengths[] = $len;
            $lines[$name] = $str;
            
        }
        sort($lengths);
        $maxLen = array_pop($lengths);
        foreach ($lines as $name => $line)
        {
            $line .= str_repeat(' ', $maxLen - strlen($line));
            echo "$line  " . $this->_switches[$name]['description'] . "\n";
            if ($this->_switches[$name]['options'])
            {
                echo str_repeat(' ', $maxLen + 2) . "(options: " . implode(', ',$this->_switches[$name]['options']) . ")\n";
            }
        }
        echo "\n";
        exit;
    }

}