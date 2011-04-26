<?php
require_once 'include/SearchForm/SugarSpot.php';

class SugarSpotTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $GLOBALS['app_strings'] = return_application_language($GLOBALS['current_language']); 
    }
    
    public function tearDown()
    {
        unset($GLOBALS['app_strings']);
    }
    
    /**
     * @ticket 41236
     */
    public function testSearchGrabsModuleDisplayName() 
    {
        $langpack = new SugarTestLangPackCreator();
        $langpack->setAppListString('moduleList',array('Foo'=>'Bar'));
        $langpack->save();
        
        $result = array(
            'Foo' => array(
                'data' => array(
                    array(
                        'ID' => '1',
                        'NAME' => 'recordname',
                        ),
                    ),
                'pageData' => array(
                    'offsets' => array(
                        'total' => 1,
                        'next' => 0,
                        ),
                    'bean' => array(
                        'moduleDir' => 'Foo',
                        ),
                    ),
                ),
            );
        
        $sugarSpot = $this->getMock('SugarSpot', array('_performSearch'));
        $sugarSpot->expects($this->any())
            ->method('_performSearch')
            ->will($this->returnValue($result));
            
        $returnValue = $sugarSpot->searchAndDisplay('','');
        
        $this->assertNotContains('<div id="SpotResults"><div>Foo </div>',$returnValue);
        $this->assertContains('<div id="SpotResults"><div>Bar </div>',$returnValue);
    }
}
