<?php
require_once 'include/MVC/SugarApplication.php';

class SugarApplicationTest extends Sugar_PHPUnit_Framework_TestCase
{
    private $_app;
    
    public function setUp()
    {
        $startTime = microtime();
        $system_config = new Administration();
        $system_config->retrieveSettings();
        $GLOBALS['system_config'] = $system_config;
        $this->_app = new SugarApplication();
        if ( isset($_SESSION['authenticated_user_theme']) )
            unset($_SESSION['authenticated_user_theme']);
    }
    
    private function _loadUser()
    {
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
        $_SESSION[$GLOBALS['current_user']->user_name.'_PREFERENCES']['global']['gridline'] = 'on';
    }
    
    private function _removeUser() 
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
    }
    
    public function tearDown()
    {
        unset($GLOBALS['current_user']);
        unset($GLOBALS['moduleList']);
        unset($GLOBALS['modInvisListActivities']);
        unset($GLOBALS['request_string']);
        unset($GLOBALS['adminOnlyList']);
        unset($GLOBALS['modListHeader']);
        unset($GLOBALS['modInvisList']);
        unset($GLOBALS['app_strings']);
        unset($GLOBALS['system_config']);
        unset($GLOBALS['app_list_strings']);
        unset($GLOBALS['mod_strings']);
        unset($GLOBALS['theme']);
        unset($GLOBALS['image_path']);
        unset($GLOBALS['starttTime']);
        unset($GLOBALS['sugar_version']);
        unset($GLOBALS['sugar_flavor']);
        $GLOBALS['current_language'] = $GLOBALS['sugar_config']['default_language'];
    }
    
    public function testSetupPrint()
    {
        $_GET['foo'] = 'bar';
        $_POST['dog'] = 'cat';
        $this->_app->setupPrint();
        $this->assertEquals($GLOBALS['request_string'],
            'foo=bar&dog=cat&print=true'
        );
    }
    
    public function testLoadDisplaySettingsDefault()
    {
        $this->_loadUser();
        
        $this->_app->loadDisplaySettings();
        
        $this->assertEquals($GLOBALS['theme'],
            $GLOBALS['sugar_config']['default_theme']);
        
        $this->_removeUser();
    }
    
    public function testLoadDisplaySettingsAuthUserTheme()
    {
        $this->_loadUser();
        
        $_SESSION['authenticated_user_theme'] = 'Sugar';
        
        $this->_app->loadDisplaySettings();
        
        $this->assertEquals($GLOBALS['theme'],
            $_SESSION['authenticated_user_theme']);
        
        $this->_removeUser();
    }
    
    public function testLoadDisplaySettingsUserTheme()
    {
        $this->_loadUser();
        
        $_REQUEST['usertheme'] = 'Sugar5';
        
        $this->_app->loadDisplaySettings();
        
        $this->assertEquals($GLOBALS['theme'],
            $_REQUEST['usertheme']);
        
        $this->_removeUser();
    }
    
    public function testLoadGlobals()
    {
        $this->_app->controller = 
            ControllerFactory::getController($this->_app->default_module);
        $this->_app->loadGlobals();
        
        $this->assertEquals($GLOBALS['currentModule'],$this->_app->default_module);
        $this->assertEquals($_REQUEST['module'],$this->_app->default_module);
        $this->assertEquals($_REQUEST['action'],$this->_app->default_action);
    }
    
    /**
     * @group bug33283
     */
    public function testCheckDatabaseVersion()
    {
        if ( isset($GLOBALS['sugar_db_version']) )
            $old_sugar_db_version = $GLOBALS['sugar_db_version'];
        if ( isset($GLOBALS['sugar_version']) )
            $old_sugar_version = $GLOBALS['sugar_version'];
        include 'sugar_version.php';
        $GLOBALS['sugar_version'] = $sugar_version;
        
        // first test a valid value
        $GLOBALS['sugar_db_version'] = $sugar_db_version;
        $this->assertTrue($this->_app->checkDatabaseVersion(false));
        
        $GLOBALS['sugar_db_version'] = '1.1.1';
        // then test to see if we pull against the cache the valid value
        $this->assertTrue($this->_app->checkDatabaseVersion(false));
        
        // now retest to be sure we actually do the check again
        sugar_cache_put('checkDatabaseVersion_row_count', 0);
        $this->assertFalse($this->_app->checkDatabaseVersion(false));
        
        if ( isset($old_sugar_db_version) )
            $GLOBALS['sugar_db_version'] = $old_sugar_db_version;
        if ( isset($old_sugar_version) )
            $GLOBALS['sugar_version'] = $old_sugar_version;
    }
}
