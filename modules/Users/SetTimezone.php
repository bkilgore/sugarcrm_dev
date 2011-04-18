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



require_once('include/JSON.php');
require_once('include/timezone/timezones.php');

require_once('modules/Users/Forms.php');



global $app_strings;
global $app_list_strings;
global $mod_strings;

$admin = new Administration();
$admin->retrieveSettings("notify");


///////////////////////////////////////////////////////////////////////////////
////	HELPER FUNCTIONS
function lookupTimezone_special($userOffset){
	$defaultZones = array('America/New_York'=>1, 'America/Los_Angeles'=>1,'America/Chicago'=>1, 'America/Denver'=>1,'America/Anchorage'=>1, 'America/Phoenix'=>1, 'Europe/Amsterdam'=>1,'Europe/Athens'=>1,'Europe/London'=>1, 'Australia/Sydney'=>1, 'Australia/Perth'=>1, 'Asia/Tokyo'=>1);
	global $timezones;

	$gmtOffset = $userOffset;
	$selectedZone = ' ';
	foreach($timezones as $zoneName=>$zone){
		if($zone['gmtOffset'] == $gmtOffset){
			$selectedZone = $zoneName;
		}
		if(!empty($defaultZones[$selectedZone]) ){
			return $selectedZone;
		}
	}
	return $selectedZone;
}
////	END HELPER FUNCTIONS
///////////////////////////////////////////////////////////////////////////////

if(isset($_REQUEST['userOffset'])) { // ajax call to lookup timezone
    echo 'userTimezone = "' . lookupTimezone_special($_REQUEST['userOffset']) . '";';
    die();
}
$admin = new Administration();
$admin->retrieveSettings();
$sugar_smarty = new Sugar_Smarty();
$sugar_smarty->assign('MOD', $mod_strings);
$sugar_smarty->assign('APP', $app_strings);


$selectedZone = $current_user->getPreference('timezone');
if(empty($selectedZone) && !empty($_REQUEST['gmto'])) {
	$selectedZone = lookupTimezone_special(-1 * $_REQUEST['gmto']);
}

$timezoneOptions = '';
ksort($timezones);

foreach($timezones as $key => $value) {
	if( $selectedZone== $key) {
		$selected = " SELECTED";
	} else { 
		$selected = "";
	}
	if(!empty($value['dstOffset'])) {
		$dst = " (+DST)";
	} else {
		$dst = "";
	}
	$gmtOffset = ($value['gmtOffset'] / 60);
	if(!strstr($gmtOffset,'-')) {
		$gmtOffset = "+".$gmtOffset;
	}
	$timezoneOptions .= "<option value='$key'".$selected.">".str_replace(array('_','North'), array(' ', 'N.'),$key). " (GMT".$gmtOffset.") ".$dst."</option>";
}
$sugar_smarty->assign('TIMEZONEOPTIONS', $timezoneOptions);
$sugar_smarty->display('modules/Users/SetTimezone.tpl');