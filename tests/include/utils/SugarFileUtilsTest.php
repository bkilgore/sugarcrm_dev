<?php
require_once 'include/utils/file_utils.php';

class SugarFileUtilsTest extends Sugar_PHPUnit_Framework_TestCase 
{
    private $_filename;
    private $_old_default_permissions;
    
    public function setUp() 
    {	
        if (is_windows())
            $this->markTestSkipped('Skipping on Windows');
        
        $this->_filename = realpath(dirname(__FILE__).'/../../../cache/').'file_utils_override'.mt_rand().'.txt';
        touch($this->_filename);
        $this->_old_default_permissions = $GLOBALS['sugar_config']['default_permissions'];
        $GLOBALS['sugar_config']['default_permissions'] =
            array (
                'dir_mode' => 0777,
                'file_mode' => 0660,
                'user' => $this->_getCurrentUser(),
                'group' => $this->_getCurrentGroup(),
              );
    }
    
    public function tearDown() 
    {
        if(file_exists($this->_filename)) {
            unlink($this->_filename);
        }
        $GLOBALS['sugar_config']['default_permissions'] = $this->_old_default_permissions;
        SugarConfig::getInstance()->clearCache();
    }
    
    private function _getCurrentUser()
    {
        if ( function_exists('posix_getuid') ) {
            return posix_getuid();
        }
        return '';
    }
    
    private function _getCurrentGroup()
    {
        if ( function_exists('posix_getgid') ) {
            return posix_getgid();
        }
        return '';
    }
    
    private function _getTestFilePermissions()
    {
        return substr(sprintf('%o', fileperms($this->_filename)), -4);
    }
    
    public function testSugarTouch()
    {
        $this->assertTrue(sugar_touch($this->_filename));
    }
    
    public function testSugarTouchWithTime()
    {
        $time = filemtime($this->_filename);
        
        $this->assertTrue(sugar_touch($this->_filename, $time));
        
        $this->assertEquals($time,filemtime($this->_filename));
    }
    
    public function testSugarTouchWithAccessTime()
    {
        $time  = filemtime($this->_filename);
        $atime = gmmktime();
        
        $this->assertTrue(sugar_touch($this->_filename, $time, $atime));
        
        $this->assertEquals($time,filemtime($this->_filename));
        $this->assertEquals($atime,fileatime($this->_filename));
    }
    
    public function testSugarChmod()
    {
    	return true;
        $this->assertTrue(sugar_chmod($this->_filename));
        $this->assertEquals($this->_getTestFilePermissions(),decoct(get_mode()));
    }
    
    public function testSugarChmodWithMode()
    {
        $mode = 0411;
        $this->assertTrue(sugar_chmod($this->_filename, $mode));
        
        $this->assertEquals($this->_getTestFilePermissions(),decoct($mode));
    }
    
    public function testSugarChmodNoDefaultMode()
    {
        $GLOBALS['sugar_config']['default_permissions']['file_mode'] = null;
        $this->assertFalse(sugar_chmod($this->_filename));
    }
    
    public function testSugarChmodDefaultModeNotAnInteger()
    {
        $GLOBALS['sugar_config']['default_permissions']['file_mode'] = '';
        $this->assertFalse(sugar_chmod($this->_filename));
    }
    
    public function testSugarChmodDefaultModeIsZero()
    {
        $GLOBALS['sugar_config']['default_permissions']['file_mode'] = 0;
        $this->assertFalse(sugar_chmod($this->_filename));
    }
    
    public function testSugarChmodWithModeNoDefaultMode()
    {
        $GLOBALS['sugar_config']['default_permissions']['file_mode'] = null;
        $mode = 0411;
        $this->assertTrue(sugar_chmod($this->_filename, $mode));
        
        $this->assertEquals($this->_getTestFilePermissions(),decoct($mode));
    }
    
    public function testSugarChmodWithModeDefaultModeNotAnInteger()
    {
        $GLOBALS['sugar_config']['default_permissions']['file_mode'] = '';
        $mode = 0411;
        $this->assertTrue(sugar_chmod($this->_filename, $mode));
        
        $this->assertEquals($this->_getTestFilePermissions(),decoct($mode));
    }
    
    public function testSugarChown()
    {
        $this->assertTrue(sugar_chown($this->_filename));
        $this->assertEquals(fileowner($this->_filename),$this->_getCurrentUser());
    }
    
    public function testSugarChownWithUser()
    {
        $this->assertTrue(sugar_chown($this->_filename,$this->_getCurrentUser()));
        $this->assertEquals(fileowner($this->_filename),$this->_getCurrentUser());
    }
    
    public function testSugarChownNoDefaultUser()
    {
        $GLOBALS['sugar_config']['default_permissions']['user'] = '';
        
        $this->assertFalse(sugar_chown($this->_filename));
    }
    
    public function testSugarChownWithUserNoDefaultUser()
    {
        $GLOBALS['sugar_config']['default_permissions']['user'] = '';
        
        $this->assertTrue(sugar_chown($this->_filename,$this->_getCurrentUser()));
        
        $this->assertEquals(fileowner($this->_filename),$this->_getCurrentUser());
    }
}
?>
