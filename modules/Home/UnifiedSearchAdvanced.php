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

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/



class UnifiedSearchAdvanced {
    
    var $query_string = '';
    
    function __construct(){
        if(!empty($_REQUEST['query_string'])){
            $query_string = trim($_REQUEST['query_string']);
            if(!empty($query_string)){
                $this->query_string = $query_string;
            }
        }
    }
    
	function getDropDownDiv($tpl = 'modules/Home/UnifiedSearchAdvanced.tpl') {
		global $app_list_strings;

		if(!file_exists($GLOBALS['sugar_config']['cache_dir'].'modules/unified_search_modules.php'))
		$this->buildCache();
		include($GLOBALS['sugar_config']['cache_dir'].'modules/unified_search_modules.php');

		global $mod_strings, $modListHeader, $app_list_strings, $current_user, $app_strings, $beanList;
		$users_modules = $current_user->getPreference('globalSearch', 'search');

		if(!isset($users_modules)) { // preferences are empty, select all
			$users_modules = array();
			foreach($unified_search_modules as $module=>$data) {
				if ( !empty($data['default']) ) {
                    $users_modules[$module] = $beanList[$module];
                }
			}
			$current_user->setPreference('globalSearch', $users_modules, 0, 'search');
		}
		$sugar_smarty = new Sugar_Smarty();

		$modules_to_search = array();
		foreach($unified_search_modules as $module => $data) {
            if(ACLController::checkAccess($module, 'list', true)) {
                $modules_to_search[$module] = array('translated' => $app_list_strings['moduleList'][$module]);
                if(array_key_exists($module, $users_modules)) $modules_to_search[$module]['checked'] = true;
                else $modules_to_search[$module]['checked'] = false;
            }
		}

		if(!empty($this->query_string)) $sugar_smarty->assign('query_string', securexss($this->query_string));
		else $sugar_smarty->assign('query_string', '');
		$sugar_smarty->assign('USE_SEARCH_GIF', 0);
		$sugar_smarty->assign('LBL_SEARCH_BUTTON_LABEL', $app_strings['LBL_SEARCH_BUTTON_LABEL']);
		$sugar_smarty->assign('MODULES_TO_SEARCH', $modules_to_search);
		$sugar_smarty->debugging = true;

		return $sugar_smarty->fetch($tpl);
	}

	function search() {
		if(!file_exists($GLOBALS['sugar_config']['cache_dir'].'modules/unified_search_modules.php'))
			$this->buildCache();

		include $GLOBALS['sugar_config']['cache_dir'].'modules/unified_search_modules.php';
		require_once 'include/ListView/ListViewSmarty.php';
		

		global $modListHeader, $beanList, $beanFiles, $current_language, $app_strings, $current_user, $mod_strings;
		$home_mod_strings = return_module_language($current_language, 'Home');

		$overlib = true;
		$this->query_string = $GLOBALS['db']->quote(securexss(from_html(clean_string($this->query_string, 'UNIFIED_SEARCH'))));

		if(!empty($_REQUEST['advanced']) && $_REQUEST['advanced'] != 'false') {
			$modules_to_search = array();
			foreach($_REQUEST as $param => $value) {
				if(preg_match('/^search_mod_(.*)$/', $param, $match)) {
					$modules_to_search[$match[1]] = $beanList[$match[1]];
				}
			}
			$current_user->setPreference('globalSearch', $modules_to_search, 0, 'search'); // save selections to user preference
		}
		else {
			$users_modules = $current_user->getPreference('globalSearch', 'search');
			if(isset($users_modules)) { // use user's previous selections
			    foreach ( $users_modules as $key => $value ) {
			        if ( isset($unified_search_modules[$key]) ) {
			            $modules_to_search[$key] = $value;
			        }
			    }
			}
			else { // select all the modules (ie first time user has used global search)
				foreach($unified_search_modules as $module=>$data) {
				    if ( !empty($data['default']) ) {
				        $modules_to_search[$module] = $beanList[$module];
				    }
				}
			}
			$current_user->setPreference('globalSearch', $modules_to_search, 'search');
		}
		echo $this->getDropDownDiv('modules/Home/UnifiedSearchAdvancedForm.tpl');

		$module_results = array();
		$module_counts = array();
		$has_results = false;

		if(!empty($this->query_string)) {
			foreach($modules_to_search as $moduleName => $beanName) {
			    $unifiedSearchFields = array () ;
                $innerJoins = array();
                foreach ( $unified_search_modules[ $moduleName ]['fields'] as $field=>$def )
                {
                    //bug: 34125 we might want to try to use the LEFT JOIN operator instead of the INNER JOIN in the case we are
                    //joining against a field that has not been populated.
                    if(!empty($def['innerjoin']) ){
                        if (empty($def['db_field']) )
                            continue;
                        $innerJoins[$field] = $def;
                        $def['innerjoin'] = str_replace('INNER', 'LEFT', $def['innerjoin']);
                    }
                    $unifiedSearchFields[ $moduleName ] [ $field ] = $def ;
                    $unifiedSearchFields[ $moduleName ] [ $field ][ 'value' ] = $this->query_string ;
                }

                /*
                 * Use searchForm2->generateSearchWhere() to create the search query, as it can generate SQL for the full set of comparisons required
                 * generateSearchWhere() expects to find the search conditions for a field in the 'value' parameter of the searchFields entry for that field
                 */
                require_once $beanFiles[$beanName] ;
                $seed = new $beanName();
				 require_once 'include/SearchForm/SearchForm2.php' ;
                $searchForm = new SearchForm ( $seed, $moduleName ) ;

                $searchForm->setup (array ( $moduleName => array() ) , $unifiedSearchFields , '' , 'saved_views' /* hack to avoid setup doing further unwanted processing */ ) ;
                $where_clauses = $searchForm->generateSearchWhere() ;
                //add inner joins back into the where clause
                $params = array('custom_select' => "");
                foreach($innerJoins as $field=>$def) {
                    if (isset ($def['db_field'])) {
                      foreach($def['db_field'] as $dbfield)
                          $where_clauses[] = $dbfield . " LIKE '" . $this->query_string . "%'";
                          $params['custom_select'] .= ", $dbfield";
                          $params['distinct'] = true;
                          //$filterFields[$dbfield] = $dbfield;
                    }
                }

                                    if (count($where_clauses) > 0 )
                                        $where = '(('. implode(' ) OR ( ', $where_clauses) . '))';

                $lv = new ListViewSmarty();
                $lv->lvd->additionalDetails = false;
                $mod_strings = return_module_language($current_language, $seed->module_dir);
                if(file_exists('custom/modules/'.$seed->module_dir.'/metadata/listviewdefs.php')){
                    require_once('custom/modules/'.$seed->module_dir.'/metadata/listviewdefs.php');
                }else{
                    require_once('modules/'.$seed->module_dir.'/metadata/listviewdefs.php');

                }
                if ( !isset($listViewDefs) || !isset($listViewDefs[$seed->module_dir]) )
                    continue;
				$displayColumns = array();
                foreach($listViewDefs[$seed->module_dir] as $colName => $param) {
                    if(!empty($param['default']) && $param['default'] == true) {
                        $param['url_sort'] = true;//bug 27933
                        $displayColumns[$colName] = $param;
                    }
                }

                if(count($displayColumns) > 0) $lv->displayColumns = $displayColumns;
                else $lv->displayColumns = $listViewDefs[$seed->module_dir];

                $lv->export = false;
                $lv->mergeduplicates = false;
                $lv->multiSelect = false;
                $lv->delete = false;
                $lv->select = false;
                $lv->showMassupdateFields = false;
                if($overlib) {
                    $lv->overlib = true;
                    $overlib = false;
                }
                else $lv->overlib = false;
                
                
                
                $lv->setup($seed, 'include/ListView/ListViewGeneric.tpl', $where, $params, 0, 10);

                $module_results[$moduleName] = '<br /><br />' . get_form_header($GLOBALS['app_list_strings']['moduleList'][$seed->module_dir] . ' (' . $lv->data['pageData']['offsets']['total'] . ')', '', false);
                $module_counts[$moduleName] = $lv->data['pageData']['offsets']['total'];

                if($lv->data['pageData']['offsets']['total'] == 0) {
                    $module_results[$moduleName] .= '<h2>' . $home_mod_strings['LBL_NO_RESULTS_IN_MODULE'] . '</h2>';
                }
                else {
                    $has_results = true;
                    $module_results[$moduleName] .= $lv->display(false, false);
                }
			}
		}

		if($has_results) {
			arsort($module_counts);
			foreach($module_counts as $name=>$value) {
				echo $module_results[$name];
			}
		}
		else {
			echo '<br>';
			echo $home_mod_strings['LBL_NO_RESULTS'];
			echo $home_mod_strings['LBL_NO_RESULTS_TIPS'];
		}

	}

	function buildCache()
	{

		global $beanList, $beanFiles, $dictionary;

		$supported_modules = array();

		foreach($beanList as $moduleName=>$beanName)
		{
			if (!isset($beanFiles[$beanName]))
				continue;

			if($beanName == 'aCase') $beanName = 'Case';
			
			$manager = new VardefManager ( );
			$manager->loadVardef( $moduleName , $beanName ) ;

			// obtain the field definitions used by generateSearchWhere (duplicate code in view.list.php)
			if(file_exists('custom/modules/'.$moduleName.'/metadata/metafiles.php')){
                require('custom/modules/'.$moduleName.'/metadata/metafiles.php');	
            }elseif(file_exists('modules/'.$moduleName.'/metadata/metafiles.php')){
                require('modules/'.$moduleName.'/metadata/metafiles.php');
            }
 		
			
			if(!empty($metafiles[$moduleName]['searchfields']))
				require $metafiles[$moduleName]['searchfields'] ;
			elseif(file_exists("modules/{$moduleName}/metadata/SearchFields.php"))
				require "modules/{$moduleName}/metadata/SearchFields.php" ;

			if(isset($dictionary[$beanName]['unified_search']) && $dictionary[$beanName]['unified_search']) // if bean participates in uf
			{

				$fields = array();
				foreach ( $dictionary [ $beanName ][ 'fields' ] as $field => $def )
				{
					// We cannot enable or disable unified_search for email in the vardefs as we don't actually have a vardef entry for 'email' -
					// the searchFields entry for 'email' doesn't correspond to any vardef entry. Instead it contains SQL to directly perform the search.
					// So as a proxy we allow any field in the vardefs that has a name starting with 'email...' to be tagged with the 'unified_search' parameter

					if (strpos($field,'email') !== false)
						$field = 'email' ;
						
					//bug: 38139 - allow phone to be searched through Global Search
					if (strpos($field,'phone') !== false)
						$field = 'phone' ;

					if ( isset($def['unified_search']) && $def['unified_search'] && isset ( $searchFields [ $moduleName ] [ $field ]  ))
					{
						$fields [ $field ] = $searchFields [ $moduleName ] [ $field ] ;
					}
				}

				if(count($fields) > 0) {
					$supported_modules [$moduleName] ['fields'] = $fields;
					if ( isset($dictionary[$beanName]['unified_search_default_enabled']) && 
					        $dictionary[$beanName]['unified_search_default_enabled'] == FALSE ) {
                        $supported_modules [$moduleName]['default'] = false;
                    }
                    else {
                        $supported_modules [$moduleName]['default'] = true;
                    }
				}

			}

		}
		ksort($supported_modules);
		write_array_to_file('unified_search_modules', $supported_modules, $GLOBALS['sugar_config']['cache_dir'].'modules/unified_search_modules.php');

	}
}

?>