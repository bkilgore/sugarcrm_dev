{*
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

*}
<div id="{$chartName}_div" style="width:{$width};height:{$height}px;z-index:80;{$style}" class="chartDiv">
 	<object type="application/x-shockwave-flash" data="include/SugarCharts/swf/chart.swf?inputFile={$chartXMLFile}&swfLocation=include/SugarCharts/swf/&inputColorScheme={$chartColorsXML}&inputStyleSheet={$chartStyleCSS}&inputLanguage={$chartStringsXML}" width="100%" height="100%">
		<param name="allowScriptAccess" value="sameDomain"/>
		<param name="movie" value="include/SugarCharts/swf/chart.swf?inputFile={$chartXMLFile}&swfLocation=include/SugarCharts/swf/&inputColorScheme={$chartColorsXML}&inputStyleSheet={$chartStyleCSS}&inputLanguage={$chartStringsXML}"/>
		<param name="menu" value="false"/>
		<param name="quality" value="high"/>
		<param name="wmode" value="transparent" />
		<p>{$app_strings.LBL_NO_FLASH_PLAYER}</p>
	</object>
</div>

<script type="text/javascript">
	if (typeof SUGAR == 'undefined' || typeof SUGAR.mySugar == 'undefined') {ldelim}
		// no op
		loadChartForReports();
	{rdelim} else {ldelim}
		SUGAR.mySugar.addToChartsArray('{$chartName}', '{$chartXMLFile}', '{$width}', '{$height}', '{$chartStyleCSS}', '{$chartColorsXML}', '{$chartStringsXML}');
	{rdelim}
	
	var loadDone=0;
	function loadChartForReports() {ldelim}
		//only allow 5 tries
		if (loadDone > 5) 
			return;
		if(typeof(loadChartSWF) == 'function'){ldelim}
			//if the function exists, call the function and set the flag
			loadChartSWF('{$chartName}', '{$chartXMLFile}', '{$width}', '{$height}', '{$chartStyleCSS}', '{$chartColorsXML}', '{$chartStringsXML}');
			loadDone = 8;
		{rdelim}else{ldelim}
			//the function has not been loaded yet, so increaste the count and call the current function again
			loadDone = loadDone+1;
			setTimeout("loadChartForReports()",500);
		{rdelim}		
	{rdelim}
</script>
