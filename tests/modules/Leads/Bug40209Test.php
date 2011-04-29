<?php

class Bug40209Test extends Sugar_PHPUnit_Framework_TestCase
{
    var $user;
    var $account;
    var $lead;
    var $contact;

    public function setUp()
    {
        //create user
        $this->user = $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();

        //create account
        $this->account = new Account();
        $this->account->name = 'bug40209 account '.date('Y-m-d-H-i-s');
        $this->account->save();

        //create contact
        $this->contact = new Contact();
        $this->lead = SugarTestLeadUtilities::createLead();

    }
    
    public function tearDown()
    {
        //delete records created from db
        $GLOBALS['db']->query("DELETE FROM accounts WHERE id= '{$this->account->id}'");
        $GLOBALS['db']->query("DELETE FROM leads WHERE id= '{$this->lead->id}'");
        $GLOBALS['db']->query("DELETE FROM contacts WHERE id= '{$this->contact->id}'");
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();

        //unset values
        unset($GLOBALS['current_user']);
        unset($this->user);
        unset($this->account);
        unset($this->contact);
    }
    


    //run test to make sure accounts related to leads record are copied over to contact recor during conversion (bug 40209)
    public function testConvertAccountCopied(){
        //there will be output from display function, so call ob_start to trap it
        ob_start();

        //set the request parameters and convert the lead
        $_REQUEST['module'] = 'Leads';
        $_REQUEST['action'] = 'ConvertLead';
        $_REQUEST['record'] = $this->lead->id;
        $_REQUEST['handle'] = 'save';
        $_REQUEST['selectedAccount'] = $this->account->id;

        //require view and call display class so that convert functionality is called
        require_once('modules/Leads/views/view.convertlead.php');
        $vc = new ViewConvertLead();
        $vc->display();

        //retrieve the lead again to make sure we have the latest converted lead in memory
        $this->lead->retrieve($this->lead->id);

        //retrieve the new contact id from the conversion
        $contact_id = $this->lead->contact_id;

        //throw error if contact id was not retrieved and exit test
        $this->assertTrue(!empty($contact_id), "contact id was not created during conversion process.  An error has ocurred, aborting rest of test.");
        if (empty($contact_id)){
            return;
        }

        //make sure the new contact has the account related and that it matches the lead account
        $this->contact->retrieve($contact_id);
        $this->assertTrue($this->contact->account_id == $this->lead->account_id, "Account id from converted lead does not match the new contact account id, there was an error during conversion.");
        $output = ob_get_clean();
    }
}