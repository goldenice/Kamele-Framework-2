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

    protected $db;
    protected $qb;

    public function __construct() {
        $this->db = self::_getDatabaseDriver();
        $this->qb = self::_getDatabaseQueryBuilder($this->db);
    }
    
    public static function _getDatabaseDriver() {
        $basepath = '\System\Database\Drivers\\' . ucfirst(DB_DRIVER) . '\\';
    	$dbclass = $basepath . 'Driver';
    	$db = new $dbclass;
    	$db->connect();
    	return $db;
    }
    
    public static function _getDatabaseQueryBuilder($dbdriver) {
        $basepath = '\System\Database\Drivers\\' . ucfirst(DB_DRIVER) . '\\';
    	$qbclass = $basepath . 'QueryBuilder';
        return new $qbclass($dbdriver);
    }

}