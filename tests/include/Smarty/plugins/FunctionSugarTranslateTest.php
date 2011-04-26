<?php 

require_once 'include/Smarty/plugins/function.sugar_translate.php';
require_once 'include/Sugar_Smarty.php';

class FunctionSugarTranslateTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function providerJsEscapedSting()
    {
        return array(
            array(
                "Friend's",
                "Friend\'s",
                ),
            array(
                "Friend\'s",
                "Friend\\\\\\'s",
                ),
            array(
                "Friend&#39;s",
                "Friend\'s",
                ),
            array(
                "Friend&#39;'s",
                "Friend\'\'s",
                ),
            array(
                "Friend&#039;s",
                "Friend\'s",
                ),
            array(
                "Friend&#039;'s",
                "Friend\'\'s",
                ),
            );
    }

    /**
     * @dataProvider providerJsEscapedSting
     * @ticket 41983
     */
    public function testJsEscapedSting($string, $returnedString) 
    {
        $langpack = new SugarTestLangPackCreator();
        $langpack->setModString('LBL_TEST_JS_ESCAPED_STRING', $string, 'Contacts');
        $langpack->save();

        $smarty = new Sugar_Smarty;
        
        $this->assertEquals($returnedString, smarty_function_sugar_translate(
            array(
                'label'  => 'LBL_TEST_JS_ESCAPED_STRING',
                'module' => 'Contacts',
                'for_js'  =>  true,
            ),
            $smarty)
        );
    }
}
