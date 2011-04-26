<?php

require_once('modules/Trackers/TrackerTestUtility.php');

class TrackerMetricsTest extends Sugar_PHPUnit_Framework_TestCase {

	var $trackerSettings;
	
	function setUp() {
		TrackerTestUtility::setUp(); 		
	}
	
    function tearDown() {
    	TrackerTestUtility::tearDown();
    }
    
    function testMetrics() {
        $trackerManager = TrackerManager::getInstance();
	    $monitor = $trackerManager->getMonitor('tracker');
        $metrics = $monitor->getMetrics();
        foreach($metrics as $metric) {
           if($metric->name() == 'monitor_id') {
           	  $this->assertFalse($metric->isMutable(), "Test that {$metric->name()} is not mutable");
           } else {
           	  $this->assertTrue($metric->isMutable(), "Test that {$metric->name()} is mutable");
           }
        }
    }
    
}  
?>