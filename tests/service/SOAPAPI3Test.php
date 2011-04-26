<?php
require_once('include/nusoap/nusoap.php');
require_once('include/TimeDate.php');
require_once('service/v3/SugarWebServiceUtilv3.php');
require_once('tests/service/APIv3Helper.php');

/**
 * This class is meant to test everything SOAP
 *
 */
class SOAPAPI3Test extends Sugar_PHPUnit_Framework_TestCase
{
	public $_user = null;
	public $_soapClient = null;
	public $_session = null;
	public $_sessionId = '';
    public $_contactId = '';
    private static $helperObject;
	
    /**
     * Create test user
     *
     */
	public function setUp() 
    {
        $this->_soapClient = new nusoapclient($GLOBALS['sugar_config']['site_url'].'/service/v3/soap.php',false,false,false,false,false,600,600);
        $this->_setupTestUser();
        $GLOBALS['app_strings'] = return_application_language($GLOBALS['current_language']);
        $GLOBALS['app_list_strings'] = return_app_list_strings_language($GLOBALS['current_language']);
        
        self::$helperObject = new APIv3Helper();
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

    public function testSearchByModule()
    {
        $this->_login();

        $seedData = self::$helperObject->populateSeedDataForSearchTest($this->_user->id);
        
        $searchModules = array('Accounts','Contacts','Opportunities');
        $searchString = "UNIT TEST";
        $offSet = 0;
        $maxResults = 10;

        $results = $this->_soapClient->call('search_by_module',
                        array(
                            'session' => $this->_sessionId,
                            'search'  => $searchString,
                            'modules' => $searchModules,
                            'offset'  => $offSet,
                            'max'     => $maxResults,
                            'user'    => $this->_user->id)
                        );
            
        $this->assertTrue( self::$helperObject->findBeanIdFromEntryList($results['entry_list'],$seedData[0]['id'],'Accounts') );  
        $this->assertFalse( self::$helperObject->findBeanIdFromEntryList($results['entry_list'],$seedData[1]['id'],'Accounts') ); 
        $this->assertTrue( self::$helperObject->findBeanIdFromEntryList($results['entry_list'],$seedData[2]['id'],'Contacts') ); 
        $this->assertTrue( self::$helperObject->findBeanIdFromEntryList($results['entry_list'],$seedData[3]['id'],'Opportunities') ); 
        $this->assertFalse( self::$helperObject->findBeanIdFromEntryList($results['entry_list'],$seedData[4]['id'],'Opportunities') );  
        $GLOBALS['db']->query("DELETE FROM accounts WHERE name like 'UNIT TEST%' ");
        $GLOBALS['db']->query("DELETE FROM opportunities WHERE name like 'UNIT TEST%' ");
        $GLOBALS['db']->query("DELETE FROM contacts WHERE first_name like 'UNIT TEST%' ");
    }
    
    public function testSearchByModuleWithReturnFields()
    {
        $this->_login();

        $seedData = self::$helperObject->populateSeedDataForSearchTest($this->_user->id);
        
        $returnFields = array('name','id','deleted');
        $searchModules = array('Accounts','Contacts','Opportunities');
        $searchString = "UNIT TEST";
        $offSet = 0;
        $maxResults = 10;

        $results = $this->_soapClient->call('search_by_module',
                        array(
                            'session' => $this->_sessionId,
                            'search'  => $searchString,
                            'modules' => $searchModules,
                            'offset'  => $offSet,
                            'max'     => $maxResults,
                            'user'    => $this->_user->id,
                            'fields'  => $returnFields)
                        );
            
        $this->assertEquals($seedData[0]['fieldValue'], self::$helperObject->findFieldByNameFromEntryList($results['entry_list'],$seedData[0]['id'],'Accounts', $seedData[0]['fieldName']));
        $this->assertFalse(self::$helperObject->findFieldByNameFromEntryList($results['entry_list'],$seedData[1]['id'],'Accounts', $seedData[1]['fieldName']));
        $this->assertEquals($seedData[2]['fieldValue'], self::$helperObject->findFieldByNameFromEntryList($results['entry_list'],$seedData[2]['id'],'Contacts', $seedData[2]['fieldName']));
        $this->assertEquals($seedData[3]['fieldValue'], self::$helperObject->findFieldByNameFromEntryList($results['entry_list'],$seedData[3]['id'],'Opportunities', $seedData[3]['fieldName']));
        $this->assertFalse(self::$helperObject->findFieldByNameFromEntryList($results['entry_list'],$seedData[4]['id'],'Opportunities', $seedData[4]['fieldName']));
        
        $GLOBALS['db']->query("DELETE FROM accounts WHERE name like 'UNIT TEST%' ");
        $GLOBALS['db']->query("DELETE FROM opportunities WHERE name like 'UNIT TEST%' ");
        $GLOBALS['db']->query("DELETE FROM contacts WHERE first_name like 'UNIT TEST%' ");
    }
    
    public function testGetVardefsMD5()
    {   
        $GLOBALS['reload_vardefs'] = TRUE;
        //Test a regular module
        $result = $this->_getVardefsMD5('Accounts');
        $a = new Account();
        $soapHelper = new SugarWebServiceUtilv3();
        $actualVardef = $soapHelper->get_return_module_fields($a,'Accounts','');
        $actualMD5 = md5(serialize($actualVardef));
        $this->assertEquals($actualMD5, $result, "Unable to retrieve vardef md5.");
        
        //Test a fake module
        $result = $this->_getVardefsMD5('BadModule');
        $this->assertTrue($result['faultstring'] == 'Module Does Not Exist');
        unset($GLOBALS['reload_vardefs']);
    }     

    public function testGetUpcomingActivities()
    {
         $this->_login();
         $expected = $this->_createUpcomingActivities(); //Seed the data.
         $results = $this->_soapClient->call('get_upcoming_activities',array('session'=>$this->_sessionId));
         
         $this->assertEquals($expected[0] ,$results[0]['id'] , 'Unable to get upcoming activities Error ('.$this->_soapClient->faultcode.'): '.$this->_soapClient->faultstring.': '.$this->_soapClient->faultdetail);
         $this->assertEquals($expected[1] ,$results[1]['id'] , 'Unable to get upcoming activities Error ('.$this->_soapClient->faultcode.'): '.$this->_soapClient->faultstring.': '.$this->_soapClient->faultdetail);
         
         $this->_removeUpcomingActivities();
    }

    public function testGetLastViewed()
    {
         $testModule = 'Accounts';
         $testModuleID = uniqid();

         $this->_createTrackerEntry($testModule,$testModuleID);

         $this->_login();
		 $results = $this->_soapClient->call('get_last_viewed',array('session'=>$this->_sessionId,'module_names'=> array($testModule) ));
         
		 $found = FALSE;
         foreach ($results as $entry)
         {
             if($entry['item_id'] == $testModuleID)
             {
                 $found = TRUE;
                 break;
             }
         }

         $this->assertTrue($found, "Unable to get last viewed modules");
     }

     private function _createTrackerEntry($module, $id,$summaryText = "UNIT TEST SUMMARY")
     {
        $trackerManager = TrackerManager::getInstance();
        $timeStamp = gmdate($GLOBALS['timedate']->get_db_date_time_format());
        $monitor = $trackerManager->getMonitor('tracker');
        $monitor->setValue('action', 'detail');
        $monitor->setValue('user_id', $this->_user->id);
        $monitor->setValue('module_name', $module);
        $monitor->setValue('date_modified', $timeStamp);
        $monitor->setValue('visible', true);
        $monitor->setValue('item_id', $id);
        $monitor->setValue('item_summary', $summaryText);
        $trackerManager->saveMonitor($monitor, true, true);
     }
    
   
    /**
     * Get Module Layout functions not exposed to soap service, make sure they are not available.
     *
     */
    public function testGetModuleLayoutMD5()
    {
        $result = $this->_getModuleLayoutMD5();
        $this->assertContains('Client',$result['faultcode']);
        
    }
    
    public function testSetEntriesForAccount() {
    	$result = $this->_setEntriesForAccount();
    	$this->assertTrue(!empty($result['ids']) && $result['ids'][0] != -1, 
            'Can not create new account using testSetEntriesForAccount. Error ('.$this->_soapClient->faultcode.'): '.$this->_soapClient->faultstring.': '.$this->_soapClient->faultdetail);
    } // fn
    
    /**********************************
     * HELPER PUBLIC FUNCTIONS
     **********************************/
    private function _removeUpcomingActivities()
    {
        $GLOBALS['db']->query("DELETE FROM calls where name = 'UNIT TEST'");
        $GLOBALS['db']->query("DELETE FROM tasks where name = 'UNIT TEST'");
    }

    private function _createUpcomingActivities()
    {
        $GLOBALS['current_user']->setPreference('datef','Y-m-d') ;
        $GLOBALS['current_user']->setPreference('timef','H:i') ;

        $date1 = $GLOBALS['timedate']->to_display_date_time(gmdate("Y-m-d H:i:s", (gmmktime() + (3600 * 24 * 2) ) ),true,true, $GLOBALS['current_user']) ; //Two days from today
        $date2 = $GLOBALS['timedate']->to_display_date_time(gmdate("Y-m-d H:i:s", (gmmktime() + (3600 * 24 * 4) ) ),true,true, $GLOBALS['current_user']) ; //Two days from today

        $callID = uniqid();
        $c = new Call();
        $c->id = $callID;
        $c->new_with_id = TRUE;
        $c->status = 'Not Planned';
        $c->date_start = $date1;
        $c->name = "UNIT TEST";
        $c->assigned_user_id = $this->_user->id;
        $c->save(FALSE);
        
        $callID = uniqid();
        $c = new Call();
        $c->id = $callID;
        $c->new_with_id = TRUE;
        $c->status = 'Planned';
        $c->date_start = $date1;
        $c->name = "UNIT TEST";
        $c->assigned_user_id = $this->_user->id;
        $c->save(FALSE);

        $taskID = uniqid();
        $t = new Task();
        $t->id = $taskID;
        $t->new_with_id = TRUE;
        $t->status = 'Not Started';
        $t->date_due = $date2;
        $t->name = "UNIT TEST";
        $t->assigned_user_id = $this->_user->id;
        $t->save(FALSE);

        return array($callID, $taskID);
    }
     
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
                'application_name' => 'SoapTest')
            );
           
        $this->_sessionId = $result['id'];
		return $result;
    }
    
    public function _getVardefsMD5($module)
    {
        $this->_login();
		$result = $this->_soapClient->call('get_module_fields_md5',array('session'=>$this->_sessionId,'module'=> array($module) ));
		if(isset($result[0]))
		  return $result[0]; 
		else 
		  return $result;
    }
    
    public function _getModuleLayoutMD5()
    {
        $this->_login();
		$result = $this->_soapClient->call('get_module_layout_md5',
		              array('session'=>$this->_sessionId,'module_names'=> array('Accounts'),'types' => array('default'),'views' => array('list')));
		if(isset($result['md5']))
		  return $result['md5']; 
		else 
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
		$result = $this->_soapClient->call('get_entry',array('session'=>$this->_sessionId,'module_name'=>'Contacts','id'=>$soap_version_test_contactId,'select_fields'=>array('last_name', 'first_name', 'do_not_call', 'lead_source'), 'link_name_to_fields_array' => array(array('name' =>  'email_addresses', 'value' => array('id', 'email_address', 'opt_out', 'primary_address')))));
		$GLOBALS['log']->fatal("_getEntryForContact" . " " . $soap_version_test_contactId);
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
    
    public function _setRelationshipForOpportunity() {
    	global $soap_version_test_contactId, $soap_version_test_opportunityId;
		$this->_login();
		$result = $this->_soapClient->call('set_relationship',array('session'=>$this->_sessionId,'module_name' => 'Opportunities','module_id' => "$soap_version_test_opportunityId", 'link_field_name' => 'contacts','related_ids' =>array("$soap_version_test_contactId"), 'name_value_list' => array(array('name' => 'contact_role', 'value' => 'testrole'))));
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
                'related_module_link_name_to_fields_array' => array(array('name' =>  'email_addresses', 'value' => array('id', 'email_address', 'opt_out', 'primary_address'))))
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
