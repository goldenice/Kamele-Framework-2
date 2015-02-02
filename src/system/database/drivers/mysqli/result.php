<?php
namespace System\Database\Drivers\Mysqli;

use \System\Database\DatabaseResult;

if (!defined('SYSTEM')) exit('No direct script access allowed');

/**
 * Fetches result of a mysqli result object
 * 
 * @package		Kamele Framework
 * @subpackage	System
 * @since		2.0
 * @author		Rick Lubbers <me@ricklubbers.nl>
 */
class Result implements DatabaseResult {
	
	private $mysqli_res = null;
	
	public function __construct($result) {
		$this->mysqli_res = $result;
	}
	
	public function fetchArray() {
		return $this->mysqli_res->fetch_assoc();
	}
	
	public function fetchObject() {
		return $this->mysqli_res->fetch_object();
	}
	
	public function fetchMultiArray() {
		return $this->mysqli_res->fetch_all(MYSQLI_ASSOC);
	}
	
	public function fetchMultiObject() {
		$output = array();
		while (($cur = $this->fetchObject()) != null) {
			$output[] = $cur;
		} 
		return $output;
	}
	
}