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

require('config.php');
global $sugar_config;
global $timedate;

//Sent when the admin generate a new password
$EmailTemp = new EmailTemplate();
$subj ='New account information';
$desc = 'This template is used when the System Administrator sends a new password to a user.';
$body = '<div><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width="550" align=\"\&quot;\&quot;center\&quot;\&quot;\"><tbody><tr><td colspan=\"2\"><p>Here is your account username and temporary password:</p><p>Username : $contact_user_user_name </p><p>Password : $contact_user_user_hash </p><br><p>'.$GLOBALS['sugar_config']['site_url'].'/index.php</p><br><p>After you log in using the above password, you may be required to reset the password to one of your own choice.</p>   </td>         </tr><tr><td colspan=\"2\"></td>         </tr> </tbody></table> </div>';
$txt_body = 
'
Here is your account username and temporary password:
Username : $contact_user_user_name
Password : $contact_user_user_hash

'.$GLOBALS['sugar_config']['site_url'].'/index.php

After you log in using the above password, you may be required to reset the password to one of your own choice.';
$name = 'System-generated password email';

$EmailTemp->name = $name;
$EmailTemp->description = $desc;
$EmailTemp->subject = $subj;
$EmailTemp->body = $txt_body;
$EmailTemp->body_html = $body;
$EmailTemp->deleted = 0;
$EmailTemp->published = 'off';
$EmailTemp->text_only = 0;
$id =$EmailTemp->save();

$sugar_config['passwordsetting']['generatepasswordtmpl'] = $id;
$sugar_config['passwordsetting']['forgotpasswordON'] = true;
$sugar_config['passwordsetting']['SystemGeneratedPasswordON'] = true;
$sugar_config['passwordsetting']['systexpirationtime'] = 7;
$sugar_config['passwordsetting']['systexpiration'] = 1;
$sugar_config['passwordsetting']['linkexpiration'] = true;
$sugar_config['passwordsetting']['linkexpirationtime'] = 24;
$sugar_config['passwordsetting']['linkexpirationtype'] = 60;
$sugar_config['passwordsetting']['minpwdlength'] = 6;
$sugar_config['passwordsetting']['oneupper'] = true;
$sugar_config['passwordsetting']['onelower'] = true;
$sugar_config['passwordsetting']['onenumber'] = true;

$result = $EmailTemp->db->query("INSERT INTO config (value, category, name) VALUES ('$id','password', 'System-generated password email')");


//User generate a link to set a new password
$EmailTemp = new EmailTemplate();
$subj ='Reset your account password';
$desc = "This template is used to send a user a link to click to reset the user's account password.";
$body = '<div><table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width="550" align=\"\&quot;\&quot;center\&quot;\&quot;\"><tbody><tr><td colspan=\"2\"><p>You recently requested on $contact_user_pwd_last_changed to be able to reset your account password. </p><p>Click on the link below to reset your password:</p><p> $contact_user_link_guid </p>  </td>         </tr><tr><td colspan=\"2\"></td>         </tr> </tbody></table> </div>';
$txt_body = 
'
You recently requested on $contact_user_pwd_last_changed to be able to reset your account password.

Click on the link below to reset your password:

$contact_user_link_guid';
$name = 'Forgot Password email';

$EmailTemp->name = $name;
$EmailTemp->description = $desc;
$EmailTemp->subject = $subj;
$EmailTemp->body = $txt_body;
$EmailTemp->body_html = $body;
$EmailTemp->deleted = 0;
$EmailTemp->published = 'off';
$EmailTemp->text_only = 0;
$id =$EmailTemp->save();
$sugar_config['passwordsetting']['lostpasswordtmpl'] = $id;
 
$result = $EmailTemp->db->query("INSERT INTO config (value, category, name) VALUES ('$id','password', 'Forgot Password email')");

//rebuildConfigFile($sugar_config, $sugar_config['sugar_version']);
write_array_to_file( "sugar_config", $sugar_config, "config.php");

?>