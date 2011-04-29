<?php 
require_once('modules/Meetings/Meeting.php');

class MeetingTest extends Sugar_PHPUnit_Framework_TestCase
{
	var $meeting = null;
	
	public function setUp()
    {
        global $current_user, $currentModule ;
		$mod_strings = return_module_language($GLOBALS['current_language'], "Meetings");
		$current_user = SugarTestUserUtilities::createAnonymousUser();

		$meeting = new Meeting();
		$meeting->id = uniqid();
        $meeting->name = 'Test Meeting';
        $meeting->save();
		$this->meeting = $meeting;
	}
	
    public function tearDown()
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
        unset($GLOBALS['mod_strings']);
        
        $GLOBALS['db']->query("DELETE FROM meetings WHERE id = '{$this->meeting->id}'");
        unset($this->meeting);
    }
	
	function testMeetingTypeSaveDefault() {
		// Assert doc type default is 'Sugar'
    	$this->assertEquals($this->meeting->type, 'Sugar');
	}

    function testMeetingTypeSaveDefaultInDb() {
        $query = "SELECT * FROM meetings WHERE id = '{$this->meeting->id}'";
        $result = $GLOBALS['db']->query($query);
    	while($row = $GLOBALS['db']->fetchByAssoc($result))
		// Assert doc type default is 'Sugar'
    	$this->assertEquals($row['type'], 'Sugar');
	}

}
?>