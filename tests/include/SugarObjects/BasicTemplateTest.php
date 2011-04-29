<?php
require_once 'include/SugarObjects/templates/basic/Basic.php';

class BasicTemplateTest extends Sugar_PHPUnit_Framework_TestCase
{
    private $_bean;
    
    public function setUp()
    {
        $this->_bean = new Basic;
    }
    
    public function tearDown()
    {
        unset($this->_bean);
    }
    
    public function testNameIsReturnedAsSummaryText()
    {
        $this->_bean->name = 'teststring';
        $this->assertEquals($this->_bean->get_summary_text(),$this->_bean->name);
    }
    
    /**
     * @ticket 27361
     */
    public function testSettingImportableFieldDefAttributeTrueAsAString()
    {
        $this->_bean->field_defs['date_entered']['importable'] = 'true';
        $this->assertTrue(array_key_exists('date_entered',$this->_bean->get_importable_fields()),
            'Field date_entered should be importable');
    }
    
    /**
     * @ticket 27361
     */
    public function testSettingImportableFieldDefAttributeTrueAsABoolean()
    {
        $this->_bean->field_defs['date_entered']['importable'] = true;
        $this->assertTrue(array_key_exists('date_entered',$this->_bean->get_importable_fields()),
            'Field date_entered should be importable');
    }
    
    /**
     * @ticket 27361
     */
    public function testSettingImportableFieldDefAttributeFalseAsAString()
    {
        $this->_bean->field_defs['date_entered']['importable'] = 'false';
        $this->assertFalse(array_key_exists('date_entered',$this->_bean->get_importable_fields()),
            'Field date_entered should not be importable');
    }
    
    /**
     * @ticket 27361
     */
    public function testSettingImportableFieldDefAttributeFalseAsABoolean()
    {
        $this->_bean->field_defs['date_entered']['importable'] = false;
        $this->assertFalse(array_key_exists('date_entered',$this->_bean->get_importable_fields()),
            'Field date_entered should not be importable');
    }
    
    public function testGetBeanFieldsAsAnArray()
    {
        $this->_bean->date_entered = '2009-01-01 12:00:00';
        $array = $this->_bean->toArray();
        $this->assertEquals($array['date_entered'],$this->_bean->date_entered);
    }
}
