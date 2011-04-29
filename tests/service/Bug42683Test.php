<?php
require_once 'tests/service/SOAPTestCase.php';

/**
 * @ticket 42683
 */
class Bug42683Test extends SOAPTestCase
{
    public function setUp()
    {
    	$this->_soapURL = $GLOBALS['sugar_config']['site_url'].'/service/v2/soap.php';
		parent::setUp();
    }

    public function testBadQuery() 
    {
        $lead = SugarTestLeadUtilities::createLead();
        
        $this->_login();
        $result = $this->_soapClient->call(
            'get_entry_list',
            array(
                'session' => $this->_sessionId,
                "module_name" => 'Leads', 
                "query" => "leads.id = '{$lead->id}'",
                '',
                0,
                array(),
                array(array('name' =>  'email_addresses', 'value' => array('id', 'email_address', 'opt_out', 'primary_address'))),
                )
            );
		
        $this->assertEquals('primary_address', $result['relationship_list'][0][0]['records'][0][3]['name']);
        
        SugarTestLeadUtilities::removeAllCreatedLeads();
    }
}
