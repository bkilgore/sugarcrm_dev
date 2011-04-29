<?php
require_once 'include/SugarEmailAddress/SugarEmailAddress.php';


/**
 * 
 * Bug 40068
 *
 */

class Bug40068Test extends Sugar_PHPUnit_Framework_TestCase
{
    public function providerEmailAddressRegex()
	{
	    return array(
	        array('john@john.com',true),
	        array('jo&hn@john.com',true),
	        array('joh#n@john.com.br',true),
	        array('&#john@john.com', true),
	        array('atendimento-hd.@uol.com.br',true),
	        );
	}
    
    /**
     * @group bug40068
     * @dataProvider providerEmailAddressRegex
     */
	public function testEmailAddressRegex($email, $valid) 
    {
        $sea = new SugarEmailAddress;
        
        if ( $valid ) {
            $this->assertRegExp($sea->regex,$email);
        }
        else {
            $this->assertNotRegExp($sea->regex,$email);
        }     
    }
}
