<?php 
require_once('include/SugarFields/Fields/Relate/SugarFieldRelate.php');

class SugarFieldEnumTest extends Sugar_PHPUnit_Framework_TestCase
{
	public function setUp()
    {
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
	}

    public function tearDown()
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
    }
    
     /**
     * @group bug36744
     */
	public function testFormatEnumField()
	{
		$GLOBALS['app_list_strings'] = return_app_list_strings_language($GLOBALS['current_language']);
		$fieldDef = array (
					    'name' => 'priority',
					    'vname' => 'LBL_PRIORITY',
					    'type' => 'enum',
					    'options' => 'case_priority_dom',
					    'len'=>25,
					    'audited'=>true,
					    'comment' => 'The priority of the case',
					);
		$field_value = "P2";
		
        require_once('include/SugarFields/SugarFieldHandler.php');
   		$sfr = SugarFieldHandler::getSugarField('enum');
    	
   	 	$this->assertEquals(trim($sfr->formatField($field_value,$fieldDef)),'Medium');
    }
}