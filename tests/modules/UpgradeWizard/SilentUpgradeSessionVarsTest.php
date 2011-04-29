<?php

class SilentUpgradeSessionVarsTest extends Sugar_PHPUnit_Framework_TestCase 
{
    private $externalTestFileName = 'test_silent_upgrade_vars.php';
    
    public function setUp() 
    {
        $this->writeExternalTestFile();
    }

    public function tearDown() 
    {
        $this->removeExternalTestFile();
    }

    public function testSilentUpgradeSessionVars()
    {
    	
    	require_once('modules/UpgradeWizard/uw_utils.php');
    	
    	$varsCacheFileName = "{$GLOBALS['sugar_config']['cache_dir']}/silentUpgrader/silentUpgradeCache.php";
        
    	$loaded = loadSilentUpgradeVars();
    	$this->assertTrue($loaded, "Could not load the silent upgrade vars");
    	global $silent_upgrade_vars_loaded;
    	$this->assertTrue(!empty($silent_upgrade_vars_loaded), "\$silent_upgrade_vars_loaded array should not be empty");
    	
    	$set = setSilentUpgradeVar('SDizzle', 'BSnizzle');
    	$this->assertTrue($set, "Could not set a silent upgrade var");
    	
    	$get = getSilentUpgradeVar('SDizzle');
    	$this->assertEquals('BSnizzle', $get, "Unexpected value when getting silent upgrade var before resetting");
    	
    	$write = writeSilentUpgradeVars();
    	$this->assertTrue($write, "Could not write the silent upgrade vars to the cache file. Function returned false");
    	$this->assertFileExists($varsCacheFileName, "Cache file doesn't exist after call to writeSilentUpgradeVars()");
    	
    	$output = shell_exec("php {$this->externalTestFileName}");
    	
    	$this->assertEquals('BSnizzle', $output, "Running custom script didn't successfully retrieve the value");
    	
    	$remove = removeSilentUpgradeVarsCache();
    	$this->assertTrue(empty($silent_upgrade_vars_loaded), "Silent upgrade vars variable should have been unset in removeSilentUpgradeVarsCache() call");
    	$this->assertFileNotExists($varsCacheFileName, "Cache file exists after call to removeSilentUpgradeVarsCache()");
    	
    	$get = getSilentUpgradeVar('SDizzle');
    	$this->assertNotEquals('BSnizzle', $get, "Unexpected value when getting silent upgrade var after resetting");
    }
    
    private function writeExternalTestFile()
    {
        $externalTestFileContents = <<<EOQ
<?php
        
        define('sugarEntry', true);
        require_once('include/entryPoint.php');
        require_once('modules/UpgradeWizard/uw_utils.php');
        
        \$get = getSilentUpgradeVar('SDizzle');
        
        echo \$get;
EOQ;
        
        file_put_contents($this->externalTestFileName, $externalTestFileContents);
    }
    
    private function removeExternalTestFile()
    {
        if(file_exists($this->externalTestFileName))
        {
            unlink($this->externalTestFileName);
        }
    }
}
?>
