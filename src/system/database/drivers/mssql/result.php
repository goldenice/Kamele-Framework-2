<?php
namespace System\Database\Drivers\Mssql;

use \System\Database\DatabaseResult;

if (!defined('SYSTEM')) exit('No direct script access allowed');

/**
 * Fetches result of a mssql result object
 * 
 * @package		Kamele Framework
 * @subpackage	System
 * @since		2.0
 * @author		Rick Lubbers <me@ricklubbers.nl>
 */
class Result implements DatabaseResult {
	
	private $result = null;
	
	public function __construct($result) {
		$this->result = $result;
	}
	
	public function fetchArray() {
		return mssql_fetch_assoc($this->result);
	}
	
	public function fetchObject() {
		return mssql_fetch_object($this->result);
	}
	
	public function fetchMultiArray() {
	    $output = array();
	    while (($cur = $this->fetchArray()) != null) {
	        $output[] = $cur;
	    }
		return $output;
	}
	
	public function fetchMultiObject() {
		$output = array();
		while (($cur = $this->fetchObject()) != null) {
			$output[] = $cur;
		} 
		return $output;
	}
	
}