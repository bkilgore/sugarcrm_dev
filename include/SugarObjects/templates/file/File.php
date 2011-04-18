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

 
 require_once('include/SugarObjects/templates/basic/Basic.php');
 require_once('include/upload_file.php');
  require_once('include/formbase.php');
class File extends Basic{
	function File(){
		parent::Basic();
	}

	//Must overwrite the save operation for uploaded file.
	var $file_url;
	var $file_url_noimage;
	function save($check_notify=false){
		if (!empty($this->uploadfile))
			$this->filename = $this->uploadfile;
		return parent::save($check_notify);
		
 	}



	function fill_in_additional_detail_fields(){

		global $theme;
		global $current_language;
		global $timedate;
		global $app_list_strings;
		$this->uploadfile = $this->filename;
		$mod_strings = return_module_language($current_language, $this->object_name);
		global $img_name;
		global $img_name_bare;
		if (!$this->file_ext) {
			$img_name = SugarThemeRegistry::current()->getImageURL(strtolower($this->file_ext)."_image_inline.gif");
			$img_name_bare = strtolower($this->file_ext)."_image_inline";
		}
		//set default file name.
		if (!empty ($img_name) && file_exists($img_name)) {
			$img_name = $img_name_bare;
		} else {
			$img_name = "def_image_inline"; //todo change the default image.
		}
		$this->file_url_noimage = basename(UploadFile :: get_url($this->filename, $this->id));
		if(!empty($this->status_id)) {
	       $this->status = $app_list_strings['document_status_dom'][$this->status_id];
	    }

	}
	
	// need to override to have a name field created for this class
	function retrieve($id = -1, $encode=true) {
		$ret_val = parent::retrieve($id, $encode);
		$this->_create_proper_name_field();
		return $ret_val;
	}
	

	function _create_proper_name_field() {
		global $locale;
		$this->name = $this->document_name;
	}
}
?>
