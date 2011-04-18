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


{if $overlib}
	<script type="text/javascript" src="include/javascript/sugar_grp_overlib.js"></script>
	<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
{/if}

<table cellpadding="0" cellspacing="0" width="100%" border="0" class="list view">
	<tr class='pagination'>
		<td colspan="{$colCount}" align="right">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td align="left">{$exportLink}{$mergeLink}{$selectedObjectsSpan}&nbsp;</td>
					<td align="right" nowrap="nowrap">
						{if $pageData.urls.startPage}
							<a href="{$pageData.urls.startPage}" {if $prerow}onclick="javascript:return sListView.save_checks(0, '{$moduleString}')"{/if} ><img src="{sugar_getimagepath file="start.gif"}" alt="Start" align="absmiddle" border="0" height="10" width="11">&nbsp;Start</a>&nbsp;&nbsp;
						{else}
							<img src="{sugar_getimagepath file="start_off.gif"}" alt="Start" align="absmiddle" border="0" height="10" width="11">&nbsp;Start&nbsp;&nbsp;
						{/if}
						{if $pageData.urls.prevPage}
							<a href="{$pageData.urls.prevPage}" {if $prerow}onclick="javascript:return sListView.save_checks(0, '{$moduleString}')"{/if} ><img src="{sugar_getimagepath file="previous.gif"}" alt="Previous" align="absmiddle" border="0" height="10" width="6">&nbsp;Previous</a>&nbsp;&nbsp;
						{else}
							<img src="{sugar_getimagepath file="previous_off.gif"}" alt="Previous" align="absmiddle" border="0" height="10" width="6">&nbsp;Previous&nbsp;&nbsp;
						{/if}
							<span class="pageNumbers">({$pageData.offsets.current+1} - {$pageData.offsets.next} of {if $pageData.offsets.totalCounted}{$pageData.offsets.total}{else}{$rowCount+1}}+{/if})</span>&nbsp;&nbsp;
						{if $pageData.urls.nextPage}
							<a href="{$pageData.urls.nextPage}" {if $prerow}onclick="javascript:return sListView.save_checks(40, '{$moduleString}')"{/if} >Next&nbsp;<img src="{sugar_getimagepath file="next.gif"}" alt="Next" align="absmiddle" border="0" height="10" width="6"></a>&nbsp;&nbsp;
						{else}
							&nbsp;&nbsp;Next&nbsp;<img src="{sugar_getimagepath file="next_off.gif"}" alt="Next" align="absmiddle" border="0" height="10" width="6">
						{/if}
						{if $pageData.urls.endPage}
							<a href="{$pageData.urls.endPage}" {if $prerow}onclick="javascript:return sListView.save_checks(980, '{$moduleString}')"{/if} >End&nbsp;<img src="{sugar_getimagepath file="end.gif"}" alt="End" align="absmiddle" border="0" height="10" width="11"></a></td>
						{else}
							&nbsp;&nbsp;Next&nbsp;<img src="{sugar_getimagepath file="next_off.gif"}" alt="Next" align="absmiddle" border="0" height="10" width="6">
						{/if}
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr height="20">
		{if $prerow}
			<td scope="col"  NOWRAP>{$checkall}</td>
		{/if}
		{foreach from=$displayColumns key=colHeader item=params}
			<td scope="col" width="{$params.width}" align="{$params.align}"  nowrap>
				<slot><a href="{$pageData.urls.orderBy}{$params.orderBy}" class="listViewThLinkS1">{$params.label}&nbsp;<img src="{smarty_function_sugar_getimagepath file='arrow.gif'}" alt="Sort" align="absmiddle" border="0"></a></slot>
			</td>
		{/foreach}
	</tr>
		
	{counter start=0 name=rowCounter print=false}
	{foreach from=$data key=id item=rowData}
		{if $rowCounter is even}
			{assign var="_rowColor" value=$rowColor[0]}
		{else}
			{assign var="_rowColor" value=$rowColor[1]}
		{/if}
		<tr height="20" class="{$_rowColor}S1">
			{if $prerow}
				<td><input onclick='sListView.check_item(this, document.MassUpdate)' type='checkbox' class='checkbox' name='mass[]' value='{$id}'></td>
			{/if}
			{foreach from=$displayColumns key=col item=params}
				<td scope='row' align="{$params.align|default:"left"}" valign="top"><slot>
					{if $params.link}
						{if $params.customCode}
							{sugar_evalcolumn_old var=$params.customCode rowData=$rowData}
						{else}
							<{$pageData.tag.$id.MAIN} href="index.php?action={$params.action|default:"DetailView"}&module={$params.module|default:$pageData.bean.moduleDir}&record={$rowData[$params.id]|default:$id}&offset={$pageData.offsets.current}&stamp={$pageData.stamp}" >{$rowData.$col}</{$pageData.tag.$id.MAIN}>
						{/if}
					{else}
						{$rowData.$col}
					{/if}
				</slot></td>
			{/foreach}
	    	</tr>
	 	
	 	{counter print=false}
	{/foreach}
	<tr class='pagination'>
		<td colspan="{$colCount}" align="right">
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td align="left">{$exportLink}{$mergeLink}{$selectedObjectsSpan}&nbsp;</td>
					<td align="right" nowrap="nowrap">
						{if $pageData.urls.startPage}
							<a href="{$pageData.urls.startPage}" onclick="javascript:return sListView.save_checks(0, '{$moduleString}')" ><img src="{sugar_getimagepath file="start.gif"}" alt="Start" align="absmiddle" border="0" height="10" width="11">&nbsp;Start</a>&nbsp;&nbsp;
						{else}
							<img src="{sugar_getimagepath file="start_off.gif"}" alt="Start" align="absmiddle" border="0" height="10" width="11">&nbsp;Start&nbsp;&nbsp;
						{/if}
						{if $pageData.urls.prevPage}
							<a href="{$pageData.urls.prevPage}" onclick="javascript:return sListView.save_checks(0, '{$moduleString}')" ><img src="{sugar_getimagepath file="previous.gif"}" alt="Previous" align="absmiddle" border="0" height="10" width="6">&nbsp;Previous</a>&nbsp;&nbsp;
						{else}
							<img src="{sugar_getimagepath file="previous_off.gif"}" alt="Previous" align="absmiddle" border="0" height="10" width="6">&nbsp;Previous&nbsp;&nbsp;
						{/if}
							<span class="pageNumbers">({$pageData.offsets.current+1} - {$pageData.offsets.next} of {if $pageData.offsets.totalCounted}{$pageData.offsets.end}{else}{$rowCount+1}}+{/if})</span>&nbsp;&nbsp;
						{if $pageData.urls.nextPage}
							<a href="{$pageData.urls.nextPage}" onclick="javascript:return sListView.save_checks(40, '{$moduleString}')" >Next&nbsp;<img src="{sugar_getimagepath file="next.gif"}" alt="Next" align="absmiddle" border="0" height="10" width="6"></a>&nbsp;&nbsp;
						{else}
							&nbsp;&nbsp;Next&nbsp;<img src="{sugar_getimagepath file="next_off.gif"}" alt="Next" align="absmiddle" border="0" height="10" width="6">
						{/if}
						{if $pageData.urls.endPage}
							<a href="{$pageData.urls.endPage}" onclick="javascript:return sListView.save_checks(980, '{$moduleString}')" >End&nbsp;<img src="{sugar_getimagepath file="end.gif"}" alt="End" align="absmiddle" border="0" height="10" width="11"></a></td>
						{else}
							&nbsp;&nbsp;Next&nbsp;<img src="{sugar_getimagepath file="next_off.gif"}" alt="Next" align="absmiddle" border="0" height="10" width="6">
						{/if}
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>