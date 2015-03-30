<?php
namespace System\Application;

if (!defined('SYSTEM')) exit('No direct script access allowed');

/**
 * Application base class
 * Some functions that Controllers, Models and all other classes have in common
 * 
 * @package		Kamele Framework
 * @subpackage	System
 * @since		2.0
 * @author		Rick Lubbers <me@ricklubbers.nl>
 */
abstract class Application {

	protected $instances = array();
	
	public final function get($class, $args = array()) {
		if (isset($instances[$class])) {
			return $instances[$class];
		}
		if (class_exists($classname)) {
			if (is_subclass_of($class, '\System\Core\Singleton')) {
				return $instances[$class] = $class::getInstance();
			} 
			else {
			    $reflection = new ReflectionClass($class);
                return $instances[$class] = $reflection->newInstanceArgs($args);
			}
		}
	}
    
}
