<?php
require_once 'modules/Configurator/Configurator.php';

class ConfiguratorTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function testPopulateFromPostConvertsBoolValuesFromStrings()
    {
        $_POST = array(
            'disable_export' => 'true',
            'admin_export_only' => 'false',
            'upload_dir' => 'yummy'
            );
        
    	$cfg = new Configurator();
    	
        $cfg->populateFromPost();
        
        $this->assertEquals($cfg->config['disable_export'], true);
        $this->assertEquals($cfg->config['admin_export_only'], false);
        $this->assertEquals($cfg->config['upload_dir'], 'yummy');
        
        unset($_POST);
    }
}
