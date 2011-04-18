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


class SugarCache_APC extends SugarCache_ExternalAbstract
{
    function init()
    {
        if (defined('SUGARCRM_IS_INSTALLING')) {
            if (EXTERNAL_CACHE_DEBUG) {
                SugarCache::log('not initializing on Windows during an installation');
            }
            $this->initialized = false;
            return;
        }
        parent::init();
    }

    function get($key)
    {
        $value = parent::get($key);
        if (!is_null($value)) {
            return $value;
        }
        if (EXTERNAL_CACHE_DEBUG) {
            SugarCache::log('grabbing via apc_fetch(' . $this->_realKey($key) . ')');
        }
        return $this->_processGet(
            $key,
            apc_fetch(
                $this->_realKey($key)
            )
        );
    }

    function set($key, $value)
    {
        parent::set($key, $value);

        // caching is turned off
        if(!$GLOBALS['external_cache_enabled']) {
            return;
        }

        $external_key = $this->_realKey($key);
		if (EXTERNAL_CACHE_DEBUG) {
            SugarCache::log("Step 3: Converting key ($key) to external key ($external_key)");
        }

        apc_store($external_key, $value, $this->timeout);

        if (EXTERNAL_CACHE_DEBUG) {
            SugarCache::log("Step 4: Added key to APC cache {$external_key} with value ($value) to be stored for ".EXTERNAL_CACHE_INTERVAL_SECONDS." seconds");
        }
    }

    function __unset($key)
    {
        parent::__unset($key);
        apc_delete($this->_realKey($key));
    }

    /**
     * Clean opcode cache
     */
    function clean_opcodes()
    {
		apc_clear_cache();
    }
}
