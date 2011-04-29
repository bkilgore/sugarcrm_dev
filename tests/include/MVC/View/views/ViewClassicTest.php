<?php 
require_once('include/MVC/View/views/view.classic.php');

class ViewClassicTest extends Sugar_PHPUnit_Framework_TestCase
{   
    public function testConstructor() 
	{
        $view = new ViewClassic();
        
        $this->assertEquals('',$view->type);
	}
	
	public function testDisplayWithClassicView()
	{
	    $view = $this->getMock('ViewClassic',array('includeClassicFile'));
	    
	    $view->module = 'testmodule'.mt_rand();
	    $view->action = 'testaction'.mt_rand();
	    
	    sugar_mkdir("modules/{$view->module}",null,true);
	    sugar_touch("modules/{$view->module}/{$view->action}.php");
	    
	    $return = $view->display();
	    
	    rmdir_recursive("modules/{$view->module}");
	    
	    $this->assertTrue($return);
	}
	
	public function testDisplayWithClassicCustomView()
	{
	    $view = $this->getMock('ViewClassic',array('includeClassicFile'));
	    
	    $view->module = 'testmodule'.mt_rand();
	    $view->action = 'testaction'.mt_rand();
	    
	    sugar_mkdir("custom/modules/{$view->module}",null,true);
	    sugar_touch("custom/modules/{$view->module}/{$view->action}.php");
	    
	    $return = $view->display();
	    
	    rmdir_recursive("custom/modules/{$view->module}");
	    
	    $this->assertTrue($return);
	}
	
	public function testDisplayWithNoClassicView()
	{
	    $view = $this->getMock('ViewClassic',array('includeClassicFile'));
	    
	    $view->module = 'testmodule'.mt_rand();
	    $view->action = 'testaction'.mt_rand();
	    
	    $this->assertFalse($view->display());
	}
}