<?php 
require_once('include/MVC/View/SugarView.php');

class Bug40019Test extends Sugar_PHPUnit_Framework_TestCase
{   
    public function setUp() 
	{
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
        $GLOBALS['app_strings'] = return_application_language($GLOBALS['current_language']);
	    global $sugar_config;
	    $max = $sugar_config['history_max_viewed'];
	    
	    $contacts = array();
	    for($i = 0; $i < $max + 1; $i++){
	        $contacts[$i] = SugarTestContactUtilities::createContact();
	        SugarTestTrackerUtility::insertTrackerEntry($contacts[$i], 'detailview');
	    }
        
	    for($i = 0; $i < $max + 1; $i++){
	        $account[$i] = SugarTestAccountUtilities::createAccount();
            SugarTestTrackerUtility::insertTrackerEntry($account[$i], 'detailview');
	    }
	    
	    $GLOBALS['app_strings'] = return_application_language($GLOBALS['current_language']);
	}
	
	public function tearDown() 
	{

		SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        SugarTestContactUtilities::removeAllCreatedContacts();
        SugarTestAccountUtilities::removeAllCreatedAccounts();
        SugarTestTrackerUtility::removeAllTrackerEntries();

        unset($GLOBALS['current_user']);
        unset($GLOBALS['app_strings']);
	}
	
	// Currently, getBreadCrumbList in BreadCrumbStack.php limits you to 10
	// Also, the Constructor in BreadCrumbStack.php limits it to 10 too.
    /*
     * @group bug40019
     */
	public function testModuleMenuLastViewedForModule()
	{
	    global $sugar_config;
	    $max = $sugar_config['history_max_viewed'];
	    
	    $tracker = new Tracker();
	    $history = $tracker->get_recently_viewed($GLOBALS['current_user']->id, 'Contacts');
	    
	    $expected = $max > 10 ? 10 : $max;
        
        $this->assertTrue(count($history) == $expected);
	}
    
	// Currently, getBreadCrumbList in BreadCrumbStack.php limits you to 10
    /*
     * @group bug40019
     */
	public function testModuleMenuLastViewedForAll()
	{
	    global $sugar_config;
	    $max = $sugar_config['history_max_viewed'];
	    
	    $tracker = new Tracker();
	    $history = $tracker->get_recently_viewed($GLOBALS['current_user']->id, '');
	    
	    $expected = $max > 10 ? 10 : $max;
	    
        $this->assertTrue(count($history) == $expected);
	}
}