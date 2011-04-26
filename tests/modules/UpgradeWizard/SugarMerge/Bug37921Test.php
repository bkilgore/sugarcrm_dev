<?php
require_once 'include/dir_inc.php';

class Bug37921Test extends Sugar_PHPUnit_Framework_TestCase  {

var $merge;

function setUp() {
   SugarTestMergeUtilities::setupFiles(array('Notes'), array('searchdefs'), 'tests/modules/UpgradeWizard/SugarMerge/od_metadata_files');
}


function tearDown() {
   SugarTestMergeUtilities::teardownFiles();
}


function test_elster_notes_searchdefs_merge() {			
   require_once 'modules/UpgradeWizard/SugarMerge/SearchMerge.php';		
   $this->merge = new SearchMerge();	
   $this->merge->merge('Notes', 'tests/modules/UpgradeWizard/SugarMerge/metadata_files/551/modules/Notes/metadata/searchdefs.php', 'modules/Notes/metadata/searchdefs.php', 'custom/modules/Notes/metadata/searchdefs.php');
   $this->assertTrue(file_exists('custom/modules/Notes/metadata/searchdefs.php.suback.php'));
   require('custom/modules/Notes/metadata/searchdefs.php');
   $fields = array();
   

   foreach($searchdefs['Notes']['layout']['basic_search'] as $col_key=>$col) {
      	$id = is_array($col) && isset($col['name']) ? $col['name'] : $col;
      	if(!empty($id) && !is_array($id)) {
   	  	   $fields[$id] = $col;
   	  	}
   }

   $this->assertTrue(count($fields) == 2, "Assert that there are 2 fields in the basic_search layout for Notes metadata");
   
   $fields = array();
   foreach($searchdefs['Notes']['layout']['advanced_search'] as $col_key=>$col) {
      	$id = is_array($col) && isset($col['name']) ? $col['name'] : $col;
      	if(!empty($id)) {
      		$fields[$id] = $col;
      	}
   }
   $this->assertTrue(count($fields) == 7, "Assert that there are 7 fields in the advanced_search layout for Notes metadata");
}


}
?>