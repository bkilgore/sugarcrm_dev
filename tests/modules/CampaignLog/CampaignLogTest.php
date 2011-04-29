<?php
require_once 'modules/Accounts/Account.php';
require_once 'modules/Campaigns/Campaign.php';
require_once 'modules/CampaignLog/CampaignLog.php';
require_once 'modules/CampaignTrackers/CampaignTracker.php';
require_once 'modules/Contacts/Contact.php';
require_once 'modules/Users/User.php';
require_once 'include/SugarEmailAddress/SugarEmailAddress.php';

class CampaignLogTest extends Sugar_PHPUnit_Framework_TestCase
{
	var $campaign_id = 'campaignforcamplogunittest';
	var $prospect_list_id = 'prospectlistforcamplogunittest';
	var $email_marketing_id = 'marketingforcamplogunittest';
	var $targetObjectArray = array('User','Contact','Lead','Prospect','Account','CampaignTracker');

	var $target_Prospect;
	var $target_Contact;
	var $target_User;
	var $target_Account;
	var $campaign_tracker;
	var	$campaign_log;

    
    public function setup()
    {
		global $current_user;	
		
		$current_user = SugarTestUserUtilities::createAnonymousUser();
		//for the purpose of this test, we need to create some records with fake campaign and prospect list data, 
		//however we do need to create some targets for the prospect list

		//create campaign tracker
		$ct = new CampaignTracker();
		$ct->tracker_name ='Campaign Log Unit Test Tracker';
		$ct->tracker_url = 'sugarcrm.com';
		$ct->campaign_id = $this->campaign_id;
		$ct->save();
		$this->campaign_tracker = $ct;

		
		//for each type, create an object and populate the campaignLog list
		foreach($this->targetObjectArray as $type){
			//skip campaign tracker
			if($type == 'CampaignTracker'){
				continue;
			}
				
			//create the new bean
			$bean = new $type();
			if ($type == 'Account'){
				$bean->name = 'CampLog Unit Test Account';
			}else{
				$bean->first_name = 'CampaignLog';
				$bean->last_name = 'Test '.$type;
			}		
			$type_obj = 'target_'.$type;
			$bean->save();
			$this->$type_obj = $bean;
			
			//save email
			$sea = new SugarEmailAddress();
			$id = $this->$type_obj->id;
			$module = $this->$type_obj->module_dir;
			$new_addrs=array();$primary='';$replyTo='';$invalid='';$optOut='';$in_workflow=false;
			$_REQUEST[$module.'_email_widget_id'] = 0;
			$_REQUEST[$module.'0emailAddress0'] = $type.'UnitTest@example.com';
			$_REQUEST[$module.'emailAddressPrimaryFlag'] = '0emailAddress0';
			$_REQUEST[$module.'emailAddressVerifiedFlag0'] = 'true';
			$_REQUEST[$module.'emailAddressVerifiedValue0'] = 'unitTest@sugarcrm.com';
			$requestVariablesSet = array('0emailAddress0','emailAddressPrimaryFlag','emailAddressVerifiedFlag0','emailAddressVerifiedValue0');
			$sea->save($id, $module, $new_addrs, $primary, $replyTo, $invalid, $optOut, $in_workflow);
			//unset email request values for next run
			foreach ($requestVariablesSet as $k)
				unset($_REQUEST[$k]);	

		
			//now create the campaign log
			$cl = new CampaignLog();
			$cl->campaign_id = $this->campaign_id;
			$cl->tracker_key = $ct->tracker_key;
			$cl->target_id = $bean->id;
			$cl->target_type = $bean->module_dir;
			$cl->activity_type = 'targeted';//options are targeted (user was sent an email), link (user clicked on link), removed (user opted out) and viewed (viewed)
			$cl->activity_date = date('Y-m-d H:i:s');
			$cl->related_id = 'somebogusemailid'.date('His'); // this means link will not really work, but we are not testing email
			$cl->related_type = 'Emails';
			$cl->list_id = $this->prospect_list_id;
			$cl->marketing_id = $this->email_marketing_id;
			$cl->save();
		}
		//keep last created campaign log bean to be used to call functions
		$this->campaign_log = $cl;

		
    }
    
    public function tearDown()
    {
		global $current_user;	
		//for each type, delete the object and it's email
		foreach($this->targetObjectArray as $type){
			//skip campaign tracker
			if($type == 'CampaignTracker'){
				continue;
			}
			//create string to reference bean by
			$type_obj = 'target_'.$type;

			//remove the email address and relationship			
			$query = 'delete from email_addresses where email_address = \''.$type.'UnitTest@example.com\'';
			$GLOBALS['db']->query($query);
			$query = 'delete from email_addr_bean_rel where bean_id = \''.$this->$type_obj->id.'\'';
			$GLOBALS['db']->query($query);			

			//remove the bean and delete record
			$this->$type_obj->deleted = 1;
			$this->$type_obj->save();
			$GLOBALS['db']->query('DELETE FROM '.$this->$type_obj->table_name.' WHERE id = \''.$this->$type_obj->id.'\' ');
			unset($this->$type_obj);
			
		}

		//delete the campaign logs and campaign tracker
		$GLOBALS['db']->query('DELETE FROM campaign_log WHERE campaign_id = \''.$this->campaign_id.'\' ');
		$GLOBALS['db']->query('DELETE FROM campaign_trkrs WHERE id = \''.$this->campaign_tracker->id.'\' ');
		unset($this->campaign_tracker);
        unset($this->campaign_log );SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
    }
	


	public function testGetListViewData(){
		global $current_user;
		$lvd = $this->campaign_log->get_list_view_data(); 

		//make sure the returned value is an array
		$this->assertTrue(is_array($lvd), 'CampaignLog->get_list_view_data should return an object of array type');

		//make sure some of the expected values exist
		$this->assertFalse(empty($lvd['CAMPAIGN_ID']), 'array element CAMPAIGN_ID is expected to exist when calling CampaignLog->get_list_view_data ');
		$this->assertFalse(empty($lvd['TARGET_ID']), 'array element TARGET_ID is expected to exist when calling CampaignLog->get_list_view_data ');
	}

	public function testGetRelatedName(){
		global $current_user,$locale;	

		foreach($this->targetObjectArray as $type){
			//skip campaign tracker
			if($type == 'CampaignTracker'){
				continue;
			}
			//create string to reference bean by
			$type_obj = 'target_'.$type;
			
			//make sure the related name is coming in from the correct related type
			$related_name = $this->campaign_log->get_related_name($this->$type_obj->id,$this->$type_obj->module_dir);

			//make sure the returned name is formatted as expected
			if ($type == 'Account'){
				$this->assertSame($related_name, $this->$type_obj->name, 'name retrieved from campaign log does not match the expected name of '.$formatted_name.' for the related '.$type.' object');	
			}elseif ($type == 'User'){
				$formatted_name = $this->$type_obj->id.$this->$type_obj->module_dir;
				$this->assertSame($related_name, $formatted_name, 'name retrieved from campaign log does not match the expected formatted name of '.$formatted_name.' for the related '.$type.' object');	
			}else{
				$bean->first_name = 'CampaignLog';
				$bean->last_name = 'Test '.$type;
				$formatted_name = $locale->getLocaleFormattedName($this->$type_obj->first_name, $this->$type_obj->last_name);
				$this->assertSame($related_name, $formatted_name, 'name retrieved from campaign log does not match the expected formatted name of '.$formatted_name.' for the related '.$type.' object');	
			}		
			
		}
		
	}

	public function testRetrieveEmailAddress(){
		global $current_user;	
		foreach($this->targetObjectArray as $type){
			//skip campaign tracker
			if($type == 'CampaignTracker'){
				continue;
			}
			//create string to reference bean by
			$type_obj = 'target_'.$type;

			$eastring = $this->campaign_log->retrieve_email_address($this->$type_obj->id);
			$this->assertSame($eastring, $type.'UnitTest@example.com', 'email retrieved from campaign log object type '.$type.'does not match the expected email of '.$type.'UnitTest@example.com');	
		}
		
	}
    	

}