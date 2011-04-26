<?php
require_once 'include/vCard.php';

class vCardBug40629Test extends Sugar_PHPUnit_Framework_TestCase
{
    protected $account;
    
    public function setUp()
    {
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
        $this->account = SugarTestAccountUtilities::createAccount();
        $this->account->name = "SDizzle Inc";
        $this->account->save();
    }
    
    public function tearDown()
    {
        unset($GLOBALS['current_user']);
        SugarTestAccountUtilities::removeAllCreatedAccounts();
    }
    
    /**
     * @group bug40629
     */
	public function testImportedVcardAccountLink()
    {
        $filename  = dirname(__FILE__)."/SimpleVCard.vcf";
        
        $vcard = new vCard();
        $contact_id = $vcard->importVCard($filename,'Contacts');
        $contact_record = new Contact();
        $contact_record->retrieve($contact_id);
        
        $this->assertFalse(empty($contact_record->account_id), "Contact should have an account record associated");
        $GLOBALS['db']->query("delete from contacts where id = '{$contact_id}'");
        
        $vcard = new vCard();
        $lead_id = $vcard->importVCard($filename,'Leads');
        $lead_record = new Lead();
        $lead_record->retrieve($lead_id);
        
        $this->assertTrue(empty($lead_record->account_id), "Lead should not have an account record associated");
        $GLOBALS['db']->query("delete from leads where id = '{$lead_id}'");
    }
}