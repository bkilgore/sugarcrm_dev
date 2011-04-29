<?php
class Bug37917Test extends Sugar_PHPUnit_Framework_TestCase  {

var $merge;

function setUp() {
   SugarTestMergeUtilities::setupFiles(array('Contacts'), array('editviewdefs'), 'tests/modules/UpgradeWizard/SugarMerge/od_metadata_files');
}


function tearDown() {
   SugarTestMergeUtilities::teardownFiles();
}


function test_contacts_editview_merge() {	
   require_once 'modules/UpgradeWizard/SugarMerge/EditViewMerge.php';
   $this->merge = new EditViewMerge();	
   $this->merge->merge('Contacts', 'tests/modules/UpgradeWizard/SugarMerge/metadata_files/551/modules/Contacts/metadata/editviewdefs.php', 'modules/Contacts/metadata/editviewdefs.php', 'custom/modules/Contacts/metadata/editviewdefs.php');
   $this->assertTrue(file_exists('custom/modules/Contacts/metadata/editviewdefs.php.suback.php'));
   require('custom/modules/Contacts/metadata/editviewdefs.php');
   $fields = array();
   $panels = array();
   
   //echo var_export($viewdefs['Contacts']['EditView']['panels'], true);
   foreach($viewdefs['Contacts']['EditView']['panels'] as $panel_key=>$panel) {
   	  $panels[$panel_key] = $panel_key;
   	  foreach($panel as $r=>$row) {
   	  	 $new_row = true;
   	  	 foreach($row as $col_key=>$col) {
   	  	 	$id = is_array($col) && isset($col['name']) ? $col['name'] : $col;
   	  	 	if(!empty($id) && !is_array($id)) {
   	  	 	   $fields[$id] = $col;
   	  	 	}
   	  	 }
   	  }
   }
   
   $this->assertTrue(isset($fields['alt_address_postalcode']) && isset($fields['alt_address_city']), 'Assert that alt_address_postalcode and alt_address_city are preserved');
   $this->assertTrue(isset($fields['alt_address_street']) && !isset($fields['alt_address_street']['displayParams']), 'Assert that the original alt_address_street field contents were preserved');
   $this->assertTrue(isset($fields['primary_address_postalcode']) && isset($fields['primary_address_city']), 'Assert that primary_address_postalcode and primary_address_city are preserved');
   $this->assertTrue(isset($fields['primary_address_street']) && !isset($fields['primary_address_street']['displayParams']), 'Assert that the original primary_address_street field contents were preserved');
}


}

?>