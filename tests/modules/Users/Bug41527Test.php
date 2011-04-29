<?php

require_once 'modules/Users/User.php';

class Bug41527Test extends Sugar_PHPUnit_Framework_TestCase
{
    public $_default_max_tabs_set = false;
    public $_default_max_tabs = '';
    public $_max_tabs_test = 666;

    public function setUp() 
    {
        $this->_default_max_tabs_set == isset($GLOBALS['sugar_config']['default_max_tabs']);
        if ($this->_default_max_tabs_set) {
            $this->_default_max_tabs = $GLOBALS['sugar_config']['default_max_tabs'];
        }

        $beanList = array();
        $beanFiles = array();
        require('include/modules.php');
        $GLOBALS['beanList'] = $beanList;
        $GLOBALS['beanFiles'] = $beanFiles;
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
        $GLOBALS['current_user']->is_admin = '1';
        $GLOBALS['sugar_config']['default_max_tabs'] = $this->_max_tabs_test;
        $GLOBALS['app_list_strings'] = return_app_list_strings_language($GLOBALS['current_language']);
        $GLOBALS['app_strings'] = return_application_language($GLOBALS['current_language']);
        $GLOBALS['request_string'] = '';
    }

    public function tearDown() 
    {
        if ($this->_default_max_tabs_set) {
            $GLOBALS['sugar_config']['default_max_tabs'] = $this->_default_max_tabs;
        } else {
            unset($GLOBALS['sugar_config']['default_max_tabs']);
        }
        unset($GLOBALS['beanFiles']);
        unset($GLOBALS['beanList']);
        unset($GLOBALS['current_user']);
        unset($GLOBALS['app_list_strings']);
        unset($GLOBALS['app_strings']);
        unset($GLOBALS['request_string']);
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        SugarTestContactUtilities::removeAllCreatedContacts();
    }

    public function testUsingDefaultMaxTabsForOptionsValues() 
    {
        global $current_user, $locale, $sugar_config;
        
        ob_start();
        $_REQUEST['module'] = 'Users';
        require('modules/Users/EditView.php');
        $html = ob_get_clean();

        $this->assertRegExp('/<select name="user_max_tabs".*<option label="' . $this->_max_tabs_test . '" value="' . $this->_max_tabs_test . '".*>' . $this->_max_tabs_test . '<\/option>.*<\/select>/ms', $html);
    }
    
    /**
     * @ticket 42719
     */
    public function testAllowSettingMaxTabsTo10WhenSettingIsLessThan10() 
    {
        global $current_user, $locale, $sugar_config;
        
        $GLOBALS['sugar_config']['default_max_tabs'] = 7;
        
        ob_start();
        $_REQUEST['module'] = 'Users';
        require('modules/Users/EditView.php');
        $html = ob_get_clean();

        $this->assertRegExp('/<select name="user_max_tabs".*<option label="10" value="10".*>10<\/option>.*<\/select>/ms', $html);
    }

    /**
     * @ticket 42719
     */
    public function testUsersDefaultMaxTabsSettingHonored() 
    {
        global $current_user, $locale, $sugar_config;
        
        $current_user->setPreference('max_tabs', 3, 0, 'global');
        
        ob_start();
        $_REQUEST['module'] = 'Users';
        $_REQUEST['record'] = $current_user->id;
        require('modules/Users/EditView.php');
        $html = ob_get_clean();
        
        $this->assertRegExp('/<select name="user_max_tabs".*<option label="3" value="3" selected="selected">3<\/option>.*<\/select>/ms', $html);
    }
}

