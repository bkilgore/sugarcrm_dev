<?php

class Bug33284_Test extends Sugar_PHPUnit_Framework_TestCase
{
    var $max_display_set = false;
    var $max_display_length;
    
    public function setUp() {
    	      
    	if(isset($sugar_config['tracker_max_display_length'])) {
    	   $this->max_display_set = true;
    	   $this->max_display_length = $sugar_config['tracker_max_display_length'];
    	}
    }
    
    public function tearDown() {
        if($this->max_display_set) {
           global $sugar_config; 
           $sugar_config['tracker_max_display_length'] = $this->max_display_length;
        }
    }

    public function test_get_tracker_substring1()
    {
    	global $sugar_config;       
        $sugar_config['tracker_max_display_length'] = 20;
        $test_string = ' Hello There How Are You? ';
        $display_string = getTrackerSubstring($test_string);
        $this->assertEquals(strlen($display_string), 20, 'Assert that the string length is equal to 20 characters');
    }
    
    /*
    public function test_get_tracker_substring2()
    {
    	global $sugar_config;       
        unset($sugar_config['tracker_max_display_length']);
        $test_string = ' Hello There How Are You? ';
        
        $default_length = 15;
                
        
        $display_string = getTrackerSubstring($test_string);
        $this->assertEquals(strlen($display_string), $default_length, 'Assert that the string length is equal to 15 characters (default)');

		$test_string = '科學家發現史上最大恐龍腳印科學家發現史上最大恐龍腳印';
        $display_string = getTrackerSubstring($test_string);
        $this->assertEquals(mb_strlen($display_string), $default_length, 'Assert that the string length is equal to 15 characters (default)');    
    }   
    */
}

?>