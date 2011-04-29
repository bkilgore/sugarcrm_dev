<?php

class SugarTestLangPackCreator
{
    public function __construct()
    {
    }
    
    public function __destruct()
    {
        $this->clearLangCache();
    }
    
    /**
     * Set a string for the app_strings array
     *
     * @param $key   string
     * @param $value string
     */
    public function setAppString(
        $key,
        $value
        )
    {
        $this->_strings['app_strings'][$key] = $value;
    }
    
    /**
     * Set a string for the app_list_strings array
     *
     * @param $key   string
     * @param $value string
     */
    public function setAppListString(
        $key,
        $value
        )
    {
        $this->_strings['app_list_strings'][$key] = $value;
    }
    
    /**
     * Set a string for the mod_strings array
     *
     * @param $key    string
     * @param $value  string
     * @param $module string
     */
    public function setModString(
        $key,
        $value,
        $module
        )
    {
        $this->_strings['mod_strings'][$module][$key] = $value;
    }
    
    /**
     * Saves the created strings
     *
     * Here, we cheat the system by storing our string overrides in the sugar_cache where
     * we normally stored the cached language strings.
     */
    public function save()
    {
        $language = $GLOBALS['current_language'];
        if ( isset($this->_strings['app_strings']) ) {
            $cache_key = 'app_strings.'.$language;
            $app_strings = sugar_cache_retrieve($cache_key);
            if ( empty($app_strings) )
                $app_strings = return_application_language($language);
            foreach ( $this->_strings['app_strings'] as $key => $value )
                $app_strings[$key] = $value;
            sugar_cache_put($cache_key, $app_strings);
            $GLOBALS['app_strings'] = $app_strings;
        }
        
        if ( isset($this->_strings['app_list_strings']) ) {
            $cache_key = 'app_list_strings.'.$language;
            $app_list_strings = sugar_cache_retrieve($cache_key);
            if ( empty($app_list_strings) )
                $app_list_strings = return_app_list_strings_language($language);
            foreach ( $this->_strings['app_list_strings'] as $key => $value )
                $app_list_strings[$key] = $value;
            sugar_cache_put($cache_key, $app_list_strings);
            $GLOBALS['app_list_strings'] = $app_list_strings;
        }
        
        if ( isset($this->_strings['mod_strings']) ) {
            foreach ( $this->_strings['mod_strings'] as $module => $strings ) {
                $cache_key = "mod_strings_lang.".$language.$module;;
                $mod_strings = sugar_cache_retrieve($cache_key);
                if ( empty($mod_strings) )
                    $mod_strings = return_module_language($language, $module);
                foreach ( $strings as $key => $value )
                    $mod_strings[$key] = $value;
                sugar_cache_put($cache_key, $mod_strings);
                $GLOBALS['mod_strings'] = $mod_strings;
            }
        }
    }
    
    /**
     * Clear the language string cache in sugar_cache, which will get rid of our
     * language file overrides.
     */
    protected function clearLangCache()
    {
        $language = $GLOBALS['current_language'];
        
        if ( isset($this->_strings['app_strings']) ) {
            $cache_key = 'app_strings.'.$language;
            sugar_cache_clear($cache_key);
        }
        
        if ( isset($this->_strings['app_list_strings']) ) {
            $cache_key = 'app_list_strings.'.$language;
            sugar_cache_clear($cache_key);
        }
        
        if ( isset($this->_strings['mod_strings']) ) {
            foreach ( $this->_strings['mod_strings'] as $module => $strings ) {
                $cache_key = "mod_strings_lang.".$language.$module;;
                sugar_cache_clear($cache_key);
            }
        }
    }
}
