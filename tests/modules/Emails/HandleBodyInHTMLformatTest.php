<?php 
require_once('modules/Emails/Email.php');
require_once('include/SugarPHPMailer.php');

/**
 * Test cases for Bug 30591
 */
class handleBodyInHTMLformatTest extends Sugar_PHPUnit_Framework_TestCase
{
	private $sugarMailer;
	private $email;
	
	public function setUp()
	{
	    global $current_user;
	    
	    $current_user = SugarTestUserUtilities::createAnonymousUser();
		$this->sugarMailer = new SugarPHPMailer();
		$this->email = new Email();
	}
	
	public function tearDown()
	{
		SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
	}
	
	public function testHandleBodyInHtmlformat ()
	{
		$emailBodyInHtml = <<<EOQ
Check to see if &quot; &lt; &gt; &#039; was translated
to " < > '
EOQ;

		$emailBodyInHtmlResult = <<<EOQ
Check to see if " < > ' was translated
to " < > '
EOQ;
		$this->email->description_html = $emailBodyInHtml;
		$this->assertNotEquals($this->sugarMailer->Body, $emailBodyInHtmlResult);
		$this->email->handleBodyInHTMLformat($this->sugarMailer);
		$this->assertEquals($this->sugarMailer->Body, $emailBodyInHtmlResult);
	}
}
?>