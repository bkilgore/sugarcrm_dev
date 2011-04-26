<?php
require_once 'include/SugarTheme/SugarTheme.php';
require_once 'include/dir_inc.php';

class SugarThemeRegistryTest extends Sugar_PHPUnit_Framework_TestCase
{
    private $_themeName;
    
    public function setup()
    {
        $this->_themeName = SugarTestThemeUtilities::createAnonymousTheme();
        
        SugarThemeRegistry::buildRegistry();
    }
    
    public function tearDown()
    {
        SugarTestThemeUtilities::removeAllCreatedAnonymousThemes();
    }
    
    public function testThemesRegistered()
    {
        $this->assertTrue(SugarThemeRegistry::exists($this->_themeName));
    }
    
    public function testGetThemeObject()
    {
        $object = SugarThemeRegistry::get($this->_themeName);
        
        $this->assertType('SugarTheme',$object);
        $this->assertEquals($object->__toString(),$this->_themeName);
    }
    
    public function testSetCurrentTheme()
    {
        SugarThemeRegistry::set($this->_themeName);
        
        $this->assertType('SugarTheme',SugarThemeRegistry::current());
        $this->assertEquals(SugarThemeRegistry::current()->__toString(),$this->_themeName);
    }
    
    public function testInListOfAvailableThemes()
    {
        if ( isset($GLOBALS['sugar_config']['disabled_themes']) ) {
            $disabled_themes = $GLOBALS['sugar_config']['disabled_themes'];
            unset($GLOBALS['sugar_config']['disabled_themes']);
        }
        
        $themes = SugarThemeRegistry::availableThemes();
        $this->assertTrue(isset($themes[$this->_themeName]));
        $themes = SugarThemeRegistry::unAvailableThemes();
        $this->assertTrue(!isset($themes[$this->_themeName]));
        $themes = SugarThemeRegistry::allThemes();
        $this->assertTrue(isset($themes[$this->_themeName]));
        
        if ( isset($disabled_themes) )
            $GLOBALS['sugar_config']['disabled_themes'] = $disabled_themes;
    }
    
    public function testDisabledThemeNotInListOfAvailableThemes()
    {
        if ( isset($GLOBALS['sugar_config']['disabled_themes']) ) {
            $disabled_themes = $GLOBALS['sugar_config']['disabled_themes'];
            unset($GLOBALS['sugar_config']['disabled_themes']);
        }
        
        $GLOBALS['sugar_config']['disabled_themes'] = $this->_themeName;
        
        $themes = SugarThemeRegistry::availableThemes();
        $this->assertTrue(!isset($themes[$this->_themeName]));
        $themes = SugarThemeRegistry::unAvailableThemes();
        $this->assertTrue(isset($themes[$this->_themeName]));
        $themes = SugarThemeRegistry::allThemes();
        $this->assertTrue(isset($themes[$this->_themeName]));
        
        if ( isset($disabled_themes) )
            $GLOBALS['sugar_config']['disabled_themes'] = $disabled_themes;
    }
    
    public function testCustomThemeLoaded()
    {
        $customTheme = SugarTestThemeUtilities::createAnonymousCustomTheme($this->_themeName);
        
        SugarThemeRegistry::buildRegistry();
        
        $this->assertEquals(
            SugarThemeRegistry::get($customTheme)->name,
            'custom ' . $customTheme
            );
    }
    
    public function testDefaultThemedefFileHandled()
    {
        create_custom_directory('themes/default/');
        sugar_file_put_contents('custom/themes/default/themedef.php','<?php $themedef = array("group_tabs" => false);');
        
        SugarThemeRegistry::buildRegistry();
        
        $this->assertEquals(
            SugarThemeRegistry::get($this->_themeName)->group_tabs,
            false
            );
        
        unlink('custom/themes/default/themedef.php');
    }
    
    public function testClearCacheAllThemes()
    {
        SugarThemeRegistry::get($this->_themeName)->getCSSURL('style.css');
        $this->assertTrue(isset(SugarThemeRegistry::get($this->_themeName)->_cssCache['style.css']),
                            'File style.css should exist in cache');
        
        SugarThemeRegistry::clearAllCaches();
        SugarThemeRegistry::buildRegistry();
        
        $this->assertFalse(isset(SugarThemeRegistry::get($this->_themeName)->_cssCache['style.css']),
                            'File style.css shouldn\'t exist in cache');
    }
    
    /**
     * @group bug35307
     */
    public function testOldThemeIsNotRecognized()
    {
        $themename = SugarTestThemeUtilities::createAnonymousOldTheme();
        
        $this->assertNull(SugarThemeRegistry::get($themename));
    }
}
