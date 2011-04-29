<?php 
require_once('include/SugarFolders/SugarFolders.php');

/**
 * @ticket 33404
 */
class Bug33906Test extends Sugar_PHPUnit_Framework_TestCase
{
    protected $folder = null;
    protected $_user = null;
    
	public function setUp()
    {
        global $current_user, $currentModule;

        $this->_user = SugarTestUserUtilities::createAnonymousUser();
		 $GLOBALS['current_user'] = $this->_user;
		$this->folder = new SugarFolder(); 
	}

    public function tearDown()
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
        
        $GLOBALS['db']->query("DELETE FROM folders_subscriptions WHERE assigned_user_id='{$this->_user->id}'");
        $GLOBALS['db']->query("DELETE FROM folders_subscriptions WHERE folder_id='{$this->folder->id}'");
        $GLOBALS['db']->query("DELETE FROM folders WHERE id='{$this->folder->id}'");
        
        unset($this->folder);
    }
    
	public function testSaveFolderNoSubscriptions()
	{
	    global $current_user;
	    $this->folder->save();

	    $result = $GLOBALS['db']->query("SELECT count(*) as cnt FROM folders_subscriptions where folder_id='{$this->folder->id}'");
	    $rs = $GLOBALS['db']->fetchByAssoc($result);

	    $this->assertGreaterThan(0, $rs['cnt'], "Could not create folder subscriptions properly." );
    }
    
	public function testSaveFolderWithSubscriptions()
	{
        global $current_user;
	    $this->folder->save(FALSE);

	    $result = $GLOBALS['db']->query("SELECT count(*) as cnt FROM folders_subscriptions where folder_id='{$this->folder->id}'");
	    $rs = $GLOBALS['db']->fetchByAssoc($result);

	    $this->assertEquals(0, $rs['cnt'], "Created folder subscriptions when none should have been created." );
    }
}