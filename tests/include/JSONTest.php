<?php
require_once 'include/JSON.php';

class JSONTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        unset($_SESSION['asychronous_key']);
    }
    
    public function testCanEncodeBasicArray() 
    {
        $array = array('foo' => 'bar', 'bar' => 'foo');
        $this->assertEquals(
            '{"foo":"bar","bar":"foo"}',
            JSON::encode($array)
        );
    }

    public function testCanEncodeBasicObjects() 
    {
        $obj = new stdClass();
        $obj->foo = 'bar';
        $obj->bar = 'foo';
        $this->assertEquals(
            '{"foo":"bar","bar":"foo"}',
            JSON::encode($obj)
        );
    }
    
    public function testCanEncodeMultibyteData() 
    {
        $array = array('foo' => '契約', 'bar' => '契約');
        $this->assertEquals(
            '{"foo":"\u5951\u7d04","bar":"\u5951\u7d04"}',
            JSON::encode($array)
        );
    }
    
    public function testCanDecodeObjectIntoArray()
    {
        $array = array('foo' => 'bar', 'bar' => 'foo');
        $this->assertEquals(
            JSON::decode('{"foo":"bar","bar":"foo"}'),
            $array
        );
    }
    
    public function testCanDecodeMultibyteData() 
    {
        $array = array('foo' => '契約', 'bar' => '契約');
        $this->assertEquals(
            JSON::decode('{"foo":"\u5951\u7d04","bar":"\u5951\u7d04"}'),
            $array
        );
    }
    
    public function testEncodeWithSecurityEnvelope()
    {
        $array = array('foo' => 'bar', 'bar' => 'foo');
        $this->assertEquals(
            'while(1);/*{"foo":"bar","bar":"foo"}*/',
            JSON::encode($array,true)
        );
    }
    
    public function testDecodeWithValidSecurityEnvelope()
    {
        $jsonString = '{"asychronous_key":"bar","jsonObject":"foo"}';
        $_SESSION['asychronous_key'] = 'bar';
        
        $this->assertEquals('foo',JSON::decode($jsonString,true));
    }
    
    public function testDecodeWithInvalidSecurityEnvelope()
    {
        $jsonString = '{"asychronous_key":"dog","jsonObject":"foo"}';
        $_SESSION['asychronous_key'] = 'bar';
        
        $this->assertEquals('',JSON::decode($jsonString,true));
    }
    
    public function testEncodeRealWorks()
    {
        $array = array('foo' => 'bar', 'bar' => 'foo');
        $this->assertEquals(
            '{"foo":"bar","bar":"foo"}',
            JSON::encodeReal($array)
        );
    }
    
    public function testDecodeRealWorks()
    {
        $array = array('foo' => 'bar', 'bar' => 'foo');
        $this->assertEquals(
            JSON::decodeReal('{"foo":"bar","bar":"foo"}'),
            $array
        );
    }
}
