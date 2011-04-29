<?php 
require_once('include/MVC/View/views/view.vcard.php');

class ViewVcardTest extends Sugar_PHPUnit_Framework_TestCase
{   
    public function setUp()
    {
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
        $beanList = array();
        $beanFiles = array();
        require('include/modules.php');
        $GLOBALS['beanList'] = $beanList;
        $GLOBALS['beanFiles'] = $beanFiles;
    }
    
    public function tearDown()
    {
        unset($GLOBALS['current_user']);
        unset($GLOBALS['beanList']);
        unset($GLOBALS['beanFiles']);
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
    }
    
    public function testConstructor() 
	{
	    $view = new ViewVcard;
        
        $this->assertEquals('detail',$view->type);
	}
	
	public function testDisplay()
	{
	    $view = new ViewVcard;
	    $view->bean = SugarTestContactUtilities::createContact();
	    $view->module = 'Contacts';
	    
        ob_start();
        $view->display();
        $output = ob_get_contents();
        ob_end_clean();
        
        SugarTestContactUtilities::removeAllCreatedContacts();
        
        $this->assertContains(
            'BEGIN:VCARD',
            $output
            );
	}
}