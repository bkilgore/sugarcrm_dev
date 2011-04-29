<?php

require_once 'modules/DynamicFields/templates/Fields/TemplateInt.php';
require_once 'modules/DynamicFields/templates/Fields/TemplateDate.php';

class TemplateDateTest extends Sugar_PHPUnit_Framework_TestCase
{
    private $hasExistingCustomSearchFields = false;
    
    public function setUp()
    {	
		if(file_exists('custom/modules/Opportunities/metadata/SearchFields.php'))
		{
		   $this->hasExistingCustomSearchFields = true;
		   copy('custom/modules/Opportunities/metadata/SearchFields.php', 'custom/modules/Opportunities/metadata/SearchFields.php.bak');
		   unlink('custom/modules/Opportunities/metadata/SearchFields.php');
		} else if(!file_exists('custom/modules/Opportunities/metadata')) {
		   mkdir_recursive('custom/modules/Opportunities/metadata');
		}
    }
    
    public function tearDown()
    {		

    	if(!$this->hasExistingCustomSearchFields)
		{
		   unlink('custom/modules/Opportunities/metadata/SearchFields.php');
		}    	
    	
		if(file_exists('custom/modules/Opportunities/metadata/SearchFields.php.bak')) {
		   copy('custom/modules/Opportunities/metadata/SearchFields.php.bak', 'custom/modules/Opportunities/metadata/SearchFields.php');
		   unlink('custom/modules/Opportunities/metadata/SearchFields.php.bak');
		}

    }
    
    public function testEnableRangeSearchInt()
    {
		$_REQUEST['view_module'] = 'Opportunities';
		$_REQUEST['name'] = 'probability';
		$templateDate = new TemplateInt();
		$templateDate->enable_range_search = true;
		$templateDate->populateFromPost();
		$this->assertTrue(file_exists('custom/modules/Opportunities/metadata/SearchFields.php'));
		include('custom/modules/Opportunities/metadata/SearchFields.php');
		$this->assertTrue(isset($searchFields['Opportunities']['range_probability']));
		$this->assertTrue(isset($searchFields['Opportunities']['start_range_probability']));
		$this->assertTrue(isset($searchFields['Opportunities']['end_range_probability']));
		$this->assertTrue(!isset($searchFields['Opportunities']['range_probability']['is_date_field']));
		$this->assertTrue(!isset($searchFields['Opportunities']['start_range_probability']['is_date_field']));
		$this->assertTrue(!isset($searchFields['Opportunities']['end_range_probability']['is_date_field']));			
    }
    
    public function testEnableRangeSearchDate()
    {
		$_REQUEST['view_module'] = 'Opportunities';
		$_REQUEST['name'] = 'date_closed';
		$templateDate = new TemplateDate();
		$templateDate->enable_range_search = true;
		$templateDate->populateFromPost();
		$this->assertTrue(file_exists('custom/modules/Opportunities/metadata/SearchFields.php'));
		include('custom/modules/Opportunities/metadata/SearchFields.php');
		$this->assertTrue(isset($searchFields['Opportunities']['range_date_closed']));
		$this->assertTrue(isset($searchFields['Opportunities']['start_range_date_closed']));
		$this->assertTrue(isset($searchFields['Opportunities']['end_range_date_closed']));
		$this->assertTrue(isset($searchFields['Opportunities']['range_date_closed']['is_date_field']));
		$this->assertTrue(isset($searchFields['Opportunities']['start_range_date_closed']['is_date_field']));
		$this->assertTrue(isset($searchFields['Opportunities']['end_range_date_closed']['is_date_field']));		
    }    
    
}
?>