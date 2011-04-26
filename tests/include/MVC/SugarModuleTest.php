<?php
require_once 'include/MVC/SugarModule.php';

class SugarModuleTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $beanList = array();
        $beanFiles = array();
        require('include/modules.php');
        $GLOBALS['beanList'] = $beanList;
        $GLOBALS['beanFiles'] = $beanFiles;
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
        $GLOBALS['current_user']->is_admin = '1';
    }
    
    public function tearDown()
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
        unset($GLOBALS['beanFiles']);
        unset($GLOBALS['beanList']);
    }
    
    public function testLoadBean()
    {
        $this->assertTrue(SugarModule::get('Accounts')->loadBean() instanceOf Account);
    }
    
    public function testLoadBeanInvalidBean()
    {
        $this->assertFalse(SugarModule::get('JohnIsACoolGuy')->loadBean());
    }
    
    public function testModuleImpliments()
    {
        $this->assertTrue(SugarModule::get('Accounts')->moduleImplements('Company'));
    }
    
    public function testModuleImplimentsInvalidBean()
    {
        $this->assertFalse(SugarModule::get('JohnIsACoolGuy')->moduleImplements('Person'));
    }
    
    public function testModuleImplimentsWhenModuleDoesNotImplimentTemplate()
    {
        $this->assertFalse(SugarModule::get('Accounts')->moduleImplements('Person'));
    }
}
