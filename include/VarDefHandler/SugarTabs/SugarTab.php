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
    	// TODO - prefs here
        //_pp($subTabs);_pp($otherTabs);
        $max_subtabs = $GLOBALS['current_user']->getPreference('max_subtabs');
        if($max_subtabs <= 0) $max_subtabs = 12;
        $max_tabs = $GLOBALS['current_user']->getPreference('max_tabs');
        if($max_tabs <= 0) $max_tabs = 12;
        $GLOBALS['sugar_smarty']->assign('sugartabs', array_slice($mainTabs, 0, $max_tabs));
        $GLOBALS['sugar_smarty']->assign('subtabs', array_slice($subTabs, 0, $max_subtabs));
        $GLOBALS['sugar_smarty']->assign('moreMenu', array_slice($mainTabs, $max_tabs));
        $GLOBALS['sugar_smarty']->assign('moreSubMenuName', $selected_group);
        $GLOBALS['sugar_smarty']->assign('moreSubMenu', array_slice($subTabs, $max_subtabs));
        $otherMoreTabs = array();
        if(!empty($otherTabs))
        {
            foreach($otherTabs as $key => $ot)
            {
            	$otherMoreTabs[$key] = array('key' => $key,
                                             'tabs' => array_slice($ot['tabs'], $max_subtabs));
                $otherTabs[$key]['tabs'] = array_slice($ot['tabs'], 0, $max_subtabs);
            }
        }
        else
        {
            $otherMoreTabs[$selected_group] = array('key' => $selected_group,
                                                    'tabs' => array_slice($subTabs, $max_subtabs));
            $otherTabs[$selected_group]['tabs'] = array_slice($subTabs, 0, $max_subtabs);
        }
        //_pp($otherMoreTabs);
        $GLOBALS['sugar_smarty']->assign('othertabs', $otherTabs);
        $GLOBALS['sugar_smarty']->assign('otherMoreSubMenu', $otherMoreTabs);
        $GLOBALS['sugar_smarty']->assign('startSubPanel', $selected_group);
        if(!empty($mainTabs))
        {
            $mtak = array_keys($mainTabs);
            $GLOBALS['sugar_smarty']->assign('moreTab', $mainTabs[$mtak[min(count($mtak)-1, $max_tabs-1)]]['label']);
        }
    }
    
    function fetch(){
        return $GLOBALS['sugar_smarty']->fetch('include/SugarTabs/tpls/' . $this->type . '.tpl');
    }
    function display(){
       $GLOBALS['sugar_smarty']->display('include/SugarTabs/tpls/' . $this->type . '.tpl');
    }
    
    
}



?>
