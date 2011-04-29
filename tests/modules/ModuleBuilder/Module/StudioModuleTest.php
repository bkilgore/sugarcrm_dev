<?php

require_once("modules/ModuleBuilder/Module/StudioModule.php");

class StudioModuleTest extends Sugar_PHPUnit_Framework_TestCase
{
	public function setUp()
    {
        $beanList = array();
        $beanFiles = array();
        require('include/modules.php');
        $GLOBALS['beanList'] = $beanList;
        $GLOBALS['beanFiles'] = $beanFiles;
        $GLOBALS['app_list_strings'] = return_app_list_strings_language($GLOBALS['current_language']);

    }
    
    public function tearDown() 
    {
        unset($GLOBALS['beanFiles']);
        unset($GLOBALS['beanList']);
        unset($GLOBALS['app_list_strings']);
    }

    /**
     * @ticket 39407
     */
    public function testRemoveFieldFromLayoutsDocumentsException()
    {
    	$SM = new StudioModule("Documents");
        try {
            $SM->removeFieldFromLayouts("aFieldThatDoesntExist");
            $this->assertTrue(true);
        } catch (Exception $e)
        {
            $this->assertTrue(false, "Studio module threw exception :" . $e->getMessage());
        }
    }
}