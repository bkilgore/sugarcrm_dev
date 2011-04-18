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
 * The Base adapter only stores values in-memory.
 *
 * Cache adapters can extend this class in order to acheive local, in-memory
 * caching to reduce the number of round trips to outside caching mechanisms
 * during a single request.
 *
 */
class SugarCache_Base
{
    /**
     * The status of this object
     *  - null if init() has not been called
     *  - false if init() failed
     *  - true if init() succeeded
     *
     * @var null|bool
     * @access public
     */
    var $initialized = null;

    /**
     * Contains an in-memory cache of values in the cache
     *
     * @var array
     * @access protected
     */
    var $_cache = array();

    var $_my_class_name = '';

    /**
     * Handle any initialization.
     *
     * @internal As shown here, at a minimum init() is responsible for flagging
     *           the {@link $initialized} property to true on success.
     */
    function init()
    {
        $this->initialized = true;
        $this->_my_class_name = strtolower(get_class($this));
    }

    /**
     * Set a given key/value pair within the cache
     *
     * @param string $key
     * @param mixed $value
     */
    function set($key, $value)
    {
        if(EXTERNAL_CACHE_DEBUG) {
            SugarCache::log("Step 1: Adding key to {$GLOBALS['external_cache_type']} cache $key with value ($value)");
        }

        if(empty($value)) {
            $value = EXTERNAL_CACHE_NULL_VALUE;
        }

        if(EXTERNAL_CACHE_DEBUG) {
            SugarCache::log("Step 2: Adding key to {$GLOBALS['external_cache_type']} cache $key with value ($value)");
        }

        $this->_cache[$key] = $value;
    }

    /**
     * Retrieve the value of a given key
     *
     * @param string $key
     * @return mixed
     */
    function get($key)
    {
        $GLOBALS['external_cache_request_local_total']++;
        if (isset($this->_cache[$key])) {
            if (EXTERNAL_CACHE_DEBUG) {
                SugarCache::log("BASE: found {$key}", 'lightpass');
            }
            $GLOBALS['external_cache_request_local_hits']++;
            return $this->_cache[$key];
        } else {
            if (EXTERNAL_CACHE_DEBUG) {
                $type = $this->_my_class_name == 'sugarcache_base' ? 'fail' : 'lightfail';
                SugarCache::log("BASE: unable to locate {$key}", $type);
            }
        }
    }

    /**
     * Unset a given value
     *
     * @internal The term "unset" is a reserved word within PHP.  This
     *           opts for using the magic __unset() within PHP5 to enable
     *           direct unset($cache->foo) calls.  Due to BC considerations
     *           with PHP 4, however, this method should be invoked
     *           directly via $cache->__unset('foo');
     *
     * @param string $key
     */
    function __unset($key)
    {
        unset($this->_cache[$key]);
    }

    /**
     * Clean opcode cache
     */
    function clean_opcodes()
    {
    	/* nothing by default */
    }
}