<?php 
require_once('include/MVC/View/views/view.noaccess.php');

class ViewNoAccessTest extends Sugar_PHPUnit_Framework_TestCase
{   
    public function testConstructor() 
	{
	    $view = new ViewNoaccess;
        
        $this->assertEquals('noaccess',$view->type);
	}
	
	public function testDisplay()
	{
	    $view = new ViewNoaccess;
	    
        ob_start();
        $view->display();
        $output = ob_get_contents();
        ob_end_clean();
        
        $this->assertEquals(
            '<p class="error">Warning: You do not have permission to access this module.</p>',
            $output
            );
	}
}