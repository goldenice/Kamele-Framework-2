<?php
namespace System\Database;

if (!defined('SYSTEM')) exit('No direct script access allowed');

/**
 * Interface for database drivers
 * 
 * @package		Kamele Framework
 * @subpackage	System
 * @since		2.0
 * @author		Rick Lubbers <me@ricklubbers.nl>
 */
interface DatabaseDriver {
	
	/**
	 * Connects to the database
	 * @return	void
	 */
	public function connect($host, $user, $pass, $name);
		
	/**
	 * Selects a database to execute queries on
	 * @param   string      $dbname
	 * @return  boolean
	 */
	public function selectDb($dbname);
	
	/**
	 * Executes a given query
	 * When writing returns true on success, false on failure
	 * When reading, returns DatabaseResult object
	 * 
	 * @param	string		$query
	 * @return 	DatabaseResult
	 */
	public function query($query);
	
	/**
	 * Returns number of affected rows by last query
	 * @return	int
	 */
	public function rowsAffected();
	
	/**
	 * Returns last error that occurred
	 * @return	string
	 */
	public function lastError();
	
}