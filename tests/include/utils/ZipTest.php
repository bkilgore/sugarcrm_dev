<?php
require_once('include/utils/zip_utils.php');
/**
 * @ticket 40957
 */
class ZipTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        sugar_mkdir('tests/include/utils/ziptest/testarchive',null,true);
        sugar_touch('tests/include/utils/ziptest/testarchive/testfile1.txt');
        sugar_touch('tests/include/utils/ziptest/testarchive/testfile2.txt');
        sugar_touch('tests/include/utils/ziptest/testarchive/testfile3.txt');
        sugar_mkdir('tests/include/utils/ziptest/testarchiveoutput',null,true);
    }
    
    public function tearDown()
    {
        if ( is_dir('tests/include/utils/ziptest/') )
            rmdir_recursive('tests/include/utils/ziptest/');
    }
    
    public function testZipADirectory()
	{
		zip_dir('tests/include/utils/ziptest/testarchive','tests/include/utils/ziptest/testarchive.zip');
		
		$this->assertTrue(file_exists('tests/include/utils/ziptest/testarchive.zip'));
	}
	
	public function testZipADirectoryFailsWhenDirectorySpecifedDoesNotExists()
	{
	    $this->assertFalse(zip_dir('tests/include/utils/ziptest/notatestarchive','tests/include/utils/ziptest/testarchive.zip'));
	}
	
	/**
     * @depends testZipADirectory
     */
    public function testExtractEntireArchive()
	{
	    zip_dir('tests/include/utils/ziptest/testarchive','tests/include/utils/ziptest/testarchive.zip');
		unzip('tests/include/utils/ziptest/testarchive.zip','tests/include/utils/ziptest/testarchiveoutput');
	    
	    $this->assertTrue(file_exists('tests/include/utils/ziptest/testarchiveoutput/testfile1.txt'));
	    $this->assertTrue(file_exists('tests/include/utils/ziptest/testarchiveoutput/testfile2.txt'));
	    $this->assertTrue(file_exists('tests/include/utils/ziptest/testarchiveoutput/testfile3.txt'));
	}
	
	/**
     * @depends testZipADirectory
     */
    public function testExtractSingleFileFromAnArchive()
	{
	    zip_dir('tests/include/utils/ziptest/testarchive','tests/include/utils/ziptest/testarchive.zip');
		unzip_file('tests/include/utils/ziptest/testarchive.zip','testfile1.txt','tests/include/utils/ziptest/testarchiveoutput');
	    
	    $this->assertTrue(file_exists('tests/include/utils/ziptest/testarchiveoutput/testfile1.txt'));
	    $this->assertFalse(file_exists('tests/include/utils/ziptest/testarchiveoutput/testfile2.txt'));
	    $this->assertFalse(file_exists('tests/include/utils/ziptest/testarchiveoutput/testfile3.txt'));
	}
	
	/**
     * @depends testZipADirectory
     */
    public function testExtractTwoIndividualFilesFromAnArchive()
	{
	    zip_dir('tests/include/utils/ziptest/testarchive','tests/include/utils/ziptest/testarchive.zip');
		unzip_file('tests/include/utils/ziptest/testarchive.zip',array('testfile2.txt','testfile3.txt'),'tests/include/utils/ziptest/testarchiveoutput');
	    
	    $this->assertFalse(file_exists('tests/include/utils/ziptest/testarchiveoutput/testfile1.txt'));
	    $this->assertTrue(file_exists('tests/include/utils/ziptest/testarchiveoutput/testfile2.txt'));
	    $this->assertTrue(file_exists('tests/include/utils/ziptest/testarchiveoutput/testfile3.txt'));
	}
	
	public function testExtractFailsWhenArchiveDoesNotExist()
	{
	    $this->assertFalse(unzip('tests/include/utils/ziptest/testarchivenothere.zip','tests/include/utils/ziptest/testarchiveoutput'));
	}
	
	public function testExtractFailsWhenExtractDirectoryDoesNotExist()
	{
	    $this->assertFalse(unzip('tests/include/utils/ziptest/testarchive.zip','tests/include/utils/ziptest/testarchiveoutputnothere'));
	}
	
	public function testExtractFailsWhenFilesDoNotExistInArchive()
	{
	    $this->assertFalse(unzip_file('tests/include/utils/ziptest/testarchive.zip','testfile4.txt','tests/include/utils/ziptest/testarchiveoutput'));
	}
}
