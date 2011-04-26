<?php
require_once('modules/Trackers/TrackerManager.php');

class TrackerReportsUsageTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        SugarTestTrackerUtility::setup();
        
        $trackerManager = TrackerManager::getInstance();
        $trackerManager->unPause();
        
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
        
        //$tracker_sessions_monitor = $trackerManager->getMonitor('tracker_sessions');
        $monitor = $trackerManager->getMonitor('tracker');
        $monitor->setEnabled(true);
        $monitor->setValue('module_name', 'Contacts');
        $monitor->setValue('item_id', '10909d69-2b55-094d-ba89-47b23d3121dd');
        $monitor->setValue('item_summary', 'Foo');
        $monitor->setValue('date_modified', gmdate($GLOBALS['timedate']->get_db_date_time_format()), strtotime("-1 day")+5000);
        $monitor->setValue('action', 'index');
        $monitor->setValue('session_id', 'test_session');
        $monitor->setValue('user_id', $GLOBALS['current_user']->id);
        $trackerManager->save();
        
        $monitor->setValue('module_name', 'Contacts');
        $monitor->setValue('item_id', '10909d69-2b55-094d-ba89-47b23d3121dd');
        $monitor->setValue('item_summary', 'Foo');
        $monitor->setValue('date_modified', gmdate($GLOBALS['timedate']->get_db_date_time_format(), strtotime("-1 week")+5000));
        $monitor->setValue('action', 'index');
        $monitor->setValue('session_id', 'test_session');        
        $monitor->setValue('user_id', $GLOBALS['current_user']->id);
        $trackerManager->save();
       
        $monitor->setValue('module_name', 'Contacts');
        $monitor->setValue('item_id', '10909d69-2b55-094d-ba89-47b23d3121dd');
        $monitor->setValue('item_summary', 'Foo');
        $monitor->setValue('date_modified', gmdate($GLOBALS['timedate']->get_db_date_time_format(), strtotime("-1 month")+5000));
        $monitor->setValue('action', 'index');
        $monitor->setValue('session_id', 'test_session');
        $monitor->setValue('user_id', $GLOBALS['current_user']->id);            
        $trackerManager->save();

        $beanList = array();
        $beanFiles = array();
        require('include/modules.php');
        $GLOBALS['beanList'] = $beanList;
        $GLOBALS['beanFiles'] = $beanFiles;
    }
    
    public function tearDown()
    {
        $query = "DELETE FROM tracker WHERE session_id = 'test_session'";
        $GLOBALS['db']->query($query);
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        SugarTestTrackerUtility::restore();
        
        unset($GLOBALS['beanList']);
        unset($GLOBALS['beanFiles']);
    }
    
    public function testUsageMetricsDay()
    {
        $query = "SELECT module_name, item_id, item_summary, date_modified from tracker where session_id = 'test_session' and user_id = '{$GLOBALS['current_user']->id}' and date_modified > ";
        $query .= db_convert("'". gmdate($GLOBALS['timedate']->get_db_date_time_format(), strtotime("-1 day")) ."'" ,"datetime");
        $count = $GLOBALS['db']->getRowCount($GLOBALS['db']->query($query));
        $this->assertEquals($count,1);
    }
    
    public function testUsageMetricsWeek()
    {
        $query = "SELECT module_name, item_id, item_summary, date_modified from tracker where session_id = 'test_session' and user_id = '{$GLOBALS['current_user']->id}' and date_modified > ";
        $query .= db_convert("'". gmdate($GLOBALS['timedate']->get_db_date_time_format(), strtotime("-1 week")) ."'" ,"datetime");
        $count = $GLOBALS['db']->getRowCount($GLOBALS['db']->query($query));
        $this->assertEquals($count,2);
    }
    
    public function testUsageMetricsMonth()
    {
        $query = "SELECT module_name, item_id, item_summary, date_modified from tracker where session_id = 'test_session' and user_id = '{$GLOBALS['current_user']->id}' and date_modified > ";
        $query .= db_convert("'". gmdate($GLOBALS['timedate']->get_db_date_time_format(), strtotime("-1 month")) ."'" ,"datetime");
        $count = $GLOBALS['db']->getRowCount($GLOBALS['db']->query($query));
        $this->assertEquals($count,3);   	
    }
}

?>
