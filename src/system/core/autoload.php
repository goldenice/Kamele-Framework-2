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
class Autoload {
    
    /**
     * Class autoloader function
     * 
     * @param   string      $classname
     * @return  boolean
     */
    public static function load($classname) {
        require (self::getPath($classname) . strtolower(self::getClassName($classname)) . '.php');
    }
    
    /**
     * Gets class name from complete namespace path
     * 
     * @param   string      $namespace
     * @return  string
     */
    public static function getClassName($namespace) {
        return end(explode('\\', $namespace));
    }
    
    /**
     * Gets directory path from namespace
     * 
     * @param   string      $namespace
     * @param   string
     */
    public static function getPath($namespace) {
        $parts = explode('\\', $namespace);
        unset($parts[sizeOf($parts)-1]);
        $dir = strtolower(implode(DIR_SEPARATOR, array_values($parts)));          // Glue the stuff back together
        if ($dir == '' or $dir == DIR_SEPARATOR) {
            $dir = '';
        } 
        else {
            $dir .= DIR_SEPARATOR;
        }
        return $dir;
    }
    
}