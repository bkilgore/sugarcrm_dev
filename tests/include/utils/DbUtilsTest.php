<?php
require_once 'include/utils/db_utils.php';

/**
 * @todo refactor this test to not use test-level fixtures.  Will require
 *       refactoring from_html() so it doesn't create static caches within
 *       itself
 */
class DbUtilsTest extends Sugar_PHPUnit_Framework_TestCase
{
    private $_old_toHTML = null;
    private $_random = null;
    
    public function setUp() 
    {
        $this->_random = rand(100, 200);
        $GLOBALS['from_html_cache_clear'] = true;
        $this->_old_toHTML = $GLOBALS['toHTML'];
        $GLOBALS['toHTML'] = array(
            'foobar' => 'barfoo',
            $this->_random => 'random',
        );
    }

    public function tearDown() 
    {
        $GLOBALS['toHTML'] = $this->_old_toHTML;
    }

    public function testReturnsSameValueOnNoneStrings() 
    {
        $random = rand(100, 200);
        $this->assertEquals(from_html($random), $random);
    }

    public function testSwapsValuesForKeysFromToHTMLGlobal() 
    {
        $GLOBALS['toHTML']['foobar'] = 'barfoo';
        $this->assertEquals(from_html('barfoo'), 'foobar');
    }

    public function testSwapsValuesForKeysFromToHTMLGlobalWithRandomData() 
    {
        $this->assertEquals(from_html('random'), $this->_random);
    }

    public function testWillReturnTheSameValueTwiceInARow() 
    {
        unset($GLOBALS['from_html_clear_cache']);
        $GLOBALS['toHTML']['foobar'] = 'barfoo';
        $this->assertEquals(from_html('barfoo'), 'foobar');
        $this->assertEquals(from_html('barfoo'), 'foobar');
    }

    public function testWillReturnRawValueIfEncodeParameterIsFalse() 
    {
        $GLOBALS['toHTML']['foobar'] = 'barfoo';
        $this->assertEquals(from_html('barfoo', false), 'barfoo');
    }
}

