<?php 
require_once('include/nusoap/nusoap.php');
require_once('modules/Users/User.php');


/**
 * @group bug40250
 */
class Bug40250Test extends Sugar_PHPUnit_Framework_TestCase
{
	public $_user = null;
	public $_soapClient = null;
	public $_session = null;
	public $_sessionId = '';
    /**
     * Create test user
     *
     */
	public function setUp() 
    {
    	
        $this->_setupTestUser();
		$this->_soapClient = new nusoapclient($GLOBALS['sugar_config']['site_url'].'/soap.php',false,false,false,false,false,60,60);
    	$this->_login();
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
    }	
    
    public function testRetrieveUsersList() {
    	//First retrieve the users count (should be at least 1)
		$countArr  = $this->_soapClient->call('get_entries_count',array('session'=>$this->_sessionId,'module_name'=>'Users','query'=>" users.status = 'active' ",0));
    	$count = $countArr['result_count'];
    	$this->assertTrue($count > 1, 'no users were retrieved so the test user was not set up correctly');
    	
		//now retrieve the list of users
    	$usersArr =   $this->_soapClient->call('get_entry_list',array('session'=>$this->_sessionId,'module_name'=>'Users','query'=>" users.status = 'active' ", 'user_name','0'  ,'select_field'=>array('user_name'),100,0));
    	$usersCount = $usersArr['result_count'];
    	
    	//the count from both functions should be the same
    	$this->assertTrue($count == $usersCount ,'count is not the same which means that the 2 calls are generating different results.');
    	
		//logout
    	$result =  $this->_soapClient->call('logout',array('session' => $this->_sessionId));
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
    public function _login(){
		global $current_user;  	
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
	public function _setupTestUser() {
        $this->_user = SugarTestUserUtilities::createAnonymousUser();
        $this->_user->status = 'Active';
        $this->_user->is_admin = 1;
        $this->_user->save();
    }
    

        
    /**
     * Remove user created for test
     *
     */
	public function _tearDownTestUser() {
       SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
    }
	
}
?>