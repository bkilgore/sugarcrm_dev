<?php
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

require_once('include/SugarFields/Fields/Base/SugarFieldBase.php');

class SugarFieldFile extends SugarFieldBase {
   
	function getDetailViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {

        global $app_strings;
        if(!isset($displayParams['link'])) {
           $error = $app_strings['ERR_SMARTY_MISSING_DISPLAY_PARAMS'] . 'link';
           $GLOBALS['log']->error($error);	
           $this->ss->trigger_error($error);
           return;
        }
        
        if(!isset($displayParams['id'])) {
           $error = $app_strings['ERR_SMARTY_MISSING_DISPLAY_PARAMS'] . 'id';
           $GLOBALS['log']->error($error);	
           $this->ss->trigger_error($error);
           return;
        }        

        $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
        return $this->fetch('include/SugarFields/Fields/File/DetailView.tpl');
    }
    
	public function save(&$bean, $params, $field, $properties, $prefix = ''){
		require_once('include/upload_file.php');
		$upload_file = new UploadFile($prefix . $field);

		//remove file
		if (isset($_REQUEST['remove_file_' . $field]) && $_REQUEST['remove_file_' . $field] == 1)
		{
			$upload_file->unlink_file($bean->$field);
			$bean->$field="";
		}
		
		$move=false;
		if (isset($_FILES[$prefix . $field]) && $upload_file->confirm_upload())
		{
    		$bean->$field = $upload_file->get_stored_file_name();
    		$bean->file_mime_type = $upload_file->mime_type;
			$bean->file_ext = $upload_file->file_ext;
			$move=true;
		}
 		
		if ($move) {
			if (empty($bean->id)) { 
				$bean->id = create_guid();
				$bean->new_with_id = true;
			}
        
			$upload_file->final_move($bean->id);
		}
	}
}
?>