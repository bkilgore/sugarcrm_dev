<?php 
require_once('include/SugarFolders/SugarFolders.php');
require_once('modules/InboundEmail/InboundEmail.php');

/**
 * @group bug33404
 */
class AutoCreateImportFolderTest extends Sugar_PHPUnit_Framework_TestCase
{
	var $folder_id = null;
	var $folder_obj = null;
	var $ie = null;
    var $_user = null;
    
    
	public function setUp()
    {
        global $current_user, $currentModule;

        $this->_user = SugarTestUserUtilities::createAnonymousUser();
        $GLOBALS['current_user'] = $this->_user;
        
		$this->folder = new SugarFolder(); 
		$this->ie = new InboundEmail();
	}

    public function tearDown()
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
        
        $GLOBALS['db']->query("DELETE FROM folders WHERE id='{$this->folder_id}'");
        
        unset($this->ie);
    }
    
	function testAutoImportFolderCreation(){
	    global $current_user;
	   
    	$this->ie->name = "Sugar Test";
    	$this->folder_id = $this->ie->createAutoImportSugarFolder();
	    $this->folder_obj = new SugarFolder();
	    $this->folder_obj->retrieve($this->folder_id);
		
		$this->assertEquals($this->ie->name, $this->folder_obj->name, "Could not create folder for Inbound Email auto folder creation" );
    	$this->assertEquals(0, $this->folder_obj->has_child, "Could not create folder for Inbound Email auto folder creation" );
        $this->assertEquals(1, $this->folder_obj->is_group, "Could not create folder for Inbound Email auto folder creation" );
        $this->assertEquals($this->_user->id, $this->folder_obj->assign_to_id, "Could not create folder for Inbound Email auto folder creation" );
        
	}
}
?>