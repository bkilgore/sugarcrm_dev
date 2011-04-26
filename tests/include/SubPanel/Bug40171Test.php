<?php

require_once('data/SugarBean.php');
require_once('modules/Contacts/Contact.php');
require_once('include/SubPanel/SubPanel.php');
require_once('include/SubPanel/SubPanel.php');
require_once('include/SubPanel/SubPanelDefinitions.php');

/**
 * @ticket 41853
 * @ticket 40171
 */
class Bug40171Test extends Sugar_PHPUnit_Framework_TestCase 
{   	
    protected $bean;

	public function setUp()
	{
	    global $moduleList, $beanList, $beanFiles;
        require('include/modules.php');
	    $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
        $this->bean = new Contact();
	}

	public function tearDown()
	{
		SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);

        foreach ($this->filename_check as $filename) {
            @unlink($filename);
        }
  		require_once('ModuleInstall/ModuleInstaller.php');
  		$moduleInstaller = new ModuleInstaller();
  		$moduleInstaller->silent = true; // make sure that the ModuleInstaller->log() function doesn't echo while rebuilding the layoutdefs
  		$moduleInstaller->rebuild_layoutdefs();
	}

    public function testSubpanelOverride()
    {
        // Create Subpanel 1
        $subpanel_1 = array(
            'order' => 100,
            'module' => 'Cases',
            'subpanel_name' => 'default',
            'sort_order' => 'asc',
            'sort_by' => 'id',
            'title_key' => 'LBL_CONTACTS_CASES_1_FROM_CASES_TITLE',
            'get_subpanel_data' => 'contacts_cases_1',
            'top_buttons' => 
            array (
                0 => array (
                      'widget_class' => 'SubPanelTopButtonQuickCreate',
                ),
                1 => array (
                    'widget_class' => 'SubPanelTopSelectButton',
                    'mode' => 'MultiSelect',
                ),
            ),
        );
        $subpanel_list_fields_1['list_fields'] = array (
            'priority' => 
            array (
                'type' => 'enum',
                'vname' => 'LBL_PRIORITY',
                'sortable' => false,
                'width' => '10%',
                'default' => true,
            ),
        );
        $subpanel_def_1 = new aSubPanel("contacts_cases_1", $subpanel_1, $this->bean);
        $subpanel_1 = new SubPanel('Contacts', 'fab4', $subpanel_def_1->_instance_properties['subpanel_name'], $subpanel_def_1);
        $subpanel_1->saveSubPanelDefOverride($subpanel_def_1, 'list_fields', $subpanel_list_fields_1);

  		$path_1     = 'custom/modules/'. $subpanel_def_1->_instance_properties['module'] . '/metadata/subpanels';
  		$filename_1 = $subpanel_def_1->parent_bean->object_name . "_subpanel_" . $subpanel_def_1->name;
  		$extname_1  = '_override'.$subpanel_def_1->parent_bean->object_name . "_subpanel_" . $subpanel_def_1->name;
  	
        // Create SubPane 2
        $subpanel_2 = array(
            'order' => 100,
            'module' => 'Cases',
            'subpanel_name' => 'default',
            'sort_order' => 'asc',
            'sort_by' => 'id',
            'title_key' => 'LBL_CONTACTS_CASES_2_FROM_CASES_TITLE',
            'get_subpanel_data' => 'contacts_cases_2',
            'top_buttons' => 
            array (
                0 => array (
                      'widget_class' => 'SubPanelTopButtonQuickCreate',
                ),
                1 => array (
                    'widget_class' => 'SubPanelTopSelectButton',
                    'mode' => 'MultiSelect',
                ),
            ),
        );
        $subpanel_list_fields_2 = array (
            'case_number' => 
            array (
                'vname' => 'LBL_LIST_NUMBER',
                'width' => '6%',
                'default' => true,
            ),
        );
        $subpanel_def_2 = new aSubPanel("contacts_cases_2", $subpanel_2, $this->bean);
        $subpanel_2 = new SubPanel('Contacts', 'fab4', $subpanel_def_2->_instance_properties['subpanel_name'], $subpanel_def_2);
        $subpanel_2->saveSubPanelDefOverride($subpanel_def_2, 'list_fields', $subpanel_list_fields_2);

  		$path_2     = 'custom/modules/'. $subpanel_def_2->_instance_properties['module'] . '/metadata/subpanels';
  		$filename_2 = $subpanel_def_1->parent_bean->object_name . "_subpanel_" . $subpanel_def_2->name;
  		$extname_2  = '_override'.$subpanel_def_1->parent_bean->object_name . "_subpanel_" . $subpanel_def_2->name;
  		
        // Check files genertaed by subpanel overriding : layout override and subpanel overire
        $this->filename_check[] = 'custom/Extension/modules/'. $subpanel_def_1->parent_bean->module_dir . "/Ext/Layoutdefs/$extname_1.php";
        $this->assertTrue(file_exists(end($this->filename_check)));
        $this->filename_check[] = $path_1.'/' . $filename_1 .'.php';
        $this->assertTrue(file_exists(end($this->filename_check)));
        $this->filename_check[] = 'custom/Extension/modules/'. $subpanel_def_2->parent_bean->module_dir . "/Ext/Layoutdefs/$extname_2.php";
        $this->assertTrue(file_exists(end($this->filename_check)));
        $this->filename_check[] = $path_2.'/' . $filename_2 .'.php';
        $this->assertTrue(file_exists(end($this->filename_check)));

        // laout_defs are reloaded in saveSubPanelDefOverride method, we lauched it
        global $layout_defs;

        // Check override_subpanel_name are differents
        $this->assertTrue(isset($layout_defs['Contacts']['subpanel_setup']['contacts_cases_1']['override_subpanel_name']));
        $this->assertTrue(isset($layout_defs['Contacts']['subpanel_setup']['contacts_cases_2']['override_subpanel_name']));
        $this->assertNotEquals($layout_defs['Contacts']['subpanel_setup']['contacts_cases_1']['override_subpanel_name'], $layout_defs['Contacts']['subpanel_setup']['contacts_cases_2']['override_subpanel_name']);

    }


}
