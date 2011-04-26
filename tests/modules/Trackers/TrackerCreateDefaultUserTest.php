<?php

require_once('tests/modules/Trackers/TrackerTestUtility.php');

class TrackerCreateDefaultUserTest extends Sugar_PHPUnit_Framework_TestCase {

	var $skipTest = true;
	var $nonAdminUser;
	var $nonAdminUserId;
	var $adminUser;
	var $adminUserId;
    
    function setUp() {
    	if($this->skipTest) {
    	   $this->markTestSkipped("Skipping unless otherwise specified");
    	}
    	
		$user = new User();
		$user->retrieve('1');
		$GLOBALS['current_user'] = $user;    	
    	
    	TrackerTestUtility::setUp(); 
    	$_SESSION['reports_getACLAllowedModules'] = null;
        $this->nonAdminUser = new User();
        $this->nonAdminUser->first_name = 'non';
        $this->nonAdminUser->last_name = 'admin';
        $this->nonAdminUser->user_name = 'nonadmin';
        $this->nonAdminUserId = $this->nonAdminUser->save();	
        
        $this->adminUser = new User();
        $this->adminUser->is_admin = true;
        $this->adminUser->first_name = 'admin';
        $this->adminUser->last_name = 'test';
        $this->adminUser->user_name = 'admintest';
        $this->adminUserId = $this->adminUser->save();
        
        global $beanFiles, $beanList, $moduleList, $modListHeader, $sugar_config;
        require('config.php');
        require('include/modules.php');
        $modListHeader = $moduleList;
    }
    
    function tearDown() {
    	TrackerTestUtility::tearDown(); 
    	$GLOBALS['db']->query("DELETE FROM users WHERE id IN ('{$this->adminUser->id}', '{$this->nonAdminUser->id}')");    	
    	$GLOBALS['db']->query("DELETE FROM team_memberships WHERE user_id IN ('{$this->adminUser->id}', '{$this->nonAdminUser->id}')");
        $GLOBALS['db']->query("DELETE FROM acl_roles_users WHERE user_id IN ('{$this->adminUser->id}', '{$this->nonAdminUser->id}')");;  

		$user = new User();
		$user->retrieve('1');
		$GLOBALS['current_user'] = $user;    
    }
   
    function test_disabled_create_non_admin_user() {
    	global $current_user;
    	$current_user = $this->nonAdminUser;
        require_once('modules/Reports/SavedReport.php');
        $allowedModules = getACLAllowedModules();
        $this->assertTrue(empty($allowedModules['Trackers']));
        $this->assertTrue(empty($allowedModules['TrackerSessions']));
        $this->assertTrue(empty($allowedModules['TrackerPerfs']));
        $this->assertTrue(empty($allowedModules['TrackerQueries']));        
    }
    
    function test_disabled_create_admin_user() {
    	global $current_user;
    	$current_user = $this->adminUser;
    	
        require_once('modules/Reports/SavedReport.php');
        $allowedModules = getACLAllowedModules();
        $this->assertTrue(!empty($allowedModules['Trackers']));
        $this->assertTrue(!empty($allowedModules['TrackerSessions']));
        $this->assertTrue(!empty($allowedModules['TrackerPerfs']));
        $this->assertTrue(!empty($allowedModules['TrackerQueries']));        
    }    
    
    
    function test_disabled_non_admin_user_given_tracker_role() {
    	global $current_user;
    	$current_user = $this->nonAdminUser;
		$result = $GLOBALS['db']->query("SELECT id FROM acl_roles where name='Tracker'");
		$trackerRoleId = $GLOBALS['db']->fetchByAssoc($result);
		if(!empty($trackerRoleId['id'])) {
		   require_once('modules/ACLRoles/ACLRole.php');
		   $role1= new ACLRole();
		   $role1->retrieve($trackerRoleId['id']);
		   $role1->set_relationship('acl_roles_users', array('role_id'=>$role1->id ,'user_id'=>$this->nonAdminUserId), false);
		}

        require_once('modules/Reports/SavedReport.php');
        $allowedModules = getACLAllowedModules();
        $this->assertTrue(!empty($allowedModules['Trackers']));
        $this->assertTrue(!empty($allowedModules['TrackerSessions']));
        $this->assertTrue(!empty($allowedModules['TrackerPerfs']));
        $this->assertTrue(!empty($allowedModules['TrackerQueries']));  		
    }
    
}
	
?>