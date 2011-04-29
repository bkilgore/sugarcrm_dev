<?php 

/**
 * @ticket 32487
 */
class GetNamePlusEmailAddressesForComposeTest extends Sugar_PHPUnit_Framework_TestCase
{
	public function setUp()
    {
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
	}

    public function tearDown()
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
    }
    
    public function testGetNamePlusEmailAddressesForCompose()
	{    
	    $account = SugarTestAccountUtilities::createAccount();
        
	    $email = new Email;
	    $this->assertEquals(
	        "{$account->name} <{$account->email1}>",
	        $email->getNamePlusEmailAddressesForCompose('Accounts',array($account->id))
	        );
	    
	    SugarTestAccountUtilities::removeAllCreatedAccounts();
    }
    
    public function testGetNamePlusEmailAddressesForComposeMultipleIds()
	{    
	    $account1 = SugarTestAccountUtilities::createAccount();
	    $account2 = SugarTestAccountUtilities::createAccount();
	    $account3 = SugarTestAccountUtilities::createAccount();
        
	    $email = new Email;
	    $addressString = $email->getNamePlusEmailAddressesForCompose('Accounts',array($account1->id,$account2->id,$account3->id));
	    $this->assertContains("{$account1->name} <{$account1->email1}>",$addressString);
	    $this->assertContains("{$account2->name} <{$account2->email1}>",$addressString);
	    $this->assertContains("{$account3->name} <{$account3->email1}>",$addressString);
	    
	    SugarTestAccountUtilities::removeAllCreatedAccounts();
    }
    

	public function testGetNamePlusEmailAddressesForComposePersonModule()
	{    
	    $contact = SugarTestContactUtilities::createContact();
        
	    $email = new Email;
	    $this->assertEquals(
	        $GLOBALS['locale']->getLocaleFormattedName($contact->first_name, $contact->last_name, $contact->salutation, $contact->title) . " <{$contact->email1}>",
	        $email->getNamePlusEmailAddressesForCompose('Contacts',array($contact->id))
	        );
	    
	    SugarTestContactUtilities::removeAllCreatedContacts();
    }
    
    public function testGetNamePlusEmailAddressesForComposeUser()
	{    
	    $user = SugarTestUserUtilities::createAnonymousUser();
	    $user->email1 = 'foo@bar.com';
	    $user->save();
	    
	    $email = new Email;
	    $this->assertEquals(
	        $GLOBALS['locale']->getLocaleFormattedName($user->first_name, $user->last_name, '', $user->title) . " <{$user->email1}>",
	        $email->getNamePlusEmailAddressesForCompose('Users',array($user->id))
	        );
    }
}