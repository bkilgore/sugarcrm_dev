<?php 
require_once('include/MVC/View/views/view.ajax.php');

class ViewAjaxTest extends Sugar_PHPUnit_Framework_TestCase
{   
    public function testConstructor() 
	{
        $view = new ViewAjax();
        
        $this->assertFalse($view->options['show_title']);
        $this->assertFalse($view->options['show_header']);
        $this->assertFalse($view->options['show_footer']);	  
        $this->assertFalse($view->options['show_javascript']);
        $this->assertFalse($view->options['show_subpanels']);
        $this->assertFalse($view->options['show_search']);
	}
}