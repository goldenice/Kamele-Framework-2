<?php
class ViewTest extends PHPUnit_Framework_Testcase {
    
    // Contains the view objects
    private $replacement_view;
    private $incl_view;
    private $foreach_view;
    
    public function setUp() {
        set_include_path('src');
        require_once 'system/core/view.php';
        
        $this->replacement_view = new \System\Core\View(dirname(__FILE__).'/extra/test_view.txt');
        $this->incl_view = new \System\Core\View(dirname(__FILE__).'/extra/test_view_inclusion.txt');
        $this->foreach_view = new \System\Core\View(dirname(__FILE__).'/extra/test_view_foreach.txt');
    }
    
    public function testBasic() {
    	$data = array('var1'=>'x', 'var2'=>'y');
    	$this->assertEquals('before x y after', $this->replacement_view->render($data), 'Pure replacement test');
    	$this->assertEquals('before x y after x', $this->incl_view->render($data), 'Replacement plus inclusion test');
    	ob_start();
    		$this->incl_view->output($data);
    	$output = ob_get_clean();
    	$this->assertEquals($this->incl_view->render($data), $output, 'Output and rendering are the same');
    }
    
    public function testForeach() {
    	$data = array('var1'=>array('x', 'y', 'z'));
    	$this->assertEquals('<ul><li>x</li><li>y</li><li>z</li></ul>', $this->foreach_view->render($data), 'Foreach test');
    }
    
}