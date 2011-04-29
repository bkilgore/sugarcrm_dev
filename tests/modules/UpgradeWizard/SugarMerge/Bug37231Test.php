<?php
require_once 'include/dir_inc.php';

class Bug37231Test extends Sugar_PHPUnit_Framework_TestCase  {

var $merge;
var $has_dir;
var $modules;

function setUp() {
   $this->modules = array('Accounts', 'Opportunities');
   $this->has_dir = array();
   
   foreach($this->modules as $module) {
	   if(!file_exists("custom/modules/{$module}/metadata")){
		  mkdir_recursive("custom/modules/{$module}/metadata", true);
	   }
	   
	   if(file_exists("custom/modules/{$module}")) {
	   	  $this->has_dir[$module] = true;
	   }
	   
	   $files = array('detailviewdefs', 'editviewdefs', 'searchdefs', 'listviewdefs');
	   foreach($files as $file) {
	   	   if(file_exists("custom/modules/{$module}/metadata/{$file}")) {
		   	  copy("custom/modules/{$module}/metadata/{$file}.php", "custom/modules/{$module}/metadata/{$file}.php.bak");
		   }
		   
		   if(file_exists("custom/modules/{$module}/metadata/{$file}.php.suback.php")) {
		      copy("custom/modules/{$module}/metadata/{$file}.php.suback.php", "custom/modules/{$module}/metadata/{$file}.php.suback.bak");
		   }
		   
		   if(file_exists("tests/modules/UpgradeWizard/SugarMerge/siupgrade_metadata_files/custom/modules/{$module}/metadata/{$file}.php")) {
		   	  copy("tests/modules/UpgradeWizard/SugarMerge/siupgrade_metadata_files/custom/modules/{$module}/metadata/{$file}.php", "custom/modules/{$module}/metadata/{$file}.php");
		   }
	   } //foreach
   } //foreach
}


function tearDown() {

   foreach($this->modules as $module) {
	   if(!$this->has_dir[$module]) {
	   	  rmdir_recursive("custom/modules/{$module}");
	   }  else {
	   	   $files = array('detailviewdefs', 'editviewdefs', 'searchdefs', 'listviewdefs');
		   foreach($files as $file) {
		      if(file_exists("custom/modules/{$module}/metadata/{$file}.php.bak")) {
		      	 copy("custom/modules/{$module}/metadata/{$file}.php.bak", "custom/modules/{$module}/metadata/{$file}.php");
	             unlink("custom/modules/{$module}/metadata/{$file}.php.bak");
		      } else if(file_exists("custom/modules/{$module}/metadata/{$file}.php")) {
		      	 unlink("custom/modules/{$module}/metadata/{$file}.php");
		      }
		      
		   	  if(file_exists("custom/modules/{$module}/metadata/{$module}.php.suback.bak")) {
		      	 copy("custom/modules/{$module}/metadata/{$file}.php.suback.bak", "custom/modules/{$module}/metadata/{$file}.php.suback.php");
	             unlink("custom/modules/{$module}/metadata/{$file}.php.suback.bak");
		      } else if(file_exists("custom/modules/{$module}/metadata/{$file}.php.suback.php")) {
		      	 unlink("custom/modules/{$module}/metadata/{$file}.php.suback.php");
		      }  
		   }
	   }
   } //foreach
}


function test_accounts_editview_merge() {		
   $original_panels = array();
   require('custom/modules/Accounts/metadata/editviewdefs.php');	
   foreach($viewdefs['Accounts']['EditView']['panels'] as $panel_key=>$panel) {
   	  $original_panels[$panel_key] = $panel_key;
   }	
   
   
   require_once 'modules/UpgradeWizard/SugarMerge/EditViewMerge.php';
   $this->merge = new EditViewMerge();	
   $this->merge->merge('Accounts', 'tests/modules/UpgradeWizard/SugarMerge/siupgrade_metadata_files/551/modules/Accounts/metadata/editviewdefs.php', 'modules/Accounts/metadata/editviewdefs.php', 'custom/modules/Accounts/metadata/editviewdefs.php');
   $this->assertTrue(file_exists('custom/modules/Accounts/metadata/editviewdefs.php.suback.php'));
   require('custom/modules/Accounts/metadata/editviewdefs.php');
   $fields = array();
   $panels = array();
   
   foreach($viewdefs['Accounts']['EditView']['panels'] as $panel_key=>$panel) {
   	  $panels[$panel_key] = $panel_key;
   	  foreach($panel as $row) {
   	  	 foreach($row as $col_key=>$col) {
   	  	 	$id = is_array($col) && isset($col['name']) ? $col['name'] : $col;
   	  	 	if(!empty($id) && !is_array($id)) {
   	  	 	   $fields[$id] = $col;
   	  	 	}
   	  	 }
   	  }
   }
   
   //echo var_export($original_panels, true);
   //echo var_export($panels, true);
   $this->assertTrue(count($panels) == count($original_panels), "Assert that orignal number of panels are preserved in custom Accounts EditView layout");
   $this->assertTrue(isset($panels['lbl_address_information']), "Assert that 'lbl_address_information' panel id is present");
   $this->assertTrue(isset($panels['lbl_email_addresses']), "Assert that 'lbl_email_addresses' panel id is present");
   $this->assertTrue(isset($panels['lbl_description_information']), "Assert that 'lbl_description_information' panel id is present");


   $custom_fields = array('reference_code_c', 'code_customized_by_c', 'customer_reference_c', 'type_of_reference_c',
                          'reference_contact_c', 'last_used_as_reference_c', 'reference_status_c', 'reference_notes_c',
   						  'last_used_reference_notes_c', 'training_credits_purchased_c', 'remaining_training_credits_c',
   					      'training_credits_pur_date_c', 'training_credits_exp_date_c', 'support_cases_purchased_c',
   );
   
   foreach($custom_fields as $c_field) {
   		$this->assertTrue(isset($fields["{$c_field}"]), "Assert that custom field {$c_field} is present");
   }
   
  
}


function test_accounts_detailview_merge() {		
   require_once 'modules/UpgradeWizard/SugarMerge/DetailViewMerge.php';
   $this->merge = new DetailViewMerge();	
   $this->merge->merge('Accounts', 'tests/modules/UpgradeWizard/SugarMerge/siupgrade_metadata_files/551/modules/Accounts/metadata/detailviewdefs.php', 'modules/Accounts/metadata/detailviewdefs.php', 'custom/modules/Accounts/metadata/detailviewdefs.php');
   $this->assertTrue(file_exists('custom/modules/Accounts/metadata/detailviewdefs.php.suback.php'));
   require('custom/modules/Accounts/metadata/detailviewdefs.php');
   $fields = array();
   $panels = array();
   
   foreach($viewdefs['Accounts']['DetailView']['panels'] as $panel_key=>$panel) {
   	  $panels[$panel_key] = $panel_key;
   	  foreach($panel as $row) {
   	  	 foreach($row as $col_key=>$col) {
   	  	 	$id = is_array($col) && isset($col['name']) ? $col['name'] : $col;
   	  	 	if(!empty($id) && !is_array($id)) {
   	  	 	   $fields[$id] = $col;
   	  	 	}
   	  	 }
   	  }
   }
   
   //echo var_export($panels, true);
   //echo var_export($viewdefs['Accounts']['DetailView']['panels'], true);
   $this->assertTrue(count($panels) == 5, "Assert that there are 5 panels matching the custom Accounts DetailView layout");
   $this->assertTrue(isset($panels['DEFAULT']), "Assert that 'DEFAULT' panel id is present");
   $this->assertTrue(isset($panels['lbl_panel7']), "Assert that 'lbl_panel7' panel id is present");
   $this->assertTrue(isset($panels['LBL_PANEL1']), "Assert that 'LBL_PANEL1' panel id is present");
   $this->assertTrue(isset($panels['LBL_PANEL6']), "Assert that 'LBL_PANEL6' panel id is present");
   $this->assertTrue(isset($panels['LBL_PANEL4']), "Assert that 'LBL_PANEL4' panel id is present");

   //Test fields that were specified in other OOTB panels, but that are moved back to the default panel for
   //this customization
   $this->assertTrue(isset($fields['team_name']), "Assert that team_name field is present");
   $this->assertTrue(isset($fields['date_modified']), "Assert that date_modified field is present"); 
}


function test_accounts_searchdefs_merge() {	
   require_once 'modules/UpgradeWizard/SugarMerge/SearchMerge.php';		
   $this->merge = new SearchMerge();	
   $this->merge->merge('Accounts', 'tests/modules/UpgradeWizard/SugarMerge/siupgrade_metadata_files/551/modules/Accounts/metadata/searchdefs.php', 'modules/Accounts/metadata/searchdefs.php', 'custom/modules/Accounts/metadata/searchdefs.php');
   $this->assertTrue(file_exists('custom/modules/Accounts/metadata/searchdefs.php.suback.php'));
   require('custom/modules/Accounts/metadata/searchdefs.php');
   $fields = array();
   
   foreach($searchdefs['Accounts']['layout']['basic_search'] as $col_key=>$col) {
      	$id = is_array($col) && isset($col['name']) ? $col['name'] : $col;
      	if(!empty($id) && !is_array($id)) {
   	  	   $fields[$id] = $col;
   	  	}
   }
  
   
   $this->assertTrue(count($fields) == 6, "Assert that there are 6 fields in the basic_search layout for Accounts metadata");
   
   $fields = array();
   foreach($searchdefs['Accounts']['layout']['advanced_search'] as $col_key=>$col) {
      	$id = is_array($col) && isset($col['name']) ? $col['name'] : $col;
      	if(!empty($id)) {
      		$fields[$id] = $col;
      	}
   }
   $this->assertTrue(count($fields) == 18, "Assert that there are 18 fields in the advanced_search layout for Accounts metadata");
}


function test_accounts_listviewdefs_merge() {	
   require_once 'modules/UpgradeWizard/SugarMerge/ListViewMerge.php';		
   $this->merge = new ListViewMerge();	
   $this->merge->merge('Accounts', 'tests/modules/UpgradeWizard/SugarMerge/siupgrade_metadata_files/551/modules/Accounts/metadata/listviewdefs.php', 'modules/Accounts/metadata/listviewdefs.php', 'custom/modules/Accounts/metadata/listviewdefs.php');
   $this->assertTrue(file_exists('custom/modules/Accounts/metadata/listviewdefs.php.suback.php'));
   require('custom/modules/Accounts/metadata/listviewdefs.php');
   $fields = array();
   $displayed_fields = array();
   foreach($listViewDefs['Accounts'] as $col_key=>$col) {
   	  	$fields[$col_key] = $col;
   	  	if(isset($col['default']) && $col['default']) {
   	  	   $displayed_fields[$col_key] = $col;
   	  	}
   }
  
   
   $this->assertTrue(count($displayed_fields) == 6, "Assert that there are 6 fields displayed in the listview layout for Accounts metadata");
   $this->assertTrue(isset($displayed_fields['NAME']), "Assert that NAME field is present");
   $this->assertTrue(isset($displayed_fields['BILLING_ADDRESS_CITY']), "Assert that BILLING_ADDRESS_CITY field is present");
   $this->assertTrue(isset($displayed_fields['BILLING_ADDRESS_STATE']), "Assert that BILLING_ADDRESS_STATE field is present");
   $this->assertTrue(isset($displayed_fields['PHONE_OFFICE']), "Assert that PHONE_OFFICE field is present");
   $this->assertTrue(isset($displayed_fields['TEAM_NAME']), "Assert that TEAM_NAME field is present");
   $this->assertTrue(isset($displayed_fields['ASSIGNED_USER_NAME']), "Assert that ASSIGNED_USER_NAME field is present");
}


function test_opportunities_searchdefs_merge() {	
   require_once 'modules/UpgradeWizard/SugarMerge/SearchMerge.php';		
   $this->merge = new SearchMerge();
   $this->merge->merge('Opportunities', 'tests/modules/UpgradeWizard/SugarMerge/siupgrade_metadata_files/551/modules/Opportunities/metadata/searchdefs.php', 'modules/Opportunities/metadata/searchdefs.php', 'custom/modules/Opportunities/metadata/searchdefs.php');
   $this->assertTrue(file_exists('custom/modules/Opportunities/metadata/searchdefs.php.suback.php'));
   require('custom/modules/Opportunities/metadata/searchdefs.php');
   $fields = array();
   
   foreach($searchdefs['Opportunities']['layout']['basic_search'] as $col_key=>$col) {
      	$id = is_array($col) && isset($col['name']) ? $col['name'] : $col;
        if(!empty($id) && !is_array($id)) {
   	  	   $fields[$id] = $col;
   	  	}
   }
  
   $this->assertTrue(count($fields) == 4, "Assert that there are 4 fields in the basic_search layout for Opportunities metadata");
   $this->assertTrue(isset($fields['name']), "Assert that name field is present");
   $this->assertTrue(isset($fields['opportunity_type']), "Assert that opportunity_type field is present");
   $this->assertTrue(isset($fields['account_name']), "Assert that account_name field is present");
   $this->assertTrue(isset($fields['current_user_only']), "Assert that current_user_only field is present");
      
   $fields = array();
   foreach($searchdefs['Opportunities']['layout']['advanced_search'] as $col_key=>$col) {
      	$id = is_array($col) && isset($col['name']) ? $col['name'] : $col;
        if(!empty($id) && !is_array($id)) {
   	  	   $fields[$id] = $col;
   	  	}
   }
   
   $this->assertTrue(count($fields) == 12, "Assert that there are 12 fields in the advanced_search layout for Opportunities metadata");
   $this->assertTrue(isset($fields['partner_assigned_to_c']), "Assert that partner_assigned_to_c field is present");
   
}

}
?>