<?php
require_once("modules/Accounts/Account.php");

/**
 * @ticket 24095
 */
class Bug40311Test extends Sugar_PHPUnit_Framework_TestCase
{
    private $_tablename;
    private $_old_installing;

    public function setUp()
    {
        $this->accountMockBean = $this->getMock('Account' , array('hasCustomFields'));
        $this->_tablename = 'test' . date("YmdHis");
        if ( isset($GLOBALS['installing']) )
            $this->_old_installing = $GLOBALS['installing'];
        $GLOBALS['installing'] = true;

        $GLOBALS['db']->createTableParams($this->_tablename . '_cstm',
            array(
                'id_c' => array (
                    'name' => 'id_c',
                    'type' => 'id',
                    ),
                'foo_c' => array (
                    'name' => 'foo_c',
                    'type' => 'datetime',
                    ),
                ),
            array()
            );
        $GLOBALS['db']->query("INSERT INTO {$this->_tablename}_cstm (id_c,foo_c) VALUES ('12345',NULL)");
    }

    public function tearDown()
    {
        $GLOBALS['db']->dropTableName($this->_tablename . '_cstm');
        if ( isset($this->_old_installing) ) {
            $GLOBALS['installing'] = $this->_old_installing;
        }
        else {
            unset($GLOBALS['installing']);
        }
    }

    public function testDynamicFieldsNullWorks()
    {
        $bean = $this->accountMockBean;
        $bean->custom_fields = new DynamicField($bean->module_dir);
        $bean->custom_fields->setup($bean);
        $bean->expects($this->any())
             ->method('hasCustomFields')
             ->will($this->returnValue(true));
        $bean->table_name = $this->_tablename;
        $bean->id = '12345';
        $bean->custom_fields->retrieve();
        $this->assertEquals($bean->id_c, '12345');
        $this->assertEquals($bean->foo_c, NULL);
    }
}
