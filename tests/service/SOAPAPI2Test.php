<?php
require_once('include/nusoap/nusoap.php');
require_once('include/TimeDate.php');
/**
 * This class is meant to test everything SOAP
 *
 */
class SOAPAPI2Test extends Sugar_PHPUnit_Framework_TestCase
{
	public $_user = null;
	public $_soapClient = null;
	public $_session = null;
	public $_sessionId = '';
    public $_contactId = '';
	
    /**
     * Create test user
     *
     */
	public function setUp() 
    {
        $this->_soapClient = new nusoapclient($GLOBALS['sugar_config']['site_url'].'/service/v2/soap.php',false,false,false,false,false,600,600);
        $this->_setupTestUser();
    }

    /**
     * Remove anything that was used during this test
     *
     */
    public function tearDown() {
    	global $soap_version_test_accountId, $soap_version_test_opportunityId, $soap_version_test_contactId;
        $this->_tearDownTestUser();
        $this->_user = null;
        $this->_sessionId = '';
        unset($soap_version_test_accountId);
        unset($soap_version_test_opportunityId);
        unset($soap_version_test_contactId);
    }

	/**
	 * Ensure we can create a session on the server.
	 *
	 */
    public function testCanLogin(){
		$result = $this->_login();
    	$this->assertTrue(!empty($result['id']) && $result['id'] != -1, 
            'SOAP Session not created. Error ('.$this->_soapClient->faultcode.'): '.$this->_soapClient->faultstring.': '.$this->_soapClient->faultdetail);
    }
         
    public function testSetEntryForContact() {
    	global $soap_version_test_contactId;
    	$result = $this->_setEntryForContact();
		$soap_version_test_contactId = $result['id'];
    	$this->assertTrue(!empty($result['id']) && $result['id'] != -1, 
            'Can not create new contact. Error ('.$this->_soapClient->faultcode.'): '.$this->_soapClient->faultstring.': '.$this->_soapClient->faultdetail);
    } // fn

    public function testGetEntryForContact() {
    	$result = $this->_getEntryForContact();
    	if (empty($this->_soapClient->faultcode)) {
    		if (($result['entry_list'][0]['name_value_list'][2]['value'] == 1) &&
    			($result['entry_list'][0]['name_value_list'][3]['value'] == "Cold Call") &&
    			($result['relationship_list'][0][0]['records'][0][1]['value'] == 'contact@sugar.com')) {
    			
    			$this->assertEquals($result['entry_list'][0]['name_value_list'][2]['value'],1,"testGetEntryForContact method - Get Entry For contact is not same as Set Entry");
    		} // else
    	} else {
    		$this->assertTrue(empty($this->_soapClient->faultcode), 'Can not retrieve newly created contact. Error ('.$this->_soapClient->faultcode.'): '.$this->_soapClient->faultstring.': '.$this->_soapClient->faultdetail);
    	}
    } // fn
    
    /**
     * @ticket 38986
     */
    public function testGetEntryForContactNoSelectFields(){
    	global $soap_version_test_contactId;
		$this->_login();
		$result = $this->_soapClient->call('get_entry',array('session'=>$this->_sessionId,'module_name'=>'Contacts','id'=>$soap_version_test_contactId,'select_fields'=>array(), 'link_name_to_fields_array' => array()));
		$this->assertTrue(!empty($result['entry_list'][0]['name_value_list']), "testGetEntryForContactNoSelectFields returned no field data");
    	
    }
        
    public function testSetEntriesForAccount() {
    	$result = $this->_setEntriesForAccount();
    	$this->assertTrue(!empty($result['ids']) && $result['ids'][0] != -1, 
            'Can not create new account using testSetEntriesForAccount. Error ('.$this->_soapClient->faultcode.'): '.$this->_soapClient->faultstring.': '.$this->_soapClient->faultdetail);
    } // fn

    public function testSetEntryForOpportunity() {
    	$result = $this->_setEntryForOpportunity();
    	$this->assertTrue(!empty($result['id']) && $result['id'] != -1, 
            'Can not create new account using testSetEntryForOpportunity. Error ('.$this->_soapClient->faultcode.'): '.$this->_soapClient->faultstring.': '.$this->_soapClient->faultdetail);
    } // fn
    
    public function testSetRelationshipForOpportunity() {
    	$result = $this->_setRelationshipForOpportunity();
    	$this->assertTrue(($result['created'] > 0), 'testSetRelationshipForOpportunity method - Relationship for opportunity to Contact could not be created');
    	
    } // fn
    
    
    public function testGetRelationshipForOpportunity() 
    {    
    	global $soap_version_test_contactId;
    	$result = $this->_getRelationshipForOpportunity();
    	$this->assertEquals(
    	    $result['entry_list'][0]['id'],
    	    $soap_version_test_contactId, 
    	    "testGetRelationshipForOpportunity - Get Relationship of Opportunity to Contact failed"
            );  	
    } // fn
    
    public function testSearchByModule() {
    	$result = $this->_searchByModule();
    	$this->assertTrue(($result['entry_list'][0]['records'] > 0 && $result['entry_list'][1]['records'] && $result['entry_list'][2]['records']), "testSearchByModule - could not retrieve any data by search");  	
    } // fn
    
    /**********************************
     * HELPER PUBLIC FUNCTIONS
     **********************************/
    
    /**
     * Attempt to login to the soap server
     *
     * @return $set_entry_result - this should contain an id and error.  The id corresponds
     * to the session_id.
     */
    public function _login(){
		global $current_user;  	
    	$result = $this->_soapClient->call('login',
            array('user_auth' => 
                array('user_name' => $current_user->user_name,
                    'password' => $current_user->user_hash, 
                    'version' => '.01'), 
                'application_name' => 'SoapTest',
                'name_value_list'=>array())
            );
        $this->_sessionId = $result['id'];
		return $result;
    }
    
    public function _setEntryForContact() {
		$this->_login();
		global $timedate;
		$current_date = $timedate->convert_to_gmt_datetime('now');
        $time = mt_rand();
    	$first_name = 'SugarContactFirst' . $time;
    	$last_name = 'SugarContactLast';
    	$email1 = 'contact@sugar.com';
		$result = $this->_soapClient->call('set_entry',array('session'=>$this->_sessionId,'module_name'=>'Contacts', 'name_value_list'=>array(array('name'=>'last_name' , 'value'=>"$last_name"), array('name'=>'first_name' , 'value'=>"$first_name"), array('name'=>'do_not_call' , 'value'=>"1"), array('name'=>'birthdate' , 'value'=>"$current_date"), array('name'=>'lead_source' , 'value'=>"Cold Call"), array('name'=>'email1' , 'value'=>"$email1"))));
		SugarTestContactUtilities::setCreatedContact(array($this->_contactId));
		return $result;
    } // fn
    
    public function _getEntryForContact() {
    	global $soap_version_test_contactId;
		$this->_login();
		$result = $this->_soapClient->call('get_entry',array('session'=>$this->_sessionId,'module_name'=>'Contacts','id'=>$soap_version_test_contactId,'select_fields'=>array('last_name', 'first_name', 'do_not_call', 'lead_source', 'email1'), 'link_name_to_fields_array' => array(array('name' =>  'email_addresses', 'value' => array('id', 'email_address', 'opt_out', 'primary_address')))));		$GLOBALS['log']->fatal("_getEntryForContact" . " " . $soap_version_test_contactId);
		return $result;
    }
        
    public function _setEntriesForAccount() {
    	global $soap_version_test_accountId;
		$this->_login();
		global $timedate;
		$current_date = $timedate->convert_to_gmt_datetime('now');
        $time = mt_rand();
    	$name = 'SugarAccount' . $time;
        $email1 = 'account@'. $time. 'sugar.com';
		$result = $this->_soapClient->call('set_entries',array('session'=>$this->_sessionId,'module_name'=>'Accounts', 'name_value_lists'=>array(array(array('name'=>'name' , 'value'=>"$name"), array('name'=>'email1' , 'value'=>"$email1")))));
		$soap_version_test_accountId = $result['ids'][0];
		$GLOBALS['log']->fatal("_setEntriesForAccount id = " . $soap_version_test_accountId);
		SugarTestAccountUtilities::setCreatedAccount(array($soap_version_test_accountId));
		return $result;
    } // fn

    public function _setEntryForOpportunity() {
    	global $soap_version_test_accountId, $soap_version_test_opportunityId;
		$this->_login();
		global $timedate;
		$date_closed = $timedate->convert_to_gmt_datetime(strtotime('+1 week'));
        $time = mt_rand();
    	$name = 'SugarOpportunity' . $time;
    	$account_id = $soap_version_test_accountId;
    	$sales_stage = 'Prospecting';
    	$probability = 10;
    	$amount = 1000;
		$GLOBALS['log']->fatal("_setEntryForOpportunity id = " . $soap_version_test_accountId);
		$result = $this->_soapClient->call('set_entry',array('session'=>$this->_sessionId,'module_name'=>'Opportunities', 'name_value_lists'=>array(array('name'=>'name' , 'value'=>"$name"), array('name'=>'amount' , 'value'=>"$amount"), array('name'=>'probability' , 'value'=>"$probability"), array('name'=>'sales_stage' , 'value'=>"$sales_stage"), array('name'=>'account_id' , 'value'=>"$account_id"))));
		$soap_version_test_opportunityId = $result['id'];
		return $result;
    } // fn
    
  public function _getEntryForOpportunity() {
    	global $soap_version_test_opportunityId;
		$this->_login();
		$result = $this->_soapClient->call('get_entry',array('session'=>$this->_sessionId,'module_name'=>'Opportunities','id'=>$soap_version_test_opportunityId,'select_fields'=>array('name', 'amount'), 'link_name_to_fields_array' => array(array('name' =>  'contacts', 'value' => array('id', 'first_name', 'last_name')))));		$GLOBALS['log']->fatal("_getEntryForContact" . " " . $soap_version_test_opportunityId);
		return $result;
    }
    
    public function _setRelationshipForOpportunity() {
    	
    	global $soap_version_test_contactId, $soap_version_test_opportunityId;
		$this->_login();
		$result = $this->_soapClient->call('set_relationship',array('session'=>$this->_sessionId,'module_name' => 'Opportunities','module_id' => "$soap_version_test_opportunityId", 'link_field_name' => 'contacts','related_ids' =>array("$soap_version_test_contactId"), 'name_value_list' => array(array('name' => 'contact_role', 'value' => 'testrole')), 'delete'=>0));
		return $result;    	
    } // fn
    
    public function _getRelationshipForOpportunity() {
    	global $soap_version_test_opportunityId;
		$this->_login();
		$result = $this->_soapClient->call('get_relationships',
				array(
                'session' => $this->_sessionId,
                'module_name' => 'Opportunities',
                'module_id' => "$soap_version_test_opportunityId",
                'link_field_name' => 'contacts',
                'related_module_query' => '',
                'related_fields' => array('id'),
                'related_module_link_name_to_fields_array' => array(array('name' =>  'contacts', 'value' => array('id', 'first_name', 'last_name'))),
            	'deleted'=>0,
				)
			);
		return $result;    	
    } // fn
    
    public function _searchByModule() {
		$this->_login();
		$result = $this->_soapClient->call('search_by_module',
				array(
                'session' => $this->_sessionId,
                'search_string' => 'Sugar',
				'modules' => array('Accounts', 'Contacts', 'Opportunities'),
                'offset' => '0',
                'max_results' => '10')
            );
		return $result;    	
    } // fn
    
    /**
     * Create a test user
     *
     */
	public function _setupTestUser() {
        $this->_user = SugarTestUserUtilities::createAnonymousUser();
        $this->_user->status = 'Active';
        $this->_user->is_admin = 1;
        $this->_user->save();
        $GLOBALS['current_user'] = $this->_user;
    }
        
    /**
     * Remove user created for test
     *
     */
	public function _tearDownTestUser() {
       SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
       unset($GLOBALS['current_user']);
    }
}
?>
