<?php
namespace System\Database\Drivers\Mysqli;

if (!defined('SYSTEM')) exit('No direct script access allowed');

use \System\Database\DatabaseDriver;

/**
 * Driver for mysqli database
 * 
 * @package		Kamele Framework
 * @subpackage	System
 * @since		2.0
 * @author		Rick Lubbers <me@ricklubbers.nl>
 */
class Driver implements DatabaseDriver {
	
	private $handler;
	private $last_query_result;
	
	public function connect($host = DB_HOST, $user = DB_USER, $pass = DB_PASS, $name = DB_NAME) {
		$this->handler = new Mysqli($host, $user, $pass);
		return (!$this->handler->connect_error && $this->selectDb($name));
	}
	
	public function selectDb($dbname) {
		return $this->handler->select_db($dbname);
	}
	
	public function query($query) {
		return $last_query_result = $this->handler->query($query);
	}
	
	public function rowsAffected() {
		return $this->handler->affected_rows;
	}
	
	public function lastError() {
		return $this->handler->error;
	}
	
}