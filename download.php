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


if(empty($_REQUEST['id']) || empty($_REQUEST['type']) || !isset($_SESSION['authenticated_user_id'])) {
	die("Not a Valid Entry Point");
}
else {
    ini_set('zlib.output_compression','Off');//bug 27089, if use gzip here, the Content-Length in hearder may be incorrect.
    // cn: bug 8753: current_user's preferred export charset not being honored
    $GLOBALS['current_user']->retrieve($_SESSION['authenticated_user_id']);
    $GLOBALS['current_language'] = $_SESSION['authenticated_user_language'];
    $app_strings = return_application_language($GLOBALS['current_language']);
    $mod_strings = return_module_language($GLOBALS['current_language'], 'ACL');
    if(!isset($_REQUEST['isTempFile'])) {
	    //Custom modules may have capilizations anywhere in thier names. We should check the passed in format first.
		require('include/modules.php');
		$module = $_REQUEST['type'];
		$file_type = strtolower($_REQUEST['type']);
		if(empty($beanList[$module])) {
			//start guessing at a module name
			$module = ucfirst($file_type);
	    	if(empty($beanList[$module])) {
	       		die($app_strings['ERROR_TYPE_NOT_VALID']);
	    	}
		}
    	$bean_name = $beanList[$module];
	    if(!file_exists('modules/' . $module . '/' . $bean_name . '.php')) {
	         die($app_strings['ERROR_TYPE_NOT_VALID']);
	    }
	    require_once('modules/' . $module . '/' . $bean_name . '.php');
	    $focus = new $bean_name();
	    $focus->retrieve($_REQUEST['id']);
	    if(!$focus->ACLAccess('view')){
	        die($mod_strings['LBL_NO_ACCESS']);
	    } // if
    } // if
	$local_location = (isset($_REQUEST['isTempFile'])) ? "{$GLOBALS['sugar_config']['cache_dir']}/modules/Emails/{$_REQUEST['ieId']}/attachments/{$_REQUEST['id']}"
		 : $GLOBALS['sugar_config']['upload_dir']."/".$_REQUEST['id'];

	if(isset($_REQUEST['isTempFile']) && ($_REQUEST['type']=="SugarFieldImage")) {			
	    $local_location =  $GLOBALS['sugar_config']['upload_dir']."/".$_REQUEST['id'];	    
    }
    
	if(!file_exists( $local_location ) || strpos($local_location, "..")) {
		die($app_strings['ERR_INVALID_FILE_REFERENCE']);
	}
	else {
		$doQuery = true;

		if($file_type == 'documents') {
			// cn: bug 9674 document_revisions table has no 'name' column.
			$query = "SELECT filename name FROM document_revisions INNER JOIN documents ON documents.id = document_revisions.document_id ";
			$query .= "WHERE document_revisions.id = '" . $_REQUEST['id'] ."'";
		} elseif($file_type == 'kbdocuments') {
				$query="SELECT document_revisions.filename name	FROM document_revisions INNER JOIN kbdocument_revisions ON document_revisions.id = kbdocument_revisions.document_revision_id INNER JOIN kbdocuments ON kbdocument_revisions.kbdocument_id = kbdocuments.id ";	 
			$query .= "WHERE document_revisions.id = '" . $_REQUEST['id'] ."'";
		}  elseif($file_type == 'notes') {
			$query = "SELECT filename name FROM notes ";
			$query .= "WHERE notes.id = '" . $_REQUEST['id'] ."'";
		} elseif( !isset($_REQUEST['isTempFile']) && !isset($_REQUEST['tempName'] ) && isset($_REQUEST['type']) && $file_type!='temp' ){ //make sure not email temp file.
			$query = "SELECT filename name FROM ". $file_type ." ";
			$query .= "WHERE ". $file_type .".id= '".$_REQUEST['id']."'";
		}elseif( $file_type == 'temp'){
			$doQuery = false;
		}

		if($doQuery && isset($query)) {
			$rs = $GLOBALS['db']->query($query);
			$row = $GLOBALS['db']->fetchByAssoc($rs);

			if(empty($row)){
				die($app_strings['ERROR_NO_RECORD']);
			}
			$name = $row['name'];
			$download_location = $GLOBALS['sugar_config']['upload_dir']."/".$_REQUEST['id'];
		} else if(isset(  $_REQUEST['tempName'] ) && isset($_REQUEST['isTempFile']) ){
			// downloading a temp file (email 2.0)
			$download_location = $local_location;
			$name = $_REQUEST['tempName'];
		}
		else if(isset($_REQUEST['isTempFile']) && ($_REQUEST['type']=="SugarFieldImage")) {
			$download_location = $local_location;
			$name = $_REQUEST['tempName'];
		}
		
		if(isset($_SERVER['HTTP_USER_AGENT']) && preg_match("/MSIE/", $_SERVER['HTTP_USER_AGENT']))
		{	
			$name = urlencode($name);
			$name = str_replace("+", "_", $name);
		}

		header("Pragma: public");
		header("Cache-Control: maxage=1, post-check=0, pre-check=0");
		if(isset($_REQUEST['isTempFile']) && ($_REQUEST['type']=="SugarFieldImage"))
			header("Content-type: image");
		else {
		    header("Content-type: application/force-download");
            header("Content-disposition: attachment; filename=\"".$name."\";");
		}
		header("Content-Length: " . filesize($local_location));
		header("Expires: 0");
		set_time_limit(0);

		@ob_end_clean();
		ob_start();

	        echo file_get_contents($download_location);
		@ob_flush();
	}
}
?>
