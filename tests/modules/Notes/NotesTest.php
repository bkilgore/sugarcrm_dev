<?php
require_once "modules/Notes/Note.php";

class NotesTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
	}

    public function tearDown()
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
    }
    
    /**
     * @group bug19499
     */
    public function testCreateProperNameFieldContainsFirstAndLastName()
    {
        require_once("modules/Contacts/Contact.php");
        $contact = new Contact();
        $contact->first_name = "Josh";
        $contact->last_name = "Chi";
        $contact->salutation = "Mr";
        $contact->title = 'VP Operations';
        $contact->disable_row_level_security = true;
        $contact_id = $contact->save();
        
        $note = new Note();
        $note->contact_id = $contact_id;
        $note->disable_row_level_security = true;
        $note->fill_in_additional_detail_fields();
        
        $this->assertContains($contact->first_name,$note->contact_name);
        $this->assertContains($contact->last_name,$note->contact_name);
        
        $GLOBALS['db']->query('DELETE FROM contacts WHERE id =\''.$contact_id.'\'');
    }
}
