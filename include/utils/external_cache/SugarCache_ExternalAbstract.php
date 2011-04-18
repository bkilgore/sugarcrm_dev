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


/**
 * @abstract
 */
class SugarCache_ExternalAbstract extends SugarCache_Base
{
    /**
     * Use the parent of object to attempt to retrieve cache?  i.e., use local
     * memory cache.
     *
     * @var bool
     * @access protected
     */
    var $_use_parent = true;
    
    /**
     * An internal value that can be used to adjust the length of a timeout.
     *
     * If not set prior to calling {@link init()}, this will default to the constant
     * EXTERNAL_CACHE_INTERVAL_SECONDS
     *
     * @var int
     */
    var $timeout = null;
    
    /**
     * Stores the cache name
     * @access private
     */
    var $_name = '';

    /**
     * Serves to initialize this cache
     */
    function init()
    {
        if (empty($this->_timeout)) {
            $this->timeout = EXTERNAL_CACHE_INTERVAL_SECONDS;
        }
        
        $this->_use_parent = false;
        
        $value = $this->get(EXTERNAL_CACHE_WORKING_CHECK_KEY);
        if ($value != EXTERNAL_CACHE_WORKING_CHECK_KEY) {
            $this->set(
                EXTERNAL_CACHE_WORKING_CHECK_KEY,
                EXTERNAL_CACHE_WORKING_CHECK_KEY
            );
            $value = $this->get(EXTERNAL_CACHE_WORKING_CHECK_KEY);
            
            // Clear the cache statistics after the test.  This makes the statistics work out.
            $GLOBALS['external_cache_request_external_hits'] = 0;
            $GLOBALS['external_cache_request_external_total'] = 0;
        }
        
        $this->_use_parent = true;
        $this->initialized = (EXTERNAL_CACHE_WORKING_CHECK_KEY == $value);
        
        if (empty($this->_name)) {
            $this->_name = substr(get_class($this), 11);
        }
    }

    function get($key)
    {
        if ($this->_use_parent && !is_null($value = parent::get($key))) {
            if (EXTERNAL_CACHE_DEBUG) {
                SugarCache::log("{$this->_name}:: found {$key} in local memory cache");
            }
            return $value;
        }

        if(!$GLOBALS['external_cache_enabled']) {
            if (EXTERNAL_CACHE_DEBUG) {
                SugarCache::log("{$this->_name}:: caching disabled", 'fail');
            }
            return null;
        }

        $GLOBALS['external_cache_request_external_total']++;

        if(EXTERNAL_CACHE_DEBUG) {
            SugarCache::log("{$this->_name}:: retrieving key from cache ($key)");
        }

        return null;
    }

    function _realKey($key)
    {
        return $GLOBALS['sugar_config']['unique_key'] . $key;
    }

    function _processGet($key, $value)
    {
        if (!empty($value)) {
            if (EXTERNAL_CACHE_DEBUG) {
                SugarCache::log("{$this->_name}:: Retrieved from external cache: {$key}", 'pass');
            }
            $GLOBALS['external_cache_request_external_hits']++;
            $this->_cache[$key] = $value;
            return $this->_cache[$key];
        }
        if(EXTERNAL_CACHE_DEBUG) {
            SugarCache::log("{$this->_name}:: External cache retrieve failed: $key", 'fail');
        }
        return null;
    }
}