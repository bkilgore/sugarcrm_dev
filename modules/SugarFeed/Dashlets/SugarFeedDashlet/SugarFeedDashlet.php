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

 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('include/Dashlets/DashletGeneric.php');


class SugarFeedDashlet extends DashletGeneric { 
var $displayRows = 15;

var $categories;

var $selectedCategories = array();

    function SugarFeedDashlet($id, $def = null) {
		global $current_user, $app_strings, $app_list_strings;
		require('modules/SugarFeed/metadata/dashletviewdefs.php');
		$this->myItemsOnly = false;
        parent::DashletGeneric($id, $def);
		$this->myItemsOnly = false;
		$this->isConfigurable = true;
		$this->hasScript = true;
        // Add in some default categories.
        $this->categories['ALL'] = translate('LBL_ALL','SugarFeed');
        // Need to get the rest of the active SugarFeed modules
        $module_list = SugarFeed::getActiveFeedModules();
        // Translate the category names
        if ( ! is_array($module_list) ) { $module_list = array(); }
        foreach ( $module_list as $module ) {
            if ( $module == 'UserFeed' ) {
                // Fake module, need to translate specially
                $this->categories[$module] = translate('LBL_USER_FEED','SugarFeed');
            } else {
                $this->categories[$module] = $app_list_strings['moduleList'][$module];
            }
        }

        if(empty($def['title'])) $this->title = translate('LBL_HOMEPAGE_TITLE', 'SugarFeed');
		if(!empty($def['rows']))$this->displayRows = $def['rows'];
		if(!empty($def['categories']))$this->selectedCategories = $def['categories'];
        $this->searchFields = $dashletData['SugarFeedDashlet']['searchFields'];
        $this->columns = $dashletData['SugarFeedDashlet']['columns'];
		$catCount = count($this->categories);
		ACLController::filterModuleList($this->categories, false);
		if(count($this->categories) < $catCount){
			if(!empty($this->selectedCategories)){
				ACLController::filterModuleList($this->selectedCategories, true);
			}else{
				$this->selectedCategories = array_keys($this->categories);
				unset($this->selectedCategories[0]);
			}
		}
        $this->seedBean = new SugarFeed();
    }
	
	function process($lvsParams = array()) {
        global $current_user;

        $currentSearchFields = array();
        $configureView = true; // configure view or regular view
        $query = false;
        $whereArray = array();
        $lvsParams['massupdate'] = false;

        // apply filters
        if(isset($this->filters) || $this->myItemsOnly) {
            $whereArray = $this->buildWhere();
        }

        $this->lvs->export = false;
        $this->lvs->multiSelect = false;
		$this->lvs->quickViewLinks = false;
        // columns
    foreach($this->columns as $name => $val) {
                if(!empty($val['default']) && $val['default']) {
                    $displayColumns[strtoupper($name)] = $val;
                    $displayColumns[strtoupper($name)]['label'] = trim($displayColumns[strtoupper($name)]['label'], ':');
                }
            }

        $this->lvs->displayColumns = $displayColumns;

        $this->lvs->lvd->setVariableName($this->seedBean->object_name, array());
   
        $lvsParams['overrideOrder'] = true;
        $lvsParams['orderBy'] = 'date_entered';
        $lvsParams['sortOrder'] = 'DESC';
                
        // Get the real module list
        if (empty($this->selectedCategories)){
            $mod_list = $this->categories;
        } else {
            $mod_list = array_flip($this->selectedCategories);//27949, here the key of $this->selectedCategories is not module name, the value is module name, so array_flip it.
        }

        $admin_modules = array();
        $owner_modules = array();
        $regular_modules = array();
        foreach($mod_list as $module => $ignore) {
			// Handle the UserFeed differently
			if ( $module == 'UserFeed') {
				$regular_modules[] = 'UserFeed';
				continue;
			}
			if (ACLAction::getUserAccessLevel($current_user->id,$module,'view') <= ACL_ALLOW_NONE ) {
				// Not enough access to view any records, don't add it to any lists
				continue;
			}
			if ( ACLAction::getUserAccessLevel($current_user->id,$module,'view') == ACL_ALLOW_OWNER ) {
				$owner_modules[] = $module;
            } else {
                $regular_modules[] = $module;
            }
        }

        if(!empty($this->displayTpl))
        {
        	//MFH BUG #14296
            $where = '';
            if(!empty($whereArray)){
                $where = '(' . implode(') AND (', $whereArray) . ')';
            }            

			$module_limiter = " sugarfeed.related_module in ('" . implode("','", $regular_modules) . "')";

            if ( count($owner_modules) > 0
				) {
                $module_limiter = " ((sugarfeed.related_module IN ('".implode("','", $regular_modules)."') "
					.") ";
				if ( count($owner_modules) > 0 ) {
					$module_limiter .= "OR (sugarfeed.related_module IN('".implode("','", $owner_modules)."') AND sugarfeed.assigned_user_id = '".$current_user->id."' "
						.") ";
				}
				$module_limiter .= ")";
            }
			if(!empty($where)) { $where .= ' AND '; }
			$where .= $module_limiter;

            $this->lvs->setup($this->seedBean, $this->displayTpl, $where , $lvsParams, 0, $this->displayRows, 
                              array('name', 
                                    'description', 
                                    'date_entered', 
                                    'created_by', 
                                    'link_url', 
                                    'link_type'));

            foreach($this->lvs->data['data'] as $row => $data) {
                $this->lvs->data['data'][$row]['CREATED_BY'] = get_assigned_user_name($data['CREATED_BY']);
                $this->lvs->data['data'][$row]['NAME'] = str_replace("{this.CREATED_BY}",$this->lvs->data['data'][$row]['CREATED_BY'],$data['NAME']);
            }

            // assign a baseURL w/ the action set as DisplayDashlet
            foreach($this->lvs->data['pageData']['urls'] as $type => $url) {
            	// awu Replacing action=DisplayDashlet with action=DynamicAction&DynamicAction=DisplayDashlet
                if($type == 'orderBy')
                    $this->lvs->data['pageData']['urls'][$type] = preg_replace('/(action=.*&)/Ui', 'action=DynamicAction&DynamicAction=displayDashlet&', $url);
                else
                    $this->lvs->data['pageData']['urls'][$type] = preg_replace('/(action=.*&)/Ui', 'action=DynamicAction&DynamicAction=displayDashlet&', $url) . '&sugar_body_only=1&id=' . $this->id;
            }

            $this->lvs->ss->assign('dashletId', $this->id);

        }
    }
	
	  function deleteUserFeed() {
    	if(!empty($_REQUEST['record'])) {
			$feed = new SugarFeed();
			$feed->retrieve($_REQUEST['record']);
			if(is_admin($GLOBALS['current_user']) || $feed->created_by == $GLOBALS['current_user']->id){ 
            	$feed->mark_deleted($_REQUEST['record']);

			}
        }
    }
	 function pushUserFeed() {
    	if(!empty($_REQUEST['text'])) {
			$text = htmlspecialchars($_REQUEST['text']);
			//allow for bold and italic user tags
			$text = preg_replace('/&amp;lt;(\/*[bi])&amp;gt;/i','<$1>', $text);
            SugarFeed::pushFeed($text, 'UserFeed', $GLOBALS['current_user']->id, 
								$GLOBALS['current_user']->id,
                                $_REQUEST['link_type'], $_REQUEST['link_url']
                                );
        }
       
    }
	  function displayOptions() {
        global $app_strings;
        $ss = new Sugar_Smarty();
        $ss->assign('titleLBL', translate('LBL_TITLE', 'SugarFeed'));
		$ss->assign('categoriesLBL', translate('LBL_CATEGORIES', 'SugarFeed'));
        $ss->assign('rowsLBL', translate('LBL_ROWS', 'SugarFeed'));
        $ss->assign('saveLBL', $app_strings['LBL_SAVE_BUTTON_LABEL']);
        $ss->assign('title', $this->title);
		$ss->assign('categories', $this->categories);
        if ( empty($this->selectedCategories) ) {
            $this->selectedCategories['ALL'] = 'ALL';
        }
		$ss->assign('selectedCategories', $this->selectedCategories);
        $ss->assign('rows', $this->displayRows);
        $ss->assign('id', $this->id);

        return  $ss->fetch('modules/SugarFeed/Dashlets/SugarFeedDashlet/Options.tpl');
    }  
	
	/**
	 * creats the values
	 * @return 
	 * @param $req Object
	 */
	  function saveOptions($req) {
        global $sugar_config, $timedate, $current_user, $theme;
        $options = array();
        $options['title'] = $_REQUEST['title'];
		$rows = intval($_REQUEST['rows']);
        if($rows <= 0) {
            $rows = 15;         
        }
		if($rows > 100){
			$rows = 100;
		}
        $options['rows'] = $rows;
		$options['categories'] = $_REQUEST['categories'];
		foreach($options['categories'] as $cat){
			if($cat == 'ALL'){
				unset($options['categories']);
			}
		}
        return $options;
    }
	
      
      function sugarFeedDisplayScript() {
          // Forces the quicksearch to reload anytime the dashlet gets refreshed
          return '<script type="text/javascript">
enableQS(false);
</script>';
      }
	/**
	 * 
	 * @return javascript including QuickSearch for SugarFeeds
	 */
	 function displayScript() {
	 	require_once('include/QuickSearchDefaults.php');

        $ss = new Sugar_Smarty();
        $ss->assign('saving', translate('LBL_SAVING', 'SugarFeed'));
        $ss->assign('saved', translate('LBL_SAVED', 'SugarFeed'));
        $ss->assign('id', $this->id);
        
        $str = $ss->fetch('modules/SugarFeed/Dashlets/SugarFeedDashlet/SugarFeedScript.tpl');
        return $str; // return parent::display for title and such
    }
	
	/**
	 * 
	 * @return the fully rendered dashlet
	 */
	function display(){
		
		$listview = parent::display();
		$GLOBALS['current_sugarfeed'] = $this;
		$listview = preg_replace_callback('/\{([^\}]+)\.([^\}]+)\}/', create_function(
            '$matches',
            'if($matches[1] == "this"){$var = $matches[2]; return $GLOBALS[\'current_sugarfeed\']->$var;}else{return translate($matches[2], $matches[1]);}'
        ),$listview);
		$listview = preg_replace('/\[(\w+)\:([\w\-\d]*)\:([^\]]*)\]/', '<a href="index.php?module=$1&action=DetailView&record=$2"><img src="themes/default/images/$1.gif" border=0>$3</a>', $listview);
        
		return $listview.'</div>';
	}
	
	
	/**
	 * 
	 * @return the title and the user post form
	 * @param $text Object
	 */
	function getHeader($text='') {
		return parent::getHeader($text) . $this->getPostForm().$this->getDisabledWarning().$this->sugarFeedDisplayScript().'<div class="sugarFeedDashlet">';	
	}
	
	
	/**
	 * 
	 * @return a warning message if the sugar feed system is not enabled currently
	 */
	function getDisabledWarning(){
        /* Check to see if the sugar feed system is enabled */
        if ( ! $this->shouldDisplay() ) {
            // The Sugar Feeds are disabled, populate the warning message
            return translate('LBL_DASHLET_DISABLED','SugarFeed');
        } else {
            return '';
        }
    }

	/**
	 * 
	 * @return the form for users posting custom messages to the feed stream
	 */
	function getPostForm(){
        global $current_user;

        if ( empty($this->categories['UserFeed']) ) {
            // The user feed system isn't enabled, don't let them post notes
            return '';
        }
		$user_name = ucfirst($GLOBALS['current_user']->user_name);
		$moreimg = SugarThemeRegistry::current()->getImage('advanced_search' , 'onclick="toggleDisplay(\'more_' . $this->id . '\'); toggleDisplay(\'more_img_'.$this->id.'\'); toggleDisplay(\'less_img_'.$this->id.'\');"');
		$lessimg = SugarThemeRegistry::current()->getImage('basic_search' , 'onclick="toggleDisplay(\'more_' . $this->id . '\'); toggleDisplay(\'more_img_'.$this->id.'\'); toggleDisplay(\'less_img_'.$this->id.'\');"');
		$ss = new Sugar_Smarty();
		$ss->assign('LBL_TO', translate('LBL_TO', 'SugarFeed'));
		$ss->assign('LBL_POST', translate('LBL_POST', 'SugarFeed'));
		$ss->assign('LBL_SELECT', translate('LBL_SELECT', 'SugarFeed'));
		$ss->assign('LBL_IS', translate('LBL_IS', 'SugarFeed'));
		$ss->assign('id', $this->id);
		$ss->assign('more_img', $moreimg);
		$ss->assign('less_img', $lessimg);
        if($current_user->getPreference('use_real_names') == 'on'){
            $ss->assign('user_name', $current_user->full_name);
        }
        else {
            $ss->assign('user_name', $user_name);
        }
        $linkTypesIn = SugarFeed::getLinkTypes();
        $linkTypes = array();
        foreach ( $linkTypesIn as $key => $value ) {
            $linkTypes[$key] = translate('LBL_LINK_TYPE_'.$value,'SugarFeed');
        }
		$ss->assign('link_types', $linkTypes);
		return $ss->fetch('modules/SugarFeed/Dashlets/SugarFeedDashlet/UserPostForm.tpl');
	
	}
    
    // This is called from the include/MySugar/DashletsDialog/DashletsDialog.php and determines if we should display the SugarFeed dashlet as an option or not
    static function shouldDisplay() {
        
        $admin = new Administration();
        $admin->retrieveSettings();

        if ( !isset($admin->settings['sugarfeed_enabled']) || $admin->settings['sugarfeed_enabled'] != '1' ) {
            return false;
        } else {
            return true;
        }
    }
}