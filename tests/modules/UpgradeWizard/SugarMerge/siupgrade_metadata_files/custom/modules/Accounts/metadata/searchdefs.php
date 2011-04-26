<?php
$searchdefs ['Accounts'] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'label' => 'LBL_NAME',
        'default' => true,
      ),
      'billing_address_city' => 
      array (
        'name' => 'billing_address_city',
        'label' => 'LBL_BILLING_ADDRESS_CITY',
        'default' => true,
      ),
      'phone_office' => 
      array (
        'name' => 'phone_office',
        'label' => 'LBL_PHONE_OFFICE',
        'default' => true,
      ),
      'address_street' => 
      array (
        'name' => 'address_street',
        'label' => 'LBL_BILLING_ADDRESS',
        'type' => 'name',
        'group' => 'billing_address_street',
        'default' => true,
      ),
      'website' => 
      array (
        'width' => '10%',
        'label' => 'LBL_WEBSITE',
        'default' => true,
        'name' => 'website',
      ),
      'current_user_only' => 
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'default' => true,
      ),
    ),
    'advanced_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'label' => 'LBL_NAME',
        'default' => true,
        'width' => '10%',
      ),
      'address_street' => 
      array (
        'name' => 'address_street',
        'label' => 'LBL_ANY_ADDRESS',
        'type' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'phone' => 
      array (
        'name' => 'phone',
        'label' => 'LBL_ANY_PHONE',
        'type' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'website' => 
      array (
        'name' => 'website',
        'label' => 'LBL_WEBSITE',
        'default' => true,
        'width' => '10%',
      ),
      'address_city' => 
      array (
        'name' => 'address_city',
        'label' => 'LBL_CITY',
        'type' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'email' => 
      array (
        'name' => 'email',
        'label' => 'LBL_ANY_EMAIL',
        'type' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'annual_revenue' => 
      array (
        'name' => 'annual_revenue',
        'label' => 'LBL_ANNUAL_REVENUE',
        'default' => true,
        'width' => '10%',
      ),
      'address_state' => 
      array (
        'name' => 'address_state',
        'label' => 'LBL_STATE',
        'type' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'employees' => 
      array (
        'name' => 'employees',
        'label' => 'LBL_EMPLOYEES',
        'default' => true,
        'width' => '10%',
      ),
      'address_postalcode' => 
      array (
        'name' => 'address_postalcode',
        'label' => 'LBL_POSTAL_CODE',
        'type' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'billing_address_country' => 
      array (
        'name' => 'billing_address_country',
        'label' => 'LBL_COUNTRY',
        'type' => 'enum',
        'options' => 'countries_dom',
        'default' => true,
        'width' => '10%',
      ),
      'ticker_symbol' => 
      array (
        'name' => 'ticker_symbol',
        'label' => 'LBL_TICKER_SYMBOL',
        'default' => true,
        'width' => '10%',
      ),
      'sic_code' => 
      array (
        'name' => 'sic_code',
        'label' => 'LBL_SIC_CODE',
        'default' => true,
        'width' => '10%',
      ),
      'rating' => 
      array (
        'name' => 'rating',
        'label' => 'LBL_RATING',
        'default' => true,
        'width' => '10%',
      ),
      'ownership' => 
      array (
        'name' => 'ownership',
        'label' => 'LBL_OWNERSHIP',
        'default' => true,
        'width' => '10%',
      ),
      'assigned_user_id' => 
      array (
        'name' => 'assigned_user_id',
        'type' => 'enum',
        'label' => 'LBL_ASSIGNED_TO',
        'function' => 
        array (
          'name' => 'get_user_array',
          'params' => 
          array (
            0 => false,
          ),
        ),
        'default' => true,
        'sortable' => false,
        'width' => '10%',
      ),
      'account_type' => 
      array (
        'name' => 'account_type',
        'label' => 'LBL_TYPE',
        'default' => true,
        'sortable' => false,
        'width' => '10%',
      ),
      'industry' => 
      array (
        'name' => 'industry',
        'label' => 'LBL_INDUSTRY',
        'default' => true,
        'sortable' => false,
        'width' => '10%',
      ),
    ),
  ),
  'templateMeta' => 
  array (
    'maxColumns' => '3',
    'widths' => 
    array (
      'label' => '10',
      'field' => '30',
    ),
  ),
);
?>
