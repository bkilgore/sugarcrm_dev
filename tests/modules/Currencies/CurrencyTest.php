<?php
require_once('modules/Currencies/Currency.php');

class CurrencyTest extends Sugar_PHPUnit_Framework_TestCase {
	
	var $previousCurrentUser;
	
    public function setUp() 
    {
    	global $current_user;
    	$this->previousCurrentUser = $current_user;
        $this->useOutputBuffering = false;        
        $current_user = SugarTestUserUtilities::createAnonymousUser();
        $current_user->setPreference('num_grp_sep', ',', 0, 'global');
        $current_user->setPreference('dec_sep', '.', 0, 'global');
        $current_user->save();
        //Force reset on dec_sep and num_grp_sep because the dec_sep and num_grp_sep values are stored as static variables
	    get_number_seperators(true);  
    }	

    public function tearDown() 
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        global $current_user;
        $current_user = $this->previousCurrentUser;
    }    
    
    public function testUnformatNumber()
    {
    	global $current_user;
    	$testValue = "$100,000.50";
    	
    	$unformattedValue = unformat_number($testValue);
    	$this->assertEquals($unformattedValue, 100000.50, "Assert that $100,000.50 becomes 100000.50");
    	
    	//Switch the num_grp_sep and dec_sep values
        $current_user->setPreference('num_grp_sep', '.');
        $current_user->setPreference('dec_sep', ',');
        $current_user->save();

        //Force reset on dec_sep and num_grp_sep because the dec_sep and num_grp_sep values are stored as static variables
	    get_number_seperators(true);       
        
        $testValue = "$100.000,50";
        $unformattedValue = unformat_number($testValue);
    	$this->assertEquals($unformattedValue, 100000.50, "Assert that $100.000,50 becomes 100000.50");
    }
    
    
    public function testFormatNumber()
    {
    	global $current_user;
    	$testValue = "100000.50";
    	
    	$formattedValue = format_number($testValue);
    	$this->assertEquals($formattedValue, "100,000.50", "Assert that 100000.50 becomes 100,000.50");
    	
    	//Switch the num_grp_sep and dec_sep values
        $current_user->setPreference('num_grp_sep', '.');
        $current_user->setPreference('dec_sep', ',');
        $current_user->save();

        //Force reset on dec_sep and num_grp_sep because the dec_sep and num_grp_sep values are stored as static variables
	    get_number_seperators(true);       
        
        $testValue = "100000.50";
        $formattedValue = format_number($testValue);
    	$this->assertEquals($formattedValue, "100.000,50", "Assert that 100000.50 becomes 100.000,50");
    }    
    
} 

?>