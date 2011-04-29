<?php

class Bug41845Test extends Sugar_PHPUnit_Framework_TestCase {
      
    public function testEnableDecimalFieldForRangeSearch()
    {
    	require_once('modules/DynamicFields/templates/Fields/TemplateRange.php');
    	require_once('modules/DynamicFields/templates/Fields/TemplateFloat.php');
    	require_once('modules/DynamicFields/templates/Fields/TemplateDecimal.php');
    	$decimal = new TemplateDecimal();
    	$this->assertTrue(isset($decimal->vardef_map['enable_range_search']), 'Assert that enable_range_search is in the vardef_map Array'); 	
    } 
   
}
?>