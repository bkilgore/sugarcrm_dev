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


class SugarSpot 
{	
	/**
	 * Performs the search and returns the HTML widget containing the results
	 *
	 * @param  $query   string what we are searching for
	 * @param  $modules array  modules we are searching in
	 * @param  $offset  int    search result offset
	 * @return string HTML widget
	 */
	public function searchAndDisplay(
	    $query, 
	    $modules, 
	    $offset = -1
	    )
	{
		$query_encoded = urlencode($query);
	    $results = $this->_performSearch($query, $modules, $offset);
		$str = '<div id="SpotResults">';
		$actions=0;
		$foundData = false;
		foreach($results as $m=>$data){ 
			if(empty($data['data'])){
				continue;
			}
			$foundData = true;
			
			$countRemaining = $data['pageData']['offsets']['total'] - count($data['data']);
			if($offset > 0) $countRemaining -= $offset;
			$more = '';
			$data['pageData']['offsets']['next']++;
			if($countRemaining > 0){
				$more = <<<EOHTML
<small class='more' onclick="DCMenu.spotZoom('$query', '$m','{$data['pageData']['offsets']['next']}' )">($countRemaining more)</small>
EOHTML;
			}
			
			$modDisplayString = $m;
			if(isset($GLOBALS['app_list_strings']['moduleList'][$m]))
			    $modDisplayString = $GLOBALS['app_list_strings']['moduleList'][$m];
			
			$str.= "<div>{$modDisplayString} $more</div>";
			$str.= '<ul>';
			foreach($data['data'] as $row){
				$name = '';
				if(!empty($row['NAME'])){
					$name = $row['NAME'];
				}else{
					foreach($row as $k=>$v){
						if(strpos($k, 'NAME') !== false){
							$name = $v;
							break;
						}
					}
				}
			
				    $str .= <<<EOHTML
<li><a href="index.php?module={$data['pageData']['bean']['moduleDir']}&action=DetailView&record={$row['ID']}">$name</a></li>
EOHTML;
			}
			$str.= '</ul>';
		}
		$str .= <<<EOHTML
<button onclick="document.location.href='index.php?module=Home&action=UnifiedSearch&search_form=false&advanced=false&query_string={$query_encoded}'">{$GLOBALS['app_strings']['LBL_EMAIL_SHOW_READ']}</button>
</div>
EOHTML;
		return $str;
	}
	
	/**
	 * Returns the array containing the $searchFields for a module
	 *
	 * @param  $moduleName string
	 * @return array
	 */
	protected function getSearchFields(
	    $moduleName
	    )
	{
		if(file_exists("modules/{$moduleName}/metadata/SearchFields.php")) {
			$searchFields = array();
		    require "modules/{$moduleName}/metadata/SearchFields.php" ;
			return $searchFields;
		}
		else {
			return array();
		}
	}
	/**
	 * Performs the search
	 *
	 * @param  $query   string what we are searching for
	 * @param  $modules array  modules we are searching in
	 * @param  $offset  int    search result offset
	 * @return array
	 */
	protected function _performSearch(
	    $query, 
	    $modules, 
	    $offset = -1
	    )
	{
		$primary_module='';
		$results = array();
		require_once 'include/SearchForm/SearchForm2.php' ;
		$where = '';
		
		$searchEmail = preg_match('/^([^\%]|\%)*@([^\%]|\%)*$/', $query);
		
		foreach($modules as $moduleName){ 
			if (empty($primary_module)) $primary_module=$moduleName;
			
			$searchFields = SugarSpot::getSearchFields($moduleName);
			$class = $GLOBALS['beanList'][$moduleName];
			$return_fields = array();
			$seed = new $class();
			if (empty($searchFields[$moduleName]))
			    continue;
			    
				if ($class == 'aCase') {
			            $class = 'Case';
				}
				foreach($searchFields[$moduleName] as $k=>$v){
					$keep = false;
					$searchFields[$moduleName][$k]['value'] = $query;

					if(!empty($GLOBALS['dictionary'][$class]['unified_search'])){  
						if(empty($GLOBALS['dictionary'][$class]['fields'][$k]['unified_search'])){
							
							if(isset($searchFields[$moduleName][$k]['db_field'])){
								foreach($searchFields[$moduleName][$k]['db_field'] as $field){
									if(!empty($GLOBALS['dictionary'][$class]['fields'][$field]['unified_search'])){
										$return_fields[] = $field;
										$keep = true;
									}
								}
							}
							if(!$keep){
								if(strpos($k,'email') === false || !$searchEmail) {
									unset($searchFields[$moduleName][$k]);
								}
							}
						}else{
							$return_fields[] = $k;
						}
					}else if(empty($GLOBALS['dictionary'][$class]['fields'][$k]) ){;
						unset($searchFields[$moduleName][$k]);
					}else{
						switch($GLOBALS['dictionary'][$class]['fields'][$k]['type']){
							case 'id':
							case 'date':
							case 'datetime':
							case 'bool':
								unset($searchFields[$moduleName][$k]);
							default:
								$return_fields[] = $k;
								
						}
						
					}
					
				}

		
			$searchForm = new SearchForm ( $seed, $moduleName ) ;
			$searchForm->setup (array ( $moduleName => array() ) , $searchFields , '' , 'saved_views' /* hack to avoid setup doing further unwanted processing */ ) ;
			$where_clauses = $searchForm->generateSearchWhere() ;
			$where = "";
	 		if (count($where_clauses) > 0){
                $where = '(('. implode(' ) OR ( ', $where_clauses) . '))';
            }
			
			$lvd = new ListViewData();
			$lvd->additionalDetails = false;
			$max = ( !empty($sugar_config['max_spotresults_initial']) ? $sugar_config['max_spotresults_initial'] : 5 );
			if($offset !== -1){
				$max = ( !empty($sugar_config['max_spotresults_more']) ? $sugar_config['max_spotresults_more'] : 20 );
			}
			$params = array();
			if ( $moduleName == 'Reports') {
			    $params['overrideOrder'] = true;
			    $params['orderBy'] = 'name';
			}
			$results[$moduleName]= $lvd->getListViewData($seed, $where, $offset,  $max, $return_fields,$params,'id') ;
			
		}
        return $results;
	}	
	
	/**
     * Function used to walk the array and find keys that map the queried string.
     * if both the pattern and module name is found the promote the string to thet top.
     */
    protected function _searchKeys(
        $item1, 
        $key, 
        $patterns
        ) 
    {
        //make the module name singular....
        if ($patterns[1][strlen($patterns[1])-1] == 's') {
            $patterns[1]=substr($patterns[1],0,(strlen($patterns[1])-1));
        }
        
        $module_exists = stripos($key,$patterns[1]); //primary module name.
        $pattern_exists = stripos($key,$patterns[0]); //pattern provided by the user.
        if ($module_exists !== false and $pattern_exists !== false)  {
            $GLOBALS['matching_keys']= array_merge(array(array('NAME'=>$key, 'ID'=>$key, 'VALUE'=>$item1)),$GLOBALS['matching_keys']);
        } 
        else {
            if ($pattern_exists !== false) {
                $GLOBALS['matching_keys'][]=array('NAME'=>$key, 'ID'=>$key, 'VALUE'=>$item1);
            }
        }
    }
}