<?php

require_once("include/utils.php");

class ValidDBNameTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function testShortNameUneffected()
    {
        $this->assertEquals(
            'idx_test_123_id',
            getValidDBName('idx_test_123_id')
        );
    }

    public function testmaxLengthParam()
    {
        $this->assertEquals(
            'idx_test_123_456_789_foo_bar_id',
            getValidDBName('idx_test_123_456_789_foo_bar_id', false, 40)
        );
    }

    public function testEnsureUnique()
    {
        $this->assertEquals(
            getValidDBName('idx_test_123_456_789_foo_bar_id', true),
            getValidDBName('idx_test_123_456_789_foo_bar_id', true)
        );

        $this->assertNotEquals(
            getValidDBName('idx_test_123_456_789_foo_bar_id', true),
            getValidDBName('idx_test_123_446_789_foo_bar_id', true)
        );
    }

}
