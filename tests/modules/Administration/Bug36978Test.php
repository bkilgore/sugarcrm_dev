<?php

class Bug36978Test extends Sugar_PHPUnit_Framework_TestCase {

var $rel_guid;	
var $has_custom_table_dictionary;	
var $moduleList;

function setUp() {
	
    if(true) {
       $this->markTestSkipped("Skipping unless otherwise specified");
       return;
    }	
	
    $admin = new User();
    $GLOBALS['current_user'] = $admin->retrieve('1');	
	
    $GLOBALS['app_list_strings'] = return_app_list_strings_language('en_us');
    
    //Create the custom relationships
    if(!file_exists('custom/Extension/modules/abc_Test/Ext/Vardefs')) {
       mkdir_recursive('custom/Extension/modules/abc_Test/Ext/Vardefs');
    }

    if(!file_exists('custom/Extension/modules/abc_Test/Ext/Layoutdefs')) {
       mkdir_recursive('custom/Extension/modules/abc_Test/Ext/Layoutdefs');
    }    
    
    if(!file_exists('modules/abc_Test/metadata')) {
       mkdir_recursive('modules/abc_Test/metadata');
    }
    
    if( $fh = @fopen('modules/abc_Test/metadata/studio.php', 'w+') )
    {
$string = <<<EOQ
\$GLOBALS['studioDefs']['abc_Test'] = array(

);
EOQ;
       fputs( $fh, $string);
       fclose( $fh );
    }
    
    if( $fh = @fopen('custom/Extension/modules/abc_Test/Ext/Vardefs/test.php', 'w+') )
    {
$string = <<<EOQ

<?php
\$dictionary["abc_Test"]["fields"]["abc_test_abc_test"] = array (
  'name' => 'abc_test_abc_test',
  'type' => 'link',
  'relationship' => 'abc_test_abc_test',
  'source' => 'non-db',
  'side' => 'right',
  'vname' => 'LBL_ABC_TEST_ABC_TEST_FROM_ABC_TEST_L_TITLE',
);
?>
<?php
\$dictionary["abc_Test"]["fields"]["abc_test_abc_test_name"] = array (
  'name' => 'abc_test_abc_test_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_ABC_TEST_ABC_TEST_FROM_ABC_TEST_L_TITLE',
  'save' => true,
  'id_name' => 'abc_test_ab6dabc_test_ida',
  'link' => 'abc_test_abc_test',
  'table' => 'abc_test',
  'module' => 'abc_Test',
  'rname' => 'name',
);
?>
<?php
\$dictionary["abc_Test"]["fields"]["abc_test_ab6dabc_test_ida"] = array (
  'name' => 'abc_test_ab6dabc_test_ida',
  'type' => 'link',
  'relationship' => 'abc_test_abc_test',
  'source' => 'non-db',
  'reportable' => false,
  'side' => 'right',
  'vname' => 'LBL_ABC_TEST_ABC_TEST_FROM_ABC_TEST_R_TITLE',
);
?>

EOQ;
       fputs( $fh, $string);
       fclose( $fh );
    } 
    
    //Create the custom relationships
    if(!file_exists('custom/metadata')) {
       mkdir_recursive('custom/metadata');
    }    
    
    if( $fh = @fopen('custom/metadata/abc_test_abc_testMetaData.php', 'w+') )
    {
$string = <<<EOQ

<?php
\$dictionary["abc_test_abc_test"] = array (
  'true_relationship_type' => 'one-to-many',
  'relationships' => 
  array (
    'abc_test_abc_test' => 
    array (
      'lhs_module' => 'abc_Test',
      'lhs_table' => 'abc_test',
      'lhs_key' => 'id',
      'rhs_module' => 'abc_Test',
      'rhs_table' => 'abc_test',
      'rhs_key' => 'id',
      'relationship_type' => 'one-to-many',
      'join_table' => 'abc_test_abc_test_c',
      'join_key_lhs' => 'abc_test_ab6dabc_test_ida',
      'join_key_rhs' => 'abc_test_aed49bc_test_idb',
    ),
  ),
  'table' => 'abc_test_abc_test_c',
  'fields' => 
  array (
    0 => 
    array (
      'name' => 'id',
      'type' => 'varchar',
      'len' => 36,
    ),
    1 => 
    array (
      'name' => 'date_modified',
      'type' => 'datetime',
    ),
    2 => 
    array (
      'name' => 'deleted',
      'type' => 'bool',
      'len' => '1',
      'default' => '0',
      'required' => true,
    ),
    3 => 
    array (
      'name' => 'abc_test_ab6dabc_test_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'abc_test_aed49bc_test_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'abc_test_abc_testspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'abc_test_abc_test_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'abc_test_ab6dabc_test_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'abc_test_abc_test_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'abc_test_aed49bc_test_idb',
      ),
    ),
  ),
);
?>


EOQ;
       fputs( $fh, $string);
       fclose( $fh );
    }

    
if(!file_exists('custom/Extension/application/Ext/TableDictionary'))  {
   mkdir_recursive('custom/Extension/application/Ext/TableDictionary');
}
    

if( $fh = @fopen('custom/Extension/application/Ext/TableDictionary/abc_test_abc_test.php', 'w+') )
{
$string = <<<EOQ
<?php
include('custom/metadata/abc_test_abc_testMetaData.php');
?>
EOQ;
       fputs( $fh, $string);
       fclose( $fh );
}

    $this->rel_guid = create_guid();
    $sql = "INSERT INTO relationships (id, relationship_name, lhs_module, lhs_table, lhs_key, rhs_module, rhs_table, rhs_key, join_table, join_key_lhs, join_key_rhs, relationship_type, reverse, deleted) VALUES ('{$this->rel_guid}', 'abc_test_abc_test', 'abc_Test', 'abc_test', 'id', 'abc_Test', 'abc_test', 'id', 'abc_test_abc_test_c', 'abc_test_ab6abc_test_id', 'abc_test_aed49bc_test_id', 'one-to-many', 0, 0)";
    $GLOBALS['db']->query($sql); 

    $rel = new Relationship();
    $rel->delete_cache();
    $rel->build_relationship_cache();
    
    $this->moduleList = $GLOBALS['moduleList'];
}

function tearDown() {
    if(file_exists('custom/Extension/modules/abc_Test/Ext/Vardefs/test.php')) {
       unlink('custom/Extension/modules/abc_Test/Ext/Vardefs/test.php'); 
    }

    if(file_exists('custom/metadata/abc_test_abc_testMetaData.php')) {
       unlink('custom/metadata/abc_test_abc_testMetaData.php'); 
    }    
    
    if(file_exists('custom/Extension/application/Ext/TableDictionary/abc_test_abc_test.php')) {
       unlink('custom/Extension/application/Ext/TableDictionary/abc_test_abc_test.php'); 
    }

    if(file_exists('modules/abc_Test/metadata/studio.php')) {
       unlink('modules/abc_Test/metadata/studio.php'); 
    }    
    
	rmdir_recursive('custom/Extension/modules/abc_Test/Ext/Vardefs');
	rmdir_recursive('custom/Extension/modules/abc_Test/Ext/Layoutdefs');
	rmdir_recursive('custom/Extension/modules/abc_Test/Ext');
	rmdir_recursive('custom/Extension/modules/abc_Test');
	rmdir_recursive('modules/abc_Test/metadata');
	rmdir_recursive('modules/abc_Test');
	
	$sql = "DELETE FROM relationships WHERE id = '{$this->rel_guid}'";
	$GLOBALS['db']->query($sql);
	
	$GLOBALS['moduleList'] = $this->moduleList;
}


function test_upgrade_custom_relationships() {	
	$GLOBALS['moduleList'] = array();
	$GLOBALS['moduleList'][] = 'abc_Test';
	$GLOBALS['beanList']['abc_Test'] = 'abc_Test';
	/*
    include('modules/Administration/upgrade_custom_relationships.php');
	upgrade_custom_relationships();
	include('custom/Extension/modules/abc_Test/Ext/Vardefs/test.php');
	*/
}


}
?>