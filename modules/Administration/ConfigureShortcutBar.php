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

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once("include/JSON.php");
$json = new JSON();

global $mod_strings;
global $app_list_strings;
global $app_strings;
global $current_user;

if (!is_admin($current_user)) sugar_die("Unauthorized access to administration.");

$title = getClassicModuleTitle(
            "Administration", 
            array(
                "<a href='index.php?module=Administration&action=index'>{$mod_strings['LBL_MODULE_NAME']}</a>",
               translate('LBL_CONFIGURE_SHORTCUT_BAR')
               ), 
            true
            );
$msg = "";

global $theme, $currentModule, $app_list_strings, $app_strings;
$GLOBALS['log']->info("Administration ConfigureShortcutBar view");
$actions_path = "include/DashletContainer/Containers/DCActions.php";

//If save is set, save then let the user know if the save worked.
if (!empty($_REQUEST['enabled_modules']))
{
	$toDecode = html_entity_decode  ($_REQUEST['enabled_modules'], ENT_QUOTES);
	$modules = json_decode($toDecode);
	$out = "<?php\n \$DCActions = \n" . var_export_helper ( $modules ) . ";";
	if (!is_file("custom/" . $actions_path))
	   create_custom_directory("include/DashletContainer/Containers/");
	if ( file_put_contents ( "custom/" . $actions_path, $out ) === false)
	   echo translate("LBL_SAVE_FAILED");
	else  {
	   echo "true";
	}
	
} else {
	include($actions_path);
	//Start with the default module
	$availibleModules = $DCActions;
	//Add the ones currently on the layout
	if (is_file('custom/' . $actions_path))
	{
	    include('custom/' . $actions_path);
	    $availibleModules = array_merge($availibleModules, $DCActions);
	}
	//Next add the ones we detect as having quick create defs.
	$modules = $app_list_strings['moduleList'];
	foreach ($modules as $module => $modLabel)
	{
		if (is_file("modules/$module/metadata/quickcreatedefs.php") || is_file("custom/modules/$module/metadata/quickcreatedefs.php"))
		   $availibleModules[$module] = $module;
	}
	
	$availibleModules = array_diff($availibleModules, $DCActions);
	
	$enabled = array();
	foreach($DCActions as $mod)
	{
	    $enabled[] = array("module" => $mod, 'label' => translate($mod));
	}
	
	$disabled = array();
	foreach($availibleModules as $mod)
	{
	    $disabled[] = array("module" => $mod, 'label' => translate($mod));
	}
	
	$this->ss->assign('APP', $GLOBALS['app_strings']);
	$this->ss->assign('MOD', $GLOBALS['mod_strings']);
	$this->ss->assign('title',  $title);
	
	$this->ss->assign('enabled_modules', $json->encode ( $enabled ));
	$this->ss->assign('disabled_modules',$json->encode ( $disabled));
	$this->ss->assign('description',  translate("LBL_CONFIGURE_SHORTCUT_BAR"));
	$this->ss->assign('msg',  $msg);
	
	echo $this->ss->fetch('modules/Administration/templates/ShortcutBar.tpl');	
}
?>
