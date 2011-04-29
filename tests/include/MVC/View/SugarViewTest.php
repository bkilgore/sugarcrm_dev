<?php
require_once 'include/MVC/View/SugarView.php';

class SugarViewTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->_view = new SugarViewTestMock();
        $GLOBALS['app_strings'] = return_application_language($GLOBALS['current_language']);
        $GLOBALS['mod_strings'] = return_module_language($GLOBALS['current_language'], 'Users');
    }
    
    public function tearDown()
    {
    	unset($GLOBALS['mod_strings']);
    	unset($GLOBALS['app_strings']);
    }
    
    public function testGetModuleTab()
    {
        $_REQUEST['module_tab'] = 'ADMIN';
        $moduleTab = $this->_view->getModuleTab();
        $this->assertEquals('ADMIN', $moduleTab, 'Module Tab names are not equal from request');
    }

    public function testGetMetaDataFile()
    {
        $this->_view->module = 'Contacts';
        $this->_view->type = 'list';
        $metaDataFile = $this->_view->getMetaDataFile();
        $this->assertEquals('modules/Contacts/metadata/listviewdefs.php', $metaDataFile, 'Did not load the correct metadata file');

        //test custom file
        sugar_mkdir('custom/modules/Contacts/metadata/', null, true);
        $customFile = 'custom/modules/Contacts/metadata/listviewdefs.php';
        if(!file_exists($customFile))
        {
            sugar_file_put_contents($customFile, array());
            $customMetaDataFile = $this->_view->getMetaDataFile();
            $this->assertEquals($customFile, $customMetaDataFile, 'Did not load the correct custom metadata file');
            unlink($customFile);
        }
    }
    
    public function testInit()
    {
        $bean = new SugarBean;
        $view_object_map = array('foo'=>'bar');
        $GLOBALS['action'] = 'barbar';
        $GLOBALS['module'] = 'foofoo';
        
        $this->_view->init($bean,$view_object_map);
        
        $this->assertInstanceOf('SugarBean',$this->_view->bean);
        $this->assertEquals($view_object_map,$this->_view->view_object_map);
        $this->assertEquals($GLOBALS['action'],$this->_view->action);
        $this->assertEquals($GLOBALS['module'],$this->_view->module);
        $this->assertInstanceOf('Sugar_Smarty',$this->_view->ss);
    }
    
    public function testInitNoParameters()
    {
        $GLOBALS['action'] = 'barbar';
        $GLOBALS['module'] = 'foofoo';
        
        $this->_view->init();
        
        $this->assertNull($this->_view->bean);
        $this->assertEquals(array(),$this->_view->view_object_map);
        $this->assertEquals($GLOBALS['action'],$this->_view->action);
        $this->assertEquals($GLOBALS['module'],$this->_view->module);
        $this->assertInstanceOf('Sugar_Smarty',$this->_view->ss);
    }
    
    public function testInitSmarty()
    {
        $this->_view->initSmarty();
        
        $this->assertInstanceOf('Sugar_Smarty',$this->_view->ss);
        $this->assertEquals($this->_view->ss->get_template_vars('MOD'),$GLOBALS['mod_strings']);
        $this->assertEquals($this->_view->ss->get_template_vars('APP'),$GLOBALS['app_strings']);
    }
    
    /**
     * @outputBuffering enabled
     */
    public function testDisplayErrors()
    {
        $this->_view->errors = array('error1','error2');
        $this->_view->suppressDisplayErrors = true;
        
        $this->assertEquals(
            '<span class="error">error1</span><br><span class="error">error2</span><br>',
            $this->_view->displayErrors()
            );
    }
    
    /**
     * @outputBuffering enabled
     */
    public function testDisplayErrorsDoNotSupressOutput()
    {
        $this->_view->errors = array('error1','error2');
        $this->_view->suppressDisplayErrors = false;
        
        $this->assertEmpty($this->_view->displayErrors());
    }
    
    public function testGetBrowserTitle()
    {
        $viewMock = $this->getMock('SugarViewTestMock',array('_getModuleTitleParams'));
        $viewMock->expects($this->any())
                 ->method('_getModuleTitleParams')
                 ->will($this->returnValue(array('foo','bar')));
        
        $this->assertEquals(
            "bar &raquo; foo &raquo; {$GLOBALS['app_strings']['LBL_BROWSER_TITLE']}",
            $viewMock->getBrowserTitle()
            );
    }
    
    public function testGetBrowserTitleUserLogin()
    {
        $this->_view->module = 'Users';
        $this->_view->action = 'Login';
        
        $this->assertEquals(
            "{$GLOBALS['app_strings']['LBL_BROWSER_TITLE']}",
            $this->_view->getBrowserTitle()
            );
    }
    
    public function testGetBreadCrumbSymbolForLTRTheme()
    {
        $theme = SugarTestThemeUtilities::createAnonymousTheme();
        SugarThemeRegistry::set($theme);
        
        $this->assertEquals(
            "<span class='pointer'>&raquo;</span>",
            $this->_view->getBreadCrumbSymbol()
            );
    }
    
    public function testGetBreadCrumbSymbolForRTLTheme()
    {
        $theme = SugarTestThemeUtilities::createAnonymousRTLTheme();
        SugarThemeRegistry::set($theme);
        
        $this->assertEquals(
            "<span class='pointer'>&laquo;</span>",
            $this->_view->getBreadCrumbSymbol()
            );
    }
}

class SugarViewTestMock extends SugarView
{
    public function getModuleTab()
    {
        return parent::_getModuleTab();
    }
    
    public function initSmarty()
    {
        return parent::_initSmarty();
    }
}
