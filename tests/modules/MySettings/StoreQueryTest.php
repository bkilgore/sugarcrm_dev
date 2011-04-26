<?php 
require_once('modules/MySettings/StoreQuery.php');

class StoreQueryTest extends Sugar_PHPUnit_Framework_TestCase{
	public function setUp(){
        global $current_user;
		$current_user = SugarTestUserUtilities::createAnonymousUser();
	}

    public function tearDown(){
    	SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
    }
    
	function testGetStoredQuery(){
		$query = StoreQuery::getStoredQueryForUser("Contacts");
	    $this->assertTrue(empty($query), "StoreQuery::getStoredQueryForUser is not empty.");
    }
}
?>