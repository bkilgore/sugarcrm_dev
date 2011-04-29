<?php
require_once 'tests/service/SOAPTestCase.php';
/**
 * @ticket 36564
 */
class Bug36564Test extends SOAPTestCase
{
    /**
     * Create test user
     *
     */
	public function setUp()
    {
    	$this->_soapURL = $GLOBALS['sugar_config']['site_url'].'/service/v2/soap.php';
		parent::setUp();
    }

    public function testBadQuery() 
    {
    	$this->_login();
		$result = $this->_soapClient->call('get_entry_list',array('session'=>$this->_sessionId,"module_name" => 'Accounts', "query" => "bad query"));
		if ( isset($result["faultstring"]) )
		    $this->assertContains("Unknown error", $result["faultstring"]);
    } // fn
}
