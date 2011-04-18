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



function create_chart($chartName,$xmlFile,$width="800",$height="400") {
	$html ='<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
 codebase="https://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
 WIDTH="'.$width.'" HEIGHT="'.$height.'" id="'.$chartName.'" ALIGN="">';
	$html .='<PARAM NAME=movie VALUE="'.getSWFPath('include/charts/'.$chartName.'.swf','filename='.$xmlFile).'">';
	$html .='<PARAM NAME=bgcolor VALUE=#FFFFFF>';
	$html .='<PARAM NAME=wmode VALUE=transparent>';
	$html .= '<PARAM NAME=quality VALUE=high>';
	$html .='<EMBED src="' . getSWFPath('include/charts/'.$chartName.'.swf','filename='.$xmlFile).'" wmode="transparent" quality=high bgcolor=#FFFFFF  WIDTH="'.$width.'" HEIGHT="'.$height.'" NAME="'.$chartName.'" ALIGN=""
 TYPE="application/x-shockwave-flash" PLUGINSPAGE="https://www.macromedia.com/go/getflashplayer">';
	$html .='</EMBED>';
	$html .='</OBJECT>';
return $html;
}


function generate_graphcolor($input,$instance) {
	if ($instance <20) {
	$color = array(
	"0xFF0000",
	"0x00FF00",
	"0x0000FF",
	"0xFF6600",
	"0x42FF8E",
	"0x6600FF",
	"0xFFFF00",
	"0x00FFFF",
	"0xFF00FF",
	"0x66FF00",
	"0x0066FF",
	"0xFF0066",
	"0xCC0000",
	"0x00CC00",
	"0x0000CC",
	"0xCC6600",
	"0x00CC66",
	"0x6600CC",
	"0xCCCC00",
	"0x00CCCC");
	$out = $color[$instance];
	} else {
	$out = "0x" . substr(md5($input), 0, 6);

	}
	return $out;
}

function save_xml_file($filename,$xml_file) {
	global $app_strings;

	if (!$handle = sugar_fopen($filename, 'w')) {
		$GLOBALS['log']->debug("Cannot open file ($filename)");
		return;
	}

	if (fwrite($handle,$xml_file) === FALSE) {
		$GLOBALS['log']->debug("Cannot write to file ($filename)");
		return false;
	}

	$GLOBALS['log']->debug("Success, wrote ($xml_file) to file ($filename)");

	fclose($handle);
	return true;

}

function get_max($numbers) {
    $max = max($numbers);
    if ($max < 1) return $max;
    $base = pow(10, floor(log10($max)));
    return ceil($max/$base) * $base;
}

// retrieve the translated strings.
global $current_language;
$app_strings = return_application_language($current_language);

if(isset($app_strings['LBL_CHARSET']))
{
	$charset = $app_strings['LBL_CHARSET'];
}
else
{
	global $sugar_config;
	$charset = $sugar_config['default_charset'];
}
?>
