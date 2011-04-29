<?php

require_once('ModuleInstall/ModuleInstaller.php');

class Bug41829Test extends Sugar_PHPUnit_Framework_TestCase 
{   	
    protected $module_installer;
    protected $log;

	public function setUp()
	{
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
        $this->module_installer = new ModuleInstaller();
        $this->module_installer->silent = true;
        $this->module_installer->base_dir = '';
        $this->module_installer->id_name = 'Bug41829Test';
        $this->module_installer->installdefs['dcaction'] = array(
            array(
                'from' => '<basepath>/dcaction_file.php',
            ),
        );
	    $this->log = $GLOBALS['log'];
        $GLOBALS['log'] = new SugarMockLogger();
	}

	public function tearDown()
	{
		SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
        $GLOBALS['log'] = $this->log;
	}

    public function testWarningOnUninstallDCActions()
    {
        $this->module_installer->uninstall_dcactions();

        $this->assertTrue(in_array('DEBUG: Uninstalling DCActions ...'  . str_replace('<basepath>', $this->module_installer->base_dir,  $this->module_installer->installdefs['dcaction'][0]['from']), $GLOBALS['log']->messages));
    }


}
