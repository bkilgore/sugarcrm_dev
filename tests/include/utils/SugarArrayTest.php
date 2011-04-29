<?php
require_once 'include/utils/array_utils.php';

class SugarArrayTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function testCanFindValueUsingDotNotation() 
    {
        $random = rand(100, 200);
        $array = array(
            'foo' => array(
                $random => array(
                    'bar' => $random,
                ),
            ),
        );

        $array = new SugarArray($array);
        $this->assertEquals($array->get("foo.{$random}.bar"), $random);
    }

    public function testReturnsDefaultValueWhenDoesNotContainRequestedValue() 
    {
        $random = rand(100, 200);
        $array = new SugarArray(array());
        $this->assertEquals($array->get('unknown', $random), $random);
    }
    
    public function testImplementsArrayAccess() 
    {
        $reflection = new ReflectionClass('SugarArray');
        $this->assertTrue($reflection->implementsInterface('ArrayAccess'));
    }

    public function testImplementsCountable() 
    {
        $reflection = new ReflectionClass('SugarArray');
        $this->assertTrue($reflection->implementsInterface('Countable'));
    }

    public function testStaticMethodCanTraverseProvidedArray() 
    {
        $random = rand(100, 200);
        $array = array(
            'foo' => array(
                $random => array(
                    'bar' => $random,
                ),
            ),
        );

        $this->assertEquals(SugarArray::staticGet($array, "foo.{$random}.bar"), $random);
    }

    public function testStaticMethodCanReturnDefaultOnUnknownValue() 
    {
        $random = rand(100, 200);
        $this->assertEquals(SugarArray::staticGet(array(123, 321), 'unknown', $random), $random);
    }
    
    public function testAdd_blank_option()
    {
    	$options = 'noneArray';
    	$expectedArray = array(''=>'');
    	$result = add_blank_option($options);
    	$this->assertEquals($result[''], $expectedArray['']);
    	$options2 = array('mason'=>'unittest');
    	$expectedArray2 = array(''=>'','mason'=>'unittest');
    	$result2 = add_blank_option($options2);
    	$this->assertEquals($result2, $expectedArray2);
    }
}

