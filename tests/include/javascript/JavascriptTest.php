<?php 

require_once 'include/javascript/javascript.php';

class JavascriptTest extends Sugar_PHPUnit_Framework_TestCase
{
    protected $_javascript;
    
    public function setUp()
    {
        $this->_javascript = new javascript();
    }
    
    public function providerBuildStringToTranslateInSmarty()
    {
        return array(
            array(
                "LBL_TEST",
                "{/literal}{sugar_translate label='LBL_TEST' module='' for_js=true}{literal}",
                ),
            array(
                array("LBL_TEST","LBL_TEST_2"),
                "{/literal}{sugar_translate label='LBL_TEST' module='' for_js=true}{literal}{/literal}{sugar_translate label='LBL_TEST_2' module='' for_js=true}{literal}",
                ),
            );
    }

    /**
     * @dataProvider providerBuildStringToTranslateInSmarty
     * @ticket 41983
     */
    public function testBuildStringToTranslateInSmarty($string, $returnedString) 
    {
        $this->assertEquals($returnedString, $this->_javascript->buildStringToTranslateInSmarty($string));
    }
}
