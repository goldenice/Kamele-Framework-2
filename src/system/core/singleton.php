<?php
namespace System\Core;

if (!defined('SYSTEM')) exit('No direct script access allowed');

/**
 * Singleton class
 * 
 * Used as a base for other classes
 * 
 * @package     Kamele Framework
 * @subpackage  System
 * @since       2.0
 * @author      Rick Lubbers <me@ricklubbers.nl>
 * 
 * Based on the version from 1.0-alpha
 */
abstract class Singleton {
    
    /**
     * @access  private
     * @var     array       Array of instances
     */
    private static $instances = array();
    
    /**
     * Some default functions we need to disable!
     */
    protected function __construct() {}
    protected function __clone() {}
    public function __wakeup() {}

    /**
     * Creates 'new' instance of the class
     * 
     * @access  public
     * @return  Object
     * @final
     */ 
    public final static function getInstance() {
        $cls = get_called_class();
        if (!isset(self::$instances[$cls])) {
            self::$instances[$cls] = new static;
        }
        return self::$instances[$cls];
    }
    
}