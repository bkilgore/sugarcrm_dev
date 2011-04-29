<?php


require_once 'include/database/DBManagerFactory.php';

class Bug34547Test extends Sugar_PHPUnit_Framework_TestCase 
{
	
    private $_has_mysqli_disabled;	
    private $_db;

    public function setUp() 
    {
        $this->_db = DBManagerFactory::getInstance();
        if(get_class($this->_db) != 'MysqlManager' && get_class($this->_db) != 'MysqliManager') {
            $this->markTestSkipped("Skipping test if not mysql or mysqli configuration");
        }
        
        unset($GLOBALS['dbinstances']);

        $this->_has_mysqli_disabled = (!empty($GLOBALS['sugar_config']['mysqli_disabled']) && $GLOBALS['sugar_config']['mysqli_disabled'] === TRUE);
        if(!$this->_has_mysqli_disabled) {
            $GLOBALS['sugar_config']['mysqli_disabled'] = TRUE;
        }
    }

    public function tearDown() 
    {
        if(!$this->_has_mysqli_disabled) {
           unset($GLOBALS['sugar_config']['mysqli_disabled']);
        }
        unset($GLOBALS['dbinstances']);
    }
        
    public function testMysqliDisabledInGetInstance() 
    {
        $this->_db = DBManagerFactory::getInstance();
        $this->assertEquals('MysqlManager', get_class($this->_db), "Assert that MysqliManager is not disabled");
    }

}