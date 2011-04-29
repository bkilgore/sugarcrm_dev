<?php
require_once('modules/DynamicFields/FieldCases.php');

/**
 * @group DynamicFieldsCurrencyTests
 */

class DynamicFieldsCurrencyTests extends Sugar_PHPUnit_Framework_TestCase
{
    private $_modulename = 'Accounts';
    private $_originaldbType = '';
    private $field;
    
    public function setUp()
    {
        // Set Original Global dbType
        $this->_originaldbType = $GLOBALS['db']->dbType;
        
    	$this->field = get_widget('currency');
        $this->field->id = $this->_modulename.'foofighter_c';
        $this->field->name = 'foofighter_c';
        $this->field->vanme = 'LBL_Foo';
        $this->field->comments = NULL;
        $this->field->help = NULL;
        $this->field->custom_module = $this->_modulename;
        $this->field->type = 'currency';
        $this->field->len = 18;
        $this->field->precision = 6;
        $this->field->required = 0;
        $this->field->default_value = NULL;
        $this->field->date_modified = '2010-12-22 01:01:01';
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
        // Reset Original Global dbType
        $GLOBALS['db']->dbType = $this->_originaldbType;
    }
    
    public function testCurrencyDbType()
    {
        // oci8 - number
        $GLOBALS['db']->dbType = 'oci8';
        $this->field->len = NULL;
        $dbTypeString = $this->field->get_db_type();
        $this->assertRegExp('/number *\(/', $dbTypeString);
        $dbTypeString = $this->field->get_db_type();
        $this->field->len = 20;
        $this->assertRegExp('/number *\(/', $dbTypeString);
        
        // mssql - decimal
        $GLOBALS['db']->dbType = 'mssql';
        $this->field->len = NULL;
        $dbTypeString = $this->field->get_db_type();
        $this->assertRegExp('/decimal *\(/', $dbTypeString);
        $this->field->len = 20;
        $dbTypeString = $this->field->get_db_type();
        $this->assertRegExp('/decimal *\(/', $dbTypeString);
        
        // default - decimal
        $GLOBALS['db']->dbType = 'mssql';
        $this->field->len = NULL;
        $dbTypeString = $this->field->get_db_type();
        $this->assertRegExp('/decimal *\(/', $dbTypeString);
        $this->field->len = 20;
        $dbTypeString = $this->field->get_db_type();
        $this->assertRegExp('/decimal *\(/', $dbTypeString);
    }
}
