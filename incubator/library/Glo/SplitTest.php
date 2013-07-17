<?php

class Glo_SplitTest
{

    private $_tests = array();
    
    
    private static $_instance = null;
    
    
    public function __construct()
    {
        $this->_loadTests();
    }
    
    
    private function _loadTests()
    {
/*
        // get the player who made the request
        $playerId = xyz;
        // query the table for all tests for this user
        $tests = App_Model_Mapper_SplitTest::findActiveByPlayer($playerId);
        $this->_tests[$player] = $tests;
*/
        return;
    }
    
    
    private function _getTestClassname($class)
    {
        return false;
    }
    
    
    public static function instance()
    {
        if (!self::$_instance)
        {
            self::$_instance = new Glo_SplitTest();
        }
        return self::$_instance;
    }
    
    
    public static function getClassname($class)
    {
        $instance = self::instance();
        $testClass = $instance->_getTestClassname($class);
        $class = $testClass ? $testClass : $class;
        return $class;
    }
    

            
    
/*
    static public function playerFeatureGroupsSql() {
        return 'select fgx.feature_name
            FROM FEATURE_GROUP_XREF fgx 
            INNER JOIN PLAYER_SOURCE ps 
              ON fgx.GROUP_ID = ps.GROUP_ID 
            WHERE 
                ps.PLAYER_ID = :player_id AND 
                (
                    fgx.START_DATE <= sysdate OR 
                    fgx.START_DATE IS NULL
                ) AND 
                (
                    fgx.END_DATE >= sysdate OR 
                    fgx.END_DATE IS NULL
                )
                AND fgx.status_id=1 ';
    }
*/
    
/*
    public function getGroup($playerId)
    {
        $query = "SELECT GROUP_ID "
            . "FROM PLAYER_SOURCE "
            . "WHERE PLAYER_ID = :player_id";
        
        $stmt = oci_parse($this->db_obj, $query);
        
        oci_bind_by_name($stmt, ":player_id", $playerId);

        if (!oci_execute($stmt)) 
        {
            return false;
        }

        if (!$row = oci_fetch_object($stmt)) 
        {
            return false;
        }

        return $row->GROUP_ID;
    }
*/
    
    
/*
    public function getFeatureStatus($featureName, $playerId)
    {
        $query = "SELECT STATUS_ID 
            FROM FEATURE_GROUP_XREF a 
            INNER JOIN PLAYER_SOURCE b ON a.GROUP_ID = b.GROUP_ID 
            WHERE 
                a.FEATURE_NAME = :feature_name AND 
                b.PLAYER_ID = :player_id AND 
                (
                    a.START_DATE <= sysdate OR 
                    a.START_DATE IS NULL
                ) AND 
                (
                    a.END_DATE >= sysdate OR 
                    a.END_DATE IS NULL
                )";
        
        $stmt = oci_parse($this->db_obj, $query);
        
        oci_bind_by_name($stmt, ":feature_name", $featureName);
        oci_bind_by_name($stmt, ":player_id", $playerId);

        if (!oci_execute($stmt)) 
        {
            return false;
        }

        if (!$row = oci_fetch_object($stmt)) 
        {
            return false;
        }

        return $row->STATUS_ID;
    }
*/
    
/*
    public function getFeatureStatusNewOnly($featureName, $playerId)
    {
        $query = "SELECT STATUS_ID 
            FROM FEATURE_GROUP_XREF a 
            INNER JOIN PLAYER_SOURCE b ON a.GROUP_ID = b.GROUP_ID 
            WHERE 
                a.FEATURE_NAME = :feature_name AND 
                b.PLAYER_ID = :player_id AND 
                a.start_date < b.create_dtime AND
                (
                    a.START_DATE <= sysdate OR 
                    a.START_DATE IS NULL
                ) AND 
                (
                    a.END_DATE >= sysdate OR 
                    a.END_DATE IS NULL
                )";
        
        $stmt = oci_parse($this->db_obj, $query);
        
        oci_bind_by_name($stmt, ":feature_name", $featureName);
        oci_bind_by_name($stmt, ":player_id", $playerId);

        if (!oci_execute($stmt)) 
        {
            return false;
        }

        if (!$row = oci_fetch_object($stmt)) 
        {
            return false;
        }

        return $row->STATUS_ID;
    }
*/
    
    
    /**
     * _ACT_tag_player
     *
     * $ACT = new 
     *
     * @param int player id
     */
/*
    public function tagPlayer($playerId)
    {
        $source = null;
        $subsource = null;
        $creative = null;
        $platform = null;
        $app = null;
        
        $query = "MERGE INTO player_source dest "
            . "USING ("
                . "SELECT "
                    . ":player_id player_id "
                . "FROM dual) src "
            . "ON (dest.player_id = src.player_id) "
            . "WHEN NOT MATCHED THEN "
                . "INSERT ("
                    . "player_id, "
                    . "group_id, "
                    . "source, "
                    . "subsource, "
                    . "creative, "
                    . "platform, "
                    . "app, "
                    . "create_dtime, "
                    . "modify_dtime "
                . ") VALUES ("
                    . ":player_id, "
                    . ":group_id, "
                    . ":source, "
                    . ":subsource, "
                    . ":creative, "
                    . ":platform, "
                    . ":app, "
                    . "sysdate, "
                    . "sysdate "
                . ")";
        $stmt = oci_parse($this->db_obj, $query);
        
        $groupId = mt_rand(1, 100); 
        oci_bind_by_name($stmt, ":player_id", $playerId);
        oci_bind_by_name($stmt, ":group_id", $groupId);
        oci_bind_by_name($stmt, ":source", $source);
        oci_bind_by_name($stmt, ":subsource", $subsource);
        oci_bind_by_name($stmt, ":creative", $creative);
        oci_bind_by_name($stmt, ":platform", $platform);
        oci_bind_by_name($stmt, ":app", $app);
        
        if(!oci_execute($stmt)) 
        {
            return false;
        }
        return $groupId;
    }
*/
    
    
}
