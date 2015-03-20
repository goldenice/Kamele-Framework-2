<?php
class QueryBuilderMysqliTest extends PHPUnit_Framework_Testcase {
    
    private $qb1;
    private $qb2;
    private $qb3;
    private $qb4;
    
    public function setUp() {
        set_include_path('src');
        require_once 'system/database/querybuilder.php';
        require_once 'system/database/drivers/mysqli/querybuilder.php';
        
        $this->qb1 = new \System\Database\Drivers\Mysqli\QueryBuilder(null);
        $this->qb2 = new \System\Database\Drivers\Mysqli\QueryBuilder(null);
        $this->qb3 = new \System\Database\Drivers\Mysqli\QueryBuilder(null);
        $this->qb4 = new \System\Database\Drivers\Mysqli\QueryBuilder(null);
    }
    
    public function testSelectFrom() {
        $select1 = '*';
        $select2 = 'test';
        $select3 = array('col1', 'col2' => 'col3', 'col4');
        
        $from1 = 'table';
        $from2 = array('table1', 'table2');
        
        $qb1 = "SELECT * FROM `table`";
        $qb2 = "SELECT `test` FROM `table1`, `table2`";
        $qb3 = "SELECT `col1`, `col2` AS `col3`, `col4` FROM `table1`, `table2`";
        
        $this->qb1->select($select1)->from($from1);
        $this->qb2->select($select2)->from($from2);
        $this->qb3->select($select3)->from($from2);
        
        $this->assertEquals($qb1, trim($this->qb1->getQuery()), "Test simple SELECT FROM");
        $this->assertEquals($qb2, trim($this->qb2->getQuery()), "Test medium SELECT with advanced FROM");
        $this->assertEquals($qb3, trim($this->qb3->getQuery()), "Test advanced SELECT with advanced FROM");
    }
    
    public function testOrderBy() {
        // At this point, the query is: SELECT * FROM `table`
        $this->qb1->select('*')->from('table');
        $this->qb2->select('*')->from('table');
        $this->qb3->select('*')->from('table');
        
        $orderby1 = 'x';
        $orderby2 = array('x' => 'ASC');
        $orderby3 = array('x' => 'ASC', 'y' => 'DESC', 'z');
        
        $this->qb1->orderBy($orderby1);
        $this->qb2->orderBy($orderby2);
        $this->qb3->orderBy($orderby3);
        
        $qb1 = "SELECT * FROM `table` ORDER BY `x` ASC";
        $qb2 = "SELECT * FROM `table` ORDER BY `x` ASC";
        $qb3 = "SELECT * FROM `table` ORDER BY `x` ASC, `y` DESC, `z` ASC";
        
        $this->assertEquals($qb1, trim($this->qb1->getQuery()), "Test simple ORDER BY");
        $this->assertEquals($qb2, trim($this->qb2->getQuery()), "Test ORDER BY with ASC / DESC");
        $this->assertEquals($qb3, trim($this->qb3->getQuery()), "Test ORDER BY multipart");
    }
    
    public function testLimit() {
        // At this point, the query is: SELECT * FROM `table`
        $this->qb1->select('*')->from('table');
        $this->qb2->select('*')->from('table');
        
        $limit1 = 2;
        $limit2 = array(2, 3);
        
        $this->qb1->limit($limit1);
        $this->qb2->limit($limit2);
        
        $qb1 = "SELECT * FROM `table` LIMIT 2";
        $qb2 = "SELECT * FROM `table` LIMIT 2,3";
        
        $this->assertEquals($qb1, trim($this->qb1->getQuery()), "Test singular LIMIT");
        $this->assertEquals($qb2, trim($this->qb2->getQuery()), "Test double LIMIT");
    }
    
    public function testWhere() {
        // At this point, the query is: SELECT * FROM `table`
        $this->qb1->select('*')->from('table')->where("`x`='y'");
        $this->qb2->select('*')->from('table')->where(array('x', 'y'));
        $this->qb3->select('*')->from('table')->where(array(array('a', 'x'), array('b', 'y')));
        
        $qb1 = "SELECT * FROM `table` WHERE `x`='y'";
        $qb2 = "SELECT * FROM `table` WHERE `x` = 'y'";
        $qb3 = "SELECT * FROM `table` WHERE `a` = 'x' AND `b` = 'y'";
        
        $this->assertEquals($qb1, trim($this->qb1->getQuery()), "Test string WHERE");
        $this->assertEquals($qb2, trim($this->qb2->getQuery()), "Test array WHERE");
        $this->assertEquals($qb3, trim($this->qb3->getQuery()), "Test multi dimensional array WHERE");
    }
    
    public function testUpdate() {
        $this->qb1->update('table', array('a'=>'x', 'b'=>'y', 'c'=>'z'));
        $this->qb2->update('table', array('a'=>'x', 'b'=>'y', 'c'=>'z'), false);
        $this->qb3->update('table', array('a'=>'x', 'b'=>'y', 'c'=>'z'))->where(array('x', 'y'));
        
        $qb1 = "UPDATE `table` SET `a` = 'x', `b` = 'y', `c` = 'z'";
        $qb2 = "UPDATE `table` SET `a` = x, `b` = y, `c` = z";
        $qb3 = "UPDATE `table` SET `a` = 'x', `b` = 'y', `c` = 'z' WHERE `x` = 'y'";
        
        $this->assertEquals($qb1, trim($this->qb1->getQuery()), "Test UPDATE with quotes");
        $this->assertEquals($qb2, trim($this->qb2->getQuery()), "Test UPDATE without quotes");
        $this->assertEquals($qb3, trim($this->qb3->getQuery()), "Test UPDATE with WHERE clause");
    }
    
    public function testInsert() {
        $this->qb1->insert('table', array('x', 'y'));
        $qb1 = "INSERT INTO `table` VALUES ('x', 'y')";
        $this->qb2->insert('table', array('x', 'y'), array('a', 'b'));
        $qb2 = "INSERT INTO `table` (`a`, `b`) VALUES ('x', 'y')";
        $this->qb3->insert('table', array(array('x1', 'y1'), array('x2', 'y2')), array('a', 'b'));
        $qb3 = "INSERT INTO `table` (`a`, `b`) VALUES ('x1', 'y1'), ('x2', 'y2')";
        
        $this->assertEquals($qb1, trim($this->qb1->getQuery()), "Test INSERT without columns");
        $this->assertEquals($qb2, trim($this->qb2->getQuery()), "Test INSERT with columns");
        $this->assertEquals($qb3, trim($this->qb3->getQuery()), "Test INSERT with multi-values and columns");
    }
    
    public function testDelete() {
        $this->qb1->delete('table');
        $qb1 = "DELETE FROM `table`";
        $this->qb2->delete('table')->where(array('x', 'y'));
        $qb2 = "DELETE FROM `table` WHERE `x` = 'y'";
        
        $this->assertEquals($qb1, trim($this->qb1->getQuery()), "Test DELETE FROM");
        $this->assertEquals($qb2, trim($this->qb2->getQuery()), "Test DELETE FROM with WHERE");
    }
    
    public function testComplex() {
    	$this->qb1->select('*')->from('y')->where(array('a', 'b'))->orderBy(array('c'))->limit(3);
    	$qb1 = "SELECT * FROM `y` WHERE `a` = 'b' ORDER BY `c` ASC LIMIT 3";
    	
    	$this->assertEquals($qb1, trim($this->qb1->getQuery()), "Testing complex query");
    }
    
}