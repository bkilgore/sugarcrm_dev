<?php
require_once 'include/dir_inc.php';

class Bug39057Test extends Sugar_PHPUnit_Framework_TestCase  {

var $merge;

function setUp() {
   SugarTestMergeUtilities::setupFiles(array('Opportunities'), array('listviewdefs'), 'tests/modules/UpgradeWizard/SugarMerge/od_metadata_files');
}


function tearDown() {
   SugarTestMergeUtilities::teardownFiles();
}


function test_listviewdefs_merge() {			
   require('custom/modules/Opportunities/metadata/listviewdefs.php');
   $original_fields = array();
   $original_displayed_fields = array();
   foreach($listViewDefs['Opportunities'] as $col_key=>$col) {
   	  	$original_fields[$col_key] = $col;
   	  	if(isset($col['default']) && $col['default']) {
   	  	   $original_displayed_fields[$col_key] = $col;
   	  	}
   }

   require_once 'modules/UpgradeWizard/SugarMerge/ListViewMerge.php';		
   $this->merge = new ListViewMerge();	
   $this->merge->merge('Opportunities', 'tests/modules/UpgradeWizard/SugarMerge/od_metadata_files/554/modules/Opportunities/metadata/listviewdefs.php', 'modules/Opportunities/metadata/listviewdefs.php', 'custom/modules/Opportunities/metadata/listviewdefs.php');
   $this->assertTrue(file_exists('custom/modules/Opportunities/metadata/listviewdefs.php.suback.php'));
   require('custom/modules/Opportunities/metadata/listviewdefs.php');
   $fields = array();
   $displayed_fields = array();
   foreach($listViewDefs['Opportunities'] as $col_key=>$col) {
   	  	$fields[$col_key] = $col;
   	  	if(isset($col['default']) && $col['default']) {
   	  	   $displayed_fields[$col_key] = $col;
   	  	}
   } 
   
   //echo var_export($displayed_fields, true);
   
   $this->assertTrue(isset($original_displayed_fields['AMOUNT_USDOLLAR']['label']));
   $this->assertTrue(isset($displayed_fields['AMOUNT_USDOLLAR']['label']));
   //This tests to ensure that the label value is the same from the custom file even though in the new
   //file we changed the label value, we should preserve the custom value
   if(isset($original_displayed_fields['AMOUNT_USDOLLAR']['label']) && isset($displayed_fields['AMOUNT_USDOLLAR']['label']))
   {
   	  $this->assertNotEquals($original_displayed_fields['AMOUNT_USDOLLAR']['label'], $displayed_fields['AMOUNT_USDOLLAR']['label']);
   }
}


}
?>