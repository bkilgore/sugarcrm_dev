<?php
require_once('modules/DynamicFields/FieldCases.php');

/**
 * Test cases for URL Field
 */
class URLFieldTest extends Sugar_PHPUnit_Framework_TestCase
{
    private $_modulename = 'Accounts';
    
    public function setUp()
    {
        $this->field = get_widget('url');
        $this->field->id = $this->_modulename.'foo_c';
        $this->field->name = 'foo_c';
        $this->field->vanme = 'LBL_Foo';
        $this->field->comments = NULL;
        $this->field->help = NULL;
        $this->field->custom_module = $this->_modulename;
        $this->field->type = 'url';
        $this->field->len = 255;
        $this->field->required = 0;
        $this->field->default_value = NULL;
        $this->field->date_modified = '2009-09-14 02:23:23';
        $this->field->deleted = 0;
        $this->field->audited = 0;
        $this->field->massupdate = 0;
        $this->field->duplicate_merge = 0;
        $this->field->reportable = 1;
        $this->field->importable = 'true';
        $this->field->ext1 = NULL;
        $this->field->ext2 = NULL;
        $this->field->ext3 = NULL;
        $this->field->ext4 = NULL;
    }
    
    public function tearDown()
    {
    }
    
    public function testURLFieldsInVardef()
    {
        $this->field->ext4 = '_self';
        $vardef = $this->field->get_field_def();
        $this->assertEquals($vardef['link_target'], '_self');
    }
}
