<?php
require_once 'modules/Import/ImportFile.php';

class ImportFileTest extends Sugar_PHPUnit_Framework_TestCase
{
	public function setUp()
	{
    	$GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
    }
    
    public function tearDown()
    {
        SugarTestImportUtilities::removeAllCreatedFiles();
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
    }
    
    /**
 	 * @ticket 23380
 	 */
	public function testFileImportNoEnclosers()
    {
    	$file = SugarTestImportUtilities::createFile(2,1);
    	$importFile = new ImportFile($file,',','');
        $row = $importFile->getNextRow();
        $this->assertEquals($row, array('foo00'));
        $row = $importFile->getNextRow();
        $this->assertEquals($row,array('foo10'));
    }
    
    public function testLoadNonExistantFile()
    {
        $importFile = new ImportFile($GLOBALS['sugar_config']['import_dir'].'/thisfileisntthere'.date("YmdHis").'.csv',',','"');
        $this->assertFalse($importFile->fileExists());
    }
    
    public function testLoadGoodFile()
    {
        $file = SugarTestImportUtilities::createFile(2,1);
        $importFile = new ImportFile($file,',','"');
        $this->assertTrue($importFile->fileExists());
    }
    
    /**
     * @ticket 39494
     */
    public function testLoadFileWithByteOrderMark()
    {
        $importFile = new ImportFile('tests/modules/Import/Bug39494ImportFile.txt',"\t",'',false);
        $this->assertTrue($importFile->fileExists());
        $row = $importFile->getNextRow();
        $this->assertEquals($row,array('name','city'));
        $row = $importFile->getNextRow();
        $this->assertEquals($row,array('tester1','wuhan'));
    }
    
    public function testGetNextRow()
    {
        $file = SugarTestImportUtilities::createFile(3,2);
        $importFile = new ImportFile($file,',','"');
        
        $row = $importFile->getNextRow();
        $this->assertEquals(array("foo00","foo01"),$row);
        $row = $importFile->getNextRow();
        $this->assertEquals(array("foo10","foo11"),$row);
        $row = $importFile->getNextRow();
        $this->assertEquals(array("foo20","foo21"),$row);
    }
    
    /**
 	 * @ticket 41361
 	 */
    public function testGetNextRowWithEOL()
    {
        $file = SugarTestImportUtilities::createFileWithEOL(1, 1);
        $importFile = new ImportFile($file,',','"');
        $row = $importFile->getNextRow();
        // both \r\n and \n should be properly replaced with PHP_EOL
        $this->assertEquals(array("start0".PHP_EOL."0".PHP_EOL."end"), $row);
    }
    
    public function testLoadEmptyFile()
    {
        $emptyFile = $GLOBALS['sugar_config']['import_dir'].'/empty'.date("YmdHis").'.csv';
        file_put_contents($emptyFile,'');
        
        $importFile = new ImportFile($emptyFile,',','"',false);
        
        $this->assertFalse($importFile->getNextRow());
        
        $importFile = new ImportFile($emptyFile,',','',false);
        
        $this->assertFalse($importFile->getNextRow());
        
        @unlink($emptyFile);
    }
    
    public function testDeleteFileOnDestroy()
    {
        $file = SugarTestImportUtilities::createFile(3,2);
        $importFile = new ImportFile($file,',','"',true);
        
        unset($importFile);
        
        $this->assertFalse(is_file($file));
    }
    
    public function testNotDeleteFileOnDestroy()
    {
        $file = SugarTestImportUtilities::createFile(3,2);
        $importFile = new ImportFile($file,',','"',false);
        
        unset($importFile);
        
        $this->assertTrue(is_file($file));
    }
    
    public function testGetFieldCount()
    {
        $file = SugarTestImportUtilities::createFile(3,2);
        $importFile = new ImportFile($file,',','"');
        
        $importFile->getNextRow();
        $this->assertEquals($importFile->getFieldCount(),2);
    }
    
    public function testMarkRowAsDuplicate()
    {
        $file = SugarTestImportUtilities::createFile(3,2);
        $importFile = new ImportFile($file,',','"');
        
        $row = $importFile->getNextRow();
        $importFile->markRowAsDuplicate();
        
        $fp = sugar_fopen(ImportCacheFiles::getDuplicateFileName(),'r');
        $duperow = fgetcsv($fp);
        fclose($fp);
        
        $this->assertEquals($row,$duperow);
    }
    
    public function testWriteError()
    {
        $file = SugarTestImportUtilities::createFile(3,2);
        $importFile = new ImportFile($file,',','"');
        
        $row = $importFile->getNextRow();
        $importFile->writeError('Some Error','field1','foo');
        
        $fp = sugar_fopen(ImportCacheFiles::getErrorFileName(),'r');
        $errorrow = fgetcsv($fp);
        fclose($fp);
        
        $this->assertEquals(array('Some Error','field1','foo',1),$errorrow);
        
        $fp = sugar_fopen(ImportCacheFiles::getErrorRecordsFileName(),'r');
        $errorrecordrow = fgetcsv($fp);
        fclose($fp);
        
        $this->assertEquals($row,$errorrecordrow);
    }
    
    public function testWriteErrorRecord()
    {
        $file = SugarTestImportUtilities::createFile(3,2);
        $importFile = new ImportFile($file,',','"');
        
        $row = $importFile->getNextRow();
        $importFile->writeErrorRecord();
        
        $fp = sugar_fopen(ImportCacheFiles::getErrorRecordsFileName(),'r');
        $errorrecordrow = fgetcsv($fp);
        fclose($fp);
        
        $this->assertEquals($row,$errorrecordrow);
    }
    
    public function testWriteStatus()
    {
        $file = SugarTestImportUtilities::createFile(3,2);
        $importFile = new ImportFile($file,',','"');
        
        $importFile->getNextRow();
        $importFile->writeError('Some Error','field1','foo');
        $importFile->getNextRow();
        $importFile->markRowAsDuplicate();
        $importFile->getNextRow();
        $importFile->markRowAsImported();
        $importFile->writeStatus();
        
        $fp = sugar_fopen(ImportCacheFiles::getStatusFileName(),'r');
        $statusrow = fgetcsv($fp);
        fclose($fp);
        
        $this->assertEquals(array(3,1,1,1,0,$file),$statusrow);
    }
    
    public function testWriteStatusWithTwoErrorsInOneRow()
    {
        $file = SugarTestImportUtilities::createFile(3,2);
        $importFile = new ImportFile($file,',','"');
        
        $row = $importFile->getNextRow();
        $importFile->writeError('Some Error','field1','foo');
        $importFile->writeError('Some Error','field1','foo');
        $importFile->getNextRow();
        $importFile->markRowAsImported();
        $importFile->getNextRow();
        $importFile->markRowAsImported();
        $importFile->writeStatus();
        
        $fp = sugar_fopen(ImportCacheFiles::getStatusFileName(),'r');
        $statusrow = fgetcsv($fp);
        fclose($fp);
        
        $this->assertEquals(array(3,1,0,2,0,$file),$statusrow);
        
        $fp = sugar_fopen(ImportCacheFiles::getErrorRecordsFileName(),'r');
        $errorrecordrow = fgetcsv($fp);
        
        $this->assertEquals($row,$errorrecordrow);
        $this->assertFalse(fgetcsv($fp),'Should be only 1 record in the csv file');
        fclose($fp);
        
    }
    
    public function testWriteStatusWithTwoUpdatedRecords()
    {
        $file = SugarTestImportUtilities::createFile(3,2);
        $importFile = new ImportFile($file,',','"');
        
        $row = $importFile->getNextRow();
        $importFile->markRowAsImported(false);
        $importFile->getNextRow();
        $importFile->markRowAsImported();
        $importFile->getNextRow();
        $importFile->markRowAsImported();
        $importFile->writeStatus();
        
        $fp = sugar_fopen(ImportCacheFiles::getStatusFileName(),'r');
        $statusrow = fgetcsv($fp);
        fclose($fp);
        
        $this->assertEquals(array(3,0,0,2,1,$file),$statusrow);
    }
    
    public function testWriteRowToLastImport()
    {
        $file = SugarTestImportUtilities::createFile(3,2);
        $importFile = new ImportFile($file,',','"');
        $record = $importFile->writeRowToLastImport("Tests","Test","TestRunner");
        
        $query = "SELECT * 
                        FROM users_last_import
                        WHERE assigned_user_id = '{$GLOBALS['current_user']->id}'
                            AND import_module = 'Tests'
                            AND bean_type = 'Test'
                            AND bean_id = 'TestRunner'
                            AND id = '$record'
                            AND deleted=0";

		$result = $GLOBALS['db']->query($query);
        
        $this->assertNotNull($GLOBALS['db']->fetchByAssoc($result));
        
        $query = "DELETE FROM users_last_import
                        WHERE assigned_user_id = '{$GLOBALS['current_user']->id}'
                            AND import_module = 'Tests'
                            AND bean_type = 'Test'
                            AND bean_id = 'TestRunner'
                            AND id = '$record'
                            AND deleted=0";
        $GLOBALS['db']->query($query);
    }
}
