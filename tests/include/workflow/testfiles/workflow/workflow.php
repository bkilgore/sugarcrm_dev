<?php

include_once("include/workflow/alert_utils.php");
include_once("include/workflow/action_utils.php");
include_once("include/workflow/time_utils.php");
include_once("include/workflow/trigger_utils.php");
//BEGIN WFLOW PLUGINS
include_once("include/workflow/custom_utils.php");
//END WFLOW PLUGINS
	class Accounts_workflow {
	function process_wflow_triggers(& $focus){
		include("custom/modules/Accounts/workflow/triggers_array.php");
		include("custom/modules/Accounts/workflow/alerts_array.php");
		include("custom/modules/Accounts/workflow/actions_array.php");
		include("custom/modules/Accounts/workflow/plugins_array.php");
		
 if(true){ 
 

	 //Frame Secondary 

	 $secondary_array = array(); 
	 //Secondary Triggers 

	global $triggeredWorkflows;
	if (!isset($triggeredWorkflows['e9fb6b5f_ae96_2c2c_c8cb_4c7354d98b09'])){
		$triggeredWorkflows['e9fb6b5f_ae96_2c2c_c8cb_4c7354d98b09'] = true;
		 unset($alertshell_array); 
		 process_workflow_actions($focus, $action_meta_array['Accounts0_action0']); 
 	}
 

	 //End Frame Secondary 

	 unset($secondary_array); 
 

 //End if trigger is true 
 } 


 if( ( !($focus->fetched_row['name'] ==  'Sugar' )) && 
 (isset($focus->name) && $focus->name ==  'Sugar')){ 
 

	 //Frame Secondary 

	 $secondary_array = array(); 
	 //Secondary Triggers 

	global $triggeredWorkflows;
	if (!isset($triggeredWorkflows['eaa0bd50_7aff_3ed6_f43c_4c7354ab1221'])){
		$triggeredWorkflows['eaa0bd50_7aff_3ed6_f43c_4c7354ab1221'] = true;
		 unset($alertshell_array); 
		 process_workflow_actions($focus, $action_meta_array['Accounts1_action0']); 
 	}
 

	 //End Frame Secondary 

	 unset($secondary_array); 
 

 //End if trigger is true 
 } 


	//end function process_wflow_triggers
	}
	
	//end class
	}

?>