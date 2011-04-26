<?php

class TrackerMonitorTest extends Sugar_PHPUnit_Framework_TestCase {

    function setUp() {
    	$trackerManager = TrackerManager::getInstance();
        $trackerManager->unsetMonitors();
    }    
    
    function tearDown() {

    }
    
    function testValidMonitors() {
        $trackerManager = TrackerManager::getInstance();
        $exceptionThrown = false;
        try {
	        $monitor = $trackerManager->getMonitor('tracker');
	        $monitor2 = $trackerManager->getMonitor('tracker_queries');
	        $monitor3 = $trackerManager->getMonitor('tracker_perf');
	        $monitor4 = $trackerManager->getMonitor('tracker_sessions');
	        $monitor5 = $trackerManager->getMonitor('tracker_tracker_queries');	
        } catch (Exception $ex) {
        	$exceptionThrown = true;
        }
        $this->assertFalse($exceptionThrown);
    }

    function testInvalidMonitors() {
        $trackerManager = TrackerManager::getInstance();
        $exceptionThrown = false;
	    $monitor = $trackerManager->getMonitor('invalid_tracker');
	    $this->assertTrue(get_class($monitor) == 'BlankMonitor');
    }
            
    function testInvalidValue() {        
        $trackerManager = TrackerManager::getInstance();
        $monitor = $trackerManager->getMonitor('tracker');
        $exceptionThrown = false;
        try {
          $monitor->setValue('invalid_column', 'foo');
        } catch (Exception $exception) {
          $exceptionThrown = true;
        }
        $this->assertTrue($exceptionThrown);
    } 
     
}  
?>