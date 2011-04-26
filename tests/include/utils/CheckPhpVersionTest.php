<?php

class CheckPHPVersionTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function providerPhpVersion()
    {
        return array(
            array('4.2.1',-1,'Invalid version below min check failed.'),
            array('5.2.1',1,'Minimum valid version check failed.'),
            array('5.2.7',-1,'Unsupported version check failed.'),
            array('5.2.0-rh',1,'Unsupported custom version check failed as invalid'),
            array('5.2.0-gentoo',-1,'Unsupported custom version check failed as invalid'),
            array('5.2.8',1,'Supported version check failed.'),
            array('5.3.0',1,'Supported version check failed.'),
            array('5.0.0',-1,'Invalid version check failed.'),
            array('7.9.5',0,'Unsupported future version check failed.'),
            );
    }
    
    /**
     * @dataProvider providerPhpVersion
     * @group bug33202
     */
	public function testPhpVersion(
	    $ver, 
	    $expected_retval, 
	    $message
	    )
	{
		$this->assertEquals($expected_retval, check_php_version($ver), $message);
	}
}
