<?php
$listViewDefs ['Opportunities'] = 
array (
  'NAME' => 
  array (
    'width' => '30%',
    'label' => 'LBL_LIST_OPPORTUNITY_NAME',
    'link' => true,
    'default' => true,
  ),
  'SALES_STAGE' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_SALES_STAGE',
    'default' => true,
  ),
  'LEAD_SOURCE' => 
  array (
    'width' => '15%',
    'label' => 'LBL_LEAD_SOURCE',
    'default' => true,
  ),
  'REQREVBAND_C' => 
  array (
    'width' => '10%',
    'label' => 'LBL_REQREVBAND',
    'default' => true,
  ),
  'AMOUNT_USDOLLAR' => 
  array (
    'width' => '50%',
    'label' => 'LBL_LIST_AMOUNT + {$MY CUSTOMIZATION}',
    'align' => 'right',
    'default' => true,
    'currency_format' => true,
  ),
  'AIRVOLUMEDAY_C' => 
  array (
    'width' => '10%',
    'label' => 'LBL_AIRVOLUMEDAY',
    'default' => true,
  ),
  'TERRITORY_C' => 
  array (
    'width' => '10%',
    'label' => 'LBL_TERRITORY',
    'default' => true,
  ),
  'HOTACCOUNT_C' => 
  array (
    'width' => '10%',
    'label' => 'LBL_HOTACCOUNT',
    'default' => true,
  ),
  'DATE_CLOSED' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_DATE_CLOSED',
    'default' => true,
  ),
  'ROE_DENIED_C' => 
  array (
    'width' => '10%',
    'label' => 'LBL_ROE_DENIED',
    'default' => true,
  ),
  'SSRLEAD_C' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_SSRLEAD',
    'width' => '10%',
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '5%',
    'label' => 'LBL_LIST_ASSIGNED_USER',
    'default' => true,
  ),
  'ACCOUNT_NAME' => 
  array (
    'width' => '20%',
    'label' => 'LBL_LIST_ACCOUNT_NAME',
    'id' => 'ACCOUNT_ID',
    'module' => 'Accounts',
    'link' => true,
    'default' => false,
    'sortable' => true,
    'ACLTag' => 'ACCOUNT',
    'contextMenu' => 
    array (
      'objectType' => 'sugarAccount',
      'metaData' => 
      array (
        'return_module' => 'Contacts',
        'return_action' => 'ListView',
        'module' => 'Accounts',
        'parent_id' => '{$ACCOUNT_ID}',
        'parent_name' => '{$ACCOUNT_NAME}',
        'account_id' => '{$ACCOUNT_ID}',
        'account_name' => '{$ACCOUNT_NAME}',
      ),
    ),
    'related_fields' => 
    array (
      0 => 'account_id',
    ),
  ),
  'NEXT_STEP' => 
  array (
    'width' => '10%',
    'label' => 'LBL_NEXT_STEP',
    'default' => false,
  ),
  'OPPORTUNITY_TYPE' => 
  array (
    'width' => '10%',
    'label' => 'LBL_TYPE',
    'default' => false,
  ),
  'TEAM_NAME' => 
  array (
    'width' => '5%',
    'label' => 'LBL_LIST_TEAM',
    'default' => false,
  ),
  'PROBABILITY' => 
  array (
    'width' => '10%',
    'label' => 'LBL_PROBABILITY',
    'default' => false,
  ),
  'DATEREQUESTED_C' => 
  array (
    'width' => '10%',
    'label' => 'LBL_DATEREQUESTED',
    'default' => false,
  ),
  'AMOUNT' => 
  array (
    'width' => '10%',
    'label' => 'LBL_AMOUNT',
    'currency_format' => true,
    'default' => false,
  ),
  'DATE_ENTERED' => 
  array (
    'width' => '10%',
    'label' => 'LBL_DATE_ENTERED',
    'default' => false,
  ),
  'CREATED_BY_NAME' => 
  array (
    'width' => '10%',
    'label' => 'LBL_CREATED',
    'default' => false,
  ),
  'MODIFIED_BY_NAME' => 
  array (
    'width' => '5%',
    'label' => 'LBL_MODIFIED',
    'default' => false,
  ),
);
?>