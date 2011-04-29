<?php
require_once 'include/Smarty/plugins/function.sugar_help.php';
require_once 'include/Sugar_Smarty.php';

class FunctionSugarHelpTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->_smarty = new Sugar_Smarty;
    }
    
    public function providerSpecialCharactersHandledInTextParameter()
    {
        return array(
            array(
                'dog "the" bounty hunter & friends are <b>cool</b>',
                'dog &quot;the&quot; bounty hunter &amp; friends are &lt;b&gt;cool&lt;/b&gt;',
                ),
            array(
                "dog 'the' bounty hunter",
                "dog \'the\' bounty hunter"
                ),
            );
    }
    
    /**
     * @dataProvider providerSpecialCharactersHandledInTextParameter
     */
	public function testSpecialCharactersHandledInTextParameter(
        $string,
        $returnedString
        )
    {
        $this->assertContains($returnedString, smarty_function_sugar_help(array('text'=>$string),$this->_smarty));
    }
    
    public function testExtraParametersAreAdded()
    {
        $string = 'my string';
        
        $output = smarty_function_sugar_help(array('text'=>$string,'foo'=>'bar'),$this->_smarty);
        
        $this->assertContains(",foo,bar",$output);
    }
}
