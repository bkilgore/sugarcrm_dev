<?php
require_once('include/nusoap/nusoap.php');

abstract class SOAPTestCase extends Sugar_PHPUnit_Framework_TestCase
{
	public $_user = null;
	public $_soapClient = null;
	public $_session = null;
	public $_sessionId = '';
    public $_soapURL = '';

    /**
     * Create test user
     *
     */
	public function setUp()
    {
        $beanList = array();
		$beanFiles = array();
		require('include/modules.php');
		$GLOBALS['beanList'] = $beanList;
		$GLOBALS['beanFiles'] = $beanFiles;
		
        $this->_soapClient = new nusoapclient($this->_soapURL,false,false,false,false,false,600,600);
        $this->_setupTestUser();
        parent::setUp();
    }

    /**
     * Remove anything that was used during this test
     *
     */
    public function tearDown()
    {
        $this->_tearDownTestUser();
        $this->_user = null;
        $this->_sessionId = '';
        
		unset($GLOBALS['beanList']);
		unset($GLOBALS['beanFiles']);
        parent::tearDown();
    }

    protected function _login()
    {
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

