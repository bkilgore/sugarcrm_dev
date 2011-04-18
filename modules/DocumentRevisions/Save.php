<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * SugarCRM is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2011 SugarCRM Inc.
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 * 
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 ********************************************************************************/

/*********************************************************************************

 * Description:  Base Form For Notes
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
 



require_once('include/formbase.php');
require_once('include/upload_file.php');

global $mod_strings;
$mod_strings = return_module_language($current_language, 'DocumentRevisions');

$prefix='';

$do_final_move = 0;

$Revision = new DocumentRevision();
$Document = new Document();
if (isset($_REQUEST['record'])) {
	$Document->retrieve($_REQUEST['record']);
}
if(!$Document->ACLAccess('Save')){
		ACLController::displayNoAccess(true);
		sugar_cleanup(true);
}
if (isset($_REQUEST['SaveRevision'])) {
	
	//fetch the document record.
	$Document->retrieve($_REQUEST['return_id']);
	
	if($useRequired &&  !checkRequired($prefix, array_keys($Revision->required_fields))){
		return null;
	}

	$Revision = populateFromPost($prefix, $Revision);
	$upload_file = new UploadFile('uploadfile');
	if (isset($_FILES['uploadfile']) && $upload_file->confirm_upload())
	{
        $Revision->filename = $upload_file->get_stored_file_name();
        $Revision->file_mime_type = $upload_file->mime_type;
		$Revision->file_ext = $upload_file->file_ext;
  	 	  	 	
  	 	$do_final_move = 1;
	}
	
	//save revision
	$Revision->document_id = $_REQUEST['return_id'];
	$Revision->id = null;  // 17767: Security fix, make sure no id is passed in via form.
	$Revision->save();

	//revsion is the document.	
	$Document->document_revision_id = $Revision->id;
	$Document->save();
	$return_id = $Document->id;
} 

if ($do_final_move)
{
   	 $upload_file->final_move($Revision->id);
}
else if ( ! empty($_REQUEST['old_id']))
{
   	 $upload_file->duplicate_file($_REQUEST['old_id'], $Revision->id, $Revision->filename);
}

$GLOBALS['log']->debug("Saved record with id of ".$return_id);
handleRedirect($return_id, "Documents");
?>
