<?php
namespace System\Application;

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
        $this->loadView();
    }
    
    /**
     * Loads view when variables are correctly set
     * @return  boolean
     */
    protected function _loadView() {
        if ($this->view == true) {
            $this->view_obj = new View($view_path);
            return true;
        }
        return false;
    }
    
    /**
     * Outputs the view to the browser if it is set
     * @return  boolean
     */
    protected function _outputView() {
        if ($this->view == true && $this->view_obj instanceof \System\Core\View) {
            $this->view_obj->output($this->view_data);
            return true;
        }
        return false;
    }
    
    /**
     * Destructor function
     * @return  void
     */
    public function __destruct() {
        $this->renderView();
    }
    
}