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
    	$basepath = '\System\Database\Drivers\\' . ucfirst(DB_DRIVER) . '\\';
    	$dbclass = $basepath . 'Driver';
    	$qbclass = $basepath . 'QueryBuilder';
        $this->db = new $dbclass;
        $this->qb = new $qbclass($this->db);
    }

}