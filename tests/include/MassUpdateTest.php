<?php
require_once 'include/MassUpdate.php';
require_once 'include/dir_inc.php';

class MassUpdateTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp()
    {
		$GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
		$GLOBALS['app_strings'] = return_application_language($GLOBALS['current_language']);
	}

    public function tearDown()
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
        unset($GLOBALS['app_strings']);
    }
    
    /**
     * @group bug12300
     */
    public function testAdddateWorksWithMultiByteCharacters()
    {
        $mass = new MassUpdate();
        $displayname = "开始日期:";
        $varname = "date_start";
        
        $result = $mass->addDate($displayname , $varname);
        $pos_f = strrpos($result, $GLOBALS['app_strings']['LBL_MASSUPDATE_DATE']);
        $this->assertTrue((bool) $pos_f);
    }
    
    /**
     * @group bug23900
     */
    public function testAddStatus() 
    {
        $mass = new MassUpdate();
        $options = array (
            '10' => 'ten',
            '20' => 'twenty',
            '30' => 'thirty',
            );
        $result = $mass->addStatus('test_dom', 'test_dom', $options);
        preg_match_all('/value=[\'\"].*?[\'\"]/si', $result, $matches);
        $this->assertTrue(isset($matches));
        $this->assertTrue($matches[0][0] == "value=''");
        $this->assertTrue($matches[0][1] == "value='10'");
        $this->assertTrue($matches[0][3] == "value='30'");       	
    }
    
    /**
     * @group bug23900
     */
    public function testAddStatusMulti() 
    {
        $mass = new MassUpdate();
        $options = array (
            '10' => 'ten',
            '20' => 'twenty',
            '30' => 'thirty',
            );
        
        $result = $mass->addStatusMulti('test_dom', 'test_dom', $options);
        preg_match_all('/value=[\'\"].*?[\'\"]/si', $result, $matches);
        $this->assertTrue(isset($matches));
        $this->assertTrue($matches[0][0] == "value=''");
        $this->assertTrue($matches[0][1] == "value='10'");
        $this->assertTrue($matches[0][3] == "value='30'");       	
    }
}
