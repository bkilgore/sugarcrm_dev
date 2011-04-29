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

    public function testSetup()
    {
        $controller = new SugarControllerMock;
        $controller->setup();
        
        $this->assertEquals('Home',$controller->module);
        $this->assertNull($controller->target_module);
    }
    
    public function testSetupSpecifyModule()
    {
        $controller = new SugarControllerMock;
        $controller->setup('foo');
        
        $this->assertEquals('foo',$controller->module);
        $this->assertNull($controller->target_module);
    }
    
    public function testSetupUseRequestVars()
    {
        $_REQUEST = array(
            'module' => 'dog33434',
            'target_module' => 'dog121255',
            'action' => 'dog3232',
            'record' => 'dog5656',
            'view' => 'dog4343',
            'return_module' => 'dog1312',
            'return_action' => 'dog1212',
            'return_id' => '11212',
            );
        $controller = new SugarControllerMock;
        $controller->setup();
        
        $this->assertEquals($_REQUEST['module'],$controller->module);
        $this->assertEquals($_REQUEST['target_module'],$controller->target_module);
        $this->assertEquals($_REQUEST['action'],$controller->action);
        $this->assertEquals($_REQUEST['record'],$controller->record);
        $this->assertEquals($_REQUEST['view'],$controller->view);
        $this->assertEquals($_REQUEST['return_module'],$controller->return_module);
        $this->assertEquals($_REQUEST['return_action'],$controller->return_action);
        $this->assertEquals($_REQUEST['return_id'],$controller->return_id);
    }
    
    public function testSetModule()
    {
        $controller = new SugarControllerMock;
        $controller->setModule('cat');
        
        $this->assertEquals('cat',$controller->module);
    }
    
    public function testLoadBean()
    {
        
    }
    
    public function testCallLegacyCodeIfLegacyDetailViewFound()
    {
        $module_name = 'TestModule'.mt_rand();
        sugar_mkdir("modules/$module_name/",null,true);
        sugar_touch("modules/$module_name/DetailView.php");
        
        $controller = new SugarControllerMock;
        $controller->setup($module_name);
        $controller->do_action = 'DetailView';
        $controller->view = 'list';
        $controller->callLegacyCode();
        
        $this->assertEquals('classic',$controller->view);
        
        rmdir_recursive("modules/$module_name");
    }

    public function testCallLegacyCodeIfNewDetailViewFound()
    {
        $module_name = 'TestModule'.mt_rand();
        sugar_mkdir("modules/$module_name/views",null,true);
        sugar_touch("modules/$module_name/views/view.detail.php");
        
        $controller = new SugarControllerMock;
        $controller->setup($module_name);
        $controller->do_action = 'DetailView';

        $controller->view = 'list';
        $controller->callLegacyCode();
        
        $this->assertEquals('list',$controller->view);
        
        rmdir_recursive("modules/$module_name");
    }


    public function testCallLegacyCodeIfLegacyDetailViewAndNewDetailViewFound()
    {
        $module_name = 'TestModule'.mt_rand();
        sugar_mkdir("modules/$module_name/views",null,true);
        sugar_touch("modules/$module_name/views/view.detail.php");
        sugar_touch("modules/$module_name/DetailView.php");
        
        $controller = new SugarControllerMock;
        $controller->setup($module_name);
        $controller->do_action = 'DetailView';

        $controller->view = 'list';
        $controller->callLegacyCode();
        
        $this->assertEquals('list',$controller->view);
        
        rmdir_recursive("modules/$module_name");
    }

    public function testCallLegacyCodeIfCustomLegacyDetailViewAndNewDetailViewFound()
    {
        $module_name = 'TestModule'.mt_rand();
        sugar_mkdir("modules/$module_name/views",null,true);
        sugar_touch("modules/$module_name/views/view.detail.php");
        sugar_mkdir("custom/modules/$module_name",null,true);
        sugar_touch("custom/modules/$module_name/DetailView.php");
        
        $controller = new SugarControllerMock;
        $controller->setup($module_name);
        $controller->do_action = 'DetailView';

        $controller->view = 'list';
        $controller->callLegacyCode();
        
        $this->assertEquals('classic',$controller->view);
        
        rmdir_recursive("modules/$module_name");
    }

    public function testCallLegacyCodeIfLegacyDetailViewAndCustomNewDetailViewFound()
    {
        $module_name = 'TestModule'.mt_rand();
        sugar_mkdir("custom/modules/$module_name/views",null,true);
        sugar_touch("custom/modules/$module_name/views/view.detail.php");
        sugar_mkdir("modules/$module_name",null,true);
        sugar_touch("modules/$module_name/DetailView.php");
        
        $controller = new SugarControllerMock;
        $controller->setup($module_name);
        $controller->do_action = 'DetailView';
        $controller->view = 'list';
        $controller->callLegacyCode();
        
        $this->assertEquals('classic',$controller->view);
        
        rmdir_recursive("modules/$module_name");
    }

    public function testCallLegacyCodeIfLegacyDetailViewAndNewDetailViewFoundAndCustomLegacyDetailViewFound()
    {
        $module_name = 'TestModule'.mt_rand();
        sugar_mkdir("modules/$module_name/views",null,true);
        sugar_touch("modules/$module_name/views/view.detail.php");
        sugar_touch("modules/$module_name/DetailView.php");
        sugar_mkdir("custom/modules/$module_name",null,true);
        sugar_touch("custom/modules/$module_name/DetailView.php");
        
        $controller = new SugarControllerMock;
        $controller->setup($module_name);
        $controller->do_action = 'DetailView';

        $controller->view = 'list';
        $controller->callLegacyCode();
        
        $this->assertEquals('classic',$controller->view);
        
        rmdir_recursive("modules/$module_name");
    }

    public function testCallLegacyCodeIfLegacyDetailViewAndNewDetailViewFoundAndCustomNewDetailViewFound()
    {
        $module_name = 'TestModule'.mt_rand();
        sugar_mkdir("custom/modules/$module_name/views",null,true);
        sugar_touch("custom/modules/$module_name/views/view.detail.php");
        sugar_mkdir("modules/$module_name/views",null,true);
        sugar_touch("modules/$module_name/views/view.detail.php");
        sugar_touch("modules/$module_name/DetailView.php");
        
        $controller = new SugarControllerMock;
        $controller->setup($module_name);
        $controller->do_action = 'DetailView';
        $controller->view = 'list';
        $controller->callLegacyCode();
        
        $this->assertEquals('list',$controller->view);
        
        rmdir_recursive("modules/$module_name");
    }

    public function testCallLegacyCodeIfLegacyDetailViewAndNewDetailViewFoundAndCustomLegacyDetailViewFoundAndCustomNewDetailViewFound()
    {
        $module_name = 'TestModule'.mt_rand();
        sugar_mkdir("custom/modules/$module_name/views",null,true);
        sugar_touch("custom/modules/$module_name/views/view.detail.php");
        sugar_touch("custom/modules/$module_name/DetailView.php");
        sugar_mkdir("modules/$module_name/views",null,true);
        sugar_touch("modules/$module_name/views/view.detail.php");
        sugar_touch("modules/$module_name/DetailView.php");
        
        $controller = new SugarControllerMock;
        $controller->setup($module_name);
        $controller->do_action = 'DetailView';

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
