<?php
if(!defined('sugarEntry'))define('sugarEntry', true);
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


require_once('service/core/REST/SugarRest.php');

/**
 * This class is a serialize implementation of REST protocol
 *
 */
class SugarRestRSS extends SugarRest{
	
	/**
	 * It will serialize the input object and echo's it
	 * 
	 * @param array $input - assoc array of input values: key = param name, value = param type
	 * @return String - echos serialize string of $input
	 */
	function generateResponse($input){
		$method = !empty($_REQUEST['method'])? $_REQUEST['method']: '';
		if($method != 'get_entry_list')$this->fault('RSS currently only supports the get_entry_list method');
		ob_clean();
		$this->generateResponseHeader($input['result_count']);
		$this->generateItems($input);
		$this->generateResponseFooter();
	} // fn
	
	function generateResponseHeader($count){
		$date = gmdate("D, d M Y H:i:s") . " GMT";
echo'<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0">
<channel>
<title>SugarCRM  RSS Feed</title>
<link>http://cnn.com</link>
<description>' . $count. ' records found</description>
<pubDate>' . $date . '</pubDate>
<generator>SugarCRM</generator>
<ttl>' . $count . '</ttl>
';
	}
	
function generateItems($input){
	if(!empty($input['entry_list'])){
		foreach($input['entry_list'] as $item){
			$this->generateItem($item);
		}
		
	}
}

function generateItem($item){
echo "<item>\n";
$name  = !empty($item['name_value_list']['name'])?htmlentities( $item['name_value_list']['name']): '';
echo "<title>$name</title>\n";
echo "<link>". $GLOBALS['sugar_config']['site_url']  . htmlentities('/index.php?module=' . $item['module_name']. '&record=' . $item['id']) .  "</link>\n";
echo "<description><![CDATA[";
$displayFieldNames = true;
if(count($item['name_value_list']) == 2 &&isset($item['name_value_list']['name']))$displayFieldNames = false;
foreach($item['name_value_list'] as $k=>$v){
	if($k =='name')continue;
	if($k == 'date_modified')continue;
	if($displayFieldNames) echo '<b>' .htmlentities( $k) . ':<b>&nbsp;';
	echo htmlentities( $v) . "\n<br>";
}
echo "]]></description>\n";
if(!empty($item['name_value_list']['date_modified'])){
	$date = date("D, d M Y H:i:s", strtotime($item['name_value_list']['date_modified'])) . " GMT";
	echo "<pubDate>$date</pubDate>";
}

echo "<guid>" . $item['id']. "</guid>\n";
echo "</item>\n";
}
function generateResponseFooter(){
		echo'</channel></rss>';
	}
	
	/**
	 * This method calls functions on the implementation class and returns the output or Fault object in case of error to client
	 *
	 * @return unknown
	 */
	function serve(){
		$this->fault('RSS is not a valid input_type');
	} // fn
	
	function fault($faultObject){
		ob_clean();
		$this->generateResponseHeader();
		echo '<item><name>';
		if(is_object($errorObject)){
			$error = $errorObject->number . ': ' . $errorObject->name . '<br>' . $errorObject->description;
			$GLOBALS['log']->error($error);
		}else{
			$GLOBALS['log']->error(var_export($errorObject, true));
			$error = var_export($errorObject, true);
		} // else
		echo $error;
		echo '</name></item>';
		$this->generateResponseFooter();
		
	}
	
	
} // clazz