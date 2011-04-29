<?php
require_once('modules/MySettings/StoreQuery.php');

class Bug42378Test extends Sugar_PHPUnit_Framework_TestCase 
{
	var $saved_search_id;
	
    public function setUp() 
    {
        //$this->useOutputBuffering = false;
        $this->saved_search_id = md5(gmmktime());
        
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
        $datetime_prefs = $GLOBALS['current_user']->getUserDateTimePreferences();
        $GLOBALS['current_user']->setPreference('datef', 'm/d/Y', 0, 'global');
        $GLOBALS['current_user']->save();
        $GLOBALS['db']->query("INSERT INTO saved_search (id, name, search_module, deleted, date_entered, date_modified, assigned_user_id, contents) VALUES ('" . $this->saved_search_id . "', 'Bug42738', 'Opportunities', 0, '2011-03-10 17:05:27', '2011-03-10 17:05:27', '".$GLOBALS["current_user"]->id."', 'YTo0OTp7czoxMzoic2VhcmNoRm9ybVRhYiI7czoxNToiYWR2YW5jZWRfc2VhcmNoIjtzOjU6InF1ZXJ5IjtzOjQ6InRydWUiO3M6MTM6Im5hbWVfYWR2YW5jZWQiO3M6MDoiIjtzOjIxOiJhY2NvdW50X25hbWVfYWR2YW5jZWQiO3M6MDoiIjtzOjM0OiJjdXN0b21kYXRlX2NfYWR2YW5jZWRfcmFuZ2VfY2hvaWNlIjtzOjE6Ij0iO3M6Mjc6InJhbmdlX2N1c3RvbWRhdGVfY19hZHZhbmNlZCI7czoxMDoiMDMvMDEvMjAxMSI7czozMzoic3RhcnRfcmFuZ2VfY3VzdG9tZGF0ZV9jX2FkdmFuY2VkIjtzOjA6IiI7czozMToiZW5kX3JhbmdlX2N1c3RvbWRhdGVfY19hZHZhbmNlZCI7czowOiIiO3M6Mzg6ImN1c3RvbWRhdGV0aW1lX2NfYWR2YW5jZWRfcmFuZ2VfY2hvaWNlIjtzOjE6Ij0iO3M6MzE6InJhbmdlX2N1c3RvbWRhdGV0aW1lX2NfYWR2YW5jZWQiO3M6MTA6IjAzLzAyLzIwMTEiO3M6Mzc6InN0YXJ0X3JhbmdlX2N1c3RvbWRhdGV0aW1lX2NfYWR2YW5jZWQiO3M6MDoiIjtzOjM1OiJlbmRfcmFuZ2VfY3VzdG9tZGF0ZXRpbWVfY19hZHZhbmNlZCI7czowOiIiO3M6Mjg6ImFtb3VudF9hZHZhbmNlZF9yYW5nZV9jaG9pY2UiO3M6MToiPSI7czoyMToicmFuZ2VfYW1vdW50X2FkdmFuY2VkIjtzOjA6IiI7czoyNzoic3RhcnRfcmFuZ2VfYW1vdW50X2FkdmFuY2VkIjtzOjA6IiI7czoyNToiZW5kX3JhbmdlX2Ftb3VudF9hZHZhbmNlZCI7czowOiIiO3M6MzQ6ImRhdGVfZW50ZXJlZF9hZHZhbmNlZF9yYW5nZV9jaG9pY2UiO3M6NzoiYmV0d2VlbiI7czoyNzoicmFuZ2VfZGF0ZV9lbnRlcmVkX2FkdmFuY2VkIjtzOjA6IiI7czozMzoic3RhcnRfcmFuZ2VfZGF0ZV9lbnRlcmVkX2FkdmFuY2VkIjtzOjEwOiIwMy8wMS8yMDExIjtzOjMxOiJlbmRfcmFuZ2VfZGF0ZV9lbnRlcmVkX2FkdmFuY2VkIjtzOjEwOiIwMy8wNS8yMDExIjtzOjM1OiJkYXRlX21vZGlmaWVkX2FkdmFuY2VkX3JhbmdlX2Nob2ljZSI7czoxMjoiZ3JlYXRlcl90aGFuIjtzOjI4OiJyYW5nZV9kYXRlX21vZGlmaWVkX2FkdmFuY2VkIjtzOjEwOiIwMy8wMS8yMDExIjtzOjM0OiJzdGFydF9yYW5nZV9kYXRlX21vZGlmaWVkX2FkdmFuY2VkIjtzOjA6IiI7czozMjoiZW5kX3JhbmdlX2RhdGVfbW9kaWZpZWRfYWR2YW5jZWQiO3M6MDoiIjtzOjMzOiJkYXRlX2Nsb3NlZF9hZHZhbmNlZF9yYW5nZV9jaG9pY2UiO3M6MTE6Imxhc3RfN19kYXlzIjtzOjI2OiJyYW5nZV9kYXRlX2Nsb3NlZF9hZHZhbmNlZCI7czoxMzoiW2xhc3RfN19kYXlzXSI7czozMjoic3RhcnRfcmFuZ2VfZGF0ZV9jbG9zZWRfYWR2YW5jZWQiO3M6MDoiIjtzOjMwOiJlbmRfcmFuZ2VfZGF0ZV9jbG9zZWRfYWR2YW5jZWQiO3M6MDoiIjtzOjQzOiJ1cGRhdGVfZmllbGRzX3RlYW1fbmFtZV9hZHZhbmNlZF9jb2xsZWN0aW9uIjtzOjA6IiI7czozMjoidGVhbV9uYW1lX2FkdmFuY2VkX25ld19vbl91cGRhdGUiO3M6NToiZmFsc2UiO3M6MzE6InRlYW1fbmFtZV9hZHZhbmNlZF9hbGxvd191cGRhdGUiO3M6MDoiIjtzOjM1OiJ0ZWFtX25hbWVfYWR2YW5jZWRfYWxsb3dlZF90b19jaGVjayI7czo1OiJmYWxzZSI7czozMToidGVhbV9uYW1lX2FkdmFuY2VkX2NvbGxlY3Rpb25fMCI7czowOiIiO3M6MzQ6ImlkX3RlYW1fbmFtZV9hZHZhbmNlZF9jb2xsZWN0aW9uXzAiO3M6MDoiIjtzOjIzOiJ0ZWFtX25hbWVfYWR2YW5jZWRfdHlwZSI7czozOiJhbnkiO3M6MjM6ImZhdm9yaXRlc19vbmx5X2FkdmFuY2VkIjtzOjE6IjAiO3M6OToic2hvd1NTRElWIjtzOjI6Im5vIjtzOjEzOiJzZWFyY2hfbW9kdWxlIjtzOjEzOiJPcHBvcnR1bml0aWVzIjtzOjE5OiJzYXZlZF9zZWFyY2hfYWN0aW9uIjtzOjQ6InNhdmUiO3M6MTQ6ImRpc3BsYXlDb2x1bW5zIjtzOjg5OiJOQU1FfEFDQ09VTlRfTkFNRXxTQUxFU19TVEFHRXxBTU9VTlRfVVNET0xMQVJ8REFURV9DTE9TRUR8QVNTSUdORURfVVNFUl9OQU1FfERBVEVfRU5URVJFRCI7czo4OiJoaWRlVGFicyI7czo5MzoiT1BQT1JUVU5JVFlfVFlQRXxMRUFEX1NPVVJDRXxORVhUX1NURVB8UFJPQkFCSUxJVFl8Q1JFQVRFRF9CWV9OQU1FfFRFQU1fTkFNRXxNT0RJRklFRF9CWV9OQU1FIjtzOjc6Im9yZGVyQnkiO3M6NDoiTkFNRSI7czo5OiJzb3J0T3JkZXIiO3M6MzoiQVNDIjtzOjE2OiJzdWdhcl91c2VyX3RoZW1lIjtzOjU6IlN1Z2FyIjtzOjEzOiJDb250YWN0c19kaXZzIjtzOjE3OiJvcHBvcnR1bml0aWVzX3Y9IyI7czoxMzoiTW9kdWxlQnVpbGRlciI7czoxNToiaGVscEhpZGRlbj10cnVlIjtzOjIyOiJzdWdhcl90aGVtZV9nbV9jdXJyZW50IjtzOjM6IkFsbCI7czoxNToiZ2xvYmFsTGlua3NPcGVuIjtzOjQ6InRydWUiO3M6ODoiYWR2YW5jZWQiO2I6MTt9')");
    }
    
    public function tearDown() 
    {
        //$GLOBALS['db']->query("DELETE FROM saved_search where id = '{$this->saved_search_id}'"); 
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
    }
    
    /**
     * This test captures the scenario for date_modified field where range search is enabled
     */    
    public function testSaveRangeDateFields() 
    {
        require_once('modules/SavedSearch/SavedSearch.php');
	    $focus = new SavedSearch();
		$focus->retrieve($this->saved_search_id);
		$_REQUEST = unserialize(base64_decode($focus->contents));
		$mockBean = new Bug42377MockOpportunity();
		$focus->handleSave('', false, '', $this->saved_search_id, $mockBean);
		
		//Now retrieve what we have saved and test
		$focus = new SavedSearch();
		$focus->retrieve($this->saved_search_id);
		$formatted_data = unserialize(base64_decode($focus->contents));
		$this->assertEquals($formatted_data['range_date_modified_advanced'], '2011-03-01', "Assert that value is in db format ('2011-03-01')");
		$this->assertEquals($formatted_data['start_range_date_entered_advanced'], '2011-03-01', "Assert that value is in db format ('2011-03-01')");
		$this->assertEquals($formatted_data['end_range_date_entered_advanced'], '2011-03-05', "Assert that value is in db format ('2011-03-05')");		
		
		//Last check to see that the macro value is okay and preserved
		$this->assertEquals($formatted_data['range_date_closed_advanced'], '[last_7_days]', "Assert that the macro date value [last_7_days] was preserved");
		$this->assertEquals($formatted_data['date_closed_advanced_range_choice'], 'last_7_days', "Assert that the macro date value choice last_7_days was preserved");		
    }
    
    /**
     * This test captures the scenario for date_modified field where range search is not enabled
     */
    public function testSaveDateFields() 
    {
        require_once('modules/SavedSearch/SavedSearch.php');
	    $focus = new SavedSearch();
		$focus->retrieve($this->saved_search_id);
		$_REQUEST = unserialize(base64_decode($focus->contents));
		unset($_REQUEST['start_range_date_modified_advanced']);
		unset($_REQUEST['end_range_date_modified_advanced']);
		unset($_REQUEST['range_date_modified_advanced']);
		$_REQUEST['date_modified_advanced'] = '07/03/2009'; //Special date :)
		$mockBean = new Bug42377MockOpportunity();
		$focus->handleSave('', false, '', $this->saved_search_id, $mockBean);
		
		//Now retrieve what we have saved and test
		$focus = new SavedSearch();
		$focus->retrieve($this->saved_search_id);
		$formatted_data = unserialize(base64_decode($focus->contents));
		$this->assertEquals($formatted_data['date_modified_advanced'], '2009-07-03', "Assert that value is in db format ('2009-07-03')");		

    	//Now test that when we populate the search form, we bring it back to user's date format
    	$focus->retrieveSavedSearch($this->saved_search_id);
		$focus->populateRequest();
    	$this->assertEquals($_REQUEST['date_modified_advanced'], '07/03/2009', "Assert that dates in db format were converted back to user's date preference");    
    
    	//Take this a step further, assume date format now changes, will date be populated correctly?
        global $current_user;
    	$current_user->setPreference('datef', 'd/m/Y', 0, 'global');
        $current_user->save();  

    	//Now test that when we populate the search form, we bring it back to user's date format
    	$focus->retrieveSavedSearch($this->saved_search_id);
		$focus->populateRequest();
    	$this->assertEquals($_REQUEST['date_modified_advanced'], '03/07/2009', "Assert that dates in db format were converted back to user's date preference");            
    }       
    
    public function testStoreQuerySaveAndPopulate()
    {
    	global $current_user, $timedate;
    	
    	$storeQuery = new StoreQuery();
    	//Simulate a search request here
    	$_REQUEST = array
			(
			    'module' => 'Opportunities',
			    'action' => 'index',
			    'searchFormTab' => 'advanced_search',
			    'query' => true,
			    'name_advanced' => '',
			    'account_name_advanced' => '',
			    'amount_advanced_range_choice' => '=',
			    'range_amount_advanced' => '',
			    'start_range_amount_advanced' => '',
			    'end_range_amount_advanced' => '',
			    'date_closed_advanced_range_choice' => '=',
			    'range_date_closed_advanced' => '09/01/2008',
			    'start_range_date_closed_advanced' => '',
			    'end_range_date_closed_advanced' => '',
			    'next_step_advanced' => '',
			    'update_fields_team_name_advanced_collection' => '',
			    'team_name_advanced_new_on_update' => false,
			    'team_name_advanced_allow_update' => '',
			    'team_name_advanced_allowed_to_check' => false,
			    'team_name_advanced_collection_0' => '',
			    'id_team_name_advanced_collection_0' => '',
			    'team_name_advanced_type' => 'any',
			    'favorites_only_advanced' => 0,
			    'showSSDIV' => 'no',
			    'saved_search_name' => '',
			    'search_module' => '',
			    'saved_search_action' => '',
			    'displayColumns' => 'NAME|ACCOUNT_NAME|SALES_STAGE|AMOUNT_USDOLLAR|DATE_CLOSED|ASSIGNED_USER_NAME|DATE_ENTERED',
			    'hideTabs' => 'OPPORTUNITY_TYPE|LEAD_SOURCE|NEXT_STEP|PROBABILITY|CREATED_BY_NAME|TEAM_NAME|MODIFIED_BY_NAME',
			    'orderBy' => 'NAME',
			    'sortOrder' => 'ASC',
			    'button' => 'Search',
			    'saved_search_select' => '_none',
			    'sugar_user_theme' => 'Sugar',
			    'ModuleBuilder' => 'helpHidden=true',
			    'Contacts_divs' => 'quotes_v=#',
			    'sugar_theme_gm_current' => 'All',
			    'globalLinksOpen' => 'true',
			    'SQLiteManager_currentLangue' => '2',
			    'PHPSESSID' => 'b8e4b4b955ef3c4b29291779751b5fca',
			);
    	
    	$storeQuery->saveFromRequest('Opportunities');
    	
    	$storedSearch = StoreQuery::getStoredQueryForUser('Opportunities');
    	$this->assertEquals($storedSearch['range_date_closed_advanced'], '2008-09-01', 'Assert that search date 09/02/2008 was saved in db format 2008-09-01');

    	//Test that value is converted to user date preferences when retrieved
    	unset($_REQUEST['range_date_closed_advanced']);
    	$storeQuery->loadQuery('Opportunities');
        $storeQuery->populateRequest();
        $this->assertTrue(isset($_REQUEST['range_date_closed_advanced']), 'Assert that the field was correctly populated');
    	$this->assertEquals($_REQUEST['range_date_closed_advanced'], '09/01/2008', 'Assert that search date in db_format 2008-09-01 was converted to user date preference 09/01/2008');
    	
    	//Now say the user changes his date preferences and switches back to this StoredQuery
        $current_user->setPreference('datef', 'Y.m.d', 0, 'global');
        $current_user->save();

        //Now when we reload this store query, the $_REQUEST array should be populated with new user date preference
    	unset($_REQUEST['range_date_closed_advanced']);
    	$storeQuery->loadQuery('Opportunities');
        $storeQuery->populateRequest();
        $this->assertTrue(isset($_REQUEST['range_date_closed_advanced']), 'Assert that the field was correctly populated');
    	$this->assertEquals($_REQUEST['range_date_closed_advanced'], '2008.09.01', 'Assert that search date in db_format 2008-09-01 was converted to user date preference 2008.09.01');    	        
    }
    
}


class Bug42377MockOpportunity extends Opportunity
{
	
var $field_defs = array (
			  'id' => 
			  array (
			    'name' => 'id',
			    'vname' => 'LBL_ID',
			    'type' => 'id',
			    'required' => true,
			    'reportable' => true,
			    'comment' => 'Unique identifier',
			  ),
			  'name' => 
			  array (
			    'name' => 'name',
			    'vname' => 'LBL_OPPORTUNITY_NAME',
			    'type' => 'name',
			    'dbType' => 'varchar',
			    'len' => '50',
			    'unified_search' => true,
			    'comment' => 'Name of the opportunity',
			    'merge_filter' => 'selected',
			    'importable' => 'required',
			    'required' => true,
			  ),
			  'date_entered' => 
			  array (
			    'name' => 'date_entered',
			    'vname' => 'LBL_DATE_ENTERED',
			    'type' => 'datetime',
			    'group' => 'created_by_name',
			    'comment' => 'Date record created',
			    'enable_range_search' => '1',
			    'options' => 'date_range_search_dom',
			  ),
			  'date_modified' => 
			  array (
			    'name' => 'date_modified',
			    'vname' => 'LBL_DATE_MODIFIED',
			    'type' => 'datetime',
			    'group' => 'modified_by_name',
			    'comment' => 'Date record last modified',
			    'enable_range_search' => '1',
			    'options' => 'date_range_search_dom',
			  ),
			  'modified_user_id' => 
			  array (
			    'name' => 'modified_user_id',
			    'rname' => 'user_name',
			    'id_name' => 'modified_user_id',
			    'vname' => 'LBL_MODIFIED',
			    'type' => 'assigned_user_name',
			    'table' => 'users',
			    'isnull' => 'false',
			    'group' => 'modified_by_name',
			    'dbType' => 'id',
			    'reportable' => true,
			    'comment' => 'User who last modified record',
			  ),
			  'modified_by_name' => 
			  array (
			    'name' => 'modified_by_name',
			    'vname' => 'LBL_MODIFIED_NAME',
			    'type' => 'relate',
			    'reportable' => false,
			    'source' => 'non-db',
			    'rname' => 'user_name',
			    'table' => 'users',
			    'id_name' => 'modified_user_id',
			    'module' => 'Users',
			    'link' => 'modified_user_link',
			    'duplicate_merge' => 'disabled',
			  ),
			  'created_by' => 
			  array (
			    'name' => 'created_by',
			    'rname' => 'user_name',
			    'id_name' => 'modified_user_id',
			    'vname' => 'LBL_CREATED',
			    'type' => 'assigned_user_name',
			    'table' => 'users',
			    'isnull' => 'false',
			    'dbType' => 'id',
			    'group' => 'created_by_name',
			    'comment' => 'User who created record',
			  ),
			  'created_by_name' => 
			  array (
			    'name' => 'created_by_name',
			    'vname' => 'LBL_CREATED',
			    'type' => 'relate',
			    'reportable' => false,
			    'link' => 'created_by_link',
			    'rname' => 'user_name',
			    'source' => 'non-db',
			    'table' => 'users',
			    'id_name' => 'created_by',
			    'module' => 'Users',
			    'duplicate_merge' => 'disabled',
			    'importable' => 'false',
			  ),
			  'description' => 
			  array (
			    'name' => 'description',
			    'vname' => 'LBL_DESCRIPTION',
			    'type' => 'text',
			    'comment' => 'Full text of the note',
			    'rows' => 6,
			    'cols' => 80,
			  ),
			  'deleted' => 
			  array (
			    'name' => 'deleted',
			    'vname' => 'LBL_DELETED',
			    'type' => 'bool',
			    'default' => '0',
			    'reportable' => false,
			    'comment' => 'Record deletion indicator',
			  ),
			  'created_by_link' => 
			  array (
			    'name' => 'created_by_link',
			    'type' => 'link',
			    'relationship' => 'opportunities_created_by',
			    'vname' => 'LBL_CREATED_USER',
			    'link_type' => 'one',
			    'module' => 'Users',
			    'bean_name' => 'User',
			    'source' => 'non-db',
			  ),
			  'modified_user_link' => 
			  array (
			    'name' => 'modified_user_link',
			    'type' => 'link',
			    'relationship' => 'opportunities_modified_user',
			    'vname' => 'LBL_MODIFIED_USER',
			    'link_type' => 'one',
			    'module' => 'Users',
			    'bean_name' => 'User',
			    'source' => 'non-db',
			  ),
			  'assigned_user_id' => 
			  array (
			    'name' => 'assigned_user_id',
			    'rname' => 'user_name',
			    'id_name' => 'assigned_user_id',
			    'vname' => 'LBL_ASSIGNED_TO_ID',
			    'group' => 'assigned_user_name',
			    'type' => 'relate',
			    'table' => 'users',
			    'module' => 'Users',
			    'reportable' => true,
			    'isnull' => 'false',
			    'dbType' => 'id',
			    'audited' => true,
			    'comment' => 'User ID assigned to record',
			    'duplicate_merge' => 'disabled',
			  ),
			  'assigned_user_name' => 
			  array (
			    'name' => 'assigned_user_name',
			    'link' => 'assigned_user_link',
			    'vname' => 'LBL_ASSIGNED_TO_NAME',
			    'rname' => 'user_name',
			    'type' => 'relate',
			    'reportable' => false,
			    'source' => 'non-db',
			    'table' => 'users',
			    'id_name' => 'assigned_user_id',
			    'module' => 'Users',
			    'duplicate_merge' => 'disabled',
			  ),
			  'assigned_user_link' => 
			  array (
			    'name' => 'assigned_user_link',
			    'type' => 'link',
			    'relationship' => 'opportunities_assigned_user',
			    'vname' => 'LBL_ASSIGNED_TO_USER',
			    'link_type' => 'one',
			    'module' => 'Users',
			    'bean_name' => 'User',
			    'source' => 'non-db',
			    'duplicate_merge' => 'enabled',
			    'rname' => 'user_name',
			    'id_name' => 'assigned_user_id',
			    'table' => 'users',
			  ),
			  'team_id' => 
			  array (
			    'name' => 'team_id',
			    'vname' => 'LBL_TEAM_ID',
			    'group' => 'team_name',
			    'reportable' => false,
			    'dbType' => 'id',
			    'type' => 'team_list',
			    'audited' => true,
			    'comment' => 'Team ID for the account',
			  ),
			  'team_set_id' => 
			  array (
			    'name' => 'team_set_id',
			    'rname' => 'id',
			    'id_name' => 'team_set_id',
			    'vname' => 'LBL_TEAM_SET_ID',
			    'type' => 'id',
			    'audited' => true,
			    'studio' => 'false',
			    'dbType' => 'id',
			  ),
			  'team_count' => 
			  array (
			    'name' => 'team_count',
			    'rname' => 'team_count',
			    'id_name' => 'team_id',
			    'vname' => 'LBL_TEAMS',
			    'join_name' => 'ts1',
			    'table' => 'teams',
			    'type' => 'relate',
			    'isnull' => 'true',
			    'module' => 'Teams',
			    'link' => 'team_count_link',
			    'massupdate' => false,
			    'dbType' => 'int',
			    'source' => 'non-db',
			    'importable' => 'false',
			    'reportable' => false,
			    'duplicate_merge' => 'disabled',
			    'studio' => 'false',
			    'hideacl' => true,
			  ),
			  'team_name' => 
			  array (
			    'name' => 'team_name',
			    'db_concat_fields' => 
			    array (
			      0 => 'name',
			      1 => 'name_2',
			    ),
			    'sort_on' => 'tj.name',
			    'join_name' => 'tj',
			    'rname' => 'name',
			    'id_name' => 'team_id',
			    'vname' => 'LBL_TEAMS',
			    'type' => 'relate',
			    'table' => 'teams',
			    'isnull' => 'true',
			    'module' => 'Teams',
			    'link' => 'team_link',
			    'massupdate' => false,
			    'dbType' => 'varchar',
			    'source' => 'non-db',
			    'len' => 36,
			    'custom_type' => 'teamset',
			  ),
			  'team_link' => 
			  array (
			    'name' => 'team_link',
			    'type' => 'link',
			    'relationship' => 'opportunities_team',
			    'vname' => 'LBL_TEAMS_LINK',
			    'link_type' => 'one',
			    'module' => 'Teams',
			    'bean_name' => 'Team',
			    'source' => 'non-db',
			    'duplicate_merge' => 'disabled',
			    'studio' => 'false',
			  ),
			  'team_count_link' => 
			  array (
			    'name' => 'team_count_link',
			    'type' => 'link',
			    'relationship' => 'opportunities_team_count_relationship',
			    'link_type' => 'one',
			    'module' => 'Teams',
			    'bean_name' => 'TeamSet',
			    'source' => 'non-db',
			    'duplicate_merge' => 'disabled',
			    'reportable' => false,
			    'studio' => 'false',
			  ),
			  'teams' => 
			  array (
			    'name' => 'teams',
			    'type' => 'link',
			    'relationship' => 'opportunities_teams',
			    'bean_filter_field' => 'team_set_id',
			    'rhs_key_override' => true,
			    'source' => 'non-db',
			    'vname' => 'LBL_TEAMS',
			    'link_class' => 'TeamSetLink',
			    'link_file' => 'modules/Teams/TeamSetLink.php',
			    'studio' => 'false',
			    'reportable' => false,
			  ),
			  'opportunity_type' => 
			  array (
			    'name' => 'opportunity_type',
			    'vname' => 'LBL_TYPE',
			    'type' => 'enum',
			    'options' => 'opportunity_type_dom',
			    'len' => '255',
			    'audited' => true,
			    'comment' => 'Type of opportunity (ex: Existing, New)',
			    'merge_filter' => 'enabled',
			  ),
			  'account_name' => 
			  array (
			    'name' => 'account_name',
			    'rname' => 'name',
			    'id_name' => 'account_id',
			    'vname' => 'LBL_ACCOUNT_NAME',
			    'type' => 'relate',
			    'table' => 'accounts',
			    'join_name' => 'accounts',
			    'isnull' => 'true',
			    'module' => 'Accounts',
			    'dbType' => 'varchar',
			    'link' => 'accounts',
			    'len' => '255',
			    'source' => 'non-db',
			    'unified_search' => true,
			    'required' => true,
			    'importable' => 'required',
			  ),
			  'account_id' => 
			  array (
			    'name' => 'account_id',
			    'vname' => 'LBL_ACCOUNT_ID',
			    'type' => 'id',
			    'source' => 'non-db',
			    'audited' => true,
			  ),
			  'campaign_id' => 
			  array (
			    'name' => 'campaign_id',
			    'comment' => 'Campaign that generated lead',
			    'vname' => 'LBL_CAMPAIGN_ID',
			    'rname' => 'id',
			    'type' => 'id',
			    'dbType' => 'id',
			    'table' => 'campaigns',
			    'isnull' => 'true',
			    'module' => 'Campaigns',
			    'reportable' => false,
			    'massupdate' => false,
			    'duplicate_merge' => 'disabled',
			  ),
			  'campaign_name' => 
			  array (
			    'name' => 'campaign_name',
			    'rname' => 'name',
			    'id_name' => 'campaign_id',
			    'vname' => 'LBL_CAMPAIGN',
			    'type' => 'relate',
			    'link' => 'campaign_opportunities',
			    'isnull' => 'true',
			    'table' => 'campaigns',
			    'module' => 'Campaigns',
			    'source' => 'non-db',
			  ),
			  'campaign_opportunities' => 
			  array (
			    'name' => 'campaign_opportunities',
			    'type' => 'link',
			    'vname' => 'LBL_CAMPAIGN_OPPORTUNITY',
			    'relationship' => 'campaign_opportunities',
			    'source' => 'non-db',
			  ),
			  'lead_source' => 
			  array (
			    'name' => 'lead_source',
			    'vname' => 'LBL_LEAD_SOURCE',
			    'type' => 'enum',
			    'options' => 'lead_source_dom',
			    'len' => '50',
			    'comment' => 'Source of the opportunity',
			    'merge_filter' => 'enabled',
			  ),
			  'amount' => 
			  array (
			    'name' => 'amount',
			    'vname' => 'LBL_AMOUNT',
			    'type' => 'currency',
			    'dbType' => 'double',
			    'comment' => 'Unconverted amount of the opportunity',
			    'duplicate_merge' => 'disabled',
			    'importable' => 'required',
			    'required' => true,
			    'options' => 'numeric_range_search_dom',
			    'enable_range_search' => '1',
			  ),
			  'amount_usdollar' => 
			  array (
			    'name' => 'amount_usdollar',
			    'vname' => 'LBL_AMOUNT_USDOLLAR',
			    'type' => 'currency',
			    'group' => 'amount',
			    'dbType' => 'double',
			    'disable_num_format' => true,
			    'audited' => true,
			    'comment' => 'Formatted amount of the opportunity',
			  ),
			  'currency_id' => 
			  array (
			    'name' => 'currency_id',
			    'type' => 'id',
			    'group' => 'currency_id',
			    'vname' => 'LBL_CURRENCY',
			    'function' => 
			    array (
			      'name' => 'getCurrencyDropDown',
			      'returns' => 'html',
			    ),
			    'reportable' => false,
			    'comment' => 'Currency used for display purposes',
			  ),
			  'currency_name' => 
			  array (
			    'name' => 'currency_name',
			    'rname' => 'name',
			    'id_name' => 'currency_id',
			    'vname' => 'LBL_CURRENCY_NAME',
			    'type' => 'relate',
			    'isnull' => 'true',
			    'table' => 'currencies',
			    'module' => 'Currencies',
			    'source' => 'non-db',
			    'function' => 
			    array (
			      'name' => 'getCurrencyNameDropDown',
			      'returns' => 'html',
			    ),
			    'studio' => 'false',
			    'duplicate_merge' => 'disabled',
			  ),
			  'currency_symbol' => 
			  array (
			    'name' => 'currency_symbol',
			    'rname' => 'symbol',
			    'id_name' => 'currency_id',
			    'vname' => 'LBL_CURRENCY_SYMBOL',
			    'type' => 'relate',
			    'isnull' => 'true',
			    'table' => 'currencies',
			    'module' => 'Currencies',
			    'source' => 'non-db',
			    'function' => 
			    array (
			      'name' => 'getCurrencySymbolDropDown',
			      'returns' => 'html',
			    ),
			    'studio' => 'false',
			    'duplicate_merge' => 'disabled',
			  ),
			  'date_closed' => 
			  array (
			    'name' => 'date_closed',
			    'vname' => 'LBL_DATE_CLOSED',
			    'type' => 'date',
			    'audited' => true,
			    'comment' => 'Expected or actual date the oppportunity will close',
			    'importable' => 'required',
			    'required' => true,
			    'enable_range_search' => '1',
			    'options' => 'date_range_search_dom',
			  ),
			  'next_step' => 
			  array (
			    'name' => 'next_step',
			    'vname' => 'LBL_NEXT_STEP',
			    'type' => 'varchar',
			    'len' => '100',
			    'comment' => 'The next step in the sales process',
			    'merge_filter' => 'enabled',
			  ),
			  'sales_stage' => 
			  array (
			    'name' => 'sales_stage',
			    'vname' => 'LBL_SALES_STAGE',
			    'type' => 'enum',
			    'options' => 'sales_stage_dom',
			    'len' => '255',
			    'audited' => true,
			    'comment' => 'Indication of progression towards closure',
			    'merge_filter' => 'enabled',
			    'importable' => 'required',
			    'required' => true,
			  ),
			  'probability' => 
			  array (
			    'name' => 'probability',
			    'vname' => 'LBL_PROBABILITY',
			    'type' => 'int',
			    'dbType' => 'double',
			    'audited' => true,
			    'comment' => 'The probability of closure',
			    'validation' => 
			    array (
			      'type' => 'range',
			      'min' => 0,
			      'max' => 100,
			    ),
			    'merge_filter' => 'enabled',
			  ),
			  'accounts' => 
			  array (
			    'name' => 'accounts',
			    'type' => 'link',
			    'relationship' => 'accounts_opportunities',
			    'source' => 'non-db',
			    'link_type' => 'one',
			    'module' => 'Accounts',
			    'bean_name' => 'Account',
			    'vname' => 'LBL_ACCOUNTS',
			  ),
			  'contacts' => 
			  array (
			    'name' => 'contacts',
			    'type' => 'link',
			    'relationship' => 'opportunities_contacts',
			    'source' => 'non-db',
			    'module' => 'Contacts',
			    'bean_name' => 'Contact',
			    'rel_fields' => 
			    array (
			      'contact_role' => 
			      array (
			        'type' => 'enum',
			        'options' => 'opportunity_relationship_type_dom',
			      ),
			    ),
			    'vname' => 'LBL_CONTACTS',
			  ),
			  'tasks' => 
			  array (
			    'name' => 'tasks',
			    'type' => 'link',
			    'relationship' => 'opportunity_tasks',
			    'source' => 'non-db',
			    'vname' => 'LBL_TASKS',
			  ),
			  'notes' => 
			  array (
			    'name' => 'notes',
			    'type' => 'link',
			    'relationship' => 'opportunity_notes',
			    'source' => 'non-db',
			    'vname' => 'LBL_NOTES',
			  ),
			  'meetings' => 
			  array (
			    'name' => 'meetings',
			    'type' => 'link',
			    'relationship' => 'opportunity_meetings',
			    'source' => 'non-db',
			    'vname' => 'LBL_MEETINGS',
			  ),
			  'calls' => 
			  array (
			    'name' => 'calls',
			    'type' => 'link',
			    'relationship' => 'opportunity_calls',
			    'source' => 'non-db',
			    'vname' => 'LBL_CALLS',
			  ),
			  'emails' => 
			  array (
			    'name' => 'emails',
			    'type' => 'link',
			    'relationship' => 'emails_opportunities_rel',
			    'source' => 'non-db',
			    'vname' => 'LBL_EMAILS',
			  ),
			  'documents' => 
			  array (
			    'name' => 'documents',
			    'type' => 'link',
			    'relationship' => 'documents_opportunities',
			    'source' => 'non-db',
			    'vname' => 'LBL_DOCUMENTS_SUBPANEL_TITLE',
			  ),
			  'quotes' => 
			  array (
			    'name' => 'quotes',
			    'type' => 'link',
			    'relationship' => 'quotes_opportunities',
			    'source' => 'non-db',
			    'vname' => 'LBL_QUOTES',
			  ),
			  'project' => 
			  array (
			    'name' => 'project',
			    'type' => 'link',
			    'relationship' => 'projects_opportunities',
			    'source' => 'non-db',
			    'vname' => 'LBL_PROJECTS',
			  ),
			  'leads' => 
			  array (
			    'name' => 'leads',
			    'type' => 'link',
			    'relationship' => 'opportunity_leads',
			    'source' => 'non-db',
			    'vname' => 'LBL_LEADS',
			  ),
			  'campaigns' => 
			  array (
			    'name' => 'campaigns',
			    'type' => 'link',
			    'relationship' => 'opportunities_campaign',
			    'module' => 'CampaignLog',
			    'bean_name' => 'CampaignLog',
			    'source' => 'non-db',
			    'vname' => 'LBL_CAMPAIGNS',
			  ),
			  'campaign_link' => 
			  array (
			    'name' => 'campaign_link',
			    'type' => 'link',
			    'relationship' => 'opportunities_campaign',
			    'vname' => 'LBL_CAMPAIGNS',
			    'link_type' => 'one',
			    'module' => 'Campaigns',
			    'bean_name' => 'Campaign',
			    'source' => 'non-db',
			  ),
			  'currencies' => 
			  array (
			    'name' => 'currencies',
			    'type' => 'link',
			    'relationship' => 'opportunity_currencies',
			    'source' => 'non-db',
			    'vname' => 'LBL_CURRENCIES',
			  ),
			  'contracts' => 
			  array (
			    'name' => 'contracts',
			    'type' => 'link',
			    'vname' => 'LBL_CONTRACTS',
			    'relationship' => 'contracts_opportunities',
			    'source' => 'non-db',
			  ),
			  'customdate_c' => 
			  array (
			    'options' => 'date_range_search_dom',
			    'enforced' => 'false',
			    'dependency' => '',
			    'enable_range_search' => '1',
			    'required' => false,
			    'source' => 'custom_fields',
			    'name' => 'customdate_c',
			    'vname' => 'LBL_CUSTOMDATE',
			    'type' => 'date',
			    'massupdate' => '0',
			    'default' => NULL,
			    'comments' => '',
			    'help' => '',
			    'importable' => 'true',
			    'duplicate_merge' => 'disabled',
			    'duplicate_merge_dom_value' => '0',
			    'audited' => false,
			    'reportable' => true,
			    'calculated' => false,
			    'size' => '20',
			    'id' => 'Opportunitiescustomdate_c',
			    'custom_module' => 'Opportunities',
			  ),
			  'customdatetime_c' => 
			  array (
			    'enforced' => 'false',
			    'dependency' => '',
			    'required' => false,
			    'source' => 'custom_fields',
			    'name' => 'customdatetime_c',
			    'vname' => 'LBL_CUSTOMDATETIME',
			    'type' => 'datetimecombo',
			    'massupdate' => '0',
			    'default' => NULL,
			    'comments' => '',
			    'help' => '',
			    'importable' => 'true',
			    'duplicate_merge' => 'disabled',
			    'duplicate_merge_dom_value' => '0',
			    'audited' => false,
			    'reportable' => true,
			    'calculated' => false,
			    'size' => '20',
			    'enable_range_search' => false,
			    'dbType' => 'datetime',
			    'id' => 'Opportunitiescustomdatetime_c',
			    'custom_module' => 'Opportunities',
			  ),
);
}