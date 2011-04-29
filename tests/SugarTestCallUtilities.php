<?php
require_once 'modules/Calls/Call.php';

class SugarTestCallUtilities
{
    private static $_createdCalls = array();

    private function __construct() {}

    public static function createCall() 
    {
        $time = mt_rand();
    	$name = 'Call';
    	$call = new Call();
        $call->name = $name . $time;
        $call->save();
        self::$_createdCalls[] = $call;
        return $call;
    }

    public static function removeAllCreatedCalls() 
    {
        $call_ids = self::getCreatedCallIds();
        $GLOBALS['db']->query('DELETE FROM calls WHERE id IN (\'' . implode("', '", $call_ids) . '\')');
    }
    
    public static function removeCallContacts(){
    	$call_ids = self::getCreatedCallIds();
        $GLOBALS['db']->query('DELETE FROM calls_contacts WHERE call_id IN (\'' . implode("', '", $call_ids) . '\')');
    }
    
    public static function getCreatedCallIds() 
    {
        $call_ids = array();
        foreach (self::$_createdCalls as $call) {
            $call_ids[] = $call->id;
        }
        return $call_ids;
    }
}
?>