<?php
namespace System\Database;

if (!defined('SYSTEM')) exit('No direct script access allowed');

/**
 * Querybuilder interface
 * 
 * @package		Kamele Framework
 * @subpackage	System
 * @since		2.0
 * @author		Rick Lubbers <me@ricklubbers.nl>
 */
interface QueryBuilder {
	
	public function execute();
	
	public function select($columns);
	public function update($table, array $keyvalpairs, $quoteval);
	public function insert($table, $values, $columns);
	public function delete($table);
	
	public function from($input);
	public function where($input);
	public function orderBy($input);
	public function limit($input);
	
}