<?php
require_once 'modules/Import/ImportFile.php';
require_once 'modules/Import/ImportFileSplitter.php';

class ImportFileSplitterTest extends Sugar_PHPUnit_Framework_TestCase
{
    protected $_goodFile;
    protected $_badFile;
    
    public function setUp()
    {
    	$GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
    	$this->_goodFile = SugarTestImportUtilities::createFile();
		$this->_badFile  = $GLOBALS['sugar_config']['import_dir'].'thisfileisntthere'.date("YmdHis");
		$this->_whiteSpaceFile  = SugarTestImportUtilities::createFileWithWhiteSpace();
    }
    
    public function tearDown()
    {
        SugarTestImportUtilities::removeAllCreatedFiles();
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
    }
    
    public function testLoadNonExistantFile()
    {
        $importFileSplitter = new ImportFileSplitter($this->_badFile);
        $this->assertFalse($importFileSplitter->fileExists());
    }
    
    public function testLoadGoodFile()
    {
        $importFileSplitter = new ImportFileSplitter($this->_goodFile);
        $this->assertTrue($importFileSplitter->fileExists());
    }
    
    public function testSplitSourceFile()
    {
        $importFileSplitter = new ImportFileSplitter($this->_goodFile);
        $importFileSplitter->splitSourceFile(',','"');
        
        $this->assertEquals($importFileSplitter->getRecordCount(),2000);
        $this->assertEquals($importFileSplitter->getFileCount(),2);
    }
    
    public function testSplitSourceFileNoEnclosure()
    {
        $importFileSplitter = new ImportFileSplitter($this->_goodFile);
        $importFileSplitter->splitSourceFile(',','');
        
        $this->assertEquals($importFileSplitter->getRecordCount(),2000);
        $this->assertEquals($importFileSplitter->getFileCount(),2);
    }
    
    public function testSplitSourceFileWithHeader()
    {
        $importFileSplitter = new ImportFileSplitter($this->_goodFile);
        $importFileSplitter->splitSourceFile(',','"',true);
        
        $this->assertEquals($importFileSplitter->getRecordCount(),1999);
        $this->assertEquals($importFileSplitter->getFileCount(),2);
    }
    
    public function testSplitSourceFileWithThreshold()
    {
        $importFileSplitter = new ImportFileSplitter($this->_goodFile,500);
        $importFileSplitter->splitSourceFile(',','"');
        
        $this->assertEquals($importFileSplitter->getRecordCount(),2000);
        $this->assertEquals($importFileSplitter->getFileCount(),4);
    }
    
    public function testGetSplitFileName()
    {
        $importFileSplitter = new ImportFileSplitter($this->_goodFile);
        $importFileSplitter->splitSourceFile(',','"');
        
        $this->assertEquals($importFileSplitter->getSplitFileName(0),"{$this->_goodFile}-0");
        $this->assertEquals($importFileSplitter->getSplitFileName(1),"{$this->_goodFile}-1");
        $this->assertEquals($importFileSplitter->getSplitFileName(2),false);
    }
	
	/**
	 * @group bug25119
	 */
    public function testTrimSpaces()
    {
        $splitter = new ImportFileSplitter($this->_whiteSpaceFile);
        $splitter->splitSourceFile(',',' ',false);
        
        $csvString = file_get_contents("{$this->_whiteSpaceFile}-0");
        
        $this->assertEquals(
            trim(file_get_contents("{$this->_whiteSpaceFile}-0")),
            trim(file_get_contents("{$this->_whiteSpaceFile}"))
            );
    }
}
