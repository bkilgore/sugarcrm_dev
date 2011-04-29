<?php
require_once('modules/MySettings/StoreQuery.php');

class Bug42915Test extends Sugar_PHPUnit_Framework_TestCase 
{
	var $previousCurrentUser;
	var $saved_search_id;
	
    public function setUp() 
    {
    	global $current_user;
    	$this->previousCurrentUser = $current_user;
    	$this->saved_search_id = md5(gmmktime());
        $this->useOutputBuffering = false;        
        $current_user = SugarTestUserUtilities::createAnonymousUser();
        $current_user->setPreference('num_grp_sep', ',', 0, 'global');
        $current_user->setPreference('dec_sep', '.', 0, 'global');
        $current_user->save();
        
        //Force reset on dec_sep and num_grp_sep because the dec_sep and num_grp_sep values are stored as static variables
	    get_number_seperators(true);  
        
        $GLOBALS['db']->query("INSERT INTO saved_search (id, name, search_module, deleted, date_entered, date_modified, assigned_user_id, contents) VALUES ('" . $this->saved_search_id . "', 'Bug42738', 'Opportunities', 0, '2011-03-10 17:05:27', '2011-03-10 17:05:27', '".$GLOBALS["current_user"]->id."', 'YTozNjp7czoxMzoic2VhcmNoRm9ybVRhYiI7czoxNToiYWR2YW5jZWRfc2VhcmNoIjtzOjU6InF1ZXJ5IjtzOjQ6InRydWUiO3M6MTM6Im5hbWVfYWR2YW5jZWQiO3M6MDoiIjtzOjIxOiJhY2NvdW50X25hbWVfYWR2YW5jZWQiO3M6MDoiIjtzOjI4OiJhbW91bnRfYWR2YW5jZWRfcmFuZ2VfY2hvaWNlIjtzOjc6ImJldHdlZW4iO3M6MjE6InJhbmdlX2Ftb3VudF9hZHZhbmNlZCI7czowOiIiO3M6Mjc6InN0YXJ0X3JhbmdlX2Ftb3VudF9hZHZhbmNlZCI7ZDo5NTAwMDtzOjI1OiJlbmRfcmFuZ2VfYW1vdW50X2FkdmFuY2VkIjtkOjQ5NTAwO3M6MjA6ImRhdGVfY2xvc2VkX2FkdmFuY2VkIjtzOjA6IiI7czoxODoibmV4dF9zdGVwX2FkdmFuY2VkIjtzOjA6IiI7czo0MzoidXBkYXRlX2ZpZWxkc190ZWFtX25hbWVfYWR2YW5jZWRfY29sbGVjdGlvbiI7czowOiIiO3M6MzI6InRlYW1fbmFtZV9hZHZhbmNlZF9uZXdfb25fdXBkYXRlIjtzOjU6ImZhbHNlIjtzOjMxOiJ0ZWFtX25hbWVfYWR2YW5jZWRfYWxsb3dfdXBkYXRlIjtzOjA6IiI7czozNToidGVhbV9uYW1lX2FkdmFuY2VkX2FsbG93ZWRfdG9fY2hlY2siO3M6NToiZmFsc2UiO3M6MzE6InRlYW1fbmFtZV9hZHZhbmNlZF9jb2xsZWN0aW9uXzAiO3M6MDoiIjtzOjM0OiJpZF90ZWFtX25hbWVfYWR2YW5jZWRfY29sbGVjdGlvbl8wIjtzOjA6IiI7czoyMzoidGVhbV9uYW1lX2FkdmFuY2VkX3R5cGUiO3M6MzoiYW55IjtzOjIzOiJmYXZvcml0ZXNfb25seV9hZHZhbmNlZCI7czoxOiIwIjtzOjk6InNob3dTU0RJViI7czoyOiJubyI7czoxMzoic2VhcmNoX21vZHVsZSI7czoxMzoiT3Bwb3J0dW5pdGllcyI7czoxOToic2F2ZWRfc2VhcmNoX2FjdGlvbiI7czo0OiJzYXZlIjtzOjE0OiJkaXNwbGF5Q29sdW1ucyI7czo4OToiTkFNRXxBQ0NPVU5UX05BTUV8U0FMRVNfU1RBR0V8QU1PVU5UX1VTRE9MTEFSfERBVEVfQ0xPU0VEfEFTU0lHTkVEX1VTRVJfTkFNRXxEQVRFX0VOVEVSRUQiO3M6ODoiaGlkZVRhYnMiO3M6OTM6Ik9QUE9SVFVOSVRZX1RZUEV8TEVBRF9TT1VSQ0V8TkVYVF9TVEVQfFBST0JBQklMSVRZfENSRUFURURfQllfTkFNRXxURUFNX05BTUV8TU9ESUZJRURfQllfTkFNRSI7czo3OiJvcmRlckJ5IjtzOjQ6Ik5BTUUiO3M6OToic29ydE9yZGVyIjtzOjM6IkFTQyI7czoxNjoic3VnYXJfdXNlcl90aGVtZSI7czo1OiJTdWdhciI7czoxMzoiTW9kdWxlQnVpbGRlciI7czoxNToiaGVscEhpZGRlbj10cnVlIjtzOjEzOiJDb250YWN0c19kaXZzIjtzOjEwOiJxdW90ZXNfdj0jIjtzOjIyOiJzdWdhcl90aGVtZV9nbV9jdXJyZW50IjtzOjM6IkFsbCI7czoxNToiZ2xvYmFsTGlua3NPcGVuIjtzOjQ6InRydWUiO3M6Mjc6IlNRTGl0ZU1hbmFnZXJfY3VycmVudExhbmd1ZSI7czoxOiIyIjtzOjQ2OiJzdGFydF9yYW5nZV9hbW91bnRfYWR2YW5jZWRfdW5mb3JtYXR0ZWRfbnVtYmVyIjtiOjE7czo0Mzoic3RhcnRfcmFuZ2VfYW1vdW50X2FkdmFuY2VkX2N1cnJlbmN5X3N5bWJvbCI7czoxOiIkIjtzOjQ0OiJlbmRfcmFuZ2VfYW1vdW50X2FkdmFuY2VkX3VuZm9ybWF0dGVkX251bWJlciI7YjoxO3M6NDE6ImVuZF9yYW5nZV9hbW91bnRfYWR2YW5jZWRfY3VycmVuY3lfc3ltYm9sIjtzOjE6IiQiO3M6ODoiYWR2YW5jZWQiO2I6MTt9')");
    }
    
    public function tearDown() 
    {
        $GLOBALS['db']->query("DELETE FROM saved_search where id = '{$this->saved_search_id}'"); 
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        global $current_user;
        $current_user = $this->previousCurrentUser;
    }

    
    public function testSaveNumericFields() 
    {
    	global $current_user;        
		require_once('modules/SavedSearch/SavedSearch.php');
	    $focus = new SavedSearch();	
	    $focus->retrieve($this->saved_search_id);
		$_REQUEST = unserialize(base64_decode($focus->contents));
		$_REQUEST['start_range_amount_advanced'] = '$9,500.00';
        $_REQUEST['end_range_amount_advanced'] = '$49,500.00';
		
		$mockBean = new Bug42915MockOpportunity();
		$focus->handleSave('', false, '', $this->saved_search_id, $mockBean);
		
		//Now retrieve what we have saved and test
		$focus->retrieveSavedSearch($this->saved_search_id);
		$formatted_data = $focus->contents;

		$this->assertEquals(9500, $formatted_data['start_range_amount_advanced'], "Assert that value is unformatted value 9500");
		$this->assertEquals(49500, $formatted_data['end_range_amount_advanced'], "Assert that value is unformatted value 49500");
		
		$focus->populateRequest();
		$this->assertEquals('$9,500.00', $_REQUEST['start_range_amount_advanced'], "Assert that value is formatted value $9,500.00");
		$this->assertEquals('$49,500.00', $_REQUEST['end_range_amount_advanced'], "Assert that value is formatted value $49,500.00");
		
        $current_user->setPreference('num_grp_sep', '.');
        $current_user->setPreference('dec_sep', ',');
        $current_user->save();
        //Force reset on dec_sep and num_grp_sep because the dec_sep and num_grp_sep values are stored as static variables
	    get_number_seperators(true);  
        
        $focus = new SavedSearch();
        $focus->retrieveSavedSearch($this->saved_search_id);
        $focus->populateRequest();
		$this->assertEquals('$9.500,00', $_REQUEST['start_range_amount_advanced'], "Assert that value is formatted value $9,500.00");
		$this->assertEquals('$49.500,00', $_REQUEST['end_range_amount_advanced'], "Assert that value is formatted value $49,500.00");
    
        //Okay so now what happens if they don't specify currency or separator or decimal values?
        $_REQUEST['start_range_amount_advanced'] = '9500';
        $_REQUEST['end_range_amount_advanced'] = '49500';
        
        //Well then the populated values should be unformatted!
        $focus->handleSave('', false, '', $this->saved_search_id, $mockBean);
        $focus->retrieveSavedSearch($this->saved_search_id);
        $focus->populateRequest();
		$this->assertEquals(9500, $_REQUEST['start_range_amount_advanced'], "Assert that value is unformatted value 9500");
		$this->assertEquals(49500, $_REQUEST['end_range_amount_advanced'], "Assert that value is unformatted value 49500");
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
			    'start_range_amount_advanced' => '$9,500.00',
			    'end_range_amount_advanced' => '$45,900.00',
			    'date_closed_advanced_range_choice' => '=',
			    'range_date_closed_advanced' => '',
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
    	$this->assertEquals($storedSearch['start_range_amount_advanced'], 9500.00, 'Assert that start range amount $9,500.00 was saved unformatted as 9500.00');
   		$this->assertEquals($storedSearch['end_range_amount_advanced'], 45900.00, 'Assert that end range amount $45,900.00 was saved unformatted as 45900.00');
    	
    	
    	//Test that value is converted to user's numer formatting
    	unset($_REQUEST['start_range_amount_advanced']);
    	unset($_REQUEST['end_range_amount_advanced']);
    	$storeQuery->loadQuery('Opportunities');
        $storeQuery->populateRequest();
        $this->assertTrue(isset($_REQUEST['start_range_amount_advanced']), 'Assert that the start_range_amount_advanced field was correctly populated');
    	$this->assertEquals('$9,500.00', $_REQUEST['start_range_amount_advanced'], 'Assert that start_range_amount_advanced value was converted to $9,500.00');
        $this->assertTrue(isset($_REQUEST['end_range_amount_advanced']), 'Assert that the end_range_amount_advanced field was correctly populated');
    	$this->assertEquals('$45,900.00', $_REQUEST['end_range_amount_advanced'], 'Assert that end_range_amount_advanced value was converted to $45,900.00');
    	
    	//Now say the user changes his number preferences and switches back to this StoredQuery
        $current_user->setPreference('num_grp_sep', '.');
        $current_user->setPreference('dec_sep', ',');
        $current_user->save();
        //Force reset on dec_sep and num_grp_sep because the dec_sep and num_grp_sep values are stored as static variables
	    get_number_seperators(true);  

        //Now when we reload this store query, the $_REQUEST array should be populated with new user date preference
    	unset($_REQUEST['start_range_amount_advanced']);
    	unset($_REQUEST['end_range_amount_advanced']);
    	$storeQuery->loadQuery('Opportunities');
        $storeQuery->populateRequest();
        $this->assertTrue(isset($_REQUEST['start_range_amount_advanced']), 'Assert that the start_range_amount_advanced field was correctly populated');
    	$this->assertEquals('$9.500,00', $_REQUEST['start_range_amount_advanced'], 'Assert that start_range_amount_advanced value was converted to $9.500,00');
        $this->assertTrue(isset($_REQUEST['end_range_amount_advanced']), 'Assert that the end_range_amount_advanced field was correctly populated');
    	$this->assertEquals('$45.900,00', $_REQUEST['end_range_amount_advanced'], 'Assert that end_range_amount_advanced value was converted to $45.900,00');

        //Okay so now what happens if they don't specify currency or separator or decimal values?
        $_REQUEST['start_range_amount_advanced'] = 9500;
        $_REQUEST['end_range_amount_advanced'] = 45900;    
        
    	$storeQuery->saveFromRequest('Opportunities');
		$storeQuery->loadQuery('Opportunities');
        $storeQuery->populateRequest();
        $this->assertTrue(isset($_REQUEST['start_range_amount_advanced']), 'Assert that the start_range_amount_advanced field was correctly populated');
    	$this->assertEquals(9500, $_REQUEST['start_range_amount_advanced'], 'Assert that start_range_amount_advanced value remained as is (9500)');
        $this->assertTrue(isset($_REQUEST['end_range_amount_advanced']), 'Assert that the end_range_amount_advanced field was correctly populated');
    	$this->assertEquals(45900, $_REQUEST['end_range_amount_advanced'], 'Assert that end_range_amount_advanced value remained as is (45900)');
    	
    }
    
}


class Bug42915MockOpportunity extends Opportunity
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