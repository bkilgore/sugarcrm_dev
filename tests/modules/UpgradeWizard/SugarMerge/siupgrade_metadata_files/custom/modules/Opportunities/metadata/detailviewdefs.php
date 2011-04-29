<?php
// created: 2010-03-12 15:05:37
$viewdefs = array (
  'Opportunities' => 
  array (
    'DetailView' => 
    array (
      'templateMeta' => 
      array (
        'form' => 
        array (
          'buttons' => 
          array (
            0 => 'EDIT',
            1 => 'DUPLICATE',
            2 => 'DELETE',
            3 => 
            array (
              'customCode' => '<input title="{$APP.LBL_DUP_MERGE}" accesskey="M" class="button" onclick="this.form.return_module.value=\'Opportunities\';this.form.return_action.value=\'DetailView\';this.form.return_id.value=\'{$fields.id.value}\'; this.form.action.value=\'Step1\'; this.form.module.value=\'MergeRecords\';" name="button" value="{$APP.LBL_DUP_MERGE}" type="submit">',
            ),
            4 => 
            array (
              'customCode' => '<input title="Close Wizard" accesskey="Z" class="button" onclick="this.form.return_module.value=\'Opportunities\';this.form.return_action.value=\'DetailView\';this.form.return_id.value=\'{$fields.id.value}\'; this.form.action.value=\'OpportunityWizard\'; this.form.module.value=\'Opportunities\';" name="button" value="Close Wizard" type="submit">',
            ),
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
        'default' => 
        array (
          0 => 
          array (
            0 => 
            array (
              'name' => 'name',
              'label' => 'LBL_OPPORTUNITY_NAME',
            ),
            1 => 
            array (
              'name' => 'amount',
              'label' => '{$MOD.LBL_AMOUNT} ({$CURRENCY})',
            ),
          ),
          1 => 
          array (
            0 => 
            array (
              'name' => 'account_name',
              'label' => 'LBL_ACCOUNT_NAME',
            ),
            1 => 
            array (
              'name' => 'date_closed',
              'label' => 'LBL_DATE_CLOSED',
            ),
          ),
          '1.5' => 
          array (
            0 => 
            array (
              'name' => NULL,
              'displayParams' => 
              array (
              ),
            ),
            1 => 
            array (
              'name' => 'discount_code_c',
              'label' => 'LBL_DISCOUNT_CODE',
            ),
          ),
          2 => 
          array (
            0 => 
            array (
              'name' => 'opportunity_type',
              'label' => 'LBL_TYPE',
            ),
            1 => 
            array (
              'name' => 'users',
              'label' => 'LBL_USERS_1',
            ),
          ),
          3 => 
          array (
            0 => 
            array (
              'name' => 'email_client',
              'label' => 'LBL_EMAIL_CLIENT',
            ),
            1 => 
            array (
              'name' => 'additional_support_cases_c',
              'label' => 'Additional_Support_Cases__c',
            ),
          ),
          4 => 
          array (
            0 => 
            array (
              'name' => 'processed_by_moofcart_c',
              'label' => 'LBL_PROCESSED_BY_MOOFCART',
            ),
            1 => 
            array (
              'name' => 'additional_training_credits_c',
              'label' => 'Learning_Credits__c',
            ),
          ),
          5 => 
          array (
            0 => 
            array (
              'name' => 'trial_name_c',
              'label' => 'Trial URL',
            ),
            1 => 
            array (
              'name' => 'sales_stage',
              'label' => 'LBL_SALES_STAGE',
            ),
          ),
          6 => 
          array (
            0 => 
            array (
              'name' => 'trial_expiration_c',
              'label' => 'Trial Expiration',
              'customCode' => '{php} global $timedate; $this->assign("jmo_trialExpirationDB", $timeDate->to_db($this->_tpl_vars["fields"]["trial_expiration_c"]["value"])); {/php} {$fields.trial_expiration_c.value}{if $fields.trial_extended_c.value eq 0 && !empty($fields.trial_name_c.value) && strtotime("now") < $jmo_trialExpirationDB|date_format:"%s"} &nbsp; <button type="submit" onClick="document.location=\'/scripts/7daytrials/trials.php?opportunity_id={$fields.id.value}\';">Extend Trial</button>{/if}',
            ),
            1 => 
            array (
              'name' => 'probability',
              'label' => 'LBL_PROBABILITY',
            ),
          ),
          7 => 
          array (
            0 => 
            array (
              'name' => 'campaign_name',
              'label' => 'LBL_CAMPAIGN',
            ),
            1 => 'score_c',
          ),
          8 => 
          array (
            0 => 
            array (
              'name' => 'lead_source',
              'label' => 'LBL_LEAD_SOURCE',
            ),
            1 => NULL,
          ),
          9 => 
          array (
            0 => 
            array (
              'name' => 'current_solution',
              'label' => 'LBL_CURRENT_SOLUTION',
            ),
            1 => 
            array (
              'name' => 'Term_c',
              'label' => 'Term__c',
            ),
          ),
          10 => 
          array (
            0 => 
            array (
              'name' => 'competitor_1',
              'label' => 'LBL_COMPETITOR_1',
            ),
            1 => 
            array (
              'name' => 'Revenue_Type_c',
              'label' => 'Revenue_Type__c',
            ),
          ),
          11 => 
          array (
            0 => 
            array (
              'name' => 'competitor_2',
              'label' => 'LBL_COMPETITOR_2',
            ),
            1 => 
            array (
              'name' => 'renewal_date_c',
              'label' => 'Renewal_Date_c',
            ),
          ),
          12 => 
          array (
            0 => 
            array (
              'name' => 'competitor_3',
              'label' => 'LBL_COMPETITOR_3',
            ),
            1 => 
            array (
              'name' => 'order_number',
              'label' => 'LBL_ORDER_NUMBER',
              'customCode' => '<a href="http://www.sugarcrm.com/sugarshop/admin/order.php?orderid={$fields.order_number.value}">{$fields.order_number.value}</a>',
            ),
          ),
          13 => 
          array (
            0 => 
            array (
              'name' => 'competitor_expiration_c',
              'label' => 'LBL_COMPETITOR_EXPIRATION',
            ),
            1 => 
            array (
              'name' => 'order_type_c',
              'label' => 'LBL_ORDER_TYPE_C',
            ),
          ),
          14 => 
          array (
            0 => NULL,
            1 => 
            array (
              'name' => 'next_step',
              'label' => 'LBL_NEXT_STEP',
            ),
          ),
          15 => 
          array (
            0 => 
            array (
              'name' => 'demo_c',
              'label' => 'Demo_1',
            ),
            1 => 
            array (
              'name' => 'next_step_due_date',
              'label' => 'LBL_NEXT_STEP_DUE_DATE',
            ),
          ),
          16 => 
          array (
            0 => 
            array (
              'name' => 'demo_date_c',
              'label' => 'Demo Date',
            ),
            1 => 
            array (
              'name' => 'top20deal_c',
              'label' => 'LBL_TOP20DEAL',
            ),
          ),
          17 => 
          array (
            0 => 
            array (
              'name' => 'evaluation',
              'label' => 'LBL_EVALUATION',
            ),
            1 => 
            array (
              'name' => 'closed_lost_reason_c',
              'label' => 'LBL_CLOSED_LOST_REASON_C',
            ),
          ),
          18 => 
          array (
            0 => 
            array (
              'name' => 'evaluation_start_date',
              'label' => 'LBL_EVALUATION_START_DATE',
            ),
            1 => 
            array (
              'name' => 'closed_lost_reason_detail_c',
              'label' => 'LBL_CLOSED_LOST_REASON_DETAIL',
            ),
          ),
          19 => 
          array (
            0 => 
            array (
              'name' => 'Evaluation_Close_Date_c',
              'label' => 'Evaluation_Close_Date__c',
            ),
            1 => 
            array (
              'name' => 'primary_reason_competitor_c',
              'label' => 'LBL_PRIMARY_REASON_COMPETITOR',
            ),
          ),
          20 => 
          array (
            0 => 
            array (
            ),
            1 => 
            array (
              'name' => 'closed_lost_description',
              'label' => 'LBL_CLOSED_LOST_DESCRIPTION',
            ),
          ),
          21 => 
          array (
            0 => 
            array (
              'name' => 'partner_assigned_to_c',
              'label' => 'Partner_Assigned_To_c',
              'customCode' => '{assign var=partner_assigned_to_key value=$fields.partner_assigned_to_c.value}<a href="index.php?module=Accounts&action=DetailView&record={$partner_assigned_to_key}">{$APP_LIST_STRINGS.partner_assigned_to.$partner_assigned_to_key}</a>',
            ),
            1 => 
            array (
              'name' => 'accepted_by_partner_c',
              'label' => 'LBL_ACCEPTED_BY_PARTNER',
            ),
          ),
          22 => 
          array (
            0 => 
            array (
              'name' => 'team_name',
              'label' => 'LBL_TEAM',
            ),
            1 => 
            array (
              'name' => 'partner_contact_c',
              'label' => 'LBL_PARTNER_CONTACT',
            ),
          ),
          23 => 
          array (
            0 => 
            array (
              'name' => 'assigned_user_name',
              'label' => 'LBL_ASSIGNED_TO_NAME',
            ),
            1 => 
            array (
              'name' => 'date_modified',
              'label' => 'LBL_DATE_MODIFIED',
              'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
            ),
          ),
          24 => 
          array (
            0 => 
            array (
              'name' => 'associated_rep_c',
              'label' => 'Associated_Rep_c',
            ),
            1 => 
            array (
              'name' => 'date_entered',
              'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
              'label' => 'LBL_DATE_ENTERED',
            ),
          ),
          25 => 
          array (
            0 => 
            array (
              'name' => 'description',
              'nl2br' => true,
              'label' => 'LBL_DESCRIPTION',
            ),
          ),
        ),
      ),
    ),
  ),
);
?>
