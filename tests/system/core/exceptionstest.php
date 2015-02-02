<?php
class ExceptionsTest extends PHPUnit_Framework_Testcase {
    
    public function setUp() {
        set_include_path('src');
        require_once 'system/core/exceptions.php';
    }
    
   	public function testHandle() {
   		$exception = new Exception("MESSAGE", 1, null);
   		ob_start();
   			\System\core\Exceptions::handle($exception);
   		$output = ob_get_clean();
   		$this->assertEquals(
   			"Exception: MESSAGE<br />On line ".$exception->getLine()." in file ".
   			$exception->getFile()."<br />Trace: ".$exception->getTraceAsString().'<br /><br />', 
   			$output, 
   			"Exception handling outputs correctly."
   		);
   	}
	
}
    