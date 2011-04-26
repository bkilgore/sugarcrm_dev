<?php
require_once 'include/utils/array_utils.php';

class DeepArrayDiffTest extends Sugar_PHPUnit_Framework_TestCase
{
    /**
     * @group bug24067
     */
    public function testdeepArrayDiffWithBooleanFalse()
    {
        $array1 = array(
            'value1' => true,
            'value2' => false,
            'value3' => 'yummy'
            );
        
        $array2 = array(
            'value1' => true,
            'value2' => true,
            'value3' => 'yummy'
            );
        
    	$diffs = deepArrayDiff($array1,$array2);
        
        $this->assertEquals($diffs['value2'], false);
        $this->assertFalse(isset($diffs['value1']));
        $this->assertFalse(isset($diffs['value3']));
        
        
        $diffs = deepArrayDiff($array2,$array1);
        
        $this->assertEquals($diffs['value2'], true);
        $this->assertFalse(isset($diffs['value1']));
        $this->assertFalse(isset($diffs['value3']));
    }
}
