<?php
require_once 'include/dir_inc.php';

class Bug39059Test extends Sugar_PHPUnit_Framework_TestCase  {

var $merge;
var $allow_call_time_pass_reference;

function setUp() {
   SugarTestMergeUtilities::setupFiles(array('Leads'), array('detailviewdefs'), 'tests/modules/UpgradeWizard/SugarMerge/cit_metadata_files');
   $this->allow_call_time_pass_reference = ini_get('allow_call_time_pass_reference');
}


function tearDown() {
   SugarTestMergeUtilities::teardownFiles();
   ini_set('allow_call_time_pass_reference', $this->allow_call_time_pass_reference);
}


function test_600_leads_detailview_merge() {			
   require('custom/modules/Leads/metadata/detailviewdefs.php');
   $this->assertTrue(!isset($viewdefs['Leads']['DetailView']['panels']['default']));
   ini_set('allow_call_time_pass_reference', 'Off');
   require_once('modules/UpgradeWizard/SugarMerge/DetailViewMerge.php');
   $this->merge = new DetailViewMerge();	
   $this->merge->merge('Leads', 'tests/modules/UpgradeWizard/SugarMerge/cit_metadata_files/554/modules/Leads/metadata/detailviewdefs.php', 'modules/Leads/metadata/detailviewdefs.php', 'custom/modules/Leads/metadata/detailviewdefs.php');
   $this->assertTrue(file_exists('custom/modules/Leads/metadata/detailviewdefs.php.suback.php'));
   require('custom/modules/Leads/metadata/detailviewdefs.php');
   $this->assertTrue(isset($viewdefs['Leads']['DetailView']['panels']['default']));
}




}

?>