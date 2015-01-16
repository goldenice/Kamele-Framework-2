<?php
namespace System\Database\Drivers\Mssql;

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
	
	private $host;
	private $user;
	private $pass;
	
	public function connect($host = DB_HOST, $user = DB_USER, $pass = DB_PASS, $name = DB_NAME) {
		$this->host = $host;
		$this->user = $user;
		$this->pass = $pass;
		$this->handler = mssql_connect($host, $user, $pass, true);
		return ($this->handler != false && $this->handler != null);
	}
	
	public function selectDb($dbname) {
		return mssql_select_db($dbname);
	}
	
	public function query($query) {
		return $last_query_result = new Result(mssql_query($query, $this->handler));
	}
	
	public function lastResult() {
		return $last_query_result;
	}
	
	public function rowsAffected() {
		return pg_affected_rows($this->handler);
	}
	
	public function lastError() {
		return pg_last_error($this->handler);
	}
	
	public function escape($input) {
		return pg_escape_string($this->handler, $input);
	}
	
}