<?php 
require_once('include/OutboundEmail/OutboundEmail.php');

/**
 * @group bug32487
 */
class Bug32487Test extends Sugar_PHPUnit_Framework_TestCase
{
	var $ib = null;
	var $outbound_id = null;
	
	public function setUp()
    {
        global $current_user, $currentModule ;
		$mod_strings = return_module_language($GLOBALS['current_language'], "Contacts");
		$current_user = SugarTestUserUtilities::createAnonymousUser();
		$this->outbound_id = uniqid();
		$time = date('Y-m-d H:i:s');

		$ib = new InboundEmail();
		$ib->is_personal = 1;
		$ib->name = "Test";
		$ib->port = 3309;
		$ib->mailbox = 'empty';
		$ib->created_by = $current_user->id;
		$ib->email_password = "pass";
		$ib->protocol = 'IMAP';
		$stored_options['outbound_email'] = $this->outbound_id;
	    $ib->stored_options = base64_encode(serialize($stored_options));
	    $ib->save();
	    $this->ib = $ib;
	}

    public function tearDown()
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
        
        $GLOBALS['db']->query("DELETE FROM inbound_email WHERE id= '{$this->ib->id}'");
        
        unset($this->ib);
    }
    
	function testGetAssoicatedInboundAccountForOutboundAccounts(){
	    global $current_user;
	    $ob = new OutboundEmail();
	    $ob->id = $this->outbound_id;
		
	    $results = $ob->getAssociatedInboundAccounts($current_user);
    	$this->assertEquals($this->ib->id, $results[0], "Could not retrieve the inbound mail accounts for an outbound account");
    	
    	$obEmpty = new OutboundEmail();
    	$obEmpty->id = uniqid();
		
	    $empty_results = $obEmpty->getAssociatedInboundAccounts($current_user);
    	$this->assertEquals(0, count($empty_results), "Outbound email account returned for unspecified/empty inbound mail account.");
    }
}
?>