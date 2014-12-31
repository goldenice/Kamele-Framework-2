<?php
class ViewTest extends PHPUnit_Framework_Testcase {
    
    // Contains the view objects
    private $replacement_view;
    private $incl_view;
    
    public function setUp() {
        define('SYSTEM', 'KAMELE2-UNITTESTING');
        define('KAMELE', '2.x');
        define('DIR_SEPARATOR', '/');
        define('BASEURL', '');
        
        set_include_path('src');
        require_once 'system/core/view.php';
        
        $this->replacement_view = new \System\Core\View(dirname(__FILE__).'/extra/test_view.txt');
        $this->incl_view = new \System\Core\View(dirname(__FILE__).'/extra/test_view_inclusion.txt');
    }
    
    public function testBasic() {
    	$data = array('var1'=>'x', 'var2'=>'y');
    	$this->assertEquals('before x y after', $this->replacement_view->render($data), 'Pure replacement test');
    	$this->assertEquals('before x y after x', $this->incl_view->render($data), 'Replacement plus inclusion test');
    }
    
}