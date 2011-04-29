<?php

require_once('modules/UpgradeWizard/uw_utils.php');

class Bug42622Test extends Sugar_PHPUnit_Framework_TestCase 
{
	var $has_notification_studio_file = false;
	var $studio_file = 'modules/Notifications/metadata/studio.php';
	var $backup_file;
	
	public function setUp() 
	{
		
		if(file_exists($this->studio_file))
		{
		   //This really shouldn't be happening, but just in case...
		   $this->has_notification_studio_file = true;
		   $this->backup_file = $this->studio_file . '.' . gmmktime() . '.bak';
		   copy($this->studio_file, $this->backup_file);
		} else {
		   if(!file_exists('modules/Notifications/metadata'))
		   {
		   	  mkdir_recursive('modules/Notifications/metadata');
		   }
		   //Create the test file
		   write_array_to_file("test", array(), $this->studio_file);
		}
	}
	
	public function tearDown() 
	{
		if($this->has_notification_studio_file)
		{
		   copy($this->backup_file, $this->studio_file);
		   unlink($this->backup_file);
		} else {
		   if(file_exists($this->studio_file))
		   {
		   		unlink($this->studio_file);
		   }
		}
	}

	public function testUnlinkUpgradeFilesPre620()
	{
		$this->assertTrue(file_exists($this->studio_file), 'Assert the ' . $this->studio_file . ' exists');
		unlinkUpgradeFiles('613');
		$this->assertFalse(file_exists($this->studio_file), 'Assert the ' . $this->studio_file . ' no longer exists');
	}	
	
	public function testUnlinkUpgradeFilesPost620()
	{
		$this->assertTrue(file_exists($this->studio_file), 'Assert the ' . $this->studio_file . ' exists');
		unlinkUpgradeFiles('620');
		$this->assertTrue(file_exists($this->studio_file), 'Assert the ' . $this->studio_file . ' still exists (post 620)');
	}
}