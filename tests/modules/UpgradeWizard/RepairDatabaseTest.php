<?php
require_once 'include/database/DBManagerFactory.php';

class RepairDatabaseTest extends Sugar_PHPUnit_Framework_TestCase
{

var $db;	
	
public function setUp()
{
	
	$this->markTestSkipped('Skip for now');	
    $this->db = DBManagerFactory::getInstance();	
    if($this->db->dbType == 'mysql')
    {
       $sql =  'ALTER TABLE meetings ALTER COLUMN status SET DEFAULT NULL';
       $sql2 = 'ALTER TABLE calls ALTER COLUMN status SET DEFAULT NULL';
       $sql3 = 'ALTER TABLE tasks ALTER COLUMN status SET DEFAULT NULL';

	   //Run the SQL
	   $this->db->query($sql);  
	   $this->db->query($sql2);  
	   $this->db->query($sql3);       
    }
    
         
}	

public function tearDown()
{
	if($this->db->dbType == 'mysql')
    {	
    	$sql = "ALTER TABLE meetings ALTER COLUMN status SET DEFAULT 'Planned'";
    	$sql2 = "ALTER TABLE calls ALTER COLUMN status SET DEFAULT 'Planned'";
    	$sql3 = "ALTER TABLE tasks ALTER COLUMN status SET DEFAULT 'Not Started'";
	    //Run the SQL
	    $this->db->query($sql);
	    $this->db->query($sql2); 
	    $this->db->query($sql3);      	
    }   	
}

public function testRepairTableParams()
{
	    if($this->db->dbType != 'mysql')
	    {
	       $this->markTestSkipped('Skip if not mysql db');
	       return;	
	    }
	
	    $bean = new Meeting();
	    $result = $this->getRepairTableParamsResult($bean);
	    $this->assertRegExp('/ALTER TABLE meetings\s+?modify column status varchar\(100\)  DEFAULT \'Planned\' NULL/i', $result);
	    
	    /*
	    $bean = new Call();
	    $result = $this->getRepairTableParamsResult($bean);
	    $this->assertTrue(!empty($result));
	    $this->assertRegExp('/ALTER TABLE calls\s+?modify column status varchar\(100\)  DEFAULT \'Planned\' NULL/i', $result);
	    */
	    
	    $bean = new Task();
	    $result = $this->getRepairTableParamsResult($bean);
	    $this->assertTrue(!empty($result));	    
	    $this->assertRegExp('/ALTER TABLE tasks\s+?modify column status varchar\(100\)  DEFAULT \'Not Started\' NULL/i', $result);
 
}

private function getRepairTableParamsResult($bean)
{
        $indices   = $bean->getIndices();
        $fielddefs = $bean->getFieldDefinitions();
        $tablename = $bean->getTableName();

		//Clean the indicies to prevent duplicate definitions
		$new_indices = array();
		foreach($indices as $ind_def)
		{
			$new_indices[$ind_def['name']] = $ind_def;
		}
		
        global $dictionary;
        $engine=null;
        if (isset($dictionary[$bean->getObjectName()]['engine']) && !empty($dictionary[$bean->getObjectName()]['engine']) )
        {
            $engine = $dictionary[$bean->getObjectName()]['engine'];	
        }
        
        
	    $result = $this->db->repairTableParams($bean->table_name, $fielddefs, $new_indices, false, $engine);
	    return $result;	
}
	
}