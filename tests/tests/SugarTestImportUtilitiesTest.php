<?php

class SugarTestImportUtilitiesTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function tearDown() 
    {
        SugarTestImportUtilities::removeAllCreatedFiles();
    }

    public function testCanCreateFile() 
    {
        $filename = SugarTestImportUtilities::createFile();

        $this->assertTrue(is_file($filename));
        $fp = fopen($filename,"r");
        $i = 0;
        $buffer = '';
        while (!feof($fp)) {
            $columns = $buffer;
            $buffer = fgetcsv($fp, 4096);
            if ( $buffer !== false )
                $i++;
        }
        fclose($fp);
        $this->assertEquals($i,2000);
        $this->assertEquals(count($columns),3);
    }

    public function testCanCreateFileAndSpecifyLines() 
    {
        $filename = SugarTestImportUtilities::createFile(1);

        $this->assertTrue(is_file($filename));
        $fp = fopen($filename,"r");
        $i = 0;
        $buffer = '';
        while (!feof($fp)) {
            $columns = $buffer;
            $buffer = fgetcsv($fp, 4096);
            if ( $buffer !== false )
                $i++;
        }
        fclose($fp);
        $this->assertEquals($i,1);
        $this->assertEquals(count($columns),3);
    }
    
    public function testCanCreateFileAndSpecifyLinesAndColumns() 
    {
        $filename = SugarTestImportUtilities::createFile(2,5);

        $this->assertTrue(is_file($filename));
        $fp = fopen($filename,"r");
        $i = 0;
        $buffer = '';
        while (!feof($fp)) {
            $columns = $buffer;
            $buffer = fgetcsv($fp, 4096);
            if ( $buffer !== false )
                $i++;
        }
        fclose($fp);
        $this->assertEquals($i,2);
        $this->assertEquals(count($columns),5);
    }

    public function testCanRemoveAllCreatedFiles() 
    {
        $filesCreated = array();
        
        for ($i = 0; $i < 5; $i++) 
            $filesCreated[] = SugarTestImportUtilities::createFile();
        $filesCreated[] = $filesCreated[4].'-0';
        
        SugarTestImportUtilities::removeAllCreatedFiles();
        
        foreach ( $filesCreated as $filename )
            $this->assertFalse(is_file($filename));
    }
}

