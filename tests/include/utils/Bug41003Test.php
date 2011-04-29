<?php
require_once 'include/utils.php';

class Bug41003Test extends Sugar_PHPUnit_Framework_TestCase
{
    public function providerVerifyStrippingOfBrInBr2nlFunction()
    {
        return array(
            array("here is my text with no newline", "here is my text with no newline"),
            array("here is my text with a newline lowercased\n", "here is my text with a newline lowercased<br>"),
            array("here is my text with a newline mixed case\n", "here is my text with a newline mixed case<Br>"),
            array("here is my text with a newline mixed case with /\n", "here is my text with a newline mixed case with /<Br />"),
            array("here is my text with a newline uppercase\n", "here is my text with a newline uppercase<BR />"),
            array("here is my crappy text éèçàô$*%ù§!#with a newline\n in the middle", "here is my crappy text éèçàô$*%ù§!#with a newline<bR> in the middle"),
            );
    }
    
    /**
     * @dataProvider providerVerifyStrippingOfBrInBr2nlFunction
     */
    public function testVerifyStrippingOfBrInBr2nlFunction($expectedResult,$testString)
    {
        $this->assertEquals($expectedResult, br2nl($testString));
    }
}

