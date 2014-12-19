<?php
namespace System\Core;

if (!defined('SYSTEM')) exit('No direct script access allowed');

/**
 * Kamele Core class
 * 
 * @package     Kamele Framework
 * @subpackage  System
 * @author      Rick Lubbers <me@ricklubbers.nl>
 * @since       2.0
 */
class Kamele extends Singleton {
    
    /**
     * Initializes router, autoloading and some other basic functionalities
     * @return	void
     */
    public function main() {
    	ob_start();			// Prevents headers erroring everywhere
    	session_start();
    	
    	$this->loadSystem();
    	$this->setHandlers(
    	        array('\System\Core\Autoload', 'load'),
    	        array('\System\Core\Exceptions', 'handle')
    	    );
    	
    	$router = $this->getRouter();
    }
    
    /**
     * Loads the bare minimum of system classes
     * @return 	void
     */
    private function loadSystem() {
    	require_once 'system/core/router.php';
    }
    
    /**
     * Initiates a new Router object
     * @return 	Router
     */
    private function getRouter() {
    	return new Router();
    }
    
    /**
     * Set handler functions for a few things like autoload, errors and exceptions
     * @param	string[]	$autoload
     * @param	string[]	$error
     * @param	string[]	$exception
     * @return	void
     */
    private function setHandlers($autoload, $error, $exception) {
    	spl_autoload_register($autoload);
        set_exception_handler($exception);
    }
    
}