<?php
require_once 'include/MVC/Controller/SugarController.php';

class SugarControllerTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
    }
    
    public function tearDown()
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
    }
    
    public function testCallLegacyCodeIfLegacyListViewFound()
    {
        $module_name = 'TestModule'.mt_rand();
        sugar_mkdir("modules/$module_name/",null,true);
        sugar_touch("modules/$module_name/ListView.php");
        
        $controller = new SugarControllerMock;
        $controller->setup($module_name);
        $controller->do_action = 'ListView';
        $controller->view = 'list';
        $controller->callLegacyCode();
        
        $this->assertEquals('classic',$controller->view);
        
        rmdir_recursive("modules/$module_name");
    }
    
    public function testCallLegacyCodeIfNewListViewFound()
    {
        $module_name = 'TestModule'.mt_rand();
        sugar_mkdir("modules/$module_name/views",null,true);
        sugar_touch("modules/$module_name/views/view.list.php");
        
        $controller = new SugarControllerMock;
        $controller->setup($module_name);
        $controller->do_action = 'ListView';
        $controller->view = 'list';
        $controller->callLegacyCode();
        
        $this->assertEquals('list',$controller->view);
        
        rmdir_recursive("modules/$module_name");
    }
    
    /**
     * @ticket 41755
     */
    public function testCallLegacyCodeIfLegacyListViewAndNewListViewFound()
    {
        $module_name = 'TestModule'.mt_rand();
        sugar_mkdir("modules/$module_name/views",null,true);
        sugar_touch("modules/$module_name/views/view.list.php");
        sugar_touch("modules/$module_name/ListView.php");
        
        $controller = new SugarControllerMock;
        $controller->setup($module_name);
        $controller->do_action = 'ListView';
        $controller->view = 'list';
        $controller->callLegacyCode();
        
        $this->assertEquals('list',$controller->view);
        
        rmdir_recursive("modules/$module_name");
    }
    
    public function testCallLegacyCodeIfCustomLegacyListViewAndNewListViewFound()
    {
        $module_name = 'TestModule'.mt_rand();
        sugar_mkdir("modules/$module_name/views",null,true);
        sugar_touch("modules/$module_name/views/view.list.php");
        sugar_mkdir("custom/modules/$module_name",null,true);
        sugar_touch("custom/modules/$module_name/ListView.php");
        
        $controller = new SugarControllerMock;
        $controller->setup($module_name);
        $controller->do_action = 'ListView';
        $controller->view = 'list';
        $controller->callLegacyCode();
        
        $this->assertEquals('classic',$controller->view);
        
        rmdir_recursive("modules/$module_name");
    }
    
    public function testCallLegacyCodeIfLegacyListViewAndCustomNewListViewFound()
    {
        $module_name = 'TestModule'.mt_rand();
        sugar_mkdir("custom/modules/$module_name/views",null,true);
        sugar_touch("custom/modules/$module_name/views/view.list.php");
        sugar_mkdir("modules/$module_name",null,true);
        sugar_touch("modules/$module_name/ListView.php");
        
        $controller = new SugarControllerMock;
        $controller->setup($module_name);
        $controller->do_action = 'ListView';
        $controller->view = 'list';
        $controller->callLegacyCode();
        
        $this->assertEquals('classic',$controller->view);
        
        rmdir_recursive("modules/$module_name");
    }
    
    public function testCallLegacyCodeIfLegacyListViewAndNewListViewFoundAndCustomLegacyListViewFound()
    {
        $module_name = 'TestModule'.mt_rand();
        sugar_mkdir("modules/$module_name/views",null,true);
        sugar_touch("modules/$module_name/views/view.list.php");
        sugar_touch("modules/$module_name/ListView.php");
        sugar_mkdir("custom/modules/$module_name",null,true);
        sugar_touch("custom/modules/$module_name/ListView.php");
        
        $controller = new SugarControllerMock;
        $controller->setup($module_name);
        $controller->do_action = 'ListView';
        $controller->view = 'list';
        $controller->callLegacyCode();
        
        $this->assertEquals('classic',$controller->view);
        
        rmdir_recursive("modules/$module_name");
    }
    
    public function testCallLegacyCodeIfLegacyListViewAndNewListViewFoundAndCustomNewListViewFound()
    {
        $module_name = 'TestModule'.mt_rand();
        sugar_mkdir("custom/modules/$module_name/views",null,true);
        sugar_touch("custom/modules/$module_name/views/view.list.php");
        sugar_mkdir("modules/$module_name/views",null,true);
        sugar_touch("modules/$module_name/views/view.list.php");
        sugar_touch("modules/$module_name/ListView.php");
        
        $controller = new SugarControllerMock;
        $controller->setup($module_name);
        $controller->do_action = 'ListView';
        $controller->view = 'list';
        $controller->callLegacyCode();
        
        $this->assertEquals('list',$controller->view);
        
        rmdir_recursive("modules/$module_name");
    }
    
    public function testCallLegacyCodeIfLegacyListViewAndNewListViewFoundAndCustomLegacyListViewFoundAndCustomNewListViewFound()
    {
        $module_name = 'TestModule'.mt_rand();
        sugar_mkdir("custom/modules/$module_name/views",null,true);
        sugar_touch("custom/modules/$module_name/views/view.list.php");
        sugar_touch("custom/modules/$module_name/ListView.php");
        sugar_mkdir("modules/$module_name/views",null,true);
        sugar_touch("modules/$module_name/views/view.list.php");
        sugar_touch("modules/$module_name/ListView.php");
        
        $controller = new SugarControllerMock;
        $controller->setup($module_name);
        $controller->do_action = 'ListView';
        $controller->view = 'list';
        $controller->callLegacyCode();
        
        $this->assertEquals('list',$controller->view);
        
        rmdir_recursive("modules/$module_name");
    }
}

class SugarControllerMock extends SugarController
{
    public $do_action;
    
    public function callLegacyCode()
    {
        return parent::callLegacyCode();
    }
}
