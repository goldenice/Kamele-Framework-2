<?php
namespace System\Core;

if (!defined('SYSTEM')) exit('No direct script access allowed');

use \System\Exceptions\RouterException;

/**
 * Router class
 * 
 * @package		Kamele Framework
 * @subpackage	System
 * @since		2.0
 * @author		Rick Lubbers <me@ricklubbers.nl>
 * 
 * Based upon the 1.0 version
 */
class Router {
	
	const MODE_CLI = 0;
	const MODE_HTTP = 1;
	
	private $mode;
	
	/**
	 * Constructor method
	 * @return 	void
	 */
	public function __construct() {
		$this->mode = $this->isCli() ? self::MODE_CLI : self::MODE_HTTP;
		
		if ($this->mode == self::MODE_CLI) {
            if (isset($_SERVER['argv'][1])) {
    	        $this->route($_SERVER['argv'][0].'/'.$_SERVER['argv'][1]);    
            }
            else {
                $this->route($_SERVER['argv'][0]);
            }
		}
        else {
            $this->route($_SERVER['REQUEST_URI']);
        }
	}
	
	/**
	 * Checks if current environment is command line interface
	 * @return	bool
	 */
	public function isCli() {
        return PHP_SAPI == "cli";
	}
	
	/**
	 * Routes from path
	 * @param	string	$uri
	 * @return	void
	 */
	private function route($uri) {
		$parts = $this->splitUri($this->cleanPath($uri));
		$parts[] = '';
		$parts[] = '';
		$this->execute(
			$this->determineController($parts[0]), 
			$this->determineMethod($parts[1]), 
			$this->getArguments($parts)
			);
	}
	
	/**
	 * Loads and executes the controller specified
	 * @param	string		$controller
	 * @param	string		$method
	 * @return	void
	 */
	private function execute($class, $method, array $arguments = array()) {
		$class = CONTROLLER_NAMESPACE.$class;
		$controller = new $class;
		$controller->$method($arguments);
		$controller->_destruct();
	}
	
	/**
	 * Clean path for route detection
	 * @param	string		$uri
	 * @return	string
	 */
	private function cleanPath($uri) {
		$parts = explode('index.php', $uri);
		if (isset($parts[1])) {
			$uri = ltrim($parts[1], '?');
		} else {
			$uri = '/';
		}
		return trim($uri, '/');
	}
	
	/**
	 * Split URI into parts
	 * @param	string		$uri
	 * @return	string[]
	 */
	private function splitUri($uri) {
		return explode('/', $uri);
	}
	
	/**
	 * Determines the controller from the relevant part of the uri
	 * @param	string		$uripart
	 * @return	string
	 */
	private function determineController($uripart) {
		if ($uripart == '' || $uripart == null) {
			return DEFAULT_CONTROLLER;
		}
		return ucfirst($uripart);
	}
	
	/**
	 * Determines the method of the controller to load
	 * @param	string		$uripart
	 * @return	string
	 */
	private function determineMethod($uripart) {
		if ($uripart == '' || $uripart == null) {
			return DEFAULT_CONTROLLER_METHOD;
		}	
		return $uripart;
	}
	
	/**
	 * Gets arguments out of the uriparts array
	 * @param	string[]	$uriparts
	 * @return	string[]
	 */
	private function getArguments(array $uriparts) {
		unset($uriparts[0]);
		unset($uriparts[1]);
		return array_values($uriparts);
	}
	
	
}