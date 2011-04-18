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

/*********************************************************************************

 * Description:
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
 * Reserved. Contributor(s): ______________________________________..
 * *******************************************************************************/

if(isset($sugar_config['i18n_test']) && $sugar_config['i18n_test'] == true && $_SESSION['setup_db_type'] != 'mssql') {
	$case_seed_names = array(
		'プラグが差し込めません',
		'システムが異常に高速に動作中',
		'カスタマイズの支援について',
		'追加ライセンスの購入について',
		'間違ったブラウザを使用する場合の警告メッセージ'
	);
	$note_seed_names_and_Descriptions = array(
		array('お客様情報の追加','3,000人のお客様にコンタクトすること'),
		array('コール情報','再コールにより電話。いい話になった。'),
		array('誕生日','担当者は10月生まれ'),
		array('お歳暮','お歳暮は歓迎される。来年のためにリスト化すること。')
	);
	$call_seed_data_names = array(
		'提案について詳細情報を得ること',
		'メッセージを残した',
		'都合が悪いとのこと。掛けなおし',
		'レビュープロセスの討議'
	);
} else {
	$case_seed_names = array(
		'Having Trouble Plugging It In',
		'System is Performing Too Fast',
		'Need assistance with large customization',
		'Need to Purchase Additional Licenses',
		'Warning message when using the wrong browser'
	);
	$note_seed_names_and_Descriptions = array(
		array('More Account Information','This could turn into a 3,000 user opportunity'),
		array('Call Information','We had a call.  The call went well.'),
		array('Birthday Information','The Owner was born in October'),
		array('Holliday Gift','The holliday gift was appreciated.  Put them on the list for next year as well.')
	);
	$call_seed_data_names = array(
		'Get More information on the proposed deal',
		'Left a message',
		'Bad time, will call back',
		'Discuss Review Process'
	);
}

?>
