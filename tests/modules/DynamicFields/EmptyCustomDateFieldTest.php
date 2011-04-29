<?php

require_once 'modules/DynamicFields/templates/Fields/TemplateDate.php';
require_once("modules/ModuleBuilder/controller.php");

/**
 * This is testing a bug where a custom date field whos value was not set would cause a bad SQL query to prevent the other custom fields from saving correctly.
 */
class EmptyCustomDateFieldTest extends Sugar_PHPUnit_Framework_TestCase
{
    protected $targetModule = "Opportunities";
    protected $secondCustomFieldName = "test_custom_c";
    protected $dateFieldDef = array(
        "module" => "ModuleBuilder",
        "action" => "saveField",
        "new_dropdown" => "",
        "to_pdf" => "true",
        "view_module" => "Opportunities",
        "is_update" => "true",
        "type" => "date",
        "name" => "test_date_c",
        "labelValue" => "test date_c",
        "label" => "LBL_TEST_DATE_C",
        "help" => "",
        "comments" => "",
        "default" => "",
        "enforced" => "false",
        "formula" => "",
        "dependency" => "",
        "reportableCheckbox" => "1",
        "reportable" => "1",
        "importable" => "true",
        "duplicate_merge" => "0",
    );

    protected $extraFieldDef = array(
        "module" => "ModuleBuilder",
        "action" => "saveField",
        "new_dropdown" => "",
        "to_pdf" => "true",
        "view_module" => "Opportunities",
        "is_update" => "true",
        "type" => "varchar",
        "name" => "test_custom_c",
        "labelValue" => "test custom_c",
        "label" => "LBL_TEST_CUSTOM_C",
        "help" => "",
        "comments" => "",
        "default" => "",
        "len" => "255",
        "orig_len" => "255",
        "enforced" => "false",
        "formula" => "",
        "dependency" => "",
        "reportableCheckbox" => "1",
        "reportable" => "1",
        "importable" => "true",
        "duplicate_merge" => "0",
    );

    protected $testOpp;

    public function setUp()
    {
        $this->markTestSkipped('causes all sorts of damage downhill');
        $GLOBALS['app_list_strings'] = return_app_list_strings_language($GLOBALS['current_language']);
        $beanList = array();
        $beanFiles = array();
        require('include/modules.php');
        $GLOBALS['beanList'] = $beanList;
        $GLOBALS['beanFiles'] = $beanFiles;
        $GLOBALS['current_user'] = SugarTestUserUtilities::createAnonymousUser();
        $GLOBALS['current_user']->is_admin = 1;
        $mbc = new ModuleBuilderController();
        //Create the new Fields
        $_REQUEST = $this->dateFieldDef;
        $mbc->action_SaveField();
        $_REQUEST = $this->extraFieldDef;
        $mbc->action_SaveField();

    }

    public function tearDown()
    {
        /*$mbc = new ModuleBuilderController();
        $_REQUEST = array(
            "module" => "ModuleBuilder",
            "action" => "DeleteField",
            "to_pdf" => "true",
            "view_module" => "Opportunities",
            "is_update" => "true",
            "type" => "date",
            "name" => "test_date_c",
            "label" => "LBL_TEST_DATE_C",
            "type" => "date",
            "name" => "test_date_c",
            "labelValue" => "test date_c",
            "label" => "LBL_TEST_DATE_C",
            "help" => "",
            "comments" => "",
            "default" => "",
            "enforced" => "false",
            "formula" => "",
            "dependency" => "",
            "reportableCheckbox" => "1",
            "reportable" => "1",
            "importable" => "true",
            "duplicate_merge" => "0",
        );
        $mbc->action_DeleteField();
        $_REQUEST = array(
            "module" => "ModuleBuilder",
            "action" => "DeleteField",
            "to_pdf" => "true",
            "view_module" => "Opportunities",
            "is_update" => "true",
            "type" => "date",
            "name" => "test_custom_c",
            "label" => "LBL_TEST_DATE_C",
            "type" => "varchar",
            "name" => "test_custom_c",
            "labelValue" => "test custom_c",
            "label" => "LBL_TEST_CUSTOM_C",
            "help" => "",
            "comments" => "",
            "default" => "",
            "len" => "255",
            "orig_len" => "255",
            "enforced" => "false",
            "formula" => "",
            "dependency" => "",
            "reportableCheckbox" => "1",
            "reportable" => "1",
            "importable" => "true",
            "duplicate_merge" => "0",
        );
        $mbc->action_DeleteField();
        
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
        unset($GLOBALS['current_user']);
        unset($GLOBALS['beanList']);
        unset($GLOBALS['beanFiles']);
        unset($GLOBALS['app_list_strings']);
        
        if (!empty($this->testOpp)) {
            $this->testOpp->mark_deleted($this->testOpp->id);
        }*/
    }

    public function testSaveCustomDateField()
    {

        $this->testOpp = new Opportunity();
        $this->testOpp->test_custom_c = "This should save";
        $this->testOpp->save(false);

        $this->testOpp->retrieve($this->testOpp->id);
        $this->assertEquals("This should save", $this->testOpp->test_custom_c);
    }

}

?>