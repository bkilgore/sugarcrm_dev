<?php

class SugarTestThemeUtilitiesTest extends Sugar_PHPUnit_Framework_TestCase
{
    private $_before_snapshot = array();
    
    public function tearDown() 
    {
        SugarTestThemeUtilities::removeAllCreatedAnonymousThemes();
    }

    public function testCanCreateAnAnonymousTheme() 
    {
        $themename = SugarTestThemeUtilities::createAnonymousTheme();

        $this->assertTrue(is_dir("themes/$themename"));
        $this->assertTrue(is_file("themes/$themename/themedef.php"));
    }

    public function testCanCreateAnAnonymousCustomTheme() 
    {
        $themename = SugarTestThemeUtilities::createAnonymousCustomTheme();

        $this->assertTrue(is_dir("custom/themes/$themename"));
        $this->assertTrue(is_file("custom/themes/$themename/themedef.php"));
        
        $themename = 'MyCustomTestTheme'.date("YmdHis");
        SugarTestThemeUtilities::createAnonymousCustomTheme($themename);
        
        $this->assertTrue(is_dir("custom/themes/$themename"));
        $this->assertTrue(is_file("custom/themes/$themename/themedef.php"));
    }
    
    public function testCanCreateAnAnonymousChildTheme() 
    {
        $themename = SugarTestThemeUtilities::createAnonymousTheme();
        $childtheme = SugarTestThemeUtilities::createAnonymousChildTheme($themename);

        $this->assertTrue(is_dir("themes/$childtheme"));
        $this->assertTrue(is_file("themes/$childtheme/themedef.php"));
        
        $themedef = array();
        include("themes/$childtheme/themedef.php");
        
        $this->assertEquals($themedef['parentTheme'],$themename);
    }
    
    public function testCanCreateAnAnonymousRTLTheme() 
    {
        $themename = SugarTestThemeUtilities::createAnonymousRTLTheme();

        $this->assertTrue(is_dir("themes/$themename"));
        $this->assertTrue(is_file("themes/$themename/themedef.php"));
        
        $themedef = array();
        include("themes/$themename/themedef.php");
        
        $this->assertEquals($themedef['directionality'],'rtl');
    }

    public function testCanTearDownAllCreatedAnonymousThemes() 
    {
        $themesCreated = array();
        
        for ($i = 0; $i < 5; $i++) 
            $themesCreated[] = SugarTestThemeUtilities::createAnonymousTheme();

        SugarTestThemeUtilities::removeAllCreatedAnonymousThemes();
        
        foreach ( $themesCreated as $themename )
            $this->assertFalse(is_dir("themes/$themename"));
    }
}

