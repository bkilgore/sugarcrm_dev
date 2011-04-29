<?php
require_once 'tests/service/SOAPTestCase.php';
require_once 'tests/SugarTestAccountUtilities.php';
require_once 'modules/Emails/Email.php';
/**
 * @ticket 22504
 */
class Bug22504Test extends SOAPTestCase
{
    /**
     * Create test account
     *
     */
	public function setUp()
    {
    	$this->_soapURL = $GLOBALS['sugar_config']['site_url'].'/service/v3_1/soap.php';
    	$GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
    	$this->acc = SugarTestAccountUtilities::createAccount();
		parent::setUp();
    }

    public function tearDown()
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
        if(!empty($this->email_id)) {
            $GLOBALS['db']->query("DELETE FROM emails WHERE id='{$this->email_id}'");
            $GLOBALS['db']->query("DELETE FROM emails_beans WHERE email_id='{$this->email_id}'");
            $GLOBALS['db']->query("DELETE FROM emails_text WHERE email_id='{$this->email_id}'");
            $GLOBALS['db']->query("DELETE FROM emails_email_addr_rel WHERE email_id='{$this->email_id}'");
        }
        SugarTestAccountUtilities::removeAllCreatedAccounts();
        parent::tearDown();
    }

    public function testEmailImport()
    {
    	$this->_login();
    	$nv = array(
    	    'from_addr' => 'test@test.com',
    	    'parent_type' => 'Accounts',
    	    'parent_id' => $this->acc->id,
    	    'description' => 'test',
    	    'name' => 'Test Subject',
    	);
		$result = $this->_soapClient->call('set_entry',array('session'=>$this->_sessionId,"module_name" => 'Emails', 'name_value_list' => $nv));
		$this->email_id = $result['id'];
        $email = new Email();
        $email->retrieve($this->email_id );
        $email->load_relationship('accounts');
        $acc = $email->accounts->get();
        $this->assertEquals($this->acc->id, $acc[0]);
    }
}
