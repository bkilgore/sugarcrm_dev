<?php

require_once('modules/UpgradeWizard/uw_utils.php');
require_once('modules/MySettings/TabController.php');

class Bug42490Test extends Sugar_PHPUnit_Framework_TestCase 
{
	private $_originalEnabledTabs;
	private $_tc;
	
	public function setUp() 
	{
		global $moduleList;
		include('include/modules.php');
	    $this->_tc = new TabController();	
	    $tabs = $this->_tc->get_tabs_system();  	
	    $this->_originalEnabledTabs = $tabs[0];
	}
	
	public function tearDown() 
	{
		$this->_tc->set_system_tabs($this->_originalEnabledTabs);
	}

	public function testUpgradeDisplayedTabsAndSubpanels() 
	{
		$modules_to_add = array('Calls', 'Meetings', 'Tasks', 'Notes', 'Prospects', 'ProspectLists');

		upgradeDisplayedTabsAndSubpanels('610');
		
		$all_tabs = $this->_tc->get_tabs_system();
		$tabs = $all_tabs[0];
		
		foreach($modules_to_add as $module)
		{
			$this->assertTrue(isset($tabs[$module]), 'Assert that ' . $module . ' tab is set for system tabs');
		}
	}
}