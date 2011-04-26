<?php
require_once 'modules/Import/ImportCacheFiles.php';

class ImportCacheFilesTest extends Sugar_PHPUnit_Framework_TestCase
{
    public function setUp() 
    {
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
    }
    
    public function tearDown() 
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
    }
    
    public function testgetDuplicateFileName()
    {
        $filename = ImportCacheFiles::getDuplicateFileName();
        
        $this->assertEquals(
            "{$GLOBALS['sugar_config']['import_dir']}dupes_{$GLOBALS['current_user']->id}.csv", $filename);
    }
    
    public function testgetErrorFileName()
    {
        $filename = ImportCacheFiles::getErrorFileName();
        
        $this->assertEquals(
            "{$GLOBALS['sugar_config']['import_dir']}error_{$GLOBALS['current_user']->id}.csv", $filename);
    }
    
    public function testgetStatusFileName()
    {
        $filename = ImportCacheFiles::getStatusFileName();
        
        $this->assertEquals(
            "{$GLOBALS['sugar_config']['import_dir']}status_{$GLOBALS['current_user']->id}.csv", $filename);
    }
    
    public function testclearCacheFiles()
    {
        // make sure there is a file in there
        file_put_contents(ImportCacheFiles::getStatusFileName(),'foo');
        
        ImportCacheFiles::clearCacheFiles();
        
        $this->assertFalse(is_file(ImportCacheFiles::getStatusFileName()));
    }
}
