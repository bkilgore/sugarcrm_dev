<?php
require_once('modules/Users/authentication/AuthenticationController.php');

class AuthenticateTest extends Sugar_PHPUnit_Framework_TestCase
{
	protected $_user = null;

	public function setUp() 
    {
    	$GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
    	$this->sugar_config_old = $GLOBALS['sugar_config'];
    	$_REQUEST['user_name'] = 'foo';
    	$_REQUEST['user_password'] = 'bar';
    	$_SESSION['authenticated_user_id'] = true;
    	$_SESSION['hasExpiredPassword'] = false;
    	$_SESSION['isMobile'] = null;
	}
	
	public function tearDown()
	{
	    unset($GLOBALS['current_user']);
	    $GLOBALS['sugar_config'] = $this->sugar_config_old;
	    unset($_REQUEST['login_module']);
        unset($_REQUEST['login_action']);
        unset($_REQUEST['login_record']);
        unset($_REQUEST['user_name']);
        unset($_REQUEST['user_password']);
        unset($_SESSION['authenticated_user_id']);
        unset($_SESSION['hasExpiredPassword']);
        unset($_SESSION['isMobile']);
	}
	
	public function testLoginRedirectIfAuthenicationFails()
	{
	    $_SESSION['authenticated_user_id'] = null;
	    
	    $authController = $this->getMock('AuthenticationController');
        
	    $url = '';
        require('modules/Users/Authenticate.php');
        
        $this->assertEquals(
            'Location: index.php?module=Users&action=Login',
            $url
            );
	}
	
	public function testDefaultAuthenicationRedirect() 
    {
        unset($GLOBALS['sugar_config']['default_module']);
        unset($GLOBALS['sugar_config']['default_action']);
        unset($_REQUEST['login_module']);
        unset($_REQUEST['login_action']);
        unset($_REQUEST['login_record']);
        
        $authController = $this->getMock('AuthenticationController');
        
        $url = '';
        require('modules/Users/Authenticate.php');
        
        $this->assertEquals(
            'Location: index.php?module=Home&action=index',
            $url
            );
    }
    
    public function testDefaultAuthenicationRedirectGivenLoginParameters() 
    {
        unset($GLOBALS['sugar_config']['default_module']);
        unset($GLOBALS['sugar_config']['default_action']);
        $_REQUEST['login_module'] = 'foo';
        $_REQUEST['login_action'] = 'bar';
        $_REQUEST['login_record'] = '123';
        
        $authController = $this->getMock('AuthenticationController');
        
        $url = '';
        require('modules/Users/Authenticate.php');
        
        $this->assertEquals(
            'Location: index.php?module=foo&action=bar&record=123',
            $url
            );
    }
    
    public function testDefaultAuthenicationRedirectGivenDefaultSettings() 
    {
        $GLOBALS['sugar_config']['default_module'] = 'dog';
        $GLOBALS['sugar_config']['default_action'] = 'cat';
        unset($_REQUEST['login_module']);
        unset($_REQUEST['login_action']);
        unset($_REQUEST['login_record']);
        
        $authController = $this->getMock('AuthenticationController');
        
        $url = '';
        require('modules/Users/Authenticate.php');
        
        $this->assertEquals(
            'Location: index.php?module=dog&action=cat',
            $url
            );
    }
    
}
