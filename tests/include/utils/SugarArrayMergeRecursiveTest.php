<?php
require_once 'include/utils.php';

class SugarArrayMergeRecursiveTest extends Sugar_PHPUnit_Framework_TestCase
{
    /**
     * @ticket 25280
     */
    public function testDeepArrayMerge()
    {
	$array1 = array("one" => array("two" => array("three" => array("some" => "stuff"))));
	$array2 = array("one" => array("two" => array("three" => array("more" => "stuff"))));
	$expected = array("one" => array("two" => array("three" => array("more" => "stuff", "some" => "stuff"))));
        $results = sugarArrayMergeRecursive($array1,$array2);
        $this->assertEquals($results, $expected);
    }

    /**
     * this one won't preserve order
     */
    public function testSubArrayKeysArePreserved() 
    {
        $array1 = array(
            'dog' => array(
                'dog1' => 'dog1',
                'dog2' => 'dog2',
                'dog3' => 'dog3',
                'dog4' => 'dog4',
                )
            );
        
        $array2 = array(
            'dog' => array(
                'dog2' => 'dog2',
                'dog1' => 'dog1',
                'dog3' => 'dog3',
                'dog4' => 'dog4',
                )
            );
        
        $results = sugarArrayMergeRecursive($array1,$array2);
        
        $keys1 = sort(array_keys($results['dog']));
        $keys2 = sort(array_keys($array2['dog']));
        
        $this->assertEquals($keys1,$keys2);
    }
    
    public function testSugarArrayMergeMergesTwoArraysWithLikeKeysOverwritingExistingKeys()
    {
        $foo = array(
            'one' => 123,
            'two' => 123,
            'foo' => array(
                'int' => 123,
                'foo' => 'bar',
            ),
        );
        $bar = array(
            'one' => 123,
            'two' => 321,
            'foo' => array(
                'int' => 123,
                'bar' => 'foo',
            ),
        );
        
        $expected = array(
            'one' => 123, 
            'two' => 321,
            'foo' => array(
                'int' => 123,
                'foo' => 'bar',
                'bar' => 'foo',
            ),
        );
        $this->assertEquals(sugarArrayMergeRecursive($foo, $bar), $expected);
        // insure that internal functions can't duplicate behavior
        $this->assertNotEquals(array_merge($foo, $bar), $expected);
        $this->assertNotEquals(array_merge_recursive($foo, $bar), $expected);
    }
}
