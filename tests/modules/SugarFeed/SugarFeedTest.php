<?php 
require_once('modules/SugarFeed/SugarFeed.php');

class SugarFeedTest extends Sugar_PHPUnit_Framework_TestCase
{
	public function testEncodeUrls()
	{
		$message = "This test contains a url here http://www.sugarcrm.com and not here amazon.com";
        $text = SugarFeed::parseMessage($message);
        $this->assertContains("<a href='http://www.sugarcrm.com' target='_blank'>http://www.sugarcrm.com</a>",$text, "Url encoded incorrectly");
        $this->assertNotContains("http://amazon.com", $text, "Second link should not be encoded");
	}

    public function testGetUrls()
	{
		$message = "This test contains a url here http://www.sugarcrm.com and not here http://amazon.com";
        $result = getUrls($message);
        $this->assertContains('http://www.sugarcrm.com', $result,' Could not find first url.');
        $this->assertContains('http://amazon.com', $result,' Could not find second url.');
     }
}
?>