<?php

require_once('install/install_utils.php');
require_once('modules/UpgradeWizard/uw_utils.php');

class Bug37214Test extends Sugar_PHPUnit_Framework_TestCase {

var $original_argv;
var $has_original_config_si_file;
var $current_working_dir;

public function setUp() {
	global $argv;
	if(isset($argv))
	{
		$this->original_argv = $argv;
	}
	
		
	$this->current_working_dir = getcwd();
	
	if(file_exists('config.php'))
	{
	   copy('config.php', 'config.php.bug37214');
	}	
	
	if(file_exists($this->current_working_dir . DIRECTORY_SEPARATOR . 'config_si.php'))
	{
	   $this->has_original_config_si_file = true;
	   copy($this->current_working_dir . DIRECTORY_SEPARATOR . 'config_si.php', $this->current_working_dir . DIRECTORY_SEPARATOR . 'config_si.php.bug37214');
	} else {
	   $this->has_original_config_si_file = false;
 	   copy('config.php', $this->current_working_dir . DIRECTORY_SEPARATOR . 'config_si.php');		
	}
	
	$sugar_config_si = array(	
		'disable_count_query' => true,
		'external_cache_disabled_apc' => true,
		'external_cache_disabled_zend' => true,
		'external_cache_disabled_memcache' => true,
		'external_cache_disabled' => true,
	);
	
	write_array_to_file("sugar_config_si", $sugar_config_si, $this->current_working_dir . DIRECTORY_SEPARATOR . 'config_si.php');
}

public function tearDown() {
	if(isset($this->original_argv))
	{
		global $argv;
		$argv = $this->original_argv;
	}
	
	if(file_exists('config.php.bug37214'))
	{
	   copy('config.php.bug37214', 'config.php');
	   unlink('config.php.bug37214');
	}		
	
	if(file_exists($this->current_working_dir . DIRECTORY_SEPARATOR . 'config_si.php.bug37214'))
	{
	   if($this->has_original_config_si_file) 
	   {
	   	  copy($this->current_working_dir . DIRECTORY_SEPARATOR . 'config_si.php.bug37214', $this->current_working_dir . DIRECTORY_SEPARATOR . 'config_si.php');
	   } else {
	   	  unlink($this->current_working_dir . DIRECTORY_SEPARATOR . 'config_si.php');
	   }
	   unlink($this->current_working_dir . DIRECTORY_SEPARATOR . 'config_si.php.bug37214');
	}
	else {
	    unlink($this->current_working_dir . DIRECTORY_SEPARATOR . 'config_si.php');
	}
}
	

public function test_silent_upgrade_parameters() {
	if(!file_exists('config.php'))
	{
		$this->markTestSkipped('Unable to locate config.php file.  Skipping test.');
		return;
	}

	
	if(!file_exists($this->current_working_dir . DIRECTORY_SEPARATOR . 'config_si.php'))
	{
		$this->markTestSkipped('Unable to locate config_si.php file.  Skipping test.');
		return;
	}	

	//Simulate silent upgrade arguments
	global $argv;
	$argv[0] = $this->current_working_dir . DIRECTORY_SEPARATOR . 'config.php'; //This would really be silentUpgrade.php, but this will suffice
	$argv[1] = $this->current_working_dir . DIRECTORY_SEPARATOR . 'someZipFile.php';
	$argv[2] = $this->current_working_dir . DIRECTORY_SEPARATOR . 'silent_upgrade.log';
	$argv[3] = $this->current_working_dir;
	$argv[4] = 'admin';
	
	$merge_result = merge_config_si_settings();
	
	include('config.php');
	//echo var_export($sugar_config, true);
	$this->assertEquals(true, $sugar_config['disable_count_query'], "Assert disable_count_query is set to true.");
	$this->assertEquals(true, $sugar_config['external_cache_disabled_apc'], "Assert external_cache_disabled_apc is set to true.");
	$this->assertEquals(true, $sugar_config['external_cache_disabled_zend'], "Assert external_cache_disabled_zend is set to true.");
	$this->assertEquals(true, $sugar_config['external_cache_disabled_memcache'], "Assert external_cache_disabled_memcache is set to true.");
	$this->assertEquals(true, $sugar_config['external_cache_disabled'], "Assert external_cache_disabled is set to true.");
}


/**
 * test_silent_upgrade_parameters2
 * This is similar to test_silent_upgrade_parameters except that $argv[0] simulates the current directory
 * (imagine the caes of something like >php silentUpgrade.php xxx yyy zzz).  This is to prove that the
 * merge_config_si_settings() can correctly determine the presence of the config_si.php file given the
 * current directory.
 * 
 */
public function test_silent_upgrade_parameters2() {
	
	if(!file_exists('config.php'))
	{
		$this->markTestSkipped('Unable to locate config.php file.  Skipping test.');
		return;
	}

	
	if(!file_exists($this->current_working_dir . DIRECTORY_SEPARATOR . 'config_si.php'))
	{
		$this->markTestSkipped('Unable to locate config_si.php file.  Skipping test.');
		return;
	}	

	//Simulate silent upgrade arguments
	global $argv;
	$argv[0] = 'config.php'; //This would really be silentUpgrade.php, but this will suffice
	$argv[1] = $this->current_working_dir . DIRECTORY_SEPARATOR . 'someZipFile.php';
	$argv[2] = $this->current_working_dir . DIRECTORY_SEPARATOR . 'silent_upgrade.log';
	$argv[3] = $this->current_working_dir;
	$argv[4] = 'admin';
	
	$merge_result = merge_config_si_settings(false);
	//$this->assertEquals(true, $merge_result, "Assert that we have merged values");
	
	include('config.php');
	//echo var_export($sugar_config, true);
	$this->assertEquals(true, $sugar_config['disable_count_query'], "Assert disable_count_query is set to true.");
	$this->assertEquals(true, $sugar_config['external_cache_disabled_apc'], "Assert external_cache_disabled_apc is set to true.");
	$this->assertEquals(true, $sugar_config['external_cache_disabled_zend'], "Assert external_cache_disabled_zend is set to true.");
	$this->assertEquals(true, $sugar_config['external_cache_disabled_memcache'], "Assert external_cache_disabled_memcache is set to true.");
	$this->assertEquals(true, $sugar_config['external_cache_disabled'], "Assert external_cache_disabled is set to true.");
}


}

?>