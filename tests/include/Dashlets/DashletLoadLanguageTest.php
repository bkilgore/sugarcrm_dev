<?php
require_once 'include/Dashlets/Dashlet.php';

class DashletLoadLanguageTest extends Sugar_PHPUnit_Framework_TestCase
{
    private $_moduleName;
    
    public function setup()
    {
        $GLOBALS['dashletStrings'] = array();
        $this->_moduleName = 'TestModuleForDashletLoadLanguageTest'.mt_rand();
    }
    
    public function tearDown()
    {
        if ( is_dir("modules/{$this->_moduleName}") )
            rmdir_recursive("modules/{$this->_moduleName}");
        if ( is_dir("custom/modules/{$this->_moduleName}") )
            rmdir_recursive("custom/modules/{$this->_moduleName}");
        
        unset($GLOBALS['dashletStrings']);
        $GLOBALS['current_language'] = $GLOBALS['sugar_config']['default_language'];
    }
    
    public function testCanLoadCurrentLanguageAppStrings() 
    {
        $GLOBALS['current_language'] = 'en_us';
        sugar_mkdir("modules/{$this->_moduleName}/Dashlets/TestModuleDashlet/",null,true);
        sugar_file_put_contents("modules/{$this->_moduleName}/Dashlets/TestModuleDashlet/TestModuleDashlet.en_us.lang.php",
            '<?php $dashletStrings["TestModuleDashlet"]["foo"] = "bar"; ?>');
        
        $dashlet = new Dashlet(0);
        $dashlet->loadLanguage('TestModuleDashlet',"modules/{$this->_moduleName}/Dashlets/");
        
        $this->assertEquals("bar",$dashlet->dashletStrings["foo"]);
    }
    
    public function testCanLoadCustomLanguageAppStrings() 
    {
        $GLOBALS['current_language'] = 'en_us';
        sugar_mkdir("modules/{$this->_moduleName}/Dashlets/TestModuleDashlet/",null,true);
        sugar_file_put_contents("modules/{$this->_moduleName}/Dashlets/TestModuleDashlet/TestModuleDashlet.en_us.lang.php",
            '<?php $dashletStrings["TestModuleDashlet"]["foo"] = "bar"; ?>');
        create_custom_directory("modules/{$this->_moduleName}/Dashlets/TestModuleDashlet/");
        sugar_file_put_contents("custom/modules/{$this->_moduleName}/Dashlets/TestModuleDashlet/TestModuleDashlet.en_us.lang.php",
            '<?php $dashletStrings["TestModuleDashlet"]["foo"] = "barbar"; ?>');
        
        $dashlet = new Dashlet(0);
        $dashlet->loadLanguage('TestModuleDashlet',"modules/{$this->_moduleName}/Dashlets/");
        
        $this->assertEquals("barbar",$dashlet->dashletStrings["foo"]);
    }
    
    public function testCanLoadCustomLanguageAppStringsWhenThereIsNoNoncustomLanguageFile() 
    {
        $GLOBALS['current_language'] = 'en_us';
        create_custom_directory("modules/{$this->_moduleName}/Dashlets/TestModuleDashlet/");
        sugar_file_put_contents("custom/modules/{$this->_moduleName}/Dashlets/TestModuleDashlet/TestModuleDashlet.en_us.lang.php",
            '<?php $dashletStrings["TestModuleDashlet"]["foo"] = "barbar"; ?>');
        
        $dashlet = new Dashlet(0);
        $dashlet->loadLanguage('TestModuleDashlet',"modules/{$this->_moduleName}/Dashlets/");
        
        $this->assertEquals("barbar",$dashlet->dashletStrings["foo"]);
    }
    
    public function testCanLoadCurrentLanguageAppStringsWhenNotEnglish() 
    {
        $GLOBALS['current_language'] = 'FR_fr';
        sugar_mkdir("modules/{$this->_moduleName}/Dashlets/TestModuleDashlet/",null,true);
        sugar_file_put_contents("modules/{$this->_moduleName}/Dashlets/TestModuleDashlet/TestModuleDashlet.en_us.lang.php",
            '<?php $dashletStrings["TestModuleDashlet"]["foo"] = "bar"; ?>');
        sugar_mkdir("modules/{$this->_moduleName}/Dashlets/TestModuleDashlet/",null,true);
        sugar_file_put_contents("modules/{$this->_moduleName}/Dashlets/TestModuleDashlet/TestModuleDashlet.FR_fr.lang.php",
            '<?php $dashletStrings["TestModuleDashlet"]["foo"] = "barrie"; ?>');
        
        $dashlet = new Dashlet(0);
        $dashlet->loadLanguage('TestModuleDashlet',"modules/{$this->_moduleName}/Dashlets/");
        
        $this->assertEquals("barrie",$dashlet->dashletStrings["foo"]);
    }
    
    public function testCanLoadEnglishLanguageAppStringsWhenCurrentLanguageDoesNotExist() 
    {
        $GLOBALS['current_language'] = 'FR_fr';
        sugar_mkdir("modules/{$this->_moduleName}/Dashlets/TestModuleDashlet/",null,true);
        sugar_file_put_contents("modules/{$this->_moduleName}/Dashlets/TestModuleDashlet/TestModuleDashlet.en_us.lang.php",
            '<?php $dashletStrings["TestModuleDashlet"]["foo"] = "bar"; ?>');
        
        $dashlet = new Dashlet(0);
        $dashlet->loadLanguage('TestModuleDashlet',"modules/{$this->_moduleName}/Dashlets/");
        
        $this->assertEquals("bar",$dashlet->dashletStrings["foo"]);
    }
    
    public function testCanLoadCustomEnglishLanguageAppStringsWhenCurrentLanguageDoesNotExist() 
    {
        $GLOBALS['current_language'] = 'FR_fr';
        sugar_mkdir("modules/{$this->_moduleName}/Dashlets/TestModuleDashlet/",null,true);
        sugar_file_put_contents("modules/{$this->_moduleName}/Dashlets/TestModuleDashlet/TestModuleDashlet.en_us.lang.php",
            '<?php $dashletStrings["TestModuleDashlet"]["foo"] = "bar"; ?>');
        create_custom_directory("modules/{$this->_moduleName}/Dashlets/TestModuleDashlet/");
        sugar_file_put_contents("custom/modules/{$this->_moduleName}/Dashlets/TestModuleDashlet/TestModuleDashlet.en_us.lang.php",
            '<?php $dashletStrings["TestModuleDashlet"]["foo"] = "barbarbar"; ?>');
        
        $dashlet = new Dashlet(0);
        $dashlet->loadLanguage('TestModuleDashlet',"modules/{$this->_moduleName}/Dashlets/");
        
        $this->assertEquals("barbarbar",$dashlet->dashletStrings["foo"]);
    }
}
