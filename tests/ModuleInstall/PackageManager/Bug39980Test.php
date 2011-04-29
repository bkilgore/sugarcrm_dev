<?php
require_once 'ModuleInstall/PackageManager/PackageManager.php';

class Bug39980Test extends Sugar_PHPUnit_Framework_TestCase
{
	
    public function tearDown()
    {
        if (is_file(Bug39980PackageManger::$manifest_location))
            unlink(Bug39980PackageManger::$manifest_location);
    }

    public function testGetinstalledPackagesUninstallable()
    {  
    	$pm = new Bug39980PackageManger();
    	$pm->extractManifest(0, 0);
    	$packs = $pm->getinstalledPackages();
    	//Its confusing, but "UNINSTALLABLE" in file_install means the package is NOT uninstallable
    	$this->assertEquals("UNINSTALLABLE", $packs[0]['file_install']);
    }

}

class Bug39980PackageManger extends PackageManager {
	static $manifest_location = "cache/Bug39980manifest.php";
	
	public function __construct() {
	   parent::__construct();
	   $this->manifest_content = <<<EOQ
<?php
\$manifest = array (
         'acceptable_sugar_versions' => 
          array (
            '6.1.0'
          ),
          'acceptable_sugar_flavors' =>
          array(
            'ENT'
          ),
          'readme'=>'',
          'key'=>'tf1',
          'author' => '',
          'description' => '',
          'icon' => '',
          'is_uninstallable' => false,
          'name' => 'test_file_1',
          'published_date' => '2010-10-20 22:10:01',
          'type' => 'module',
          'version' => '1287612601',
          'remove_tables' => 'prompt',
          );
\$installdefs = array (
  'id' => 'asdfqq',
  'copy' => 
  array (
     0 => array (
      'from' => '<basepath>/Extension/modules/Cases/Ext/Vardefs/dummy_extension2.php',
      'to' => 'custom/Extension/modules/Cases/Ext/Vardefs/dummy_extension2.php',
    ),
  ),
);    
        
EOQ;
	}

	public function getInstalled($types)
	{
		include($this->extractManifest(0,0));
		$sm = array(
		    'manifest'         => (isset($manifest) ? $manifest : ''),
            'installdefs'      => (isset($installdefs) ? $installdefs : ''),
            'upgrade_manifest' => (isset($upgrade_manifest) ? $upgrade_manifest : '')
		);
		return array (
			(object) array(
			    'filename' => Bug39980PackageManger::$manifest_location,
			    'manifest' => base64_encode(serialize($sm)),
			    'date_entered' => '1/1/2010',
			    'new_schema' => '1',
			    'module_dir' => 'Administration' ,
			    'id' => 'b4d22740-4e96-65b3-b712-4ca230d95987' ,
			    'md5sum' => 'fe221d731d8c624f15712878300aa907' ,
			    'type' => 'module' ,
			    'version' => '1285697780' ,
			    'status' => 'installed' ,
			    'name' => 'test_file_1' ,
			    'description' => '' ,
			    'id_name' => 'tf1' ,
			    'enabled' => true ,
			)
		);
	}
	
	public function extractManifest($filename, $base_tmp_upgrade_dir)
	{
	   if (!is_file(Bug39980PackageManger::$manifest_location))
	       file_put_contents(Bug39980PackageManger::$manifest_location, $this->manifest_content);
	   
	   return Bug39980PackageManger::$manifest_location;
	}
}