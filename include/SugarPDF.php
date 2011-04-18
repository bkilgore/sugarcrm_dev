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

 * Description:
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
 * Reserved. Contributor(s): ______________________________________..
 * *******************************************************************************/

require_once("include/pdf/class.expdf.php");
/**
 * Subclass of EzPDF for SugarCRM
 * contains SugarCRM-specific private methods for handling of data for PDF
 * export
 */
class SugarPDF extends Cezpdf {
	
	/**
	 * sole constructor
	 * @param array vars Setup values for parent class, EzPDF
	 */
	function SugarPDF($vars) {
		parent::Cezpdf($vars);
	}
	
	/**
	 * takes a $bean and processes all of its list variables for character set
	 * issues
	 * @param bean object The focus bean
	 * @return bean object The focus bean with processed strings
	 */
	function handleBeanStrings($bean) {
		foreach($bean->field_defs as $k => $field) {
			if($field['type'] == 'varchar' || $field['type'] == 'text' || $field['type'] == 'enum') {
				$bean->$k = $this->handleCharset($bean->$k);
			}
		}
		
		return $bean;
	}

	/**
	 * Translates text from UTF-8 (as of SugarCRM v4.5) into the selected
	 * default character set for a given instance, abrogated by user preference.
	 * @param string text The text to be handled
	 * @return string ret The translated string.
	 */
	function handleCharset($text) {
		global $locale;
		
		$ret = $locale->translateCharset($text, 'UTF-8', $locale->getPrecedentPreference('default_export_charset'));
		return $ret;
	}
}
?>