<?php

require_once("modules/ModuleBuilder/parsers/views/AbstractMetaDataParser.php");

class AbstractMetaDataParserTest extends Sugar_PHPUnit_Framework_TestCase
{
	
	public function setUp() 
    {                       
	
    }
    
    public function tearDown() 
    {
       
    }
    
    public function testValidField()
    {
    	$validDef = array (
		    'name' => 'status',
		    'vname' => 'LBL_STATUS',
		    'type' => 'enum',
		    'len' => '25',
		    'options' => 'meeting_status_dom',
		    'comment' => 'Meeting status (ex: Planned, Held, Not held)'
		);
		
		$invalidDef = array (
		    'name' => 'direction',
		    'vname' => 'LBL_DIRECTION',
		    'type' => 'enum',
		    'len' => '25',
		    'options' => 'call_direction_dom',
		    'comment' => 'Indicates whether call is inbound or outbound',
		    'source' => 'non-db',
		    'importable' => 'false',
		    'massupdate'=>false,
		    'reportable'=>false
		);
		
		$this->assertTrue(AbstractMetaDataParser::validField($validDef));
		$this->assertFalse(AbstractMetaDataParser::validField($invalidDef));
		
		//Test the studio override property
		$invalidDef['studio'] = 'visible';
		$validDef['studio'] = false;
		
		$this->assertFalse(AbstractMetaDataParser::validField($validDef));
        $this->assertTrue(AbstractMetaDataParser::validField($invalidDef));
		
		$invalidDef['studio'] = array('editview' => 'visible');
        
        $this->assertTrue(AbstractMetaDataParser::validField($invalidDef, 'editview'));
		$this->assertFalse(AbstractMetaDataParser::validField($invalidDef, 'detailview'));
    }
    
}