<?php

class Glo_Environment
{
    
    const ENV_DEV_JASON         = 'jason';
    const ENV_DEV_ANDREW        = 'andrew';
    const ENV_DEV_ASH           = 'ash';
    
    const ENV_QA                = 'qa';
    
    const ENV_PRODUCTION 	    = 'production';
    
    private static $_environment = '';
    
    
    /**
     * Determine which environment we are in
     * (production, stage, jason, ...)
     *
     * @return string the environment
     */
    public static function get()
    {
        return self::$_environment;
    }
    
    
    /**
     * setEnvironment
     *
     * Allows one to override the default behavior of this class and 
     * set the environment manually.  This may be necessary of you are
     * running dev or qa code in a spot that this class would mistake
     * for some other environment.
     */
    public static function set($const = null)
    {
        if ($const)
        {
            self::$_environment = $const;
        }
        elseif (getenv('APPLICATION_ENV'))
        {
            self::$_environment = getenv('APPLICATION_ENV');
        }
        else
        {
            throw new Exception('No environment specified.');
        }
        return;
    }
    
    
    public static function getEnvironmentString()
    {
        $envString = '';
        $env = self::get();
        if ($env != self::ENV_PRODUCTION)
        {
            $envString = "$env-";
        }
        return $envString;
    }
    
    
    public static function getEnvironmentBaseURL()
    {  
        $protocol = 'http';
        if (array_key_exists('SERVER_PROTOCOL', $_SERVER))
        {
            $protocol = strtolower(array_shift(explode('/', $_SERVER['SERVER_PROTOCOL'])));
        }
        
        $baseUrl = self::getEnvironmentString() . 'api.farmling.com';
     
        $baseUrl = $protocol . "://" . $baseUrl;
        
        return $baseUrl;
    }
    
    
    public static function getClientIp()
    {
        $clientIp = null;
        if (array_key_exists('HTTP_X_LFS_CLIENTIP', $_SERVER))
        {
            $clientIp = $_SERVER['HTTP_X_LFS_CLIENTIP'];
        }
        elseif (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) 
        {
            $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($ips as $ip) 
            {
                $ip = trim($ip);
                $segments = explode('.', $ip);
                if (preg_match('/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/', $ip)
                        && $ip != '127.0.0.1' 
                        && $segments[0] != '10' 
                        && !($segments[0] == '192' && $segments[1] == '168')) 
                {
                    $clientIp = $ip;
                    break;
                }
            }
            if (empty($clientIp) && !empty($_SERVER['REMOTE_ADDR'])) 
            {
                $clientIp = $_SERVER['REMOTE_ADDR'];
            }
        }
        else
        {
            $clientIp = $_SERVER['REMOTE_ADDR'];
        }
        return $clientIp;
    }
    
    public static function getEnvironments()
    {
        $ref = new ReflectionClass('Glo_Util_Server');
        $envs = $ref->getConstants();
        return $envs;
    }
    
}
