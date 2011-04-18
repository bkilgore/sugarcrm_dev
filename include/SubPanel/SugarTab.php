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




if(empty($GLOBALS['sugar_smarty']))$GLOBALS['sugar_smarty'] = new Sugar_Smarty();
class SugarTab{
    
    function SugarTab($type='singletabmenu'){
        $this->type = $type;
        
    }
    
    function setup($mainTabs, $otherTabs=array(), $subTabs=array(), $selected_group='All'){
        global $sugar_version, $sugar_config, $current_user;
        
        $max_tabs = $current_user->getPreference('max_tabs');
        if(!isset($max_tabs) || $max_tabs <= 0) $max_tabs = $GLOBALS['sugar_config']['default_max_tabs'];
        
        $moreTabs = array_slice($mainTabs,$max_tabs);
        /* If the current tab is in the 'More' menu, move it into the visible menu. */
        if(!empty($moreTabs[$selected_group]))
        {
        	$temp = array($selected_group => $mainTabs[$selected_group]);
            unset($mainTabs[$selected_group]);
            array_splice($mainTabs, $max_tabs-1, 0, $temp);
        }
        
        $GLOBALS['sugar_smarty']->assign('showLinks', 'false');
        $GLOBALS['sugar_smarty']->assign('sugartabs', array_slice($mainTabs, 0, $max_tabs));
        $GLOBALS['sugar_smarty']->assign('moreMenu', array_slice($mainTabs, $max_tabs));
        $GLOBALS['sugar_smarty']->assign('othertabs', $otherTabs);
        $GLOBALS['sugar_smarty']->assign('startSubPanel', $selected_group);
        $GLOBALS['sugar_smarty']->assign('sugarVersionJsStr', "?s=$sugar_version&c={$sugar_config['js_custom_version']}");
        if(!empty($mainTabs))
        {
            $mtak = array_keys($mainTabs);
            $GLOBALS['sugar_smarty']->assign('moreTab', $mainTabs[$mtak[min(count($mtak)-1, $max_tabs-1)]]['label']);
        }
    }
    
    function fetch(){
        return $GLOBALS['sugar_smarty']->fetch('include/SubPanel/tpls/' . $this->type . '.tpl');
    }
    function display(){
       $GLOBALS['sugar_smarty']->display('include/SubPanel/tpls/' . $this->type . '.tpl');
    }
    
    
}



?>
