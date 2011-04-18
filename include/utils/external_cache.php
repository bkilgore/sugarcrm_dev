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


/**##@+
 * Load require libraries
 *
 * @ignore
 */
require 'include/utils/external_cache/SugarCache.php';
require 'include/utils/external_cache/SugarCache_Base.php';
require 'include/utils/external_cache/SugarCache_ExternalAbstract.php';
require 'include/utils/external_cache/SugarCache_APC.php';
require 'include/utils/external_cache/SugarCache_Memcache.php';
require 'include/utils/external_cache/SugarCache_Zend.php';
require 'include/utils/external_cache/SugarCache_ZendServer.php';
require 'include/utils/external_cache/SugarCache_sMash.php';
require 'include/utils/external_cache/SugarCache_Wincache.php';
/**##@- */

/**
 * Internal -- Has the external cache been checked to determine if it is available and configured.
 */
$GLOBALS['external_cache_checked'] = false;

/**
 * Internal -- Is the external cache available.  This setting is determined by checking for the availability
 * of the external cache functions.  It can be overridden by adding a config variable
 * (external_cache_disabled=true).
 */
$GLOBALS['external_cache_enabled'] = false;

/**
 * Internal -- This is controlled by a config setting (external_cache_reset) that will update the cache
 * with new values, but not read from the cache.
 */
$GLOBALS['external_cache_overwrite'] = false;

/**
 * Internal -- The number of hits on the external out of process cache in this request
 */
$GLOBALS['external_cache_request_external_hits'] = 0;

/**
 * Internal -- The number of total requests to the external out of process cache in this request
 */
$GLOBALS['external_cache_request_external_total'] = 0;

/**
 * Internal -- The number of hits on the external local cache in this request
 */
$GLOBALS['external_cache_request_local_hits'] = 0;

/**
 * Internal -- The number of total requests to the external local cache in this request
 */
$GLOBALS['external_cache_request_local_total'] = 0;

/**
 * Internal -- The data structure for the local cache.
 */
$GLOBALS['cache_local_store'] = array();

/**
 * Internal -- The type of external store available.
 */
$GLOBALS['external_cache_type'] = null;

/**
 * The interval in seconds that an external cache entry is valid.
 */
define('EXTERNAL_CACHE_INTERVAL_SECONDS', 300 );

/**
 * This constant is provided as a convenience for users that want to store null values
 * in the cache.  If your function frequently has null results that take a long time to
 * calculate, store those results in the cache.  On retrieval, substitue the value you
 * stored for null.
 */
define('EXTERNAL_CACHE_NULL_VALUE', "SUGAR_CACHE_NULL_ZZ");

/**
 * Set this to true to see cache debugging messages in the end user UI.
 * This is a quick way to determine how well the cache is working and find out what
 * records are not being cached effectively.
 */
define('EXTERNAL_CACHE_DEBUG', false);

/**
 * Set this to true to validate that all items stored in the external cache are
 * identical when they are retrieved.  This forces an immediate retrieve after each
 * store call to make sure the contents are reproduced exactly.
 */
define('EXTENRAL_CACHE_VALIDATE_STORES', false);

/**
 * This key is used to write to the external cache to validate that it is indeed working.
 * This prevents non-functioning caches from being detected as functional.
 */
define('EXTERNAL_CACHE_WORKING_CHECK_KEY', 'EXTERNAL_CACHE_WORKING_CHECK_KEY');

/**
 * Internal -- Determine if there is an external cache available for use.
 * Currently only Zend Platform is supported.
 */
function check_cache()
{
    if(EXTERNAL_CACHE_DEBUG) SugarCache::log("Checking cache");

    if($GLOBALS['external_cache_checked'] == false)
    {
        $GLOBALS['external_cache_checked'] = true;
        $GLOBALS['external_cache_object'] = SugarCache::discover();
    }

    if(EXTERNAL_CACHE_DEBUG) SugarCache::log("Checking cache: " . var_export($GLOBALS['external_cache_enabled'], true));
}

/**
 * This function is called once an external cache has been identified to ensure that it is correctly
 * working.
 * @return true for success, false for failure.
 */
function sugar_cache_validate()
{
    if($GLOBALS['external_cache_checked'] == false) {
        check_cache();
    }
    // should never be called
    $GLOBALS['external_cache_object']->init();
    return $GLOBALS['external_cache_object']->initialized;
}

/**
 * Retrieve a key from cache.  For the Zend Platform, a maximum age of 5 minutes is assumed.
 *
 * @param String $key -- The item to retrieve.
 * @return The item unserialized
 */
function sugar_cache_retrieve($key)
{
    if($GLOBALS['external_cache_checked'] == false) {
        check_cache();
    }
    return $GLOBALS['external_cache_object']->get($key);
}

/**
 * Internal -- This function actually retrieves information from the caches.
 * It is a helper function that provides that actual cache API abstraction.
 *
 * @param unknown_type $key
 * @return unknown
 * @deprecated
 * @see sugar_cache_retrieve
 */
function external_cache_retrieve_helper($key)
{
    if($GLOBALS['external_cache_checked'] == false) {
        check_cache();
    }
    return sugar_cache_retrieve($key);
}

/**
 * Put a value in the cache under a key
 *
 * @param String $key -- Global namespace cache.  Key for the data.
 * @param Serializable $value -- The value to store in the cache.
 */
function sugar_cache_put($key, $value)
{
    if($GLOBALS['external_cache_checked'] == false) {
        check_cache();
    }
    $GLOBALS['external_cache_object']->set($key, $value);
}

/**
 * Clear a key from the cache.  This is used to invalidate a single key.
 *
 * @param String $key -- Key from global namespace
 */
function sugar_cache_clear($key)
{
    if($GLOBALS['external_cache_checked'] == false) {
        check_cache();
    }
    $GLOBALS['external_cache_object']->__unset($key);
}

/**
 * Turn off external caching for the rest of this round trip and for all round
 * trips for the next cache timeout.  This function should be called when global arrays
 * are affected (studio, module loader, upgrade wizard, ... ) and it is not ok to
 * wait for the cache to expire in order to see the change.
 */
function sugar_cache_reset()
{
    //@todo implement this in new code
    // Set a flag to clear the code.
    sugar_cache_put('EXTERNAL_CACHE_RESET', true);

    // Clear the local cache
    $GLOBALS['cache_local_store'] = array();

    // Disable the external cache for the rest of the round trip
    $GLOBALS['external_cache_enabled'] = false;

    sugar_clean_opcodes();
}

/**
 * Reset opcode cache if present
 */
function sugar_clean_opcodes()
{
    if($GLOBALS['external_cache_checked'] == false) {
        check_cache();
    }

    if($GLOBALS['external_cache_object']) {
    	$GLOBALS['external_cache_object']->clean_opcodes();
    }
}
