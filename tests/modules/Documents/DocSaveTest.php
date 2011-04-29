<?php 
require_once('modules/Documents/Document.php');

class DocSaveTest extends Sugar_PHPUnit_Framework_TestCase
{
	var $doc = null;
	
	public function setUp()
    {
        global $current_user, $currentModule ;
		$mod_strings = return_module_language($GLOBALS['current_language'], "Documents");
		$current_user = SugarTestUserUtilities::createAnonymousUser();

		$document = new Document();
		$document->id = uniqid();
        $document->name = 'Test Document';
        $document->save();
		$this->doc = $document;
	}
	
    public function tearDown()
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
        unset($GLOBALS['mod_strings']);
        
        $GLOBALS['db']->query("DELETE FROM documents WHERE id = '{$this->doc->id}'");
        unset($this->doc);
    }
	
	function testDocTypeSaveDefault() {
		// Assert doc type default is 'Sugar'
    	$this->assertEquals($this->doc->doc_type, 'Sugar');
	}

    function testDocTypeSaveDefaultInDb() {
        $query = "SELECT * FROM documents WHERE id = '{$this->doc->id}'";
        $result = $GLOBALS['db']->query($query);
    	while($row = $GLOBALS['db']->fetchByAssoc($result))
		// Assert doc type default is 'Sugar'
    	$this->assertEquals($row['doc_type'], 'Sugar');
	}

}
?>