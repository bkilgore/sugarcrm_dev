<?php
require_once 'include/javascript/jsAlerts.php';

class JSAlertsTest extends Sugar_PHPUnit_Framework_TestCase
{
    var $beans;

    public function setUp()
    {
        global $current_user;
        $this->beans = array();
        $this->old_user = $current_user;
        $current_user = $this->_user = SugarTestUserUtilities::createAnonymousUser();
        $GLOBALS['app_list_strings'] = return_app_list_strings_language($GLOBALS['current_language']);
        $GLOBALS['app_strings'] = return_application_language($GLOBALS['current_language']);
    }
    
    public function tearDown()
    {
        foreach($this->beans as $bean) {
            $bean->mark_deleted($bean->id);
        }
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();

		unset($GLOBALS['app_list_strings']);
		unset($GLOBALS['current_user']);
		unset($GLOBALS['app_strings']);
    }

    protected function createNewMeeting()
    {
        $m = new Meeting();
        $m->name = "40541TestMeeting";
        $m->date_start = gmdate($GLOBALS['timedate']->get_db_date_time_format(), time() + 3000);
        $m->duration_hours = 0;
        $m->duration_minutes = 15;
        $m->reminder_time = 60;
        $m->reminder_checked = true;
        $m->save();
        $m->load_relationship("users");
        $m->users->add($this->_user->id);
        $this->beans[] = $m;
        return $m;
    }

    public function testGetAlertsForUser()
    {

        global $app_list_strings;
            $app_list_strings['reminder_max_time'] = 5000;
        $m = $this->createNewMeeting();
        $alerts = new jsAlerts();
        $script = $alerts->getScript();
        $this->assertRegExp("/addAlert.*\"{$m->name}\"/", $script);
    }

    public function testGetDeclinedAlertsForUser()
    {

        global $app_list_strings;
            $app_list_strings['reminder_max_time'] = 5000;
        $m = $this->createNewMeeting();
        //Decline the meeting
        $query = "UPDATE meetings_users SET deleted = 0, accept_status = 'decline' " .
    			 "WHERE meeting_id = '$m->id' AND user_id = '{$this->_user->id}'";
    	$m->db->query($query);
        $alerts = new jsAlerts();
        $script = $alerts->getScript();
        $this->assertNotRegExp("/addAlert.*\"{$m->name}\"/", $script);
    }
}
