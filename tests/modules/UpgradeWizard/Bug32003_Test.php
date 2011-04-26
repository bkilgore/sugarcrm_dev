<?php
class Bug32003_Test extends Sugar_PHPUnit_Framework_TestCase {

var $controller;	
var $original_current_user_id;
	
function setUp() {
	
global $dictionary;	
	
$dictionary['iFrame'] = array('table' => 'iframes'
                               ,'fields' => array (
  'id' =>
  array (
    'name' => 'id',
    'vname' => 'LBL_ID',
    'type' => 'id',
    'required'=>true,
  ),
  'name' =>
  array (
    'name' => 'name',
    'vname' => 'LBL_LIST_NAME',
    'type' => 'varchar',
    'len' => '255',
    'required'=>true,
    'importable' => 'required',
  ),
  'url' =>
  array (
    'name' => 'url',
    'vname' => 'LBL_LIST_URL',
    'type' => 'varchar',
    'len' => '255',
    'required'=>true,
    'importable' => 'required',
  ),
  'type' =>
  array (
    'name' => 'type',
    'vname' => 'LBL_LIST_TYPE',
    'type' => 'varchar',
    'len' => '255',
    'required'=>true,
  ),
  'placement' =>
  array (
    'name' => 'placement',
    'vname' => 'LBL_LIST_PLACEMENT',
    'type' => 'varchar',
    'len' => '255',
    'required'=>true,
    'importable' => 'required',
  ),
  'status' =>
  array (
    'name' => 'status',
    'vname' => 'LBL_LIST_STATUS',
    'type' => 'bool',
    'required'=>true,
  ),
  'deleted' =>
  array (
    'name' => 'deleted',
    'vname' => 'LBL_DELETED',
    'type' => 'bool',
    'required'=>true,
  ),
  'date_entered' =>
  array (
    'name' => 'date_entered',
    'vname' => 'LBL_DATE_ENTERED',
    'type' => 'datetime',
    'required'=>true,
  ),
  'date_modified' =>
  array (
    'name' => 'date_modified',
    'vname' => 'LBL_DATE_MODIFIED',
    'type' => 'datetime',
    'required'=>true,
  ),
  'created_by' =>
  array (
    'name' => 'created_by',
    'rname' => 'user_name',
    'id_name' => 'modified_user_id',
    'vname' => 'LBL_ASSIGNED_TO',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id',
    'required'=>true,
  ),
), 
'indices' => array (
       array('name' =>'iframespk', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'idx_cont_name', 'type'=>'index', 'fields'=>array('name','deleted'))
)
);	
	

$dictionary['Feed'] = array('table' => 'feeds', 'comment' => 'RSS Feeds'
                               ,'fields' => array (
  'id' =>
  array (
    'name' => 'id',
    'vname' => 'LBL_ID',
    'type' => 'id',
    'required'=>true,
    'reportable'=>true,
    'comment' => 'Unique identifier'
  ),
  'deleted' =>
  array (
    'name' => 'deleted',
    'vname' => 'LBL_CREATED_BY',
    'type' => 'bool',
    'required'=>true,
    'reportable'=>false,
    'comment' => 'Record deletion indicator'
  ),
  'date_entered' =>
  array (
    'name' => 'date_entered',
    'vname' => 'LBL_DATE_ENTERED',
    'type' => 'datetime',
    'required'=>true,
    'comment' => 'Date record created'
  ),
  'date_modified' =>
  array (
    'name' => 'date_modified',
    'vname' => 'LBL_DATE_MODIFIED',
    'type' => 'datetime',
    'required'=>true,
    'comment' => 'Date record last modified'
  ),
  'modified_user_id' =>
  array (
    'name' => 'modified_user_id',
    'rname' => 'user_name',
    'id_name' => 'modified_user_id',
    'vname' => 'LBL_ASSIGNED_TO',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id',
    'required'=>true,
    'reportable'=>true,
    'comment' => 'User who last modified record'
  ),
  'assigned_user_id' =>
  array (
    'name' => 'assigned_user_id',
    'rname' => 'user_name',
    'id_name' => 'assigned_user_id',
    'vname' => 'LBL_ASSIGNED_TO',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id',
    'reportable'=>true,
    'comment' => 'User assigned to record'
  ),
  'created_by' =>
  array (
    'name' => 'created_by',
    'rname' => 'user_name',
    'id_name' => 'modified_user_id',
    'vname' => 'LBL_ASSIGNED_TO',
    'type' => 'assigned_user_name',
    'table' => 'users',
    'isnull' => 'false',
    'dbType' => 'id',
    'comment' => 'User that created record'
  ),
  'title' =>
  array (
    'name' => 'title',
    'type' => 'varchar',
    'len' => '100',
    'vname' => 'LBL_TITLE',
    'comment' => 'Title of RSS feed'
  ),
  'description' =>
  array (
    'name' => 'description',
    'type' => 'text',
    'vname' => 'LBL_DESCRIPTION',
    'comment' => 'Description of RSS feed'
  ),
  'url' =>
  array (
    'name' => 'url',
    'type' => 'varchar',
    'len' => '255',
    'vname' => 'LBL_URL',
    'comment' => 'URL that represents the RSS feed',
    'importable' => 'required',
  ),
  'created_by_link' =>
  array (
        'name' => 'created_by_link',
    'type' => 'link',
    'relationship' => 'feeds_created_by',
    'vname' => 'LBL_CREATED_BY_USER',
    'link_type' => 'one',
    'module'=>'Users',
    'bean_name'=>'User',
    'source'=>'non-db',
  ),
  'modified_user_link' =>
  array (
        'name' => 'modified_user_link',
    'type' => 'link',
    'relationship' => 'feeds_modified_user',
    'vname' => 'LBL_MODIFIED_BY_USER',
    'link_type' => 'one',
    'module'=>'Users',
    'bean_name'=>'User',
    'source'=>'non-db',
  ),
  'assigned_user_link' =>
  array (
        'name' => 'assigned_user_link',
    'type' => 'link',
    'relationship' => 'feeds_assigned_user',
    'vname' => 'LBL_ASSIGNED_TO_USER',
    'link_type' => 'one',
    'module'=>'Users',
    'bean_name'=>'User',
    'source'=>'non-db',
  ),
)
,
 'relationships' => array (

  'feeds_assigned_user' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Feeds', 'rhs_table'=> 'feeds', 'rhs_key' => 'assigned_user_id',
   'relationship_type'=>'one-to-many')

   ,'feeds_modified_user' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Feeds', 'rhs_table'=> 'feeds', 'rhs_key' => 'modified_user_id',
   'relationship_type'=>'one-to-many')

   ,'feeds_created_by' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Feeds', 'rhs_table'=> 'feeds', 'rhs_key' => 'created_by',
   'relationship_type'=>'one-to-many')
)

                                                      , 'indices' => array (
       array('name' =>'feedspk', 'type' =>'primary', 'fields'=>array('id')),
       array('name' =>'idx_feed_name', 'type'=>'index', 'fields'=>array('title','deleted'))
                                                      )

                            );

VardefManager::createVardef('Feeds','Feed', array(
));
	
	require_once('include/database/DBManagerFactory.php');
	$db = DBManagerFactory::getInstance();
	
    if(!$db->tableExists('iframes')){
       $db->createTableParams('iframes', $dictionary['iFrame']['fields'], $dictionary['iFrame']['indices']);
    }
    
    if(!$db->tableExists('feeds')){
       $db->createTableParams('feeds', $dictionary['Feed']['fields'], $dictionary['Feed']['indices']);
    }    
    
    global $moduleList;
    $moduleList['Feeds'] = 'Feeds';
    $moduleList['iFrames'] = 'iFrames';
    
	require_once("modules/MySettings/TabController.php");
	$this->controller = new TabController(); 

	global $current_user;
	if ( !( $current_user instanceOf User ) ) {
	    $current_user = new User;		
	    $current_user->retrieve('1');   
	}
	$this->original_current_user_id = $current_user->id;
}	

function tearDown() {
	$db = DBManagerFactory::getInstance();
	if($db->tableExists('iframes')) {
		$db->dropTableName('iframes');
	}
	
	if($db->tableExists('feeds')) {
		$db->dropTableName('feeds');
	}	
	
    global $moduleList;
	require_once("modules/MySettings/TabController.php");
	$this->controller = new TabController();  

    global $moduleList;
    $keys = array_flip($moduleList);
    if(in_array('Feeds', $moduleList)) {
       unset($moduleList[$keys['Feeds']]);
    }
    
    if(in_array('iFrames', $moduleList)) {
       unset($moduleList[$keys['iFrames']]);
    }
    
    $this->controller->set_system_tabs($moduleList);

    if(file_exists('custom/Extension/application/Ext/Include/Feed.php')) {
    	unlink('custom/Extension/application/Ext/Include/Feed.php');
    }
    
    if(file_exists('custom/Extension/application/Ext/Include/iFrame.php')) {
    	unlink('custom/Extension/application/Ext/Include/iFrame.php');
    }
    
    global $current_user;
    $current_user->retrieve($this->original_current_user_id);
}


function  disabled_post_install_upgrade_with_iframes() {
	$db = DBManagerFactory::getInstance();
	$db->query('INSERT into iframes(id, name, url) values (\'' . mktime() . '\', \'test\', \'www.test.com\')');
    hide_iframes_and_feeds_modules();
    $this->assertTrue($db->tableExists('iframes'));
    $this->assertTrue(file_exists('custom/Extension/application/Ext/Include/iFrame.php'));
}


function  disabled_post_install_upgrade_without_iframes() {
    hide_iframes_and_feeds_modules();
	$db = DBManagerFactory::getInstance();
    $this->assertTrue(!$db->tableExists('iframes'));  

    $this->assertTrue(!file_exists('custom/Extension/application/Ext/Include/iFrame.php'));
}


function  disabled_post_install_upgrade_with_feeds() {
	$this->markTestSkipped("Skip test_post_install_upgrade_with_feeds");
	$tabs = $this->controller->get_tabs_system();
	echo var_export($tabs, true);
	
	//If it is hidden, set it to show
	if(isset($tabs[1]['Feeds'])) {
	   $db = DBManagerFactory::getInstance();
	   $db->query('DELETE FROM config WHERE category = \'MySettings\' AND name = \'tab\'');
	   unset($tabs[1]['Feeds']);
	   $tabs[0]['Feeds'] = 'Feeds';
	   
	   $administration = new Administration();
	   $serialized = base64_encode(serialize($moduleList));
	   $administration->saveSetting('MySettings', 'tab', $serialized);		   
	   $this->controller->set_system_tabs($tabs);
	}

	/*
	//It's as if this never changes 
	$tabs = $this->controller->get_tabs_system();
	echo var_export($tabs, true);	

    */
    hide_iframes_and_feeds_modules();
    $this->assertTrue($db->tableExists('feeds'));  
    $this->assertTrue(file_exists('custom/Extension/application/Ext/Include/Feed.php'));
}


function  disabled_post_install_upgrade_without_feeds() {
    hide_iframes_and_feeds_modules();
    $db = DBManagerFactory::getInstance();
    $this->assertTrue(!$db->tableExists('feeds'));
    $this->assertTrue(!file_exists('custom/Extension/application/Ext/Include/Feed.php'));
}

/*
 * Added a minimum of 1 test to make sure that 
 */
function test_donothing() {
	echo "";
}

function  disabled_dashlets_module_changed() {
	
	$db = DBManagerFactory::getInstance();
	$query = "SELECT id, contents, assigned_user_id FROM user_preferences WHERE deleted = 0 AND category = 'Home'";
	$result = $db->query($query, true, "Unable to update iFrames and Feeds dashlets!");
	while ($row = $GLOBALS['db']->fetchByAssoc($result)) {
		$content = unserialize(base64_decode($row['contents']));
		$assigned_user_id = $row['assigned_user_id'];
		$record_id = $row['id'];
		$current_user = new User();
        $current_user->retrieve($row['assigned_user_id']);
        
		if(!empty($content['dashlets']) && !empty($content['pages'])){
			$originalDashlets = $content['dashlets'];
			$originalPages = $content['pages'];
			
			//Determine if the original perference has already had the two dashlets or not
			foreach($originalDashlets as $key=>$ds){

				if(!empty($ds['options']['title']) && $ds['options']['title'] == 'LBL_DASHLET_DISCOVER_SUGAR_PRO'){
				   $originalDashlets[$key]['module'] = 'iFrames';
				}
				if(!empty($ds['options']['title']) && $ds['options']['title'] == 'LBL_DASHLET_SUGAR_NEWS'){
				   $originalDashlets[$key]['module'] = 'iFrames';
				}
			}
			
			$current_user->setPreference('dashlets', $originalDashlets, 0, 'Home');
			$current_user->setPreference('pages', $originalPages, 0, 'Home');	
		}
	} //while	
	
	
	hide_iframes_and_feeds_modules();
	$result = $db->query($query, true, "Unable to update iFrames and Feeds dashlets!");
	$not_home_module = false;
	
	while ($row = $db->fetchByAssoc($result)) {
		$content = unserialize(base64_decode($row['contents']));
		$assigned_user_id = $row['assigned_user_id'];
		$record_id = $row['id'];
		$current_user = new User();
        $current_user->retrieve($row['assigned_user_id']);
        
		if(!empty($content['dashlets']) && !empty($content['pages'])){
			$originalDashlets = $content['dashlets'];
			$originalPages = $content['pages'];
			
			//Determine if the original perference has already had the two dashlets or not
			foreach($originalDashlets as $key=>$ds){
				if(!empty($ds['options']['title']) && $ds['options']['title'] == 'LBL_DASHLET_DISCOVER_SUGAR_PRO' && $originalDashlets[$key]['module'] != 'Home') {
				   $not_home_module = true;
				}
				if(!empty($ds['options']['title']) && $ds['options']['title'] == 'LBL_DASHLET_SUGAR_NEWS' && $originalDashlets[$key]['module'] != 'Home'){
				   $not_home_module = true;
				}
			}
		}
	} //while

	$this->assertFalse($not_home_module, 'Assert that dashlet\'s module were correctly set to Home module');
}

}


//BEGIN INLINE METHODS FROM BUILD (post_install.php)

/**
 * hide_iframes_and_feeds_modules
 * This method determines whether or not to hide the iFrames and Feeds module
 * for an upgrade to 551
 */
function hide_iframes_and_feeds_modules() {
	global $path;
	
    _logThis('Updating the iFrames Dashlets', $path);
	$query = "SELECT id, contents, assigned_user_id FROM user_preferences WHERE deleted = 0 AND category = 'Home'";
	$result = $GLOBALS['db']->query($query, true, "Unable to update iFrames and Feeds dashlets!");
	while ($row = $GLOBALS['db']->fetchByAssoc($result)) {
		$content = unserialize(base64_decode($row['contents']));
		$assigned_user_id = $row['assigned_user_id'];
		$record_id = $row['id'];
		$current_user = new User();
        $current_user->retrieve($row['assigned_user_id']);
        
		if(!empty($content['dashlets']) && !empty($content['pages'])){
			$originalDashlets = $content['dashlets'];
			$originalPages = $content['pages'];
			
			//Determine if the original perference has already had the two dashlets or not
			foreach($originalDashlets as $key=>$ds){
				if(!empty($ds['options']['title']) && $ds['options']['title'] == 'LBL_DASHLET_DISCOVER_SUGAR_PRO'){
				   $originalDashlets[$key]['module'] = 'Home';
				}
				if(!empty($ds['options']['title']) && $ds['options']['title'] == 'LBL_DASHLET_SUGAR_NEWS'){
				   $originalDashlets[$key]['module'] = 'Home';
				}
			}
			
			$current_user->setPreference('dashlets', $originalDashlets, 0, 'Home');
			$current_user->setPreference('pages', $originalPages, 0, 'Home');	
		}
	} //while	
	
	$remove_iframes = false;
	$remove_feeds = false;
	
	//Check if we should remove iframes.  Use the count of entries in iframes table
	$result = $GLOBALS['db']->query('SELECT count(id) as total from iframes');
	if(!empty($result)) {
		$row = $GLOBALS['db']->fetchByAssoc($result);
		if($row['total'] == 0) {
		   $remove_iframes = true;
		}
	}
	
	//Check if we should remove Feeds.  We check if the tab is hidden
	require_once("modules/MySettings/TabController.php");
	$controller = new TabController();	
	$tabs = $controller->get_tabs_system();

	//If the Feeds tab is hidden then remove it
	if(!isset($tabs[0]['Feeds'])) {
	   $remove_feeds = true;
	}
	
	if($remove_feeds) {
	   //Remove the modules/Feeds files
	   if(is_dir('modules/Feeds')) {
	   _logThis('Removing the Feeds files', $path);
	   rmdir_recursive('modules/Feeds');
	   }
		
	   //Drop the table
	   _logThis('Removing the Feeds table', $path);
	   $GLOBALS['db']->dropTableName('feeds');
	} else {
	   if(file_exists('modules/Feeds')) {
		   _logThis('Writing Feed.php module to custom/Extension/application/Ext/Include', $path);
		   write_to_modules_ext_php('Feed', 'Feeds', 'modules/Feeds/Feed.php', true);
	   }
	}
	
	if($remove_iframes) {
		//Remove the module/iFrames files
		if(is_dir('modules/iFrames')) {
		_logThis('Removing the iFrames files', $path);
		rmdir_recursive('modules/iFrames');
		}
		
		//Drop the table
		_logThis('Removing the iframes table', $path);
		$GLOBALS['db']->dropTableName('iframes');
	} else {
	   if(file_exists('modules/iFrames')) {
		  _logThis('Writing iFrame.php module to custom/Extension/application/Ext/Include', $path);
		  write_to_modules_ext_php('iFrame', 'iFrames', 'modules/iFrames/iFrame.php', true);
	   }
	}	
}

function write_to_modules_ext_php($module, $class, $path, $show=false) {
	
	global $beanList, $beanFiles;
	include('include/modules.php');
	if(!isset($beanFiles[$module])) {
		$str = "<?php \n //WARNING: The contents of this file are auto-generated\n";

			if(!empty($module) && !empty($class) && !empty($path)){
				$str .= "\$beanList['$module'] = '$class';\n";
				$str .= "\$beanFiles['$class'] = '$path';\n";
				if($show){
					$str .= "\$moduleList[] = '$module';\n";
				}else{
					$str .= "\$modules_exempt_from_availability_check['$module'] = '$module';\n";
					$str .= "\$modInvisList[] = '$module';\n";
				}
			}

		$str.= "\n?>";
		if(!file_exists("custom/Extension/application/Ext/Include")) {
			mkdir_recursive("custom/Extension/application/Ext/Include", true);
		}
		
		$out = sugar_fopen("custom/Extension/application/Ext/Include/{$module}.php", 'w');
		fwrite($out, $str);
		fclose($out);
	}

}

function _logThis($string, $path) {
	//echo $string . "\n";
	//no-opt
}




?>