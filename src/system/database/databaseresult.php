<?php
namespace System\Database;

if (!defined('SYSTEM')) exit('No direct script access allowed');

/**
 * Interface for database results
 * 
 * @package		Kamele Framework
 * @subpackage	System
 * @since		2.0
 * @author		Rick Lubbers <me@ricklubbers.nl>
 */
interface Databaseresult {
    
    /**
     * Constructor that stores the native result data
     * @param   ?       $result
     * @return  void
     */
    public function __construct($result);
    
    /**
     * Fetch a single row as array
     * @return  array
     */
    public function fetchArray();
    
    /**
     * Fetch a single row as object
     * @return  object
     */
    public function fetchObject();
    
    /**
     * Fetch a multidimensional array
     * @return  array(array)
     */
    public function fetchMultiArray();
    
    /**
     * Fetch an array of objects (all data)
     * @return  array(object)
     */
    public function fetchMultiObject();
    
}