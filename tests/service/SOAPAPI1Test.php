<?php
require_once('include/nusoap/nusoap.php');
/**
 * This class is meant to test everything SOAP
 *
 */
class SOAPAPI1Test extends Sugar_PHPUnit_Framework_TestCase
{
	public $_user = null;
	public $_contact = null;
	public $_meeting = null;
	public $_soapClient = null;
	public $_session = null;
	public $_userUtils = null;
	public $_sessionId = '';

    /**
     * Create test user
     *
     */
	public function setUp() 
    {
     	$this->_soapClient = new nusoapclient($GLOBALS['sugar_config']['site_url'].'/soap.php',false,false,false,false,false,60,60);
        $this->_setupTestUser();
        $this->_setupTestContact();
        $this->_meeting = SugarTestMeetingUtilities::createMeeting();
    }

    /**
     * Remove anything that was used during this test
     *
     */
    public function tearDown() 
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        $this->_user = null;
        SugarTestContactUtilities::removeAllCreatedContacts();
        SugarTestContactUtilities::removeCreatedContactsUsersRelationships();
        $this->_contact = null;
        SugarTestMeetingUtilities::removeAllCreatedMeetings();
         SugarTestMeetingUtilities::removeMeetingContacts();
        $this->_meeting = null;
    }

	/**
	 * Ensure we can create a session on the server.
	 *
	 */
    public function testCanLogin()
    {
		$result = $this->_login();
    	$this->assertTrue(!empty($result['id']) && $result['id'] != -1, 
            'SOAP Session not created. Error ('.$result['error']['number'].'): '.$result['error']['name'].': '.$result['error']['description'].'. HTTP Response: '.$this->_soapClient->response);
    }
    
    public function testSearchContactByEmail()
    {
    	$result = $this->_soapClient->call('contact_by_email', array('user_name' => $this->_user->user_name, 'password' => $this->_user->user_hash, 'email_address' => $this->_contact->email1));
    	$this->assertTrue(!empty($result) && count($result) > 0, 'Incorrect number of results returned. HTTP Response: '.$this->_soapClient->response); 
    	$this->assertEquals($result[0]['name1'], $this->_contact->first_name, 'Incorrect result found'); 
    }
    
	public function testSearchByModule()
    {
		$modules = array('Contacts');
    	$result = $this->_soapClient->call('search_by_module', array('user_name' => $this->_user->user_name, 'password' => $this->_user->user_hash, 'search_string' => $this->_contact->email1, 'modules' => $modules, 'offset' => 0, 'max_results' => 10));
    	$this->assertTrue(!empty($result) && count($result['entry_list']) > 0, 'Incorrect number of results returned. HTTP Response: '.$this->_soapClient->response); 
    	$this->assertEquals($result['entry_list'][0]['name_value_list'][1]['name'], 'first_name' && $result['entry_list'][0]['name_value_list'][1]['value'] == $this->_contact->first_name, 'Incorrect result returned'); 
    }
    
	public function testSearchBy()
    {
        $this->markTestSkipped('SOAP call "search" is deprecated');
        
		$result = $this->_soapClient->call('search', array('user_name' => $this->_user->user_name, 'password' => $this->_user->user_hash, 'name' => $this->_contact->first_name));
    	$this->assertTrue(!empty($result) && count($result) > 0, "Incorrect number of results returned - Returned $result results. HTTP Response: ".$this->_soapClient->response); 
    	$this->assertEquals($result[0]['name1'], $this->_contact->first_name, "Contact First name does not match data returnd from SOAP_test"); 
    }
    
	public function testGetModifiedEntries()
    {
		$this->_login();
		$ids = array($this->_contact->id);
    	$result = $this->_soapClient->call('get_modified_entries', array('session' => $this->_sessionId, 'module_name' => 'Contacts', 'ids' => $ids, 'select_fields' => array()));
    	$decoded = base64_decode($result['result']);
    }
    
	public function testGetAttendeeList()
    {
    	$this->_login();
    	$this->_meeting->load_relationship('contacts');
    	$this->_meeting->contacts->add($this->_contact->id);
		$result = $this->_soapClient->call('get_attendee_list', array('session' => $this->_sessionId, 'module_name' => 'Meetings', 'id' => $this->_meeting->id));
    	$decoded = base64_decode($result['result']);
        $decoded = simplexml_load_string($decoded);
        $this->assertTrue(!empty($result['result']), 'Results not returned. HTTP Response: '.$this->_soapClient->response); 
		$this->assertEquals(urldecode($decoded->attendee->first_name), $this->_contact->first_name, 'Incorrect Result returned expected: '.$this->_contact->first_name.' Found: '.urldecode($decoded->attendee->first_name)); 
	}
    
    public function testSyncGetModifiedRelationships()
    {
    	$this->_login();
    	$ids = array($this->_contact->id);
    	$yesterday = date('Y-m-d', strtotime('last year')); 
    	$tomorrow = date('Y-m-d', mktime(0, 0, 0, date("m") , date("d") + 1, date("Y"))); 
    	$result = $this->_soapClient->call('sync_get_modified_relationships', array('session' => $this->_sessionId, 'module_name' => 'Users', 'related_module' => 'Contacts', 'from_date' => $yesterday, 'to_date' => $tomorrow, 'offset' => 0, 'max_results' => 10, 'deleted' => 0, 'module_id' => $this->_user->id, 'select_fields'=> array(), 'ids' => $ids, 'relationship_name' => 'contacts_users', 'deletion_date' => $yesterday, 'php_serialize' => 0));
    	$this->assertTrue(!empty($result['entry_list']), 'Results not returned. HTTP Response: '.$this->_soapClient->response); 
        $decoded = base64_decode($result['entry_list']);
    	$decoded = simplexml_load_string($decoded);
        if (isset($decoded->item[0]) ) {
            $this->assertEquals(urlencode($decoded->item->name_value_list->name_value[1]->name), 'contact_id', "testSyncGetModifiedRelationships - could not retrieve contact_id column name");
            $this->assertEquals(urlencode($decoded->item->name_value_list->name_value[1]->value), $this->_contact->id, "vlue of contact id is not same as returned via SOAP");
        }
    }
    
    /**********************************
     * HELPER PUBLIC FUNCTIONS
     **********************************/
    
    /**
     * Attempt to login to the soap server
     *
     * @return $set_entry_result - this should contain an id and error.  The id corresponds
     * to the session_id.
     */
    private function _login(){
    	$result = $this->_soapClient->call('login',
            array('user_auth' => 
                array('user_name' => $this->_user->user_name,
                    'password' => $this->_user->user_hash, 
                    'version' => '.01'), 
                'application_name' => 'SoapTest')
            );
        $this->_sessionId = $result['id'];
		return $result;
    }
    
    /**
     * Create a test user
     *
     */
	private function _setupTestUser() {
        $this->_user = SugarTestUserUtilities::createAnonymousUser();
        $this->_user->status = 'Active';
         $this->_user->is_admin = 1;
        $this->_user->save();
        $GLOBALS['current_user'] = $this->_user;
    }
    
	private function _setupTestContact() {
        $this->_contact = SugarTestContactUtilities::createContact();
        $this->_contact->contacts_users_id = $this->_user->id;
        $this->_contact->save();
    }
    
}
?>
