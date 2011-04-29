<?php
require_once 'modules/Home/UnifiedSearchAdvanced.php';
require_once 'modules/Contacts/Contact.php';
require_once 'include/utils/layout_utils.php';

/**
 * @ticket 34125
 */
class UnifiedSearchAdvancedTest extends Sugar_PHPUnit_Framework_OutputTestCase
{
    protected $_contact = null;
    private $_hasUnifiedSearchModulesConfig = false;
    private $_hasUnifiedSearchModulesDisplay = false;
    
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
        
        if(file_exists('cache/modules/unified_search_modules.php'))
        {
        	$this->_hasUnifiedSearchModulesConfig = true;
        	copy('cache/modules/unified_search_modules.php', 'cache/modules/unified_search_modules.php.bak');
        	unlink('cache/modules/unified_search_modules.php');
        }

        if(file_exists('cache/modules/unified_search_modules_display.php'))
        {
        	$this->_hasUnifiedSearchModulesDisplay = true;
        	copy('cache/modules/unified_search_modules_display.php', 'cache/modules/unified_search_modules_display.php.bak');
        	unlink('cache/modules/unified_search_modules_display.php');
        }        
        
	}

	public function tearDown()
	{
        $GLOBALS['db']->query("DELETE FROM contacts WHERE id= '{$this->_contact->id}'");
        unset($this->_contact);
        
        if($this->_hasUnifiedSearchModulesConfig)
        {
        	copy('cache/modules/unified_search_modules.php.bak', 'cache/modules/unified_search_modules.php');
        	unlink('cache/modules/unified_search_modules.php.bak');
        } else {
        	unlink('cache/modules/unified_search_modules.php');
        }
        
        if($this->_hasUnifiedSearchModulesDisplay)
        {
        	copy('cache/modules/unified_search_modules_display.php.bak', 'cache/modules/unified_search_modules_display.php');
        	unlink('cache/modules/unified_search_modules_display.php.bak');
        } else {
        	unlink('cache/modules/unified_search_modules_display.php');
        }
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
		$usa->search();
		$this->expectOutputRegex("/{$this->_contact->first_name}/");
    }

	public function testSearchByFirstAndLastName()
	{
		global $mod_strings, $modListHeader, $app_strings, $beanList, $beanFiles;
		require('config.php');
		require('include/modules.php');
		$_REQUEST['query_string'] = $this->_contact->first_name.' '.$this->_contact->last_name;
    	$_REQUEST['module'] = 'Home';
		$usa = new UnifiedSearchAdvanced();
		$usa->search();
		$this->expectOutputRegex("/{$this->_contact->first_name}/");
    }
    
    public function testUserPreferencesSearch()
    {
		global $mod_strings, $modListHeader, $app_strings, $beanList, $beanFiles;
		require('config.php');
		require('include/modules.php');
  	
    	$usa = new UnifiedSearchAdvanced();
    	$_REQUEST['enabled_modules'] = 'Accounts,Contacts';
    	$usa->saveGlobalSearchSettings();
    	
    	$_REQUEST = array();
		$_REQUEST['query_string'] = $this->_contact->first_name.' '.$this->_contact->last_name;
    	$_REQUEST['module'] = 'Home';      	
    	$usa->search();
    	global $current_user;
    	$modules = $current_user->getPreference('globalSearch', 'search');
    	$this->assertEquals(count($modules), 2, 'Assert that there are two modules in the user preferences as defined from the global search');
    	$this->assertTrue(isset($modules['Accounts']) && isset($modules['Contacts']), 'Assert that the Accounts and Contacts modules have been added');    	
    }
}

