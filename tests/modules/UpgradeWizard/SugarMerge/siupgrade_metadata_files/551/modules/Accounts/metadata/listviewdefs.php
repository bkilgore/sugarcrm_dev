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




$listViewDefs['Accounts'] = array(
	'NAME' => array(
		'width' => '20', 
		'label' => 'LBL_LIST_ACCOUNT_NAME', 
		'link' => true,
        'default' => true), 
	'BILLING_ADDRESS_CITY' => array(
		'width' => '10', 
		'label' => 'LBL_LIST_CITY',
        'default' => true 
		),
	'PHONE_OFFICE' => array(
		'width' => '10', 
		'label' => 'LBL_LIST_PHONE',
        'default' => true),
    'ACCOUNT_TYPE' => array(
        'width' => '10', 
        'label' => 'LBL_TYPE'),
    'INDUSTRY' => array(
        'width' => '10', 
        'label' => 'LBL_INDUSTRY'),
    'ANNUAL_REVENUE' => array(
        'width' => '10', 
        'label' => 'LBL_ANNUAL_REVENUE'),
    'PHONE_FAX' => array(
        'width' => '10', 
        'label' => 'LBL_PHONE_FAX'),
    'BILLING_ADDRESS_STREET' => array(
        'width' => '15', 
        'label' => 'LBL_BILLING_ADDRESS_STREET'),
    'BILLING_ADDRESS_STATE' => array(
        'width' => '7', 
        'label' => 'LBL_BILLING_ADDRESS_STATE'),
    'BILLING_ADDRESS_POSTALCODE' => array(
        'width' => '10', 
        'label' => 'LBL_BILLING_ADDRESS_POSTALCODE'),
    'BILLING_ADDRESS_COUNTRY' => array(
        'width' => '10', 
        'label' => 'LBL_BILLING_ADDRESS_COUNTRY'),
    'SHIPPING_ADDRESS_STREET' => array(
        'width' => '15', 
        'label' => 'LBL_SHIPPING_ADDRESS_STREET'),
    'SHIPPING_ADDRESS_CITY' => array(
        'width' => '10', 
        'label' => 'LBL_SHIPPING_ADDRESS_CITY'),
    'SHIPPING_ADDRESS_STATE' => array(
        'width' => '7', 
        'label' => 'LBL_SHIPPING_ADDRESS_STATE'),
    'SHIPPING_ADDRESS_POSTALCODE' => array(
        'width' => '10', 
        'label' => 'LBL_SHIPPING_ADDRESS_POSTALCODE'),
    'SHIPPING_ADDRESS_COUNTRY' => array(
        'width' => '10', 
        'label' => 'LBL_SHIPPING_ADDRESS_COUNTRY'),
    'RATING' => array(
        'width' => '10', 
        'label' => 'LBL_RATING'),
    'PHONE_ALTERNATE' => array(
        'width' => '10', 
        'label' => 'LBL_OTHER_PHONE'),
    'WEBSITE' => array(
        'width' => '10', 
        'label' => 'LBL_WEBSITE'),
    'OWNERSHIP' => array(
        'width' => '10', 
        'label' => 'LBL_OWNERSHIP'),
    'EMPLOYEES' => array(
        'width' => '10', 
        'label' => 'LBL_EMPLOYEES'),
    'SIC_CODE' => array(
        'width' => '10', 
        'label' => 'LBL_SIC_CODE'),
    'TICKER_SYMBOL' => array(
        'width' => '10', 
        'label' => 'LBL_TICKER_SYMBOL'),
    'DATE_MODIFIED' => array(
        'width' => '5', 
        'label' => 'LBL_DATE_MODIFIED'),
    'DATE_ENTERED' => array(
        'width' => '5', 
        'label' => 'LBL_DATE_ENTERED'),
    'CREATED_BY_NAME' => array(
        'width' => '10', 
        'label' => 'LBL_CREATED'),
    'ASSIGNED_USER_NAME' => array(
        'width' => '10', 
        'label' => 'LBL_LIST_ASSIGNED_USER',
        'default' => true),
    'MODIFIED_BY_NAME' => array(
        'width' => '10', 
        'label' => 'LBL_MODIFIED')
);
?>
