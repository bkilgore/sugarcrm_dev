<?php
// created: 2010-04-23 20:17:49
$viewdefs['Contacts']['EditView'] = array (
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
          'name' => 'last_name',
          'displayParams' => 
          array (
            'required' => true,
          ),
          'label' => 'LBL_LAST_NAME',
        ),
      ),
      1 => 
      array (
        0 => 
        array (
          'name' => 'personeels_id_c',
          'label' => 'LBL_PERSONEELS_ID',
        ),
        1 => 
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
      ),
      2 => 
      array (
        0 => 
        array (
          'name' => 'phone_work',
          'label' => 'LBL_OFFICE_PHONE',
        ),
        1 => 
        array (
          'name' => 'phone_mobile',
          'label' => 'LBL_MOBILE_PHONE',
        ),
      ),
      3 => 
      array (
        0 => 
        array (
          'name' => 'phone_other',
          'label' => 'LBL_OTHER_PHONE',
        ),
        1 => 
        array (
          'name' => 'lead_source',
          'label' => 'LBL_LEAD_SOURCE',
        ),
      ),
      4 => 
      array (
        0 => 
        array (
          'name' => 'phone_home',
          'label' => 'LBL_HOME_PHONE',
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
        1 => 
        array (
          'name' => 'title',
          'label' => 'LBL_TITLE',
        ),
      ),
      6 => 
      array (
        0 => 
        array (
          'name' => 'report_to_name',
          'label' => 'LBL_REPORTS_TO',
        ),
        1 => 
        array (
          'name' => 'contactpersoon_c',
          'label' => 'LBL_CONTACTPERSOON',
        ),
      ),
      7 => 
      array (
        0 => 
        array (
          'name' => 'birthdate',
          'label' => 'LBL_BIRTHDATE',
        ),
        1 => 
        array (
          'name' => 'assigned_user_name',
          'label' => 'LBL_ASSIGNED_TO_NAME',
        ),
      ),
      8 => 
      array (
        0 => 
        array (
          'name' => 'sync_contact',
          'label' => 'LBL_SYNC_CONTACT',
        ),
        1 => 
        array (
          'name' => 'team_name',
          'displayParams' => 
          array (
            'display' => true,
          ),
          'label' => 'LBL_TEAM',
        ),
      ),
    ),
    'lbl_address_information' => 
    array (
      0 => 
      array (
        0 => 
        array (
          'name' => 'nulmeting_sturen_c',
          'studio' => 'visible',
          'label' => 'LBL_NULMETING_STUREN',
        ),
        1 => 
        array (
          'name' => 'datum_nulmeeting_verzonden_c',
          'label' => 'LBL_DATUM_NULMEETING_VERZONDEN',
        ),
      ),
      1 => 
      array (
        0 => 
        array (
          'name' => 'deelnemende_bedrijven_c',
          'studio' => 'visible',
          'label' => 'LBL_DEELNEMENDE_BEDRIJVEN',
        ),
      ),
    ),
    'lbl_panel1' => 
    array (
      0 => 
      array (
        0 => 
        array (
          'name' => 'primary_address_street',
          'label' => 'LBL_PRIMARY_ADDRESS_STREET',
        ),
        1 => 
        array (
          'name' => 'alt_address_street',
          'label' => 'LBL_ALT_ADDRESS_STREET',
        ),
      ),
      1 => 
      array (
        0 => 
        array (
          'name' => 'primary_address_postalcode',
          'label' => 'LBL_PRIMARY_ADDRESS_POSTALCODE',
        ),
        1 => 
        array (
          'name' => 'alt_address_postalcode',
          'label' => 'LBL_ALT_ADDRESS_POSTALCODE',
        ),
      ),
      2 => 
      array (
        0 => 
        array (
          'name' => 'primary_address_city',
          'label' => 'LBL_PRIMARY_ADDRESS_CITY',
        ),
        1 => 
        array (
          'name' => 'alt_address_city',
          'label' => 'LBL_ALT_ADDRESS_CITY',
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
  ),
);
?>
