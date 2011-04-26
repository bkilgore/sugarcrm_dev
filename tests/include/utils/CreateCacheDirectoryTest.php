<?php
require_once 'include/utils/file_utils.php';

class CreateCacheDirectoryTest extends Sugar_PHPUnit_Framework_TestCase
{
    private $_original_cwd = '';
    
    public function setUp() 
    {
        $this->_original_cwd = getcwd();
        chdir(dirname(__FILE__));
        $this->_removeCacheDirectory('./cache');
    }

    public function tearDown() 
    {
        $this->_removeCacheDirectory('./cache');
        chdir($this->_original_cwd);
    }

    private function _removeCacheDirectory($dir)
    {
        $dir_handle = @opendir($dir);
        if ($dir_handle === false) {
            return;
        }
        while (($filename = readdir($dir_handle)) !== false) {
            if ($filename == '.' || $filename == '..') {
                continue;
            }
            if (is_dir("{$dir}/{$filename}")) {
                $this->_removecacheDirectory("{$dir}/{$filename}");
            } else {
                unlink("{$dir}/{$filename}");
            }
        }
        closedir($dir_handle);
        rmdir("{$dir}");
    }

    public function testCreatesCacheDirectoryIfDoesnotExist()
    {
        $this->assertFalse(file_exists('./cache'), 'check that the cache directory does not exist');
        create_cache_directory('foobar');
        $this->assertTrue(file_exists('./cache'), 'creates a cache directory');
    }

    public function testCreatesDirectoryInCacheDirectoryProvidedItIsGivenAFile()
    {
        $this->assertFalse(file_exists('./cache/foobar-test'));
        create_cache_directory('foobar-test/cache-file.php');
        $this->assertTrue(file_exists('./cache/foobar-test'));
    }

    public function testReturnsDirectoryCreated()
    {
        $created = create_cache_directory('foobar/cache-file.php');
        $this->assertEquals(
            'cache/foobar/cache-file.php',
            $created
        );
    }
}

