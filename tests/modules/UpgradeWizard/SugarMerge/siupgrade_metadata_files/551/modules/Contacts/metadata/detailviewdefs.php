<?php
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

$viewdefs['Contacts']['DetailView'] = array(
'templateMeta' => array('form' => array('buttons'=>array('EDIT', 'DUPLICATE', 'DELETE', 'FIND_DUPLICATES',
                                                         array('customCode'=>'<input title="{$APP.LBL_MANAGE_SUBSCRIPTIONS}" class="button" onclick="this.form.return_module.value=\'Contacts\'; this.form.return_action.value=\'DetailView\'; this.form.return_id.value=\'{$fields.id.value}\'; this.form.action.value=\'Subscriptions\'; this.form.module.value=\'Campaigns\'; this.form.module_tab.value=\'Contacts\';" type="submit" name="Manage Subscriptions" value="{$APP.LBL_MANAGE_SUBSCRIPTIONS}">'),
                                                        ),
                                       ),
                        'maxColumns' => '2', 
                        'widths' => array(
                                        array('label' => '10', 'field' => '30'), 
                                        array('label' => '10', 'field' => '30')
                                        ),
                        'includes'=> array(
                            			array('file'=>'modules/Leads/Lead.js'),
                         				),		                
                        ),
'panels' =>array (
   'default'=>array(
      array (
	    array (
	      'name' => 'full_name',
	      'label' => 'LBL_NAME',
	    ),
	
	    array (
	      'name' => 'phone_work',
	      'label' => 'LBL_OFFICE_PHONE',
	    ),
	  ),
	  
	  array (
	    'account_name',
	    
	    array (
	      'name' => 'phone_mobile',
	      'label' => 'LBL_MOBILE_PHONE',
	    ),
	  ),
	  
	  array (
	    'lead_source',
	    
	    array (
	      'name' => 'phone_home',
	      'label' => 'LBL_HOME_PHONE',
	    ),
	  ),
	  
	  array (
	    
	    array (
	      'name' => 'campaign_name',
	      'label' => 'LBL_CAMPAIGN',
	    ),
	    
	    array (
	      'name' => 'phone_other',
	      'label' => 'LBL_OTHER_PHONE',
	    ),
	  ),
	  
	  array (
	    'title',
	    array (
	      'name' => 'phone_fax',
	      'label' => 'LBL_FAX_PHONE',
	    ),
	  ),
	  
	  array (
	    'department',
	    'birthdate',
	  ),
	  
	  array (
	    'report_to_name',
	    'assistant',
	  ),
	  
	  array (
	    'sync_contact',
	    'assistant_phone',
	  ),
	  
	  array (
	    'do_not_call',
	    '',
	  ),
	  
	  array (
	    
	    array (
	      'name' => 'date_modified',
	      'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
	      'label' => 'LBL_DATE_MODIFIED',
	    ),
	  ),
	  
	  array (
	    'assigned_user_name',
	    
	    array (
	      'name' => 'date_entered',
	      'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
	      'label' => 'LBL_DATE_ENTERED',
	    ),
	  ),
	  
	  array (
	      array (
		      'name' => 'primary_address_street',
		      'label'=> 'LBL_PRIMARY_ADDRESS',
		      'type' => 'address',
		      'displayParams'=>array('key'=>'primary'),
	      ),
	      
	      array (
		      'name' => 'alt_address_street',
		      'label'=> 'LBL_ALTERNATE_ADDRESS',
		      'type' => 'address',
		      'displayParams'=>array('key'=>'alt'),      
	      ),
	  ),
	  array (
	    'description',
	  ),
	  
	  array (
	    'email1',
	  ),
   ),
)


   
);
?>