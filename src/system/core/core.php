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
    	
    	// Use default autoloading implementation
    	spl_autoload_extensions('.php');
    	spl_autoload_register('spl_autoload');
    	
    	// Set an exception handler
    	set_exception_handler('\System\Core\Exceptions::handle');
    	
    	$router = new Router();
    }
    
}