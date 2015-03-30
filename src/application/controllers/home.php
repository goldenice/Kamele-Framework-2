<?php
namespace Application\Controllers;

// System classes
use System\Application\Controller;

// Models
use Application\Models\Info;


if (!defined('SYSTEM')) exit('No direct script access allowed');

class Home extends Controller {
    
    public function __construct() {
        $this->view = true;
        $this->view_path = "application/views/home.html";
        parent::__construct();
    }
    
    public function index($arg = null) {
        $model = new Info();
        
    	$this->view_data['title'] = $model->getTitle();
    	$this->view_data['text'] = $model->getHelloWorld();
    	$this->view_data['important'] = true;
    }
}