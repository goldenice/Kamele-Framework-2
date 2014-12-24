<?php
namespace System\Database;

if (!defined('SYSTEM')) exit('No direct script access allowed');

/**
 * Wrapper around the selected database driver
 * 
 * @package		Kamele Framework
 * @subpackage	System
 * @since		2.0
 * @author		Rick Lubbers <me@ricklubbers.nl>
 */
class Wrapper implements DatabaseDriver {
    
    private $driver;
    
    public function __construct($driver = DB_DRIVER) {
    	$this->driver = $driver;
    }
    
    public function __call($name, $arguments) {
    	call_user_func_array(array($this->driver, $name), $arguments);
    }
    
}