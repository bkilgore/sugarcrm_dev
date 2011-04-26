<?php

class Bug39780Test extends Sugar_PHPUnit_Framework_TestCase
{
    protected $contact;
    
    public function setUp()
    {
        $this->contact = SugarTestContactUtilities::createContact();
	}
	
	public function tearDown()
	{
	    SugarTestContactUtilities::removeAllCreatedContacts();
	}
	
	// Test unPopulateDefaultValues to make sure it doesn't generate any notices
    /*
     * @group bug39780
     */
    public function testSugarBeanUnPopulateDefaultValues()
    {
        $this->contact->first_name = 'SadekDizzle';
        $this->contact->field_defs['first_name']['default'] = 'SadekSnizzle';
        try{
            $this->contact->unPopulateDefaultValues();
        }
        catch(Exception $e){
            $this->assertTrue(false, "SugarBean->unPopulateDefaultValues is generating a notice/warning/fatal: " .$e->getMessage());
            return;
        }
        
        $this->assertTrue(true);
    }
}
