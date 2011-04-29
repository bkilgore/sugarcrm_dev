<?php

require_once("modules/ModuleBuilder/parsers/views/GridLayoutMetaDataParser.php");
require_once("modules/ModuleBuilder/parsers/views/ListLayoutMetaDataParser.php");
class Bug39161Test extends Sugar_PHPUnit_Framework_TestCase
{
	public function testCallsContactStudioViews()
    {
        $seed = new Call();
		$def = $seed->field_defs['contact_name'];
        $this->assertTrue(ListLayoutMetaDataParser::isValidField($def['name'], $def));
		$this->assertFalse(GridLayoutMetaDataParser::validField($def, 'editview'));
        $this->assertFalse(GridLayoutMetaDataParser::validField($def, 'detailview'));
        $this->assertFalse(GridLayoutMetaDataParser::validField($def, 'quickcreate'));
    }
    
}