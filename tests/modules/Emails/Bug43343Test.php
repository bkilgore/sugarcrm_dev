<?php 

/**
 * @ticket 43343
 */
class Bug43343Test extends Sugar_PHPUnit_Framework_TestCase
{
    private $email;
    
	public function setUp()
    {
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
        $this->email = new Email();
	}

    public function tearDown()
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
        unset($_REQUEST['searchDateFrom']);
        unset($_REQUEST['searchDateTo']);
    }
    
    public function testImportSearchDateWhereClause()
    {
        $_REQUEST['searchDateFrom'] = '04/04/2010';
        $_REQUEST['searchDateTo'] = '02/22/2011';
        $whereClause = $this->email->_generateSearchImportWhereClause();
 
        $this->assertTrue( preg_match('/2010-04-04/', $whereClause) == 1 );
        $this->assertTrue( preg_match('/2011-02-22/', $whereClause) == 1 );
    }
    
    public function testEmptyImportSearchDateWhereClause()
    {
        unset($_REQUEST['searchDateFrom']);
        unset($_REQUEST['searchDateTo']);
        $whereClause = $this->email->_generateSearchImportWhereClause();
 
        $this->assertTrue( preg_match('/emails.date_sent/', $whereClause) == 0 );
    }
}
