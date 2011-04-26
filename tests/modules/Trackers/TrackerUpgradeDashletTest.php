<?php

require_once('tests/modules/Trackers/TrackerTestUtility.php');

class TrackerUpgradeDashletTest extends Sugar_PHPUnit_Framework_TestCase  {

	var $defaultTrackingDashlets = array('TrackerDashlet', 'MyModulesUsedChartDashlet', 'MyTeamModulesUsedChartDashlet');
       
    function setUp() {
    	 $this->markTestSkipped("Skipping unless otherwise specified");
    	
    	TrackerTestUtility::setUp(); 
        $GLOBALS['mod_strings'] = return_module_language($GLOBALS['current_language'], 'Home');      	

        $cuser = new User();
		$cuser->retrieve('1');
    	$GLOBALS['current_user'] = $cuser;
    	
	    //Set the user theme to be 'Sugar' theme since this is run for CE flavor conversions
	    $cuser->setPreference('user_theme', 'Sugar5', 0, 'global');
    	
        if(ACLController::checkAccess('Trackers', 'view', false, 'Tracker')) {
		  $pages = $GLOBALS['current_user']->getPreference('pages', 'Home');
		  $pages = !empty($pages) ? $pages : array();
		  $dashlets = $GLOBALS['current_user']->getPreference('dashlets', 'Home');
		  $dashlets = !empty($dashlets) ? $dashlets : array();
		  $new_dashlets = array();
		  
              foreach($dashlets as $id=>$dashlet) {
                if(!in_array($dashlet['className'], $this->defaultTrackingDashlets)) {
                	 $new_dashlets[$id] = $dashlet;
                }
              }
              
              $GLOBALS['current_user']->setPreference('dashlets', $new_dashlets, 0, 'Home');
              
              $new_pages = array();
              foreach($pages as $page) {
                    if(!empty($page['pageTitle']) && $page['pageTitle'] != 'Tracker') {
                    	 $new_pages[] = $page;
                    }
              }
              
              $GLOBALS['current_user']->setPreference('pages', $new_pages, 0, 'Home');
              $GLOBALS['current_user']->save();
        } //if        
    }
    
    function tearDown() {
		TrackerTestUtility::tearDown(); 
		$user = new User();
		$user->retrieve('1');
		$GLOBALS['current_user'] = $user; 		
    }

    
    function testUpgradeTrackerDashlet() {
    	$this->upgradeUserPreferencesCopy();
    	$cuser = new User();
    	$cuser->retrieve('1');
		$dashlets = $cuser->getPreference('dashlets', 'Home');
		$countAdded = 0;
		
		foreach($dashlets as $id=>$dashlet) {
			    if(in_array($dashlet['className'], $this->defaultTrackingDashlets)) {
			       $countAdded++;
			    }
		}

		$this->assertEquals($countAdded, 3);
		
		$pages = $cuser->getPreference('pages', 'Home');
	    $countAdded = 0;
		foreach($pages as $id=>$page) {
			    if($page['pageTitle'] == 'Tracker') {
			       $countAdded++;
			    }
		}
		
		$theme = $cuser->getPreference('user_theme', 'global');
		$this->assertTrue($theme == 'Sugar');
		$this->assertTrue($countAdded == 1);
    }    
    
    
/**
 * upgradeUserPreferencesCopy
 *
 */
private function upgradeUserPreferencesCopy() {
	
}    
    
}  
?>