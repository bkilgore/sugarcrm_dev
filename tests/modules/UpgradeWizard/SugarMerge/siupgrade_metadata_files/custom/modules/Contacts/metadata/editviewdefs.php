<?php
$viewdefs ['Contacts'] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'hidden' => 
        array (
          0 => '<input type="hidden" name="opportunity_id" value="{$smarty.request.opportunity_id}">',
          1 => '<input type="hidden" name="case_id" value="{$smarty.request.case_id}">',
          2 => '<input type="hidden" name="bug_id" value="{$smarty.request.bug_id}">',
          3 => '<input type="hidden" name="email_id" value="{$smarty.request.email_id}">',
          4 => '<input type="hidden" name="inbound_email_id" value="{$smarty.request.inbound_email_id}">',
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
    ),
    'panels' => 
    array (
      'lbl_contact_information' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'first_name',
            'customCode' => '{html_options name="salutation" options=$fields.salutation.options selected=$fields.salutation.value}&nbsp;<input name="first_name" size="25" maxlength="25" type="text" value="{$fields.first_name.value}">',
            'label' => 'LBL_FIRST_NAME',
          ),
          1 => 
          array (
            'name' => 'phone_work',
            'label' => 'LBL_OFFICE_PHONE',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'last_name',
            'displayParams' => 
            array (
              'required' => true,
            ),
            'label' => 'LBL_LAST_NAME',
          ),
          1 => 
          array (
            'name' => 'phone_mobile',
            'label' => 'LBL_MOBILE_PHONE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'account_name',
            'displayParams' => 
            array (
              'key' => 'billing',
              'copy' => 'primary',
              'billingKey' => 'primary',
              'additionalFields' => 
              array (
                'phone_office' => 'phone_work',
              ),
            ),
            'label' => 'LBL_ACCOUNT_NAME',
          ),
          1 => 
          array (
            'name' => 'phone_home',
            'label' => 'LBL_HOME_PHONE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'lead_source',
            'label' => 'LBL_LEAD_SOURCE',
          ),
          1 => 
          array (
            'name' => 'phone_other',
            'label' => 'LBL_OTHER_PHONE',
          ),
        ),
        4 => 
        array (
          0 => 
          array (
            'name' => 'title',
            'label' => 'LBL_TITLE',
          ),
          1 => 
          array (
            'name' => 'phone_fax',
            'label' => 'LBL_FAX_PHONE',
          ),
        ),
        5 => 
        array (
          0 => 
          array (
            'name' => 'department',
            'label' => 'LBL_DEPARTMENT',
          ),
          1 => NULL,
        ),
        6 => 
        array (
          0 => 
          array (
            'name' => 'birthdate',
            'label' => 'LBL_BIRTHDATE',
          ),
          1 => NULL,
        ),
        7 => 
        array (
          0 => 
          array (
            'name' => 'report_to_name',
            'label' => 'LBL_REPORTS_TO',
          ),
          1 => 
          array (
            'name' => 'assistant',
            'label' => 'LBL_ASSISTANT',
          ),
        ),
        8 => 
        array (
          0 => 
          array (
            'name' => 'technical_proficiency_',
            'label' => 'LBL_TECHNICAL_PROFICIENCY_',
          ),
          1 => 
          array (
            'name' => 'assistant_phone',
            'label' => 'LBL_ASSISTANT_PHONE',
          ),
        ),
        9 => 
        array (
          0 => 
          array (
            'name' => 'do_not_call',
            'label' => 'LBL_DO_NOT_CALL',
          ),
          1 => NULL,
        ),
        10 => 
        array (
          0 => 
          array (
            'name' => 'team_name',
            'displayParams' => 
            array (
              'display' => true,
            ),
            'label' => 'LBL_TEAM',
          ),
          1 => 
          array (
            'name' => 'sync_contact',
            'label' => 'LBL_SYNC_CONTACT',
          ),
        ),
        11 => 
        array (
          0 => 
          array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO_NAME',
          ),
          1 => NULL,
        ),
        12 => 
        array (
          0 => NULL,
          1 => 
          array (
            'name' => 'primary_business_c',
            'label' => 'Primary_Business_Contact__c',
          ),
        ),
        13 => 
        array (
          0 => NULL,
          1 => 
          array (
            'name' => 'support_authorized_c',
            'label' => 'Support_Authorized_Contact__c',
          ),
        ),
        14 => 
        array (
          0 => NULL,
          1 => 
          array (
            'name' => 'university_enabled_c',
            'label' => 'LBL_UNIVERSITY_ENABLED',
          ),
        ),
        15 => 
        array (
          0 => NULL,
          1 => 
          array (
            'name' => 'billing_contact_c',
            'label' => 'Billing_Contact__c',
          ),
        ),
        16 => 
        array (
          0 => NULL,
          1 => 
          array (
            'name' => 'oppq_active_c',
            'label' => 'LBL_OPPQ_ACTIVE_C',
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
            'label' => 'LBL_EMAIL_ADDRESS',
          ),
        ),
      ),
      'lbl_address_information' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'primary_address_street',
            'hideLabel' => true,
            'type' => 'address',
            'displayParams' => 
            array (
              'key' => 'primary',
              'rows' => 2,
              'cols' => 30,
              'maxlength' => 150,
            ),
            'label' => 'LBL_PRIMARY_ADDRESS_STREET',
          ),
          1 => 
          array (
            'name' => 'alt_address_street',
            'hideLabel' => true,
            'type' => 'address',
            'displayParams' => 
            array (
              'key' => 'alt',
              'copy' => 'primary',
              'rows' => 2,
              'cols' => 30,
              'maxlength' => 150,
            ),
            'label' => 'LBL_ALT_ADDRESS_STREET',
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
              'rows' => 6,
              'cols' => 80,
            ),
            'label' => 'LBL_DESCRIPTION',
          ),
        ),
      ),
      'lbl_portal_information' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'portal_name',
		 
            'customCode' => '<table border="0" cellspacing="0" cellpadding="0"><tr><td>
<input id="portal_name" name="portal_name" type="text" size="30" maxlength="30" value="{$fields.portal_name.value}">
<input type="hidden" id="portal_name_existing" value="{$fields.portal_name.value}">

<input type="button" name="btn_{$fields.portal_name.name}" title="{$APP.LBL_SELECT_BUTTON_TITLE}" accessKey="{$APP.LBL_SELECT_BUTTON_KEY}" class="button" value="{$APP.LBL_SELECT_BUTTON_LABEL}" onclick=\'window.open("custom/si_custom_files/choose_portal_name_popup.php?check_email="+this.form.emailAddress0.value, "choose_popup", "status=1, toolbar=1, menubar=1, location=1, scrollbars=1, width=600, height=400"); return false;\'>

<input type="button" name="btn_clr_{$fields.portal_name.name}" tabindex="2" title="{$APP.LBL_CLEAR_BUTTON_TITLE}" accessKey="{$APP.LBL_CLEAR_BUTTON_KEY}" class="button" onclick="this.form.{$fields.portal_name.name}.value = \'\';" value="{$APP.LBL_CLEAR_BUTTON_LABEL}">

</td><tr><tr><td><input type="hidden" id="portal_name_verified" value="true"></td></tr></table>',
		 
            'label' => 'LBL_PORTAL_NAME',
          ),
          1 => 
          array (
            'name' => 'portal_active',
            'label' => 'LBL_PORTAL_ACTIVE',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'portal_password1',
            'type' => 'password',
            'customCode' => '<input id="portal_password1" name="portal_password1" type="password" size="32" maxlength="32" value="{$fields.portal_password.value}">',
            'label' => 'LBL_PORTAL_PASSWORD',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'portal_password',
            'customCode' => '<input id="portal_password" name="portal_password" type="password" size="32" maxlength="32" value="{$fields.portal_password.value}"><input name="old_portal_password" type="hidden" value="{$fields.portal_password.value}">',
            'label' => 'LBL_CONFIRM_PORTAL_PASSWORD',
          ),
        ),
      ),
      'lbl_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'dce_user_name_c',
            'label' => 'LBL_DCE_USER_NAME',
          ),
          1 => 
          array (
            'name' => 'licensing_rights_c',
            'label' => 'LBL_LICENSING_RIGHTS',
          ),
        ),
      ),
    ),
  ),
);
?>
