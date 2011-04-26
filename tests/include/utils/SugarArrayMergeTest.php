<?php
require_once 'include/utils.php';

class SugarArrayMergeTest extends Sugar_PHPUnit_Framework_TestCase
{
    /**
     * @group bug17142
     */
    public function testSubArrayOrderIsPreserved() 
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
        
        $results = sugarArrayMerge($array1,$array2);
        
        $keys1 = array_keys($results['dog']);
        $keys2 = array_keys($array2['dog']);
        
        for ( $i = 0; $i < 4; $i++ ) {
            $this->assertEquals($keys1[$i],$keys2[$i]);
        }
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
        $this->assertEquals(sugarArrayMerge($foo, $bar), $expected);
        // insure that internal functions can't duplicate behavior
        $this->assertNotEquals(array_merge($foo, $bar), $expected);
        $this->assertNotEquals(array_merge_recursive($foo, $bar), $expected);
    }
}
