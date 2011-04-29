<?php

class Bug40989 extends Sugar_PHPUnit_Framework_TestCase
{
    protected $contact;

	public static function setUpBeforeClass()
	{
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
	}

	public static function tearDownAfterClass()
	{
	    SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
	}

	public function setUp()
    {
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
        $this->contact = SugarTestContactUtilities::createContact();
	}

	public function tearDown()
	{
	    SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
        SugarTestContactUtilities::removeAllCreatedContacts();
	}
	
    /*
     * @group bug40989
     */
    public function testRetrieveByStringFieldsFetchedRow()
    {
        $loadedContact = loadBean('Contacts');
        $loadedContact = $loadedContact->retrieve_by_string_fields(array('last_name'=>'SugarContactLast'));
    
        $this->assertEquals('SugarContactLast', $loadedContact->fetched_row['last_name']);
    }

    public function testProcessFullListQuery()
    {
        $loadedContact = loadBean('Contacts');
        $loadedContact->disable_row_level_security = true;
        $contactList = $loadedContact->get_full_list();
    
        $exampleContact = array_pop($contactList);	
    
        $this->assertNotNull($exampleContact->fetched_row['last_name']);
    }
}
