<?php

require_once('service/v3/SugarWebServiceUtilv3.php');
require_once('tests/service/APIv3Helper.php');


class RESTAPI3Test extends Sugar_PHPUnit_Framework_TestCase
{
    protected $_user;
    
    protected $_lastRawResponse;
    
    private static $helperObject;
    
    public function setUp()
    {
        //Reload langauge strings 
        $GLOBALS['app_strings'] = return_application_language($GLOBALS['current_language']);
        $GLOBALS['app_list_strings'] = return_app_list_strings_language($GLOBALS['current_language']);
        $GLOBALS['mod_strings'] = return_module_language($GLOBALS['current_language'], 'Accounts');
        //Create an anonymous user for login purposes/
        $this->_user = SugarTestUserUtilities::createAnonymousUser();
        $this->_user->status = 'Active';
        $this->_user->is_admin = 1;
        $this->_user->save();
        $GLOBALS['current_user'] = $this->_user;
        
        self::$helperObject = new APIv3Helper();
    }
    
    public function tearDown() 
	{
	    if(isset($GLOBALS['listViewDefs'])) unset($GLOBALS['listViewDefs']); 
	    if(isset($GLOBALS['viewdefs'])) unset($GLOBALS['viewdefs']); 
	}
	
    protected function _makeRESTCall($method,$parameters)
    {
        // specify the REST web service to interact with 
        $url = $GLOBALS['sugar_config']['site_url'].'/service/v3/rest.php'; 
        // Open a curl session for making the call 
        $curl = curl_init($url); 
        // set URL and other appropriate options
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0 );
        // build the request URL
        $json = json_encode($parameters); 
        $postArgs = "method=$method&input_type=JSON&response_type=JSON&rest_data=$json"; 
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postArgs); 
        // Make the REST call, returning the result 
        $response = curl_exec($curl); 
        // Close the connection 
        curl_close($curl);  
        
        $this->_lastRawResponse = $response;
        
        // Convert the result from JSON format to a PHP array 
        return json_decode($response,true); 
    }
    
    protected function _returnLastRawResponse()
    {
        return "Error in web services call. Response was: {$this->_lastRawResponse}";
    }
    
    protected function _login()
    {
        return $this->_makeRESTCall('login',
            array(
                'user_auth' => 
                    array( 
                        'user_name' => $this->_user->user_name, 
                        'password' => $this->_user->user_hash, 
                        'version' => '.01',
                        ), 
                'application_name' => 'SugarTestRunner', 
                'name_value_list' => array(),
                )
            ); 
    }
    
    public function testSearchByModule()
    {
        $result = $this->_login();
        $session = $result['id'];

        $seedData = self::$helperObject->populateSeedDataForSearchTest($this->_user->id);
        
        $searchModules = array('Accounts','Contacts','Opportunities');
        $searchString = "UNIT TEST";
        $offSet = 0;
        $maxResults = 10;

        $results = $this->_makeRESTCall('search_by_module',
                        array(
                            'session' => $session,
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
        $result = $this->_login();
        $session = $result['id'];

        $seedData = self::$helperObject->populateSeedDataForSearchTest($this->_user->id);
        
        $returnFields = array('name','id','deleted');
        $searchModules = array('Accounts','Contacts','Opportunities');
        $searchString = "UNIT TEST";
        $offSet = 0;
        $maxResults = 10;

        $results = $this->_makeRESTCall('search_by_module',
                        array(
                            'session' => $session,
                            'search'  => $searchString,
                            'modules' => $searchModules,
                            'offset'  => $offSet,
                            'max'     => $maxResults,
                            'user'    => $this->_user->id,
                            'selectFields' => $returnFields)
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
    
    public function testGetServerInformation()
    {
        require('sugar_version.php');
        
        $result = $this->_login();
        $session = $result['id'];
            
        $result = $this->_makeRESTCall('get_server_info',array());
        
        $this->assertEquals($sugar_version, $result['version'],'Unable to get server information');
        $this->assertEquals($sugar_flavor, $result['flavor'],'Unable to get server information');
    }
    
    public function testGetModuleList()
    {
        $result = $this->_login();
        $session = $result['id'];
        
        $account = new Account();
        $account->id = uniqid();
        $account->new_with_id = TRUE;
        $account->name = "Test " . $account->id;
        $account->save();
        
        $whereClause = "accounts.name='{$account->name}'";
        $module = 'Accounts';
        $orderBy = 'name';
        $offset = 0;
        $returnFields = array('name');
        $result = $this->_makeRESTCall('get_entry_list', array($session, $module, $whereClause, $orderBy,$offset, $returnFields)); 
        
        $this->assertEquals($account->id, $result['entry_list'][0]['id'],'Unable to retrieve account list during search.');

        $GLOBALS['db']->query("DELETE FROM accounts WHERE id = '{$account->id}'");
        
    }
    
    public function testLogin()
    {
        $result = $this->_login();
        $this->assertTrue(!empty($result['id']) && $result['id'] != -1,$this->_returnLastRawResponse());
    }
    
    public static function _multipleModuleLayoutProvider()
    {
        return array(                 
                        array(
                            'module' => array('Accounts','Contacts'),
                            'type' => array('default'),
                            'view' => array('list'),
                            'expected_file' => array(
                                'Accounts' => array( 'default' => array('list' => 'modules/Accounts/metadata/listviewdefs.php')),
                                'Contacts' => array( 'default' => array('list' => 'modules/Contacts/metadata/listviewdefs.php')))
                        ),
                        array(
                            'module' => array('Accounts','Contacts'),
                            'type' => array('default'),
                            'view' => array('list','detail'),
                            'expected_file' => array(
                                'Accounts' => array(
                                    'default' => array(
                                                'list' => 'modules/Accounts/metadata/listviewdefs.php',
                                                'detail' => 'modules/Accounts/metadata/detailviewdefs.php')),
                                'Contacts' => array(
                                    'default' => array(
                                                'list' => 'modules/Contacts/metadata/listviewdefs.php',
                                                'detail' => 'modules/Contacts/metadata/detailviewdefs.php'))
                        ))
        );
    }

    /**
     * @dataProvider _multipleModuleLayoutProvider
     */
    public function testGetMultipleModuleLayout($a_module, $a_type, $a_view, $a_expected_file)
    {
        $result = $this->_login();
        $session = $result['id'];
            
        $results = $this->_makeRESTCall('get_module_layout',
                        array(
                            'session' => $session,
                            'module' => $a_module,
                            'type' => $a_type,
                            'view' => $a_view)
                        );

        foreach ($results as $module => $moduleResults ) 
        {                       
            foreach ($moduleResults as $type => $viewResults)
            {
                foreach ($viewResults as $view => $result)
                {    
                    $expected_file = $a_expected_file[$module][$type][$view];
                    if ( is_file('custom'  . DIRECTORY_SEPARATOR . $expected_file) ) 
                    	require('custom'  . DIRECTORY_SEPARATOR . $expected_file);
                    else
                        require($expected_file);
            
                    if($view == 'list')
                        $expectedResults = $listViewDefs[$module];
                    else
                        $expectedResults = $viewdefs[$module][ucfirst($view) .'View' ];
                    
                    $this->assertEquals(md5(serialize($expectedResults)), md5(serialize($result)), "Unable to retrieve module layout: module {$module}, type $type, view $view");
                }
                }
        } 
   }
    
    public static function _moduleLayoutProvider()
    {
        return array( 
                    array('module' => 'Accounts','type' => 'default', 'view' => 'list','expected_file' => 'modules/Accounts/metadata/listviewdefs.php' ), 
                    array('module' => 'Accounts','type' => 'default', 'view' => 'edit','expected_file' => 'modules/Accounts/metadata/editviewdefs.php' ),  
                    array('module' => 'Accounts','type' => 'default', 'view' => 'detail','expected_file' => 'modules/Accounts/metadata/detailviewdefs.php' ),  
        );
    }

    /**
     * @dataProvider _moduleLayoutProvider
     */
    public function testGetModuleLayout($module, $type, $view, $expected_file)
    {
        $result = $this->_login();
        $session = $result['id'];
            
        $result = $this->_makeRESTCall('get_module_layout',
                        array(
                            'session' => $session,
                            'module' => array($module),
                            'type' => array($type),
                            'view' => array($view))
                        );
                    
        if ( is_file('custom'  . DIRECTORY_SEPARATOR . $expected_file) ) 
        	require('custom'  . DIRECTORY_SEPARATOR . $expected_file);
        else
            require($expected_file);

        if($view == 'list')
            $expectedResults = $listViewDefs[$module];
        else
            $expectedResults = $viewdefs[$module][ucfirst($view) .'View' ];
 
        $a_expectedResults = array();
        $a_expectedResults[$module][$type][$view] = $expectedResults;
        
        $this->assertEquals(md5(serialize($a_expectedResults)), md5(serialize($result)), "Unable to retrieve module layout: module {$module}, type $type, view $view");
    }

     /**
     * @dataProvider _moduleLayoutProvider
     */
    public function testGetModuleLayoutMD5($module, $type, $view, $expected_file)
    {
        $result = $this->_login();
        $session = $result['id'];
            
        $fullResult = $this->_makeRESTCall('get_module_layout_md5',
                        array(
                            'session' => $session,
                            'module' => array($module),
                            'type' => array($type),
                            'view' => array($view) )
                        );
        $result = $fullResult['md5'];
        if ( is_file('custom'  . DIRECTORY_SEPARATOR . $expected_file) ) 
        	require('custom'  . DIRECTORY_SEPARATOR . $expected_file);
        else
            require($expected_file);

        if($view == 'list')
            $expectedResults = $listViewDefs[$module];
        else
            $expectedResults = $viewdefs[$module][ucfirst($view) .'View' ];
        
        $a_expectedResults = array();
        $a_expectedResults[$module][$type][$view] = $expectedResults;
        
        $this->assertEquals(md5(serialize($expectedResults)), $result[$module][$type][$view], "Unable to retrieve module layout md5: module {$module}, type $type, view $view");

    }
    
    public function testGetAvailableModules()
    {
        $this->markTestSkipped('Will be updated week of June 21, 2010');
        
        $result = $this->_login();
        $this->assertTrue(!empty($result['id']) && $result['id'] != -1,$this->_returnLastRawResponse());
        $session = $result['id'];
        
        $fullResult = $this->_makeRESTCall('get_available_modules', array('session' => $session, 'filter' => 'all' ));
        $this->assertTrue(in_array('ACLFields', $fullResult['modules']), "Unable to get all available modules");
        $this->assertTrue(in_array('Schedulers', $fullResult['modules']), "Unable to get all available modules");
        $this->assertTrue(in_array('Roles', $fullResult['modules']), "Unable to get all available modules");
        
        $sh = new SugarWebServiceUtilv3();
                
        $mobileResult = $this->_makeRESTCall('get_available_modules', array('session' => $session, 'filter' => 'mobile' ));
        $mobileResultExpected = $sh->get_visible_mobile_modules($fullResult['modules']);
        $mobileResultExpected = md5(serialize(array('modules' => $mobileResultExpected)));
        $mobileResult = md5(serialize($mobileResult));
        $this->assertEquals($mobileResultExpected, $mobileResult, "Unable to get all visible mobile modules");
        
        $defaultResult = $this->_makeRESTCall('get_available_modules', array('session' => $session, 'filter' => 'default' ));
        $defaultResult = md5(serialize($defaultResult['modules']));
        $defaultResultExpected = $sh->get_visible_modules($fullResult['modules']);
        $defaultResultExpected = md5(serialize($defaultResultExpected));        
        $this->assertEquals($defaultResultExpected, $defaultResult, "Unable to get all visible default modules");
      
    }
    
    public function testGetVardefsMD5()
    {
        $GLOBALS['reload_vardefs'] = TRUE;
        $result = $this->_login();
        $this->assertTrue(!empty($result['id']) && $result['id'] != -1,$this->_returnLastRawResponse());
        $session = $result['id'];
        
        //Test a regular module
        $fullResult = $this->_makeRESTCall('get_module_fields_md5', array('session' => $session, 'module' => 'Accounts' )); 
        $result = $fullResult['Accounts'];
        $a = new Account();
        $soapHelper = new SugarWebServiceUtilv3();
        $actualVardef = $soapHelper->get_return_module_fields($a,'Accounts','');
        $actualMD5 = md5(serialize($actualVardef));
        $this->assertEquals($actualMD5, $result, "Unable to retrieve vardef md5.");
        
        //Test a fake module
        $result = $this->_makeRESTCall('get_module_fields_md5', array('session' => $session, 'module' => 'BadModule' )); 
        $this->assertTrue($result['name'] == 'Module Does Not Exist');
        unset($GLOBALS['reload_vardefs']);
    }
    
    public function testAddNewAccountAndThenDeleteIt()
    {
        $result = $this->_login();
        $this->assertTrue(!empty($result['id']) && $result['id'] != -1,$this->_returnLastRawResponse());
        $session = $result['id']; 
        
        $result = $this->_makeRESTCall('set_entry',
            array(
                'session' => $session, 
                'module' => 'Accounts', 
                'name_value_list' => array( 
                    array('name' => 'name', 'value' => 'New Account'), 
                    array('name' => 'description', 'value' => 'This is an account created from a REST web services call'), 
                    ), 
                )
            ); 
        
        $this->assertTrue(!empty($result['id']) && $result['id'] != -1,$this->_returnLastRawResponse());
        
        $accountId = $result['id'];
        
        // verify record was created
        $result = $this->_makeRESTCall('get_entry',
            array(
                'session' => $session, 
                'module' => 'Accounts', 
                'id' => $accountId,
                )
            );
        
        $this->assertEquals($result['entry_list'][0]['id'],$accountId,$this->_returnLastRawResponse());
        
        // delete the record
        $result = $this->_makeRESTCall('set_entry',
            array(
                'session' => $session, 
                'module' => 'Accounts', 
                'name_value_list' => array( 
                    array('name' => 'id', 'value' => $accountId), 
                    array('name' => 'deleted', 'value' => '1'), 
                    ), 
                )
            ); 
        
        $this->assertTrue(!empty($result['id']) && $result['id'] != -1,$this->_returnLastRawResponse());
        
        // try to retrieve again to validate it is deleted
        $result = $this->_makeRESTCall('get_entry',
            array(
                'session' => $session, 
                'module' => 'Accounts', 
                'id' => $accountId,
                )
            ); 
        
        $this->assertTrue(!empty($result['entry_list'][0]['id']) && $result['entry_list'][0]['id'] != -1,$this->_returnLastRawResponse());
        $this->assertEquals($result['entry_list'][0]['name_value_list'][0]['name'],'warning',$this->_returnLastRawResponse());
        $this->assertEquals($result['entry_list'][0]['name_value_list'][0]['value'],"Access to this object is denied since it has been deleted or does not exist",$this->_returnLastRawResponse());
        $this->assertEquals($result['entry_list'][0]['name_value_list'][1]['name'],'deleted',$this->_returnLastRawResponse());
        $this->assertEquals($result['entry_list'][0]['name_value_list'][1]['value'],1,$this->_returnLastRawResponse());
    }
    
    public function testRelateAccountToTwoContacts()
    {
        $result = $this->_login();
        $this->assertTrue(!empty($result['id']) && $result['id'] != -1,$this->_returnLastRawResponse());
        $session = $result['id']; 
        
        $result = $this->_makeRESTCall('set_entry',
            array(
                'session' => $session, 
                'module' => 'Accounts', 
                'name_value_list' => array( 
                    array('name' => 'name', 'value' => 'New Account'), 
                    array('name' => 'description', 'value' => 'This is an account created from a REST web services call'), 
                    ), 
                )
            ); 
        
        $this->assertTrue(!empty($result['id']) && $result['id'] != -1,$this->_returnLastRawResponse());
        
        $accountId = $result['id'];
        
        $result = $this->_makeRESTCall('set_entry',
            array(
                'session' => $session, 
                'module' => 'Contacts', 
                'name_value_list' => array( 
                    array('name' => 'last_name', 'value' => 'New Contact 1'), 
                    array('name' => 'description', 'value' => 'This is a contact created from a REST web services call'), 
                    ), 
                )
            ); 
        
        $this->assertTrue(!empty($result['id']) && $result['id'] != -1,$this->_returnLastRawResponse());
        
        $contactId1 = $result['id'];
        
        $result = $this->_makeRESTCall('set_entry',
            array(
                'session' => $session, 
                'module' => 'Contacts', 
                'name_value_list' => array( 
                    array('name' => 'last_name', 'value' => 'New Contact 2'), 
                    array('name' => 'description', 'value' => 'This is a contact created from a REST web services call'), 
                    ), 
                )
            ); 
        
        $this->assertTrue(!empty($result['id']) && $result['id'] != -1,$this->_returnLastRawResponse());
        
        $contactId2 = $result['id'];
        
        // now relate them together
        $result = $this->_makeRESTCall('set_relationship',
            array( 
                'session' => $session, 
                'module' => 'Accounts', 
                'module_id' => $accountId,
                'link_field_name' => 'contacts', 
                'related_ids' => array($contactId1,$contactId2), 
                )
            ); 
        
        $this->assertEquals($result['created'],1,$this->_returnLastRawResponse());
        
        // check the relationship
        $result = $this->_makeRESTCall('get_relationships',
            array( 
                'session' => $session, 
                'module' => 'Accounts', 
                'module_id' => $accountId,
                'link_field_name' => 'contacts', 
                'related_module_query' => '', 
                'related_fields' => array('last_name','description'),
                'related_module_link_name_to_fields_array' => array(),
                'deleted' => false,
                )
            );
        
        $returnedValues = array();
        $returnedValues[] = $result['entry_list'][0]['name_value_list']['last_name']['value'];
        $returnedValues[] = $result['entry_list'][1]['name_value_list']['last_name']['value'];
        
        
        $this->assertContains('New Contact 1',$returnedValues,$this->_returnLastRawResponse());
        $this->assertContains('New Contact 2',$returnedValues,$this->_returnLastRawResponse());       
    }
     
    /**
     * @group bug36658
     */
    public function testOrderByClauseOfGetRelationship()
    {
        $result = $this->_login();
        $this->assertTrue(!empty($result['id']) && $result['id'] != -1,$this->_returnLastRawResponse());
        $session = $result['id']; 
        
        $result = $this->_makeRESTCall('set_entry',
            array(
                'session' => $session, 
                'module' => 'Accounts', 
                'name_value_list' => array( 
                    array('name' => 'name', 'value' => 'New Account'), 
                    array('name' => 'description', 'value' => 'This is an account created from a REST web services call'), 
                    ), 
                )
            ); 
        
        $this->assertTrue(!empty($result['id']) && $result['id'] != -1,$this->_returnLastRawResponse());
        
        $accountId = $result['id'];
        
        $result = $this->_makeRESTCall('set_entry',
            array(
                'session' => $session, 
                'module' => 'Contacts', 
                'name_value_list' => array( 
                    array('name' => 'last_name', 'value' => 'New Contact 1'), 
                    array('name' => 'description', 'value' => 'This is a contact created from a REST web services call'), 
                    ), 
                )
            ); 
        
        $this->assertTrue(!empty($result['id']) && $result['id'] != -1,$this->_returnLastRawResponse());
        
        $contactId1 = $result['id'];
        
        $result = $this->_makeRESTCall('set_entry',
            array(
                'session' => $session, 
                'module' => 'Contacts', 
                'name_value_list' => array( 
                    array('name' => 'last_name', 'value' => 'New Contact 2'), 
                    array('name' => 'description', 'value' => 'This is a contact created from a REST web services call'), 
                    ), 
                )
            ); 
        
        $this->assertTrue(!empty($result['id']) && $result['id'] != -1,$this->_returnLastRawResponse());
        
        $contactId2 = $result['id'];
        
        // now relate them together
        $result = $this->_makeRESTCall('set_relationship',
            array( 
                'session' => $session, 
                'module' => 'Accounts', 
                'module_id' => $accountId,
                'link_field_name' => 'contacts', 
                'related_ids' => array($contactId1,$contactId2), 
                )
            ); 
        
        $this->assertEquals($result['created'],1,$this->_returnLastRawResponse());
        
        // check the relationship
        $result = $this->_makeRESTCall('get_relationships',
            array( 
                'session' => $session, 
                'module' => 'Accounts', 
                'module_id' => $accountId,
                'link_field_name' => 'contacts', 
                'related_module_query' => '', 
                'related_fields' => array('last_name','description'),
                'related_module_link_name_to_fields_array' => array(),
                'deleted' => false,
                'order_by' => 'last_name',
                )
            );
        
        $this->assertEquals($result['entry_list'][0]['name_value_list']['last_name']['value'],'New Contact 1',$this->_returnLastRawResponse());
        $this->assertEquals($result['entry_list'][1]['name_value_list']['last_name']['value'],'New Contact 2',$this->_returnLastRawResponse());       
    }
    
    public static function _subpanelLayoutProvider()
    {
        return array(
            array(
                'module' => 'Contacts',
                'type' => 'default',
                'view' => 'subpanel',
            ),
            array(
                'module' => 'Leads',
                'type' => 'wireless',
                'view' => 'subpanel',
            )
        );
    }

    /**
     * @dataProvider _subpanelLayoutProvider
     */
    public function testGetSubpanelLayout($module, $type, $view)
    {
        $result = $this->_login();
        $session = $result['id'];

        $results = $this->_makeRESTCall('get_module_layout',
            array(
                'session' => $session,
                'module' => array($module),
                'type' => array($type),
                'view' => array($view))
        );

        $this->assertTrue(isset($results[$module][$type][$view]), "Unable to get subpanel defs");
    }
    
     public function testGetLastViewed()
     {
         $result = $this->_login();
         $this->assertTrue(!empty($result['id']) && $result['id'] != -1,$this->_returnLastRawResponse());
         $session = $result['id'];

         $testModule = 'Accounts';
         $testModuleID = uniqid();

         $this->_createTrackerEntry($testModule,$testModuleID);

         $results = $this->_makeRESTCall('get_last_viewed',
                             array(
                             'session' => $session,
                             'modules' => array($testModule),
                             )
         );

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
     
     public function testGetUpcomingActivities()
     {
         $result = $this->_login();
         $this->assertTrue(!empty($result['id']) && $result['id'] != -1,$this->_returnLastRawResponse());
         $session = $result['id'];
         $expected = $this->_createUpcomingActivities(); //Seed the data.
         $results = $this->_makeRESTCall('get_upcoming_activities',
                             array(
                             'session' => $session,
                             )
         );
         
         $this->assertEquals($expected[0] ,$results[0]['id'] , "Unable to get upcoming activities");
         $this->assertEquals($expected[1] ,$results[1]['id'] , "Unable to get upcoming activities");
         
         $this->_removeUpcomingActivities();
     }
     
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
}
