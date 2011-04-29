<?php 
require_once('modules/Documents/DocumentSoap.php');

class Bug43560Test extends Sugar_PHPUnit_Framework_TestCase
{
	var $doc = null;
	
	public function setUp()
    {
        global $current_user, $currentModule ;
		$mod_strings = return_module_language($GLOBALS['current_language'], "Documents");
		$current_user = SugarTestUserUtilities::createAnonymousUser();

		$document = new Document();
        $document->document_name = 'Bug 43560 Test Document';
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
	
	function testRevisionSave() {
        $ret = $GLOBALS['db']->query("SELECT COUNT(*) AS rowcount FROM document_revisions WHERE document_id = '{$this->doc->id}'");
        $row = $GLOBALS['db']->fetchByAssoc($ret);
        $this->assertEquals($row['rowcount'],0,'We created an empty revision');

        $ret = $GLOBALS['db']->query("SELECT document_revision_id FROM documents WHERE id = '{$this->doc->id}'");
        $row = $GLOBALS['db']->fetchByAssoc($ret);
        $this->assertTrue(empty($row['document_revision_id']),'We linked the document to a fake document_revision');
        
        $ds = new DocumentSoap();
        $revision_stuff = array('file' => base64_encode('Pickles has an extravagant beard of pine fur.'), 'filename' => 'a_file_about_pickles.txt', 'id' => $this->doc->id, 'revision' => '1');
        $revisionId = $ds->saveFile($revision_stuff);

        $ret = $GLOBALS['db']->query("SELECT COUNT(*) AS rowcount FROM document_revisions WHERE document_id = '{$this->doc->id}'");
        $row = $GLOBALS['db']->fetchByAssoc($ret);
        $this->assertEquals($row['rowcount'],1,'We didn\'t create a revision when we should have');
        
        $ret = $GLOBALS['db']->query("SELECT document_revision_id FROM documents WHERE id = '{$this->doc->id}'");
        $row = $GLOBALS['db']->fetchByAssoc($ret);
        $this->assertEquals($row['document_revision_id'],$revisionId,'We didn\'t link the newly created document revision to the document');

        // Double saving doesn't work because save doesn't reset the new_with_id
        $newDoc = new Document();
        $newDoc->retrieve($this->doc->id);

        $newDoc->document_revision_id = $revisionId;
        $newDoc->save(FALSE);

        $ret = $GLOBALS['db']->query("SELECT COUNT(*) AS rowcount FROM document_revisions WHERE document_id = '{$this->doc->id}'");
        $row = $GLOBALS['db']->fetchByAssoc($ret);
        $this->assertEquals($row['rowcount'],1,'We didn\'t create a revision when we should have');
        
        $ret = $GLOBALS['db']->query("SELECT document_revision_id FROM documents WHERE id = '{$this->doc->id}'");
        $row = $GLOBALS['db']->fetchByAssoc($ret);
        $this->assertEquals($row['document_revision_id'],$revisionId,'We didn\'t link the newly created document revision to the document');


	}

}
