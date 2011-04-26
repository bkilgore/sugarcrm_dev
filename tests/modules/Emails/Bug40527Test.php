<?php 
require_once('modules/Emails/Email.php');

class Bug40527Test extends Sugar_PHPUnit_Framework_TestCase
{
    private $contact;
    private $account;
    private $email;
    
	public function setUp()
    {
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
        $this->contact = SugarTestContactUtilities::createContact();
        $this->account = SugarTestAccountUtilities::createAccount();
        
        $override_data = array(
            'parent_type' => 'Accounts',
            'parent_id' => $this->account->id,
        );
        $this->email   = SugarTestEmailUtilities::createEmail('', $override_data);
	}

    public function tearDown()
    {
        SugarTestContactUtilities::removeAllCreatedContacts();
        SugarTestAccountUtilities::removeAllCreatedAccounts();
        SugarTestEmailUtilities::removeAllCreatedEmails();
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
    }
    
    public function testContactRelationship()
    {
        $this->assertTrue($this->email->parent_type == 'Accounts', "The email parent_type should be Accounts");
        $this->assertTrue($this->email->parent_id == $this->account->id, "The email parent_id should be SDizzle");
        
        $this->email->fill_in_additional_detail_fields();
        $this->assertTrue(empty($this->email->contact_id), "There should be no contact associated with the Email");
    }
}
