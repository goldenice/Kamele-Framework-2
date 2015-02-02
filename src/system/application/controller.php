<?php
namespace System\Application;
use System\Core\View;

if (!defined('SYSTEM')) exit('No direct script access allowed');

/**
 * Controller
 * 
 * @package		Kamele Framework
 * @subpackage	System
 * @since		2.0
 * @author		Rick Lubbers <me@ricklubbers.nl>
 */
abstract class Controller extends Application {
    
    protected $view = false;
    protected $view_obj = null;
    protected $view_path = "";
    protected $view_data = array();
    
    /**
     * Constructor function
     * @return  void
     */
    public function __construct() {
        $this->_loadView();
    }
    
    /**
     * Loads view when variables are correctly set
     * @return  boolean
     */
    protected function _loadView() {
        if ($this->view == true) {
            $this->view_obj = new View($this->view_path);
            return true;
        }
        return false;
    }
    
    /**
     * Destructor. IS NOT BUILT-IN DESTRUCTOR BY PHP! Is called by Router class...
     * @return  boolean
     */
    public function _destruct() {
        if ($this->view == true && $this->view_obj != null) {
            $this->view_obj->output($this->view_data);
            return true;
        }
        return false;
    }
    
}