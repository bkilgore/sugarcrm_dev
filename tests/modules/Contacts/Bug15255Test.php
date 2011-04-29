<?php 
require_once('modules/Contacts/Contact.php');
require_once('modules/Accounts/Account.php');

class Bug15255Test extends Sugar_PHPUnit_Framework_TestCase
{
	var $c = null;
	var $a = null;
	var $ac_id = null;
	
	public function setUp()
    {
        global $current_user, $currentModule ;
		$mod_strings = return_module_language($GLOBALS['current_language'], "Contacts");
		$current_user = SugarTestUserUtilities::createAnonymousUser();
		$unid = uniqid();
		$time = date('Y-m-d H:i:s');

		$contact = new Contact();
		$contact->id = 'c_'.$unid;
        $contact->first_name = 'testfirst';
        $contact->last_name = 'testlast';
        $contact->new_with_id = true;
        $contact->disable_custom_fields = true;
        $contact->save();
		$this->c = $contact;
		
		$account = new Account();
		$account->id = 'a_'.$unid;
        $account->first_name = 'testfirst';
        $account->last_name = 'testlast';
        $account->assigned_user_id = 'SugarUser';
        $account->new_with_id = true;
        $account->disable_custom_fields = true;
        $account->save();
        $this->a = $account;
        
        $ac_id = 'ac_'.$unid;
        $this->ac_id = $ac_id;
		$GLOBALS['db']->query("insert into accounts_contacts (id , contact_id, account_id, date_modified, deleted) values ('{$ac_id}', '{$contact->id}', '{$account->id}', '$time', 0)");
	}

    public function tearDown()
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
        unset($GLOBALS['mod_strings']);
        
        $GLOBALS['db']->query("DELETE FROM contacts WHERE id= '{$this->c->id}'");
        $GLOBALS['db']->query("DELETE FROM accounts WHERE id = '{$this->a->id}'");
        $GLOBALS['db']->query("DELETE FROM accounts_contacts WHERE id = '{$this->ac_id}'");
        
        unset($this->a);
        unset($this->c);
        unset($this->ac_id);
    }
    
	function testFill_in_additional_detail_fields(){
		$locale = new Localization();
    	$this->c->fill_in_additional_detail_fields();
    	$localName = $locale->getLocaleFormattedName('testfirst', 'testlast');
    	$this->assertEquals($this->c->name, $localName);
    	//$this->assertEquals($this->c->name, 'testfirst testlast');
    }
}
?>