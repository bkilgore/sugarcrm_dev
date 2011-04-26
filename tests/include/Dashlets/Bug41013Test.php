<?php
require_once 'include/Dashlets/DashletGeneric.php';

/**
 * @ticket 41013
 */
class Bug41013Test extends Sugar_PHPUnit_Framework_TestCase
{
    protected $_moduleName;
    
    public function setup()
    {
        $this->_moduleName = 'TestModuleForDashletLoadLanguageTest'.mt_rand();
        
        sugar_mkdir("custom/modules/{$this->_moduleName}/metadata/",null,true);
        sugar_file_put_contents("custom/modules/{$this->_moduleName}/metadata/dashletviewdefs.php",
            '<?php $dashletData[\''.$this->_moduleName.'Dashlet\'][\'searchFields\'] = array(); $dashletData[\''.$this->_moduleName.'Dashlet\'][\'columns\'] = array(\'Foo\'); ?>');
        
    }
    
    public function tearDown()
    {
        if ( is_dir("custom/modules/{$this->_moduleName}") )
            rmdir_recursive("custom/modules/{$this->_moduleName}");
        
        unset($GLOBALS['dashletStrings']);
    }
    
    public function testCanLoadCustomMetadataTwiceInARow() 
    {
        $dashlet = new DashletGenericMock();
        $dashlet->seedBean->module_dir = $this->_moduleName;
        
        $dashlet->loadCustomMetadata();
        
        $this->assertEquals(array('Foo'),$dashlet->columns);
        
        $dashlet->columns = array();
        
        $dashlet->loadCustomMetadata();
        
        $this->assertEquals(array('Foo'),$dashlet->columns);
    }
}

class DashletGenericMock extends DashletGeneric
{
    public function __construct()
    {
    }
    
    public function loadCustomMetadata()
    {
        parent::loadCustomMetadata();
    }
}
