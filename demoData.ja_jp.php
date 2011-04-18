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



//users demodata
//VP
global $sugar_demodata;
$sugar_demodata['users'][0] = array(
  'id' => 'seed_jim_id',	
  'last_name' => 'ブレナン',
  'first_name' => 'ジーム',
  'user_name' => 'jim',
  'title'	=> 'Sales Manager West',
  'is_admin' => false,
  'reports_to' => null,
  'reports_to_name' => null,
  'email' => 'jim@example.com'
);

//west team
$sugar_demodata['users'][] = array(
  'id' => 'seed_sarah_id',	
  'last_name' => 'スミス',
  'first_name' => 'サーラー',
  'user_name' => 'sarah',
  'title'	=> 'Sales Manager West',
  'is_admin' => false,
  'reports_to' => 'seed_jim_id',
  'reports_to_name' => 'ブレナン, ジーム',
  'email' => 'sarah@example.com'
);

$sugar_demodata['users'][] = array(
  'id' => 'seed_sally_id',	
  'last_name' => 'ブロンソン',
  'first_name' => 'サーリー',
  'user_name' => 'sally',
  'title'	=> 'Senior Account Rep',
  'is_admin' => false,
  'reports_to' => 'seed_sarah_id',
  'reports_to_name' => 'スミス, サーラー',
  'email' => 'sally@example.com'
);

$sugar_demodata['users'][] = array(
  'id' => 'seed_max_id',	
  'last_name' => 'ジェンソン',
  'first_name' => 'マクス',
  'user_name' => 'max',
  'title'	=> 'Account Rep',
  'is_admin' => false,
  'reports_to' => 'seed_sarah_id',
  'reports_to_name' => 'スミス、サーラー',
  'email' => 'tom@example.com'
);
		
//east team
$sugar_demodata['users'][] = array(
  'id' => 'seed_will_id',	
  'last_name' => 'ウエストン',
  'first_name' => 'ウイル',
  'user_name' => 'will',
  'title'	=> 'Sales Manager East',
  'is_admin' => false,
  'reports_to' => 'seed_jim_id',
  'reports_to_name' => 'ブレナン, ジーム',
  'email' => 'will@example.com'
);

$sugar_demodata['users'][] = array(
  'id' => 'seed_chris_id',	
  'last_name' => 'オリバー',
  'first_name' => 'クリス',
  'user_name' => 'chris',
  'title'	=> 'Senior Account Rep',
  'is_admin' => false,
  'reports_to' => 'seed_will_id',
  'reports_to_name' => 'ウエストン, ウイル',
  'email' => 'chris@example.com'
);

//teams demodata
$sugar_demodata['teams'][] = array(
  'name' => 'East イースト',	
  'description' => 'これは東のためのチームです。',
  'team_id' => 'East',
);

$sugar_demodata['teams'][] = array(
  'name' => 'West イースト',	
  'description' => 'Westウエスト", "これは西のためのチームです。 ',
  'team_id' => 'West',
);


//company name array
$sugar_demodata['company_name_array'] = array(
	"Butée Torique",
	"Targéte",
	"Wally Marté",
	"Bünde-Mitte",
	"Fabriqué Interationål",
	"Életriqué Géneråle",
	"Bénellî GMBh",
	"реклама",
	"Berufskolleg für Elektrotechnik",
	"プロダクションG.I.",
	"パンダビジュアル",
	"A.B.ケアブレインズ",
	"株式会社未来商事",
);

//contacts accounts
$sugar_demodata['last_name_array'] = array(
	"Cumberbund",
	"Reînhold",
	"für Büurigérre",
	"Înténåénd",
	"Väestörekisterikeskus",
	"Elenå",
	"あおき",
	"オーラム",
	"タイロー",
	"ノルドウエル",
	"ロベルトス",
	"上原",
	"伊藤",
	"吉田",
	"土見",
	"大空寺",
	"天川",
	"小林",
	"平",
	"玉野",
	"石橋",
	"窪田",
	"真田",
	"徳川",
	"菊地",
	"谷山",
	"速瀬",
	"野島",
	"青木",
	"香月",
	"鳴海",
);

$sugar_demodata['first_name_array'] = array(
	"Johånnes",
	"Hénri",
	"Marîe",
	"Lars",
	"Österreich",
	"Klaüse",
	"Александра",
	"Валерия",
	"Виктория",
	"あゆ",
	"クリス",
	"クリント",
	"さやか",
	"ジェイコブ",
	"ジョン",
	"はるか",
	"ひと美",
	"まゆ",
	"まりこ",
	"モトコ",
	"ラーズ",
	"孝之",
	"五郎",
	"徹",
	"紀章",
	"勝昭",
	"正二",
	"健太",
	"敏文",
);


$sugar_demodata['company_name_array'] = array(
	"Butée Torique",
	"Targéte",
	"Wally Marté",
	"Bünde-Mitte",
	"Fabriqué Interationål",
	"Életriqué Géneråle",
	"Bénellî GMBh",
	"реклама",
	"Berufskolleg für Elektrotechnik",
	"プロダクションG.I.",
	"パンダビジュアル",
	"A.B.ケアブレインズ",
	"株式会社未来商事",
);


$sugar_demodata['street_address_array'] = array(
	"123 Any Street",
	"562 38th Avenue",
	"2 Penultimate Loop",
	"3 Ôester Ĭsland",
	"777 ΩΔΠβκτηηκμ",
	"10 Besançon Ave",
	"4542 Rue Chambéry",
	"678 Rue St. Angoulême",
	"三番町1-2-5",
	"富士見が丘545",
	"北見通り659",
	"海岸通り975",
	"3丁目2-5",
	"金岡町54654",
	"多古4678",
	"大洲5454",
	"海浜幕張4789",
	"御茶ノ水647",
);


$sugar_demodata['city_array'] = array (
	"Nice",
	"Orléans",
	"San Francisco",
	"Cupertino",
	"New York",
	"London",
	"Moscow",
	"Lisbon",
	"Böblingen",
	"Thüringen",
	"中央区",
	"京都市",
	"ニューヨーク",
	"サンフランシスコ",
	"ロサンゼルス",
	"ロンドン",
	"シカゴ",
);


//cases demo data
$sugar_demodata['case_seed_names'] = array(
	'プラグが差し込めません',
	'システムが異常に高速に動作中',
	'カスタマイズの支援について',
	'追加ライセンスの購入について',
	'間違ったブラウザを使用する場合の警告メッセージ'
);
$sugar_demodata['note_seed_names_and_Descriptions'] = array(
	array('お客様情報の追加','3,000人のお客様にコンタクトすること'),
	array('コール情報','再コールにより電話。いい話になった。'),
	array('誕生日','担当者は10月生まれ'),
	array('お歳暮','お歳暮は歓迎される。来年のためにリスト化すること。')
);
$sugar_demodata['call_seed_data_names'] = array(
	'提案について詳細情報を得ること',
	'メッセージを残した',
	'都合が悪いとのこと。掛けなおし',
	'レビュープロセスの討議'
);
	

//titles
$sugar_demodata['titles'] = array(
	"President",
	"VP Operations",
	"VP Sales",
	"Director Operations",
	"Director Sales",
	"Mgr Operations",
	"IT Developer",
"");

//tasks
$sugar_demodata['task_seed_data_names'] = array(
	'Assemble catalogs', 
	'Make travel arrangements', 
	'Send a letter', 
	'Send contract', 
	'Send fax', 
	'Send a follow-up letter', 
	'Send literature', 
	'Send proposal', 
	'Send quote', 
	'Call to schedule meeting', 
	'Setup evaluation', 
	'Get demo feedback', 
	'Arrange introduction', 
	'Escalate support request', 
	'Close out support request', 
	'Ship product', 
	'Arrange reference call', 
	'Schedule training', 
	'Send local user group information', 
	'Add to mailing list',
);

//meetings
$sugar_demodata['meeting_seed_data_names'] = array(
	'Follow-up on proposal', 
	'Initial discussion', 
	'Review needs', 
	'Discuss pricing', 
	'Demo', 
	'Introduce all players',
);
$sugar_demodata['meeting_seed_data_descriptions'] = 'Meeting to discuss project plan and hash out the details of implementation';

//emails
$sugar_demodata['email_seed_data_subjects'] = array(
	'Follow-up on proposal', 
	'Initial discussion', 
	'Review needs', 
	'Discuss pricing', 
	'Demo', 
	'Introduce all players', 
);
$sugar_demodata['email_seed_data_descriptions'] = 'Meeting to discuss project plan and hash out the details of implementation';

//leads
$sugar_demodata['primary_address_state'] = 'CA';
$sugar_demodata['billing_address_state']['east'] = 'NY';
$sugar_demodata['billing_address_state']['west'] = 'CA';
$sugar_demodata['primary_address_country'] = 'USA';

//manufacturers
$sugar_demodata['manufacturer_seed_data_names'] = array(
	'TekkyWare Inc.', 
	'Wally\'s Widget World'
);

//Shippers
$sugar_demodata['shipper_seed_data_names'] = array(
	'FedEx', 
	'USPS Ground'
);
//productcategories
$sugar_demodata['category_ext_name'] = ' Widgets';
$sugar_demodata['product_ext_name'] = ' Gadget';
$sugar_demodata['productcategory_seed_data_names'] = array(
	'Desktops', 
	'Laptops', 
	'Stationary Widgets', 
	'Wobbly Widgets'
);

//producttype
$sugar_demodata['producttype_seed_data_names']= array(
	'Widgets', 
	'Hardware', 
	'Support Contract'
);
//taxrate
$sugar_demodata['taxrate_seed_data'][] = array(
	'name' => '8.25 - Cupertino, CA',
	'value' => '8.25',
);

$sugar_demodata['currency_seed_data'][] = array(
	'name' => 'Euro',
	'conversion_rate' => 0.9,
	'iso4217' => 'EUR',
	'symbol' => '€',
);

//producttemplate
$sugar_demodata['producttemplate_seed_data'][] = array(
	'name' => 'TK 1000 Desktop',
	'tax_class' => 'Taxable',
	'cost_price' => 500.00,
	'cost_usdollar' => 500.00,
	'list_price' => 800.00,
	'list_usdollar' => 800.00,
	'discount_price' => 800.00,
	'discount_usdollar' => 800.00,
	'pricing_formula' => 'IsList',
	'mft_part_num' => 'XYZ7890122222',
	'pricing_factor' => '1',
	'status' => 'Available',
	'weight' => 20.0,
	'date_available' => '2004-10-15',
	'qty_in_stock' => '72',
); 

$sugar_demodata['producttemplate_seed_data'][] = array(
	'name' => 'TK 1000 Desktop',
	'tax_class' => 'Taxable',
	'cost_price' => 600.00,
	'cost_usdollar' => 600.00,
	'list_price' => 900.00,
	'list_usdollar' => 900.00,
	'discount_price' => 900.00,
	'discount_usdollar' => 900.00,
	'pricing_formula' => 'IsList',
	'mft_part_num' => 'XYZ7890123456',
	'pricing_factor' => '1',
	'status' => 'Available',
	'weight' => 20.0,
	'date_available' => '2004-10-15',
	'qty_in_stock' => '65',
); 

$sugar_demodata['producttemplate_seed_data'][] = array(
	'name' => 'TK m30 Desktop',
	'tax_class' => 'Taxable',
	'cost_price' => 1300.00,
	'cost_usdollar' => 1300.00,
	'list_price' => 1700.00,
	'list_usdollar' => 1700.00,
	'discount_price' => 1625.00,
	'discount_usdollar' => 1625.00,
	'pricing_formula' => 'ProfitMargin',
	'mft_part_num' => 'ABCD123456890',
	'pricing_factor' => '20',
	'status' => 'Available',
	'weight' => 5.0,
	'date_available' => '2004-10-15',
	'qty_in_stock' => '12',
); 

$sugar_demodata['producttemplate_seed_data'][] = array(
	'name' => 'Reflective Mirror Widget',
	'tax_class' => 'Taxable',
	'cost_price' => 200.00,
	'cost_usdollar' => 200.00,
	'list_price' => 325.00,
	'list_usdollar' => 325.00,
	'discount_price' => 266.50,
	'discount_usdollar' => 266.50,
	'pricing_formula' => 'PercentageDiscount',
	'mft_part_num' => '2.0',
	'pricing_factor' => '20',
	'status' => 'Available',
	'weight' => 20.0,
	'date_available' => '2004-10-15',
	'qty_in_stock' => '65',
); 

$sugar_demodata['contract_seed_data'][] = array(
	'name' => 'IT Tech Support for Moon Base',
	'reference_code' => 'EMP-9802',
	'total_contract_value' => '500600.01',
	'start_date' => '2010-05-15',
	'end_date' => '2020-05-15',
	'company_signed_date' => '2010-03-15',
	'customer_signed_date' => '2010-03-16',
	'description' => 'This is a sub-contract for a very large, very hush-hush project on the moon of Endor.',
); 

$sugar_demodata['contract_seed_data'][] = array(
	'name' => 'Ion Engines for Empire',
	'reference_code' => 'EMP-7277',
	'total_contract_value' => '333444.34',
	'start_date' => '2010-05-15',
	'end_date' => '2020-05-15',
	'company_signed_date' => '2010-03-15',
	'customer_signed_date' => '2010-03-16',
	'description' => 'In competition with Sienar Fleet Systems for this one.',
); 

$sugar_demodata['project_seed_data']['audit'] = array(
	'name' => 'Create new project plan for audit',
	'description' => 'Annual audit coming up next month.',
	'estimated_start_date' => '2007-11-01',
	'estimated_end_date' => '2007-12-31',
	'status' => 'Draft',
	'priority' => 'medium',
);

$sugar_demodata['project_seed_data']['audit']['project_tasks'][] = array(
	'name' => 'Communicate to stakeholders',
	'date_start' => '2007/11/1',
	'date_finish' => '2007/11/8',
	'description' => 'Schedule individual meetings with Will, Max, and Sarah.',
	'duration' => '6',
	'duration_unit' => 'Days',
	'percent_complete' => 100,
);

$sugar_demodata['project_seed_data']['audit']['project_tasks'][] = array(
	'name' => 'Create draft of the plan',
	'date_start' => '2007/11/5',
	'date_finish' => '2007/11/20',
	'description' => 'Schedule individual meetings with Will, Max, and Sarah.',
	'duration' => '12',
	'duration_unit' => 'Days',
	'percent_complete' => 38,
);

$sugar_demodata['project_seed_data']['audit']['project_tasks'][] = array(
	'name' => 'Field work for collecting data.',
	'date_start' => '2007/11/5',
	'date_finish' => '2007/11/13',
	'description' => 'We need to get approval from all stakeholders on the plan',
	'duration' => '17',
	'duration_unit' => 'Days',
	'percent_complete' => 75,
);

$sugar_demodata['project_seed_data']['audit']['project_tasks'][] = array(
	'name' => 'Create draft of the plan',
	'date_start' => '2007/11/12',
	'date_finish' => '2007/11/19',
	'description' => 'Schedule the meeting with the head of business units to solicit help.',
	'duration' => '6',
	'duration_unit' => 'Days',
	'percent_complete' => 0,
);

$sugar_demodata['project_seed_data']['audit']['project_tasks'][] = array(
	'name' => 'Gather data from meetings',
	'date_start' => '2007/11/20',
	'date_finish' => '2007/11/20',
	'description' => 'Need to organize the data and put it in the right spreadsheet.',
	'duration' => '1',
	'duration_unit' => 'Days',
	'percent_complete' => 0,
);

?>