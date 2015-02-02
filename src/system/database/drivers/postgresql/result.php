<?php
namespace System\Database\Drivers\Postgresql;

use \System\Database\DatabaseResult;

if (!defined('SYSTEM')) exit('No direct script access allowed');

/**
 * Fetches result of a postgresql result object
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
		return pg_fetch_assoc($this->result);
	}
	
	public function fetchObject() {
		return pg_fetch_object($this->result);
	}
	
	public function fetchMultiArray() {
		return pg_fetch_all($this->result);
	}
	
	public function fetchMultiObject() {
		$output = array();
		while (($cur = $this->fetchObject()) != null) {
			$output[] = $cur;
		} 
		return $output;
	}
	
}