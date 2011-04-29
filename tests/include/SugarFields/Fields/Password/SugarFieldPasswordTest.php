<?php 
require_once('include/SugarFields/Fields/Password/SugarFieldPassword.php');
require_once('modules/Import/ImportFieldSanitize.php');

class SugarFieldPasswordTest extends Sugar_PHPUnit_Framework_TestCase
{
	/**
	 * @ticket 40304
	 */
	public function testImportSanitize()
	{
	    $fieldObj = new SugarFieldPassword('Password');
	    
	    $settings = new ImportFieldSanitize();
	    
        $this->assertEquals(
            md5('test value'),
            $fieldObj->importSanitize('test value',array(),null,$settings)
            );
    }
}