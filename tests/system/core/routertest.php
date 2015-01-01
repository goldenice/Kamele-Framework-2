<?php
class RouterTest extends PHPUnit_Framework_Testcase {
    
    private $router;
    
    public function setUp() {
        set_include_path('src');
        require_once 'system/core/router.php';
        $this->router = new \System\Core\Router(true);
    }
    
    public function testCleaning() {
        $uri = 'index.php?/class/method/arg1/arg2/';
        $uri2 = '///';
        $this->assertEquals('class/method/arg1/arg2', $this->router->cleanPath($uri), "Cleaning the URI");
        $this->assertEquals('', $this->router->cleanPath($uri2), "Cleaning empty URI to slash");
    }
    
    public function testDetermineController() {
        $class = 'class';
        $class2 = '';
        $this->assertEquals('Class', $this->router->determineController($class), "Determine class with input");
        $this->assertEquals('Home', $this->router->determineController($class2), "Determine class without input");
    }
    
    public function testDetermineMethod() {
        $method = 'method';
        $method2 = '';
        $this->assertEquals('method', $this->router->determineMethod($method), "Determine method with input");
        $this->assertEquals('index', $this->router->determineMethod($method2), "Determine method without input");
    }
    
    public function testDetermineArguments() {
        $args = array('class', 'method', 'arg1', 'arg2');
        $args2 = array('class', 'method');
        $this->assertEquals(array('arg1', 'arg2'), $this->router->getArguments($args), "Determine arguments with sufficient input");
        $this->assertEquals(array(), $this->router->getArguments($args2), "Determine arguments without sufficient input");
    }
    
    public function testSplit() {
        $input = 'class/method/arg1/arg2';
        $this->assertEquals(array('class', 'method', 'arg1', 'arg2'), $this->router->splitUri($input), "Split URI test");
    }

}