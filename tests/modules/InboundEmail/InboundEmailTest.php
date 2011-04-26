<?php
require_once('modules/InboundEmail/InboundEmail.php');
require_once('include/TimeDate.php');
/**
 * This class is meant to test everything for InboundEmail
 *
 */
class InboundEmailTest extends Sugar_PHPUnit_Framework_TestCase
{
	var $_user = null;
    /**
     * Create test user
     *
     */
	public function setUp() 
	{
    	global $inbound_account_id;
    	
    	// the email server is down, so this test doesn't work
    	$this->markTestSkipped('Connection to mail server is down.');
    	
    	if (empty($inbound_account_id)) {
        	$this->_setupTestUser();
        	$this->_createInboundAccount();
    	} // IF
    }

    function _createInboundAccount() { 
    	global $inbound_account_id, $current_user; 	
		$stored_options = array();
		$stored_options['from_name'] = "UnitTest";
		$stored_options['from_addr'] = "ajaysales@sugarcrm.com";
		$stored_options['reply_to_name'] = "UnitTest";
		$stored_options['reply_to_addr'] = "ajaysales@sugarcrm.com";
		$stored_options['only_since'] = false;
		$stored_options['filter_domain'] = "";
    	$stored_options['trashFolder'] = "INBOX.Trash";
		$stored_options['leaveMessagesOnMailServer'] = 1;
		
    	$useSsl = false;
		$focus = new InboundEmail();
    	$focus->name = "Ajay Sales Personal Unittest";
    	$focus->email_user = "ajaysales@sugarcrm.com";
    	$focus->email_password = "f00f004";
    	$focus->server_url = "mail.sugarcrm.com";
    	$focus->protocol = "imap";
    	$focus->mailbox = "INBOX";
    	$focus->port = "143";
    	
		$optimum = $focus->findOptimumSettings($useSsl);
    	
		$focus->service = $optimum['serial'];
		$focus->is_personal = 1;
		$focus->status = "Active";
		$focus->mailbox_type = 'pick';
		$focus->group_id = $current_user->id;
		$teamId = User::getPrivateTeam($current_user->id);
		$focus->team_id = $teamId;
		$focus->team_set_id = $focus->getTeamSetIdForTeams($teamId);
		$focus->stored_options = base64_encode(serialize($stored_options));
		$inbound_account_id = $focus->save();
    } // fn
    
	/**
	 * retrieve an inbound account.
	 *
	 */
    function _retrieveInboundAccount() {
    	global $inbound_account_id;
		$focus = new InboundEmail();
		$focus->retrieve($inbound_account_id);
		$result = $focus->connectMailserver();
		if ( $result == 'false' )
		    $this->markTestSkipped('Connection to mail server is down.');
		return $focus;
    } // fn
    
	/**
	 * Create a folder in inbound account.
	 *
	 */
    function testCreateFolder() {
    	$focus = $this->_retrieveInboundAccount();
    	$status = $focus->saveNewFolder("unittest1", "INBOX");
    	$this->assertTrue($status,"INBOX.unittest1 can not be created = " . $status);
    } // fn
    
	/**
	 * Delete a folder in inbound account.
	 *
	 */
    function testDeleteFolder() {
    	global $inbound_account_id;
		$focus = $this->_retrieveInboundAccount();
		$statusArray = $focus->deleteFolder("INBOX.unittest1");
    	if ($statusArray['status']) {
    		$this->_tearDownInboundAccount($inbound_account_id);
        	unset($inbound_account_id);
    	}
    	$this->assertTrue($statusArray['status'],"INBOX.unittest1 can not be deleted");
    }
    
	public function testIdWithSingleQuotesCanBeInsertedIntoCacheTable()
    {
        $focus = new InboundEmail();
        $focus->id = create_guid();
        $focus->setCacheTimestamp("John's House");
        
        $r = $focus->db->getOne('select id from inbound_email_cache_ts where id = \''.
            $focus->db->quote("{$focus->id}_John's House").'\'');
        
        $this->assertTrue($r !== false,"Could not find id \"{$focus->id}_John's House\" in inbound_email_cache_ts");
        
        $focus->db->query('delete from inbound_email_cache_ts where id = \''.
            $focus->db->quote("{$focus->id}_John's House").'\'');
    }
    
    /**
     * Remove anything that was used during this test
     *
     */
    function tearDown() {
    	global $inbound_account_id;
        $this->_tearDownTestUser();
    }

	/**
	 * Delete this inbound account.
	 *
	 */
    function _tearDownInboundAccount($inbound_account_id) {
		$focus = new InboundEmail();
		$focus->retrieve($inbound_account_id);
		$focus->mark_deleted($inbound_account_id);
		$focus->db->query("delete from inbound_email WHERE id = '{$inbound_account_id}'");
    }
    
    /**
     * Create a test user
     *
     */
	function _setupTestUser() {
		global $current_user;
        $this->_user = SugarTestUserUtilities::createAnonymousUser();
        $GLOBALS['current_user'] = $this->_user;
        $current_user = $this->_user;
        $this->_user->status = 'Active';
        $this->_user->is_admin = 1;
        $this->_user->save();
    }
        
    /**
     * Remove user created for test
     *
     */
	function _tearDownTestUser() {
       SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
       unset($GLOBALS['current_user']);
    }
    
}
?>