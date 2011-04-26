<?php

class TrackerManagerTest extends Sugar_PHPUnit_Framework_TestCase {

	function setUp() {
		$user = new User();
		$user->retrieve('1');
		$GLOBALS['current_user'] = $user;
	}
	
    function tearDown()
    {
    	$trackerManager = TrackerManager::getInstance();
    	$trackerManager->unPause();
    	
		$user = new User();
		$user->retrieve('1');
		$GLOBALS['current_user'] = $user;    	
    }
    
    function testPausing() {
        $trackerManager = TrackerManager::getInstance();
        $trackerManager->unPause();
        $this->assertFalse($trackerManager->isPaused());
        $trackerManager->pause();
        $this->assertTrue($trackerManager->isPaused());
    }
    
    function testPausing2() {
        $query = "select count(id) as total from tracker";
    	$result = $GLOBALS['db']->query($query);
    	$count1 = 0;
		while($row = $GLOBALS['db']->fetchByAssoc($result)){
		      $count1 = $row['total'];
		}

		$trackerManager = TrackerManager::getInstance();
		$trackerManager->pause();
		
        $monitor = $trackerManager->getMonitor('tracker');         
        $monitor->setValue('module_name', 'Contacts');
        $monitor->setValue('item_id', '10909d69-2b55-094d-ba89-47b23d3121dd');
        $monitor->setValue('item_summary', 'Foo');
        $monitor->setValue('date_modified', gmdate($GLOBALS['timedate']->get_db_date_time_format()), strtotime("-1 day")+5000);
        $monitor->setValue('action', 'index');
        $monitor->setValue('session_id', 'test_session');
        $monitor->setValue('user_id', 1);
        $trackerManager->save();
        
        $count2 = 0;
        $query = "select count(id) as total from tracker";
    	$result = $GLOBALS['db']->query($query);        
    	while($row = $GLOBALS['db']->fetchByAssoc($result)){
		      $count2 = $row['total'];
		}
		$this->assertEquals($count1, $count2);		
    }
    

}  
?>