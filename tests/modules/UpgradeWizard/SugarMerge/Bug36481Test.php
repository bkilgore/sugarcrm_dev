<?php
require_once 'include/dir_inc.php';

class Bug36481Test extends Sugar_PHPUnit_Framework_TestCase  {

var $ev_merge;
var $has_contacts_dir = false;
var $has_suback_file = false;

function setUp() {
   global $current_user;
   if(!isset($current_user)) {
   	  $current_user = SugarTestUserUtilities::createAnonymousUser();
   }
   if(!file_exists("custom/modules/Contacts/metadata")){
	  mkdir_recursive("custom/modules/Contacts/metadata", true);
   }
   
   if(file_exists('custom/modules/Contacts/metadata/editviewdefs.php')) {
   	  $this->has_contacts_dir = true;
   	  copy('custom/modules/Contacts/metadata/editviewdefs.php', 'custom/modules/Contacts/metadata/editviewdefs.php.bak');
   }
   
   $this->has_suback_file = file_exists('custom/modules/Contacts/metadata/editviewdefs.php.suback.php');
   
   copy('tests/modules/UpgradeWizard/SugarMerge/metadata_files/custom/modules/Contacts/metadata/editviewdefs.php', 'custom/modules/Contacts/metadata/editviewdefs.php');
}

function tearDown() {
	return;
   if(!$this->has_contacts_dir) {
   	  rmdir_recursive('custom/modules/Contacts');
   }  else if(file_exists('custom/modules/Contacts/metadata/editviewdefs.php.bak')) {
   	  copy('custom/modules/Contacts/metadata/editviewdefs.php.bak', 'custom/modules/Contacts/metadata/editviewdefs.php');
      unlink('custom/modules/Contacts/metadata/editviewdefs.php.bak');
      
      if(!$this->has_suback_file) {
   	     unlink('custom/modules/Contacts/metadata/editviewdefs.php.suback.php');
   	  }
   }
   

}

function test_contacts_editview_merge() {
   require_once('modules/UpgradeWizard/SugarMerge/EditViewMerge.php');	
   $this->ev_merge = new EditViewMerge();	
   $this->ev_merge->merge('Contacts', 'tests/modules/UpgradeWizard/SugarMerge/metadata_files/550/modules/Contacts/metadata/editviewdefs.php', 'modules/Contacts/metadata/editviewdefs.php', 'custom/modules/Contacts/metadata/editviewdefs.php');
   $this->assertTrue(file_exists('custom/modules/Contacts/metadata/editviewdefs.php.suback.php'));
   require('custom/modules/Contacts/metadata/editviewdefs.php');
   $fields = array();
   foreach($viewdefs['Contacts']['EditView']['panels'] as $panel) {
   	  foreach($panel as $row) {
   	  	 foreach($row as $col_key=>$col) {
   	  	 	$id = is_array($col) && isset($col['name']) ? $col['name'] : $col;
   	  	 	$fields[$id] = $col;
   	  	 }
   	  }
   }
   
   $this->assertTrue(isset($fields['test_c']), 'Assert that test_c custom field exists');
}


}

?>