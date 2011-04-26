<?php
require_once 'include/utils.php';

class CheckPlatformTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->_isOnWindows = (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN');
    }
    
    public function testVerifyIfWeAreOnWindows()
    {
        $this->assertEquals(is_windows(), $this->_isOnWindows);
    }
}

