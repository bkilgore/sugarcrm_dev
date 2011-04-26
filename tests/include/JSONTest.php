<?php
require_once 'include/JSON.php';

class JSONTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function testCanEncodeBasicArray() 
    {
        $array = array('foo' => 'bar', 'bar' => 'foo');
        $json = new JSON();
        $this->assertEquals(
            '{"foo":"bar","bar":"foo"}',
            $json->encode($array)
        );
    }

    public function testCanEncodeBasicObjects() 
    {
        $obj = new stdClass();
        $obj->foo = 'bar';
        $obj->bar = 'foo';
        $json = new JSON();
        $this->assertEquals(
            '{"foo":"bar","bar":"foo"}',
            $json->encode($obj)
        );
    }
}
