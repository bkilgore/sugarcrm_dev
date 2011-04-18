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

 * Description: class for sanitizing field values
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 ********************************************************************************/
require_once('modules/Import/ImportFile.php');

class ImportFieldSanitize
{
    /**
     * properties set to handle locale formatting
     */
    public $dateformat;
    public $timeformat;
    public $timezone;
    public $currency_symbol;
    public $default_currency_significant_digits;
    public $num_grp_sep;
    public $dec_sep;
    public $default_locale_name_format;
    
    /**
     * array of modules/users_last_import ids pairs that are created in this class
     * needs to be reset after the row is imported
     */
    public static $createdBeans = array();
    
    /**
     * Validate boolean fields
     *
     * @param  $value  string
     * @param  $vardef array
     * @return string sanitized and validated value on success, bool false on failure
     */
    public function bool(
        $value,
        $vardef
        )
    {
        $bool_values = array(0=>'0',1=>'no',2=>'off',3=>'n',4=>'yes',5=>'y',6=>'on',7=>'1');
        $bool_search = array_search($value,$bool_values);
        if ( $bool_search === false ) {
            return false;
        } 
        else {
            //Convert all the values to a real bool.
            $value = (int) ( $bool_search > 3 );
        }
        if ( isset($vardef['dbType']) && $vardef['dbType'] == 'varchar' )
            $value = ( $value ? 'on' : 'off' );
        
        return $value;
    }
    
    /**
     * Validate currency fields
     *
     * @param  $value  string
     * @param  $vardef array
     * @return string sanitized and validated value on success, bool false on failure
     */
    public function currency(
        $value,
        $vardef
        )
    {
        $value = str_replace($this->currency_symbol,"",$value);
        
        return $this->float($value,$vardef);
    }
    
     /**
     * Validate datetimecombo fields
     *
     * @see ImportFieldSanitize::datetime()
     *
     * @param  $value    string
     * @param  $vardef   array
     * @return string sanitized and validated value on success, bool false on failure
     */
    public function datetimecombo(
        $value,
        $vardef
        )
    {
        return $this->datetime($value,$vardef);
    }
    
    /**
     * Validate datetime fields
     *
     * @param  $value    string
     * @param  $vardef   array
     * @return string sanitized and validated value on success, bool false on failure
     */
    public function datetime(
        $value,
        $vardef
        )
    {
        global $timedate;
        $value = preg_replace('/\s([pm|PM|am|AM]+)/', '\1', $value);
        $format = $this->dateformat . ' ' . $this->timeformat;
        
        if ( !$timedate->check_matching_format($value, $format) ) {
            // see if adding a valid time at the end makes it work
			list($dateformat,$timeformat) = explode(' ',$format);
            $value .= ' ' . date($timeformat,0);
            if ( !$timedate->check_matching_format($value, $format) ) {
                return false;
            }
        }
        
        if ( !$this->isValidTimeDate($value, $format) )
            return false;
        
        $value = $timedate->swap_formats(
            $value, $format, $timedate->get_date_time_format());
        $value = $timedate->handle_offset(
            $value, $timedate->get_date_time_format(), false, $GLOBALS['current_user'], $this->timezone);
        $value = $timedate->swap_formats(
            $value, $timedate->get_date_time_format(), $timedate->get_db_date_time_format() );
        
        return $value;
    }
    
    /**
     * Validate date fields
     *
     * @param  $value  string
     * @param  $vardef array
     * @return string sanitized and validated value on success, bool false on failure
     */
    public function date(
        $value,
        $vardef
        )
    {
        global $timedate;
        
        $format = $this->dateformat;
        
        if ( !$timedate->check_matching_format($value, $format) ) 
            return false;
        
        if ( !$this->isValidTimeDate($value, $format) )
            return false;
        
        $value = $timedate->swap_formats(
            $value, $format, $timedate->get_date_format());
        
        return $value;
    }
    
    /**
     * Validate email fields
     *
     * @param  $value  string
     * @param  $vardef array
     * @return string sanitized and validated value on success, bool false on failure
     */
    public function email(
        $value,
        $vardef
        )
    {
        if ( !empty($value) && !preg_match('/^\w+(?:[\'.\-+]\w+)*@\w+(?:[.\-]\w+)*(?:[.]\w{2,})+$/',$value) ) {
            return false;
        }
        
        return $value;
    }
    
    /**
     * Validate enum fields
     *
     * @param  $value  string
     * @param  $vardef array
     * @return string sanitized and validated value on success, bool false on failure
     */
    public function enum(
        $value,
        $vardef
        )
    {
        global $app_list_strings;
        
        // Bug 27467 - Trim the value given
        $value = trim($value);
        
        if ( isset($app_list_strings[$vardef['options']]) 
                && !isset($app_list_strings[$vardef['options']][$value]) ) {
            // Bug 23485/23198 - Check to see if the value passed matches the display value
            if ( in_array($value,$app_list_strings[$vardef['options']]) )
                $value = array_search($value,$app_list_strings[$vardef['options']]);
            // Bug 33328 - Check for a matching key in a different case
            elseif ( in_array(strtolower($value), array_keys(array_change_key_case($app_list_strings[$vardef['options']]))) ) {
                foreach ( $app_list_strings[$vardef['options']] as $optionkey => $optionvalue )
                    if ( strtolower($value) == strtolower($optionkey) )
                        $value = $optionkey;
            }
            // Bug 33328 - Check for a matching value in a different case
            elseif ( in_array(strtolower($value), array_map('strtolower', $app_list_strings[$vardef['options']])) ) {
                foreach ( $app_list_strings[$vardef['options']] as $optionkey => $optionvalue )
                    if ( strtolower($value) == strtolower($optionvalue) )
                        $value = $optionkey;
            }
            else
                return false;
        }
        
        return $value;
    }
    
    /**
     * Validate float fields
     *
     * @param  $value  string
     * @param  $vardef array
     * @return string sanitized and validated value on success, bool false on failure
     */
    public function float(
        $value,
        $vardef
        )
    {
        $value = str_replace($this->num_grp_sep,"",$value);
        $dec_sep = $this->dec_sep;
        if ( $dec_sep != '.' ) {
            $value = str_replace($dec_sep,".",$value);
        }
        if ( !is_numeric($value) ) {
            return false;
        }
        
        return $value;
    }
    
    /**
     * Split full_name field into first_name and last_name
     *
     * @param  $value  string
     * @param  $vardef array
     * @param  $focus  object bean of the module we're importing into
     * @return string sanitized and validated value on success, bool false on failure
     */
    public function fullname(
        $value,
        $vardef,
        &$focus
        )
    {
        if ( property_exists($focus,'first_name') && property_exists($focus,'last_name') ) {
            $name_arr = preg_split('/\s+/',$value);
    
            if ( count($name_arr) == 1) {
                $focus->last_name = $value;
            }
            else {
                // figure out what comes first, the last name or first name
                if ( strpos($this->default_locale_name_format,'l') > strpos($this->default_locale_name_format,'f') ) {
                    $focus->first_name = array_shift($name_arr);
                    $focus->last_name = join(' ',$name_arr);
                }
                else {
                    $focus->last_name = array_shift($name_arr);
                    $focus->first_name = join(' ',$name_arr);
                }
            }
        }
    }
    
    /**
     * Validate id fields
     *
     * @param  $value  string
     * @param  $vardef array
     * @return string sanitized and validated value on success, bool false on failure
     */
    public function id(
        $value,
        $vardef
        )
    {
        if ( strlen($value) > 36 ) {
            return false;
        }
        
        return $value;
    }
    
    /**
     * Validate int fields
     *
     * @param  $value  string
     * @param  $vardef array
     * @return string sanitized and validated value on success, bool false on failure
     */
    public function int(
        $value,
        $vardef
        )
    {
        $value = str_replace($this->num_grp_sep,"",$value);
        if (!is_numeric($value) || strstr($value,".")) {
            return false;
        }
        
        return $value;
    }
    
    /**
     * Validate multienum fields
     *
     * @param  $value  string
     * @param  $vardef array
     * @return string sanitized and validated value on success, bool false on failure
     */
    public function multienum(
        $value,
        $vardef
        )
    {
        if(!empty($value) && is_array($value)) {
            $enum_list = $value;
        }
        else {
            // If someone was using the old style multienum import technique
            $value = str_replace("^","",$value);
            
            // We will need to break it apart to put test it.
            $enum_list = explode(",",$value);
        }
        // parse to see if all the values given are valid
        foreach ( $enum_list as $key => $enum_value ) {
            $enum_list[$key] = $enum_value = trim($enum_value);
            if ( $this->enum($enum_value,$vardef) === false ) {
                return false;
            }
        }
        $value = encodeMultienumValue($enum_list);
        
        return $value;
    }
    
    /**
     * Validate name fields
     *
     * @param  $value  string
     * @param  $vardef array
     * @return string sanitized and validated value on success, bool false on failure
     */
    public function name(
        $value,
        $vardef
        )
    {
        if( isset($vardef['len']) ) { 
            // check for field length
            $value = sugar_substr($value, $vardef['len']);
        }
        
        return $value;
    }
    
    /**
     * Validate num fields
     *
     * @param  $value  string
     * @param  $vardef array
     * @return string sanitized and validated value on success, bool false on failure
     */
    public function num(
        $value,
        $vardef
        )
    {
        return $this->int($value,$vardef);
    }
    
    /**
     * Validate parent fields
     *
     * @param  $value  string
     * @param  $vardef array
     * @param  $focus  object bean of the module we're importing into
     * @param  $addRelatedBean bool true if we want to add the related bean if it is not found
     * @return string sanitized and validated value on success, bool false on failure
     */
    public function parent(
        $value,
        $vardef,
        &$focus,
        $addRelatedBean = true
        )
    {
        global $beanList;
        
        if ( isset($vardef['type_name']) ) {
            $moduleName = $vardef['type_name'];
            if ( isset($focus->$moduleName) && isset($beanList[$focus->$moduleName]) ) {
                $vardef['module'] = $focus->$moduleName;
                $vardef['rname'] = 'name';
                $relatedBean = loadBean($focus->$moduleName);
                $vardef['table'] = $relatedBean->table_name;
                return $this->relate($value,$vardef,$focus,$addRelatedBean);
            }
        }
        
        return false;
    }
    
    /**
     * Validate relate fields
     *
     * @param  $value  string
     * @param  $vardef array
     * @param  $focus  object bean of the module we're importing into
     * @param  $addRelatedBean bool true if we want to add the related bean if it is not found
     * @return string sanitized and validated value on success, bool false on failure
     */
    public function relate(
        $value,
        $vardef,
        &$focus,
        $addRelatedBean = true
        )
    {
        if ( !isset($vardef['module']) )
            return false;
        $newbean = loadBean($vardef['module']);
        
        // Bug 38885 - If we are relating to the Users table on user_name, there's a good chance
        // that the related field data is the full_name, rather than the user_name. So to be sure
        // let's try to lookup the field the relationship is expecting to use (user_name).
        if ( $vardef['module'] == 'Users' && $vardef['rname'] == 'user_name' ) {
            $userFocus = new User;
            $userFocus->retrieve_by_string_fields(
                array($userFocus->db->concat('users',array('first_name','last_name')) => $value ));
            if ( !empty($userFocus->id) ) {
                $value = $userFocus->user_name;
            }
        }       
        
        // Bug 32869 - Assumed related field name is 'name' if it is not specified
        if ( !isset($vardef['rname']) )            
            $vardef['rname'] = 'name';
        
        // Bug 27046 - Validate field against type as it is in the related field
        $rvardef = $newbean->getFieldDefinition($vardef['rname']);
        if ( isset($rvardef['type']) 
                && method_exists($this,$rvardef['type']) ) {
            $fieldtype = $rvardef['type'];
            $returnValue = $this->$fieldtype($value,$rvardef,$focus,$addRelatedBean);
            if ( !$returnValue )
                return false;
            else
                $value = $returnValue;
        }
        
        if ( isset($vardef['id_name']) ) {
            $idField = $vardef['id_name'];
            
            // Bug 24075 - clear out id field value if it is invalid
            if ( isset($focus->$idField) ) {
                $checkfocus = loadBean($vardef['module']);
                if ( $checkfocus && is_null($checkfocus->retrieve($focus->$idField)) )
                    $focus->$idField = '';
            }
            
            // Bug 38356 - Populate the table entry in the vardef from the bean information in case it's not provided
            if (!isset($vardef['table'])) {
                // Set target module table as the default table name
                $tmpfocus = loadBean($vardef['module']);
                $vardef['table'] = $tmpfocus->table_name;
            }
            
            // be sure that the id isn't already set for this row
            if ( empty($focus->$idField)
                    && $idField != $vardef['name']
                    && !empty($vardef['rname']) 
                    && !empty($vardef['table'])) {
                // Bug 27562 - Check db_concat_fields first to see if the field name is a concat
                $relatedFieldDef = $newbean->getFieldDefinition($vardef['rname']);
                if ( isset($relatedFieldDef['db_concat_fields']) 
                        && is_array($relatedFieldDef['db_concat_fields']) )
                    $fieldname = db_concat($vardef['table'],$relatedFieldDef['db_concat_fields']);
                else
                    $fieldname = $vardef['rname'];
                // lookup first record that matches in linked table
                $query = "SELECT id 
                            FROM {$vardef['table']} 
                            WHERE {$fieldname} = '" . $focus->db->quote($value) . "'
                                AND deleted != 1";
                
                $result = $focus->db->limitQuery($query,0,1,true, "Want only a single row");
                if(!empty($result)){
                    if ( $relaterow = $focus->db->fetchByAssoc($result) )
                        $focus->$idField = $relaterow['id'];
                    elseif ( !$addRelatedBean 
                            || ( $newbean->bean_implements('ACL') && !$newbean->ACLAccess('save') )
                            || ( in_array($newbean->module_dir,array('Teams','Users')) )
                            )
                        return false;
                    else {
                        // add this as a new record in that bean, then relate
                        if ( isset($relatedFieldDef['db_concat_fields']) 
                                && is_array($relatedFieldDef['db_concat_fields']) ) {
                            $relatedFieldParts = explode(' ',$value);
                            foreach ($relatedFieldDef['db_concat_fields'] as $relatedField)
                                $newbean->$relatedField = array_shift($relatedFieldParts);
                        }
                        else
                            $newbean->$vardef['rname'] = $value;
                        if ( !isset($focus->assigned_user_id) || $focus->assigned_user_id == '' )
                            $newbean->assigned_user_id = $GLOBALS['current_user']->id;
                        else
                            $newbean->assigned_user_id = $focus->assigned_user_id;
                        if ( !isset($focus->modified_user_id) || $focus->modified_user_id == '' )
                            $newbean->modified_user_id = $GLOBALS['current_user']->id;
                        else
                            $newbean->modified_user_id = $focus->modified_user_id;
                        
                        // populate fields from the parent bean to the child bean
                        $focus->populateRelatedBean($newbean);
                        
                        $newbean->save(false);
                        $focus->$idField = $newbean->id;
                        $this->createdBeans[] = ImportFile::writeRowToLastImport(
                                $focus->module_dir,$newbean->object_name,$newbean->id);
                    }
                }
            }
        }
        
        return $value;
    }
    
    
    /**
     * Validate sync_to_outlook field
     *
     * @param  $value     string
     * @param  $vardef    array
     * @param  $bad_names array used to return list of bad users/teams in $value
     * @return string sanitized and validated value on success, bool false on failure
     */
    public function synctooutlook(
        $value,
        $vardef,
        &$bad_names
        )
    {
        static $focus_user;
        
        // cache this object since we'll be reusing it a bunch
        if ( !($focus_user instanceof User) ) {
            
            $focus_user = new User();
        }
        
        
        if ( !empty($value) && strtolower($value) != "all" ) {
            $theList   = explode(",",$value);
            $isValid   = true;
            $bad_names = array();
            foreach ($theList as $eachItem) {
                if ( $focus_user->retrieve_user_id($eachItem)
                        || $focus_user->retrieve($eachItem)
                ) {
                    // all good
                }
                else {
                    $isValid     = false;
                    $bad_names[] = $eachItem;
                    continue;
                }
            }
            if(!$isValid) {
                return false;
            }
        }
        
        return $value;
    }
    
    /**
     * Validate time fields
     *
     * @param  $value    string
     * @param  $vardef   array
     * @return string sanitized and validated value on success, bool false on failure
     */
    public function time(
        $value,
        $vardef
        )
    {
        global $timedate;
        
        $format = $this->timeformat;
        
        if ( !$timedate->check_matching_format($value, $format) ) 
            return false;
        
        if ( !$this->isValidTimeDate($value, $format) )
            return false;
        
        $value = $timedate->swap_formats(
            $value, $format, $timedate->get_time_format());
        $value = $timedate->handle_offset(
            $value, $timedate->get_time_format(), false, $GLOBALS['current_user'], $this->timezone);
        $value = $timedate->handle_offset(
            $value, $timedate->get_time_format(), true);
        
        return $value;
    }
    
    /**
     * Validate varchar fields
     *
     * @param  $value  string
     * @param  $vardef array
     * @return string sanitized and validated value on success, bool false on failure
     */
    public function varchar(
        $value,
        $vardef
        )
    {
        return $this->name($value,$vardef);
    }
    
    /**
     * Added to handle Bug 24104, to make sure the date/time value is correct ( i.e. 20/20/2008 doesn't work )
     *
     * @param  $value  string
     * @param  $format string
     * @return string sanitized and validated value on success, bool false on failure
     */
    private function isValidTimeDate(
        $value,
        $format
        )
    {
        global $timedate;
        
        $dateparts = array();
        $reg = $timedate->get_regular_expression($format);
        preg_match('@'.$reg['format'].'@', $value, $dateparts);
        
        if ( isset($reg['positions']['a']) 
                && !in_array($dateparts[$reg['positions']['a']], array('am','pm')) )
            return false;
        if ( isset($reg['positions']['A']) 
                && !in_array($dateparts[$reg['positions']['A']], array('AM','PM')) )
            return false;
        if ( isset($reg['positions']['h']) && (
                !is_numeric($dateparts[$reg['positions']['h']]) 
                || $dateparts[$reg['positions']['h']] < 1
                || $dateparts[$reg['positions']['h']] > 12 ) )
            return false;
        if ( isset($reg['positions']['H']) && (
                !is_numeric($dateparts[$reg['positions']['H']]) 
                || $dateparts[$reg['positions']['H']] < 0
                || $dateparts[$reg['positions']['H']] > 23 ) )
            return false;
        if ( isset($reg['positions']['i']) && (
                !is_numeric($dateparts[$reg['positions']['i']]) 
                || $dateparts[$reg['positions']['i']] < 0
                || $dateparts[$reg['positions']['i']] > 59 ) )
            return false;
        if ( isset($reg['positions']['s']) && (
                !is_numeric($dateparts[$reg['positions']['s']]) 
                || $dateparts[$reg['positions']['s']] < 0
                || $dateparts[$reg['positions']['s']] > 59 ) )
            return false;
        if ( isset($reg['positions']['d']) && (
                !is_numeric($dateparts[$reg['positions']['d']]) 
                || $dateparts[$reg['positions']['d']] < 1
                || $dateparts[$reg['positions']['d']] > 31 ) )
            return false;
        if ( isset($reg['positions']['m']) && (
                !is_numeric($dateparts[$reg['positions']['m']]) 
                || $dateparts[$reg['positions']['m']] < 1
                || $dateparts[$reg['positions']['m']] > 12 ) )
            return false;
        if ( isset($reg['positions']['Y']) &&
                !is_numeric($dateparts[$reg['positions']['Y']]) )
            return false;
        
        return true;
    }

}
