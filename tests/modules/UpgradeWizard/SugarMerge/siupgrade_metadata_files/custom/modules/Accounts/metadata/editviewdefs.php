<?php
$viewdefs ['Accounts'] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'SAVE',
          1 => 'CANCEL',
        ),
      ),
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'includes' => 
      array (
        0 => 
        array (
          'file' => 'modules/Accounts/Account.js',
        ),
      ),
    ),
    'panels' => 
    array (
      'lbl_account_information' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'name',
            'label' => 'LBL_NAME',
            'displayParams' => 
            array (
              'required' => true,
            ),
          ),
          1 => 
          array (
            'name' => 'phone_office',
            'label' => 'LBL_PHONE_OFFICE',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'high_prio_c',
            'label' => 'High_Priority_Account_c',
          ),
          1 => NULL,
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'website',
            'type' => 'link',
            'label' => 'LBL_WEBSITE',
          ),
          1 => 
          array (
            'name' => 'phone_fax',
            'label' => 'LBL_PHONE_FAX',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'ticker_symbol',
            'label' => 'LBL_TICKER_SYMBOL',
          ),
          1 => 
          array (
            'name' => 'phone_alternate',
            'label' => 'LBL_OTHER_PHONE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'parent_name',
            'label' => 'LBL_MEMBER_OF',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'employees',
            'label' => 'LBL_EMPLOYEES',
          ),
          1 => NULL,
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'ownership',
            'label' => 'LBL_OWNERSHIP',
          ),
          1 => 
          array (
            'name' => 'rating',
            'label' => 'LBL_RATING',
          ),
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'industry',
            'label' => 'LBL_INDUSTRY',
          ),
          1 => 
          array (
            'name' => 'sic_code',
            'label' => 'LBL_SIC_CODE',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'account_type',
            'label' => 'LBL_TYPE',
            'customCode' => '
<select name="{$fields.account_type.name}" id="{$fields.account_type.name}" title=\'\' tabindex="0" OnChange=\'checkAccountTypeDependentDropdown({$ref_code_param})\' >
{if isset($fields.account_type.value) && $fields.account_type.value != \'\'}
{html_options options=$fields.account_type.options selected=$fields.account_type.value}
{else}
{html_options options=$fields.account_type.options selected=$fields.account_type.default}
{/if}
</select>
<script src=\'custom/include/javascript/custom_javascript.js\'></script>
',
          ),
          1 => 
          array (
            'name' => 'annual_revenue',
            'label' => 'LBL_ANNUAL_REVENUE',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'reference_code_c',
            'label' => 'LBL_REFERENCE_CODE_C',
          ),
          1 => 
          array (
            'name' => 'ref_code_expiration_c',
            'label' => 'LBL_REF_CODE_EXPIRATION',
          ),
        ),
        10 => 
        array (
			NULL,
          1 => 
          array (
            'name' => 'code_customized_by_c',
            'label' => 'LBL_CODE_CUSTOMIZED_BY',
          ),
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'resell_discount',
            'label' => 'LBL_RESELL_DISCOUNT',
          ),
          1 => 
          array (
            'name' => 'Support_Service_Level_c',
            'label' => 'Support Service Level_0',
          ),
        ),
        12 => 
        array (
          0 => 
          array (
            'name' => 'Partner_Type_c',
            'label' => 'partner_Type__c',
          ),
          1 => 
          array (
            'name' => 'deployment_type_c',
            'label' => 'Deployment_Type__c',
          ),
        ),
        13 => 
        array (
          0 => 
          array (
            'name' => 'team_name',
            'label' => 'LBL_LIST_TEAM',
            'displayParams' => 
            array (
              'display' => true,
            ),
          ),
          1 => array(
			'name' => 'renewal_contact_c',
			'label' => 'LBL_RENEWAL_CONTACT_C',
'displayParams' => array('initial_filter' => '&account_name_advanced={$fields.name.value}'),
			),
        ),
        14 => 
        array (
          0 => 
          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO',
          ),
		  1 =>
		  array (
			'name' => 'auto_send_renewal_emails_c',
			'label' => 'LBL_AUTO_SEND_RENEWAL_EMAILS',
		  ),
        ),
      ),
      'lbl_panel5' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'customer_reference_c',
            'label' => 'LBL_CUSTOMER_REFERENCE',
          ),
          1 => 
          array (
            'name' => 'type_of_reference_c',
            'label' => 'LBL_TYPE_OF_REFERENCE',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'reference_contact_c',
            'label' => 'LBL_REFERENCE_CONTACT',
          ),
          1 => 
          array (
            'name' => 'last_used_as_reference_c',
            'label' => 'LBL_LAST_USED_AS_REFERENCE',
          ),
        ),
        2 => 
        array (
          0 => NULL,
          1 => 
          array (
            'name' => 'reference_status_c',
            'label' => 'LBL_REFERENCE_STATUS',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'reference_notes_c',
            'label' => 'LBL_REFERENCE_NOTES',
          ),
          1 => 
          array (
            'name' => 'last_used_reference_notes_c',
            'label' => 'LBL_LAST_USED_REFERENCE_NOTES',
          ),
        ),
      ),
      'lbl_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'training_credits_purchased_c',
            'label' => 'Learning_Credits_Purchased__c',
          ),
          1 => 
          array (
            'name' => 'remaining_training_credits_c',
            'label' => 'Remaining_Learning_Credits__c',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'training_credits_pur_date_c',
            'label' => 'Most_Recent_Credits_Purchase_Date_c',
          ),
          1 => 
          array (
            'name' => 'training_credits_exp_date_c',
            'label' => 'Upcoming_Credits_Expiration_Date__c',
          ),
        ),
      ),
      'LBL_PANEL6' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'support_cases_purchased_c',
            'label' => 'Support_Cases_Purchased__c',
          ),
          1 => 
          array (
            'name' => 'remaining_support_cases_c',
            'label' => 'Remaining_Support_Cases__c',
          ),
        ),
      ),
      'lbl_panel4' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'dce_auth_user_c',
            'label' => 'LBL_DCE_AUTH_USER',
          ),
          1 => 
          array (
            'name' => 'dce_app_id_c',
            'label' => 'LBL_DCE_APP_ID',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'dce_auth_pass_c',
            'label' => 'LBL_DCE_AUTH_PASSWORD',
          ),
          1 => NULL,
        ),
      ),
      'lbl_address_information' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'billing_address_street',
            'hideLabel' => true,
            'type' => 'address',
            'displayParams' => 
            array (
              'key' => 'billing',
              'rows' => 2,
              'cols' => 30,
              'maxlength' => 150,
            ),
            'label' => 'LBL_BILLING_ADDRESS_STREET',
          ),
          1 => 
          array (
            'name' => 'shipping_address_street',
            'hideLabel' => true,
            'type' => 'address',
            'displayParams' => 
            array (
              'key' => 'shipping',
              'copy' => 'billing',
              'rows' => 2,
              'cols' => 30,
              'maxlength' => 150,
            ),
            'label' => 'LBL_SHIPPING_ADDRESS_STREET',
          ),
        ),
      ),
      'lbl_email_addresses' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'email1',
            'label' => 'LBL_EMAIL',
          ),
        ),
      ),
      'lbl_description_information' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'description',
            'displayParams' => 
            array (
              'cols' => 80,
              'rows' => 6,
            ),
            'label' => 'LBL_DESCRIPTION',
          ),
        ),
      ),
    ),
  ),
);
?>
