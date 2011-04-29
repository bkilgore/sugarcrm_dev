<?php
require_once('include/Smarty/plugins/function.multienum_to_array.php');
require_once 'include/Sugar_Smarty.php';

class FunctionMultienumToArrayTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->_smarty = new Sugar_Smarty;
    }
    
    public function providerPassedString()
    {
        return array(
            array("Employee^,^Boss","Cold Call",array('Employee','Boss')),
            array("^Employee^,^Boss^","Cold Call",array('Employee','Boss')),
            array("^Employee^","Cold Call",array('Employee')),
            array("Employee","Cold Call",array('Employee')),
            array("","^Cold Call^",array("Cold Call")),
            array(array("Employee"),"Cold Call",array("Employee")),
            array(NULL,array("Employee"),array("Employee")),
            );
    }
    
    /**
     * @ticket 21574
     * @dataProvider providerPassedString
     */
	public function testPassedString(
        $string,
        $default,
        $result
        )
    {
        $params = array();
        $params['string']  = $string;
        $params['default'] = $default;
        
        $this->assertEquals($result, smarty_function_multienum_to_array($params, $this->_smarty));
    }
	
	public function testAssignSmartyVariable()
    {
        $params = array();
        $params['string']  = "^Employee^";
        $params['default'] = "Cold Call";
		$params['assign'] = "multi";
		smarty_function_multienum_to_array($params, $this->_smarty);
        
        $this->assertEquals(
            $this->_smarty->get_template_vars($params['assign']),
            array("Employee")
        );
    }
}