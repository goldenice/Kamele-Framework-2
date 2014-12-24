<?php
namespace System\Application;

if (!defined('SYSTEM')) exit('No direct script access allowed');

/**
 * Model
 * 
 * @package		Kamele Framework
 * @subpackage	System
 * @since		2.0
 * @author		Rick Lubbers <me@ricklubbers.nl>
 */
abstract class Model extends Application {

    protected $db;

    public function __construct() {
        $this->db = new \System\Database\Wrapper;
    }

}