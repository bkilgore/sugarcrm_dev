<?php
/**
 * Bug40019_Test.php
 * This test verifies the fixes to properly store the items in the BreadCrumbStack class
 * 
 */

require_once 'SugarTestUserUtilities.php';
require_once 'SugarTestAccountUtilities.php';
require_once 'SugarTestContactUtilities.php';
require_once 'modules/Trackers/TrackerManager.php';

class Bug40019_Test extends Sugar_PHPUnit_Framework_TestCase 
{
    private $anonymous_user;
    private $saved_current_user;
	
    public function setUp()
    {
    	$this->anonymous_user = SugarTestUserUtilities::createAnonymousUser();
    	if(!empty($GLOBALS['current_user']))
    	{
    		$this->saved_current_user = $GLOBALS['current_user'];
    	}
    	$GLOBALS['current_user'] = $this->anonymous_user;
    	
    	$i = 0;
		while($i++ < 10)
		{
			$account = SugarTestAccountUtilities::createAccount();
			$contact = SugarTestContactUtilities::createContact();
			
		    $trackerManager = TrackerManager::getInstance();
		    $trackerManager->unPause();
	        if($monitor = $trackerManager->getMonitor('tracker')) {
	        	$monitor->setEnabled(true);
	        	
	            $monitor->setValue('date_modified', gmdate($GLOBALS['timedate']->get_db_date_time_format()));
	            $monitor->setValue('user_id', $GLOBALS['current_user']->id);
	            $monitor->setValue('module_name', $account->module_dir);
	            $monitor->setValue('action', 'detailview');
	            $monitor->setValue('item_id', $account->id);
	            $monitor->setValue('item_summary', $account->name);
	            $monitor->setValue('visible',1);
	            $trackerManager->saveMonitor($monitor, true, true);
	            
	            $monitor = $trackerManager->getMonitor('tracker');
	            $monitor->setValue('date_modified', gmdate($GLOBALS['timedate']->get_db_date_time_format()));
	            $monitor->setValue('user_id', $GLOBALS['current_user']->id);
	            $monitor->setValue('module_name', $contact->module_dir);
	            $monitor->setValue('action', 'detailview');
	            $monitor->setValue('item_id', $contact->id);
	            $monitor->setValue('item_summary', $contact->name);
	            $monitor->setValue('visible',1);
	            $trackerManager->saveMonitor($monitor, true, true);	            
	        }	
		}
    } 

    public function tearDown()
    {
    	$GLOBALS['db']->query("DELETE FROM tracker WHERE user_id = '{$this->anonymous_user->id}'");
    	SugarTestAccountUtilities::removeAllCreatedAccounts();
    	SugarTestContactUtilities::removeAllCreatedContacts();
    	SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
    	if(!empty($this->saved_current_user))
    	{
    	   $GLOBALS['current_user'] = $this->saved_current_user;
    	}
    	
    	
    }
    
    public function testBreadCrumbStack()
    {
    	$GLOBALS['sugar_config']['history_max_viewed'] = 50;
    	$breadCrumbStack = new BreadCrumbStack($GLOBALS['current_user']->id);
    	$list = $breadCrumbStack->getBreadCrumbList('Accounts');
    	$this->assertEquals(count($list), 10, 'Assert that there are 10 entries for Accounts module');

    	$list = $breadCrumbStack->getBreadCrumbList('Contacts');
    	$this->assertEquals(count($list), 10, 'Assert that there are 10 entries for Contacts module');    	
    	
    	/*
    	$GLOBALS['sugar_config']['history_max_viewed'] = 10;
    	$breadCrumbStack = new BreadCrumbStack($GLOBALS['current_user']->id);
    	$list = $breadCrumbStack->getBreadCrumbList(array('Accounts', 'Contacts'));
    	$contacts = 0;
    	$accounts = 0;
    	foreach($list as $list_entry)
    	{
    		switch ($list_entry['module_name'])
    		{
    			case 'Contacts': 
    				 $contacts++;
    			     break;
    			case 'Accounts': 
    				 $accounts++;
    			     break;
    		}
    	}
    	
    	$this->assertEquals($contacts, 5, 'Assert there are 5 entries found for Contacts using array filter of Contacts & Accounts');
    	$this->assertEquals($accounts, 5, 'Assert there are 5 entries found for Accounts using array filter of Contacts & Accounts');
        */
    }
    
}

?>