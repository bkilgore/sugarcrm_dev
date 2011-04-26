<?php
require_once('modules/DynamicFields/FieldCases.php');

/**
 * Test cases for URL Field
 */
class RepairCustomFieldsTest extends Sugar_PHPUnit_Framework_TestCase
{
    protected $modulename = 'Accounts';
    protected $objectname = 'Account';
    protected $table_name = 'accounts_cstm';
    protected $seed;

    protected function repairDictionary()
    {

        $this->df->buildCache($this->modulename);
        VardefManager::clearVardef();
        VardefManager::refreshVardefs($this->modulename, $this->objectname);
        $this->seed->field_defs = $GLOBALS['dictionary'][$this->objectname]['fields'];

    }
    
    public function setUp()
    {
        $this->markTestSkipped("Skipping for now...");
        $this->field = get_widget('varchar');
        $this->field->id = $this->modulename.'foo_c';
        $this->field->name = 'foo_c';
        $this->field->vanme = 'LBL_Foo';
        $this->field->comments = NULL;
        $this->field->help = NULL;
        $this->field->custom_module = $this->modulename;
        $this->field->type = 'varchar';
        $this->field->label = 'LBL_FOO';
        $this->field->len = 255;
        $this->field->required = 0;
        $this->field->default_value = NULL;
        $this->field->date_modified = '2009-09-14 02:23:23';
        $this->field->deleted = 0;
        $this->field->audited = 0;
        $this->field->massupdate = 0;
        $this->field->duplicate_merge = 0;
        $this->field->reportable = 1;
        $this->field->importable = 'true';
        $this->field->ext1 = NULL;
        $this->field->ext2 = NULL;
        $this->field->ext3 = NULL;
        $this->field->ext4 = NULL;
        $this->seed = new Account();
        $this->df = new DynamicField($this->modulename);
        $this->df->setup ( $this->seed  ) ;

        $this->field->save ( $this->df ) ;
        $this->db = $GLOBALS['db'];

        $this->repairDictionary();

    }
    
    public function tearDown()
    {
        $this->field->delete ( $this->df ) ;
        if ($this->db->tableExists($this->table_name))
        {
            $this->db->dropTableName($this->table_name);
        }
    }
    
    public function testRepairRemovedFieldNoExecute()
    {
        //Remove the custom column
        $this->db->query("ALTER TABLE {$this->table_name} DROP COLUMN {$this->field->name}");
        //Run repair
        $ret = $this->df->repairCustomFields(false);
        $this->assertRegExp("/MISSING IN DATABASE - {$this->field->name} -  ROW/", $ret);
        $compareFieldDefs = $this->db->getHelper()->get_columns($this->table_name);
        $this->assertArrayNotHasKey($this->field->name, $compareFieldDefs);
    }

    public function testRepairRemovedFieldExecute()
    {
        //Remove the custom column
        $this->db->query("ALTER TABLE {$this->table_name} DROP COLUMN {$this->field->name}");
        //Run repair
        $ret = $this->df->repairCustomFields(true);
        $this->assertRegExp("/MISSING IN DATABASE - {$this->field->name} -  ROW/", $ret);
        $compareFieldDefs = $this->db->getHelper()->get_columns($this->table_name);
        $this->assertArrayHasKey($this->field->name, $compareFieldDefs);
    }

    public function testCreateTableNoExecute()
    {
        //Remove the custom table
        $this->db->dropTableName($this->table_name);
        //Run repair
        $ret = $this->df->repairCustomFields(false);
        //Test that the table is going to be created.
        $this->assertRegExp("/Missing Table: {$this->table_name}/", $ret);
        //Test that the custom field is going to be added.
        $this->assertRegExp("/MISSING IN DATABASE - {$this->field->name} -  ROW/", $ret);
        //Assert that the table was NOT created
        $this->assertFalse($this->db->tableExists($this->table_name),
            "Asserting that the custom table is not created when repair is run with execute set false");
    }

    public function testCreateTableExecute()
    {
        //Remove the custom table
        $this->db->dropTableName($this->table_name);
        //Run repair
        $ret = $this->df->repairCustomFields();
        $this->assertRegExp("/MISSING IN DATABASE - {$this->field->name} -  ROW/", $ret);
        //Test that the table is going to be created.
        $this->assertRegExp("/Missing Table: {$this->table_name}/", $ret);
        //Test that the custom field is going to be added.
        $this->assertRegExp("/MISSING IN DATABASE - {$this->field->name} -  ROW/", $ret);
        //Assert that the table was created
        $this->assertTrue($this->db->tableExists($this->table_name),
            "Asserting that the custom table is created when repair is run with execute not set");
    }
}
