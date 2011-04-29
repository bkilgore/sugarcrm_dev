<?php
require_once('modules/UpgradeWizard/uw_utils.php');		

class UWUtilsTest extends Sugar_PHPUnit_Framework_TestCase  {

var $meeting;	
var $call;
var $original_current_user;

function setUp() 
{
	global $db, $timedate, $current_user;
	
	
	$this->original_current_user = $current_user;
	$user = new User();
	$user->retrieve('1');
	$current_user = $user;
	
	if($db->dbType != 'mysql')
	{
		$this->markTestSkipped('Skipping for non-mysql dbs');
	}	
	
	$this->meeting = SugarTestMeetingUtilities::createMeeting();
	$date_start = $timedate->nowDb();
	$this->meeting->date_start = $date_start;
	$this->meeting->duration_hours = 2;
	$this->meeting->duration_minutes = 30; 
	$this->meeting->save();
	
	$sql = "UPDATE meetings SET date_end = '{$date_start}' WHERE id = '{$this->meeting->id}'";
	$db->query($sql);
	
	$this->call = SugarTestCallUtilities::createCall();
	$date_start = $timedate->nowDb();
	$this->call->date_start = $date_start;
	$this->call->duration_hours = 2;
	$this->call->duration_minutes = 30; 
	$this->call->save();	
	
	$sql = "UPDATE calls SET date_end = '{$date_start}' WHERE id = '{$this->call->id}'";
	$db->query($sql);	
}

function tearDown() {
	global $db, $current_user;
    SugarTestMeetingUtilities::removeAllCreatedMeetings();	
	SugarTestCallUtilities::removeAllCreatedCalls();	
    
	$this->meeting = null;
	$this->call = null;
	
	$meetingsSql = "UPDATE meetings SET date_end = date_add(date_start, INTERVAL - CONCAT(duration_hours, ':', duration_minutes) HOUR_MINUTE)";
	$callsSql = "UPDATE calls SET date_end = date_add(date_start, INTERVAL - CONCAT(duration_hours, ':', duration_minutes) HOUR_MINUTE)";	
	
	$db->query($meetingsSql);
	$db->query($callsSql);
	
	$current_user = $this->original_current_user;
}

function testUpgradeDateTimeFields() {		

	upgradeDateTimeFields();

	global $db;
	$query = "SELECT date_start, date_end FROM meetings WHERE id = '{$this->meeting->id}'";
	$result = $db->query($query);
	$row = $db->fetchByAssoc($result);
	$start_time = strtotime($row['date_start']);
	$end_time = strtotime($row['date_end']);
	$this->assertEquals(($end_time - $start_time), (2.5 * 60 * 60), 'Assert that date_end in meetings table has been properly converted');	
	
	$query = "SELECT date_start, date_end FROM calls WHERE id = '{$this->call->id}'";
	$result = $db->query($query);
	$row = $db->fetchByAssoc($result);
	$start_time = strtotime($row['date_start']);
	$end_time = strtotime($row['date_end']);
	$this->assertEquals(($end_time - $start_time), (2.5 * 60 * 60), 'Assert that date_end in calls table has been properly converted');	
}


}

?>