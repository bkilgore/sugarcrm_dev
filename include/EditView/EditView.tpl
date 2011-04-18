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
{{include file=$headerTpl}}
{sugar_include include=$includes}


<div id="{{$form_name}}_tabs" 
{{if $useTabs}}
class="yui-navset"
{{/if}}
>
    {{if $useTabs}}
    {* Generate the Tab headers *}
    {{counter name="tabCount" start=-1 print=false assign="tabCount"}}
    <ul class="yui-nav">
    {{foreach name=section from=$sectionPanels key=label item=panel}}
        {{counter name="tabCount" print=false}}
        <li class="selected"><a id="tab{{$tabCount}}" href="#tab{{$tabCount}}"><em>{sugar_translate label='{{$label}}' module='{{$module}}'}</em></a></li>
    {{/foreach}}
    </ul>
    {{/if}}
    <div {{if $useTabs}}class="yui-content"{{/if}}>
{{* Loop through all top level panels first *}}
{{counter name="panelCount" start=-1 print=false assign="panelCount"}}

{{foreach name=section from=$sectionPanels key=label item=panel}}
{{counter name="panelCount" print=false}}

{{* Print out the table data *}}
{{if $label == 'DEFAULT'}}
	<div id="Default_{$module}_Subpanel">
{{else}}
	<div id="{{$label}}">
{{/if}}

{counter name="panelFieldCount" start=0 print=false assign="panelFieldCount"}
{{* Check to see if the panel variable is an array, if not, we'll attempt an include with type param php *}}
{{* See function.sugar_include.php *}}
{{if !is_array($panel)}}
    {sugar_include type='php' file='{{$panel}}'}
{{else}}

<table width="100%" border="0" cellspacing="1" cellpadding="0"  class="{$def.templateMeta.panelClass|default:'edit view'}">
{{* Only show header if it is not default or an int value *}}
{{if !empty($label) && !is_int($label) && $label != 'DEFAULT' && !$useTabs && $showSectionPanelsTitles}}
<tr>
<th align="left" colspan="8">
<h4>{sugar_translate label='{{$label}}' module='{{$module}}'}</h4>
</th>
</tr>
{{/if}}

{{assign var='rowCount' value=0}}
{{foreach name=rowIteration from=$panel key=row item=rowData}}
<tr>

	{{assign var='columnsInRow' value=$rowData|@count}}
	{{assign var='columnsUsed' value=0}}

    {{* Loop through each column and display *}}
    {{counter name="colCount" start=0 print=false assign="colCount"}}

	{{foreach name=colIteration from=$rowData key=col item=colData}}

	{{counter name="colCount" print=false}}
	{{math assign="tabIndex" equation="$panelCount * $maxColumns + $colCount"}}
	{{if count($rowData) == $colCount}}
		{{assign var="colCount" value=0}}
	{{/if}}


		{{if empty($def.templateMeta.labelsOnTop) && empty($colData.field.hideLabel)}}
		<td valign="top" id='{{$colData.field.name}}_label' width='{{$def.templateMeta.widths[$smarty.foreach.colIteration.index].label}}%' scope="row">
			{{if isset($colData.field.customLabel)}}
			   {{$colData.field.customLabel}}
			{{elseif isset($colData.field.label)}}
			   {capture name="label" assign="label"}
			   {sugar_translate label='{{$colData.field.label}}' module='{{$module}}'}
			   {/capture}
			   {$label|strip_semicolon}:
			{{elseif isset($fields[$colData.field.name])}}
			   {capture name="label" assign="label"}
			   {sugar_translate label='{{$fields[$colData.field.name].vname}}' module='{{$module}}'}
			   {/capture}
			   {$label|strip_semicolon}:
			{{/if}}
			{{* Show the required symbol if field is required, but override not set.  Or show if override is set *}}
			{{if ($fields[$colData.field.name].required && !isset($colData.field.displayParams.required)) || 
			     (isset($colData.field.displayParams.required) && $colData.field.displayParams.required && $fields[$colData.field.name].required !== false)}}
			    <span class="required">{{$APP.LBL_REQUIRED_SYMBOL}}</span>
			{{/if}}
		</td>
		{{/if}}

		<td valign="top" width='{{$def.templateMeta.widths[$smarty.foreach.colIteration.index].field}}%' {{if $colData.colspan}}colspan='{{$colData.colspan}}'{{/if}}>
			{{if !empty($def.templateMeta.labelsOnTop)}}
				{{if isset($colData.field.label)}}
				    {{if !empty($colData.field.label)}}
			   		    {sugar_translate label='{{$colData.field.label}}' module='{{$module}}'}:
				    {{/if}}
				{{elseif isset($fields[$colData.field.name])}}
			  		{sugar_translate label='{{$fields[$colData.field.name].vname}}' module='{{$module}}'}:
				{{/if}}
				
				{{* Show the required symbol if field is required, but override not set.  Or show if override is set *}}
				{{if ($fields[$colData.field.name].required && (!isset($colData.field.displayParams.required) || $colData.field.displayParams.required)) || 
				     (isset($colData.field.displayParams.required) && $colData.field.displayParams.required)}}
				    <span class="required" title="{{$APP.LBL_REQUIRED_TITLE}}">{{$APP.LBL_REQUIRED_SYMBOL}}</span>
				{{/if}}
				{{if !isset($colData.field.label) || !empty($colData.field.label)}}
				<br>
				{{/if}}
			{{/if}}

			
			{{if $fields[$colData.field.name] && !empty($colData.field.fields) }}
			    {{foreach from=$colData.field.fields item=subField}}
			        {{if $fields[$subField.name]}}
			        	{counter name="panelFieldCount"}
			            {{sugar_field parentFieldArray='fields' tabindex=$colData.field.tabindex vardef=$fields[$subField.name] displayType='EditView' displayParams=$subField.displayParams formName=$form_name}}&nbsp;
			        {{/if}}
			    {{/foreach}}
			{{elseif !empty($colData.field.customCode)}}
				{counter name="panelFieldCount"}
				{{sugar_evalcolumn var=$colData.field.customCode colData=$colData tabindex=$colData.field.tabindex}}
			{{elseif $fields[$colData.field.name]}}
				{counter name="panelFieldCount"}
			    {{$colData.displayParams}}
				{{sugar_field parentFieldArray='fields' tabindex=$colData.field.tabindex vardef=$fields[$colData.field.name] displayType='EditView' displayParams=$colData.field.displayParams typeOverride=$colData.field.type formName=$form_name}}
			{{/if}}

	{{/foreach}}
</tr>
{{/foreach}}
</table>

{{/if}}

</div>
{if $panelFieldCount == 0}

<script>document.getElementById("{{$label}}").style.display='none';</script>
{/if}
{{/foreach}}
</div></div>
{{include file=$footerTpl}}
{{if $useTabs}}
<script type="text/javascript" src="include/javascript/sugar_grp_yui_widgets.js"></script>
<script type="text/javascript">
var {{$form_name}}_tabs = new YAHOO.widget.TabView("{{$form_name}}_tabs");
{{$form_name}}_tabs.selectTab(0);
</script> 
{{/if}}
<script type="text/javascript">
YAHOO.util.Event.onContentReady("form_QuickCreate_Accounts", 
    function () {ldelim} initEditView(document.forms.{{$form_name}}) {rdelim});
//window.setTimeout(, 100);
window.onbeforeunload = function () {ldelim} return onUnloadEditView(); {rdelim};
</script>
