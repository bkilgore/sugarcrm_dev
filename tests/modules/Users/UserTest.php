<?php
require_once 'modules/Users/User.php';

class UserTest extends Sugar_PHPUnit_Framework_TestCase
{
	protected $_user = null;

	public function setUp() 
    {
    	$this->_user = SugarTestUserUtilities::createAnonymousUser();
    	$GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
	}
	
	public function tearDown()
	{
	    unset($GLOBALS['current_user']);
	    SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
	}

	public function testSettingAUserPreference() 
    {
        $this->_user->setPreference('test_pref','dog');

        $this->assertEquals('dog',$this->_user->getPreference('test_pref'));
    }
    
    public function testGettingSystemPreferenceWhenNoUserPreferenceExists()
    {
        $GLOBALS['sugar_config']['somewhackypreference'] = 'somewhackyvalue';
        
        $result = $this->_user->getPreference('somewhackypreference');
        
        unset($GLOBALS['sugar_config']['somewhackypreference']);
        
        $this->assertEquals('somewhackyvalue',$result);
    }
    
    /**
     * @ticket 42667
     */
    public function testGettingSystemPreferenceWhenNoUserPreferenceExistsForEmailDefaultClient()
    {
        if ( isset($GLOBALS['sugar_config']['email_default_client']) ) {
            $oldvalue = $GLOBALS['sugar_config']['email_default_client'];
        }
        $GLOBALS['sugar_config']['email_default_client'] = 'somewhackyvalue';
        
        $result = $this->_user->getPreference('email_link_type');
        
        if ( isset($oldvalue) ) {
            $GLOBALS['sugar_config']['email_default_client'] = $oldvalue;
        }
        else {
            unset($GLOBALS['sugar_config']['email_default_client']);
        }
        
        $this->assertEquals('somewhackyvalue',$result);
    }
    
    public function testResetingUserPreferences()
    {
        $this->_user->setPreference('test_pref','dog');

        $this->_user->resetPreferences();
        
        $this->assertNull($this->_user->getPreference('test_pref'));
    }
    
    /**
     * @ticket 36657
     */
    public function testCertainPrefsAreNotResetWhenResetingUserPreferences()
    {
        $this->_user->setPreference('ut','1');
        $this->_user->setPreference('timezone','GMT');

        $this->_user->resetPreferences();
        
        $this->assertEquals('1',$this->_user->getPreference('ut'));
        $this->assertEquals('GMT',$this->_user->getPreference('timezone'));
    }

    public function testDeprecatedUserPreferenceInterface()
    {
        User::setPreference('deprecated_pref','dog',0,'global',$this->_user);
        
        $this->assertEquals('dog',User::getPreference('deprecated_pref','global',$this->_user));
    }
    
    public function testSavingToMultipleUserPreferenceCategories()
    {
        $this->_user->setPreference('test_pref1','dog',0,'cat1');
        $this->_user->setPreference('test_pref2','dog',0,'cat2');
        
        $this->_user->savePreferencesToDB();
        
        $this->assertEquals(
            'cat1',
            $GLOBALS['db']->getOne("SELECT category FROM user_preferences WHERE assigned_user_id = '{$this->_user->id}' AND category = 'cat1'")
            );
        
        $this->assertEquals(
            'cat2',
            $GLOBALS['db']->getOne("SELECT category FROM user_preferences WHERE assigned_user_id = '{$this->_user->id}' AND category = 'cat2'")
            );
    }
}

