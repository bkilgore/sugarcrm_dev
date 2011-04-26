<?php
require_once 'include/database/DBManagerFactory.php';

/**
 * @group bug33049
 */
class FreeTDSManagerTest extends Sugar_PHPUnit_Framework_TestCase
{
    private $_db;
    
    public function setUp()
    {
        $this->_db = DBManagerFactory::getInstance();
        if(get_class($this->_db) != 'FreeTDSManager') {
           $this->markTestSkipped("Skipping test if not mssql configuration");
    }
    }
    
    public function testAppendnAddsNCorrectly()
    {
       $sql = $this->_db->appendN('SELECT name FROM accounts where name = \'Test\'');
       $this->assertEquals($sql, 'SELECT name FROM accounts where name = N\'Test\'', 'Assert N was added.');
        
	   $sql = $this->_db->appendN('SELECT name FROM accounts where name = \'O\\\'Rielly\'');
       $this->assertEquals($sql, 'SELECT name FROM accounts where name = N\'O\\\'Rielly\'', 'Assert N was added.');    
    }
    
}
