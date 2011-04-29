<?php
require_once 'include/utils.php';

class MiscUtilsTest extends Sugar_PHPUnit_Framework_TestCase
{
	public function providerIsGuid()
	{
		return array(
            array('yuckyuck',false),
            array('1d6a784b-e082-8522-9b1c-4d48e25c5e03',true),
            array('1d6a784b-e082-8522-9b1c-4d48e2c5e03',false),
        );
    }
    
	/**
     * @dataProvider providerIsGuid
     */
	public function testIsGuid(
	    $guid, 
	    $expectedResult
	    )
	{
	    $this->assertEquals($expectedResult,is_guid($guid));
	}
	
	public function testGenerateWhereStatement()
	{
		$where = array("dog = '1'","cat = '3'");
		
		$this->assertEquals("dog = '1' and cat = '3'",generate_where_statement($where));
    }
    
    public function testAppendWhereClause()
    {
        $where = array();
        $_REQUEST['dog'] = 'yuck';
        
        append_where_clause($where,'dog');
        
        unset($_REQUEST['dog']);
        
        $this->assertEquals("dog like 'yuck%'",$where[0]);
    }
    
    public function testAppendWhereClauseDifferentColumnName()
    {
        $where = array();
        $_REQUEST['dog'] = 'yuck';
        
        append_where_clause($where,'dog','cat');
        
        unset($_REQUEST['dog']);
        
        $this->assertEquals("cat like 'yuck%'",$where[0]);
    }
    
    public function testReturnSessionOrDefaultWhenSessionSet()
    {
        $_SESSION['yellow'] = 'blue';
        
        $result = return_session_value_or_default('yellow','red');
        
        unset($_SESSION['yellow']);
        
        $this->assertEquals('blue',$result);
    }
    
    public function testReturnSessionOrDefaultWhenSessionIsNotSet()
    {
        $result = return_session_value_or_default('yellow','red');
        
        $this->assertEquals('red',$result);
    }
    
    public function testGetVariableFromQueryString()
    {
        $this->assertEquals('123',getVariableFromQueryString('great','ok=12&great=123&bad=234'));
    }
    
    public function testGetVariableFromQueryStringWhenNoVariableFound()
    {
        $this->assertFalse(getVariableFromQueryString('horrible','ok=12&great=123&bad=234'));
    }
    
    public function testSugarUcfirst()
    {
        $this->assertEquals('John',sugar_ucfirst('John'));
    }
    
    public function testFilterInboundEmailPopSelection()
    {
        $protocals = array('pop3' => 'POP3','imap' => 'IMAP');
        
        if ( isset($GLOBALS['sugar_config']['allow_pop_inbound']) ) {
            $oldsetting = $GLOBALS['sugar_config']['allow_pop_inbound'];
        }
        $GLOBALS['sugar_config']['allow_pop_inbound'] = false;
        
        $protocals = filterInboundEmailPopSelection($protocals);
        
        if ( isset($oldsetting) ) {
            $GLOBALS['sugar_config']['allow_pop_inbound'] = $oldsetting;
        }
        else {
            unset($GLOBALS['sugar_config']['allow_pop_inbound']);
        }
        
        $this->assertEquals(array('imap' => 'IMAP'),$protocals);
    }
    
    public function testFilterInboundEmailPopSelectionWhenItShouldBethere()
    {
        $protocals = array('imap' => 'IMAP');
        
        if ( isset($GLOBALS['sugar_config']['allow_pop_inbound']) ) {
            $oldsetting = $GLOBALS['sugar_config']['allow_pop_inbound'];
        }
        $GLOBALS['sugar_config']['allow_pop_inbound'] = true;
        
        $protocals = filterInboundEmailPopSelection($protocals);
        
        if ( isset($oldsetting) ) {
            $GLOBALS['sugar_config']['allow_pop_inbound'] = $oldsetting;
        }
        else {
            unset($GLOBALS['sugar_config']['allow_pop_inbound']);
        }
        
        $this->assertEquals(array('imap' => 'IMAP','pop3' => 'POP3',),$protocals);
    }
    
    public function testIsTouchScreenReturnsTrueIfCookieSet()
    {
        $_COOKIE['touchscreen'] = '1';
        
        $result = isTouchScreen();
        
        unset($_COOKIE['touchscreen']);
        
        $this->assertTrue($result);
    }
    
    public function testIsTouchScreenReturnsTrueIfUserAgentIsIpad()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.10';
        
        $result = isTouchScreen();
        
        unset($_SERVER['HTTP_USER_AGENT']);
        
        $this->assertTrue($result);
    }
    
    public function testIsTouchScreenReturnsTrueIfUserAgentIsNotIpad()
    {
        $_SERVER['HTTP_USER_AGENT'] = 'Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420+ (KHTML, like Gecko) Version/3.0 Mobile/1A543a Safari/419.3';
        
        $result = isTouchScreen();
        
        unset($_SERVER['HTTP_USER_AGENT']);
        
        $this->assertFalse($result);
    }

    public function testValuesToKeys()
    {
        $arr = array('apples', 'oranges', 'lemons', 'strawberries');
        $newArr = values_to_keys($arr);
        $this->assertArrayHasKey('lemons', $newArr, 'The conversion to hash did not work properly');
    }

    public function testNumberEmpty()
    {
        $num1 = 0;
        $num2 = '0';
        $num3 = -1;
        $num4 = 'true';
        $num5 = 'false';
        $num6 = false;
        $num7 = 10;
        $num8 = '10';
        $num9 = '';

        $this->assertEquals(false, number_empty($num1), "Found 0 to be empty");
        $this->assertEquals(false, number_empty($num2), "Found '0' to be empty");
        $this->assertEquals(false, number_empty($num3), "Found -1 to be empty");
        $this->assertEquals(false, number_empty($num4), "Found 'true' to be empty");
        $this->assertEquals(false, number_empty($num5), "Found 'false' to be empty");
        $this->assertEquals(false, number_empty($num6), "Found false to be empty");
        $this->assertEquals(false, number_empty($num7), "Found 10 to be empty");
        $this->assertEquals(false, number_empty($num8), "Found '10' to be empty");
        $this->assertEquals(true, number_empty($num9), "Did not find empty string to be empty");
    }
}
