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
class Core extends Singleton {
    
    /**
     * Initializes router, autoloading and some other basic functionalities
     * @return	void
     */
    public function main() {
    	ob_start();			// Prevents headers erroring everywhere
    	session_start();
    	
    	$this->loadBaseSystem();
    	
    	// Use default autoloading implementation
    	spl_autoload_extensions('.php');
    	spl_autoload_register();
    	
    	// Set an exception handler
    	set_exception_handler('\System\Core\Exceptions::handle');
    	
    	$router = $this->getRouter();
    }
    
    /**
     * Loads the bare minimum of system classes
     * @return 	void
     */
    private function loadBaseSystem() {
    	require_once 'system/core/router.php';
    }
    
    /**
     * Initiates a new Router object
     * @return 	Router
     */
    private function getRouter() {
    	return new Router();
    }
    
}