<?php

class Glo_Util_Uuid
{

    /**
     * generate
     *
     * v4
     */
    public static function generate()
    {
        if (!function_exists('uuid_create'))  
            return false;  
        
        uuid_create(&$context);
        uuid_make($context, UUID_MAKE_V4);  
        uuid_export($context, UUID_FMT_STR, &$uuid);  
        return trim($uuid);  
    }
    
}