<?php 
require_once('include/SugarFields/Fields/Relate/SugarFieldRelate.php');

class Bug7825Test extends Sugar_PHPUnit_Framework_TestCase
{
	public function testFormatFieldInSugarFieldRelate()
	{
		global $current_user;   
    	$current_user = new User();
        $current_user->retrieve('1');
		
		$current_user->setPreference('default_locale_name_format', 'l f s');
	    $sugar_field_relate = new SugarFieldRelate('Relate');
		$new_field = $sugar_field_relate->formatField('Max Liang', array('name' => 'contact_name'));
		
		$this->assertEquals(trim($new_field), trim('Liang Max'), "Assert that name format is correct");
		
		$current_user->setPreference('default_locale_name_format', 'f l s');
	    $sugar_field_relate = new SugarFieldRelate('Relate');
		$new_field = $sugar_field_relate->formatField('Max Liang', array('name' => 'contact_name'));
		
		$this->assertEquals(trim($new_field), trim('Max Liang'), "Assert that name format is correct");
    }
}