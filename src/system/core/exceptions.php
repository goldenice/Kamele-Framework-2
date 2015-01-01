<?php
namespace System\Core;

if (!defined('SYSTEM')) exit('No direct script access allowed');

/**
 * Autoload class
 * 
 * @package     Kamele Framework
 * @subpackage  System
 * @since       2.0
 * @author      Rick Lubbers <me@ricklubbers.nl>
 */
class Exceptions {
	
	/**
	 * Handles an exception
	 * @param	Exception	$e
	 * @return	void
	 */
	public static function handle(\Exception $e) {
		echo 'Exception: '.$e->getMessage().'<br />';
		echo 'On line '.$e->getLine().' in file '.$e->getFile().'<br />';
		echo 'Trace: '.$e->getTraceAsString().'<br /><br />';
	}
	
}