<?php
class SugarTestTrackerUtility
{
    private static $_trackerSettings = array();
    private static $_monitorId = '';
    
    private function __construct() {}
    
    public static function setup()
    {
        require('modules/Trackers/config.php');
        foreach($tracker_config as $entry) {
            if(isset($entry['bean'])) {
                $GLOBALS['tracker_' . $entry['name']] = false;
            } //if
        } //foreach
        
        $result = $GLOBALS['db']->query("SELECT category, name, value from config WHERE category = 'tracker' and name != 'prune_interval'");
        while($row = $GLOBALS['db']->fetchByAssoc($result)){
            self::$_trackerSettings[$row['name']] = $row['value'];
            $GLOBALS['db']->query("DELETE FROM config WHERE category = 'tracker' AND name = '{$row['name']}'");
        }
    }
    
    public static function restore()
    {
        foreach(self::$_trackerSettings as $name=>$value) {
            $GLOBALS['db']->query("INSERT INTO config (category, name, value) VALUES ('tracker', '{$name}', '{$value}')");
        }
    }
    
    public static function insertTrackerEntry($bean, $action)
    {
        require_once('modules/Trackers/TrackerManager.php');
        $trackerManager = TrackerManager::getInstance();
        $timeStamp = gmdate($GLOBALS['timedate']->get_db_date_time_format());
        $_REQUEST['action'] = $action;
        if($monitor = $trackerManager->getMonitor('tracker'))
        {
            $monitor->setValue('action', $action);
            $monitor->setValue('user_id', $GLOBALS['current_user']->id);
            $monitor->setValue('module_name', $bean->module_dir);
            $monitor->setValue('date_modified', $timeStamp);
            $monitor->setValue('visible', (($action == 'detailview') || ($action == 'editview')
                                            || ($action == 'wirelessdetail') || ($action == 'wirelessedit')
                                            ) ? 1 : 0);

            if (!empty($bean->id))
            {
                $monitor->setValue('item_id', $bean->id);
                $monitor->setValue('item_summary', $bean->get_summary_text());
            }

            //If visible is true, but there is no bean, do not track (invalid/unauthorized reference)
            //Also, do not track save actions where there is no bean id
            if($monitor->visible && empty($bean->id))
            {
               $trackerManager->unsetMonitor($monitor);
               return false;
            }
            $trackerManager->saveMonitor($monitor, true, true);
            if(empty(self::$_monitorId))
            {
                self::$_monitorId = $monitor->monitor_id;
            }
        }
    }
    
    public static function removeAllTrackerEntries()
    {
        if(!empty(self::$_monitorId))
        {
            $GLOBALS['db']->query("DELETE FROM tracker WHERE monitor_id = '".self::$_monitorId."'");
        }
    }
}
?>
