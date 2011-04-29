<?php
require_once 'ModuleInstall/ModuleScanner.php';

class ModuleScannerTest extends Sugar_PHPUnit_Framework_TestCase
{
    var $fileLoc;
    
	public function setUp()
	{
        $this->fileLoc = "cache/moduleScannerTemp.php";
	}

	public function tearDown()
	{
		if (is_file($this->fileLoc))
			unlink($this->fileLoc);
	}
	
	public function testFileTemplatePass() 
    {
        
    	$fileModContents = <<<EOQ
<?PHP
require_once('include/SugarObjects/templates/file/File.php');

class testFile_sugar extends File {
	function fileT_testFiles_sugar(){	
		parent::File();
		\$this->file = new File();
		\$file = "file";
	}
}
?>
EOQ;
		file_put_contents($this->fileLoc, $fileModContents);
		$ms = new ModuleScanner();
		$errors = $ms->scanFile($this->fileLoc);
		$this->assertTrue(empty($errors));
    }
    
	public function testFileFunctionFail() 
    {
        
    	$fileModContents = <<<EOQ
<?PHP
require_once('include/SugarObjects/templates/file/File.php');

class testFile_sugar extends File {
	function fileT_testFiles_sugar(){	
		parent::File();
		\$this->file = new File();
		\$file = file('test.php');
		
	}
}
?>
EOQ;
		file_put_contents($this->fileLoc, $fileModContents);
		$ms = new ModuleScanner();
		$errors = $ms->scanFile($this->fileLoc);
		$this->assertTrue(!empty($errors));
    }
    
	public function testCallUserFunctionFail() 
    {
        
    	$fileModContents = <<<EOQ
<?PHP
	call_user_func("sugar_file_put_contents", "test2.php", "test");
?>
EOQ;
		file_put_contents($this->fileLoc, $fileModContents);
		$ms = new ModuleScanner();
		$errors = $ms->scanFile($this->fileLoc);
		$this->assertTrue(!empty($errors));
    }
    
    

}
