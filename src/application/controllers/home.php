<?php
namespace Application\Controllers;
use System\Application\Controller;

if (!defined('SYSTEM')) exit('No direct script access allowed');

class Home extends Controller {
    
    public function __construct() {
        $this->view = true;
        $this->view_path = "application/views/home.html";
        parent::__construct();
    }
    
    public function index($arg = null) {
    	$this->view_data['title'] = 'Kamele 2.x';
    }
}