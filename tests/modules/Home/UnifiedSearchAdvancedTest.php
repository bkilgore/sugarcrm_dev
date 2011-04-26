<?php
require_once 'modules/Home/UnifiedSearchAdvanced.php';
require_once 'modules/Contacts/Contact.php';
require_once 'include/utils/layout_utils.php';

/**
 * @group bug34125
 */
class UnifiedSearchAdvancedTest extends Sugar_PHPUnit_Framework_TestCase
{
    protected $_contact = null;

    public function setUp()
    {
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
        
        $unid = uniqid();
        $contact = new Contact();
        $contact->id = 'l_'.$unid;
        $contact->first_name = 'Greg';
        $contact->last_name = 'Brady';
        $contact->new_with_id = true;
        $contact->save();
        $this->_contact = $contact;
	}

	public function tearDown()
	{
        $GLOBALS['db']->query("DELETE FROM contacts WHERE id= '{$this->_contact->id}'");
        unset($this->_contact);
	}

	public function testSearchByFirstName()
	{
		global $mod_strings, $modListHeader, $app_strings, $beanList, $beanFiles;
		require('config.php');
		require('include/modules.php');
		$modListHeader = $moduleList;
    	$_REQUEST['query_string'] = $this->_contact->first_name;
    	$_REQUEST['module'] = 'Home';
		$usa = new UnifiedSearchAdvanced();
		ob_start();
		$usa->search();
		$html = ob_get_contents();
		ob_clean();
		$pos = strpos($html, $this->_contact->first_name);
		$this->assertTrue(!empty($pos), "Could not find the contact: ".$this->_contact->first_name." in the search results.");
    }

	public function testSearchByFirstAndLastName()
	{
		global $mod_strings, $modListHeader, $app_strings, $beanList, $beanFiles;
		require('config.php');
		require('include/modules.php');
		$_REQUEST['query_string'] = $this->_contact->first_name.' '.$this->_contact->last_name;
    	$_REQUEST['module'] = 'Home';
		$usa = new UnifiedSearchAdvanced();
		ob_start();
		$usa->search();
		$html = ob_get_contents();
		ob_clean();
		$pos = strpos($html, $this->_contact->first_name);
		$this->assertTrue(!empty($pos), "Could not find the lead: ".$this->_contact->first_name." in the search results.");
    }
}

