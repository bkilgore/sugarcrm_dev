<?php
require_once 'modules/Leads/Lead.php';

class SugarTestLeadUtilities
{
    private static $_createdLeads = array();

    private function __construct() {}

    public static function createLead($id = '') 
    {
        $time = mt_rand();
    	$first_name = 'SugarLeadFirst';
    	$last_name = 'SugarLeadLast';
    	$email1 = 'lead@sugar.com';
    	$lead = new Lead();
        $lead->first_name = $first_name . $time;
        $lead->last_name = $last_name ;
        $lead->email1 = 'lead@'. $time. 'sugar.com';
        if(!empty($id))
        {
            $lead->new_with_id = true;
            $lead->id = $id;
        }
        $lead->save();
        self::$_createdLeads[] = $lead;
        return $lead;
    }

    public static function setCreatedLead($lead_ids) {
    	foreach($lead_ids as $lead_id) {
    		$lead = new Lead();
    		$lead->id = $lead_id;
        	self::$_createdLeads[] = $lead;
    	} // foreach
    } // fn
    
    public static function removeAllCreatedLeads() 
    {
        $lead_ids = self::getCreatedLeadIds();
        $GLOBALS['db']->query('DELETE FROM leads WHERE id IN (\'' . implode("', '", $lead_ids) . '\')');
    }
    
    public static function removeCreatedLeadsUsersRelationships(){
    	$lead_ids = self::getCreatedLeadIds();
        $GLOBALS['db']->query('DELETE FROM leads_users WHERE lead_id IN (\'' . implode("', '", $lead_ids) . '\')');
    }
    
    public static function getCreatedLeadIds() 
    {
        $lead_ids = array();
        foreach (self::$_createdLeads as $lead) {
            $lead_ids[] = $lead->id;
        }
        return $lead_ids;
    }
}
?>