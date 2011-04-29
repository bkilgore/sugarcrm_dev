<?php
require_once 'include/dir_inc.php';

class Bug43226Test extends Sugar_PHPUnit_Framework_TestCase  {
	
var $merge;

function setUp() {
   $this->useOutputBuffering = false;
   SugarTestMergeUtilities::setupFiles(array('Documents'), array('editviewdefs'), 'tests/modules/UpgradeWizard/SugarMerge/metadata_files');
}


function tearDown() {
   SugarTestMergeUtilities::teardownFiles();
}

function test_uploadfile_convert_merge_600() {
   require_once 'modules/UpgradeWizard/SugarMerge/EditViewMerge.php';
   $this->merge = new EditViewMerge();
   $this->merge->merge('Documents', 'tests/modules/UpgradeWizard/SugarMerge/metadata_files/600/modules/Documents/metadata/editviewdefs.php','modules/Documents/metadata/editviewdefs.php','custom/modules/Documents/metadata/editviewdefs.php');

   require('custom/modules/Documents/metadata/editviewdefs.php');

   $foundUploadFile = 0;
   $foundFilename = 0;

   foreach ( $viewdefs['Documents']['EditView']['panels'] as $panel ) {
       foreach ( $panel as $row ) {
           foreach ( $row as $col ) {
               if ( is_array($col) ) {
                   $fieldName = $col['name'];
               } else {
                   $fieldName = $col;
               }
               
               if ( $fieldName == 'filename' ) {
                   $foundFilename++;
               } else if ( $fieldName == 'uploadfile' ) {
                   $foundUploadFile++;
               }
           }
       }
   }
   
   $this->assertTrue($foundUploadFile==0,'Uploadfile field still exists, should be filename');
   $this->assertTrue($foundFilename>0,'Filename field doesn\'t exit, it should');

   if ( file_exists('custom/modules/Documents/metadata/editviewdefs-testback.php') ) {
       copy('custom/modules/Documents/metadata/editviewdefs-testback.php','custom/modules/Documents/metadata/editviewdefs.php');
       unlink('custom/modules/Documents/metadata/editviewdefs-testback.php');
   }
}

function test_uploadfile_convert_merge_610() {
   require_once 'modules/UpgradeWizard/SugarMerge/EditViewMerge.php';
   $this->merge = new EditViewMerge();
   $this->merge->merge('Documents', 'tests/modules/UpgradeWizard/SugarMerge/metadata_files/610/modules/Documents/metadata/editviewdefs.php','modules/Documents/metadata/editviewdefs.php','custom/modules/Documents/metadata/editviewdefs.php');

   require('custom/modules/Documents/metadata/editviewdefs.php');

   $foundUploadFile = 0;
   $foundFilename = 0;

   foreach ( $viewdefs['Documents']['EditView']['panels'] as $panel ) {
       foreach ( $panel as $row ) {
           foreach ( $row as $col ) {
               if ( is_array($col) ) {
                   $fieldName = $col['name'];
               } else {
                   $fieldName = $col;
               }
               
               if ( $fieldName == 'filename' ) {
                   $foundFilename++;
               } else if ( $fieldName == 'uploadfile' ) {
                   $foundUploadFile++;
               }
           }
       }
   }
   
   $this->assertTrue($foundUploadFile==0,'Uploadfile field still exists, should be filename');
   $this->assertTrue($foundFilename>0,'Filename field doesn\'t exit, it should');

   if ( file_exists('custom/modules/Documents/metadata/editviewdefs-testback.php') ) {
       copy('custom/modules/Documents/metadata/editviewdefs-testback.php','custom/modules/Documents/metadata/editviewdefs.php');
       unlink('custom/modules/Documents/metadata/editviewdefs-testback.php');
   }
}

}