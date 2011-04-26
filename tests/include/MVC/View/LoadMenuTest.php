<?php 
require_once('include/MVC/View/SugarView.php');

class LoadMenuTest extends Sugar_PHPUnit_Framework_TestCase
{   
    protected $_moduleName;
    
    public function setUp() 
	{
		global $mod_strings, $app_strings;
		$mod_strings = return_module_language($GLOBALS['current_language'], 'Accounts');
		$app_strings = return_application_language($GLOBALS['current_language']);	
		
		// create a dummy module directory
		$this->_moduleName = 'TestModule'.mt_rand();
		
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
        
        sugar_mkdir("modules/{$this->_moduleName}",null,true);
	}
	
	public function tearDown() 
	{
		unset($GLOBALS['mod_strings']);
		unset($GLOBALS['app_strings']);
        
		SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
		unset($GLOBALS['current_user']);
        
		if ( is_dir("modules/{$this->_moduleName}") )
		    rmdir_recursive("modules/{$this->_moduleName}");
		if ( is_dir("custom/modules/{$this->_moduleName}") )
		    rmdir_recursive("custom/modules/{$this->_moduleName}");
	}
	
	public function testMenuDoesNotExists()
	{
        $view = new SugarView;
        $module_menu = $view->getMenu($this->_moduleName);
        $this->assertTrue(empty($module_menu),'Assert the module menu array is empty');
	}
	
	public function testMenuExistsCanFindModuleMenu()
	{
	    // Create module menu
        if( $fh = @fopen("modules/{$this->_moduleName}/Menu.php", 'w+') ) {
	        $string = <<<EOQ
<?php
\$module_menu[]=Array("index.php?module=Import&action=bar&import_module=Accounts&return_module=Accounts&return_action=index","Foo","Foo", 'Accounts');
?>
EOQ;
            fputs( $fh, $string);
            fclose( $fh );
        }
        
        $view = new SugarView;
        $module_menu = $view->getMenu($this->_moduleName);
        $found_custom_menu = false;
        foreach ($module_menu as $menu_entry) {
        	foreach ($menu_entry as $menu_item) {
        		if (preg_match('/action=bar/', $menu_item)) {
        		   $found_custom_menu = true;
        		}
        	}
        }
        $this->assertTrue($found_custom_menu, "Assert that menu was detected");
	}

    /**
     * @group bug29114
     */
    public function testMenuExistsCanFindModuleExtMenu()
    {
        // Create module ext menu
        sugar_mkdir("custom/modules/{$this->_moduleName}/Ext/Menus/",null,true);
        if( $fh = @fopen("custom/modules/{$this->_moduleName}/Ext/Menus/menu.ext.php", 'w+') ) {
	        $string = <<<EOQ
<?php
\$module_menu[]=Array("index.php?module=Import&action=foo&import_module=Accounts&return_module=Accounts&return_action=index","Foo","Foo", 'Accounts');
?>
EOQ;
            fputs( $fh, $string);
            fclose( $fh );
        }
        
        $view = new SugarView;
        $module_menu = $view->getMenu($this->_moduleName);
        $found_custom_menu = false;
        foreach ($module_menu as $key => $menu_entry) {
        	foreach ($menu_entry as $id => $menu_item) {
        		if (preg_match('/action=foo/', $menu_item)) {
        		   $found_custom_menu = true;
        		}
        	}
        }
        $this->assertTrue($found_custom_menu, "Assert that custom menu was detected");
    }

    /**
     * @group bug38935
     */
    public function testMenuExistsCanFindModuleExtMenuWhenModuleMenuDefinedGlobal()
    {
        // Create module ext menu
        sugar_mkdir("custom/modules/{$this->_moduleName}/Ext/Menus/",null,true);
        if( $fh = @fopen("custom/modules/{$this->_moduleName}/Ext/Menus/menu.ext.php", 'w+') ) {
	        $string = <<<EOQ
<?php
global \$module_menu;
\$module_menu[]=Array("index.php?module=Import&action=foo&import_module=Accounts&return_module=Accounts&return_action=index","Foo","Foo", 'Accounts');
?>
EOQ;
            fputs( $fh, $string);
            fclose( $fh );
        }
        
        $view = new SugarView;
        $module_menu = $view->getMenu($this->_moduleName);
        $found_custom_menu = false;
        foreach ($module_menu as $key => $menu_entry) {
        	foreach ($menu_entry as $id => $menu_item) {
        		if (preg_match('/action=foo/', $menu_item)) {
        		   $found_custom_menu = true;
        		}
        	}
        }
        $this->assertTrue($found_custom_menu, "Assert that custom menu was detected");
    }    
    
    public function testMenuExistsCanFindApplicationExtMenu()
	{
	    // Create module ext menu
	    $backupCustomMenu = false;
	    if ( !is_dir("custom/application/Ext/Menus/") )
	        sugar_mkdir("custom/application/Ext/Menus/",null,true);
        if (file_exists('custom/application/Ext/Menus/menu.ext.php')) {
	        copy('custom/application/Ext/Menus/menu.ext.php', 'custom/application/Ext/Menus/menu.ext.php.backup');
	        $backupCustomMenu = true;
	    }
	    
        if ( $fh = @fopen("custom/application/Ext/Menus/menu.ext.php", 'w+') ) {
	        $string = <<<EOQ
<?php
\$module_menu[]=Array("index.php?module=Import&action=foobar&import_module=Accounts&return_module=Accounts&return_action=index","Foo","Foo", 'Accounts');
?>
EOQ;
            fputs( $fh, $string);
            fclose( $fh );
        }
        
        $view = new SugarView;
        $module_menu = $view->getMenu($this->_moduleName);
        $found_application_custom_menu = false;
        foreach ($module_menu as $key => $menu_entry) {
        	foreach ($menu_entry as $id => $menu_item) {
        		if (preg_match('/action=foobar/', $menu_item)) {
        		   $found_application_custom_menu = true;
        		}
        	}
        }
        $this->assertTrue($found_application_custom_menu, "Assert that application custom menu was detected");
        
        if($backupCustomMenu) {
            copy('custom/application/Ext/Menus/menu.ext.php.backup', 'custom/application/Ext/Menus/menu.ext.php');
            unlink('custom/application/Ext/Menus/menu.ext.php.backup');
        }	
        else
            unlink('custom/application/Ext/Menus/menu.ext.php');
	}

	public function testMenuExistsCanFindModuleMenuAndModuleExtMenu()
	{
	    // Create module menu
        if( $fh = @fopen("modules/{$this->_moduleName}/Menu.php", 'w+') ) {
	        $string = <<<EOQ
<?php
\$module_menu[]=Array("index.php?module=Import&action=foo&import_module=Accounts&return_module=Accounts&return_action=index","Foo","Foo", 'Accounts');
?>
EOQ;
            fputs( $fh, $string);
            fclose( $fh );
        }
        
        // Create module ext menu
        sugar_mkdir("custom/modules/{$this->_moduleName}/Ext/Menus/",null,true);
        if( $fh = @fopen("custom/modules/{$this->_moduleName}/Ext/Menus/menu.ext.php", 'w+') ) {
	        $string = <<<EOQ
<?php
\$module_menu[]=Array("index.php?module=Import&action=bar&import_module=Accounts&return_module=Accounts&return_action=index","Foo","Foo", 'Accounts');
?>
EOQ;
            fputs( $fh, $string);
            fclose( $fh );
        }
        
        $view = new SugarView;
        $module_menu = $view->getMenu($this->_moduleName);
        $found_custom_menu = false;
        $found_menu = false;
        foreach ($module_menu as $key => $menu_entry) {
        	foreach ($menu_entry as $id => $menu_item) {
        		if (preg_match('/action=foo/', $menu_item)) {
        		   $found_menu = true;
        		}
        		if (preg_match('/action=bar/', $menu_item)) {
        		   $found_custom_menu = true;
        		}
        	}
        }
        $this->assertTrue($found_menu, "Assert that menu was detected");
        $this->assertTrue($found_custom_menu, "Assert that custom menu was detected");
	}
}

class ViewLoadMenuTest extends SugarView
{
    public function menuExists(
        $module
        )
    {
        return $this->_menuExists($module);
    }
}