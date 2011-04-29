<?php
require_once 'include/MassUpdate.php';
require_once 'include/dir_inc.php';

class Bug43468Test extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    	$this->useOutputBuffering = false;
		$GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
		$GLOBALS['current_user']->setPreference('datef', "Y/m/d");
		$GLOBALS['app_strings'] = return_application_language($GLOBALS['current_language']);
	}

    public function tearDown()
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
        unset($GLOBALS['app_strings']);
    }
    
    public function testGetMassUpdateForm()
    {
    	global $current_user, $timedate;
    	$expected_dateformat = $timedate->get_cal_date_format();
    	
    	$_REQUEST['module'] = 'Calls';
        $mass = new MassUpdate();
        $call = new Call();
        $call->fieldDefs['date_start']['massupdate'] = true;
        $mass->setSugarBean($call);
		$form_results = $mass->getMassUpdateForm();
		$found_match = false;
		if(preg_match('/daFormat\s+?\:\s+\"(.*?)\"/', $form_results, $matches))
		{
			$this->assertEquals($expected_dateformat, $matches[1], 'Assert that the daFormat set in Calendar widget is %Y/%m/%d');
			$found_match = true;
		}
		$this->assertEquals($found_match, true, 'Assert that the daFormat value was set');
    }
 
}
