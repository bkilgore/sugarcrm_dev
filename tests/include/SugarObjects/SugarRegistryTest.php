<?php
require_once 'include/SugarObjects/SugarRegistry.php';

class SugarRegistryTest extends Sugar_PHPUnit_Framework_TestCase
{
    private $_old_reporting = null;
    private $_old_globals = null;

    public function setUp() 
    {
        $this->_old_reporting = error_reporting(E_ALL);
        $this->_old_globals = $GLOBALS;
        unset($GLOBALS);
    }

    public function tearDown() 
    {
        error_reporting($this->_old_reporting);
        $GLOBALS = $this->_old_globals;
        unset($this->_old_globals);
    }

    public function testGetInstanceReturnsAnInstanceOfSugarRegistry() 
    {
        $this->assertTrue(SugarRegistry::getInstance() instanceOf SugarRegistry,'Returned object is not a SugarRegistry instance');
    }

    public function testGetInstanceReturnsSameObject() 
    {
        $one = SugarRegistry::getInstance();
        $two = SugarRegistry::getInstance();
        $this->assertSame($one, $two);
    }

    public function testParameterPassedToGetInstanceSpecifiesInstanceName() 
    {
        $foo1 = SugarRegistry::getInstance('foo');
        $foo2 = SugarRegistry::getInstance('foo');
        $this->assertSame($foo1, $foo2);

        $bar = SugarRegistry::getInstance('bar');
        $this->assertNotSame($foo1, $bar);
    }

    public function testCanSetAndGetValues() 
    {
        $random = rand(100, 200);
        $r = SugarRegistry::getInstance();
        $r->integer = $random;
        $this->assertEquals($random, $r->integer);
        $this->assertEquals($random, SugarRegistry::getInstance()->integer);
    }

    public function testIssetReturnsTrueFalse() 
    {
        $r = SugarRegistry::getInstance();
        $this->assertFalse(isset($r->foo));
        $this->assertFalse(isset(SugarRegistry::getInstance()->foo));

        $r->foo = 'bar';
        $this->assertTrue(isset($r->foo));
        $this->assertTrue(isset(SugarRegistry::getInstance()->foo));
    }

    public function testUnsetRemovesValueFromRegistry() 
    {
        $r = SugarRegistry::getInstance();
        $r->foo = 'bar';
        unset($r->foo);
        $this->assertFalse(isset($r->foo));
        $this->assertFalse(isset(SugarRegistry::getInstance()->foo));
    }

    public function testReturnsNullOnAnyUnknownValue() 
    {
        $r = SugarRegistry::getInstance();
        $this->assertNull($r->unknown);
        $this->assertNull(SugarRegistry::getInstance()->unknown);
    }

    public function testAddToGlobalsPutsRefsToAllRegistryObjectsInGlobalSpace() 
    {
        $r = SugarRegistry::getInstance();
        $r->foo = 'bar';

        $this->assertFalse(isset($GLOBALS['foo']), 'sanity check');
        $r->addToGlobals();
        $this->assertTrue(isset($GLOBALS['foo']));
    }
}

