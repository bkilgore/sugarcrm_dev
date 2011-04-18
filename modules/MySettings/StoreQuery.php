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




class StoreQuery{
	var $query = array();
	
	function addToQuery($name, $val){
		$this->query[$name] = $val;	
	}
	
	function SaveQuery($name){
		global $current_user;
		$current_user->setPreference($name.'Q', $this->query);
	}
	
	function clearQuery($name){
		$this->query = array();
		$this->saveQuery($name);	
	}
	
	function loadQuery($name){
		$saveType = $this->getSaveType($name);
		if($saveType == 'all' || $saveType == 'myitems'){
			global $current_user;
			$this->query = StoreQuery::getStoredQueryForUser($name);
			if(empty($this->query)){
				$this->query = array();	
			}
			if(!empty($this->populate_only) && !empty($this->query['query'])){
				$this->query['query'] = 'MSI';
			}
		}
	}
	
	
	function populateRequest(){
		foreach($this->query as $key=>$val){
            // todo wp: remove this
            if($key != 'advanced' && $key != 'module') { // cn: bug 6546 storequery stomps correct value for 'module' in Activities
    			$_REQUEST[$key] = $val;	
    			$_GET[$key] = $val;	
            }
		}	
	}
	
	function getSaveType($name)
	{
		global $sugar_config;
		$save_query = empty($sugar_config['save_query']) ?
			'all' : $sugar_config['save_query'];

		if(is_array($save_query))
		{
			if(isset($save_query[$name]))
			{
				$saveType = $save_query[$name];
			}
			elseif(isset($save_query['default']))
			{
				$saveType = $save_query['default'];
			}
			else
			{
				$saveType = 'all';
			}	
		}
		else
		{
			$saveType = $save_query;
		}	
		if($saveType == 'populate_only'){
			$saveType = 'all';
			$this->populate_only = true;
		}
		return $saveType;
	}

	
	function saveFromRequest($name){
		if(isset($_REQUEST['query'])){
			if(!empty($_REQUEST['clear_query']) && $_REQUEST['clear_query'] == 'true'){
				$this->clearQuery($name);
				return;	
			}
			$saveType = $this->getSaveType($name);
			
			if($saveType == 'myitems'){
				if(!empty($_REQUEST['current_user_only'])){
					$this->query['current_user_only'] = $_REQUEST['current_user_only'];
					$this->query['query'] = true;
				}
				$this->saveQuery($name);
				
			}else if($saveType == 'all'){
                // Bug 39580 - Added 'EmailTreeLayout','EmailGridWidths' to the list as these are added merely as side-effects of the fact that we store the entire
                // $_REQUEST object which includes all cookies.  These are potentially quite long strings as well.
				$blockVariables = array('mass', 'uid', 'massupdate', 'delete', 'merge', 'selectCount', 'current_query_by_page','EmailTreeLayout','EmailGridWidths');
				$this->query = $_REQUEST;
                foreach($blockVariables as $block) {
                    unset($this->query[$block]);
                }
				$this->saveQuery($name);	
			}
		}
	}
	
	function saveFromGet($name){
		if(isset($_GET['query'])){
			if(!empty($_GET['clear_query']) && $_GET['clear_query'] == 'true'){
				$this->clearQuery($name);
				return;	
			}
			$saveType = $this->getSaveType($name);
			
			if($saveType == 'myitems'){
				if(!empty($_GET['current_user_only'])){
					$this->query['current_user_only'] = $_GET['current_user_only'];
					$this->query['query'] = true;
				}
				$this->saveQuery($name);
				
			}else if($saveType == 'all'){
				$this->query = $_GET;
				$this->saveQuery($name);	
			}
		}
	}
	
	/**
	 * Static method to retrieve the user's stored query for a particular module
	 *
	 * @param string $module
	 * @return array
	 */
	public static function getStoredQueryForUser($module){
		global $current_user;
		return $current_user->getPreference($module.'Q');
	}
}

?>
