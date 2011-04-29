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

$viewdefs['Accounts']['EditView'] = array(
    'templateMeta' => array(
                            'form' => array('buttons'=>array('SAVE', 'CANCEL')),
                            'maxColumns' => '2', 
                            'widths' => array(
                                            array('label' => '10', 'field' => '30'),
                                            array('label' => '10', 'field' => '30'),
                                            ),
                            'includes'=> array(
                                            array('file'=>'modules/Accounts/Account.js'),
                                         ),
                           ),
                           
    'panels' => array(
	   'lbl_account_information'=>array(
		        array(
		        	array(
		        			'name'=>'name',
		        			 'label'=>'LBL_NAME',
		        			  'displayParams'=>array('required'=>true)
		        	), 
		        	array(
		        		'name'=>'phone_office',
		        		 'label'=>'LBL_PHONE_OFFICE'
		        	)
		       
				),
		        array(
		        	array(
		        		'name'=>'website', 
		        		'type'=>'link',
		        		'label'=>'LBL_WEBSITE'
		        	), 
		        	array(
		        		'name'=>'phone_fax', 
		        		 'label'=>'LBL_PHONE_FAX'
					)
		        ),
		        array(
		        	array(
		        		'name'=>'ticker_symbol',
		        		'label'=>'LBL_TICKER_SYMBOL'
		        	 ),
		        	 array(
		        	 	'name'=>'phone_alternate',
		        	 	 'label'=>'LBL_OTHER_PHONE'
		        	 	 )
		        ),
	        array(
	        	array('name'=>'parent_name','label' => 'LBL_MEMBER_OF'),
	        	array('name'=>'employees','label' => 'LBL_EMPLOYEES' )
	        ),	        
	         array(
	        	array('name'=>'ownership','label' => 'LBL_OWNERSHIP'),
	        	array('name'=>'rating','label' => 'LBL_RATING' )
	        ),
	        array(
	        	array('name'=>'industry','label' => 'LBL_INDUSTRY'),
	        	array('name'=>'sic_code','label' => 'LBL_SIC_CODE' )
	        ),
	        array(
	        	array('name'=>'account_type'),
	        	array('name'=>'annual_revenue','label' => 'LBL_ANNUAL_REVENUE' )
	        ),
            array(
                array('name'=>'campaign_name')
            ),
                array(
                	array('name'=>'assigned_user_name','label' =>'LBL_ASSIGNED_TO')
                )
	   ),
	   'lbl_address_information'=>array(
				array (
				      array (
					  'name' => 'billing_address_street',
				      'hideLabel'=> true,
				      'type' => 'address',
				      'displayParams'=>array('key'=>'billing', 'rows'=>2, 'cols'=>30, 'maxlength'=>150),
				      ),
				array (
				      'name' => 'shipping_address_street',
				      'hideLabel' => true,
				      'type' => 'address',
				      'displayParams'=>array('key'=>'shipping', 'copy'=>'billing', 'rows'=>2, 'cols'=>30, 'maxlength'=>150),      
				      ),
				),
	   ),
	   
  	   'lbl_email_addresses'=>array(
  				array('email1')
  	   ),
  	   
	   'lbl_description_information' =>array(
		        array(array('name'=>'description', 'displayParams'=>array('cols'=>80, 'rows'=>6),'label' => 'LBL_DESCRIPTION')),
	   ),
	    
    )
);
?>
