<?php

class TrackerSaveTest extends Sugar_PHPUnit_Framework_TestCase {
   
    function testSaveObject() {
        $trackerManager = TrackerManager::getInstance();
	    $monitor = $trackerManager->getMonitor('tracker');
	    $monitor->setEnabled(true);
        // Test to see how it handles saving an Array
        $user = new User();
		$monitor->setValue('module_name', $user);
		$this->assertTrue($monitor->module_name == "User");
    }
    
}  
?>