<?php
require_once 'include/TimeDate.php';
require_once 'modules/Calendar/Calendar.php';
require_once 'modules/Meetings/Meeting.php';

/**
 * @group bug20626
 */
class Bug20626Test extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    	$GLOBALS['reload_vardefs'] = true;
        global $current_user;
		
        $current_user = SugarTestUserUtilities::createAnonymousUser();
	}

    public function tearDown()
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
        $GLOBALS['reload_vardefs'] = false;
    }
    
    public function testDateAndTimeShownInCalendarActivityAdditionalDetailsPopup()
    {
        global $timedate,$sugar_config,$DO_USER_TIME_OFFSET , $current_user;
		
        $DO_USER_TIME_OFFSET = true;
        $timedate = new TimeDate();
		
        $meeting = new Meeting();
        $format = $current_user->getUserDateTimePreferences();
        $meeting->date_start = $timedate->swap_formats("2006-12-23 11:00pm" , 'Y-m-d h:ia', $format['date'].' '.$format['time']);
        $meeting->time_start = "";
        $meeting->object_name = "Meeting";
        $meeting->duration_hours = 2;
        $ca = new CalendarActivity($meeting);
        $this->assertEquals($meeting->date_start , $ca->sugar_bean->date_start);
    }
}