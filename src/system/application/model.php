<?php
namespace System\Application;

if (!defined('SYSTEM')) exit('No direct script access allowed');

/**
 * Model
 * 
 * @package		Kamele Framework
 * @subpackage	System
 * @since		2.0
 * @author		Rick Lubbers <me@ricklubbers.nl>
 */
abstract class Model extends Application {

    protected static $db;
    protected static $qb;
    
    public function __get($name) {
    	if (isset($this->$name)) {
    		return $this->$name;
    	} else if ($name == "db") {
    		return _getDatabaseDriver();
    	} else if ($name == "qb") {
    		return _getDatabaseQueryBuilder($this->db);
    	} else {
    		return null;
    	}
    }
    
    public static function _getDatabaseDriver() {
    	if (!isset(self::$db)) {
        	$dbclass = '\System\Database\Drivers\\' . ucfirst(DB_DRIVER) . '\Driver';
    		self::$db = new $dbclass;
    		self::$db->connect();
    	}
    	return self::$db;
    }
    
    public static function _getDatabaseQueryBuilder($dbdriver) {
    	if (!isset(self::$qb)) {
        	$qbclass = '\System\Database\Drivers\\' . ucfirst(DB_DRIVER) . '\QueryBuilder';
    		self::$qb = new $qbclass(self::_getDatabaseDriver());
    	}
        return self::$qb;
    }

}