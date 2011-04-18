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

 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/




require_once('include/upload_file.php');


global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;
global $gridline;
global $locale;

$focus = new DocumentRevision();

if(isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}
$old_id = '';

echo get_module_title('DocumentRevisions', $mod_strings['LBL_MODULE_NAME'].": ".$focus->document_name, true); 


$GLOBALS['log']->info("Document revision detail view");

$xtpl=new XTemplate ('modules/DocumentRevisions/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);

$focus->fill_document_name_revision($focus->document_id);

$xtpl->assign("ID", $focus->id);
$xtpl->assign("DOCUMENT_NAME",$focus->name);
$xtpl->assign("CURRENT_REVISION",$focus->latest_revision);
$xtpl->assign("CHANGE_LOG",$focus->change_log);
$created_user = new User();
$created_user->retrieve($focus->created_by);
$xtpl->assign("CREATED_BY",$locale->getLocaleFormattedName($created_user->first_name, $created_user->last_name));

$xtpl->assign("DATE_CREATED",$focus->date_entered);
$xtpl->assign("REVISION",$focus->revision);
$xtpl->assign("FILENAME",$focus->filename);

$xtpl->assign("FILE_NAME", $focus->filename);
$xtpl->assign("SAVE_FILE", $focus->id);

$xtpl->assign("FILE_URL", UploadFile::get_url($focus->filename,$focus->id));
$xtpl->assign("GRIDLINE", $gridline);


$xtpl->parse("main");
$xtpl->out("main");
?>